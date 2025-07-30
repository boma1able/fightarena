<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    protected static function booted(): void
    {
        static::created(function (User $user) {

            $endurance = 3;

            $user->character()->create([
                'bace_health' => 0,
                'current_health' => $endurance * 6,
                'strength' => 3,
                'agility' => 3,
                'intuition' => 3,
                'endurance' => $endurance,
                'stat_points' => 3,
                'level' => 0,
                'experience' => 0,
                'gold' => 0,
            ]);

            // Завантажуємо свіжо створений персонаж
             $character = $user->character;

            // Виклик додавання предметів у shop
            $character->giveShopItems();
        });

        static::deleting(function (User $user) {
            if ($user->character) {
                $user->character->delete();
            }
        });
    }

    public function character()
    {
        return $this->hasOne(Character::class);
    }
}
