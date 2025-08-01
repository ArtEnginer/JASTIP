<a href="#!" class="nav-close"><i class="material-icons">menu</i></a>
<div class="nav-header">
    <h1><b>
            PANEL ADMIN
        </b></h1>
</div>
<div class="nav-list">
    <div class="nav-item" data-page="dashboard">
        <a href="<?= base_url('panel') ?>" class="nav-link"><i class="material-icons">dashboard</i>Dashboard</a>
    </div>
    <!-- halaman untuk transaksi barang -->
    <div class="nav-item" data-page="transaksi-barang">
        <a href="<?= base_url('panel/transaksi-barang') ?>" class="nav-link"><i class="material-icons">shopping_cart</i>Transaksi
            Barang</a>
    </div>
    <?php if (auth()->user()->inGroup('admin')) : ?>
        <div class="nav-item" data-page="produk">
            <a href="<?= base_url('panel/produk') ?>" class="nav-link"><i class="material-icons">
                    inventory_2</i>Produk</a>

        </div>
        <div class="nav-item" data-page="kategori">
            <a href="<?= base_url('panel/kategori') ?>" class="nav-link"><i class="material-icons">
                    category</i>Kategori</a>

        </div>
        <!-- transaksi -->
        <div class="nav-item" data-page="transaksi">
            <a href="<?= base_url('panel/transaksi') ?>" class="nav-link"><i class="material-icons">receipt</i>Data Transaksi</a>
        </div>


        <div class="nav-item" data-page="user">
            <a href="<?= base_url('panel/user') ?>" class="nav-link"><i class="material-icons">person</i>Data
                User</a>
        </div>
    <?php endif ?>




    <div class="nav-item">
        <a href="<?= base_url('logout') ?>" class="nav-link btn-logout"><i class="material-icons">logout</i>Logout</a>
    </div>
</div>