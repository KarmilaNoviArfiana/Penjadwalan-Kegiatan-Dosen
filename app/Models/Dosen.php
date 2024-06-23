<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $primaryKey = 'nip';

    protected $fillable = ['nip', 'nama_dosen', 'telp', 'alamat', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addNewDosen($payloads)
    {
        try {
            DB::beginTransaction();

            $payloads['name'] = $payloads['nama_dosen'];
            $payloads['role'] = 'user';
            $user = User::create($payloads);

            $payloads['telp'] = purify_phone_number($payloads['telp']);
            $payloads['user_id'] = $user->id;
            Dosen::create($payloads);

            DB::commit();
        } catch (\Throwable $th) {
            throw new Exception("Gagal menambahkan data dosen");
        }
    }
    public static function editDosen($id, $payloads)
    {
        try {
            $dosen = Dosen::findOrFail($id);
            $user = $dosen->user;

            DB::beginTransaction();

            $payloads['name'] = $payloads['nama_dosen'];
            $payloads['role'] = 'user';

            if (isset($payloads['password']) && $payloads['password'] == '') {
                unset($payloads['password']);
            }

            $user->update($payloads);

            $payloads['telp'] = purify_phone_number($payloads['telp']);
            $payloads['user_id'] = $user->id;
            $dosen->update($payloads);

            DB::commit();
        } catch (\Throwable $th) {
            throw new Exception("Gagal mengubah data dosen");
        }
    }
}
