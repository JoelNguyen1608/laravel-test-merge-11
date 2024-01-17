<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'session_token', // Added from new code
        'session_expiration', // Added from new code
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'session_token', // Added from new code
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'session_expiration' => 'datetime', // Added from new code
        // Removed 'password' => 'hashed', from new code as it is not a valid cast type in Laravel.
    ];

    /**
     * Find a user by session token.
     *
     * @param string $sessionToken
     * @return User|null
     */
    public static function findBySessionToken($sessionToken)
    {
        return self::where('session_token', $sessionToken)
                    ->where('session_expiration', '>', now())
                    ->first();
    }

    /**
     * Log a failed login attempt.
     *
     * @param string $email
     * @return void
     */
    public function logFailedLoginAttempt($email)
    {
        \Log::warning("Failed login attempt for email: {$email} at " . now());
    }
}
