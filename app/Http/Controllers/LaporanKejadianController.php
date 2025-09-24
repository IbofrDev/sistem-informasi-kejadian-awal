<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\Lampiran;

class LaporanKejadianController extends Controller
{
    /**
     * Menampilkan form untuk membuat laporan baru.
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * Menyimpan laporan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'jabatan_pelapor' => 'required|string|max:255',
            'telepon_pelapor' => 'required|string|max:20',
            'jenis_kapal' => 'required|string|max:255',
            'nama_kapal' => 'required|string|max:255',
            'nama_kapal_kedua' => 'nullable|string|max:255',
            'bendera_kapal' => 'required|string|max:100',
            'grt_kapal' => 'required|integer',
            'imo_number' => 'nullable|string|max:100',
            'pelabuhan_asal' => 'required|string|max:255',
            'waktu_berangkat' => 'required|date',
            'pelabuhan_tujuan' => 'required|string|max:255',
            'estimasi_tiba' => 'required|date',
            'pemilik_kapal' => 'required|string|max:255',
            'kontak_pemilik' => 'required|string|max:20',
            'agen_lokal' => 'required|string|max:255',
            'kontak_agen' => 'required|string|max:20',
            'nama_pandu' => 'nullable|string|max:255',
            'nomor_register_pandu' => 'nullable|string|max:255',
            'jenis_muatan' => 'required|string',
            'jumlah_muatan' => 'required|string|max:100',
            'jumlah_penumpang' => 'required|integer',
            'posisi_lintang' => 'required|string|max:50',
            'posisi_bujur' => 'required|string|max:50',
            'tanggal_laporan' => 'required|date',
            'isi_laporan' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,webm|max:20480',
        ]);

        $dataToStore = $validatedData;
        $dataToStore['user_id'] = auth()->id();

        $laporan = LaporanKejadian::create($dataToStore);

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('lampiran', 'public');
                $tipe = str_starts_with($file->getMimeType(), 'image') ? 'foto' : 'video';
                Lampiran::create([
                    'laporan_id' => $laporan->id,
                    'tipe_file' => $tipe,
                    'path_file' => $path,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Laporan kejadian berhasil dikirim!');
    }

    /**
     * Menampilkan detail dari satu laporan spesifik.
     */
    public function show(LaporanKejadian $laporan)
    {
        $this->authorize('view', $laporan);
        $laporan->load('lampiran');
        return view('laporan.show', ['laporan' => $laporan]);
    }

    /**
     * Menampilkan form untuk mengedit laporan.
     */
    public function edit(LaporanKejadian $laporan)
    {
        $this->authorize('update', $laporan);
        return view('laporan.edit', ['laporan' => $laporan]);
    }

    /**
     * Memperbarui laporan di database.
     */
    public function update(Request $request, LaporanKejadian $laporan)
    {
        $this->authorize('update', $laporan);

        $validatedData = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'jabatan_pelapor' => 'required|string|max:255',
            'telepon_pelapor' => 'required|string|max:20',
            'jenis_kapal' => 'required|string|max:255',
            'nama_kapal' => 'required|string|max:255',
            'nama_kapal_kedua' => 'nullable|string|max:255',
            'bendera_kapal' => 'required|string|max:100',
            'grt_kapal' => 'required|integer',
            'imo_number' => 'nullable|string|max:100',
            'pelabuhan_asal' => 'required|string|max:255',
            'waktu_berangkat' => 'required|date',
            'pelabuhan_tujuan' => 'required|string|max:255',
            'estimasi_tiba' => 'required|date',
            'pemilik_kapal' => 'required|string|max:255',
            'kontak_pemilik' => 'required|string|max:20',
            'agen_lokal' => 'required|string|max:255',
            'kontak_agen' => 'required|string|max:20',
            'nama_pandu' => 'nullable|string|max:255',
            'nomor_register_pandu' => 'nullable|string|max:255',
            'jenis_muatan' => 'required|string',
            'jumlah_muatan' => 'required|string|max:100',
            'jumlah_penumpang' => 'required|integer',
            'posisi_lintang' => 'required|string|max:50',
            'posisi_bujur' => 'required|string|max:50',
            'tanggal_laporan' => 'required|date',
            'isi_laporan' => 'required|string',
        ]);
        
        $laporan->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * Menghapus laporan dari database.
     */
    public function destroy(LaporanKejadian $laporan)
    {
        $this->authorize('delete', $laporan);
        $laporan->delete();
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}
