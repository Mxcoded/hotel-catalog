<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    protected $services = [
        'reservation' => 'Reservation & Reception',
        'atmosphere' => 'General Atmosphere & Cleanliness',
        'housekeeping' => 'Housekeeping & Laundry Service',
        'food' => 'Food and Beverage (Service Menu & Presentation)',
        'recreations' => 'Recreation Pool and Spa Center',
        'internet' => 'Internet / TV Channels',
        'location' => 'Location and Surroundings',
        'meeting' => 'Meeting Conference & Events Halls',
    ];

    public function create()
    {
        return view('feedback', ['services' => $this->services]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateFeedback($request);

        Feedback::create($validatedData);

        return redirect()->route('feedback.create')->with('feedback_saved', true);
    }

    public function showReport()
    {
        $feedbacks = Feedback::all();
        $totalFeedback = $feedbacks->count();

        $positiveFeedback = $this->calculatePositiveFeedback($feedbacks);
        $negativeFeedback = $totalFeedback - $positiveFeedback;

        $categoryData = $this->getCategoryData();

        // Calculate the lowest index
        $lowestIndex = $this->calculateLowestIndex($feedbacks);

        return view('report', compact(
            'feedbacks',
            'totalFeedback',
            'positiveFeedback',
            'negativeFeedback',
            'categoryData',
            'lowestIndex'
        ));
    }

    private function validateFeedback(Request $request)
    {
        return $request->validate([
            'guest_full_name' => 'required|string|max:255',
            'guest_phone_number' => 'required|string|max:15',
            'guest_room_no' => 'required|string|max:10',
            'reservation' => 'required|integer|between:0,4',
            'atmosphere' => 'required|integer|between:0,4',
            'housekeeping' => 'required|integer|between:0,4',
            'food' => 'required|integer|between:0,4',
            'recreations' => 'required|integer|between:0,4',
            'internet' => 'required|integer|between:0,4',
            'location' => 'required|integer|between:0,4',
            'meeting' => 'required|integer|between:0,4',
            'recommend' => 'nullable|in:yes,no',
            'lead_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'lead_contact' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
        ]);
    }

    private function calculatePositiveFeedback($feedbacks)
    {
        return $feedbacks->filter(function ($feedback) {
            $averageRating = collect([
                $feedback->reservation,
                $feedback->atmosphere,
                $feedback->housekeeping,
                $feedback->food,
                $feedback->recreations,
                $feedback->internet,
                $feedback->location,
                $feedback->meeting,
            ])->average();

            return $averageRating >= 3;
        })->count();
    }

    private function getCategoryData()
    {
        $ratings = [0, 1, 2, 3, 4];
        $data = [];

        foreach ($this->services as $column => $serviceName) {
            $data[$serviceName] = Feedback::selectRaw("$column, COUNT(*) as count")
                ->groupBy($column)
                ->pluck('count', $column)
                ->toArray();

            // Ensure all ratings are included, even those with no feedback
            foreach ($ratings as $rating) {
                if (!isset($data[$serviceName][$rating])) {
                    $data[$serviceName][$rating] = 0;
                }
            }

            // Sort by rating for consistency
            ksort($data[$serviceName]);
        }

        return $data;
    }

    private function calculateLowestIndex($feedbacks)
    {
        $lowestIndex = null;

        if ($feedbacks->count() > 0) {
            $lowestIndex = $feedbacks->reduce(function ($lowest, $feedback, $index) {
                $currentScore = $feedback->reservation +
                    $feedback->atmosphere +
                    $feedback->housekeeping +
                    $feedback->food +
                    $feedback->recreations +
                    $feedback->internet +
                    $feedback->location +
                    $feedback->meeting;

                if (is_null($lowest) || $currentScore < $lowest['score']) {
                    return ['score' => $currentScore, 'index' => $index];
                }
                return $lowest;
            }, null)['index'];
        }

        return $lowestIndex;
    }
}
