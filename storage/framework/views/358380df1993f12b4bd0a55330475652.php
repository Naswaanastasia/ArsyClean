<?php $__env->startSection('title', 'Login - ArsyClean Laundry'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[85vh] flex items-center justify-center p-4 bg-slate-50">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden flex flex-col md:flex-row">

        <!-- BAGIAN KIRI: BRANDING -->
        <div class="md:w-5/12 p-8 text-white bg-gradient-to-br from-rose-500 via-sky-600 to-indigo-700 flex flex-col justify-between">

            <div>
                <div class="w-16 h-16 rounded-2xl bg-white/20 p-2 mb-5 flex items-center justify-center">
                    <img
                        src="/src/assets/images/arsy_clean_logo_1793340486742.jpg"
                        alt="Logo ArsyClean"
                        class="w-full h-full object-contain rounded-xl"
                    >
                </div>

                <h1 class="text-3xl font-black tracking-wide">
                    ARSY CLEAN
                </h1>

                <p class="text-sm text-white/80 mt-2">
                    Sistem Pengelolaan Laundry Coin & Reguler
                </p>

                <p class="text-sm text-white/90 leading-relaxed mt-6">
                    Selamat datang di ArsyClean. Masuk ke akun Anda untuk menikmati layanan laundry dengan mudah, praktis, dan nyaman.
                </p>
            </div>

            <div class="mt-8 rounded-2xl overflow-hidden border-2 border-white/20 bg-white/10 p-2">
                <img
                    src="/src/assets/images/arsy_clean_mascot_1793340487175.jpg"
                    alt="Maskot ArsyClean"
                    class="w-full h-40 object-cover rounded-xl"
                >
            </div>

            <p class="text-xs text-white/70 mt-5">
                © <?php echo e(date('Y')); ?> ArsyClean Laundry. All rights reserved.
            </p>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="flex-1 p-6 sm:p-10 flex flex-col justify-center">

            <div class="mb-8">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-widest">
                    Selamat Datang
                </span>

                <h2 class="text-3xl font-black text-slate-800 mt-2">
                    Login Akun
                </h2>

                <p class="text-sm text-slate-500 mt-2">
                    Masukkan email dan kata sandi untuk melanjutkan.
                </p>
            </div>

            <!-- PESAN ERROR -->
            <?php if(session('error')): ?>
                <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <!-- PESAN SUKSES -->
            <?php if(session('success')): ?>
                <div class="mb-5 p-3 rounded-xl bg-green-50 border border-green-200 text-green-600 text-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- PESAN VALIDASI -->
            <?php if($errors->any()): ?>
                <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                    <ul class="list-disc pl-5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- FORM LOGIN -->
            <form action="<?php echo e(route('login.process')); ?>" method="POST" class="space-y-5">

                <?php echo csrf_field(); ?>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        placeholder="Masukkan email Anda"
                        required
                        autofocus
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Kata Sandi
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl shadow-md transition-all"
                >
                    Masuk ke Akun →
                </button>

            </form>

            <!-- LINK REGISTRASI -->
            <div class="text-center mt-7 pt-6 border-t border-slate-100">

                <p class="text-sm text-slate-500">
                    Belum memiliki akun?
                    <a
                        href="<?php echo e(route('register')); ?>"
                        class="text-sky-600 font-bold hover:underline"
                    >
                        Daftar Sekarang
                    </a>
                </p>

            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Downloads\arsyclean_laravel_project\laravel_project\resources\views/auth/login.blade.php ENDPATH**/ ?>