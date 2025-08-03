<?php

/** @var \CodeIgniter\View\View $this */ ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BabyShop - Toko online perlengkapan bayi terbaik dengan kualitas premium">

    <!--Import Google Icon Font-->
    <?= $this->include('layouts/head') ?>
    <?= $this->renderSection('head') ?>
    <?= $this->include('layouts/style') ?>

    <link type="text/css" rel="stylesheet" href="<?= base_url('css/materialize.min.css') ?>" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="<?= base_url('css/pages/auth.css') ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4a6bff;
            --primary-dark: #3a56cc;
            --secondary-color: #ff6b6b;
            --accent-color: #4d96ff;
            --text-dark: #2d3748;
            --text-medium: #4a5568;
            --text-light: #718096;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --error-color: #f56565;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }

        /* Layout */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            padding: 4rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            font-size: 2rem;
            color: var(--text-dark);
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            margin: 0.5rem auto 0;
            border-radius: 2px;
        }

        /* Navigation */
        .main-nav {
            background-color: var(--bg-white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: fixed;
            width: 100%;
            z-index: 1000;
            padding: 1rem 0;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.8rem;
            display: flex;
            align-items: center;
        }

        .brand-logo i {
            margin-right: 0.5rem;
            font-size: 2rem;
        }

        .nav-actions {
            display: flex;
            align-items: center;
        }

        .nav-icon {
            margin-left: 1.5rem;
            cursor: pointer;
            color: var(--text-medium);
            transition: var(--transition);
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-icon:hover {
            color: var(--primary-color);
        }

        .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 10rem 0 5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: var(--bg-light);
            transform: skewY(-2deg);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-btn {
            display: inline-block;
            background: white;
            color: var(--primary-color);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .product-card {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--secondary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            z-index: 1;
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 220px;
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

        .product-info {
            padding: 1.5rem;
        }

        .product-category {
            display: inline-block;
            background: #e0f7fa;
            color: #00838f;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
            font-weight: 500;
        }

        .product-name {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-desc {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-rating {
            color: #ffb400;
            font-size: 0.9rem;
        }

        .product-stock {
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .stock-icon {
            margin-right: 0.5rem;
        }

        .in-stock {
            color: var(--success-color);
        }

        .low-stock {
            color: var(--warning-color);
        }

        .out-of-stock {
            color: var(--error-color);
        }

        .add-to-cart {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-to-cart i {
            margin-right: 0.5rem;
        }

        .add-to-cart:hover {
            background: var(--primary-dark);
        }

        .add-to-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Footer */
        footer {
            background: var(--text-dark);
            color: white;
            padding: 4rem 0 0;
            margin-top: 4rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.8rem;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background: var(--primary-color);
        }

        .footer-about p {
            color: #ddd;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 0.8rem;
        }

        .footer-column ul li a {
            color: #ddd;
            font-size: 0.95rem;
            transition: var(--transition);
            display: inline-block;
        }

        .footer-column ul li a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .footer-newsletter input {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .footer-newsletter input::placeholder {
            color: #ccc;
        }

        .footer-newsletter button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .footer-newsletter button:hover {
            background: var(--primary-dark);
        }

        .copyright {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }

        /* Auth Modal */
        .modal {
            max-width: 500px;
            border-radius: var(--border-radius);
            padding: 2rem;
            background: var(--bg-white);
        }

        .modal h4 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 0.8rem;
            cursor: pointer;
            color: var(--text-light);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .auth-tab.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-color);
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .input-field {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-field label {
            color: var(--text-medium);
            font-size: 0.95rem;
        }

        .input-field input {
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            padding: 0.8rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: var(--transition);
        }

        .input-field input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 107, 255, 0.2);
        }

        .btn-auth {
            width: 100%;
            background: var(--primary-color);
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: var(--border-radius);
            color: white;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-auth:hover {
            background: var(--primary-dark);
        }

        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .auth-link a {
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero {
                padding: 8rem 0 4rem;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .nav-container {
                padding: 0 1rem;
            }

            .nav-icon {
                margin-left: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <?= $this->renderSection('style') ?>
</head>

<body>

    <!-- Navigation -->

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Perlengkapan Bayi Berkualitas Tinggi</h1>
            <p>Temukan berbagai kebutuhan bayi dengan bahan premium dan desain ergonomis untuk kenyamanan buah hati Anda</p>
            <a href="#products" class="hero-btn">Belanja Sekarang</a>

        </div>
    </section>

    <!-- Main Content -->
    <main class="section">
        <div class="container">
            <h2 class="section-title" id="products">Produk Terbaru</h2>
            <div class="products-grid" id="products-container">
                <!-- Products will be loaded here via JavaScript -->
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column footer-about">
                    <h3>Tentang Kami</h3>
                    <p>BabyShop berkomitmen menyediakan produk bayi berkualitas tinggi dengan standar keamanan terbaik untuk tumbuh kembang optimal buah hati Anda.</p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="material-icons">facebook</i></a>
                        <a href="#" title="Instagram"><i class="material-icons">photo_camera</i></a>
                        <a href="#" title="Twitter"><i class="material-icons">twitter</i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Kategori</h3>
                    <ul>
                        <li><a href="#">Pakaian Bayi</a></li>
                        <li><a href="#">Perlengkapan Makan</a></li>
                        <li><a href="#">Mainan Edukatif</a></li>
                        <li><a href="#">Perawatan Bayi</a></li>
                        <li><a href="#">Kesehatan & Keamanan</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="#">Cara Pembelian</a></li>
                        <li><a href="#">Pengembalian Produk</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-column footer-newsletter">
                    <h3>Newsletter</h3>
                    <p>Dapatkan promo dan penawaran terbaik langsung ke email Anda</p>
                    <form>
                        <input type="email" placeholder="Alamat Email Anda">
                        <button type="submit">Berlangganan</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 BabyShop. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal Structure -->
    <div id="auth-modal" class="modal">
        <h4>Selamat Datang di BabyShop</h4>
        <div class="auth-tabs">
            <div class="auth-tab active" data-tab="login">Masuk</div>
            <div class="auth-tab" data-tab="register">Daftar</div>
        </div>

        <!-- Login Form -->
        <form id="login-form" class="auth-form active" action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="input-field">
                <label for="login-email">Email</label>
                <input id="login-email" name="email" type="email" class="validate" required>
            </div>
            <div class="input-field">
                <label for="login-password">Kata Sandi</label>
                <input id="login-password" name="password" type="password" class="validate" required>
            </div>
            <button type="submit" class="btn-auth">
                Masuk
            </button>
            <div class="auth-link">
                Lupa password? <a href="#">Klik disini</a>
            </div>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="auth-form" action="<?= base_url('register') ?>" method="post">
            <?= csrf_field() ?>
            <div class="input-field">
                <label for="register-name">Nama Lengkap</label>
                <input id="register-name" name="name" type="text" class="validate" required>
            </div>
            <div class="input-field">
                <label for="register-email">Email</label>
                <input id="register-email" name="email" type="email" class="validate" required>
            </div>
            <div class="input-field">
                <label for="register-password">Kata Sandi</label>
                <input id="register-password" name="password" type="password" class="validate" required>
            </div>
            <div class="input-field">
                <label for="register-confirm">Konfirmasi Kata Sandi</label>
                <input id="register-confirm" name="confirm_password" type="password" class="validate" required>
            </div>
            <button type="submit" class="btn-auth">
                Daftar
            </button>
        </form>
    </div>

    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="<?= base_url('js/materialize.min.js') ?>"></script>
    <?= $this->include('layouts/script') ?>
    <script>
        const baseUrl = '<?= base_url() ?>';

        // Initialize modal
        document.addEventListener('DOMContentLoaded', function() {
            const modal = M.Modal.init(document.getElementById('auth-modal'), {
                preventScrolling: false
            });

            // Open modal when auth trigger is clicked
            document.getElementById('auth-trigger').addEventListener('click', function() {
                modal.open();
            });

            // Tab switching
            const tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Show corresponding form
                    const tabName = this.getAttribute('data-tab');
                    document.querySelectorAll('.auth-form').forEach(form => {
                        form.classList.remove('active');
                    });
                    document.getElementById(`${tabName}-form`).classList.add('active');
                });
            });
        });

        // Products display functions
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function getStockStatus(stok) {
            if (stok > 50) return 'in-stock';
            if (stok > 0) return 'low-stock';
            return 'out-of-stock';
        }

        function getStockIcon(stok) {
            if (stok > 50) return 'check_circle';
            if (stok > 0) return 'error';
            return 'cancel';
        }

        function displayProducts(products) {
            const productsContainer = document.getElementById('products-container');
            productsContainer.innerHTML = '';

            if (products.length === 0) {
                productsContainer.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                        <i class="material-icons" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;">child_care</i>
                        <h3 style="color: var(--text-light); margin-bottom: 1rem;">Produk Tidak Tersedia</h3>
                        <p>Maaf, saat ini tidak ada produk yang tersedia. Silakan cek kembali nanti.</p>
                    </div>
                `;
                return;
            }

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';

                const stockStatus = getStockStatus(product.stok);
                const stockIcon = getStockIcon(product.stok);
                const stockText = stockStatus === 'in-stock' ? 'Tersedia' :
                    stockStatus === 'low-stock' ? 'Stok terbatas' : 'Stok habis';

                productCard.innerHTML = `
                    <div class="product-image-container">
                        <img src="<?= base_url('api/v2/source/storage/') ?>${product.gambar}" alt="${product.nama}" class="product-image">
                        ${product.diskon ? `<span class="product-badge">${product.diskon}% OFF</span>` : ''}
                    </div>
                    <div class="product-info">
                        <span class="product-category">${product.kategori.nama}</span>
                        <h3 class="product-name">${product.nama}</h3>
                        <p class="product-desc">${product.deskripsi}</p>
                        <div class="product-price-container">
                            <span class="product-price">${formatRupiah(product.harga)}</span>
                            <span class="product-rating">
                                <i class="material-icons" style="font-size: 1rem; vertical-align: middle;">star</i>
                                ${product.rating || '4.8'}
                            </span>
                        </div>
                        <div class="product-stock ${stockStatus}">
                            <i class="material-icons stock-icon" style="font-size: 1rem;">${stockIcon}</i>
                            ${stockText} (${product.stok})
                        </div>
                        <a href="${baseUrl}/login" class="add-to-cart">
                            <i class="material-icons">add_shopping_cart</i> Tambah ke Keranjang
                        </a>
                    </div>
                `;

                productsContainer.appendChild(productCard);
            });
        }

        async function fetchProducts() {
            try {
                // Show loading state
                document.getElementById('products-container').innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                        <div class="preloader-wrapper active">
                            <div class="spinner-layer spinner-blue-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                        <p style="margin-top: 1rem; color: var(--text-light);">Memuat produk...</p>
                    </div>
                `;

                const response = await fetch('api/v2/produk');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                displayProducts(data);
            } catch (error) {
                console.error('Error fetching products:', error);
                document.getElementById('products-container').innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--error-color);">
                        <i class="material-icons" style="font-size: 3rem; margin-bottom: 1rem;">error_outline</i>
                        <h3 style="margin-bottom: 1rem;">Gagal Memuat Produk</h3>
                        <p>Silakan coba lagi nanti atau hubungi tim dukungan kami.</p>
                        <button onclick="fetchProducts()" style="margin-top: 1rem; background: var(--primary-color); color: white; border: none; padding: 0.5rem 1.5rem; border-radius: var(--border-radius); cursor: pointer;">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', fetchProducts);
    </script>
    <?= $this->renderSection('script') ?>
</body>

</html>