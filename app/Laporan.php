<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;
	protected $guarded=['id'];

	public function akun()
	{
		$this->belongsToMany('App\Akun', 'laporan_has_akun', 'laporan_id', 'akun_nomor');
	}
}
