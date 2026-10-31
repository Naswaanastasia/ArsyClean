<?php $__env->startSection('title', 'Dashboard Eksekutif Owner - Laravel ArsyClean'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Executive Omzet Banner -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 uppercase tracking-wider mb-2 text-amber-100">
                👑 Panel Pemilik Usaha (Owner)
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">Laporan Omzet & Keuangan Laundry</h1>
            <p class="text-xs text-amber-100 max-w-xl leading-relaxed mt-1">
                Pantau kesehatan arus kas, omzet bulanan riil, volume transaksi, dan pengaturan tarif layanan.
            </p>
        </div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/20 text-right min-w-56">
            <div class="text-xs text-amber-200 uppercase font-semibold">Total Omzet Masuk</div>
            <div class="text-3xl font-black text-white mt-1">Rp <?php echo e(number_format($totalOmzet, 0, ',', '.')); ?></div>
            <div class="text-[10px] text-amber-200 mt-1">Performa Bisnis Sehat &amp; Bertumbuh</div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Total Pelanggan Terdaftar</div>
            <div class="text-2xl font-black text-slate-900 mt-1"><?php echo e($totalPelanggan); ?> Pengguna</div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1">&uarr; Pertumbuhan Loyalitas</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Transaksi Selesai</div>
            <div class="text-2xl font-black text-slate-900 mt-1"><?php echo e($totalTransaksi); ?> Pesanan</div>
            <div class="text-[10px] text-sky-600 font-bold mt-1">Tingkat Penyelesaian 98.4%</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs text-slate-400 font-semibold">Estimasi Margin Laba Bersih</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">38.5%</div>
            <div class="text-[10px] text-slate-400 mt-1">Setelah HPP Deterjen &amp; Utilitas</div>
        </div>
    </div>

    <!-- Service Catalog Price Adjustment -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Tarif Layanan & Status Operasional</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Nama Layanan</th>
                        <th class="p-3">Jenis</th>
                        <th class="p-3">Tarif Satuan (Rp)</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $layanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3 font-mono font-bold text-slate-900"><?php echo e($layanan->kode_layanan); ?></td>
                        <td class="p-3 font-semibold text-slate-800"><?php echo e($layanan->nama_layanan); ?></td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                <?php echo e($layanan->jenis_layanan); ?>

                            </span>
                        </td>
                        <form action="<?php echo e(route('owner.layanan.update', $layanan->id_layanan)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <td class="p-3">
                                <div class="flex items-center gap-1">
                                    <span class="text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="harga" value="<?php echo e($layanan->harga); ?>" class="w-24 px-2 py-1 bg-white border rounded font-bold">
                                    <span class="text-[10px] text-slate-400">/ <?php echo e($layanan->satuan); ?></span>
                                </div>
                            </td>
                            <td class="p-3">
                                <select name="status" class="px-2 py-1 bg-white border rounded text-xs">
                                    <option value="tersedia" <?php echo e($layanan->status === 'tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                                    <option value="tidak_tersedia" <?php echo e($layanan->status === 'tidak_tersedia' ? 'selected' : ''); ?>>Nonaktif</option>
                                </select>
                            </td>
                            <td class="p-3">
                                <button type="submit" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-xs shadow-xs">
                                    Simpan Perubahan
                                </button>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\arsyclean_laravel_project\laravel_project\resources\views/owner/dashboard.blade.php ENDPATH**/ ?>