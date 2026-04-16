<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
    .detail-card {
        background: linear-gradient(145deg, #1e293b, #111827);
        border: 1px solid #334155;
        border-radius: 24px;
    }

    .info-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #f8fafc;
    }

    .badge-sector {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.2);
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    .ai-box {
        background: rgba(15, 23, 42, 0.5);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(56, 189, 248, 0.1);
    }

    #aiContent {
        color: #cbd5e1;
        font-size: 0.9rem;
        line-height: 1.7;
    }

    #aiContent h1,
    #aiContent h2 {
        color: #38bdf8;
        font-size: 1.1rem;
        margin-top: 1rem;
    }

    .company-img {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: contain;
        background: white;
        padding: 4px;
    }
</style>

<div class="detail-card p-4 mb-4 shadow-lg border-info border-opacity-10">
    <div class="row align-items-center">
        <div class="col-md-6 d-flex align-items-center gap-3">
            <?php if (isset($fundamental['image'])): ?>
                <img src="<?= $fundamental['image'] ?>" class="company-img" alt="logo">
            <?php endif; ?>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h1 class="h3 fw-bold text-info mb-0"><?= $stock['code'] ?></h1>
                    <span class="badge-sector"><?= $fundamental['sector'] ?? $stock['sector'] ?></span>
                </div>
                <h5 class="text-secondary small fw-medium mb-0"><?= $fundamental['companyName'] ?? $stock['name'] ?>
                </h5>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="info-label">Current Price</div>
            <div class="h2 fw-bold text-white mb-0">IDR <?= number_format($stock['last_price'], 0, ',', '.') ?></div>
            <?php
            $change = $stock['last_price'] - $stock['previous_close'];
            $pct = ($stock['previous_close'] > 0) ? ($change / $stock['previous_close']) * 100 : 0;
            ?>
            <div class="<?= $change >= 0 ? 'text-success' : 'text-danger' ?> fw-bold small">
                <i class="fa-solid <?= $change >= 0 ? 'fa-caret-up' : 'fa-caret-down' ?> me-1"></i>
                <?= number_format(abs($pct), 2) ?>%
                (<?= ($change >= 0 ? '+' : '-') . number_format(abs($change), 0, ',', '.') ?>)
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="detail-card p-4 mb-4">
            <h6 class="fw-bold text-white mb-4 border-start border-info border-3 ps-3">Fundamental Stats (FMP)</h6>
            <div class="row g-4">
                <div class="col-6">
                    <div class="info-label">Market Cap</div>
                    <div class="info-value text-info" style="font-size: 0.95rem;">
                        IDR
                        <?= isset($fundamental['marketCap']) ? number_format($fundamental['marketCap'] / 1000000000000, 2) . ' T' : 'N/A' ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-label">Beta (Volatility)</div>
                    <div class="info-value"><?= $fundamental['beta'] ?? 'N/A' ?></div>
                </div>
                <div class="col-6">
                    <div class="info-label">Dividend</div>
                    <div class="info-value">
                        <?= isset($fundamental['lastDividend']) ? 'IDR ' . $fundamental['lastDividend'] : '-' ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-label">Employees</div>
                    <div class="info-value">
                        <?= isset($fundamental['fullTimeEmployees']) ? number_format($fundamental['fullTimeEmployees']) : '-' ?>
                    </div>
                </div>
            </div>

            <hr class="border-secondary opacity-20 my-4">

            <div class="info-label">Industry</div>
            <div class="text-white-50 small mb-3"><?= $fundamental['industry'] ?? '-' ?></div>

            <div class="info-label">Business Description</div>
            <p class="text-secondary"
                style="font-size: 0.75rem; text-align: justify; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                <?= $fundamental['description'] ?? 'No description available.' ?>
            </p>
        </div>

        <div class="detail-card p-4 border-info border-opacity-25">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-info mb-0"><i class="fa-solid fa-robot me-2"></i>AI Smart Analysis</h6>
                <button onclick="triggerAI('<?= $stock['code'] ?>')"
                    class="btn btn-sm btn-info rounded-pill px-3 shadow-sm">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Update
                </button>
            </div>
            <div class="ai-box">
                <div id="aiContent">
                    <?php if ($stock['ai_analysis']): ?>
                        <script>document.write(marked.parse(`<?= addslashes($stock['ai_analysis']) ?>`))</script>
                    <?php else: ?>
                    <span class="text-muted small">Belum ada analisis. Klik tombol Update.</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($stock['last_ai_update']): ?>
            <div class="mt-3 text-end" style="font-size: 0.65rem; color: #64748b; font-style: italic;">
                Last AI Scan: <?= date('d M Y, H:i', strtotime($stock['last_ai_update'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="detail-card p-4 shadow-lg h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold text-white mb-0">Price Performance</h6>
                <div class="badge bg-dark border border-secondary text-secondary px-3 py-2 rounded-pill"
                    style="font-size: 0.7rem;">
                    Range: <?= number_format($stock['day_low']) ?> - <?= number_format($stock['day_high']) ?>
                </div>
            </div>

            <div style="height: 500px; position: relative;">
                <canvas id="stockDetailChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockDetailChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 450);
    gradient.addColorStop(0, 'rgba(56, 189, 248, 0.25)');
    gradient.addColorStop(1, 'rgba(56, 189, 248, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['labels']) ?>,
            datasets: [{
                label: 'Price',
                data: <?= json_encode($chartData['prices']) ?>,
                borderColor: '#38bdf8',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.2,
                pointRadius: 0,
                pointHoverRadius: 6
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
                    callbacks: {
                        label: function (context) {
                            return ' Price: IDR ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    position: 'right',
                    grid: { color: 'rgba(51, 65, 85, 0.3)' },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 }, maxTicksLimit: 8 }
                }
            }
        }
    });

    async function triggerAI(code) {
        const aiBox = document.getElementById('aiContent');
        const btn = event.currentTarget;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Thinking...';
        aiBox.style.opacity = '0.4';

        try {
            const response = await fetch(`<?= base_url('stock/analyze-ai') ?>/${code}`);
            const data = await response.json();

            if (data.status === 'success') {
                aiBox.innerHTML = marked.parse(data.analysis);
                aiBox.style.opacity = '1';
                // Jika ingin auto-reload detail tanggal, bisa reload page atau update DOM manual
            }
        } catch (error) {
            aiBox.innerHTML = '<span class="text-danger small">Gagal memproses AI. Periksa koneksi Ollama/Groq.</span>';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles me-1"></i> Update';
        }
    }
</script>

<?= $this->endSection() ?>