<?php

namespace App\Http\Controllers;

use App\Helpers\WaSender;
use App\Models\Dosen;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KegiatanDosenController extends Controller
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
            $dtKegiatan = Kegiatan::where('nip', $dosen->nip);
        } else {
            $dtKegiatan = Kegiatan::query();
        }

        if ($request->has('daterange') && $request->get('daterange')) {
            $tanggal = explode(" - ", $request->get('daterange'));
            $tanggalMulai = date("Y-m-d", strtotime($tanggal[0]));
            $tanggalSelesai = date("Y-m-d", strtotime($tanggal[1]));
            // dd($dtKegiatan->toSql());
            $dtKegiatan->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($request->has('tugas') && $request->get('tugas')) {
            $dtKegiatan->where('tugas', $request->get('tugas'));
        }

        if ($request->has('nip') && $request->get('nip')) {
            $dtKegiatan->where('nip', $request->get('nip'));
        }

        if ($request->has('search')) {
            $keyword = $request->get('search');
            $dtKegiatan->where('nama_kegiatan', 'like', "%$keyword%");
        }

        $dtKegiatan = $dtKegiatan->orderBy('tanggal', 'desc')->paginate(5);

        // Memastikan bahwa $dtKegiatan adalah objek LengthAwarePaginator
        if (!($dtKegiatan instanceof \Illuminate\Pagination\LengthAwarePaginator)) {
            // Jika bukan objek LengthAwarePaginator, tampilkan pesan error
            return redirect()->back()->with('error', 'Failed to fetch data.');
        }

        // Mengambil semua data dosen
        $dosen = Dosen::all();
      
        // Mengirim data ke view 'kegiatanDosen'
        return view('kegiatanDosen', compact('dtKegiatan', 'dosen', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::all();

        return view('tambahKegiatan', compact('dosen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Memeriksa apakah file telah diunggah
        if ($request->hasFile('surat_tugas')) {
            // Mendapatkan file yang diunggah
            $file = $request->file('surat_tugas');
            $namaFile = $file->getClientOriginalName();

            // Memindahkan file ke folder yang ditentukan
            $file->move(public_path() . '/pdf', $namaFile);
        } else {
            // Tidak ada file yang diunggah, set "surat_tugas" menjadi null
            $namaFile = null;
        }

        // Set locale ke bahasa Indonesia
        Carbon::setLocale('id');

        // Membuat instance dari model Kegiatan
        $dtKegiatan = new Kegiatan;
        $dtKegiatan->nip = $request->nip;
        $dtKegiatan->tugas = $request->tugas;
        $dtKegiatan->nama_kegiatan = $request->nama_kegiatan;
        $dtKegiatan->Tempat = $request->Tempat;
        $dtKegiatan->tanggal = $request->tanggal;
        $dtKegiatan->waktu_mulai = $request->waktu_mulai;
        $dtKegiatan->waktu_selesai = $request->waktu_selesai;
        $dtKegiatan->surat_tugas = $namaFile; // Tetapkan nama file atau null

        // Menyimpan model Kegiatan
        $dtKegiatan->save();

        // Format tanggal untuk ditampilkan dalam Bahasa Indonesia
        $formattedDate = Carbon::parse($dtKegiatan->tanggal)->locale('id')->isoFormat('dddd, DD MMMM YYYY');

        $user = auth()->user();
        $dosen = Dosen::where('nip', $request->nip)->first();
        if ($user->role !== "user" && $dosen) {
            WaSender::send($dosen->telp, "*Ada kegiatan baru untuk anda dari {$request->tugas}* \n\nTanggal: {$request->tanggal} \n\nHarap untuk melihat sistem penjadwalan untuk informasi selengkapnya dan harap konfirmasi kehadiran jika tidak hadir.
            \nTerimakasih \n\nKreator: {$user->name}");
        }

        return redirect('kegiatanDosen')->with('formattedDate', $formattedDate);
    }

    // Buat kegiatan baru dengan data yang diterima dari form

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $kegiatan = Kegiatan::findOrFail($id);
        // return view('detailKegiatan', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $dosen = Dosen::all();

        return view('editKegiatan', compact('kegiatan', 'dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ubah = Kegiatan::findOrFail($id);
        $old_nip = $ubah->nip;
        $old_tanggal = $ubah->tanggal;
        $awal = $ubah->surat_tugas;

        // Menyimpan perubahan lainnya
        $dt = [
            'nip' => $request['nip'],
            'tugas' => $request['tugas'],
            'nama_kegiatan' => $request['nama_kegiatan'],
            'Tempat' => $request['Tempat'],
            'tanggal' => $request['tanggal'],
            'waktu_mulai' => $request['waktu_mulai'],
            'waktu_selesai' => $request['waktu_selesai'],
            'surat_tugas' => $request['surat_tugas'], // Ini mungkin perlu disesuaikan
        ];

        // Memeriksa apakah ada file yang diunggah
        if ($request->hasFile('surat_tugas')) {
            // Mendapatkan file yang diunggah
            $file = $request->file('surat_tugas');

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
            $dt['surat_tugas'] = $namaFile;
        }

        $ubah->update($dt);

        if ($ubah->nip != $old_nip || $ubah->tanggal != $old_tanggal) {
            $user = auth()->user();
            $dosen = Dosen::where('nip', $request->nip)->first();
            if ($user->role !== "user" && $dosen) {
                WaSender::send($dosen->telp, "*Update kegiatan baru untuk anda dari {$request->tugas}* \n\nTanggal: {$request->tanggal} \n\nHarap untuk melihat sistem penjadwalan untuk informasi selengkapnya dan harap konfirmasi kehadiran jika tidak hadir. 
                \nTerimakasih \n\nKreator: {$user->name}");
            }
        }

        return redirect('kegiatanDosen');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect('kegiatanDosen');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $date = $request->get('date');

        // Query pencarian data dosen berdasarkan nama
        $dtDosen = Dosen::where('nama_dosen', 'like', '%' . $search . '%')->get();

        // Mengambil nip dari hasil pencarian dosen
        $nipDosen = $dtDosen->pluck('nip')->toArray();

        // Query pencarian kegiatan berdasarkan nip dosen dan tanggal
        $dtKegiatan = Kegiatan::query()
            ->whereIn('nip', $nipDosen) // Menambahkan kriteria nip yang sesuai dengan hasil pencarian nama dosen
            ->whereDate('tanggal', $date)
            ->paginate(5);

        return view('kegiatanDosen', compact('dtKegiatan', 'dtDosen'));
    }

}
