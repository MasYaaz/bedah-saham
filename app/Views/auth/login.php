<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card bg-dark text-white border-0 shadow-lg"
            style="background: rgba(30, 41, 59, 0.5) !important; backdrop-filter: blur(10px); border: 1px solid var(--border-dark) !important;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i data-lucide="lock" class="text-info"></i>
                    </div>
                    <h3 class="fw-bold">Masuk Akun</h3>
                    <p class="text-slate small">Analisis saham lebih dalam dengan AI</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger text-center small">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-slate">Username atau Email</label>
                        <input type="text" name="login_identity"
                            class="form-control bg-dark border-secondary text-white py-2"
                            style="background: rgba(15, 23, 42, 0.5) !important;"
                            placeholder="Masukkan username atau email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-slate">Password</label>
                        <input type="password" name="password"
                            class="form-control bg-dark border-secondary text-white py-2"
                            style="background: rgba(15, 23, 42, 0.5) !important;" required>
                    </div>
                    <button type="submit" class="btn btn-info w-100 fw-bold py-2 shadow-sm text-dark">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-4 text-center small">
                    <span class="text-slate">Belum punya akun?</span>
                    <a href="<?= base_url('register') ?>" class="text-info text-decoration-none fw-semibold">
                        Daftar & Bonus 5 Token
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>