@extends('template_backend.home')
@section('heading', 'Edit Team')
@section('page')
  <li class="breadcrumb-item active"><a href="{{ route('team.index') }}">Team</a></li>
  <li class="breadcrumb-item active">Edit Team</li>
@endsection
@section('content')
<div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Data Team</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route('team.store') }}" method="post">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <div class="form-group">
                  <label for="nama_team">Nama Team</label>
                  <input type="text" id="nama_team" name="nama_team" value="{{ $team->nama_team }}" class="form-control @error('nama_team') is-invalid @enderror" placeholder="{{ __('Nama Mata Pelajaran') }}">
                </div>
                <div class="form-group">
                  <label for="paket_id">Paket</label>
                  <select id="paket_id" name="paket_id" class="form-control @error('paket_id') is-invalid @enderror select2bs4">
                    <option value="">-- Pilih Paket Team --</option>
                    <option value="9"
                        @if ($team->paket_id == '9')
                            selected
                        @endif
                    >Semua</option>
                    @foreach ($paket as $data)
                      <option value="{{ $data->id }}"
                        @if ($team->paket_id == $data->id)
                            selected
                        @endif
                      >{{ $data->ket }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <label for="kelompok">Kelompok</label>
                    <select id="kelompok" name="kelompok" class="select2bs4 form-control @error('kelompok') is-invalid @enderror">
                        <option value="">-- Pilih Kelompok Team --</option>
                        <option value="A"
                            @if ($team->kelompok == 'A')
                                selected
                            @endif
                        >Pelajaran Umum</option>
                        <option value="B"
                            @if ($team->kelompok == 'B')
                                selected
                            @endif
                        >Pelajaran Khusus</option>
                        <option value="C"
                            @if ($team->kelompok == 'C')
                                selected
                            @endif
                        >Pelajaran Keahlian</option>
                    </select>
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
        window.location="{{ route('team.index') }}";
        });
    });
    $("#MasterData").addClass("active");
    $("#liMasterData").addClass("menu-open");
    $("#DataTeam").addClass("active");
</script>
@endsection