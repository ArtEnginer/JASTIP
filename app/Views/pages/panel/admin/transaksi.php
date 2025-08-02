<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<style>
    /* Add this to your CSS file */
    .modal.modal-fixed-footer {
        max-height: 80%;
        height: 80%;
        width: 80%;
        max-width: 800px;
    }

    .modal.modal-fixed-footer .modal-content {
        height: calc(100% - 56px);
        overflow-y: auto;
    }

    #detail-produk-list img {
        max-height: 50px;
        margin-right: 10px;
    }
</style>
<h1 class="page-title">Data transaksi</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">

            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-transaksi" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal untuk edit status -->
<div id="modal-edit-status" class="modal">
    <div class="modal-content">
        <h4>Ubah Status Pesanan</h4>
        <form id="form-edit-status">
            <input type="hidden" name="id">
            <div class="row">
                <div class="input-field col s12">
                    <select name="status" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="pending">Pending</option>
                        <option value="dikemas">Dikemas</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <label>Status Pesanan</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <button type="button" class="btn waves-effect waves-light btn-popup-close">Batal</button>
                    <button type="submit" class="btn waves-effect waves-light">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal untuk lihat detail transaksi -->
<div id="modal-detail-transaksi" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Detail Transaksi #<span id="detail-transaksi-id"></span></h4>
        <div class="row">
            <div class="col s12 m6">
                <p><strong>Nama Penerima:</strong> <span id="detail-nama-penerima"></span></p>
                <p><strong>Alamat:</strong> <span id="detail-alamat"></span></p>
            </div>
            <div class="col s12 m6">
                <p><strong>Email:</strong> <span id="detail-email"></span></p>
                <p><strong>Telepon:</strong> <span id="detail-telepon"></span></p>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <p><strong>Status:</strong> <span id="detail-status" class="pl-2 pr-2 rounded text-white"></span></p>
                <p><strong>Total Harga:</strong> <span id="detail-total-harga"></span></p>
                <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
            </div>
        </div>

        <h5>Daftar Produk</h5>
        <table class="striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="detail-produk-list">
                <!-- Produk akan dimasukkan di sini oleh JavaScript -->
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn waves-effect waves-light btn-popup-close">Tutup</button>
    </div>
</div>
<?= $this->endSection() ?>