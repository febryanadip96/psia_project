<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaJual;
use App\Pelanggan;
use App\Bank;
use App\Barang;
use App\JasaPengiriman;
use App\Jurnal;
use App\Periode;
use App\Akun;

class PenjualanController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	$notaJuals = NotaJual::all();
    	return view('user.penjualan.index', ['notaJuals' => $notaJuals]);
    }

    public function create()
    {
    	$pelanggans = Pelanggan::all();
    	$banks = Bank::all();
    	$barangs = Barang::all();
        $jasa_pengirimans = JasaPengiriman::all();
    	return view('user.penjualan.create', ['pelanggans' => $pelanggans, 'banks' => $banks, 'barangs' => $barangs, 'jasa_pengirimans'=> $jasa_pengirimans]);

    }

    public function store(Request $request)
    {
    	$tahun = substr(date("Y",strtotime($request->tanggal)), 2);
        $nomor = $tahun.date("md",strtotime($request->tanggal));
        $jumlah = NotaJual::where('nomor','like', 'J'.$nomor.'%')->count()+1;
        if($jumlah<10){
            $nomor = $nomor."00".$jumlah;
        }
        else if($jumlah<100)
        {
            $nomor = $nomor."0".$jumlah;
        }
        else
        {
            $nomor = $nomor.$jumlah;
        }
    	$notaJual = new NotaJual();
    	$notaJual->nomor = 'J'.$nomor;
    	$notaJual->tanggal = $request->tanggal;
    	$notaJual->cara_bayar = $request->cara_bayar;
    	$notaJual->pelanggan_id = $request->pelanggan_id;
    	$notaJual->ppn = $request->ppn;
		$notaJual->grand_total=$request->grand_total;

		if($request->cara_bayar==1){//tunai
            $notaJual->status = 2;
        } else if($request->cara_bayar==2){//transfer
            $notaJual->status = 2;
            $notaJual->bank_id = $request->bank;
            $notaJual->no_rek = $request->no_rek;
            $notaJual->nama_pemilik_rek = $request->atas_nama;
        } else if($request->cara_bayar==3){//kredit
            $notaJual->status = 1;
            $notaJual->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaJual->diskon_pelunasan = $request->diskon_pelunasan;
            $notaJual->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else{//penjualan dengan cek
            $notaJual->status = 2;
            $notaJual->bank_id = $request->bank;
            $notaJual->no_cek = $request->no_cek;
        }
        //bila pengiriman = 1 tidak ada biaya dan dibayar oleh
        if($request->pengiriman==2){
            $notaJual->biaya_kirim = $request->biaya_kirim;
            $notaJual->dibayar_oleh = $request->pengiriman;
            $notaJual->jasa_pengiriman_id = $request->jasa_pengiriman_id;
        }

        $notaJual->save();

        $barangs = $request->barang;
        $hargas = $request->harga;
        $qtys = $request->qty;
        $subtotals = $request->subtotal;


        foreach ($barangs as $key => $barang) {
            $notaJual->barang()->attach($barang, ['qty' => $qtys[$key], 'harga' => $hargas[$key], 'subtotal' => $subtotals[$key]]);
            $barang = Barang::where('kode', $barang)->first();
            $barang->stok -= $qtys[$key];
            $barang->save();
        }

        //Jurnal
        $jurnal = new Jurnal();
        $jurnal->tanggal = $notaJual->tanggal;
        $jurnal->no_bukti = $notaJual->nomor;
        $jurnal->jenis = 1;
        $periodeAktif = Periode::where('tgl_awal', '<=', $notaJual->tanggal)->where('tgl_akhir', '>=', $notaJual->tanggal)->first();
        $jurnal->periode_id = $periodeAktif->id;
        //keterangan jurnal
        $keterangan = "";
        if($notaJual->cara_bayar == 1){//tunai
            $keterangan = "Transaksi Penjualan Tunai";
        }
        else if($notaJual->cara_bayar == 2){ //transfer
            $keterangan = "Transaksi Penjualan Transfer  ".$notaJual->bank->nama;
        }
        else if($notaJual->cara_bayar == 3){//kredit
            $keterangan = "Transaksi Penjualan Kredit";
            if($notaJual->diskon_pelunasan!=0){
                $keterangan .= " - dengan diskon pembayaran";
            }
        }
        else{ //cek
            $keterangan = "Transaksi Penjualan Cek";
        }

        if($notaJual->ppn!=0){
            $keterangan .= " - dengan PPN";
        }

        if($request->pengiriman == 2){
            if($notaJual->dibayar_oleh == 1){
                $keterangan .= " - FOB Shipping Point";
            }
            else{
                $keterangan .= " - FOB Destination Point";
            }
        }
        $jurnal->keterangan = $keterangan;
        $jurnal->save();

        //akun has jurnal
        $urutan = 1;
        if($notaJual->cara_bayar == 1){//tunai
            $akun = Akun::find('101');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaJual->grand_total, 'nominal_kredit' => 0]);
        }
        else if($notaJual->cara_bayar == 2){//transfer
            if($notaJual->bank_id == 1){//bank baca-baca
                $akun = Akun::find('102');
            }
            else{//bank suka sendiri
                $akun = Akun::find('103');
            }
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaJual->grand_total, 'nominal_kredit' => 0]);
        }
        else if($notaJual->cara_bayar == 3){//kredit
            $akun = Akun::find('104');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaJual->grand_total, 'nominal_kredit' => 0]);
        }
        else if($notaJual->cara_bayar == 4){//cek
            $akun = Akun::find('105');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaJual->grand_total, 'nominal_kredit' => 0 ]);
        }
        $urutan++;

        //penjualan akun
        $akun = Akun::find('401');
        $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' =>  $notaJual->grand_total*100/(100+$notaJual->ppn)]);
        $urutan++;

        //hutang ppn
        if($notaJual->ppn != 0)
        {
            $akun = Akun::find('204');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaJual->grand_total*$notaJual->ppn/(100+$notaJual->ppn)]);
            $urutan++;
        }

        //HPP
        $akun = Akun::find('501');
        $totalHpp = 0;
        foreach ($barangs as $key => $barang) {
            $barang = Barang::find($barang);
            $totalHpp += $barang->harga_beli_rata*$qtys[$key];
        }
        $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $totalHpp, 'nominal_kredit' => 0]);
        $urutan++;


        $alat_tulis = 0;
        $rumah_tangga = 0;
        foreach ($barangs as $key => $barang) {
            $barang = Barang::find($barang);
            if($barang->jenis_id == 1){
                $alat_tulis += $barang->harga_beli_rata*$qtys[$key];
            }
            else{
                $rumah_tangga += $barang->harga_beli_rata*$qtys[$key];
            }
        }
        if($alat_tulis != 0){
            $akun = Akun::find('106');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $alat_tulis]);
            $urutan++;
        }
        if($rumah_tangga != 0){
            $akun = Akun::find('107');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $rumah_tangga]);
            $urutan++;
        }

        if($request->dibayar_oleh == 1){//biaya kirim
            $akun = Akun::find('520');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaJual->biaya_kirim, 'nominal_kredit' => 0]);
            $urutan++;
            $akun = Akun::find('101');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaJual->biaya_kirim]);
        }

        
       return redirect()->action('User\PenjualanController@index');
    	
 
    }
}
