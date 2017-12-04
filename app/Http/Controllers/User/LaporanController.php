<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Akun;

class LaporanController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	$akuns = Akun::all();
    	$laporanJurnals = DB::table('vlaporanjurnal')->get();
    	$arusKasList = DB::table('varuskas')->get();
    	$pendapatans = DB::table('vlabarugi')->where('nomor', 'like', '4%')->get();
    	$biayas = DB::table('vlabarugi')->where('nomor', 'like', '5%')->get();
    	$perubahaEkuitasList = DB::table('vperubahanEkuitas')->get();
    	$aktivas = DB::table('vneraca')->where('nomor', 'like', '1%')->get();
    	$pasivas = DB::table('vneraca')->where('nomor', 'like', '2%')->get();
    	return view('user.laporan.index',['akuns' => $akuns,'laporanJurnals' => $laporanJurnals, 'arusKasList' => $arusKasList,'perubahaEkuitasList' => $perubahaEkuitasList, 'pendapatans' => $pendapatans, 'biayas' => $biayas, 'aktivas' => $aktivas, 'pasivas' => $pasivas]);
    }
}
