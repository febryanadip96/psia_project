<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaPelunasanJual extends Model
{
    protected $table = 'nota_pelunasan_penjualan';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nominal_seharusnya', 'nota_jual_nomor', 'nominal_bayar', 'diskon_pelunasan'];
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
