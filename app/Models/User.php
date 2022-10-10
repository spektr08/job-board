<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'coins',
        'coins_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobVacancies(): hasMany
    {
        return $this->hasMany(JobVacancy::class, 'user_id', 'id');
    }

    public function favorites():  MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function canPublish(): bool
    {
        $jobVacancyTodayCount = $this->jobVacancies()->whereDate('published_at', Carbon::today())->count();
        return $jobVacancyTodayCount < 2;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function scopeDaily($query, User $user): Builder
    {
        return $query->where('coins_date', Carbon::today());
    }

    public function canBeLikedByUser(int $userId): bool
    {
        return !$this->favorites()->where('user_id',$userId)->exists();
    }

    public function getFavoriteId(int $userId): int
    {
        $favorite = $this->favorites()->where('user_id',$userId)->first();
        if(!$favorite){
            throw new \DomainException("Can't find favorite");
        }
        return $favorite->id;
    }
}
