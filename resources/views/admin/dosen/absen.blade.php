@extends('template_backend.home')
@section('heading', 'Absensi Dosen')
@section('page')
    <li class="breadcrumb-item active">Absensi Dosen</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Cek Absensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dosen as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->nama_dosen }}</td>
                        <td>
                            <a href="{{ route('dosen.kehadiran', Crypt::encrypt($data->id)) }}" class="btn btn-info btn-sm"><i class="nav-icon fas fa-search-plus"></i> &nbsp; Ditails</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $("#AbsensiDosen").addClass("active");
    </script>
@endsection