@extends('layouts.main')

@section('title', 'Data Dosen')

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('tambahDosen') }}" class="btn btn-success btn-sm"> <!-- Tambahkan kelas btn-sm -->
                            <i class="fas fa-plus-square"></i> <!-- Ikon -->
                            <span> Tambah Dosen</span> <!-- Teks -->
                        </a>
                        <form action="{{ route('searchDosen') }}" method="GET" class="ml-auto">
                            <!-- Menggunakan ml-auto untuk memindahkan form ke kanan -->
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" name="search" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default btn-sm"><i
                                            class="fas fa-search"></i></button> <!-- Tambahkan kelas btn-sm -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead style="text-align: center;">
                            <tr>
                                <th class="text-center">No</th> <!-- Tambahkan class text-center -->
                                <th>Nama Dosen</th>
                                <th>No Telepon</th>
                                <th>Homebase</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($dtDosen) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($dtDosen as $key => $d)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td> <!-- Tambahkan class text-center -->
                                    <td>{{ $d->nama_dosen }}</td>
                                    <td>{{ $d->telp }}</td>
                                    <td>{{ $d->alamat }}</td>
                                    <td class="text-center"> <!-- Menjadikan sel aksi menjadi terpusat -->
                                        <!-- Edit Button -->
                                        <a href="{{ route('editDosen', $d->nip) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> <!-- Icon edit -->
                                            Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <a href="{{ route('deleteDosen', $d->nip) }}" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> <!-- Icon hapus -->
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link" href="{{ $dtDosen->previousPageUrl() }}"><i
                                        class="fas fa-chevron-left"></i></a></li>
                            @for ($i = 1; $i <= $dtDosen->lastPage(); $i++)
                                <li class="page-item{{ $dtDosen->currentPage() === $i ? ' active' : '' }}">
                                    <a class="page-link" href="{{ $dtDosen->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item"><a class="page-link" href="{{ $dtDosen->nextPageUrl() }}"><i
                                        class="fas fa-chevron-right"></i></a></li>
                        </ul>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
@endsection
