<?php
namespace App\Domains\Profile\Models;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];


    /**
     * @var bool
     */
    public $timestamps = true;


    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
