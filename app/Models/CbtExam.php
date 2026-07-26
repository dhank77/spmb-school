<?php

namespace App\Models;

use Database\Factories\CbtExamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $date
 * @property string $session
 * @property string $room
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'date', 'session', 'room'])]
class CbtExam extends Model
{
    /** @use HasFactory<CbtExamFactory> */
    use HasFactory;
}
