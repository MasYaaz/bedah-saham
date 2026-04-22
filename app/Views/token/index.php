<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 py-4 space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-2xl">
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2">Pilih Paket Token</h1>
            <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                Isi ulang saldo untuk menggunakan fitur analisis mendalam DeepSeek AI.
            </p>
        </div>

        <div class="md:w-72 group">
            <div
                class="relative overflow-hidden p-5 rounded-2xl border border-sky-400/20 bg-slate-900/40 backdrop-blur-md shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-sky-400/40 hover:shadow-sky-500/10">

                <div
                    class="absolute -right-2 -top-2 h-20 w-20 pointer-events-none rounded-full bg-sky-500/15 blur-2xl transition-all duration-500 group-hover:scale-110 group-hover:bg-sky-500/25">
                </div>

                <div class="relative z-10 mb-2 flex items-center justify-between">
                    <small class="text-[10px]  uppercase tracking-[2px] text-slate-400">
                        Saldo Saat Ini
                    </small>
                    <span class="h-1.5 w-1.5 rounded-full bg-sky-500 shadow-[0_0_8px_rgba(56,189,248,0.6)]"></span>
                </div>

                <div class="relative z-10 flex items-end md:justify-end gap-2">
                    <div
                        class="mb-1 transition-transform duration-300 group-hover:scale-110 group-hover:-translate-y-0.5">
                        <i data-lucide="coins" class="h-6 w-6 text-sky-400"></i>
                    </div>

                    <span class="text-3xl font-black leading-none tracking-tighter text-white">
                        <?= number_format(session()->get('token_balance') ?? 0, 0, ',', '.') ?>
                    </span>

                    <span class="mb-0.5 text-xs font-medium lowercase text-slate-500">
                        Token
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
        <?php foreach ($packages as $p): ?>
            <div
                class="group relative flex flex-col h-full rounded-4xl border border-white/5 bg-slate-800/40 backdrop-blur-xl transition-all duration-500 hover:-translate-y-3 hover:bg-slate-800/70 hover:border-sky-400/30 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4),0_0_20px_rgba(56,189,248,0.1)] overflow-hidden">

                <div
                    class="absolute top-0 left-0 right-0 h-1 bg-linear-to-r from-transparent via-sky-400 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                </div>

                <div class="p-8 md:p-10 flex flex-col h-full">
                    <div class="text-center mb-8">
                        <span
                            class="inline-block px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[1.5px] text-sky-400 bg-sky-400/10 border border-sky-400/20 mb-4">
                            <?= strtoupper($p->package_name) ?>
                        </span>

                        <div class="flex items-baseline justify-center gap-1.5">
                            <span class="text-slate-500 text-lg font-medium">Rp</span>
                            <h2 class="text-5xl font-black text-white tracking-tighter">
                                <?= number_format($p->price, 0, ',', '.') ?>
                            </h2>
                        </div>
                    </div>

                    <div
                        class="mb-8 py-4 px-2 text-center rounded-2xl bg-slate-950/30 border border-white/3 group-hover:bg-slate-950/50 transition-colors">
                        <h4 class="text-2xl font-bold text-sky-400 leading-none mb-1"><?= $p->token_amount ?></h4>
                        <span class="block text-[9px] font-bold uppercase tracking-widest text-slate-500">Kredit Analisis
                            AI</span>
                    </div>

                    <ul class="space-y-4 mb-10 grow">
                        <?php
                        $features = [
                            ['icon' => 'zap', 'text' => 'Analisis Menggunakan AI'],
                            ['icon' => 'infinity', 'text' => 'Tanpa Batas Waktu (Lifetime)'],
                            ['icon' => 'shield-check', 'text' => 'Analisa dengan data terbaru']
                        ];
                        foreach ($features as $f): ?>
                            <li class="flex items-center gap-4 text-slate-400 group/item">
                                <div
                                    class="shrink-0 w-7 h-7 rounded-full bg-sky-400/10 border border-sky-400/10 flex items-center justify-center transition-colors group-hover:bg-sky-400/20 group-hover:border-sky-400/30">
                                    <i data-lucide="<?= $f['icon'] ?>" class="w-3.5 h-3.5 text-sky-400"></i>
                                </div>
                                <span class="text-sm font-medium tracking-wide"><?= $f['text'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <button data-url="<?= base_url('stock/token/buy/' . $p->id) ?>" data-package="<?= $p->package_name ?>"
                        data-price="<?= number_format($p->price, 0, ',', '.') ?>"
                        class="btn-confirm-buy w-full py-4 rounded-full font-bold text-sm tracking-wide transition-all duration-300 bg-white/5 text-sky-400 border border-sky-400/30 shadow-lg hover:bg-sky-400 hover:text-slate-950 hover:shadow-sky-500/40">
                        Beli Sekarang
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    lucide.createIcons();

    // Logika Konfirmasi Pembelian
    document.querySelectorAll('.btn-confirm-buy').forEach(button => {
        button.addEventListener('click', function (e) {
            const targetUrl = this.getAttribute('data-url');
            const packageName = this.getAttribute('data-package');
            const price = this.getAttribute('data-price');

            Swal.fire({
                title: 'Konfirmasi Pembelian',
                html: `<div class="text-sm py-2">Anda akan membeli paket <b>${packageName}</b><br>seharga <b class="text-emerald-400">Rp ${price}</b></div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#38bdf8',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Beli!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#ffffff',
                customClass: {
                    popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                    confirmButton: 'rounded-full px-6 py-2 font-bold',
                    cancelButton: 'rounded-full px-6 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mengarahkan ke pembayaran',
                        allowOutsideClick: false,
                        background: '#0f172a',
                        color: '#ffffff',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = targetUrl;
                }
            });
        });
    });

    // Handle Flash Data (SweetAlert2)
    const toastConfig = {
        background: '#0f172a',
        color: '#ffffff',
        customClass: { popup: 'rounded-2xl border border-white/10' }
    };

    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({ ...toastConfig, icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>' });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({ ...toastConfig, icon: 'error', title: 'Gagal', text: '<?= session()->getFlashdata('error') ?>' });
    <?php endif; ?>
</script>

<?= $this->endSection() ?>