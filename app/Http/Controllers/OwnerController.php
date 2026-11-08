<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class OwnerController extends Controller
{
    /**
     * Dashboard Eksekutif Owner
     */
    public function dashboard(Request $request)
    {
        $queryPembayaran = Pembayaran::where('status_pembayaran', 'Lunas');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $queryPembayaran->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $totalOmzet = (float) $queryPembayaran->sum('jumlah_bayar');

        $totalPesanan = Pesanan::count();
        $totalTransaksi = Pesanan::where('status_pesanan', 'Selesai')->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        $jumlahPickup = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Antar Jemput');
        })->count();

        $jumlahReguler = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Reguler');
        })->count();

        $jumlahKoin = Pesanan::whereHas('layanan', function ($query) {
            $query->where('jenis_layanan', 'Koin');
        })->count();

        $pesananSelesai = Pesanan::where('status_pesanan', 'Selesai')->count();
        $pesananDiproses = Pesanan::where('status_pesanan', 'Diproses')->count();
        $pesananDibatalkan = Pesanan::where('status_pesanan', 'Dibatalkan')->count();

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
     * Laporan Keuangan & Transaksi
     */
    public function laporan(Request $request)
    {
        $query = Pembayaran::with(['pesanan.user', 'pesanan.layanan']);

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $pembayarans = $query->orderByDesc('id_pembayaran')->get();

        $totalOmzet = (float) $pembayarans
            ->where('status_pembayaran', 'Lunas')
            ->sum('jumlah_bayar');

        return view('owner.laporan', compact('pembayarans', 'totalOmzet'));
    }

    /**
     * Halaman Pengelolaan Layanan
     */
    public function layanan()
    {
        $layanans = Layanan::orderBy('id_layanan')->get();

        return view('owner.layanan', compact('layanans'));
    }

    /**
     * Daftar Akun Admin
     */
    public function adminIndex()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('id_user', 'desc')
            ->get();

        return view('owner.admin', compact('admins'));
    }

    /**
     * Menambahkan Akun Admin
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ], [
            'nama.required' => 'Nama admin wajib diisi.',
            'email.required' => 'Alamat email admin wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'no_hp.required' => 'Nomor WhatsApp / HP admin wajib diisi.',
            'no_hp.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'no_hp.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 15 digit.',
            'alamat.required' => 'Alamat domisili admin wajib diisi.',
            'password.required' => 'Password admin wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $nextId = (int) (User::max('id_user') ?? 0) + 1;

        $kodeUser = 'ADM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        User::create([
            'kode_user' => $kodeUser,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        return redirect()->route('owner.admin.index')
            ->with('success', "Akun Admin baru ({$request->nama}) berhasil ditambahkan dengan kode {$kodeUser}.");
    }

    /**
     * MENGHAPUS AKUN ADMIN
     */
    public function destroyAdmin($idAdmin)
    {
        try {
            $admin = User::where('id_user', $idAdmin)
                ->where('role', 'admin')
                ->first();

            if (!$admin) {
                return redirect()->route('owner.admin.index')
                    ->with('error', 'Akun admin tidak ditemukan atau sudah dihapus.');
            }

            if (auth()->id() == $admin->id_user) {
                return redirect()->route('owner.admin.index')
                    ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
            }

            $namaAdmin = $admin->nama;
            $admin->delete();

            return redirect()->route('owner.admin.index')
                ->with('success', "Akun Admin {$namaAdmin} berhasil dihapus.");

        } catch (QueryException $e) {
            return redirect()->route('owner.admin.index')
                ->with('error', 'Akun admin tidak dapat dihapus karena masih memiliki data yang terhubung di database.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('owner.admin.index')
                ->with('error', 'Terjadi kesalahan saat menghapus akun admin. Silakan periksa log Laravel.');
        }
    }

    /**
     * Mengatur Tarif & Status Layanan (Update Quick)
     */
    public function updateLayanan(Request $request, $idLayanan)
    {
        $request->validate([
            'harga'  => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        $layanan = Layanan::findOrFail($idLayanan);
        $layanan->update([
            'harga'  => $request->harga,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', "Data tarif & status layanan {$layanan->nama_layanan} berhasil diperbarui.");
    }

    /**
     * Menambahkan Layanan Baru
     */
    public function storeLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan'  => 'required|string|max:255',
            'harga'         => 'required|numeric|min:0',
            'satuan'        => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:50',
            'status'        => 'required|in:tersedia,tidak_tersedia',
        ]);

        $nextId = (int) (Layanan::max('id_layanan') ?? 0) + 1;
        $kodeLayanan = 'L' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Layanan::create([
            'kode_layanan'  => $kodeLayanan,
            'nama_layanan'  => $request->nama_layanan,
            'harga'         => $request->harga,
            'satuan'        => $request->satuan,
            'jenis_layanan' => $request->jenis_layanan,
            'status'        => $request->status,
        ]);

        return redirect()->back()->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    /**
     * Menghapus Layanan
     */
    public function destroyLayanan($idLayanan)
    {
        try {
            $layanan = Layanan::findOrFail($idLayanan);
            $namaLayanan = $layanan->nama_layanan;
            $layanan->delete();

            return redirect()->back()->with('success', "Layanan {$namaLayanan} berhasil dihapus.");
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Layanan tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }
    }

    /**
     * Cetak Laporan PDF
     */
    public function cetakLaporanPdf(Request $request)
    {
        $queryPembayaran = Pembayaran::with(['pesanan.user', 'pesanan.layanan'])
            ->where('status_pembayaran', 'Lunas');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $queryPembayaran->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $pembayarans = $queryPembayaran
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        $totalOmzet = (float) $pembayarans->sum('jumlah_bayar');

        return view('owner.cetak_laporan', compact(
            'pembayarans',
            'totalOmzet'
        ));
    }
}