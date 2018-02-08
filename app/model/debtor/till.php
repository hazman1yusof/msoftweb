<?php

namespace App\model\debtor;

use Illuminate\Database\Eloquent\Model;
use App\defaultModel;

class till extends defaultModel
{
    protected $table = 'debtor.till';

    public function scopeGet_till_use($query, $user){

    	
    	
        return $query->where('tillcode', $user);
    }
}
