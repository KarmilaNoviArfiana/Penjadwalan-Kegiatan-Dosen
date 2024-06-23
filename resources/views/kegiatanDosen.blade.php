@extends('layouts.main')

@section('title', 'Kegiatan Dosen')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">@if (auth()->user()->role !== 'pimpinan')
                    <a href="{{ route('tambahKegiatan') }}" class="btn btn-success btn-sm"> <!-- Tambahkan kelas btn-sm -->
                        <i class="fas fa-plus-square"></i> <!-- Ikon -->
                        <span> Tambah Kegiatan</span> <!-- Teks -->
                    </a>
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="GET" class="mb-3">
                        <!-- Menggunakan float-right untuk memindahkan form ke kanan -->
                        @if (auth()->user()->role == 'user')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Cari..."
                                            value="{{ request()->get('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="tugas" id="" class="form-control">
                                            <option value="">Semua Pemberi Tugas</option>
                                            @foreach (\App\Models\Kegiatan::PemberiTugas as $item)
                                                <option value="{{ $item }}"
                                                    {{ request()->get('tugas') == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="daterange"
                                            placeholder="Search by Date" autocomplete="off"
                                            value="{{ request()->get('daterange') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else<div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Cari..."
                                            value="{{ request()->get('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <select name="tugas" id="" class="form-control">
                                            <option value="">Semua Pemberi Tugas</option>
                                            @foreach (\App\Models\Kegiatan::PemberiTugas as $item)
                                                <option value="{{ $item }}"
                                                    {{ request()->get('tugas') == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <select name="nip" id="" class="form-control">
                                            <option value="">Semua Dosen</option>
                                            @foreach (\App\Models\Dosen::all() as $item)
                                                <option value="{{ $item->nip }}"
                                                    {{ request()->get('nip') == $item->nip ? 'selected' : '' }}>
                                                    {{ $item->nama_dosen }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="daterange"
                                            placeholder="Search by Date" autocomplete="off"
                                            value="{{ request()->get('daterange') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                    <table class="table table-bordered">
                        <thead style="text-align: center;">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Dosen</th>
                                <th>Pemberi Tugas</th>
                                <th>Nama Kegiatan</th>
                                <th>Tempat</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Surat Tugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($dtKegiatan) == 0)
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($dtKegiatan as $index => $d)
                            @php
                                \Carbon\Carbon::setLocale('id');
                            @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ optional($d->dosen)->nama_dosen }}</td>
                                    <td>{{ $d->tugas }}</td>
                                    <td>{{ $d->nama_kegiatan }}</td>
                                    <td>{{ $d->Tempat }}</td>
                                    <!-- <td>{{ readable_date($d->tanggal) }}</td> -->
                                    <td>{{ \Carbon\Carbon::parse($d->tanggal)->isoFormat('dddd, DD MMMM YYYY') }}</td>
                                    <td>{{ readable_time($d->waktu_mulai) }} - {{ readable_time($d->waktu_selesai) }}</td>
                                    <!-- Kolom waktu -->
                                    <td>
                                        @if ($d->surat_tugas)
                                            <a href="{{ asset('/pdf/' . $d->surat_tugas) }}" target="_blank"
                                                rel="noopener noreferrer">Lihat Surat Tugas</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                    @if (auth()->user()->role !== 'pimpinan')
                                        <!-- Edit Button -->
                                        <a href="{{ route('editKegiatan', $d->id) }}" type="button"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i></a>
                                        <!-- Delete Button -->
                                        <a href="{{ route('deleteKegiatan', $d->id) }}" type="button"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i></a>
                                        <!-- ... bagian JavaScript SweetAlert ... -->
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $dtKegiatan->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('styles')

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endsection
@section('scripts')
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script>
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endsection
