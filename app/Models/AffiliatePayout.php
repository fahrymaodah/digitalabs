<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliatePayout extends Model
{
    use HasUuid;

    protected $fillable = [
        'affiliate_id',
        'amount',
        'status',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'notes',
        'admin_notes',
        'processed_at',
        'completed_at',
        'uuid',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ==================== METHODS ====================

    /**
     * Start processing payout
     */
    public function process(): void
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);
    }

    /**
     * Complete payout
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Mark all pending commissions as paid
        $this->affiliate->commissions()
            ->where('status', 'approved')
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
    }

    /**
     * Fail payout
     */
    public function fail(string $reason = null): void
    {
        $this->update([
            'status' => 'failed',
            'admin_notes' => $reason,
        ]);
    }
}
