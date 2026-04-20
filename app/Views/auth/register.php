<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-5">
        <div class="card bg-dark text-white border-0 shadow-lg"
            style="background: rgba(30, 41, 59, 0.5) !important; backdrop-filter: blur(10px); border: 1px solid var(--border-dark) !important;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i data-lucide="user-plus" class="text-info"></i>
                    </div>
                    <h3 class="fw-bold">Daftar Akun</h3>
                    <p class="text-slate small">Bergabung dan dapatkan <span class="text-info fw-bold">5 Token
                            Gratis</span> untuk analisis saham.</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger text-center small">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('register') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-slate">Username</label>
                        <input type="text" name="username" class="form-control bg-dark border-secondary text-white py-2"
                            style="background: rgba(15, 23, 42, 0.5) !important;" placeholder="AflahYazdi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-slate">Alamat Email</label>
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white py-2"
                            style="background: rgba(15, 23, 42, 0.5) !important;" placeholder="nama@email.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-slate">Password</label>
                        <input type="password" name="password"
                            class="form-control bg-dark border-secondary text-white py-2"
                            style="background: rgba(15, 23, 42, 0.5) !important;" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-info w-100 fw-bold py-2 shadow-sm text-dark mb-3">
                        Daftar Sekarang
                    </button>

                    <p class="text-center text-slate" style="font-size: 0.7rem;">
                        Dengan mendaftar, kamu menyetujui ketentuan penggunaan BedahSaham.
                    </p>
                </form>

                <div class="mt-4 text-center small">
                    <span class="text-slate">Sudah punya akun?</span>
                    <a href="<?= base_url('login') ?>" class="text-info text-decoration-none fw-semibold">Masuk di
                        sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>