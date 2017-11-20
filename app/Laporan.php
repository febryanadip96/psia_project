<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    public $incrementing =false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;
	protected $guarded=['id'];

	public function akun()
	{
		return $this->belongsToMany('App\Akun', 'laporan_has_akun', 'laporan_id', 'akun_nomor');
	}
}
