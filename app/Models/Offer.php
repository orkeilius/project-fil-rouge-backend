<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    /** @use HasFactory<\Database\Factories\OffertFactory> */
    use HasFactory;

    protected $fillable = [
        'price',
        'created_at',
        'author_id',
        'auction_id'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'price' => 'float',
        ];
    }
    public function Auction()
    {
        return $this->belongsTo(Auction::class);
    }
    public function Author()
    {
        return $this->belongsTo(User::class);
    }

}
