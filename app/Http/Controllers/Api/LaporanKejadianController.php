<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\Lampiran;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\LaporanKejadianResource;
use Carbon\Carbon;

class LaporanKejadianController extends Controller
{
    /**
     * 🔹 Menampilkan data laporan (untuk API dan web dashboard).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $laporanQuery = LaporanKejadian::where('user_id', $user->id)
            ->with(['lampiran', 'user']);

        if ($bulan && $tahun) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan)
                ->whereYear('tanggal_laporan', $tahun);
        } elseif ($bulan) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan);
        } elseif ($tahun) {
            $laporanQuery->whereYear('tanggal_laporan', $tahun);
        }

        $laporanKejadian = $laporanQuery->orderBy('tanggal_laporan', 'desc')->get();

        // ✅ mode API (mobile)
        if ($request->wantsJson() || $request->is('api/*')) {
            return LaporanKejadianResource::collection($laporanKejadian)->resolve();
        }

        // ✅ mode web (Blade)
        $tahunList = LaporanKejadian::selectRaw('YEAR(tanggal_laporan) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('dashboard', [
            'laporanKejadian' => $laporanKejadian,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahunList' => $tahunList
        ]);
    }

    /**
     * Menampilkan form untuk membuat laporan baru (WEB ONLY)
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * Menyimpan laporan baru ke database.
     * Mode API → kirim JSON
     * Mode Web → redirect ke dashboard
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
            'jenis_kecelakaan' => 'nullable|string|max:255',
            'pihak_terkait' => 'nullable|string|max:255',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,webm|max:20480',
        ]);

        $dataToStore = $validatedData;
        $dataToStore['user_id'] = auth()->id();
        $dataToStore['sent_at'] = now(); // 🕒 waktu laporan dikirim

        $laporan = LaporanKejadian::create($dataToStore);

        // Simpan lampiran
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

        // ✅ mode API → gunakan resource
        if ($request->wantsJson() || $request->is('api/*')) {
            $laporan->load('lampiran', 'user');
            return (new LaporanKejadianResource($laporan))
                ->additional(['message' => 'Laporan kejadian berhasil dikirim!'])
                ->response()
                ->setStatusCode(201);
        }

        // ✅ mode web (tetap seperti semula)
        return redirect()->route('dashboard')->with('success', 'Laporan kejadian berhasil dikirim!');
    }

    /**
     * Menampilkan detail laporan.
     */
    public function show(LaporanKejadian $laporan, Request $request)
    {
        $this->authorize('view', $laporan);
        $laporan->load('lampiran', 'user');
        $laporan->refresh();

        // ✅ mode API → gunakan resource
        if ($request->wantsJson() || $request->is('api/*')) {
            return (new LaporanKejadianResource($laporan))
                ->response()
                ->getData(true);
        }

        // ✅ mode web Blade
        return view('laporan.show', compact('laporan'));
    }

    /**
     * Menampilkan form edit laporan (WEB ONLY)
     */
    public function edit(LaporanKejadian $laporan)
    {
        $this->authorize('update', $laporan);
        return view('laporan.edit', compact('laporan'));
    }

    /**
     * Update data laporan.
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
            'jenis_kecelakaan' => 'nullable|string|max:255',
            'pihak_terkait' => 'nullable|string|max:255',
            'status_laporan' => 'sometimes|string|in:dikirim,diverifikasi,selesai',
        ]);

        $laporan->update($validatedData);

        // 🕒 isi otomatis kolom waktu jika status berubah
        if (isset($validatedData['status_laporan'])) {
            switch ($validatedData['status_laporan']) {
                case 'dikirim':
                    if (empty($laporan->sent_at)) {
                        $laporan->update(['sent_at' => now()]);
                    }
                    break;
                case 'diverifikasi':
                    $laporan->update(['verified_at' => now()]);
                    break;
                case 'selesai':
                    $laporan->update(['completed_at' => now()]);
                    break;
            }
        }

        // ✅ mode API → gunakan resource
        if ($request->wantsJson() || $request->is('api/*')) {
            $laporan->load('lampiran', 'user');
            $laporan->refresh();
            return (new LaporanKejadianResource($laporan))
                ->additional(['message' => 'Laporan berhasil diperbarui'])
                ->response()
                ->setStatusCode(200);
        }

        // ✅ mode web
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * Hapus laporan.
     */
    public function destroy(LaporanKejadian $laporan, Request $request)
    {
        $this->authorize('delete', $laporan);
        $laporan->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Laporan berhasil dihapus.'], 200);
        }

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * 🔹 Cetak laporan ke PDF.
     */
    public function print(LaporanKejadian $laporan)
    {
        $this->authorize('view', $laporan);
        $laporan->load('lampiran');
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);
        return $pdf->stream('laporan-kejadian-' . $laporan->id . '.pdf');
    }
}