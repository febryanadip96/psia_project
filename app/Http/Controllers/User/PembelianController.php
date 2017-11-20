<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Barang;
use App\Supplier;
use App\Bank;
use App\NotaBeli;
use Carbon\Carbon;

class PembelianController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {
        $notaBelis = NotaBeli::all();
    	return view('user.pembelian.index', ['notaBelis' => $notaBelis]);
    }

    public function create(Request $request)
    {
    	$barangs = Barang::all();
        $suppliers = Supplier::all();
        $banks = Bank::all();
    	return view('user.pembelian.create', ['barangs' => $barangs, 'suppliers' => $suppliers, 'banks' => $banks]);
    }

    public function store(Request $request)
    {
        $tahun = substr(date("Y",strtotime($request->tanggal)), 2);
        $nomor = $tahun.date("md",strtotime($request->tanggal));
        $jumlah = NotaBeli::where('nomor','like', 'B'.$nomor.'%')->count()+1;
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
        $notaBeli = new NotaBeli();
        $notaBeli->nomor = 'B'+$nomor;
        $notaBeli->tanggal = $request->tanggal;
        $notaBeli->supplier_id = $request->supplier_id;
        $notaBeli->cara_bayar = $request->cara_bayar;
        $notaBeli->grand_total = $request->grand_total;
        $notaBeli->diskon_langsung = $request->diskon_langsung;
        if($request->cara_bayar==1){
            $notaBeli->status = 2;
        } else if($request->cara_bayar==2){
            $notaBeli->status = 1;
            $notaBeli->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaBeli->diskon_pelunasan = $request->diskon_pelunasan;
            $notaBeli->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else if($request->cara_bayar==3){
            $notaBeli->status = 1;
            $notaBeli->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaBeli->diskon_pelunasan = $request->diskon_pelunasan;
            $notaBeli->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else{
            //pembelian dengan cek
        }
        //bila pengiriman = 1 tidak ada biaya dan dibayar oleh
        if($request->pengiriman==2){
            $notaBeli->biaya_kirim = $request->biaya_kirim;
            $notaBeli->dibayar_oleh = $request->pengiriman;
        }

        $notaBeli->save();

        $barangs = $request->barang;
        $hargas = $request->harga;
        $qtys = $request->qty;
        $subtotals = $request->subtotal;

        foreach ($barangs as $key => $barang) {
            $notaBeli->barang()->attach($barang, ['qty' => $qtys[$key], 'harga' => $hargas[$key], 'subtotal' => $subtotals[$key]]);
            $barang = $notaBeli->barang->where('kode', $barang)->first();
            $barang->harga_beli_rata = (($barang->harga_beli_rata*$barang->stok)+($subtotals[$key]))/($barang->stok+$qtys[$key]);
            $barang->stok += $qtys[$key];
            $barang->save();
        }

        return redirect()->action('User\PembelianController@index');

    }
}
