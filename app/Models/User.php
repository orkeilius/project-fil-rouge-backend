<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Auction;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    protected $appends = ['avatar_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'email'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function Auctions()
    {
        return $this->hasMany(Auction::class, 'author_id');
    }
    public function Offers()
    {
        return $this->hasMany(Offer::class, 'author_id');
    }
    public function getAvatarUrlAttribute()
    {
        $hash = hash( 'sha256', strtolower( trim( $this->email ) ) );
        return sprintf( 'https://www.gravatar.com/avatar/%s?d=identicon', $hash );
    }


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isAuctionAuthor(Auction $auction): bool
    {
        return $this->id === $auction->author_id;
    }
    public function isOfferAuthor(Offer $offer): bool
    {
        return $this->id === $offer->author_id;
    }
}
