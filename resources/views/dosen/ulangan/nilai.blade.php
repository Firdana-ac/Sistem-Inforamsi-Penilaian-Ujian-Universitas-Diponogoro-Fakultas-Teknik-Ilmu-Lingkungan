@extends('template_backend.home')
@section('heading', 'Entry Nilai Seminar')
@section('page')
  <li class="breadcrumb-item active">Entry Nilai Seminar</li>
@endsection
@section('content')
<div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Entry Nilai Ulangan</h3>
      </div>
      <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
                <table class="table" style="margin-top: -10px;">
                    <tr>
                        <td>Nama Kelas</td>
                        <td>:</td>
                        <td>{{ $kelas->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <td>Ketua Sidang</td>
                        <td>:</td>
                        <td>{{ $kelas->dosen->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Mhs</td>
                        <td>:</td>
                        <td>{{ $mhs->count() }}</td>
                    </tr>
                    <tr>
                        <td>Team</td>
                        <td>:</td>
                        <td>{{ $dosen->team->nama_team }}</td>
                    </tr>
                    <tr>
                        <td>Dosen </td>
                        <td>:</td>
                        <td>{{ $dosen->nama_dosen }}</td>
                    </tr>
                    @php
                        $bulan = date('m');
                        $tahun = date('Y');
                    @endphp
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td>
                            @if ($bulan > 6)
                                {{ 'Semester Ganjil' }}
                            @else
                                {{ 'Semester Genap' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tahun Pelajaran</td>
                        <td>:</td>
                        <td>
                            @if ($bulan > 6)
                                {{ $tahun }}/{{ $tahun+1 }}
                            @else
                                {{ $tahun-1 }}/{{ $tahun }}
                            @endif
                        </td>
                    </tr>
                </table>
                <hr>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="ctr">No.</th>
                            <th>Nama Mhs</th>
                            <th class="ctr">Nilai 1</th>
                            <th class="ctr">Nilai 2</th>
                            <th class="ctr">Nilai 3</th>
                            <th class="ctr">Nilai 4</th>
                            <th class="ctr">Nilai 5</th>
                            <th class="ctr">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="" method="post">
                            @csrf
                            <input type="hidden" name="dosen_id" value="{{$dosen->id}}">
                            <input type="hidden" name="kelas_id" value="{{$kelas->id}}">
                            @foreach ($mhs as $data)
                                <input type="hidden" name="mhs_id" value="{{$data->id}}">
                                <tr>
                                    <td class="ctr">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $data->nama_mhs }}
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['id'])
                                            <input type="hidden" name="ulangan_id" class="ulangan_id_{{$data->id}}" value="{{ $data->ulangan($data->id)->id }}">
                                        @else
                                            <input type="hidden" name="ulangan_id" class="ulangan_id_{{$data->id}}" value="">
                                        @endif
                                    </td>
                                    <td class="ctr">
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['ulha_1'])
                                            <div class="text-center">{{ $data->ulangan($data->id)['ulha_1'] }}</div>
                                            <input type="hidden" name="ulha_1" class="ulha_1_{{$data->id}}" value="{{ $data->ulangan($data->id)['ulha_1'] }}">
                                        @else
                                            <input type="text" name="ulha_1" maxlength="2" onkeypress="return inputAngka(event)" style="margin: auto;" class="form-control text-center ulha_1_{{$data->id}}" autocomplete="off">
                                        @endif
                                    </td>
                                    <td class="ctr">
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['ulha_2'])
                                            <div class="text-center">{{ $data->ulangan($data->id)['ulha_2'] }}</div>
                                            <input type="hidden" name="ulha_2" class="ulha_2_{{$data->id}}" value="{{ $data->ulangan($data->id)['ulha_2'] }}">
                                        @else
                                            <input type="text" name="ulha_2" maxlength="2" onkeypress="return inputAngka(event)" style="margin: auto;" class="form-control text-center ulha_2_{{$data->id}}" autocomplete="off">
                                        @endif
                                    </td>
                                    <td class="ctr">
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['uts'])
                                            <div class="text-center">{{ $data->ulangan($data->id)['uts'] }}</div>
                                            <input type="hidden" name="uts" class="uts_{{$data->id}}" value="{{ $data->ulangan($data->id)['uts'] }}">
                                        @else
                                            <input type="text" name="uts" maxlength="2" onkeypress="return inputAngka(event)" style="margin: auto;" class="form-control text-center uts_{{$data->id}}" autocomplete="off">
                                        @endif
                                    </td>
                                    <td class="ctr">
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['ulha_3'])
                                            <div class="text-center">{{ $data->ulangan($data->id)['ulha_3'] }}</div>
                                            <input type="hidden" name="ulha_3" class="ulha_3_{{$data->id}}" value="{{ $data->ulangan($data->id)['ulha_3'] }}">
                                        @else
                                            <input type="text" name="ulha_3" maxlength="2" onkeypress="return inputAngka(event)" style="margin: auto;" class="form-control text-center ulha_3_{{$data->id}}" autocomplete="off">
                                        @endif
                                    </td>
                                    <td class="ctr">
                                        @if ($data->ulangan($data->id) && $data->ulangan($data->id)['uas'])
                                            <div class="text-center">{{ $data->ulangan($data->id)['uas'] }}</div>
                                            <input type="hidden" name="uas" class="uas_{{$data->id}}" value="{{ $data->ulangan($data->id)['uas'] }}">
                                        @else
                                            <input type="text" name="uas" maxlength="2" onkeypress="return inputAngka(event)" style="margin: auto;" class="form-control text-center uas_{{$data->id}}" autocomplete="off">
                                        @endif
                                    </td>
                                    <td class="ctr sub_{{$data->id}}">
                                        @if ($data->nilai($data->id))
                                            <i class="fas fa-check" style="font-weight:bold;"></i>
                                        @else
                                            <button type="button" id="submit-{{$data->id}}" class="btn btn-default btn_click" data-id="{{$data->id}}"><i class="nav-icon fas fa-save"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                    </tbody>
                </table>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection
@section('script')
    <script>
        $(".btn_click").click(function(){
            var id = $(this).attr('data-id');
            var ulha_1 = $(".ulha_1_"+id).val();
            var ulha_2 = $(".ulha_2_"+id).val();
            var uts = $(".uts_"+id).val();
            var ulha_3 = $(".ulha_3_"+id).val();
            var uas = $(".uas_"+id).val();
            var ulangan_id = $(".ulangan_id_"+id).val();
            var dosen_id = $("input[name=dosen_id]").val();
            var kelas_id = $("input[name=kelas_id]").val();
            
            $.ajax({
                url: "{{ route('ulangan.store') }}",
                type: "POST",
                dataType: 'json',
                data 	: {
                    _token: '{{ csrf_token() }}',
                    id : ulangan_id,
                    mhs_id : id,
                    kelas_id : kelas_id,
                    dosen_id : dosen_id,
                    ulha_1 : ulha_1,
                    ulha_2 : ulha_2,
                    uts : uts,
                    ulha_3 : ulha_3,
                    uas : uas,
                },
                success: function(data){
                    toastr.success("Nilai ulangan mhs berhasil ditambahkan!");
                    location.reload();
                },
                error: function (data) {
                    toastr.warning("Errors 404!");
                }
            });
        });
        
        $("#NilaiDosen").addClass("active");
        $("#liNilaiDosen").addClass("menu-open");
        $("#UlanganDosen").addClass("active");
    </script>
@endsection