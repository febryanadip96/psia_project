<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Akun;
use App\Helpers\Data;
use App\Periode;

class LaporanController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index($id = null)
    {
        $periodeList = Periode::all();
    	$akuns = Akun::all();
        if(!isset($id))
        {
            $periode = Periode::whereNull('tutup')->first();
        } else{
            $periode = Periode::find($id);
        }
    	$laporanJurnals = Data::LaporanJurnal($periode->id)->get();
    	$arusKasList = Data::ArusKas($periode->id)->get();
    	$pendapatans = Data::LabaRugi($periode->id)->where('nomor', 'like', '4%')->get();
    	$biayas = Data::LabaRugi($periode->id)->where('nomor', 'like', '5%')->get();
    	$perubahaEkuitasList = Data::PerubahanEkuitas($periode->id)->get();
    	$aktivas = Data::Neraca($periode->id)->where('nomor', 'like', '1%')->get();
    	$pasivas = Data::Neraca($periode->id)->where('nomor', 'like', '2%')->get();
    	return view('user.laporan.index',['periodeList' => $periodeList, 'periode' => $periode,'akuns' => $akuns,'laporanJurnals' => $laporanJurnals, 'arusKasList' => $arusKasList,'perubahaEkuitasList' => $perubahaEkuitasList, 'pendapatans' => $pendapatans, 'biayas' => $biayas, 'aktivas' => $aktivas, 'pasivas' => $pasivas]);
    }
}
