<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Periode;
use Carbon\Carbon;
use DB;
use App\Akun;
use App\Jurnal;
use App\Helpers\Data;

class PenutupanController extends Controller
{
	public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	$periodeList = Periode::all();
    	return view('user.penutupan.index', ['periodeList' => $periodeList]);
    }

    public function tutup($id)
    {
    	//periode yang dipilih
    	$periode = Periode::find($id);

    	//nomor bukti jurnal
        $nomor = $periode->id;

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
    	$akunList = Data::SaldoAkhir($periode->id)->whereIn('nomor', $akunNomorList)->get();
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
    	$akunList = Data::SaldoAkhir($periode->id)->whereIn('nomor', $akunNomorList)->get();
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
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => Data::SaldoAkhir($periode->id)->where('nomor', '302')->first()->SaldoAkhir, 'nominal_kredit' => 0]);
        $urutan++;

        //prive
        $akun = Akun::find('302');
    	$akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => Data::SaldoAkhir($periode->id)->where('nomor', '302')->first()->SaldoAkhir]);
        $urutan++;

        //isi saldo akhir
        $akunList = Data::SaldoAkhir($periode->id)->get();
        foreach ($akunList as $item) {
    		$periode->akun()->updateExistingPivot($item->nomor, ['saldo_akhir' => $item->SaldoAkhir]);
    	}

    	//tutup periode sekarang
    	$periode->tutup = Carbon::now();
    	$periode->save();

    	//periode baru
    	$periode = new Periode();
    	$start = new Carbon('first day of next month');
		$end = new Carbon('last day of next month');
		$periode->id = date("Ym",strtotime($start->toDateString()));
    	$periode->tgl_awal = $start->toDateString();
    	$periode->tgl_akhir = $end->toDateString();
    	$periode->save();

        //saldo awal periode
    	foreach ($akunList as $item) {
    		$periode->akun()->attach($item->nomor, ['saldo_awal' => $item->SaldoAkhir, 'saldo_akhir' => 0]);
    	}

    	return back();
    }

    public function lihat($id)
    {
    	
    }
}
