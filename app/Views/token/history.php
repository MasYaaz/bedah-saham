<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 py-4 space-y-8">

    <div class="flex items-center gap-4 mb-2">
        <div class="bg-sky-400/10 p-4 rounded-2xl border border-sky-400/20 shadow-lg shadow-sky-500/5">
            <i data-lucide="receipt" class="text-sky-400 w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight mb-1">Riwayat Transaksi</h2>
            <p class="text-slate-400 text-sm md:text-base opacity-80">Daftar pembelian token analisis saham kamu.</p>
        </div>
    </div>

    <div class="relative overflow-hidden bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-3xl shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/2 border-b border-white/5">
                    <tr class="text-[11px] font-bold text-slate-500 uppercase tracking-[1.5px]">
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5">Paket</th>
                        <th class="px-6 py-5">Nominal</th>
                        <th class="px-6 py-5">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/3">
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <i data-lucide="history" class="w-12 h-12 mb-4 text-slate-500"></i>
                                    <p class="text-slate-400 text-sm">Belum ada riwayat transaksi.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $h): ?>
                            <tr class="hover:bg-white/1 transition-colors duration-200 group">
                                <td class="px-6 py-5 text-sm text-slate-400 group-hover:text-slate-300">
                                    <?= date('d M Y, H:i', strtotime($h->created_at)) ?>
                                </td>
                                <td class="px-6 py-5 font-semibold text-white tracking-wide">
                                    <?= $h->package_name ?>
                                </td>
                                <td class="px-6 py-5 text-slate-200 font-medium font-mono">
                                    <span class="text-slate-500 text-xs mr-0.5">Rp</span>
                                    <?= number_format($h->amount_paid, 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if ($h->status == 'success'): ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-[10px] font-bold tracking-widest uppercase rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.05)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                            SUCCESS
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-[10px] font-bold tracking-widest uppercase rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-[0_0_15px_rgba(245,158,11,0.05)]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                                            PENDING
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Pastikan Lucide Icons ter-render dengan benar
    lucide.createIcons();
</script>
<?= $this->endSection() ?>