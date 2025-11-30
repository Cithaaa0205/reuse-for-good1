<?php

namespace App\Http\Controllers;

use App\Models\BarangDonasi;
use App\Models\Kategori;
use App\Models\RequestBarang;
use App\Services\AutoCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BarangDonasiController extends Controller
{
    /**
     * Service untuk auto-kategori (OpenAI + rule-based).
     *
     * @var \App\Services\AutoCategoryService
     */
    protected AutoCategoryService $autoCategory;

    public function __construct(AutoCategoryService $autoCategory)
    {
        $this->autoCategory = $autoCategory;
    }

    /**
     * Menampilkan daftar barang donasi yang tersedia (etalase).
     */
    public function index(Request $request)
    {
        $kategoris = Kategori::all();

        // Hanya tampilkan barang yang tersedia & tidak di-hide admin
        $query = BarangDonasi::with(['kategori', 'donatur'])
            ->where('status', 'Tersedia')
            ->where('is_hidden', false);

        $user = Auth::user();

        // Dipakai di view untuk menandai apakah filter / search aktif
        $isSearchActive = $request->filled('search')
            || $request->filled('kategori')
            || $request->filled('jarak')
            || $request->filled('filter_provinsi')
            || $request->filled('filter_kabupaten');

        // ==========================
        // FILTER PENCARIAN
        // ==========================
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('provinsi', 'like', "%{$keyword}%")
                    ->orWhere('kabupaten', 'like', "%{$keyword}%")
                    ->orWhereHas('kategori', function ($qc) use ($keyword) {
                        $qc->where('nama_kategori', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('kategori')) {
            $kategoriSlug = $request->kategori;
            $query->whereHas('kategori', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        // ==========================
        // FILTER LOKASI
        // ==========================
        if ($request->filled('filter_provinsi')) {
            $query->where('provinsi', $request->filter_provinsi);
        }

        if ($request->filled('filter_kabupaten')) {
            $query->where('kabupaten', $request->filter_kabupaten);
        }

        // ==========================
        // FILTER & URUTKAN BERDASARKAN JARAK
        // ==========================
        $hasCoordinates = $user && $user->latitude && $user->longitude;

        if ($hasCoordinates) {
            $lat = $user->latitude;
            $lng = $user->longitude;

            // Hitung jarak (km) ke lokasi user
            $query->selectRaw("
                barang_donasis.*,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat]);

            // Jika user memilih filter jarak
            if ($request->filled('jarak')) {
                $query->having('distance', '<=', $request->jarak)
                    ->orderBy('distance');
            }
        }

        // ==========================
        // PRIORITAS LOKASI SAAT TIDAK PAKAI FILTER JARAK
        // ==========================
        if ($user && !$request->filled('jarak')) {
            if ($user->kabupaten && $user->provinsi) {
                $query->orderByRaw("
                    CASE 
                        WHEN kabupaten = ? AND provinsi = ? THEN 0
                        WHEN provinsi = ? THEN 1
                        ELSE 2
                    END,
                    created_at DESC
                ", [
                    $user->kabupaten,
                    $user->provinsi,
                    $user->provinsi,
                ]);
            } elseif ($user->provinsi) {
                $query->orderByRaw("
                    CASE 
                        WHEN provinsi = ? THEN 0
                        ELSE 1
                    END,
                    created_at DESC
                ", [
                    $user->provinsi,
                ]);
            } else {
                $query->latest();
            }
        }

        // Kalau tidak login atau pakai filter jarak & belum ada order lain → pakai newest
        if ((!$user || $request->filled('jarak')) && empty($query->getQuery()->orders)) {
            $query->latest();
        }

        $barang = $query->paginate(20);

        // ==========================
        // FAVORIT USER LOGIN
        // ==========================
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = $user->favorites()
                ->pluck('barang_donasis.id')
                ->toArray();
        }

        // Label lokasi user untuk tampilan
        $userLocationLabel = null;
        if ($user && ($user->kabupaten || $user->provinsi)) {
            $userLocationLabel = trim(
                ($user->kabupaten ? $user->kabupaten . ', ' : '') .
                ($user->provinsi ?? '')
            );
        }

        return view('barang.index', [
            'barang'            => $barang,
            'kategoris'         => $kategoris,
            'favoriteIds'       => $favoriteIds,
            'isSearchActive'    => $isSearchActive,
            'userLocationLabel' => $userLocationLabel,
            'hasCoordinates'    => $hasCoordinates,
        ]);
    }

    /**
     * Form buat input barang baru.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Simpan barang donasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'          => 'required|string|max:255',
            'deskripsi'            => 'required|string',
            'kategori_id'          => 'nullable|exists:kategoris,id',
            'kondisi'              => 'required|string',
            'provinsi'             => 'required|string',
            'kabupaten'            => 'required|string',
            'foto_barang'          => 'nullable|array',
            'foto_barang.*'        => 'image|mimes:jpeg,png,jpg|max:10240',
            'catatan_pengambilan'  => 'nullable|string',
        ]);

        // ==========================
        // AUTO KATEGORI (jika user tidak pilih)
        // ==========================
        $kategoriId = $validated['kategori_id'] ?? null;

        if (!$kategoriId) {
            $kategoriId = $this->autoCategory->guessCategoryId(
                $validated['nama_barang'],
                $validated['deskripsi']
            );
        }

        if (!$kategoriId) {
            $fallback = Kategori::where('nama_kategori', 'like', '%lain%')->first()
                ?? Kategori::orderBy('id')->first();

            if ($fallback) {
                $kategoriId = $fallback->id;
            }
        }

        if (!$kategoriId) {
            return back()
                ->withErrors([
                    'kategori_id' => 'Kategori belum tersedia di sistem. Tambahkan minimal satu kategori terlebih dahulu.',
                ])
                ->withInput();
        }

        // ==========================
        // UPLOAD FOTO
        // ==========================
        $fotoUtama = null;
        $fotoLain  = [];

        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $index => $file) {
                $namaFile = time() . '_' .
                    Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
                    '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads/barang'), $namaFile);

                if ($index === 0) {
                    $fotoUtama = $namaFile;
                } else {
                    $fotoLain[] = $namaFile;
                }
            }
        }

        BarangDonasi::create([
            'donatur_id'          => Auth::id(),
            'kategori_id'         => $kategoriId,
            'nama_barang'         => $validated['nama_barang'],
            'deskripsi'           => $validated['deskripsi'],
            'kondisi'             => $validated['kondisi'],
            'provinsi'            => $validated['provinsi'],
            'kabupaten'           => $validated['kabupaten'],
            'foto_barang_utama'   => $fotoUtama,
            'foto_barang_lainnya' => json_encode($fotoLain),
            'catatan_pengambilan' => $validated['catatan_pengambilan'] ?? null,
            'status'              => 'Tersedia',
            'is_hidden'           => false,
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Donasi berhasil diposting! Kategori sudah diisi secara otomatis oleh sistem.');
    }

    /**
     * Detail satu barang donasi.
     */
    public function show(BarangDonasi $barang)
    {
        // Jika barang disembunyikan admin → hanya admin & pemilik yang boleh lihat
        if ($barang->is_hidden) {
            if (!Auth::check()) {
                abort(404);
            }

            $user   = Auth::user();
            $isAdmin = $user->role === 'admin';
            $isOwner = $user->id === $barang->donatur_id;

            if (!$isAdmin && !$isOwner) {
                abort(404);
            }
        }

        $barang->load('donatur', 'kategori');

        // Barang serupa (kategori sama, status tersedia & tidak di-hide)
        $barangSerupa = BarangDonasi::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $barang->id)
            ->where('status', 'Tersedia')
            ->where('is_hidden', false)
            ->take(5)
            ->get();

        // Status request user saat ini terhadap barang ini (jika ada)
        $requestStatus = null;

        if (Auth::check()) {
            $existing = RequestBarang::where('barang_donasi_id', $barang->id)
                ->where('penerima_id', Auth::id())
                ->first();

            $requestStatus = $existing->status ?? null;
        }

        return view('barang.show', compact('barang', 'barangSerupa', 'requestStatus'));
    }

    /**
     * Hapus barang (hanya pemilik / donatur).
     */
    public function destroy(BarangDonasi $barang)
    {
        if (Auth::id() !== $barang->donatur_id) {
            return back()->with('error', 'Anda tidak berhak menghapus donasi ini.');
        }

        // Hapus foto utama
        if ($barang->foto_barang_utama) {
            $pathUtama = public_path('uploads/barang/' . $barang->foto_barang_utama);
            if (File::exists($pathUtama)) {
                File::delete($pathUtama);
            }
        }

        // Hapus foto lain
        if ($barang->foto_barang_lainnya) {
            foreach (json_decode($barang->foto_barang_lainnya, true) as $foto) {
                $path = public_path('uploads/barang/' . $foto);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }

        $barang->delete();

        return redirect()
            ->route('profile.show', Auth::user()->username)
            ->with('success', 'Donasi berhasil dihapus.');
    }
}
