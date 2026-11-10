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
            'totalOmzet', 'totalPesanan', 'totalTransaksi', 'totalPelanggan',
            'totalAdmin', 'jumlahPickup', 'jumlahReguler', 'jumlahKoin',
            'pesananSelesai', 'pesananDiproses', 'pesananDibatalkan',
            'pembayarans', 'layanans'
        ));
    }

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
        $totalOmzet = (float) $pembayarans->where('status_pembayaran', 'Lunas')->sum('jumlah_bayar');
        return view('owner.laporan', compact('pembayarans', 'totalOmzet'));
    }

    public function layanan()
    {
        $layanans = Layanan::all();
        return view('owner.layanan', compact('layanans'));
    }

    // CREATE: Tambah Layanan Baru (Satu saja, tidak ganda)
    public function storeLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
        ]);

        $maxId = (int) (Layanan::max('id_layanan') ?? Layanan::max('id') ?? 0);
        $nextId = $maxId + 1;
        $kodeLayanan = 'L' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Layanan::create([
            'kode_layanan'  => $kodeLayanan,
            'nama_layanan'  => $request->nama_layanan,
            'jenis_layanan' => $request->jenis_layanan ?? 'Reguler',
            'satuan'        => $request->satuan ?? 'Kg',
            'harga'         => $request->harga,
            'status'        => 'tersedia',
        ]);

        return redirect()->route('owner.layanan')
            ->with('success', "Layanan ({$request->nama_layanan}) berhasil ditambahkan.");
    }

    // UPDATE: Perbarui Tarif & Status
    public function updateLayanan(Request $request, $idLayanan)
    {
        $request->validate([
            'harga'  => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $layanan = Layanan::where('id_layanan', $idLayanan)->first() 
                    ?? Layanan::where('id', $idLayanan)->first()
                    ?? Layanan::findOrFail($idLayanan);

        $layanan->harga = $request->harga;
        $statusInput = strtolower($request->status);
        $layanan->status = ($statusInput === 'nonaktif' || $statusInput === 'tidak_tersedia') ? 'tidak_tersedia' : 'tersedia';
        $layanan->save();

        return redirect()->route('owner.layanan')
            ->with('success', "Tarif & status layanan {$layanan->nama_layanan} berhasil diperbarui.");
    }

    // DELETE: Hapus Layanan
    public function destroyLayanan($idLayanan)
    {
        $layanan = Layanan::where('id_layanan', $idLayanan)->first() 
                    ?? Layanan::where('id', $idLayanan)->first()
                    ?? Layanan::findOrFail($idLayanan);
                    
        $namaLayanan = $layanan->nama_layanan;
        $layanan->delete();

        return redirect()->route('owner.layanan')
            ->with('success', "Layanan ({$namaLayanan}) berhasil dihapus.");
    }

    public function adminIndex()
    {
        $admins = User::where('role', 'admin')->orderBy('id_user', 'desc')->get();
        return view('owner.admin', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email',
            'no_hp'    => 'required|numeric|digits_between:10,15',
            'alamat'   => 'required|string|max:255',
            'password' => 'required|string|min:6',
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
            ->with('success', "Akun Admin ({$request->nama}) berhasil ditambahkan.");
    }

    public function destroyAdmin($idAdmin)
    {
        $admin = User::where('role', 'admin')->findOrFail($idAdmin);
        $namaAdmin = $admin->nama;
        $admin->delete();

        return redirect()->route('owner.admin.index')
            ->with('success', "Akun Admin ({$namaAdmin}) berhasil dihapus.");
    }

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

        $pembayarans = $queryPembayaran->orderBy('id_pembayaran', 'desc')->get();
        $totalOmzet = (float) $pembayarans->sum('jumlah_bayar');

        return view('owner.cetak_laporan', compact('pembayarans', 'totalOmzet'));
    }
}