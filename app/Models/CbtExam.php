<?php

namespace App\Models;

use Database\Factories\CbtExamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $cbt_subject_id
 * @property string $name
 * @property string $date
 * @property string $session
 * @property string $room
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['cbt_subject_id', 'name', 'date', 'session', 'room'])]
class CbtExam extends Model
{
    /** @use HasFactory<CbtExamFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<CbtSubject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(CbtSubject::class, 'cbt_subject_id');
    }
}
