<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use Database\Factories\AttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $trainee_id
 * @property int $exam_id
 * @property Carbon $started_at
 * @property Carbon|null $submitted_at
 * @property AttemptStatus $status
 * @property string|null $score
 * @property int $current_question_index
 * @property Carbon $current_question_started_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'trainee_id',
    'exam_id',
    'started_at',
    'submitted_at',
    'status',
    'score',
    'current_question_index',
    'current_question_started_at',
])]
class Attempt extends Model
{
    /** @use HasFactory<AttemptFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'status' => AttemptStatus::class,
            'score' => 'decimal:2',
            'current_question_index' => 'integer',
            'current_question_started_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    /**
     * @return BelongsTo<Exam, $this>
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * @return HasMany<Answer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function isInProgress(): bool
    {
        return $this->status === AttemptStatus::InProgress;
    }

    public function isCompleted(): bool
    {
        return $this->status === AttemptStatus::Completed;
    }

    public function belongsToTrainee(User $user): bool
    {
        return $this->trainee_id === $user->id;
    }
}
