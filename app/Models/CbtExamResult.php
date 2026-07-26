<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $cbt_exam_id
 * @property int $cbt_subject_id
 * @property int $score
 * @property int $total_points
 * @property int $correct_count
 * @property int $total_questions
 * @property string $status
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'cbt_exam_id', 'cbt_subject_id', 'score', 'total_points', 'correct_count', 'total_questions', 'status', 'completed_at'])]
class CbtExamResult extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<CbtSubject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(CbtSubject::class, 'cbt_subject_id');
    }

    /**
     * @return BelongsTo<CbtExam, $this>
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }
}
