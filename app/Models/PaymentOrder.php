<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $merchant_order_id
 * @property string|null $reference
 * @property string|null $payment_method
 * @property int $amount
 * @property string $status
 * @property string|null $result_code
 * @property string|null $notes
 */
#[Fillable(['user_id', 'merchant_order_id', 'reference', 'payment_method', 'amount', 'status', 'result_code', 'notes'])]
class PaymentOrder extends Model
{
    /**
     * Get the user who owns this payment order.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the payment was successful.
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success' && $this->result_code === '00';
    }
}
