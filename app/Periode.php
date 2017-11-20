<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'id';
	protected $fillable=['id', 'tgl_awal', 'tgl_akhir'];
    public $timestamps=false;
	protected $guarded=['id'];

	public function akun()
	{
		return $this->belongsToMany('App\Akun', 'periode_has_akun', 'periode_id', 'akun_nomor')->withPivot('saldo_awal', 'saldo_akhir');
	}

	public function jurnal()
	{
		return $this->hasMany('App\Jurnal', 'periode_id');
	}
}
