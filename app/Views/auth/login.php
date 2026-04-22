<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col items-center justify-center min-h-[70vh] px-4">
    <div class="w-full max-w-md">
        <div
            class="relative overflow-hidden bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-4xl shadow-2xl transition-all duration-300">

            <div
                class="absolute -top-24 -right-24 w-48 h-48 bg-sky-500/10 rounded-full blur-[80px] pointer-events-none">
            </div>

            <div class="p-8 md:p-10 relative z-10">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-sky-500/10 rounded-full border border-sky-500/20 mb-4">
                        <i data-lucide="lock" class="text-sky-400 w-6 h-6"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white tracking-tight uppercase">Masuk Akun</h3>
                    <p class="text-slate-500 text-xs font-medium mt-1">Analisis saham lebih dalam dengan AI</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div
                        class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-center text-xs font-bold animate-pulse">
                        <i data-lucide="alert-circle" class="inline-block w-4 h-4 mr-1 -mt-0.5"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="POST" class="space-y-5">
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Username
                            atau Email</label>
                        <div class="relative group">
                            <i data-lucide="user"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                            <input type="text" name="login_identity"
                                class="w-full bg-slate-900/50 border border-white/10 rounded-2xl py-3 pl-11 pr-4 text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-medium text-sm"
                                placeholder="Username / Email" required>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Password</label>
                        <div class="relative group">
                            <i data-lucide="key-round"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                            <input type="password" name="password"
                                class="w-full bg-slate-900/50 border border-white/10 rounded-2xl py-3 pl-11 pr-4 text-slate-100 focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all font-medium text-sm"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-sky-400 hover:bg-sky-300 text-slate-950 font-black py-3.5 rounded-2xl shadow-lg shadow-sky-500/20 transition-all active:scale-[0.98] mt-4 uppercase text-xs tracking-widest">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[11px] text-slate-500 font-medium">
                        Belum punya akun?
                        <a href="<?= base_url('register') ?>"
                            class="text-sky-400 hover:text-sky-300 font-bold transition-colors ml-1">
                            Daftar & Bonus 5 Token
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>