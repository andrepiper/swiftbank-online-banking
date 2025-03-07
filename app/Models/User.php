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
   * Role constants
   */
  const ROLE_USER = 'USER';
  const ROLE_ADMIN = 'ADMIN';
  const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'firstname',
    'middlename',
    'lastname',
    'email',
    'profile_picture',
    'password',
    'role',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'two_factor_confirmed_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * Get the user's full name.
   *
   * @return string
   */
  public function getFullNameAttribute()
  {
    $middleInitial = $this->middlename ? $this->middlename[0] . '. ' : '';
    return "{$this->firstname} {$middleInitial}{$this->lastname}";
  }

  /**
   * Get the user's profile picture URL or a default image.
   *
   * @return string
   */
  public function getProfilePictureUrlAttribute()
  {
    return $this->profile_picture
      ? asset('storage/' . $this->profile_picture)
      : asset('images/default-avatar.jpg');
  }

  /**
   * Check if the user is an admin
   *
   * @return bool
   */
  public function isAdmin()
  {
    return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_SUPER_ADMIN;
  }

  /**
   * Check if the user is a super admin
   *
   * @return bool
   */
  public function isSuperAdmin()
  {
    return $this->role === self::ROLE_SUPER_ADMIN;
  }

  /**
   * Check if the user has a specific role
   *
   * @param string $role
   * @return bool
   */
  public function hasRole($role)
  {
    return $this->role === $role;
  }
}
