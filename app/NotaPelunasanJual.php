<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaPelunasanJual extends Model
{
    protected $table = 'nota_pelunasan_penjualan';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nominal_seharusnya', 'diskon_pelunasan', 'nominal_bayar', 'no_cek', 'rekening_perusahaan_id', 'nota_jual_nomor', 'bank_id'];
    public $timestamps=false;

    public function notaJual()
    {
    	return $this->belongsTo('App\NotaJual', 'nota_jual_nomor');
    }

    public function bank()
    {
    	return $this->belongsTo('App\Bank', 'bank_id');
    }
}
