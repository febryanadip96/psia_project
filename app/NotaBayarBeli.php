<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaBayarBeli extends Model
{
    protected $table = 'nota_bayar_beli';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nominal_seharusnya', 'diskon_pelunasan', 'nominal_bayar', 'bank_id', 'nota_beli_nomor', 'no_rek', 'nama_pemilik_rek', 'no_cek'];
    public $timestamps=false;
}
