<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;
    protected $fillable = ['id', 'url', 'description', 'created_at','auction_id'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function Auction()
    {
        return $this->belongsTo(Auction::class);
    }


}
