@extends('layouts.main')

@section('title', 'Edit Dosen')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8 mx-auto">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit DOSEN</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="myForm" action="{{ route('updateDosen', $dosen->nip) }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="NIP" class="col-sm-2 col-form-label">NIP:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="NIP" name="nip"
                                            value="{{ old('nip') ?? $dosen->nip }}" Placeholder="NIP" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nama_dosen" class="col-sm-2 col-form-label">Nama Dosen:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nama_dosen" name="nama_dosen"
                                            value="{{ old('nama_dosen') ?? $dosen->nama_dosen }}" Placeholder="Nama Dosen"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Email" class="col-sm-2 col-form-label">Email:</label>
                                    <div class="col-sm-10">
                                        <input type="int" class="form-control" id="Email" name="email"
                                            value="{{ old('email') ?? $dosen->user->email }}" Placeholder="Email" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Password" class="col-sm-2 col-form-label">Password:</label>
                                    <div class="col-sm-10">
                                        <input type="int" class="form-control" id="Password" name="password"
                                            value="{{ old('Password') }}" Placeholder="Password"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="telp" class="col-sm-2 col-form-label">No Telepon:</label>
                                    <div class="col-sm-10">
                                        <input type="int" class="form-control" id="telp" name="telp"
                                            value="{{ old('telp') ?? $dosen->telp }}" Placeholder="No Telepon" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Homebase:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            value="{{ old('alamat') ?? $dosen->alamat }}" Placeholder="Homebase" required>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Ubah Data</button>
                            </div>
                        </form>
                        <script>
                            document.getElementById("myForm").addEventListener("submit", function(event) {
                                // Check if the No Ref field is empty
                                var noRefField = document.getElementById("no_ref");
                                if (noRefField.value.trim() === "") {
                                    // If it's empty, set a default value (or you can choose to leave it empty)
                                    noRefField.value = "";
                                }
                            });
                        </script>

                        <!-- Tampilan SweetAlert -->
                        @if (session('success'))
                            <!-- Link eksternal untuk SweetAlert -->
                            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

                            <script>
                                // Tampilkan alert pesan sukses saat halaman dimuat
                                document.addEventListener('DOMContentLoaded', function() {
                                    swal({
                                        title: "Data Berhasil Disimpan!",
                                        text: "",
                                        icon: "success",
                                        buttons: {
                                            confirm: {
                                                text: "OK",
                                                value: true,
                                                className: "btn btn-success"
                                            }
                                        }
                                    });
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
@endsection
