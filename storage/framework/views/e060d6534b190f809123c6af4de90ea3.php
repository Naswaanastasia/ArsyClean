<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'ArsyClean Laundry - Self Service Coin & Reguler'); ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col text-slate-800">

    <!-- Top Navbar -->
    <nav class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 border border-sky-500/20 p-1 flex items-center justify-center">
                    <img src="/src/assets/images/arsy_clean_logo_1793340486742.jpg" alt="Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg text-slate-900">ArsyClean</span>
                        </span>
                    </div>
                </div>
            </div>

           <div class="flex items-center gap-3">
    <?php if(auth()->guard()->check()): ?>
        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-1.5 rounded-2xl">
            <div class="w-7 h-7 rounded-xl overflow-hidden bg-sky-100 shrink-0">
                <img src="<?php echo e(Auth::user()->foto ?? '/src/assets/images/arsy_clean_mascot_1793340487175.jpg'); ?>" alt="User" class="w-full h-full object-cover">
            </div>
            
            <!-- Nama User saja (Role & teks bawah dihapus) -->
            <div class="text-left text-xs pr-1">
                <div class="font-bold text-slate-900 leading-tight">
                    <?php echo e(explode(' ', Auth::user()->nama)[0]); ?>

                </div>
            </div>

            <!-- Tombol Logout saja -->
            <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-3 py-1.5 bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 rounded-xl text-xs font-semibold">
                    Logout
                </button>
            </form>
        </div>
    <?php else: ?>

    <?php endif; ?>
</div>
    </nav>

    
<!-- Flash Notifications -->
<?php if(session('success')): ?>
    <div id="flash-success" class="max-w-7xl mx-auto px-4 mt-4 w-full transition-all duration-500">
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl">
            <?php echo e(session('success')); ?>

        </div>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div id="flash-error" class="max-w-7xl mx-auto px-4 mt-4 w-full transition-all duration-500">
        <div class="p-3 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl">
            <?php echo e(session('error')); ?>

        </div>
    </div>
<?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
        
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifications = [
            document.getElementById('flash-success'),
            document.getElementById('flash-error')
        ];

        notifications.forEach(function (notification) {
            if (notification) {
                setTimeout(function () {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';

                    setTimeout(function () {
                        notification.remove();
                    }, 500);
                }, 3000);
            }
        });
    });
</script>
</body>
</html>

</body>
</html>
<?php /**PATH C:\Users\HP\Downloads\arsyclean_laravel_project\laravel_project\resources\views/layouts/app.blade.php ENDPATH**/ ?>