<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Stock-Hub' ?> | Aggregator Saham</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --border-dark: #334155;
            --accent-info: #38bdf8;
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
        }

        .navbar {
            background-color: rgba(30, 41, 59, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-dark);
            padding: 0.8rem 0;
            flex-shrink: 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--accent-info) !important;
            font-size: 1.4rem;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            background-color: var(--card-dark);
            border-top: 1px solid var(--border-dark);
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('stock') ?>">
                <i class="fa-solid fa-bolt-lightning me-2"></i>STOCK-HUB
            </a>

            <div class="ms-auto d-flex align-items-center">
                <?php if (url_is('stock/detail/*')): ?>
                    <a href="<?= base_url('stock') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="footer py-4">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-secondary small">© 2026 <b>Stock-Hub</b>. Universitas Surabaya Research
                        Project.</span>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-dark text-secondary border border-secondary">
                        <i class="fa-solid fa-microchip me-1"></i> CI 4.x + MariaDB
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>