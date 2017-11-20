<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;

    public function notaPelunasanBeli()
    {
    	return $this->hasMany('App\NotaPelunasanBeli', 'bank_id');
    }

    public function notaPelunasanJual()
    {
    	return $this->hasMany('App\NotaPelunasanJual', 'bank_id');
    }
}
