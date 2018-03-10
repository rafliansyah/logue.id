<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{

    protected $table = 'user_profile';

    protected $fillable = [
        'photo', 'sex', 'placeOfBirth', 'dateOfBirth', 'phone', 'address', 'city', 'state', 'country',
    ];

}
