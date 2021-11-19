@extends('template_backend.home')
@section('heading', 'Trash Mhs')
@section('page')
  <li class="breadcrumb-item active">Trash Mhs</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Trash Data Mhs</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Mhs</th>
                    <th>Nomor Induk</th>
                    <th>Kelas</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mhs as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama_mhs }}</td>
                    <td>{{ $data->no_induk }}</td>
                    <td>{{ $data->kelas->nama_kelas }}</td>
                    <td>
                        <a href="{{ asset($data->foto) }}" data-toggle="lightbox" data-title="Foto {{ $data->nama_mhs }}" data-gallery="gallery" data-footer='<a href="{{ route('mhs.ubah-foto', Crypt::encrypt($data->id)) }}" id="linkFotoDosen" class="btn btn-link btn-block btn-light"><i class="nav-icon fas fa-file-upload"></i> &nbsp; Ubah Foto</a>'>
                            <img src="{{ asset($data->foto) }}" width="130px" class="img-fluid mb-2">
                        </a>
                        {{-- https://siakad.didev.id/mhs/ubah-foto/{{$data->id}} --}}
                    </td>
                    <td>
                        <form action="{{ route('mhs.kill', $data->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <a href="{{ route('mhs.restore', Crypt::encrypt($data->id)) }}" class="btn btn-success btn-sm mt-2"><i class="nav-icon fas fa-undo"></i> &nbsp; Restore</a>
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
        $("#TrashMhs").addClass("active");
    </script>
@endsection