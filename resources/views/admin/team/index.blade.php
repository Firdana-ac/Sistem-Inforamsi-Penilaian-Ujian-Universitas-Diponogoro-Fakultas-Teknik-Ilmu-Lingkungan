@extends('template_backend.home')
@section('heading', 'Data Team')
@section('page')
  <li class="breadcrumb-item active">Data Team</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".tambah-team">
                    <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Team
                </button>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Team</th>
                    <th>Paket</th>
                    <th>Kelompok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($team as $result => $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama_team }}</td>
                    @if ( $data->paket_id == 9 )
                      <td>{{ 'Semua' }}</td>
                    @else
                      <td>{{ $data->paket->ket }}</td>
                    @endif
                    <td>{{ $data->kelompok }}</td>
                    <td>
                        <form action="{{ route('team.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <a href="{{ route('team.edit', Crypt::encrypt($data->id)) }}" class="btn btn-success btn-sm"><i class="nav-icon fas fa-edit"></i> &nbsp; Edit</a>
                            <button class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash-alt"></i> &nbsp; Hapus</button>
                        </form>
                    </td>
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

<!-- Extra large modal -->
<div class="modal fade bd-example-modal-md tambah-team" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Tambah Data Team</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form action="{{ route('team.store') }}" method="post">
          @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="nama_team">Nama Team</label>
                  <input type="text" id="nama_team" name="nama_team" class="form-control @error('nama_team') is-invalid @enderror" placeholder="{{ __('Nama Mata Pelajaran') }}">
                </div>
                <div class="form-group">
                  <label for="paket_id">Paket</label>
                  <select id="paket_id" name="paket_id" class="form-control @error('paket_id') is-invalid @enderror select2bs4">
                    <option value="">-- Pilih Paket Team --</option>
                    <option value="9">Semua</option>
                    @foreach ($paket as $data)
                      <option value="{{ $data->id }}">{{ $data->ket }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <label for="kelompok">Kelompok</label>
                    <select id="kelompok" name="kelompok" class="select2bs4 form-control @error('kelompok') is-invalid @enderror">
                      <option value="">-- Pilih Kelompok Team --</option>
                      <option value="A">Pelajaran Umum</option>
                      <option value="B">Pelajaran Khusus</option>
                      <option value="C">Pelajaran Keahlian</option>
                    </select>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
            <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Tambahkan</button>
        </form>
    </div>
    </div>
  </div>
</div>
@endsection
@section('script')
  <script>
    $("#MasterData").addClass("active");
    $("#liMasterData").addClass("menu-open");
    $("#DataTeam").addClass("active");
  </script>
@endsection