<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\Lampiran;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Jika request berasal dari API (Accept: application/json atau path /api/)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($laporanKejadian, 200);
        }

        // Web mode tetap berjalan
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
            'nama_pelapor'        => 'required|string|max:255',
            'jabatan_pelapor'     => 'required|string|max:255',
            'telepon_pelapor'     => 'required|string|max:20',
            'jenis_kapal'         => 'required|string|max:255',
            'nama_kapal'          => 'required|string|max:255',
            'nama_kapal_kedua'    => 'nullable|string|max:255',
            'bendera_kapal'       => 'required|string|max:100',
            'grt_kapal'           => 'required|integer',
            'imo_number'          => 'nullable|string|max:100',
            'pelabuhan_asal'      => 'required|string|max:255',
            'waktu_berangkat'     => 'required|date',
            'pelabuhan_tujuan'    => 'required|string|max:255',
            'estimasi_tiba'       => 'required|date',
            'pemilik_kapal'       => 'required|string|max:255',
            'kontak_pemilik'      => 'required|string|max:20',
            'agen_lokal'          => 'required|string|max:255',
            'kontak_agen'         => 'required|string|max:20',
            'nama_pandu'          => 'nullable|string|max:255',
            'nomor_register_pandu'=> 'nullable|string|max:255',
            'jenis_muatan'        => 'required|string',
            'jumlah_muatan'       => 'required|string|max:100',
            'jumlah_penumpang'    => 'required|integer',
            'posisi_lintang'      => 'required|string|max:50',
            'posisi_bujur'        => 'required|string|max:50',
            'tanggal_laporan'     => 'required|date',
            'isi_laporan'         => 'required|string',
            // opsional field baru
            'jenis_kecelakaan'    => 'nullable|string|max:255',
            'pihak_terkait'       => 'nullable|string|max:255',
            'lampiran.*'          => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,webm|max:20480',
        ]);

        $dataToStore = $validatedData;
        $dataToStore['user_id'] = auth()->id();

        $laporan = LaporanKejadian::create($dataToStore);

        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('lampiran', 'public');
                $tipe = str_starts_with($file->getMimeType(), 'image') ? 'foto' : 'video';
                Lampiran::create([
                    'laporan_id' => $laporan->id,
                    'tipe_file'  => $tipe,
                    'path_file'  => $path,
                ]);
            }
        }

        // Mode API → return JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            $laporan->load('lampiran', 'user');
            return response()->json([
                'message' => 'Laporan kejadian berhasil dikirim!',
                'laporan' => $laporan,
            ], 201);
        }

        // Mode WEB → redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Laporan kejadian berhasil dikirim!');
    }

    /**
     * Menampilkan detail laporan.
     */
    public function show(LaporanKejadian $laporan, Request $request)
    {
        $this->authorize('view', $laporan);
        $laporan->load('lampiran', 'user');

        // Mode API → JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($laporan, 200);
        }

        // Mode WEB → tampilkan view
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
            'nama_pelapor'        => 'required|string|max:255',
            'jabatan_pelapor'     => 'required|string|max:255',
            'telepon_pelapor'     => 'required|string|max:20',
            'jenis_kapal'         => 'required|string|max:255',
            'nama_kapal'          => 'required|string|max:255',
            'nama_kapal_kedua'    => 'nullable|string|max:255',
            'bendera_kapal'       => 'required|string|max:100',
            'grt_kapal'           => 'required|integer',
            'imo_number'          => 'nullable|string|max:100',
            'pelabuhan_asal'      => 'required|string|max:255',
            'waktu_berangkat'     => 'required|date',
            'pelabuhan_tujuan'    => 'required|string|max:255',
            'estimasi_tiba'       => 'required|date',
            'pemilik_kapal'       => 'required|string|max:255',
            'kontak_pemilik'      => 'required|string|max:20',
            'agen_lokal'          => 'required|string|max:255',
            'kontak_agen'         => 'required|string|max:20',
            'nama_pandu'          => 'nullable|string|max:255',
            'nomor_register_pandu'=> 'nullable|string|max:255',
            'jenis_muatan'        => 'required|string',
            'jumlah_muatan'       => 'required|string|max:100',
            'jumlah_penumpang'    => 'required|integer',
            'posisi_lintang'      => 'required|string|max:50',
            'posisi_bujur'        => 'required|string|max:50',
            'tanggal_laporan'     => 'required|date',
            'isi_laporan'         => 'required|string',
            'jenis_kecelakaan'    => 'nullable|string|max:255',
            'pihak_terkait'       => 'nullable|string|max:255',
        ]);

        $laporan->update($validatedData);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Laporan berhasil diperbarui',
                'laporan' => $laporan->load('lampiran', 'user')
            ], 200);
        }

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