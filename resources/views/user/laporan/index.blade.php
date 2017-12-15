@extends('layouts.app')

@section('content')
<div class="container">

	<form>
		<div class="form-group">
			<label for="periode">Pilih Periode:</label>
			<select id="periode" class="form-control">
				@foreach($periodeList as $item)
					<option value="{{$item->id}}" {{$item->id==$periode->id? 'selected':''}}>{{$item->id}} ({{\Carbon\Carbon::parse($item->tgl_awal)->formatLocalized('%d %B %Y')}} - {{\Carbon\Carbon::parse($item->tgl_akhir)->formatLocalized('%d %B %Y')}})</option>
				@endforeach
			</select>
		</div>
	</form>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#laporanJurnal">Laporan Jurnal</a></li>
        <li><a data-toggle="tab" href="#arusKas">Arus Kas</a></li>
        <li><a data-toggle="tab" href="#laporanLabaRugi">Laporan Laba/Rugi</a></li>
        <li><a data-toggle="tab" href="#perubahanEkuitas">Perubahan Ekuitas</a></li>
        <li><a data-toggle="tab" href="#neraca">Neraca</a></li>
    </ul>

    <div class="tab-content">
        <div id="laporanJurnal" class="tab-pane fade in active">
            <h3>Laporan Jurnal</h3>
		    <table class="table table-striped">
		        <thead>
		            <tr>
		                <th>Tanggal</th>
		                <th>Keterangan Transaksi</th>
		                <th>Akun</th>
		                <th>Debet</th>
		                <th>Kredit</th>
		                <th>Nomor Bukti</th>
		            </tr>
		        </thead>
		        <tbody>
		        @foreach ($laporanJurnals as $laporanJurnal)
					<tr>
						<td>{{\Carbon\Carbon::parse($laporanJurnal->tanggal)->formatLocalized('%A, %d %B %Y')}}</td>
						<td>{{$laporanJurnal->keterangan}}</td>
						<td>{{$laporanJurnal->NamaAkun}}</td>
						<td>Rp. {{$laporanJurnal->Debet?number_format($laporanJurnal->Debet,0,',','.'):0}}</td>
						<td>Rp. {{$laporanJurnal->Kredit?number_format($laporanJurnal->Kredit,0,',','.'):0}}</td>
						<td>{{$laporanJurnal->NomorBukti?$laporanJurnal->NomorBukti:'-'}}</td>
					</tr>
				@endforeach
		        </tbody>
		    </table>
        </div>
        <div id="arusKas" class="tab-pane fade">
            <h3>Arus Kas</h3>
	        @foreach ($arusKasList as $arusKas)
				<table class="table .table-striped">
					<tr>
						<th>{{$arusKas->nomor}}</th>
						<th colspan="3">{{$arusKas->NamaAkun}}</th>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td>Keterangan</td>
						<td>Debet</td>
						<td>Kredit</td>
						<td>Nomor Bukti</td>
					</tr>
					<tr>
						<td>-</td>
						<td>Saldo Awal</td>
						<td>Rp. {{$akuns->where('nomor', $arusKas->nomor)->first()->periode->where('id', $periode->id)->first()->pivot->saldo_awal?number_format($akuns->where('nomor', $arusKas->nomor)->first()->periode->where('id', $periode->id)->first()->pivot->saldo_awal,0,',','.'):0}}</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				@foreach($bukuBesar->where('akun_nomor', $arusKas->nomor) as $item)
					<tr>
						<td>{{\Carbon\Carbon::parse($item->tanggal)->formatLocalized('%A, %d %B %Y')}}</td>
						<td>{{$item->keterangan}}</td>
						<td>Rp. {{$arusKas->SaldoAkhir?number_format($item->nominal_debet,0,',','.'):0}}</td>
						<td>Rp. {{$arusKas->SaldoAkhir?number_format($item->nominal_kredit,0,',','.'):0}}</td>
						<td>{{$item->no_bukti}}</td>
					</tr>
				@endforeach
					<tr>
						<th colspan="2">Total</th>
						<th>Rp. {{$arusKas->SaldoAkhir?number_format($arusKas->SaldoAkhir,0,',','.'):0}}</th>
						<th></th>
					</tr>
			</table>
			@endforeach
        </div>
        <div id="laporanLabaRugi" class="tab-pane fade">
            <h3>Laporan Laba Rugi</h3>
            <h4><b>Pendapatan</b></h4>
            @php
            	$totalPendapatan = 0;
            @endphp
	        @foreach ($pendapatans as $pendapatan)
				<p class="col-xs-4">{{$pendapatan->NamaAkun}}</p>
				<p class="col-xs-8">Rp. {{$pendapatan->SaldoAkhir?number_format($pendapatan->SaldoAkhir,0,',','.'):0}}</p>
				@php
					$totalPendapatan += ($pendapatan->SaldoAkhir*$akuns->where('nomor',$pendapatan->nomor)->first()->saldo_normal*-1);
				@endphp
			@endforeach
			<p class="col-xs-4">TOTAL PENDAPATAN</p>
			<p class="col-xs-8">Rp. {{$totalPendapatan?number_format($totalPendapatan,0,',','.'):0}}</p>
		    <h4><b>Biaya</b></h4>
		    @php
            	$totalBiaya = 0;
            @endphp
	        @foreach ($biayas as $biaya)
				<p class="col-xs-4">{{$biaya->NamaAkun}}</p>
				<p class="col-xs-8">Rp. {{$biaya->SaldoAkhir?number_format($biaya->SaldoAkhir,0,',','.'):0}}</p>
				@php
					$totalBiaya += ($biaya->SaldoAkhir*$akuns->where('nomor',$biaya->nomor)->first()->saldo_normal);
				@endphp
			@endforeach
			<p class="col-xs-4">TOTAL BIAYA</p>
			<p class="col-xs-8">Rp. {{$totalBiaya?number_format($totalBiaya,0,',','.'):0}}</p>
			<br>
			<br>
			<h4 class="col-xs-4"><b>LABA/RUGI</b></h4>
			@php
				$labaRugi = $totalPendapatan-$totalBiaya;
			@endphp
			<h4 class="col-xs-8"><b>Rp. {{($labaRugi)?number_format(($labaRugi),0,',','.'):0}}</b></h4>
        </div>
        <div id="perubahanEkuitas" class="tab-pane fade">
            <h3>Perubahan Ekuitas</h3>
            @php
            	$modalPemilik = 0;
            @endphp
	        @foreach ($perubahaEkuitasList as $perubahanEkuitas)
				<p class="col-xs-4">{{$perubahanEkuitas->NamaAkun}}</p>
				<p class="col-xs-8">Rp. {{$perubahanEkuitas->SaldoAkhir?number_format($perubahanEkuitas->SaldoAkhir,0,',','.'):0}}</p>
				@php
					$modalPemilik += ($perubahanEkuitas->SaldoAkhir*$akuns->where('nomor',$perubahanEkuitas->nomor)->first()->saldo_normal*-1);
				@endphp
			@endforeach
			<p class="col-xs-4">LABA/RUGI</p>
			<p class="col-xs-8">Rp. {{($labaRugi)?number_format(($labaRugi),0,',','.'):0}}</p>
			<p class="col-xs-4"><b>Modal Pemilik</b></p>
			@php
				$modalPemilik += $labaRugi;
			@endphp
			<p class="col-xs-8"><b>Rp. {{($modalPemilik)?number_format(($modalPemilik),0,',','.'):0}}</b></p>
        </div>
        <div id="neraca" class="tab-pane fade">
            <h3>Laporan Neraca</h3>
        	<h4 class="col-xs-2">Aktiva</h4>
        	@php
        		$totalAktiva = 0;
        	@endphp
        	<div class="col-xs-10">
        		@foreach ($aktivas as $aktiva)
					<p class="col-xs-4">{{$aktiva->NamaAkun}}</p>
					<p class="col-xs-8">Rp. {{$aktiva->SaldoAkhir?number_format($aktiva->SaldoAkhir,0,',','.'):0}}</p>
					@php
						$totalAktiva += ($aktiva->SaldoAkhir*$akuns->where('nomor', $aktiva->nomor)->first()->saldo_normal);
					@endphp
				@endforeach
				<p class="col-xs-4"><b>TOTAL AKTIVA</b></p>
				<p class="col-xs-8"><b>Rp. {{$totalAktiva?number_format($totalAktiva,0,',','.'):0}}</b></p>
        	</div>
        	<h4 class="col-xs-2">Pasiva</h4>
        	@php
        		$totalPasiva = 0;
        	@endphp
        	<div class="col-xs-10">
        		@foreach ($pasivas as $pasiva)
					<p class="col-xs-4">{{$pasiva->NamaAkun}}</p>
					<p class="col-xs-8">Rp. {{$pasiva->SaldoAkhir?number_format($pasiva->SaldoAkhir,0,',','.'):0}}</p>
					@php
						$totalPasiva += ($pasiva->SaldoAkhir*$akuns->where('nomor', $pasiva->nomor)->first()->saldo_normal*-1);
					@endphp
				@endforeach
				<p class="col-xs-4">{{$akuns->find('301')->nama}}</p>
				<p class="col-xs-8">Rp. {{$modalPemilik?number_format($modalPemilik,0,',','.'):0}}</p>
				@php
					$totalPasiva += $modalPemilik;
				@endphp
				<p class="col-xs-4"><b>TOTAL PASIVA</b></p>
				<p class="col-xs-8"><b>Rp. {{$totalPasiva?number_format($totalPasiva,0,',','.'):0}}</b></p>
        	</div>
        </div>
    </div>
</div>
<script>
	$(function(){
		$('#periode').change(function(){
			var periode = $(this).val();
			var url = '{{url('laporan')}}';
			window.location.replace(url+"/"+periode);
		});
	});
</script>
@endsection
