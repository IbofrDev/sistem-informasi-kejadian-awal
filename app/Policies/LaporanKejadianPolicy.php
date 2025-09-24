<?php

namespace App\Policies;

use App\Models\LaporanKejadian;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LaporanKejadianPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @return void|bool
     */
    // Di dalam file LaporanKejadianPolicy.php

    public function before(User $user)
    {
        // Kode untuk debugging, akan menghentikan eksekusi dan menampilkan peran pengguna
        //dd($user->role);

        // Berikan akses penuh ke admin untuk semua aksi
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Izinkan semua pengguna yang login untuk melihat daftar laporan mereka
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LaporanKejadian  $laporanKejadian
     * @return bool
     */
    public function view(User $user, LaporanKejadian $laporanKejadian): bool
    {
        // Pengguna biasa hanya boleh melihat laporannya sendiri.
        // Admin sudah diizinkan oleh fungsi before().
        return $user->id === $laporanKejadian->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Izinkan semua pengguna yang login untuk membuat laporan
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LaporanKejadian  $laporanKejadian
     * @return bool
     */
    public function update(User $user, LaporanKejadian $laporanKejadian): bool
    {
        // Pengguna biasa hanya boleh mengedit laporannya sendiri
        return $user->id === $laporanKejadian->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LaporanKejadian  $laporanKejadian
     * @return bool
     */
    public function delete(User $user, LaporanKejadian $laporanKejadian): bool
    {
        // Pengguna biasa hanya boleh menghapus laporannya sendiri
        return $user->id === $laporanKejadian->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LaporanKejadian  $laporanKejadian
     * @return bool
     */
    public function restore(User $user, LaporanKejadian $laporanKejadian): bool
    {
        // Logika untuk restore (jika menggunakan SoftDeletes)
        return $user->id === $laporanKejadian->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LaporanKejadian  $laporanKejadian
     * @return bool
     */
    public function forceDelete(User $user, LaporanKejadian $laporanKejadian): bool
    {
        // Logika untuk force delete (jika menggunakan SoftDeletes)
        return $user->id === $laporanKejadian->user_id;
    }
}
