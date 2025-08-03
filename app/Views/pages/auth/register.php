<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('layouts/auth/main') ?>
<?= $this->section('main') ?>

<div class="auth-card">
    <h1 class="title">
        Buat Akun
    </h1>
    <br>
    <div class="row card-body">
        <form class="col s12" action="#!" id="register" method="post">
            <?= csrf_field() ?>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <input id="nama" name="nama" type="text" class="validate" value="<?= old('nama') ?>"
                        required>
                    <label for="nama">Nama Lengkap</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <input id="email" name="email" type="email" class="validate" value="<?= old('email') ?>"
                        required>
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <input id="username" name="username" type="text" class="validate" value="<?= old('username') ?>"
                        required>
                    <label for="username">Username</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <input id="password" name="password" type="password" class="validate" required>
                    <label for="password">Kata Sandi</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <input id="password_confirm" name="password_confirm" type="password" class="validate" required>
                    <label for="password_confirm">Konfirmasi Kata Sandi</label>
                </div>
            </div>
            <button type="submit" class="btn waves-effect waves-light btn-auth">
                Daftar
            </button>
            <p class="center">Sudah punya akun ? <a class="blue-text" href="<?= base_url('login') ?>">Masuk</a></p>
        </form>
    </div>
</div>

<?= $this->endSection() ?>