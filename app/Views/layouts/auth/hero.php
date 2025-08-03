<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f5f5;
        color: #333;
    }

    .nav-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        background-color: #ff6b6b;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin: 10px;
        transition: all 0.3s ease;
    }

    .nav-btn:hover {
        background-color: #ff5252;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-btn i {
        margin-right: 5px;
    }

    .abs-topleft {
        position: absolute;
        top: 0;
        left: 0;
    }

    .white {
        color: white;
    }

    header {
        background-color: #4d96ff;
        color: white;
        text-align: center;
        padding: 60px 20px 30px;
        position: relative;
        margin-bottom: 30px;
    }

    h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .product-card {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-info {
        padding: 20px;
    }

    .product-category {
        display: inline-block;
        background-color: #e0f7fa;
        color: #00838f;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 1.2rem;
        margin-bottom: 8px;
        color: #333;
    }

    .product-desc {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: bold;
        color: #ff6b6b;
        margin-bottom: 15px;
    }

    .product-stock {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
    }

    .in-stock {
        color: #4caf50;
    }

    .low-stock {
        color: #ff9800;
    }

    .out-of-stock {
        color: #f44336;
    }

    .add-to-cart {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #4d96ff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .add-to-cart:hover {
        background-color: #3a7bd5;
    }

    .add-to-cart:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    footer {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 40px;
    }
</style>

<header>
    <h1>Produk Bayi Terbaik</h1>
    <p>Temukan berbagai kebutuhan bayi dengan kualitas terbaik</p>
</header>

<div class="container">
    <div class="products-grid" id="products-container">
        <!-- Produk akan dimuat di sini melalui JavaScript -->
    </div>
</div>

<footer>
    <p>&copy; 2025 Toko Bayi. All Rights Reserved.</p>
</footer>
<script>
    // Fungsi untuk memformat harga ke format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Fungsi untuk menentukan status stok
    function getStockStatus(stok) {
        if (stok > 50) return 'in-stock';
        if (stok > 0) return 'low-stock';
        return 'out-of-stock';
    }

    // Fungsi untuk menampilkan produk
    function displayProducts(products) {
        const productsContainer = document.getElementById('products-container');
        productsContainer.innerHTML = ''; // Kosongkan container sebelum menambahkan produk baru

        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';

            const stockStatus = getStockStatus(product.stok);
            const stockText = stockStatus === 'in-stock' ? 'Tersedia' :
                stockStatus === 'low-stock' ? 'Stok terbatas' : 'Stok habis';

            productCard.innerHTML = `
                <img src="<?= base_url('assets/images/') ?>${product.gambar}" alt="${product.nama}" class="product-image">
                <div class="product-info">
                    <span class="product-category">${product.kategori.nama}</span>
                    <h3 class="product-name">${product.nama}</h3>
                    <p class="product-desc">${product.deskripsi}</p>
                    <div class="product-price">${formatRupiah(product.harga)}</div>
                    <div class="product-stock ${stockStatus}">${stockText} (${product.stok})</div>
                    <button class="add-to-cart" ${product.stok === 0 ? 'disabled' : ''}>
                        <i class="material-icons" style="vertical-align: middle; font-size: 1rem;">add_shopping_cart</i> Tambah ke Keranjang
                    </button>
                </div>
            `;

            productsContainer.appendChild(productCard);
        });
    }

    // Fungsi untuk mengambil data dari API
    async function fetchProducts() {
        try {
            const response = await fetch('api/v2/produk');

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            displayProducts(data);
        } catch (error) {
            console.error('Error fetching products:', error);
            // Tampilkan pesan error ke pengguna atau fallback ke data lokal
            document.getElementById('products-container').innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 20px; color: #f44336;">
                    <p>Gagal memuat produk. Silakan coba lagi nanti.</p>
                </div>
            `;
        }
    }

    // Panggil fungsi untuk mengambil dan menampilkan produk saat halaman dimuat
    document.addEventListener('DOMContentLoaded', fetchProducts);
</script>