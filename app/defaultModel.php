<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class defaultModel extends Model
{
    protected $guarded = [];
    const CREATED_AT = 'adddate';
	const UPDATED_AT = 'upddate';
	protected $primaryKey = 'idno';
}
