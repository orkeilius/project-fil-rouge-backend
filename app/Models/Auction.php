<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auction extends Model
{
    /** @use HasFactory<\Database\Factories\AuctionFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'starting_price','end_at', 'created_at'];
    protected $appends = ['highest_offer'];

        /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'end_at' => 'datetime',
            'starting_price' => 'float',
        ];
    }

    public function Author()
    {
        return $this->belongsTo(User::class);
    }
    public function Offers()
    {
        return $this->hasMany(Offer::class, 'auction_id');
    }

    public function getHighestOfferAttribute()
    {
        return $this->Offers()->max('price');
    }
}
