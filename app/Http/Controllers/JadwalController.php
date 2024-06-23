<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $idUser = auth()->user()->id;

        // Mengambil data kegiatan dengan paginasi
        if ($dosen) {
            $dtJadwal = Jadwal::where('nip', $dosen->nip);
        } else {
            $dtJadwal = Jadwal::query();
        }

        if ($request->has('nip') && $request->get('nip')) {
            $dtJadwal->where('nip', $request->get('nip'));
        }

        if ($request->has('search')) {
            $keyword = $request->get('search');
            $dtJadwal->whereHas('dosen', function ($query) use ($keyword) {
                $query->where('nama_dosen', 'like', "%$keyword%");
            });
        }        

        $dtJadwal = $dtJadwal->orderBy('created_at', 'desc')->paginate(5);

        // Memastikan bahwa $dtKegiatan adalah objek LengthAwarePaginator
        if (!($dtJadwal instanceof \Illuminate\Pagination\LengthAwarePaginator)) {
            // Jika bukan objek LengthAwarePaginator, tampilkan pesan error
            return redirect()->back()->with('error', 'Failed to fetch data.');
        }

        // Mengambil semua data dosen
        $dosen = Dosen::all();

        // Mengirim data ke view 'kegiatanDosen'
        return view('jadwal', compact('dtJadwal', 'dosen', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::all();

        return view('tambahJadwal', compact('dosen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Memeriksa apakah file telah diunggah
        if ($request->hasFile('jadwal')) {
            // Mendapatkan file yang diunggah
            $file = $request->file('jadwal');
            $namaFile = $file->getClientOriginalName();

            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path() . '/pdf', $namaFile);
        } else {
            // Tidak ada file yang diunggah, set "surat_tugas" menjadi null
            $namaFile = null;
        }

        // Membuat instance dari model Kegiatan
        $dtJadwal = new Jadwal;
        $dtJadwal->nip = $request->nip;
        $dtJadwal->jadwal = $namaFile; // Tetapkan nama file atau null

        // Menyimpan model Kegiatan
        $dtJadwal->save();

        $user = auth()->user();
        $dosen = Dosen::where('nip', $request->nip)->first();

        return redirect('jadwal');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $dosen = Dosen::all();

        return view('editJadwal', compact('jadwal', 'dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ubah = Jadwal::findOrFail($id);
        $old_nip = $ubah->nip;
        $awal = $ubah->surat_tugas;

        // Menyimpan perubahan lainnya
        $dt = [
            'nip' => $request['nip'],
            'jadwal' => $request['jadwal'],
        ];

        // Memeriksa apakah ada file yang diunggah
        if ($request->hasFile('jadwal')) {
            // Mendapatkan file yang diunggah
            $file = $request->file('jadwal');

            // Membuat nama unik untuk file yang akan disimpan
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();

            // Memindahkan file ke direktori yang ditentukan
            $file->move(public_path() . '/pdf', $namaFile);

            // Menghapus file lama jika ada dan menggantikan dengan yang baru
            if (!empty($awal)) {
                // Hapus file lama
                if (file_exists(public_path('pdf/' . $awal))) {
                    unlink(public_path('pdf/' . $awal));
                }
            }

            // Memperbarui nama file di model Kegiatan
            $dt['jadwal'] = $namaFile;
        }

        $ubah->update($dt);

        return redirect('jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect('jadwal');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        // Query pencarian data dosen berdasarkan nama
        $dtDosen = Dosen::where('nama', 'like', '%' . $search . '%')->get();

        // Mengambil nip dari hasil pencarian dosen
        $nipDosen = $dtDosen->pluck('nip')->toArray();

        // Query pencarian kegiatan berdasarkan nip dosen dan tanggal
        $dtJadwal = Jadwal::query()
            ->whereIn('nip', $nipDosen)
            ->paginate(5);

        return view('jadwal', compact('dtJadwal', 'dtDosen'));
    }

}
