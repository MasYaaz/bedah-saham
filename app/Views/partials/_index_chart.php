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
        <div class="stat-label">Indonesia Composite Index (JKSE)</div>
        <div class="d-flex align-items-baseline gap-3">
            <div class="stat-value" id="livePrice">7.200,00</div>
            <div id="liveChange" class="fw-bold" style="font-size: 1rem;">
                <span id="changeIcon"></span>
                <span id="changeValue">0.00%</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 d-flex justify-content-md-end align-items-center">
        <div class="btn-group p-1 rounded-pill" style="background: rgba(15, 23, 42, 0.5); border: 1px solid #334155;">
            <?php foreach (['1D', '1W', '1M', '6M', '1Y'] as $r): ?>
                <button type="button" class="btn btn-time-range rounded-pill <?= $r == '1Y' ? 'active' : '' ?>"
                    onclick="updateChart('<?= $r ?>')">
                    <?= $r ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="chart-container shadow-2xl mb-5">
    <div id="loader" class="loading-overlay">
        <div class="spinner-border text-info" role="status"></div>
    </div>
    <canvas id="ihsgChart" style="height: 450px;"></canvas>
</div>

<script>
    // Fungsi ini didefinisikan di level window agar bisa dipanggil dari tombol onclick
    window.updateChart = async function (range) {
        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'flex';

        document.querySelectorAll('.btn-time-range').forEach(btn => btn.classList.toggle('active', btn.innerText === range));

        try {
            const response = await fetch(`<?= base_url('stock/ihsg-history') ?>/${range.toLowerCase()}`);
            const data = await response.json();

            if (data.prices && data.prices.length > 0 && window.ihsgChart) {
                // 1. Update Chart
                window.ihsgChart.data.labels = data.labels;
                window.ihsgChart.data.datasets[0].data = data.prices;
                window.ihsgChart.update();

                // 2. Kalkulasi Perubahan Dinamis
                const firstPrice = data.prices[0];
                const lastPrice = data.prices[data.prices.length - 1];
                const diff = lastPrice - firstPrice;
                const percentChange = (diff / firstPrice) * 100;

                // 3. Update UI Price
                document.getElementById('livePrice').innerText = lastPrice.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // 4. Update UI Tanda Naik/Turun
                const changeContainer = document.getElementById('liveChange');
                const changeIcon = document.getElementById('changeIcon');
                const changeValue = document.getElementById('changeValue');

                if (diff >= 0) {
                    // Jika Naik atau Tetap
                    changeContainer.className = "text-success fw-bold";
                    changeIcon.innerHTML = '<i class="fa-solid fa-caret-up me-1"></i>';
                    changeValue.innerText = `${percentChange.toFixed(2)}%`;
                } else {
                    // Jika Turun
                    changeContainer.className = "text-danger fw-bold";
                    changeIcon.innerHTML = '<i class="fa-solid fa-caret-down me-1"></i>';
                    changeValue.innerText = `${Math.abs(percentChange).toFixed(2)}%`;
                }
            }
        } catch (e) {
            console.error("Chart Error:", e);
        } finally {
            if (loader) loader.style.display = 'none';
        }
    }</script>