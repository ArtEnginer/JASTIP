<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<style>
    /* Style untuk e-commerce */
    .product-card {
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-card .card-image {
        height: 200px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-card .card-image img {
        object-fit: cover;
        height: 100%;
        width: 100%;
        transition: transform 0.3s;
    }

    .product-card:hover .card-image img {
        transform: scale(1.05);
    }

    .product-card .card-content {
        flex-grow: 1;
        padding: 15px;
    }

    .product-card .card-action {
        padding: 10px 15px;
        border-top: 1px solid rgba(160, 160, 160, 0.2);
    }

    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff4081;
        color: white;
        padding: 3px 8px;
        border-radius: 2px;
        font-size: 12px;
    }

    .badge-sale {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #4caf50;
        color: white;
        padding: 3px 8px;
        border-radius: 2px;
        font-size: 12px;
    }

    .price-original {
        text-decoration: line-through;
        color: #9e9e9e;
        font-size: 0.9em;
        margin-right: 5px;
    }

    .price-discounted {
        color: #f44336;
        font-weight: bold;
    }

    .cart-preview {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 997;
    }

    .cart-badge {
        position: absolute;
        top: -10px;
        right: -10px;
    }

    .category-chip {
        margin: 5px;
        cursor: pointer;
    }

    .category-chip.active {
        background: #2196F3 !important;
    }

    /* Responsif */
    @media only screen and (max-width: 992px) {
        .product-card .card-image {
            height: 150px;
        }
    }

    @media only screen and (max-width: 600px) {
        .cart-preview {
            bottom: 10px;
            right: 10px;
        }
    }
</style>

<h1 class="page-title">TOKO ONLINE KAMI</h1>
<div class="page-wrapper">
    <div class="page">
        <!-- Hero Banner -->
        <div class="card-panel blue lighten-5" style="padding: 20px; margin-bottom: 30px;">
            <div class="row valign-wrapper">
                <div class="col s12 m8">
                    <h4 style="margin-top: 0;">Selamat Berbelanja!</h4>
                    <p class="flow-text">Temukan produk terbaik dengan harga spesial hanya untuk Anda.</p>
                    <a href="#featured-products" class="btn blue waves-effect">Lihat Produk</a>
                </div>
                <div class="col s12 m4 hide-on-small-only">
                    <img src="<?= base_url('assets/images/shopping.svg') ?>" alt="Shopping" style="width: 100%;">
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">Kategori Produk</span>
                <div id="category-chips" class="chips">
                    <!-- Kategori akan dimuat via JS -->
                </div>
            </div>
        </div>

        <!-- Pencarian dan Filter -->
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">search</i>
                        <input type="text" id="search-product" placeholder="Cari produk...">
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="sort-products">
                            <option value="" disabled selected>Urutkan</option>
                            <option value="price-asc">Harga: Rendah ke Tinggi</option>
                            <option value="price-desc">Harga: Tinggi ke Rendah</option>
                            <option value="newest">Terbaru</option>
                            <option value="popular">Terpopuler</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="card">
            <div class="card-content">
                <div class="row" id="product-list">
                    <!-- Produk akan dimuat via JS -->
                </div>
                <div id="loading-products" class="center-align" style="display: none; margin: 20px 0;">
                    <div class="preloader-wrapper small active">
                        <div class="spinner-layer spinner-blue-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                            <div class="gap-patch">
                                <div class="circle"></div>
                            </div>
                            <div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    <p>Memuat produk...</p>
                </div>
            </div>
        </div>

        <!-- Floating Cart Button -->
        <div class="cart-preview">
            <a class="btn-floating btn-large blue waves-effect waves-light" id="cart-preview-btn">
                <i class="material-icons">shopping_cart</i>
                <span class="cart-badge badge red" id="cart-count">0</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal Detail Produk -->
<div id="product-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <div class="col s12 m6">
                <div class="slider">
                    <ul class="slides" id="product-images">
                        <!-- Gambar produk akan dimuat via JS -->
                    </ul>
                </div>
            </div>
            <div class="col s12 m6">
                <h4 id="modal-product-name"></h4>
                <div id="product-rating" class="rating" style="margin: 10px 0;">
                    <!-- Rating akan dimuat via JS -->
                </div>
                <div id="product-price" style="font-size: 1.5em; margin: 15px 0;"></div>
                <p id="modal-product-description" class="flow-text"></p>

                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <input type="number" id="product-quantity" value="1" min="1">
                            <label for="product-quantity">Jumlah</label>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <ul class="collapsible">
                    <li>
                        <div class="collapsible-header"><i class="material-icons">info</i>Detail Produk</div>
                        <div class="collapsible-body">
                            <table>
                                <tbody id="product-details">
                                    <!-- Detail produk akan dimuat via JS -->
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">comment</i>Ulasan (5)</div>
                        <div class="collapsible-body">
                            <div id="product-reviews">
                                <!-- Ulasan akan dimuat via JS -->
                            </div>
                            <div class="input-field">
                                <textarea id="review-text" class="materialize-textarea"></textarea>
                                <label for="review-text">Tulis ulasan Anda</label>
                            </div>
                            <button class="btn waves-effect waves-light" id="submit-review">Kirim Ulasan</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Tutup</a>
        <a href="#!" class="waves-effect waves-light btn blue" id="add-to-cart-btn">
            <i class="material-icons left">add_shopping_cart</i>Tambah ke Keranjang
        </a>
        <a href="#!" class="waves-effect waves-light btn pink" id="buy-now-btn">
            <i class="material-icons left">flash_on</i>Beli Sekarang
        </a>
    </div>
</div>

<!-- Modal Keranjang -->
<div id="cart-modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>Keranjang Belanja</h4>
        <div id="cart-items">
            <p class="center-align grey-text">Keranjang kosong</p>
        </div>
        <div class="divider"></div>
        <div class="row">
            <div class="col s6">
                <h5>Total:</h5>
            </div>
            <div class="col s6 right-align">
                <h5 id="total-amount">Rp 0</h5>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Lanjutkan Belanja</a>
        <a href="#checkout" class="waves-effect waves-light btn blue modal-trigger modal-close" id="checkout-btn">
            <i class="material-icons left">shopping_cart</i>Proses Checkout
        </a>
    </div>
</div>

<!-- Modal Checkout -->
<div id="checkout" class="modal">
    <div class="modal-content">
        <h4>Checkout</h4>
        <form id="checkout-form">
            <div class="row">
                <div class="col s12 m6">
                    <h5>Informasi Pengiriman</h5>
                    <div class="input-field">
                        <input type="text" id="customer-name" required>
                        <label for="customer-name">Nama Lengkap</label>
                    </div>
                    <div class="input-field">
                        <input type="email" id="customer-email" required>
                        <label for="customer-email">Email</label>
                    </div>
                    <div class="input-field">
                        <input type="tel" id="customer-phone" required>
                        <label for="customer-phone">No. Telepon</label>
                    </div>
                    <div class="input-field">
                        <textarea id="shipping-address" class="materialize-textarea" required></textarea>
                        <label for="shipping-address">Alamat Pengiriman</label>
                    </div>
                    <div class="input-field">
                        <select id="shipping-method" required>
                            <option value="" disabled selected>Pilih metode pengiriman</option>
                            <option value="jne">JNE Reguler</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="jnt">JNT Express</option>
                        </select>
                    </div>
                </div>
                <div class="col s12 m6">
                    <h5>Ringkasan Pesanan</h5>
                    <div id="order-summary">
                        <!-- Ringkasan pesanan akan dimuat via JS -->
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col s6">
                            <h5>Total:</h5>
                        </div>
                        <div class="col s6 right-align">
                            <h5 id="checkout-total">Rp 0</h5>
                        </div>
                    </div>

                    <h5>Metode Pembayaran</h5>
                    <div class="input-field">
                        <select id="payment-method" required>
                            <option value="" disabled selected>Pilih metode pembayaran</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="cod">COD (Bayar di Tempat)</option>
                            <option value="e-wallet">E-Wallet</option>
                            <option value="credit-card">Kartu Kredit</option>
                        </select>
                    </div>

                    <div id="payment-instruction" style="display: none;">
                        <p>Silakan transfer ke:</p>
                        <p><strong>Bank ABC</strong></p>
                        <p>No. Rekening: 1234567890</p>
                        <p>Atas Nama: Toko Online Kami</p>
                        <p>Jumlah: <span id="payment-amount">Rp 0</span></p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Batal</a>
        <button class="btn waves-effect waves-light blue" type="submit" form="checkout-form">
            <i class="material-icons left">check</i>Konfirmasi Pesanan
        </button>
    </div>
</div>

<?= $this->endSection() ?>