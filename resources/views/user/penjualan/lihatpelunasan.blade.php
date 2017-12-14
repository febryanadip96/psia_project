@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{url('penjualan')}}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
    <h4 class="text-center">Form Nota Pelunasan Pembelian</h4><br>
    <form class="form-horizontal" method="POST" action="{{url('penjualan/pelunasan')}}">
        {{ csrf_field() }}
		<div class="form-group">
            <label class="control-label col-sm-2" for="nomor_nota">Nota Jual:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nomor_nota" id="nomor_nota" value="{{$notaJual->nomor}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tanggal">Tanggal:</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{$notaJual->tanggal}}" readonly>
            </div>
        </div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="nominal_seharusnya">Nominal Seharusnya:</label>
			<div class="col-sm-10">
				<input id="nominal_seharusnya" class="form-control" name="nominal_seharusnya" type="number" value="{{$notaJual->grand_total}}" readonly>
			</div>
		</div>
        @if($notaJual->cara_bayar == 'kredit')
	        <div class="form-group">
	            <label class="control-label col-sm-2" for="diskon_pelunasan">Diskon Pelunasan:</label>
	            <div class="col-sm-10">
					<div class="input-group">
	                	<input id="diskon_pelunasan" class="form-control" name="diskon_pelunasan" value="0" type="number" readonly>
					</div>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="control-label col-sm-2" for="cara_bayar">Cara Bayar : </label>
	            <div class="col-sm-10">
	                <input id="cara_bayar" class="form-control" name="cara_bayar" value="{{$notaJual->notaPelunasanJual->cara_bayar}}" type="text" readonly>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="control-label col-sm-2" for="nominal_bayar">Nominal Bayar:</label>
	            <div class="col-sm-10">
	                <input id="nominal_bayar" class="form-control" name="nominal_bayar" value="{{$notaJual->notaPelunasanJual->nominal_bayar}}" type="number" readonly>
	            </div>
	        </div>
			@if($notaJual->notaPelunasanJual->cara_bayar == 'transfer')
	            <div class="form-group">
	                <label class="control-label col-sm-2" for="no_rek">No. Rekening Tujuan:</label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" name="no_rek" id="no_rek" value="{{$notaJual->notaPelunasanJual->no_rek}}" readonly>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" name="atas_nama" id="atas_nama" value="{{$notaJual->notaPelunasanJual->pemilik_no_rek}}" readonly>
	                </div>
	            </div>
	        @elseif($notaJual->notaPelunasanJual->cara_bayar == 'cek')
	            <div class="form-group">
	                <label class="control-label col-sm-2" for="no_cek">No. Cek:</label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" name="no_cek" id="no_cek" value="{{$notaJual->notaPelunasanJual->no_cek}}" readonly>
	                </div>
	            </div>
	        @endif
		@elseif($notaJual->cara_bayar == 'transfer')
			<div class="form-group">
				<label class="control-label col-sm-2" for="no_rek">No. Rek:</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="no_rek" id="no_rek" value="{{$notaJual->no_rek}}" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="nama_bank">Nama Bank:</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="nama_bank" id="nama_bank" value="{{$notaJual->bank->nama}}" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="atas_nama" id="atas_nama" value="{{$notaJual->nama_pemilik_rek}}" readonly>
				</div>
			</div>
        @elseif($notaJual->cara_bayar == 'cek')
			<div class="form-group">
				<label class="control-label col-sm-2" for="no_cek">No. Cek:</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="no_cek" id="no_cek" value="{{$notaJual->no_cek}}" readonly>
				</div>
			</div>
        @endif
    </form>
</div><br>
@endsection
