<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    /**
     * Dashboard Eksekutif Owner
     */
    public function dashboard(Request $request)
    {
        // 1. Query Dasar Pembayaran Lunas dengan Filter Tanggal
        $queryPembayaran = Pembayaran::where('status_pembayaran', 'Lunas');

        $queryPembayaran = $this->terapkanFilterTanggal(
            $queryPembayaran,
            $request
        );

        // Total omzet berdasarkan filter / keseluruhan
        $totalOmzet = (float) $queryPembayaran->sum('jumlah_bayar');

        // 2. Statistik Volume Usaha
        $totalPesanan = Pesanan::count();
        $totalTransaksi = Pesanan::where('status_pesanan', 'Selesai')->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // 3. Memantau Aktivitas Layanan: Pickup, Reguler, dan Koin
        $jumlahPickup = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Antar Jemput');
        })->count();

        $jumlahReguler = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Reguler');
        })->count();

        $jumlahKoin = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Koin');
        })->count();

        // 4. Statistik status pesanan
        $pesananSelesai = Pesanan::where('status_pesanan', 'Selesai')->count();
        $pesananDiproses = Pesanan::where('status_pesanan', 'Diproses')->count();
        $pesananDibatalkan = Pesanan::where('status_pesanan', 'Dibatalkan')->count();

        // 5. Riwayat Pembayaran & Layanan
        $pembayarans = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        $layanans = Layanan::all();

        return view('owner.dashboard', compact(
            'totalOmzet',
            'totalPesanan',
            'totalTransaksi',
            'totalPelanggan',
            'totalAdmin',
            'jumlahPickup',
            'jumlahReguler',
            'jumlahKoin',
            'pesananSelesai',
            'pesananDiproses',
            'pesananDibatalkan',
            'pembayarans',
            'layanans'
        ));
    }

    /**
     * Halaman Khusus: Laporan Keuangan & Transaksi
     */
    public function laporan(Request $request)
    {
        $query = Pembayaran::with(['pesanan.user', 'pesanan.layanan']);

        $query = $this->terapkanFilterTanggal($query, $request);

        $pembayarans = $query->orderByDesc('id_pembayaran')->get();
        $totalOmzet = (float) $pembayarans->where('status_pembayaran', 'Lunas')->sum('jumlah_bayar');
        return view('owner.laporan', compact('pembayarans', 'totalOmzet'));
    }

    /**
     * Halaman Khusus: Tarif & Monitoring Layanan
     */
    public function layanan()
    {
        $layanans = Layanan::orderBy('id_layanan')->get();
        return view('owner.layanan', compact('layanans'));
    }


    /**
     * Halaman Khusus Pengelolaan Akun Admin (Dipisahkan dari Dashboard)
     */
    public function adminIndex()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('id_user', 'desc')
            ->get();

        return view('owner.admin', compact('admins'));
    }

    /**
     * Menambah Akun Admin Baru (Langkah 6: Mengelola Admin)
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email',
            'no_hp'    => 'required|numeric|digits_between:10,15',
            'alamat'   => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ], [
            'nama.required'     => 'Nama admin wajib diisi.',
            'email.required'    => 'Alamat email admin wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Alamat email sudah terdaftar.',
            'no_hp.required'    => 'Nomor WhatsApp / HP admin wajib diisi.',
            'no_hp.numeric'     => 'Nomor WhatsApp harus berupa angka numerik.',
            'no_hp.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 15 digit.',
            'alamat.required'   => 'Alamat domisili admin wajib diisi.',
            'password.required' => 'Password admin wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
        ]);

        $nextId = (int) (User::max('id_user') ?? 0) + 1;
        $kodeUser = 'ADM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        User::create([
            'kode_user' => $kodeUser,
            'nama'      => $request->nama,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'alamat'    => $request->alamat,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',
            'status'    => 'aktif',
        ]);

        return redirect()->route('owner.admin.index')
            ->with('success', "Akun Admin baru ({$request->nama}) berhasil ditambahkan dengan kode {$kodeUser}.");
    }

    /**
     * Menghapus Akun Admin
     */
    public function destroyAdmin($idAdmin)
    {
        $admin = User::where('role', 'admin')->findOrFail($idAdmin);
        $namaAdmin = $admin->nama;
        $admin->delete();

        return redirect()->route('owner.admin.index')
            ->with('success', "Akun Admin ({$namaAdmin}) berhasil dihapus.");
    }

    /**
     * Mengatur Tarif & Status Layanan
     */
    public function updateLayanan(Request $request, $idLayanan)
    {
        $request->validate([
            'harga'  => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ], [
            'harga.required'  => 'Tarif layanan wajib diisi.',
            'harga.numeric'   => 'Tarif layanan harus berupa angka.',
            'harga.min'       => 'Tarif layanan minimal Rp 0.',
            'status.required' => 'Status operasional layanan wajib dipilih.',
            'status.in'       => 'Pilihan status layanan tidak valid.',
        ]);

        $layanan = Layanan::findOrFail($idLayanan);
        $layanan->harga = $request->harga;
        $layanan->status = $request->status;
        $layanan->save();

        return redirect()->route('owner.dashboard')
            ->with('success', "Tarif & status layanan {$layanan->nama_layanan} berhasil diperbarui.");
    }

    /**
     * Cetak Laporan PDF Laporan Omzet & Keuangan
     */
    public function cetakLaporanPdf(Request $request)
    {
        $queryPembayaran = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->where('status_pembayaran', 'Lunas');

        $queryPembayaran = $this->terapkanFilterTanggal(
            $queryPembayaran,
            $request
        );

        $pembayarans = $queryPembayaran->orderBy('id_pembayaran', 'desc')->get();
        $totalOmzet = (float) $pembayarans->sum('jumlah_bayar');

        return view('owner.cetak_laporan', compact('pembayarans', 'totalOmzet'));
    }

    /**
     * Menerapkan filter tanggal pada query pembayaran.
     */
    private function terapkanFilterTanggal($query, Request $request)
    {
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        return $query;
    }
}