<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Socialite\Two\User as SocialiteUser;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function __construct(array $attributes = [],
                                SocialiteUser $socialiteUser = null)
    {
        parent::__construct($attributes);
        $socialiteUser === null
            ?: $this->prepareUserBySocialite($socialiteUser);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'socialite_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function hashPassword(string $password): User
    {
        $this->password = Hash::make($password);
        return $this;
    }
    private function prepareUserBySocialite($social): void
    {
        $this->name = $social->name;
        $this->email = $social->email;
        $this->hashPassword($social->email . $social->id);
        $this->createToken();
    }
}
