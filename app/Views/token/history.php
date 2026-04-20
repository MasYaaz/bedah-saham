<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex align-items-center gap-3 mb-5">
        <div class="bg-info bg-opacity-10 p-3 rounded-4">
            <i data-lucide="receipt" class="text-info"></i>
        </div>
        <div>
            <h2 class="fw-bold text-white mb-1">Riwayat Transaksi</h2>
            <p class="text-slate mb-0">Daftar pembelian token analisis saham kamu.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden"
        style="background: rgba(30, 41, 59, 0.6); border: 1px solid var(--border-dark) !important; border-radius: 20px;">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
                <thead class="text-slate small text-uppercase" style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th class="px-4 py-3 border-0">Tanggal</th>
                        <th class="px-4 py-3 border-0">Paket</th>
                        <th class="px-4 py-3 border-0">Nominal</th>
                        <th class="px-4 py-3 border-0">Status</th>
                    </tr>
                </thead>
                <tbody class="text-white border-0">
                    <?php foreach ($history as $h): ?>
                        <tr style="border-bottom: 1px solid var(--border-dark);">
                            <td class="px-4 py-4 text-slate small">
                                <?= date('d M Y, H:i', strtotime($h->created_at)) ?>
                            </td>
                            <td class="px-4 py-4 fw-medium">
                                <?= $h->package_name ?>
                            </td>
                            <td class="px-4 py-4">
                                Rp
                                <?= number_format($h->amount_paid, 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($h->status == 'success'): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">SUCCESS</span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">PENDING</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>