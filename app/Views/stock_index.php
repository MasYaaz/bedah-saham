<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
date_default_timezone_set('Asia/Jakarta');
$now = new DateTime();
$day = $now->format('N');
$time = $now->format('H:i');

$is_open = false;
if ($day >= 1 && $day <= 5) {
    if ($day == 5) { // Jumat
        if (($time >= '09:00' && $time <= '11:30') || ($time >= '13:30' && $time <= '16:00'))
            $is_open = true;
    } else { // Senin - Kamis
        if (($time >= '09:00' && $time <= '12:00') || ($time >= '13:30' && $time <= '16:00'))
            $is_open = true;
    }
}
?>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
    .index-header-card {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
    }

    .market-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-open {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-closed {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            opacity: 1;
        }

        50% {
            transform: scale(1.5);
            opacity: 0.5;
        }

        100% {
            transform: scale(0.95);
            opacity: 1;
        }
    }
</style>

<div class="index-header-card p-4 mb-4 shadow-2xl">
    <div class="row align-items-center">
        <div class="col-md-7 d-flex align-items-center gap-3">
            <div class="bg-info bg-opacity-10 p-3 rounded-4 border border-info border-opacity-20 text-info">
                <i data-lucide="layout-dashboard" size="28"></i>
            </div>
            <div>
                <h1 class="h3 fw-bold text-white mb-0">Market Overview</h1>
                <p class="text-slate-400 small mb-0">Monitoring Indonesia Stock Exchange Real-time</p>
            </div>
        </div>

        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="mb-2">
                <?php if ($is_open): ?>
                    <div class="market-status-badge status-open">
                        <span class="pulse-dot"></span>
                        IDX Open
                    </div>
                <?php else: ?>
                    <div class="market-status-badge status-closed">
                        <i data-lucide="lock" size="10"></i>
                        IDX Closed
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-slate-500 d-flex align-items-center justify-content-md-end gap-1"
                style="font-size: 0.65rem;">
                <i data-lucide="clock" size="12"></i>
                Last Sync: <?= date('H:i:s') ?> WIB
            </div>
        </div>
    </div>
</div>

<?= view('partials/universal_chart', [
    'symbol' => 'IHSG',
    'chart_title' => 'Indonesia Composite Index (JKSE)'
]) ?>

<?= $this->include('partials/index_table') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.onload = () => {
        // Initialize Icons
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Logika Sinkronisasi Tabel
        setInterval(() => {
            fetch('<?= base_url('stock/get_live_data') ?>')
                .then(res => res.json())
                .then(data => {
                    if (typeof updateTableUI === "function") updateTableUI(data);
                });
        }, 5000);
    };
</script>

<?= $this->endSection() ?>