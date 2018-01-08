<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $hidden = [];
    protected $table = 'users';

    const CREATED_AT = 'adddate';
    const UPDATED_AT = 'upddate';

}
