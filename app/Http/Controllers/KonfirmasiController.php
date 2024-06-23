<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;


class KonfirmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $idUser = auth()->user()->id;

        if ($dosen) {
            $dtKegiatan = Kegiatan::where('nip', $dosen->nip);
        } else {
            $dtKegiatan = Kegiatan::query();
        }

        // Mengambil data kegiatan dari model Kegiatan
        $dtKegiatan = $dtKegiatan->orderBy('tanggal', 'desc')->paginate(5);

        if (!($dtKegiatan instanceof \Illuminate\Pagination\LengthAwarePaginator)) {
            // Jika bukan objek LengthAwarePaginator, tampilkan pesan error
            return redirect()->back()->with('error', 'Failed to fetch data.');
        }
        $kegiatanOptions = Kegiatan::distinct()->pluck('status_kehadiran')->toArray();
        // Mengirim data ke view 'konfirmasi'

        $additionalOptions = ['Hadir', 'Tidak Hadir'];
    
        // Gabungkan opsi manual dengan opsi yang ditemukan dari tabel
        $kegiatanOptions = array_merge($additionalOptions, $kegiatanOptions);
        
        // Hilangkan nilai duplikat
        $kegiatanOptions = array_unique($kegiatanOptions);
        
        return view('konfirmasi', compact('dtKegiatan', 'dosen', 'user', 'kegiatanOptions'));
    }

    public function konfirmasiKehadiran(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status_kehadiran = $request->input('status_kehadiran', 'Hadir');
        $kegiatan->save();

        return redirect()->route('konfirmasi.index')->with('success', 'Attendance status updated successfully.');
    }
    
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Perbarui status kehadiran menjadi 'Tidak Hadir' untuk kegiatan yang sudah berlalu
            $now = now()->toDateString();
            Kegiatan::where('tanggal', '>=', $now)
                ->update(['status_kehadiran' => 'Hadir']);
        })->daily(); // Pengecekan dilakukan setiap hari
    }

//     public function uploadBuktiKehadiran(Request $request)
// {
//     // Validasi input
//     $request->validate([
//         'id' => 'required|exists:kegiatan,id',
//         'keterangan' => 'required|string|max:255',
//         'bukti' => 'nullable|file|mimes:pdf|max:2048',
//     ]);

//     $kegiatan = Kegiatan::findOrFail($request->id);
//     $kegiatan->status_kehadiran = 'Tidak Hadir';
//     $kegiatan->keterangan = $request->keterangan;

//     // Jika ada file yang diunggah
//     if ($request->hasFile('bukti')) {
//         // Mendapatkan file yang diunggah
//         $file = $request->file('bukti');
//         // Menyimpan file ke direktori public/pdf
//         $namaFile = $file->getClientOriginalName();
//         // Menyimpan path file ke database tanpa 'public/' di depannya
//         $file->move(public_path() . '/pdf', $namaFile);
//         $kegiatan->bukti = $namaFile;
//     } else {
//         // Tidak ada file yang diunggah, set "bukti" menjadi null
//         $kegiatan->bukti = null;
//     }

//     $kegiatan->save();

//     return redirect()->route('konfirmasi.index')->with('success', 'Bukti ketidakhadiran berhasil diunggah.');
// }

public function uploadBuktiKehadiran(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|exists:kegiatan,id',
        'keterangan' => 'required|string|max:255',
        'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,txt|max:5120',
    ]);

    $kegiatan = Kegiatan::findOrFail($request->id);
    $kegiatan->status_kehadiran = 'Tidak Hadir';
    $kegiatan->keterangan = $request->keterangan;

    // Jika ada file yang diunggah
    if ($request->hasFile('bukti')) {
        // Membuat nama file unik dengan timestamp
        $file = $request->file('bukti');
        $namaFile = time() . '_' . $file->getClientOriginalName();

        // Memastikan folder public/pdf ada
        if (!file_exists(public_path('pdf'))) {
            mkdir(public_path('pdf'), 0775, true);
        }

        // Memindahkan file ke direktori public/pdf
        $file->move(public_path('pdf'), $namaFile);

        // Menyimpan nama file saja ke database
        $kegiatan->bukti = $namaFile;
    } else {
        // Tidak ada file yang diunggah, set "bukti" menjadi null
        $kegiatan->bukti = null;
    }

    $kegiatan->save();

    return redirect()->route('konfirmasi.index')->with('success', 'Bukti ketidakhadiran berhasil diunggah.');
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
