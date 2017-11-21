<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaJual extends Model
{
    protected $table = 'nota_jual';
    public $incrementing =false;
    protected $keyType = 'string';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'cara_bayar', 'ppn', 'tgl_jatuh_tempo', 'diskon_langsung', 'diskon_pelunasan', 'tgl_batas_diskon', 'biaya_kirim', 'grand_total', 'dibayar_oleh', 'status', 'pelanggan_id', 'no_cek', 'rekening_perusahaan_id', 'bank_id' ];
    public $timestamps=false;

    public function barang()
    {
    	return $this->belongsToMany('App\Barang', 'barang_has_nota_jual', 'nota_jual_nomor', 'barang_kode')->withPivot('qty', 'harga', 'subtotal');
    }

    public function notaPelunasanJual()
    {
    	return $this->hasOne('App\NotaPelunasanJual', 'nota_jual_nomor');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Pelanggan', 'pelanggan_id');
    }
}
