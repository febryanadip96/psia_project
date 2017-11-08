<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaBayarJual extends Model
{
    protected $table = 'nota_bayar_jual';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nominal_seharusnya', 'diskon_pelunasan', 'nominal_bayar', 'no_cek', 'rekening_perusahaan_id', 'nota_jual_nomor' ];
    public $timestamps=false;
}
