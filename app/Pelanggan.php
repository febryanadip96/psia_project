<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
	protected $fillable=['id', 'nama', 'jenis', 'no_telp', 'alamat'];
    public $timestamps=false;
	protected $guarded=['id'];

	public function notaJual()
	{
		$this->hasMany('App\NotaJual', 'pelanggan_id');
	}
}
