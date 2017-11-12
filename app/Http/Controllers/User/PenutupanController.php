<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Periode;
use Carbon\Carbon;
use DB;
use App\Akun;
use App\Jurnal;


class PenutupanController extends Controller
{
	public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	$periode = Periode::where('tgl_awal', '<=', Carbon::today()->toDateString())->where('tgl_akhir', '>=', Carbon::today()->toDateString())->first();
    	return view('user.penutupan.index', ['periode' => $periode]);
    }

    public function tutup()
    {
    	//update saldo akhir
    	$periode = Periode::where('tgl_awal', '<=', Carbon::today()->toDateString())->where('tgl_akhir', '>=', Carbon::today()->toDateString())->first();

    	//nomor bukti jurnal
    	$tahun = substr(date("Y",strtotime(Carbon::today()->toDateString())), 2);
        $nomor = $tahun.date("m",strtotime(Carbon::today()->toDateString()));

    	//jurnal tutup pendapatan
    	$jurnal = new Jurnal();
        $jurnal->tanggal = Carbon::today()->toDateString();
        $jurnal->no_bukti = 'T'.$nomor.'001';
        $jurnal->jenis = 4;
        $jurnal->keterangan = 'Penutupan Pendapatan';
        $jurnal->periode_id = $periode->id;
        $jurnal->save();

    	//pendapatan
    	$laba = 0;
    	$urutan = 1;
    	$akunNomorList =  Akun::where('nomor', 'like', '4%')->pluck('nomor');
    	$akunList = DB::table('vsaldoakhir')->whereIn('nomor', $akunNomorList)->get();
    	foreach ($akunList as $item) {
    		$akun = Akun::find($item->nomor);
    		if($akun->saldo_normal == -1){
    			$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $item->SaldoAkhir, 'nominal_kredit' => 0]);
    			$laba += $item->SaldoAkhir;
    		}
    		else{
    			$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $item->SaldoAkhir]);
    			$laba -= $item->SaldoAkhir;
    		}
            $urutan++;
    	}
    	//akun ikhtisar
    	$akun = Akun::find('000');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $laba]);
        $urutan++;

    	//jurnal tutup rugi
    	$jurnal = new Jurnal();
        $jurnal->tanggal = Carbon::today()->toDateString();
        $jurnal->no_bukti = 'T'.$nomor.'002';
        $jurnal->jenis = 4;
        $jurnal->keterangan = 'Penutupan Biaya';
        $jurnal->periode_id = $periode->id;
        $jurnal->save();

    	//rugi
    	$rugi = 0;
    	$urutan = 1;
    	$akunNomorList =  Akun::where('nomor', 'like', '5%')->pluck('nomor');
    	$akunList = DB::table('vsaldoakhir')->whereIn('nomor', $akunNomorList)->get();
    	foreach ($akunList as $item) {
    		$akun = Akun::find($item->nomor);
    		if($akun->saldo_normal == -1){
    			$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $item->SaldoAkhir, 'nominal_kredit' => 0]);
    			$rugi -= $item->SaldoAkhir;
    		}
    		else{
    			$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $item->SaldoAkhir]);
    			$rugi += $item->SaldoAkhir;
    		}
            $urutan++;
    	}
    	//akun ikhtisar
    	$akun = Akun::find('000');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $rugi, 'nominal_kredit' => 0]);
        $urutan++;
		
        //nilai laba rugi
    	$labaRugi = $laba-$rugi;

    	//jurnal penutupan modal dan laba rugi
    	$jurnal = new Jurnal();
        $jurnal->tanggal = Carbon::today()->toDateString();
        $jurnal->no_bukti = 'T'.$nomor.'003';
        $jurnal->jenis = 4;
        $jurnal->keterangan = 'Penutupan Modal dan Laba Rugi';
        $jurnal->periode_id = $periode->id;
        $jurnal->save();

        //akun ikhtisar
    	$urutan = 1;
        $akun = Akun::find('000');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $labaRugi, 'nominal_kredit' => 0]);
        $urutan++;

        //modal pemilik
        $akun = Akun::find('301');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $labaRugi]);
        $urutan++;

        //jurnal penutupan modal dan prive
        $jurnal = new Jurnal();
        $jurnal->tanggal = Carbon::today()->toDateString();
        $jurnal->no_bukti = 'T'.$nomor.'004';
        $jurnal->jenis = 4;
        $jurnal->keterangan = 'Penutupan Modal dan Prive';
        $jurnal->periode_id = $periode->id;
        $jurnal->save();

        //modal pemilik
    	$urutan = 1;
        $akun = Akun::find('301');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => DB::table('vsaldoakhir')->where('nomor', '302')->first()->SaldoAkhir, 'nominal_kredit' => 0]);
        $urutan++;

        //prive
        $akun = Akun::find('302');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => DB::table('vsaldoakhir')->where('nomor', '302')->first()->SaldoAkhir]);
        $urutan++;


    	//periode baru
    	$periode = new Periode();
    	$start = new Carbon('first day of next month');
		$end = new Carbon('last day of next month');
		$periode->id = date("Ym",strtotime($start->toDateString()));
    	$periode->tgl_awal = $start->toDateString();
    	$periode->tgl_akhir = $end->toDateString();
    	$periode->save();

    	//saldo awal periode
    	$akunList = DB::table('vsaldoakhir')->get();
    	foreach ($akunList as $item) {
    		$periode->akun()->attach($item->nomor, ['saldo_awal' => $item->SaldoAkhir, 'saldo_akhir' => 0]);
    	}
    }
}
