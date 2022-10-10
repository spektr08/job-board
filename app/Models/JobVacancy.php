<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $published_at
 *
 * @property User $user
 * @method Builder forUser(User $user)
 */
class JobVacancy extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISH = 'publish';
    public const COST = 2;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'published_at'
    ];

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISH => 'Publish',
        ];
    }

    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function publish(Carbon $date): void
    {
        if ($this->status == self::STATUS_PUBLISH) {
            throw new \DomainException('JobVacancy is already published.');
        }
        $this->update([
            'published_at' => $date,
            'status' => self::STATUS_PUBLISH,
        ]);
    }

    public function canBeChanged(): bool
    {
        return $this->isDraft();
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(JobVacancyResponse::class, 'job_vacancy_id', 'id');
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function canResponse(int $user_id): bool
    {
        return !$this->responses()->where('user_id',$user_id)->exists() && $user_id != $this->user_id ;
    }

    public function canBeLikedByUser(int $userId): bool
    {
        return !$this->favorites()->where('user_id',$userId)->exists();
    }

    public function scopeForUser($query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopePublished($query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
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
