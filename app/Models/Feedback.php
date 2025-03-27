<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'guest_full_name',
        'guest_phone_number',
        'guest_room_no',
        'reservation',
        'atmosphere',
        'housekeeping',
        'food',
        'recreations',
        'internet',
        'location',
        'meeting',
        'recommend',
        'lead_name',
        'company_name',
        'lead_contact',
        'comments',
    ];
    /**
     * Calculate the average rating across all service categories.
     *
     * @return float
     */
    public function averageRating()
    {
        $categories = [
            $this->reservation,
            $this->atmosphere,
            $this->housekeeping,
            $this->food,
            $this->recreations,
            $this->internet,
            $this->location,
            $this->meeting,
        ];

        $totalRatings = count(array_filter($categories, fn($rating) => $rating !== null));
        return $totalRatings ? array_sum($categories) / $totalRatings : 0;
    }
}
