<?php
namespace App\Domains\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'user_id',
    ];


    /**
     * @var bool
     */
    public $timestamps = false;
}
