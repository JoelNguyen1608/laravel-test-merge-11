<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Stylist extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stylists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password_hash',
        'session_token',
        'token_expiration',
        'keep_session_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'session_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'token_expiration' => 'datetime',
        'keep_session_active' => 'boolean',
    ];

    /**
     * Update the stylist's password.
     *
     * @param string $new_password
     * @return void
     */
    public function updatePassword($new_password)
    {
        $this->password_hash = Hash::make($new_password);
        $this->save();
    }

    /**
     * Get the password reset tokens for the stylist.
     */
    public function passwordResetTokens()
    {
        return $this->hasMany(PasswordResetToken::class, 'stylist_id');
    }

    /**
     * Generate and save a password reset token for the stylist.
     *
     * @return PasswordResetToken
     */
    public function generatePasswordResetToken()
    {
        // Assuming PasswordResetToken model has a method to create a token
        return $this->passwordResetTokens()->create(['token' => Str::random(60), 'expiration' => now()->addHour(), 'used' => false]);
    }

    /**
     * Clear the session token and set the token expiration to the current time.
     */
    public function clearSessionToken()
    {
        $this->session_token = null;
        $this->token_expiration = Carbon::now();
        $this->save();
    }
}
