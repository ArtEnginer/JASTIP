<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>

<h1 class="page-title">Data Jasa Titip</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
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
    </div>
</div>
<?= $this->endSection() ?>