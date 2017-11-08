<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaJual extends Model
{
    protected $table = 'nota_beli';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'cara_bayar', 'ppn', 'status_kirim', 'tgl_jatuh_tempo', 'diskon_langsung', 'diskon_pelunasan', 'tgl_batas_diskon', 'biaya_kirim', 'dibayar_oleh', 'pelanggan_id' ];
    public $timestamps=false;
}
