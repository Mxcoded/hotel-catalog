<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class RoomController extends Controller
{
    // public function __construct()
    // {
    //     // Apply 'auth' middleware to all methods except 'index' and 'show'
    //     $this->middleware('auth')->except(['index', 'show']);
    // }
    // Display a listing of the rooms
    public function index()
{
    $rooms = Room::with('images')->paginate(6); // Paginate rooms, 5 per page
    return view('rooms.index', compact('rooms')); // Pass data to the view
}
   
    // Show the form for creating a new room
    public function create()
    {
        return view('rooms.create'); // Show the form view
    }

    // Store method
    public function store(Request $request)
    {
        // Log file upload information
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                Log::info("Uploaded file $key: " . $image->getClientOriginalName());
                Log::info("File type: " . $image->getMimeType());
                Log::info("File extension: " . $image->getClientOriginalExtension());
            }
        }
    
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'features' => 'nullable|string',
            'available' => 'nullable|boolean',
            'available_from' => 'date|nullable',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480', // 20MB max size for each image
        ]);
    
        // Check if features is provided; if not, set it to an empty JSON array
        $features = $request->input('features') ? json_encode(array_filter(explode(',', $request->input('features')))) : '[]';

        // Create the room instance
        $room = Room::create(array_merge(
            $request->except('images', 'features'),
            ['features' => $features]
        ));
    
        // Process each image and save the path to the database
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public'); // Store image in public disk
                $room->images()->create(['image_path' => $imagePath]); // Save path in database
            }
        }
    
        // Redirect with success message
        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }
    
    public function availableRooms()
    {
        // Fetch only rooms that are available
        $rooms = Room::all()->filter(function ($room) {
            return $room->isAvailable(); // Uses the isAvailable() method in the Room model
        });

        // Return a view with available rooms (adjust according to your view structure)
        return view('rooms.available', compact('rooms'));
    }
    // Display the specified room
    public function show(Room $room)
    {
        return view('rooms.show', compact('room')); // Show room details
    }

    // Show the form for editing the specified room
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room')); // Show edit form
    }
    public function destroy($id, Request $request)
    {
        $room = Room::findOrFail($id); // Find the room first
    
        if ($request->has('image_id')) {
            // We're deleting an image
            $image = RoomImage::findOrFail($request->image_id);
    
            // Log the image and room IDs for debugging
            Log::info("Image ID: " . $image->id);
            Log::info("Room ID: " . $room->id);
            Log::info("Image room ID: " . $image->room_id);
    
            // Ensure the image belongs to the room
            if ($image->room_id === $room->id) {
                Storage::disk('public')->delete($image->image_path); // Delete the image from storage
                $image->delete(); // Delete the image record from the database
                return redirect()->route('rooms.edit', $room->id)->with('success', 'Image deleted successfully.');
            }      
    
            return redirect()->back()->withErrors('Image not found or does not belong to this room.');
        } else {
            // We're deleting a room
            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
        }
    }
    
    // public function deleteImage(Room $room, RoomImage $image)
    // {
    //     // Ensure the image belongs to the room
    //     if ($image->room_id === $room->id) {
    //         Storage::disk('public')->delete($image->image_path); // Delete the image from storage
    //         $image->delete(); // Delete the image record from the database
    //         return redirect()->route('rooms.edit', $room->id)->with('success', 'Image deleted successfully.');
    //     }
    
    //     return redirect()->back()->withErrors('Image not found or does not belong to this room.');
    // }
    // Update the specified room in storage
    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'features' => 'nullable|string',
            'available' => 'required|boolean',
            'available_from' => 'nullable|date', 
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480', //20MB in Kb
        ]);
    
        // Update the room
        $room->name = $validatedData['name'];
        $room->description = $validatedData['description'];
        $room->price = $validatedData['price'];
        $room->features = json_encode(array_filter(explode(',', $validatedData['features']))); // Convert to JSON array
    
        // Check availability and set the available_from date
        if ($validatedData['available'] == 0) {
            $room->available = false;
            $room->available_from = $validatedData['available_from'] ?: null; // Set available_from
        } else {
            $room->available = true;
            $room->available_from = null; // Clear available_from if available
        }
    
        $room->save();
    
        // Handle image uploads if any
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public'); // Store the image
                $room->images()->create(['image_path' => $imagePath]); // Save the path in the database
            }
        }
    
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }
    
}
