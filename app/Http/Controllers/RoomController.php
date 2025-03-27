<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('images')->paginate(6);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'features' => 'nullable|string',
            'available' => 'nullable|boolean',
            'available_from' => 'nullable|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        $features = $request->input('features')
            ? json_encode(array_filter(explode(',', $request->input('features'))))
            : '[]';

        $room = Room::create(array_merge(
            $request->except('images', 'features'),
            ['features' => $features]
        ));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->processAndStoreImage($image);
                $room->images()->create(['image_path' => $imagePath]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function availableRooms()
    {
        $rooms = Room::all()->filter(function ($room) {
            return $room->isAvailable();
        });

        return view('rooms.available', compact('rooms'));
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function destroy($id, Request $request)
    {
        $room = Room::findOrFail($id);

        if ($request->has('image_id')) {
            $image = RoomImage::findOrFail($request->image_id);

            if ($image->room_id === $room->id) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
                return redirect()->route('rooms.edit', $room->id)->with('success', 'Image deleted successfully.');
            }

            return redirect()->back()->withErrors('Image not found or does not belong to this room.');
        } else {
            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
        }
    }

    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'features' => 'nullable|string',
            'available' => 'required|boolean',
            'available_from' => 'nullable|date',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        $room->name = $validatedData['name'];
        $room->description = $validatedData['description'];
        $room->price = $validatedData['price'];
        $room->features = $validatedData['features']
            ? json_encode(array_filter(explode(',', $validatedData['features'])))
            : '[]';

        if ($validatedData['available'] == 0) {
            $room->available = false;
            $room->available_from = $validatedData['available_from'] ?: null;
        } else {
            $room->available = true;
            $room->available_from = null;
        }

        $room->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $this->processAndStoreImage($image);
                $room->images()->create(['image_path' => $imagePath]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    private function processAndStoreImage($image)
    {
        try {
            $filePath = 'images/' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the original image
            Storage::disk('public')->put($filePath, file_get_contents($image->getRealPath()));

            // Optimize the image using Spatie's Image Optimizer
            $optimizerChain = OptimizerChainFactory::create();
            $absolutePath = Storage::disk('public')->path($filePath);
            $optimizerChain->optimize($absolutePath);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage());
            throw $e;
        }
    }
}
