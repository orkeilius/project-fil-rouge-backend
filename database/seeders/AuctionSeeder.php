<?php

namespace Database\Seeders;

use App\Models\Auction;
use Database\Factories\AuctionFactory;
use Database\Factories\ImageFactory;
use Database\Factories\UserFactory;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Auction::Factory()
            ->count(1000)
            ->create();
    }
}
