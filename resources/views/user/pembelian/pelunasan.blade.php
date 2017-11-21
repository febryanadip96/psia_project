@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{url('pembelian')}}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
    <h4 class="text-center">Form Nota Pelunasan Pembelian</h4><br>
    <form class="form-horizontal" method="POST" action="{{url('pembelian/pelunasan')}}">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-sm-2" for="nomor_nota">Nota Beli:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nomor_nota" id="nomor_nota" value="{{$notaBeli->nomor}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tanggal">Tanggal:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
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
                <input id="diskon_pelunasan" class="form-control" name="diskon_pelunasan" value="0" type="number" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="nominal_bayar">Nominal Bayar:</label>
            <div class="col-sm-10"> 
                <input id="nominal_bayar" class="form-control" name="nominal_bayar" value="0" type="number">
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
    </form>
</div><br>

<script>
    $(function(){
        $('#tanggal').on('change', function(){
            var jatuh_tempo = new Date('{{$notaBeli->tgl_batas_diskon}}');
            var hari_ini = new Date($(this).val());
            if(hari_ini<=jatuh_tempo){
                $('#diskon_pelunasan').val({{$notaBeli->grand_total*$notaBeli->diskon_pelunasan/100}});
            }
            else{
                $('#diskon_pelunasan').val(0);
            }
        });
    });
    

</script>
@endsection