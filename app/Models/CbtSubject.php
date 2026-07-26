<?php

namespace App\Models;

use Database\Factories\CbtSubjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $topic
 * @property int $items_count
 * @property string $difficulty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['code', 'name', 'topic', 'items_count', 'difficulty'])]
class CbtSubject extends Model
{
    /** @use HasFactory<CbtSubjectFactory> */
    use HasFactory;

    /**
     * @return HasMany<CbtQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(CbtQuestion::class);
    }
}
