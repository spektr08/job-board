<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property int $job_vacancy_id
 * @property string $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $notify_at
 *
 * @property User $user
 * @property JobVacancy $jobVacancy
 * @method Builder forUser(User $user)
 */
class JobVacancyResponse extends Model
{

    public const COST = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'notify_at'
    ];


    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'notify_at' => 'datetime',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }

    public function scopeForUser($query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

}
