<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';
    public $incrementing =false;
    protected $keyType = 'string';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'nama','saldo_normal'];
    public $timestamps=false;

    public function laporan()
    {
    	return $this->belongsToMany('App\Laporan', 'laporan_has_akun', 'akun_nomor', 'laporan_id');
    }

    public function jurnal()
    {
    	return $this->belongsToMany('App\Jurnal', 'akun_has_jurnal', 'akun_nomor', 'jurnal_id')->withPivot('nominal_debet', 'nominal_kredit', 'urutan');
    }

    public function periode()
    {
    	return $this->belongsToMany('App\Periode', 'periode_has_akun', 'akun_nomor', 'periode_id')->withPivot('saldo_awal', 'saldo_akhir');
    }
}
