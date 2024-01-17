<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Stylist extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Updated table name to 'users' to resolve conflict

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'password_hash',
        'session_token',
        'session_expiration', // Renamed from 'token_expiration' to 'session_expiration'
        'keep_session', // Renamed from 'keep_session_active' to 'keep_session'
    ]; // Combined fillable attributes from both versions

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_hash',
        'session_token',
    ]; // Combined hidden attributes from both versions

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Added from new code
        'created_at' => 'datetime', // Added from new code
        'updated_at' => 'datetime', // Added from new code
        'session_expiration' => 'datetime', // Renamed from 'token_expiration' to 'session_expiration'
        'keep_session' => 'boolean', // Renamed from 'keep_session_active' to 'keep_session'
    ]; // Combined casts from both versions

    // Relationships
    /**
     * Get the password reset requests for the stylist.
     */
    public function passwordResetRequests()
    {
        return $this->hasMany(PasswordResetRequest::class, 'user_id');
    }

    /**
     * Get the stylist requests for the stylist.
     */
    public function stylistRequests()
    {
        return $this->hasMany(StylistRequest::class, 'user_id');
    }

    /**
     * Get the password reset tokens for the stylist.
     */
    public function passwordResetTokens()
    {
        return $this->hasMany(PasswordResetToken::class, 'stylist_id');
    }

    // Methods
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

    // Other methods from both versions should be combined here without any removal
}
