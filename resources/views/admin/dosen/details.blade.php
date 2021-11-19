@extends('template_backend.home')
@section('heading', 'Details Dosen')
@section('page')
  <li class="breadcrumb-item active"><a href="{{ route('dosen.index') }}">Dosen</a></li>
  <li class="breadcrumb-item active">Details Dosen</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <a href="{{ route("dosen.team", Crypt::encrypt($dosen->team_id)) }}" class="btn btn-default btn-sm"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a>
        </div>
        <div class="card-body">
            <div class="row no-gutters ml-2 mb-2 mr-2">
                <div class="col-md-4">
                    <img src="{{ asset($dosen->foto) }}" class="card-img img-details" alt="...">
                </div>
                <div class="col-md-1 mb-4"></div>
                <div class="col-md-7">
                    <h5 class="card-title card-text mb-2">Nama : {{ $dosen->nama_dosen }}</h5>
                    <h5 class="card-title card-text mb-2">NIP : {{ $dosen->nip }}</h5>
                    <h5 class="card-title card-text mb-2">No Id Card : {{ $dosen->id_card }}</h5>
                    <h5 class="card-title card-text mb-2">Team : {{ $dosen->team->nama_team }}</h5>
                    <h5 class="card-title card-text mb-2">Kode Jadwal : {{ $dosen->kode }}</h5>
                    @if ($dosen->jk == 'L')
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Laki-laki</h5>
                    @else
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Perempuan</h5>
                    @endif
                    <h5 class="card-title card-text mb-2">Tempat Lahir : {{ $dosen->tmp_lahir }}</h5>
                    <h5 class="card-title card-text mb-2">Tanggal Lahir : {{ date('l, d F Y', strtotime($dosen->tgl_lahir)) }}</h5>
                    <h5 class="card-title card-text mb-2">No. Telepon : {{ $dosen->telp }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataDosen").addClass("active");
    </script>
@endsection