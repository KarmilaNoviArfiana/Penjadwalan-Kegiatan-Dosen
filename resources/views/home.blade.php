@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-4 col-8">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalDosen }}</h3>
                            <p>Data Dosen</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard"></i>
                        </div>
                        <a href="/dosen" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-8">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalKegiatan }}</h3>
                            <p>Kegiatan Dosen</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="/kegiatanDosen" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>

            @if (auth()->user()->role === 'user')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card card-default">
                            <div class="card-header">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" name="date2" id="" class="form-control"
                                                value="{{ request()->get('date2') ?? date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6 d-flex" style="gap:1rem">
                                            <select name="filter2" id="" class="form-control">
                                                <option value="">-- Semua --</option>
                                                <option value="1"
                                                    {{ request()->get('filter2') == 1 ? 'selected' : '' }}>1
                                                    Bulan
                                                </option>
                                                <option value="2"
                                                    {{ request()->get('filter2') == 2 ? 'selected' : '' }}>6
                                                    bulan
                                                </option>
                                                <option value="3"
                                                    {{ request()->get('filter2') == 3 ? 'selected' : '' }}>1
                                                    Tahun
                                                </option>
                                            </select>

                                            <button class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            @else
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card card-default">
                            <div class="card-header">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" name="date" id="" class="form-control"
                                                value="{{ request()->get('date') ?? date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6 d-flex" style="gap:1rem">
                                            <select name="filter" id="" class="form-control">
                                                <option value="">-- Semua --</option>
                                                <option value="1"
                                                    {{ request()->get('filter') == 1 ? 'selected' : '' }}>1
                                                    Bulan
                                                </option>
                                                <option value="2"
                                                    {{ request()->get('filter') == 2 ? 'selected' : '' }}>6
                                                    bulan
                                                </option>
                                                <option value="3"
                                                    {{ request()->get('filter') == 3 ? 'selected' : '' }}>1
                                                    Tahun
                                                </option>
                                            </select>

                                            <button class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card card-default">
                            <div class="card-header">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="nip" id="nip" class="form-control">
                                                <option value="">-- Pilih --</option>
                                                @foreach (\App\Models\Dosen::all() as $item)
                                                    <option value="{{ $item->nip }}"
                                                        {{ request()->get('nip') == $item->nip ? 'selected' : '' }}>
                                                        {{ $item->nama_dosen }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" name="date2" id="" class="form-control"
                                                value="{{ request()->get('date2') ?? date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-4 d-flex" style="gap:1rem">
                                            <select name="filter2" id="" class="form-control">
                                                <option value="">-- Semua --</option>
                                                <option value="1"
                                                    {{ request()->get('filter2') == 1 ? 'selected' : '' }}>1
                                                    Bulan
                                                </option>
                                                <option value="2"
                                                    {{ request()->get('filter2') == 2 ? 'selected' : '' }}>6
                                                    bulan
                                                </option>
                                                <option value="3"
                                                    {{ request()->get('filter2') == 3 ? 'selected' : '' }}>1
                                                    Tahun
                                                </option>
                                            </select>

                                            <button class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="chart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            @endif

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        const ctx = document.getElementById('chart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($listDosen) !!},
                datasets: [{
                    label: '# dari Kegiatan',
                    data: {!! json_encode($listNilai) !!},
                    backgroundColor: '#C44628',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @if (auth()->user()->role != 'user')
        <script>
            const ctx2 = document.getElementById('chart2');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($listDosen2) !!},
                    datasets: [{
                        label: '# dari Kegiatan',
                        data: {!! json_encode($listNilai2) !!},
                        backgroundColor: ['#C44628','#F00', '#25A512', '#81DE87', '#3245DE'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endif
@endsection
