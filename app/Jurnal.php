<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';
    protected $primaryKey = 'id';
	protected $fillable=['tanggal', 'keterangan', 'no_bukti', 'jenis', 'periode_id'];
    public $timestamps=false;
	protected $guarded=['id'];

	public function akun()
	{
		$this->belongsToMany('App\Akun', 'akun_has_jurnal', 'jurnal_id','akun_nomor')->withPivot('nominal_debet', 'nominal_kredit', 'urutan');
	}

	public function periode()
	{
		$this->belongsTo('App\Periode', 'periode_id');
	}
}
