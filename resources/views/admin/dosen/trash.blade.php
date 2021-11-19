@extends('template_backend.home')
@section('heading', 'Trash Dosen')
@section('page')
  <li class="breadcrumb-item active">Trash Dosen</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Trash Data Dosen</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Dosen</th>
                    <th>Id Card</th>
                    <th>Team</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dosen as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama_dosen }}</td>
                    <td>{{ $data->id_card }}</td>
                    <td>{{ $data->team->nama_team }}</td>
                    <td>
                        <a href="{{ asset($data->foto) }}" data-toggle="lightbox" data-title="Foto {{ $data->nama_dosen }}" data-gallery="gallery" data-footer='<a href="{{ route('dosen.ubah-foto', Crypt::encrypt($data->id)) }}" id="linkFotoDosen" class="btn btn-link btn-block btn-light"><i class="nav-icon fas fa-file-upload"></i> &nbsp; Ubah Foto</a>'>
                            <img src="{{ asset($data->foto) }}" width="130px" class="img-fluid mb-2">
                        </a>
                        {{-- https://siakad.didev.id/dosen/ubah-foto/{{$data->id}} --}}
                    </td>
                    <td>
                        <form action="{{ route('dosen.kill', $data->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <a href="{{ route('dosen.restore', Crypt::encrypt($data->id)) }}" class="btn btn-success btn-sm mt-2"><i class="nav-icon fas fa-undo"></i> &nbsp; Restore</a>
                            <button class="btn btn-danger btn-sm mt-2"><i class="nav-icon fas fa-trash-alt"></i> &nbsp; Hapus</button>
                        </form>
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
        $("#ViewTrash").addClass("active");
        $("#liViewTrash").addClass("menu-open");
        $("#TrashDosen").addClass("active");
    </script>
@endsection