<?php

namespace App\Models;

use Database\Factories\CbtQuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $cbt_subject_id
 * @property string $question_text
 * @property string $option_a
 * @property string $option_b
 * @property string $option_c
 * @property string $option_d
 * @property string $correct_answer
 * @property int $points
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['cbt_subject_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'points'])]
class CbtQuestion extends Model
{
    /** @use HasFactory<CbtQuestionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<CbtSubject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(CbtSubject::class, 'cbt_subject_id');
    }
}
