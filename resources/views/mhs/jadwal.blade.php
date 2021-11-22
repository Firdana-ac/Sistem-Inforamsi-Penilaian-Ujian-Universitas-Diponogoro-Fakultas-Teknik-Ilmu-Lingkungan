@extends('template_backend.home')
@section('heading')
    Jadwal Kelas {{ $kelas->nama_kelas }}
@endsection
@section('page')
  <li class="breadcrumb-item active">Jadwal Kelas</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <table id="example2" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Team</th>
                    <th>Jam Pelajaran</th>
                    <th>Ruang Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwal as $data)
                <tr>
                    <td>{{ $data->hari->nama_hari }}</td>
                    <td>
                        <h5 class="card-title">{{ $data->team->nama_team }}</h5>
                        <p class="card-text"><small class="text-muted">{{ $data->dosen->nama_dosen }}</small></p>
                    </td>
                    <td>{{ $data->jam_mulai }} - {{ $data->jam_selesai }}</td>
                    <td>{{ $data->ruang->nama_ruang }}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.col -->
@endsection
@section('script')
    <script>
        $("#JadwalMhs").addClass("active");
    </script>
@endsection