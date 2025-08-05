<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>

<h1 class="page-title">Data Jasa riwayat</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <div class="row">
                <!-- <div class="col-12 text-end">
                    <button class="btn waves-effect waves-light green btn-popup rounded" data-target="add" type="button"><i class="material-icons left">add</i>Tambah riwayat</button>
                </div> -->
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-riwayat" width="100%">
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
<?= $this->endSection() ?>

<?= $this->section('popup') ?>
<!-- Form Tambah riwayat -->
<div class="popup side" data-page="add">
    <h1>Tambah Jasa riwayat</h1>
    <br>
    <form id="form-add" class="row" enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <div class="input-field col s12">
            <textarea name="keterangan" id="add-keterangan" class="materialize-textarea" required></textarea>
            <label for="add-keterangan">Keterangan Umum</label>
        </div>

        <div class="col s12">
            <h5>Daftar Barang</h5>
            <div id="items-container">
                <!-- Item akan ditambahkan di sini -->
                <div class="item-row row">
                    <div class="input-field col s5">
                        <input type="text" name="items[0][nama_barang]" class="validate" required>
                        <label>Nama Barang</label>
                    </div>
                    <div class="input-field col s3">
                        <input type="number" name="items[0][jumlah]" class="validate" required min="1">
                        <label>Jumlah</label>
                    </div>
                    <div class="input-field col s3">
                        <input type="text" name="items[0][keterangan]" class="validate">
                        <label>Keterangan</label>
                    </div>
                    <div class="col s1">
                        <button type="button" class="btn-floating red remove-item"><i class="material-icons">remove</i></button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-item" class="btn-floating green"><i class="material-icons">add</i></button>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Form Edit riwayat -->
<div class="popup side" data-page="edit">
    <h1>Edit Jasa riwayat</h1>
    <br>
    <form id="form-edit" class="row" enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <input type="hidden" name="id" id="edit-id">

        <div class="input-field col s12">
            <textarea name="keterangan" id="edit-keterangan" class="materialize-textarea" required></textarea>
            <label for="edit-keterangan">Keterangan Umum</label>
        </div>

        <!-- status -->
        <div class="input-field col s12">
            <select name="status" id="edit-status" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="pending">Pending</option>
                <option value="proses">Proses Pengiriman</option>
                <option value="selesai">Selesai</option>
                <option value="batal">Batal</option>
            </select>
            <label>Status riwayat</label>
        </div>

        <div class="input-field col s12">
            <textarea name="catatan" id="edit-catatan" class="materialize-textarea"></textarea>
            <label for="edit-catatan">Catatan Admin</label>
        </div>

        <div class="col s12">
            <h5>Daftar Barang</h5>
            <div id="edit-items-container">
                <!-- Item akan diisi oleh JavaScript -->
            </div>
            <button type="button" id="edit-add-item" class="btn-floating green"><i class="material-icons">add</i></button>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Popup Detail riwayat -->
<div class="popup side" data-page="detail">
    <h1>Detail Jasa riwayat</h1>
    <br>
    <div class="row">
        <div class="col s12">
            <p><strong>Keterangan:</strong> <span id="detail-keterangan"></span></p>
            <p><strong>Status:</strong> <span id="detail-status"></span></p>
            <p><strong>Catatan Admin:</strong> <span id="detail-catatan"></span></p>
            <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
        </div>

        <div class="col s12">
            <h5>Daftar Barang</h5>
            <table class="striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                    </tr>
                </thead>
                <tbody id="detail-items">
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>