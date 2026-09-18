<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\ExamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $lawyer_id
 * @property string $title
 * @property int $seconds_per_question
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['lawyer_id', 'title', 'seconds_per_question'])]
class Exam extends Model
{
    /** @use HasFactory<ExamFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'seconds_per_question' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    /**
     * @return HasMany<Question, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order')->orderBy('id');
    }

    /**
     * @return HasMany<Attempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function hasAttempts(): bool
    {
        return $this->attempts()->exists();
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->lawyer_id === $user->id && $user->role === UserRole::Lawyer;
    }
}
