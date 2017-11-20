<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaPelunasanBeli extends Model
{
    protected $table = 'nota_pelunasan_pembelian';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nominal_seharusnya', 'diskon_pelunasan', 'nominal_bayar', 'bank_id', 'nota_beli_nomor', 'no_rek', 'nama_pemilik_rek', 'no_cek'];
    public $timestamps=false;

    public function notaBeli()
    {
    	return $this->belongsTo('App\NotaBeli', 'nota_beli_nomor');
    }

    public function bank()
    {
    	return $this->belongsTo('App\Bank', 'bank_id');
    }
}
