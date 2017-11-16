<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Barang;
use App\Supplier;
use App\Bank;
use App\NotaBeli;

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
        $notaBeli = new NotaBeli();
        $notaPelunasanBeli = new NotaPelunasanBeli();
        $notaBeli->nomor = $request->nomor;
        $notaBeli->tanggal = $request->tanggal;
        $notaBeli->supplier_id = $request->supplier_id;
        $notaBeli->cara_bayar = $request->cara_bayar;
        $notaBeli->grand_total = $request->grand_total;
        if($request->jenis_pembayaran==1){

        } else if($request->jenis_pembayaran==2){
            $notaBeli->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaBeli->diskon_pelunasan = $request->diskon_pelunasan;
            $notaBeli->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else if($request->jenis_pembayaran==3){
            $notaPelunasanBeli->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaBeli->diskon_pelunasan = $request->diskon_pelunasan;
            $notaBeli->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else{

        }
        //bila pengiriman = 1 tidak ada biaya dan dibayar oleh
        if($request->pengiriman=2){
            $notaBeli->biaya_kirim = $request->biaya_kirim;
            $notaBeli->dibayar_oleh = $request->pengiriman;
        }
        $notaBeli->save();

        $barangs = $request->barang;
        $hargas = $request->harga;
        $qtys = $request->qty;

        
        $notaPelunasanBeli->nota_beli_nomor = $request->nomor;
        $notaPelunasanBeli->nominal_seharusnya = $request->grand_total;
        $notaPelunasanBeli->nota_beli_nomor = $notaBeli->nomor;

        $notaPelunasanBeli->save();

    }
}
