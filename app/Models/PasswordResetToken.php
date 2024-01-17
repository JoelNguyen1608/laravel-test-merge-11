<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * Ensure these attributes align with your table's columns
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
        'expiration',
        'used',
        'stylist_id',
        // Add any new attributes you need here
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // Update casts if new attributes have been added or types have changed
    protected $casts = [
        'created_at' => 'datetime',
        'expiration' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the stylist that owns the password reset token.
     *
     * @return BelongsTo
     */
    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class, 'stylist_id');
        // Ensure the foreign key 'stylist_id' is correctly defined in your migrations
    }

    /**
     * Validate the password reset token.
     *
     * @param string $token
     * @param int $stylist_id
     * @return bool
     */
    public function validateToken(string $token, int $stylist_id): bool
    {
        return $this->where('token', $token)
                    ->where('stylist_id', $stylist_id)
                    ->where('expiration', '>', now())
                    ->where('used', false)
                    ->exists();
    }

    /**
     * Mark the token as used.
     *
     * @param string $token
     * @return bool
     */
    public function markAsUsed(string $token): bool
    {
        return $this->where('token', $token)->update(['used' => true]);
    }
}
