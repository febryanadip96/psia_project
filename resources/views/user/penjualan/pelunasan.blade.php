@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{url('penjualan')}}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
    <h4 class="text-center">Form Nota Pelunasan Penjualan</h4><br>
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
                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="nominal_seharusnya">Nominal Seharusnya:</label>
            <div class="col-sm-10"> 
                <input id="nominal_seharusnya" class="form-control" name="nominal_seharusnya" type="number" value="{{$notaJual->grand_total}}" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="diskon_pelunasan">Diskon Pelunasan:</label>
            <div class="col-sm-10"> 
                <div class="input-group">
                    <input id="diskon_pelunasan" class="form-control" name="diskon_pelunasan" value="{{$notaJual->diskon_pelunasan}}" type="number" readonly>
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="cara_bayar">Cara Bayar:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="1" id="tunai" checked>Tunai</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="2" id="transfer">Transfer</label>
                </div>
                <!-- <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="3" id="kredit">Kredit</label>
                </div> -->
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="4" id="cek">Cek</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="bank">Nama Bank:</label>
            <div class="col-sm-10">
                <select class="form-control" id="bank" name="bank" disabled>
                    @foreach($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="no_rek">No. Rekening Tujuan:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="no_rek" id="no_rek" placeholder="Nomor Rekening" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="atas_nama" id="atas_nama" placeholder="Atas Nama" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="no_cek">No. Cek:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="no_cek" id="no_cek" placeholder="Nomor Cek" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="nominal_bayar">Nominal Bayar:</label>
            <div class="col-sm-10"> 
                <input id="nominal_bayar" class="form-control" name="nominal_bayar" value="{{$notaJual->grand_total}}" type="number" readonly>
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
    </form>
</div><br>

<script>
     $(function(){
        $('#tanggal').on('change', function(){
            var jatuh_tempo = new Date('{{$notaJual->tgl_batas_diskon}}');
            var hari_ini = new Date($(this).val());
            if(hari_ini<=jatuh_tempo){
                $('#nominal_bayar').val({{$notaJual->grand_total*(100-$notaJual->diskon_pelunasan)/100}});
            }
            else{
                $('#nominal_bayar').val({{$notaJual->grand_total}});
            }
        });
        $('#tunai').change(function(){
            $('#bank').attr('disabled',true);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
        $('#kredit').change(function(){
            $('#bank').attr('disabled',true);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', false);
            $('#diskon_pelunasan').attr('disabled', false);
            $('#tgl_batas_diskon').attr('disabled', false);
        });
        $('#transfer').change(function(){
            $('#bank').attr('disabled',false);
            $('#no_rek').attr('disabled',false);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',false);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
        $('#cek').change(function(){
            $('#bank').attr('disabled',false);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',false);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
    });
    

</script>
@endsection