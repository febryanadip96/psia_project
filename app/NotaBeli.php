<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaBeli extends Model
{
    protected $table = 'nota_beli';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'cara_bayar', 'status_kirim', 'tgl_jatuh_tempo', 'diskon_langsung', 'diskon_pelunasan', 'tgl_batas_diskon', 'biaya_kirim', 'dibayar_oleh', 'supplier_id' ];
    public $timestamps=false;

    public function notaPelunasanBeli()
    {
    	$this->hasOne('App\notaPelunasanBeli', 'nota_beli_nomor');
    }

    public function supplier()
    {
    	$this->belongsTo('App\Supplier', 'supplier_id');
    }

    public function barang()
    {
    	$this->belongsToMany('App\Barang', 'barang_has_nota_beli', 'nota_beli_nomor', 'barang_kode');
    }
}
