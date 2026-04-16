<style>
    .market-card {
        background: linear-gradient(145deg, #1e293b, #111827);
        border: 1px solid #334155;
        border-radius: 24px;
    }

    .table-dark {
        --bs-table-bg: transparent;
        --bs-table-hover-bg: rgba(56, 189, 248, 0.03);
    }

    .stock-row {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .stock-row:hover {
        transform: scale(1.002);
    }

    .badge-code {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.2);
        padding: 0.5rem 0.8rem;
        border-radius: 10px;
        font-weight: 700;
    }

    .search-container .input-group-text {
        background: #0f172a;
        border: 1px solid #334155;
        color: #64748b;
        border-radius: 12px 0 0 12px;
    }

    .search-container .form-control {
        background: #0f172a;
        border: 1px solid #334155;
        color: #f8fafc;
        border-radius: 0 12px 12px 0;
    }

    .search-container .form-control:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.1);
    }

    /* Scrollbar minimalis */
    .table-responsive {
        overflow-x: hidden !important;
        scrollbar-width: thin;
        scrollbar-color: #334155 transparent;
    }

    .table-responsive::-webkit-scrollbar {
        width: 4px;
        height: 0px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }

    /* Desain Search Bar Modern */
    .search-wrapper {
        position: relative;
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 4px 15px;
        transition: all 0.3s ease;
    }

    .search-wrapper:focus-within {
        border-color: #38bdf8;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
        background: rgba(30, 41, 59, 0.8);
    }

    .search-wrapper i {
        color: #64748b;
        transition: color 0.3s;
    }

    .search-wrapper:focus-within i {
        color: #38bdf8;
    }

    .search-input {
        background: transparent;
        border: none;
        color: #f8fafc;
        padding: 10px;
        width: 100%;
        outline: none;
        font-size: 0.9rem;
    }

    .search-input::placeholder {
        color: #64748b;
    }
</style>

<div class="row g-4 mb-4 align-items-center">
    <div class="col-md-7">
        <h3 class="fw-bold mb-1 text-white">Market Dashboard</h3>
        <p class="text-secondary mb-0 small">Menampilkan <span class="text-info fw-bold"><?= count($stocks) ?></span>
            emiten terdaftar</p>
    </div>
    <div class="col-md-5">
        <div class="search-wrapper d-flex align-items-center">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="stockSearch" class="search-input"
                placeholder="Cari kode saham atau nama perusahaan...">
        </div>
    </div>
</div>

<div class="market-card shadow-2xl overflow-hidden">
    <div class="table-responsive" style="max-height: 75vh;">
        <table class="table table-dark table-hover align-middle mb-0" id="stockTable">
            <thead class="sticky-top bg-dark" style="z-index: 10;">
                <tr class="text-secondary border-bottom border-secondary"
                    style="font-size: 0.75rem; letter-spacing: 1px;">
                    <th class="ps-4 py-4 uppercase">EMITEN</th>
                    <th class="py-4 uppercase">NAMA PERUSAHAAN</th>
                    <th class="py-4 text-end uppercase">HARGA (IDR)</th>
                    <th class="py-4 text-end uppercase">PERUBAHAN</th>
                    <th class="py-4 text-center uppercase">RANGE (H/L)</th>
                    <th class="py-4 text-center pe-4 uppercase">AKSI</th>
                </tr>
            </thead>
            <tbody id="stockTableBody">
                <?php foreach ($stocks as $s): ?>
                    <?php
                    $change = $s['last_price'] - $s['previous_close'];
                    $percent = ($s['previous_close'] > 0) ? ($change / $s['previous_close']) * 100 : 0;
                    ?>
                    <tr id="row-<?= $s['code'] ?>" class="stock-row border-bottom border-secondary border-opacity-10">
                        <td class="ps-4">
                            <div class="badge-code d-inline-block"><?= $s['code'] ?></div>
                            <div class="text-secondary mt-1" style="font-size: 0.65rem;"><?= $s['sector'] ?></div>
                        </td>
                        <td>
                            <div class="text-truncate text-light fw-semibold" style="max-width: 250px;"><?= $s['name'] ?>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-white last-price">
                            <?= number_format($s['last_price'], 0, ',', '.') ?>
                        </td>
                        <td class="text-end <?= $change >= 0 ? 'text-success' : 'text-danger' ?>">
                            <div class="fw-bold small"><i
                                    class="fa-solid <?= $change >= 0 ? 'fa-caret-up' : 'fa-caret-down' ?> me-1"></i><?= number_format(abs($percent), 2) ?>%
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="small opacity-75">
                                <span class="text-success">H: <?= number_format($s['day_high'], 0, ',', '.') ?></span><br>
                                <span class="text-danger">L: <?= number_format($s['day_low'], 0, ',', '.') ?></span>
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <a href="<?= base_url('stock/detail/' . $s['code']) ?>"
                                class="btn btn-sm btn-outline-info rounded-pill px-3">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Logika Live Search yang Diperbaiki
    document.getElementById('stockSearch').addEventListener('input', function () {
        const filter = this.value.toUpperCase().trim();
        const rows = document.querySelectorAll('#stockTableBody .stock-row');

        rows.forEach(row => {
            // Ambil teks dari badge kode emiten
            const codeElement = row.querySelector('.badge-code');
            const nameElement = row.querySelector('.stock-name-text');

            const codeText = codeElement ? codeElement.textContent.toUpperCase() : '';
            const nameText = nameElement ? nameElement.textContent.toUpperCase() : '';

            // Cek apakah filter cocok dengan kode ATAU nama
            if (codeText.includes(filter) || nameText.includes(filter)) {
                row.style.display = ""; // Tampilkan
                // Tambahkan animasi fade in tipis agar smooth
                row.style.opacity = "1";
            } else {
                row.style.display = "none"; // Sembunyikan
            }
        });

        // Opsional: Tampilkan pesan "Data tidak ditemukan" jika semua row hidden
        const visibleRows = document.querySelectorAll('#stockTableBody .stock-row[style="display: px"]').length;
        // (Tambahkan logika empty state jika perlu)
    });

    // Fungsi update UI baris tabel
    window.updateTableUI = function (data) {
        data.forEach(stock => {
            const row = document.getElementById(`row-${stock.code}`);
            if (row) {
                const priceEl = row.querySelector('.last-price');
                const newPrice = new Intl.NumberFormat('id-ID').format(stock.last_price);
                if (priceEl.innerText !== newPrice) {
                    priceEl.innerText = newPrice;
                    priceEl.style.color = '#38bdf8';
                    setTimeout(() => priceEl.style.color = 'white', 1500);
                }
            }
        });
    }
</script>