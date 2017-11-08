<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;
}
