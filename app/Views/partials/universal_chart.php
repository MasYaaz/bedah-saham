<style>
    .chart-container {
        position: relative;
        background: linear-gradient(145deg, #1e293b, #111827);
        border: 1px solid #334155;
        border-radius: 24px;
        padding: 2rem;
    }

    .btn-time-range {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid #334155;
        color: #94a3b8;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-time-range:hover {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border-color: #38bdf8;
    }

    .btn-time-range.active {
        background: #38bdf8 !important;
        color: #0f172a !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
    }

    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #f8fafc;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 5;
        border-radius: 24px;
    }
</style>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-label">
            <i data-lucide="<?= $symbol === 'IHSG' ? 'globe' : 'line-chart' ?>" size="14"></i>
            <?= $chart_title ?>
        </div>
        <div class="d-flex align-items-baseline gap-3">
            <div class="stat-value" id="livePrice">--</div>
            <div id="liveChange" class="fw-bold" style="font-size: 1rem;">
                <span id="changeIcon"></span>
                <span id="changeValue">0.00%</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 d-flex justify-content-md-end align-items-center">
        <div class="btn-group p-1 rounded-pill" style="background: rgba(15, 23, 42, 0.5); border: 1px solid #334155;">
            <?php foreach (['1D', '1W', '1M', '6M', '1Y'] as $r): ?>
                <button type="button"
                    class="btn btn-time-range rounded-pill btn-range-selector <?= $r == '1D' ? 'active' : '' ?>"
                    onclick="updateUniversalChart('<?= $r ?>')">
                    <?= $r ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="chart-container shadow-2xl mb-5">
    <div id="chartLoader" class="loading-overlay">
        <div class="spinner-border text-info" role="status"></div>
    </div>
    <canvas id="universalCanvas" style="height: 450px;"></canvas>
</div>

<script>
    // Variabel Konfigurasi Global untuk Partial ini
    const CHART_SYMBOL = "<?= $symbol ?>";
    let universalChartInstance = null;

    // Inisialisasi Chart saat DOM Ready
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('universalCanvas').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(56, 189, 248, 0.3)');
        gradient.addColorStop(1, 'rgba(56, 189, 248, 0)');

        universalChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: CHART_SYMBOL,
                    data: [],
                    borderColor: '#38bdf8',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: (context) => CHART_SYMBOL + ': ' + new Intl.NumberFormat('id-ID').format(context.parsed.y)
                        }
                    }
                },
                scales: {
                    y: { position: 'right', grid: { color: 'rgba(51, 65, 85, 0.2)' }, ticks: { color: '#64748b', font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 }, maxTicksLimit: 12 } }
                }
            }
        });

        // Load data awal
        updateUniversalChart('1D');
    });

    window.updateUniversalChart = async function (range) {
        const loader = document.getElementById('chartLoader');
        if (loader) loader.style.display = 'flex';

        // Toggle Button Active
        document.querySelectorAll('.btn-range-selector').forEach(btn => {
            btn.classList.toggle('active', btn.innerText.trim() === range);
        });

        try {
            const response = await fetch(`<?= base_url('stock/history') ?>/${CHART_SYMBOL}/${range.toLowerCase()}`);
            const data = await response.json();

            if (data.prices && data.prices.length > 0) {
                // 1. Update Chart Data
                universalChartInstance.data.labels = data.labels;
                universalChartInstance.data.datasets[0].data = data.prices;

                // 2. Dinamis Warna berdasarkan Performa
                const isUp = data.prices[data.prices.length - 1] >= data.prices[0];
                const themeColor = isUp ? '#10b981' : '#ef4444';

                universalChartInstance.data.datasets[0].borderColor = themeColor;
                const ctx = document.getElementById('universalCanvas').getContext('2d');
                const newGradient = ctx.createLinearGradient(0, 0, 0, 400);
                newGradient.addColorStop(0, isUp ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)');
                newGradient.addColorStop(1, 'rgba(0,0,0,0)');
                universalChartInstance.data.datasets[0].backgroundColor = newGradient;

                universalChartInstance.update();

                // 3. Update UI Stats
                const lastPrice = data.prices[data.prices.length - 1];
                const firstPrice = data.prices[0];
                const diff = lastPrice - firstPrice;
                const percent = ((diff / firstPrice) * 100).toFixed(2);

                document.getElementById('livePrice').innerText = lastPrice.toLocaleString('id-ID', { minimumFractionDigits: 2 });

                const changeValue = document.getElementById('changeValue');
                const changeIcon = document.getElementById('changeIcon');
                document.getElementById('liveChange').className = isUp ? "text-success fw-bold" : "text-danger fw-bold";

                changeIcon.innerHTML = isUp ? '<i data-lucide="trending-up" size="16"></i>' : '<i data-lucide="trending-down" size="16"></i>';
                changeValue.innerText = `${isUp ? '+' : ''}${percent}%`;

                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        } catch (e) {
            console.error("Universal Chart Fetch Error:", e);
        } finally {
            if (loader) loader.style.display = 'none';
        }
    }
</script>