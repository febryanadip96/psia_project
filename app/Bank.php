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

    public function notaBeli()
    {
        return $this->hasMany('App\NotaBeli', 'bank_id');
    }

    public function notaJual()
    {
        return $this->hasMany('App\NotaJual', 'bank_id');
    }
}
