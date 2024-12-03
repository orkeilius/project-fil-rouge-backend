<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('auctions')->insert([
            'name' => Str::random(10),
            'description' => Str::random(100),
            'starting_price' => Float::random(10,10000),
            'end_at' => now()->addDays(Int::random(1, 30)),
        ]);
    }
}
