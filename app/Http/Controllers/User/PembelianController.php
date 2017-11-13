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
    	return view('user.pembelian.index');
    }

    public function create(Request $request)
    {
    	$barangs = Barang::all();
        $suppliers = Supplier::all();
        $banks = Bank::all();
    	return view('user.pembelian.create', ['barangs' => $barangs, 'suppliers' => $suppliers, 'banks' => $banks]);
    }
}
