@extends('template_backend.home')
@section('heading', 'Details Mhs')
@section('page')
  <li class="breadcrumb-item active"><a href="{{ route('mhs.index') }}">Mhs</a></li>
  <li class="breadcrumb-item active">Details Mhs</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('mhs.kelas', Crypt::encrypt($mhs->kelas_id)) }}" class="btn btn-default btn-sm"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a>
        </div>
        <div class="card-body">
            <div class="row no-gutters ml-2 mb-2 mr-2">
                <div class="col-md-4">
                    <img src="{{ asset($mhs->foto) }}" class="card-img img-details" alt="...">
                </div>
                <div class="col-md-1 mb-4"></div>
                <div class="col-md-7">
                    <h5 class="card-title card-text mb-2">Nama : {{ $mhs->nama_mhs }}</h5>
                    <h5 class="card-title card-text mb-2">No. Induk : {{ $mhs->no_induk }}</h5>
                    <h5 class="card-title card-text mb-2">NIS : {{ $mhs->nis }}</h5>
                    <h5 class="card-title card-text mb-2">Kelas : {{ $mhs->kelas->nama_kelas }}</h5>
                    @if ($mhs->jk == 'L')
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Laki-laki</h5>
                    @else
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Perempuan</h5>
                    @endif
                    <h5 class="card-title card-text mb-2">Tempat Lahir : {{ $mhs->tmp_lahir }}</h5>
                    <h5 class="card-title card-text mb-2">Tanggal Lahir : {{ date('l, d F Y', strtotime($mhs->tgl_lahir)) }}</h5>
                    <h5 class="card-title card-text mb-2">No. Telepon : {{ $mhs->telp }}</h5>
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
        $("#DataMhs").addClass("active");
    </script>
@endsection