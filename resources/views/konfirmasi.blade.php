@extends('layouts.main')

@section('title', 'Konfirmasi Kegiatan')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
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
                                        <input type="text" class="form-control" name="daterange"
                                            placeholder="Search by Date" autocomplete="off"
                                            value="{{ request()->get('daterange') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
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
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status Kehadiran</th>
                                <th>Keterangan</th>
                                @if (auth()->user()->role === 'user')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($dtKegiatan) == 0)
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($dtKegiatan as $index => $d)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ optional($d->dosen)->nama_dosen }}</td>
                                    <td>{{ $d->nama_kegiatan }}</td>
                                    <td>{{ readable_date($d->tanggal) }}</td>
                                    <td>{{ readable_time($d->waktu_mulai) }} - {{ readable_time($d->waktu_selesai) }}</td>
                                    <td>
                                        @if (auth()->user()->role === 'user')
                                            @if ($d->tanggal >= now()->toDateString() && $d->status_kehadiran == 'Hadir')
                                                <form action="{{ route('konfirmasi.kehadiran', $d->id) }}" method="POST">
                                                    @csrf
                                                    <select name="status_kehadiran">
                                                        @foreach($kegiatanOptions as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                </form>
                                            @else
                                            <span style="display: inline-block; width: 100%; text-align: center;" class="badge bg-warning">{{ $d->status_kehadiran }}</span>
                                         @endif
                                        @else
                                            <span style="display: inline-block; width: 100%; text-align: center;" class="badge bg-success">{{ $d->status_kehadiran }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $d->keterangan }}
                                        <br>
                                        @if ($d->bukti)
                                        <a href="{{ url('pdf/' . $d->bukti) }}" target="_blank"
                                                rel="noopener noreferrer">Lihat Bukti</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- Button untuk membuka modal -->
                                    @if (auth()->user()->role === 'user')
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirmasiModal{{ $d->id }}"  @if($d->keterangan || $d->status_kehadiran == 'Hadir') disabled @endif>
                                            <i class="fas fa-mail-bulk"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="konfirmasiModal{{ $d->id }}" tabindex="-1" aria-labelledby="konfirmasiModalLabel{{ $d->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="konfirmasiModalLabel{{ $d->id }}">Konfirmasi Kehadiran</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('konfirmasi.upload') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $d->id }}">
                                                            <div class="form-group">
                                                                <label for="keterangan">Keterangan Tidak Menghadiri Kegiatan:</label>
                                                                <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="bukti">Unggah Bukti (Gambar atau File Lainnya):</label>
                                                                <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
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
