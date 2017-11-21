<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaJual;
use App\Pelanggan;
use App\Bank;
use App\Barang;

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
    	return view('user.penjualan.create', ['pelanggans' => $pelanggans, 'banks' => $banks, 'barangs' => $barangs]);

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
        } else{//pembelian dengan cek
            $notaJual->status = 2;
            $notaJual->no_cek = $request->no_cek;
        }
        //bila pengiriman = 1 tidak ada biaya dan dibayar oleh
        if($request->pengiriman==2){
            $notaJual->biaya_kirim = $request->biaya_kirim;
            $notaJual->dibayar_oleh = $request->pengiriman;
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
        
       return redirect()->action('User\PenjualanController@index');
    	
 
    }
}
