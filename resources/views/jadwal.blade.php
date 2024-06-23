@extends('layouts.main')

@section('title', 'Jadwal Dosen')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">@if (auth()->user()->role !== 'pimpinan')
                    <a href="{{ route('tambahJadwal') }}" class="btn btn-success btn-sm"> <!-- Tambahkan kelas btn-sm -->
                        <i class="fas fa-plus-square"></i> <!-- Ikon -->
                        <span> Tambah Jadwal</span> <!-- Teks -->
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
                                <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                            </div>
                        @endif
                    </form>
                    <table class="table table-bordered">
                        <thead style="text-align: center;">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Dosen</th>
                                <th>Jadwal Dosen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($dtJadwal) == 0)
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($dtJadwal as $index => $d)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ optional($d->dosen)->nama_dosen }}</td>
                                    <td>
                                        @if ($d->jadwal)
                                            <a href="{{ asset('/pdf/' . $d->jadwal) }}" target="_blank"
                                                rel="noopener noreferrer">Lihat Jadwal</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Edit Button -->
                                        <a href="{{ route('editJadwal', $d->id) }}" type="button"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i></a>
                                        <!-- Delete Button -->
                                        <a href="{{ route('deleteJadwal', $d->id) }}" type="button"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i></a>
                                        <!-- ... bagian JavaScript SweetAlert ... -->
                                    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $dtJadwal->links() }}
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
