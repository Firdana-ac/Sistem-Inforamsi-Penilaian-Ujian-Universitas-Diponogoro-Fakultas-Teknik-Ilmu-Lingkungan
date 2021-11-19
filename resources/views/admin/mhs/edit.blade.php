@extends('template_backend.home')
@section('heading', 'Edit Mhs')
@section('page')
  <li class="breadcrumb-item active"><a href="{{ route('mhs.index') }}">Mhs</a></li>
  <li class="breadcrumb-item active">Edit Mhs</li>
@endsection
@section('content')
<div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Data Mhs</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route('mhs.update', $mhs->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_induk">Nomor Induk</label>
                    <input type="text" id="no_induk" name="no_induk" value="{{ $mhs->no_induk }}" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="nama_mhs">Nama Mhs</label>
                    <input type="text" id="nama_mhs" name="nama_mhs" value="{{ $mhs->nama_mhs }}" class="form-control @error('nama_mhs') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="jk">Jenis Kelamin</label>
                    <select id="jk" name="jk" class="select2bs4 form-control @error('jk') is-invalid @enderror">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L"
                            @if ($mhs->jk == 'L')
                                selected
                            @endif
                        >Laki-Laki</option>
                        <option value="P"
                            @if ($mhs->jk == 'P')
                                selected
                            @endif
                        >Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tmp_lahir">Tempat Lahir</label>
                    <input type="text" id="tmp_lahir" name="tmp_lahir" value="{{ $mhs->tmp_lahir }}" class="form-control @error('tmp_lahir') is-invalid @enderror">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nis">NIS</label>
                    <input type="text" id="nis" name="nis" onkeypress="return inputAngka(event)" value="{{ $mhs->nis }}" class="form-control @error('nis') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="kelas_id">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="select2bs4 form-control @error('kelas_id') is-invalid @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $data)
                            <option value="{{ $data->id }}"
                                @if ($mhs->kelas_id == $data->id)
                                    selected
                                @endif
                            >{{ $data->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="telp">Nomor Telpon/HP</label>
                    <input type="text" id="telp" name="telp" value="{{ $mhs->telp }}" onkeypress="return inputAngka(event)" class="form-control @error('telp') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ $mhs->tgl_lahir }}" class="form-control @error('tgl_lahir') is-invalid @enderror">
                </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <a href="#" name="kembali" class="btn btn-default" id="back"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a> &nbsp;
          <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Update</button>
        </div>
      </form>
    </div>
    <!-- /.card -->
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#back').click(function() {
        window.location="{{ route('mhs.kelas', Crypt::encrypt($mhs->kelas_id)) }}";
        });
    });
    $("#MasterData").addClass("active");
    $("#liMasterData").addClass("menu-open");
    $("#DataMhs").addClass("active");
</script>
@endsection