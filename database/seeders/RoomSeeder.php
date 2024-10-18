<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Room::create([
        'name' => 'Deluxe Suite',
        'description' => 'A luxurious suite with a beautiful view.',
        'price' => 200.00,
        'features' => json_encode(['Free Wi-Fi', 'Air Conditioning', 'Ocean View']),
        'available' => true,
    ]);

    Room::create([
        'name' => 'Standard Room',
        'description' => 'A comfortable standard room for a relaxing stay.',
        'price' => 100.00,
        'features' => json_encode(['Free Wi-Fi', 'Queen Bed']),
        'available' => true,
    ]);
}
}
