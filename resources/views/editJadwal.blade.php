@extends('layouts.main')

@section('title', 'Tambah Jadwal')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Jadwal</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="myForm" action="{{ route('updateJadwal', $jadwal->id) }}" method="post" 
                        enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                    <div class="form-group row">
                                        <label for="nip" class="col-sm-2 col-form-label">Nama Dosen:</label>
                                        <div class="col-sm-10">
                                        <select class="form-control" id="nip" name="nip" required>
                                            <option disabled value="">Pilih Nama Dosen</option>
                                            @foreach ($dosen as $d)
                                                <option value="{{ $d->nip }}"
                                                    {{ $d->nip == $jadwal->nip ? 'selected' : '' }}>{{ $d->nama_dosen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="col-sm-10">
                                        @if ($jadwal->jadwal)
                                            <p>File yang sudah diunggah: {{ $jadwal->jadwal }}</p>
                                        @else
                                            <p>Belum ada file yang diunggah.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <label for="jadwal">Surat Tugas:</label>
                                        <input type="file" id="jadwal" name="jadwal">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
