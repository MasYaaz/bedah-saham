<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= $this->include('partials/_index_chart') ?>
<?= $this->include('partials/_index_table') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Inisialisasi Chart.js secara Global
    const ctx = document.getElementById('ihsgChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(56, 189, 248, 0.3)');
    gradient.addColorStop(1, 'rgba(56, 189, 248, 0)');

    window.ihsgChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'IHSG',
                data: [],
                borderColor: '#38bdf8',
                backgroundColor: gradient,
                fill: true,
                tension: 0.2,
                pointRadius: 0,
                pointHitRadius: 20, // Mempermudah kursor mendeteksi garis
                pointHoverRadius: 6, // Titik muncul saat di-hover
                pointHoverBackgroundColor: '#38bdf8',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false, // Tooltip muncul meskipun kursor tidak tepat di titik
                mode: 'index',
            },
            plugins: {
                legend: { display: false },
                // --- KONFIGURASI TOOLTIP MODERN ---
                tooltip: {
                    enabled: true,
                    backgroundColor: '#1e293b', // Warna card slate kamu
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    titleFont: { family: 'Inter', size: 12, weight: '600' },
                    bodyFont: { family: 'Inter', size: 14, weight: '700' },
                    padding: 12,
                    cornerRadius: 12,
                    borderColor: '#334155',
                    borderWidth: 1,
                    displayColors: false, // Hilangkan kotak warna kecil di tooltip
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                // Format angka ke Rupiah/Desimal Indonesia
                                label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    position: 'right',
                    grid: {
                        color: 'rgba(51, 65, 85, 0.3)', // Garis horizontal lebih tipis
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: { size: 10, family: 'Inter' },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#64748b',
                        font: { size: 10, family: 'Inter' },
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 20, // Membatasi jumlah tanggal agar tidak numpuk
                        padding: 10
                    }
                }
            }
        }
    });

    // 2. Loop Utama (Orchestration)
    window.onload = () => {
        // Panggil fungsi dari komponen chart
        updateChart('1Y');

        // Jalankan sinkronisasi realtime
        setInterval(() => {
            fetch('<?= base_url('stock/get_live_data') ?>')
                .then(res => res.json())
                .then(data => {
                    if (typeof updateTableUI === "function") {
                        updateTableUI(data);
                    }
                });
        }, 5000);

        // Jalankan background sync data
        setTimeout(() => fetch('<?= base_url('stock/update') ?>'), 3000);
    };
</script>
<?= $this->endSection() ?>