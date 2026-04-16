<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Stock-Hub' ?> | Aggregator Saham</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --border-dark: rgba(255, 255, 255, 0.08);
            --accent-info: #38bdf8;
            --text-slate: #94a3b8;
        }

        html,
        body {
            height: 100%;
        }

        body {
            background-color: var(--bg-dark);
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            /* Tambahkan ini agar konten tidak tertutup navbar fixed */
            padding-top: 80px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Tambahan transisi halus saat scroll */
        .navbar {
            background-color: rgba(15, 23, 42, 0.6) !important;
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            border-bottom: 1px solid var(--border-dark);
            padding: 0.8rem 0;
            transition: all 0.3s ease;
            z-index: 1030;
            /* Memastikan navbar di atas elemen lain */
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--accent-info) !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-nav-back {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dark);
            color: var(--text-slate);
            transition: all 0.2s;
        }

        .btn-nav-back:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: var(--accent-info);
        }

        main {
            flex: 1 0 auto;
        }

        /* Footer Modern */
        footer {
            background: rgba(15, 23, 42, 0.9);
            border-top: 1px solid var(--border-dark);
            padding: 2rem 0;
            flex-shrink: 0;
        }

        .footer-badge {
            background: rgba(56, 189, 248, 0.05);
            color: var(--accent-info);
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-info);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('stock') ?>">
                <div class="bg-info bg-opacity-10 p-2 rounded-3">
                    <i data-lucide="zap" fill="currentColor"></i>
                </div>
                STOCK-HUB
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <?php if (url_is('stock/detail/*')): ?>
                    <a href="<?= base_url('stock') ?>"
                        class="btn btn-nav-back btn-sm rounded-pill px-3 d-flex align-items-center gap-2">
                        <i data-lucide="chevron-left" size="16"></i>
                        <span>Dashboard</span>
                    </a>
                <?php endif; ?>

                <div class="text-slate d-none d-md-block" style="font-size: 0.75rem;">
                    <i data-lucide="map-pin" size="12" class="me-1"></i> Surabaya, ID
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start mb-2">
                        <span class="fw-bold text-white">Stock-Hub</span>
                        <span class="text-slate opacity-50">|</span>
                        <span class="text-slate small">Aggregator Web for stock analyzing</span>
                    </div>
                    <p class="text-slate mb-0" style="font-size: 0.75rem;">
                        © 2026. Built for high-performance stock analysis.
                    </p>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Lucide Icons globally
        lucide.createIcons();

        // Navbar Scroll Effect
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').style.padding = '0.5rem 0';
                document.querySelector('.navbar').style.backgroundColor = 'rgba(15, 23, 42, 0.9) !important';
            } else {
                document.querySelector('.navbar').style.padding = '0.8rem 0';
            }
        });
    </script>
</body>

</html>