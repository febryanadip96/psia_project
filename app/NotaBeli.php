<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaBeli extends Model
{
    protected $table = 'nota_beli';
    public $incrementing =false;
    protected $keyType = 'string';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'cara_bayar', 'tgl_jatuh_tempo', 'diskon_langsung', 'diskon_pelunasan', 'tgl_batas_diskon', 'biaya_kirim', 'grand_total', 'dibayar_oleh', 'status', 'supplier_id', 'bank_id', 'no_rek', 'nama_pemilik_rek', 'no_cek' ];
    public $timestamps=false;

    public function notaPelunasanBeli()
    {
    	return $this->hasOne('App\notaPelunasanBeli', 'nota_beli_nomor');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier', 'supplier_id');
    }

    public function barang()
    {
    	return $this->belongsToMany('App\Barang', 'barang_has_nota_beli', 'nota_beli_nomor', 'barang_kode')->withPivot('qty','harga','subtotal');
    }
}
