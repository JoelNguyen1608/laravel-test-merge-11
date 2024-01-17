<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
     * Get the password reset tokens for the stylist.
     */
    public function passwordResetTokens()
    {
        return $this->hasMany(PasswordResetToken::class, 'stylist_id');
    }

    /**
     * Clear the session token and set the token expiration to the current time.
     */
    public function clearSessionToken()
    {
        $this->session_token = null;
        $this->token_expiration = \Carbon\Carbon::now();
        $this->save();
    }
}
