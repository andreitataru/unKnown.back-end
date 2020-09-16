<?php

namespace App;

use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'fbUser', 'datebirth', 'gender', 'bio', 'picture', 
        'work', 'place', 'school', 'discovery'
    ];

    public function toArray() 
    {
        return [
            'name' =>$this->name,
            'email' => $this->email,
            'verified' => $this->verified,
            'fbUser' => $this->fbUser,
            'datebirth' => $this->datebirth,
            'gender' => $this->gender,
            'bio' => $this->bio,
            'picture' => $this->picture,
            'work' => $this->work,
            'place' => $this->place,
            'school' => $this->school,
            'discovery' => $this->discovery
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_token', 'AccCreationStep'
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
