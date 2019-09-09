<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Undocumented variable
     * @var string
     */
    protected $table = 'users';
    protected $gravatarLink = 'http://www.gravatar.com/avatar/';

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return $this->gravatarLink . $hash . '?s=' . $size;
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
    public function feed()
    {
        $user_ids = $this->followings->pluck('id')->toArray();
        array_push($user_ids, $this->id);
        return Status::whereIn('user_id', $user_ids)
                              ->with('user')
                              ->orderBy('created_at', 'desc');
    }
    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }
    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }
    /**
     * 關注
     * @param [type] $user_ids
     * @return void
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    /**
     * 取消關注
     * @param [type] $user_ids
     * @return void
     */
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    /**
     * 檢查是否關注
     * @param [type] $user_id
     * @return boolean
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
