<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<!-- Midtrans SDK -->
<script
    type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-U4g1AdoP3Ny_Oxuh">
</script>
<style>
    /* Main Styles */
    .ecommerce-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #6e8efb 0%, #4a6cf7 100%);
        color: white;
        border-radius: 8px;
        padding: 40px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-bg-pattern {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 15px 15px;
    }

    /* Product Card */
    .product-card {
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .product-image-container {
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 10px;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: white;
    }

    .badge-new {
        left: 10px;
        background: #4CAF50;
    }

    .badge-sale {
        right: 10px;
        background: #F44336;
    }

    .product-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        margin-top: auto;
    }

    .current-price {
        font-size: 18px;
        font-weight: 700;
        color: #2196F3;
    }

    .original-price {
        font-size: 14px;
        text-decoration: line-through;
        color: #9E9E9E;
        margin-left: 5px;
    }

    .product-actions {
        border-top: 1px solid #f0f0f0;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
    }

    /* Category Filter */
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .category-chip {
        padding: 8px 16px;
        border-radius: 20px;
        background: #f5f5f5;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .category-chip.active {
        background: #2196F3;
        color: white;
    }

    /* Search and Sort */
    .search-sort-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .search-box {
        flex-grow: 1;
        min-width: 250px;
    }

    .sort-select {
        min-width: 200px;
    }

    /* Cart Preview */
    .cart-preview {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #F44336;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    /* Loading Indicator */
    .loading-indicator {
        display: flex;
        justify-content: center;
        padding: 30px 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 0;
    }

    .empty-state-icon {
        font-size: 60px;
        color: #BDBDBD;
        margin-bottom: 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .product-image-container {
            height: 180px;
        }

        .hero-section {
            padding: 30px 20px;
        }

        .cart-preview {
            bottom: 20px;
            right: 20px;
        }
    }

    @media (max-width: 576px) {
        .product-image-container {
            height: 150px;
        }

        .hero-section {
            padding: 20px 15px;
        }
    }
</style>
<div class="page-wrapper">
    <div class="page">
        <div class="ecommerce-container">
            <!-- Hero Banner -->
            <div class="hero-section">
                <div class="hero-content">
                    <h3 style="margin-top: 0; font-weight: 700;">Selamat Berbelanja di Toko Kami</h3>
                    <p style="font-size: 16px; margin-bottom: 20px;">Temukan produk berkualitas dengan harga terbaik dan penawaran spesial setiap hari.</p>
                    <a href="#product-list" class="btn white blue-text waves-effect" style="font-weight: 600;">Jelajahi Produk</a>
                </div>
                <div class="hero-bg-pattern"></div>
            </div>

            <!-- Category Filter -->
            <div class="card">
                <div class="card-content">
                    <h5 class="card-title" style="margin-bottom: 15px;">Kategori Produk</h5>
                    <div class="category-filter" id="category-chips">
                        <!-- Categories will be loaded via JS -->
                    </div>
                </div>
            </div>

            <!-- Search and Sort -->
            <div class="card">
                <div class="card-content">
                    <div class="search-sort-container">
                        <div class="search-box input-field">
                            <i class="material-icons prefix">search</i>
                            <input type="text" id="search-product" placeholder="Cari produk...">
                        </div>
                        <div class="input-field sort-select">
                            <select id="sort-products">
                                <option value="" disabled selected>Urutkan</option>
                                <option value="price-asc">Harga: Rendah ke Tinggi</option>
                                <option value="price-desc">Harga: Tinggi ke Rendah</option>
                                <option value="newest">Terbaru</option>
                                <option value="popular">Terpopuler</option>
                                <option value="best-selling">Terlaris</option>
                            </select>
                            <label>Urutkan Berdasarkan</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="card">
                <div class="card-content">
                    <h5 class="card-title" style="margin-bottom: 20px;" id="product-list">Daftar Produk</h5>
                    <div class="row" id="products-container">
                        <!-- Products will be loaded via JS -->
                    </div>
                    <div id="loading-products" class="loading-indicator" style="display: none;">
                        <div class="preloader-wrapper active">
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
                    </div>
                    <div id="no-more-products" class="center-align grey-text" style="display: none; padding: 20px 0;">
                        Tidak ada produk lagi untuk ditampilkan
                    </div>
                </div>
            </div>

            <!-- Floating Cart Button -->
            <div class="cart-preview">
                <a class="btn-floating btn-large waves-effect waves-light blue darken-2 tooltipped"
                    id="cart-preview-btn" data-position="left" data-tooltip="Lihat Keranjang">
                    <i class="material-icons">shopping_cart</i>
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
            </div>
        </div>

        <!-- Product Detail Modal -->
        <div id="product-modal" class="modal modal-fixed-footer" style="width: 90%; max-width: 1200px;">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12 m6 l5">
                        <div class="product-gallery">
                            <div class="slider">
                                <ul class="slides" id="product-images">
                                    <!-- Product images will be loaded here -->
                                </ul>
                            </div>
                            <div class="thumbnails row" id="product-thumbnails" style="margin-top: 15px;">
                                <!-- Thumbnails will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l7">
                        <h4 id="modal-product-name"></h4>
                        <div class="product-meta" style="margin: 10px 0 15px;">
                            <span class="product-sku grey-text" id="product-sku"></span>
                            <span class="product-rating" id="product-rating" style="margin-left: 10px;"></span>
                            <span class="review-count grey-text" id="review-count"></span>
                        </div>

                        <div class="product-price-section" style="margin: 20px 0;">
                            <div class="current-price" id="product-price" style="font-size: 28px; font-weight: 700;"></div>
                            <div class="discount-info" id="discount-info"></div>
                        </div>

                        <div class="product-description" style="margin: 20px 0;">
                            <h6>Deskripsi Produk</h6>
                            <div id="modal-product-description" class="flow-text" style="font-size: 14px;"></div>
                        </div>

                        <div class="product-variants" id="product-variants" style="margin: 20px 0;">
                            <!-- Product variants will be loaded here if available -->
                        </div>

                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <input type="number" id="product-quantity" value="1" min="1" max="100">
                                    <label for="product-quantity">Jumlah</label>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="stock-info" id="stock-info" style="padding: 15px 0;">
                                    <!-- Stock info will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <div class="divider" style="margin: 20px 0;"></div>

                        <div class="product-actions">
                            <button class="btn waves-effect waves-light blue darken-2" id="add-to-cart-btn" style="margin-right: 10px;">
                                <i class="material-icons left">add_shopping_cart</i>Tambah ke Keranjang
                            </button>
                            <button class="btn waves-effect waves-light green" id="buy-now-btn">
                                <i class="material-icons left">flash_on</i>Beli Sekarang
                            </button>
                        </div>

                        <div class="product-details-section" style="margin-top: 30px;">
                            <ul class="collapsible expandable">
                                <li>
                                    <div class="collapsible-header active"><i class="material-icons">info</i>Detail Produk</div>
                                    <div class="collapsible-body">
                                        <table class="striped">
                                            <tbody id="product-details">
                                                <!-- Product details will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="material-icons">local_shipping</i>Pengiriman & Pengembalian</div>
                                    <div class="collapsible-body">
                                        <p>Pengiriman dilakukan dalam 1-3 hari kerja setelah pembayaran diterima. Garansi pengembalian 14 hari jika produk tidak sesuai.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="collapsible-header"><i class="material-icons">comment</i>Ulasan Produk</div>
                                    <div class="collapsible-body">
                                        <div id="product-reviews">
                                            <!-- Reviews will be loaded here -->
                                        </div>
                                        <div class="add-review-section" style="margin-top: 30px;">
                                            <h5>Tulis Ulasan Anda</h5>
                                            <div class="input-field">
                                                <input type="text" id="reviewer-name" placeholder="Nama Anda">
                                            </div>
                                            <div class="input-field">
                                                <textarea id="review-text" class="materialize-textarea" placeholder="Bagaimana pengalaman Anda dengan produk ini?"></textarea>
                                            </div>
                                            <div class="rating-input" style="margin: 15px 0;">
                                                <span style="margin-right: 10px;">Rating:</span>
                                                <div class="star-rating" id="star-rating">
                                                    <i class="material-icons star" data-value="1">star_border</i>
                                                    <i class="material-icons star" data-value="2">star_border</i>
                                                    <i class="material-icons star" data-value="3">star_border</i>
                                                    <i class="material-icons star" data-value="4">star_border</i>
                                                    <i class="material-icons star" data-value="5">star_border</i>
                                                </div>
                                            </div>
                                            <button class="btn waves-effect waves-light" id="submit-review">
                                                <i class="material-icons left">send</i>Kirim Ulasan
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Tutup</a>
            </div>
        </div>

        <!-- Cart Modal -->
        <!-- Cart Modal -->
        <div id="cart-modal" class="modal bottom-sheet" style="max-height: 80%;">
            <div class="modal-content">
                <h4>Keranjang Belanja</h4>
                <div id="cart-items">
                    <!-- Item keranjang akan dimuat di sini -->
                </div>
                <div class="cart-summary" id="cart-summary" style="display: none;">
                    <div class="divider"></div>
                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s6">
                            <h5>Subtotal</h5>
                        </div>
                        <div class="col s6 right-align">
                            <h5 id="subtotal-amount">Rp 0</h5>
                        </div>
                    </div>
                    <!-- <div class="row" style="margin-bottom: 0;">
                        <div class="col s6">
                            <h5>Ongkos Kirim</h5>
                        </div>
                        <div class="col s6 right-align">
                            <h5 id="shipping-cost">Rp 0</h5>
                        </div>
                    </div> -->
                    <div class="divider"></div>
                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s6">
                            <h5>Total</h5>
                        </div>
                        <div class="col s6 right-align">
                            <h5 id="total-amount">Rp 0</h5>
                        </div>
                    </div>
                </div>
                <div id="empty-cart-message" class="center-align" style="padding: 40px 0;">
                    <i class="material-icons large grey-text">shopping_cart</i>
                    <h5>Keranjang Belanja Kosong</h5>
                    <p class="grey-text">Tambahkan beberapa produk untuk memulai belanja</p>
                    <a href="#!" class="btn waves-effect waves-light blue modal-close">Lanjutkan Belanja</a>
                </div>
            </div>
            <div class="modal-footer" id="cart-footer" style="display: none;">
                <a href="#checkout" class="waves-effect waves-light btn blue darken-2 modal-trigger modal-close">
                    <i class="material-icons left">shopping_cart</i>Proses Checkout
                </a>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkout" class="modal" style="width: 90%; max-width: 1000px;">
            <div class="modal-content">
                <h4>Checkout</h4>
                <form id="checkout-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="card">
                                <div class="card-content">
                                    <h5 class="card-title">Informasi Pengiriman</h5>

                                    <div class="row">
                                        <div class="col s12">
                                            <div class="input-field">
                                                <input type="text" id="customer-name" required>
                                                <label for="customer-name">Nama Lengkap*</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <input type="email" id="customer-email" required>
                                                <label for="customer-email">Email*</label>
                                            </div>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="input-field">
                                                <input type="tel" id="customer-phone" required>
                                                <label for="customer-phone">No. Telepon*</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12">
                                            <div class="input-field">
                                                <textarea id="shipping-address" class="materialize-textarea" required></textarea>
                                                <label for="shipping-address">Alamat Lengkap*</label>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col s12">
                                            <div class="input-field">
                                                <textarea id="order-notes" class="materialize-textarea"></textarea>
                                                <label for="order-notes">Catatan Pesanan (Opsional)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="card">
                                <div class="card-content">
                                    <h5 class="card-title">Ringkasan Pesanan</h5>
                                    <div id="order-summary">
                                        <!-- Order summary will be loaded here -->
                                    </div>

                                    <div class="divider" style="margin: 15px 0;"></div>

                                    <h5 class="card-title">Metode Pembayaran</h5>
                                    <!-- In your checkout modal, replace the payment-methods section with this: -->
                                    <div class="payment-methods">
                                        <div class="payment-option">
                                            <label>
                                                <input name="payment-method" type="radio" value="transfer" checked />
                                                <span>Transfer Bank</span>
                                            </label>
                                            <div class="payment-details" id="transfer-details">
                                                <p style="margin: 5px 0 0 25px; font-size: 13px;">
                                                    Pembayaran melalui transfer bank akan diverifikasi otomatis
                                                </p>
                                            </div>
                                        </div>

                                        <div class="payment-option">
                                            <label>
                                                <input name="payment-method" type="radio" value="cod" />
                                                <span>COD (Bayar di Tempat)</span>
                                            </label>
                                            <div class="payment-details" id="cod-details" style="display: none;">
                                                <p style="margin: 5px 0 0 25px; font-size: 13px;">
                                                    Bayar ketika barang diterima. Biaya tambahan Rp 10.000.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="payment-option">
                                            <label>
                                                <input name="payment-method" type="radio" value="midtrans" />
                                                <span>Pembayaran Digital (Midtrans)</span>
                                            </label>
                                            <div class="payment-details" id="midtrans-details" style="display: none;">
                                                <div style="margin: 5px 0 0 25px; font-size: 13px;">
                                                    <p>Pilih metode pembayaran digital:</p>
                                                    <select class="browser-default" id="midtrans-method" style="font-size: 13px;">
                                                        <option value="gopay">GoPay</option>
                                                        <option value="shopeepay">ShopeePay</option>
                                                        <option value="qris">QRIS</option>
                                                        <option value="bank_transfer">Transfer Bank</option>
                                                        <option value="credit_card">Kartu Kredit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider" style="margin: 15px 0;"></div>

                                    <div class="order-total">
                                        <div class="row" style="margin-bottom: 0;">
                                            <div class="col s6">
                                                <h5>Total</h5>
                                            </div>
                                            <div class="col s6 right-align">
                                                <h5 id="checkout-total">Rp 0</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="terms-agreement" style="margin-top: 20px;">
                                        <label>
                                            <input type="checkbox" id="agree-terms" required />
                                            <span>Saya menyetujui <a href="#!" class="blue-text">Syarat & Ketentuan</a></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-grey btn-flat">Batal</a>
                <button class="btn waves-effect waves-light blue darken-2" type="submit" form="checkout-form">
                    <i class="material-icons left">check</i>Konfirmasi Pesanan
                </button>
            </div>
        </div>

        <!-- Order Confirmation Modal -->
        <div id="order-confirmation" class="modal">
            <div class="modal-content center-align">
                <i class="material-icons large green-text" style="font-size: 80px;">check_circle</i>
                <h4>Pesanan Berhasil!</h4>
                <p>Terima kasih telah berbelanja di toko kami.</p>
                <div class="order-details" style="margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px;">
                    <p style="margin: 5px 0;">No. Pesanan: <strong id="order-number">#123456</strong></p>
                    <p style="margin: 5px 0;">Total Pembayaran: <strong id="order-total">Rp 0</strong></p>
                </div>
                <p>Kami telah mengirimkan detail pesanan ke email Anda. Silakan lakukan pembayaran untuk memproses pesanan.</p>
            </div>
            <div class="modal-footer center-align">
                <a href="#!" class="modal-close waves-effect waves-light btn blue">Tutup</a>
                <a href="<?= base_url('/orders') ?>" class="waves-effect waves-light btn green">Lihat Pesanan Saya</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>