<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Create api_token for logged in user
     * @return string
     */
    public function generateToken()
    {
        do{
            $random_string = str_random(60);
        }while(User::where('api_token', $random_string)->count());
            
        $this->api_token = $random_string;
        $this->save();

        return $this->api_token;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role','url'];

    /**
     * Get role of the user.
     *
     * @return bool
     */
    public function getRoleAttribute()
    {
        return $this->roles()->first()->display_name;
    }

    /**
     * Get role of the user.
     *
     * @return bool
     */
    public function getUrlAttribute()
    {
        if($this->hasRole('superadmin'))
            return '/admin';
        elseif($this->hasRole('clerk'))
            return '/warehouse';
        elseif($this->hasRole('manager'))
            return '/manager';
        elseif($this->hasRole('laboratorian'))
            return '/laboratorian';
        elseif($this->hasRole('requestor'))
            return '/requestor';
        elseif($this->hasRole('director'))
            return '/director';
        else
            return 'none';
    }
}
