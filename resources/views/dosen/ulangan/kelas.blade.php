@extends('template_backend.home')
@section('heading', 'Entry Nilai Ulangan')
@section('page')
  <li class="breadcrumb-item active">Entry Nilai Ulangan</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12" style="margin-top: -21px;">
                <table class="table">
                    <tr>
                        <td>Nama Dosen</td>
                        <td>:</td>
                        <td>{{ $dosen->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <td>Team</td>
                        <td>:</td>
                        <td>{{ $dosen->team->nama_team }}</td>
                    </tr>
                </table>
                <hr>
            </div>
            <div class="col-md-12">
              <table id="example2" class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Nama Kelas</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                  @foreach ($kelas as $val => $data)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $data[0]->rapot($val)->nama_kelas }}</td>
                      <td><a href="{{ route('ulangan.show', Crypt::encrypt($val)) }}" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-pen"></i> &nbsp; Entry Nilai</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@section('script')
  <script>
    $("#NilaiDosen").addClass("active");
    $("#liNilaiDosen").addClass("menu-open");
    $("#UlanganDosen").addClass("active");
  </script>
@endsection