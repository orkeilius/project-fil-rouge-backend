<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function PHPUnit\Framework\isNull;

class Auction extends Model
{
    /** @use HasFactory<\Database\Factories\AuctionFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'starting_price','end_at', 'created_at','author_id'];
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

    public function getHighestOfferAttribute():float
    {
        $max = $this->Offers()->max('price');
        return isNull($max) ? 0 : $max;
    }
    public function isEnded():bool
    {
        return $this->end_at < now();
    }
}
