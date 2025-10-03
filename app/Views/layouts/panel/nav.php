<a href="#!" class="nav-close"><i class="material-icons">menu</i></a>
<div class="nav-header">
    <h1><b>
            JASTIP
        </b></h1>
</div>
<div class="nav-list">

    <div class="nav-item" data-page="dashboard">
        <a href="<?= base_url('panel') ?>" class="nav-link"><i class="material-icons">dashboard</i>Dashboard</a>
    </div>


    <div class="nav-item" data-page="jastip">
        <a href="<?= base_url('panel/jastip') ?>" class="nav-link"><i class="material-icons">shopping_cart</i>Kelola Titipan</a>
    </div>



    <div class="nav-item" data-page="user">
        <a href="<?= base_url('panel/user') ?>" class="nav-link"><i class="material-icons">person</i>Data
            User</a>
    </div>





    <?php if (auth()->user()->inGroup('user')) : ?>
        <div class="nav-item" data-page="pengajuan">
            <a href="<?= base_url('panel/pengajuan') ?>" class="nav-link"><i class="material-icons">assignment</i>Pengajuan Titipan</a>
        </div>
        <div class="nav-item" data-page="riwayat">
            <a href="<?= base_url('panel/riwayat') ?>" class="nav-link"><i class="material-icons">assignment</i>Riwayat Titipan</a>
        </div>
    <?php endif ?>


    <div class="nav-item">
        <a href="<?= base_url('logout') ?>" class="nav-link btn-logout"><i class="material-icons">logout</i>Logout</a>
    </div>
</div>