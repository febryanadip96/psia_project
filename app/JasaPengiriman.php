<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JasaPengiriman extends Model
{
    protected $table = 'jasa_pengiriman';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;

    public function notaBeli()
    {
    	return $this->hasMany('App\NotaBeli', 'jasa_pengiriman_id');
    }

    public function notaJual()
    {
    	return $this->hasMany('App\NotaJual', 'jasa_pengiriman_id');
    }
}
