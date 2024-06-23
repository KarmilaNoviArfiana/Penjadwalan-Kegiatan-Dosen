<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $idUser = auth()->user()->id;

        $jumlahDosen = Dosen::count();

        $dtDosen = Dosen::paginate(5);

        return view('dosen', compact('user', 'jumlahDosen', 'dtDosen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tambahDosen');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric',
            'nama_dosen' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
            'telp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first())->withInput();
        }

        try {
            Dosen::addNewDosen($validator->validated());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan data')->withInput();
        }

        return redirect('dosen');
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
    public function edit($id)
    {
        $dosen = Dosen::findorfail($id);

        return view('editDosen', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric',
            'nama_dosen' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:6',
            'telp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first())->withInput();
        }

        try {
            Dosen::editDosen($id, $validator->validated());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah data')->withInput();
        }

        return redirect('dosen');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::findorfail($id);
        $user = $dosen->user;
        $dosen->delete();
        $user->delete();

        return redirect('dosen');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $dtDosen = Dosen::where('nama_dosen', 'like', '%' . $search . '%')->paginate(10);

        return view('dosen', compact('dtDosen'));
    }

}
