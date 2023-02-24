<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    protected $table = 'receivers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone1', 'phone2', 'city', 'country', 'address', 'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function trackings()
    {
        return $this->hasMany(Tracking::class);
    }
}
