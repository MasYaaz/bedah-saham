<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
date_default_timezone_set('Asia/Jakarta');
$now = new DateTime();
$day = (int) $now->format('N');
$time = $now->format('H:i');

$is_open = false;
if ($day >= 1 && $day <= 5) {
    $is_friday = ($day == 5);
    $session1_end = $is_friday ? '11:30' : '12:00';
    $in_session1 = ($time >= '09:00' && $time <= $session1_end);
    $in_session2 = ($time >= '13:30' && $time <= '16:00');
    if ($in_session1 || $in_session2)
        $is_open = true;
}
?>

<div
    class="relative overflow-hidden bg-slate-800/40 backdrop-blur-xl border border-white/10 rounded-3xl p-6 mb-6 shadow-2xl transition-transform duration-300">
    <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">

        <div class="md:col-span-7 flex items-center gap-4">
            <div class="bg-sky-500/10 p-4 rounded-2xl border border-sky-500/20 text-sky-400">
                <i data-lucide="layout-dashboard" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white mb-0">Market Overview</h1>
                <p class="text-slate-400 text-sm mb-0">Monitoring Indonesia Stock Exchange Real-time</p>
            </div>
        </div>

        <div class="md:col-span-5 flex flex-col items-start md:items-end gap-2 mt-4 md:mt-0">
            <?php if ($is_open): ?>
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold tracking-wider">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    MARKET OPEN
                </div>
            <?php else: ?>
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 text-xs font-bold tracking-wider">
                    <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                    MARKET CLOSED
                </div>
            <?php endif; ?>

            <div class="flex items-center gap-2 text-slate-500 text-[11px] uppercase tracking-tight">
                <i data-lucide="clock" class="w-3 h-3"></i>
                <span>Last Sync: <strong class="text-slate-300"><?= date('H:i:s') ?> WIB</strong></span>
            </div>
        </div>
    </div>
</div>

<div class="mb-10">
    <?= view('partials/universal_chart', [
        'symbol' => 'IHSG',
        'chart_title' => 'Indonesia Composite Index (JKSE)'
    ]) ?>
</div>

<div class="mb-8 max-w-7xl px-4">
    <?= $this->include('partials/index_table') ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const syncMarketData = async () => {
            try {
                const response = await fetch('<?= base_url('stock/get_live_data') ?>');
                const data = await response.json();
                if (typeof updateTableUI === "function") updateTableUI(data);
            } catch (error) {
                console.error('Market sync failed:', error);
            }
        };

        // Poll every 5 seconds
        setInterval(syncMarketData, 5000);
    });
</script>

<?= $this->endSection() ?>