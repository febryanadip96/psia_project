<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaJual extends Model
{
    protected $table = 'nota_beli';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'cara_bayar', 'ppn', 'status_kirim', 'tgl_jatuh_tempo', 'diskon_langsung', 'diskon_pelunasan', 'tgl_batas_diskon', 'biaya_kirim', 'grand_total', 'dibayar_oleh', 'status', 'pelanggan_id' ];
    public $timestamps=false;

    public function barang()
    {
    	return $this->belongsToMany('App\Barang', 'barang_has_nota_jual', 'barang_kode', 'nota_jual_id')->withPivot('qty', 'harga', 'subtotal');
    }

    public function notaPelunasanJual()
    {
    	return $this->hasOne('App\NotaPelunasanJual', 'nota_jual_nomor');
    }
}
