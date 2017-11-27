@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{url('pembelian')}}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
    <h4 class="text-center">Form Nota Pelunasan Pembelian</h4><br>
    <form class="form-horizontal" method="POST" action="{{url('pembelian/pelunasan')}}">
        {{ csrf_field() }}
        @if($notaBeli->status=='Lunas' && $notaBeli->cara_bayar == 'kredit')
        <div class="form-group">
            <label class="control-label col-sm-2" for="nomor_nota">Nota Beli:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nomor_nota" id="nomor_nota" value="{{$notaBeli->nomor}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tanggal">Tanggal:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{$notaBeli->tanggal}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="nominal_seharusnya">Nominal Seharusnya:</label>
            <div class="col-sm-10"> 
                <input id="nominal_seharusnya" class="form-control" name="nominal_seharusnya" type="number" value="{{$notaBeli->grand_total}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="diskon_pelunasan">Diskon Pelunasan:</label>
            <div class="col-sm-10"> 
                <input id="diskon_pelunasan" class="form-control" name="diskon_pelunasan" value="{{isset($notaBeli->notaPelunasanBeli->diskon_pelunasan)?$notaBeli->notaPelunasanBeli->diskon_pelunasan:0}}" type="number" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="cara_bayar">Cara Bayar : </label>
            <div class="col-sm-10"> 
                <input id="cara_bayar" class="form-control" name="cara_bayar" value="{{$notaBeli->notaPelunasanBeli->cara_bayar}}" type="text" readonly>
            </div>
        </div>
        @if($notaBeli->notaPelunasanBeli->cara_bayar == 'transfer')
            <div class="form-group">
                <label class="control-label col-sm-2" for="no_rek">No. Rekening Tujuan:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="no_rek" id="no_rek" value="{{$notaBeli->notaPelunasanBeli->no_rek}}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="atas_nama" id="atas_nama" value="{{$notaBeli->notaPelunasanBeli->pemilik_no_rek}}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="nominal_bayar">Nominal Bayar:</label>
                <div class="col-sm-10"> 
                    <input id="nominal_bayar" class="form-control" name="nominal_bayar" value="{{$notaBeli->notaPelunasanBeli->nominal_bayar}}" type="number" readonly>
                </div>
            </div>
        @endif

        @if($notaBeli->notaPelunasanBeli->cara_bayar == 'cek')
            <div class="form-group">
                <label class="control-label col-sm-2" for="no_cek">No. Cek:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="no_cek" id="no_cek" value="{{$notaBeli->notaPelunasanBeli->no_cek}}" disabled>
                </div>
            </div>
        @endif
        
        @endif
        @if($notaBeli->notaPelunasanBeli->cara_bayar == 'tunai')
            <div class="form-group">
                <label class="control-label col-sm-2" for="nomor_nota">Nota Jual:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nomor_nota" id="nomor_nota" value="{{$notaBeli->nomor}}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="tanggal">Tanggal:</label>
                <div class="col-sm-10"> 
                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{$notaBeli->tanggal}}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="nominal_seharusnya">Nominal Seharusnya:</label>
                <div class="col-sm-10"> 
                    <input id="nominal_seharusnya" class="form-control" name="nominal_seharusnya" type="number" value="{{$notaBeli->grand_total}}" readonly>
                </div>
            </div>             
        @endif

        
        
    </form>
</div><br>

<script>
    $(function(){
        
    });
    

</script>
@endsection