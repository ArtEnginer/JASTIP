<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<style>
    .dashboard-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 25px;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .card-blue {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
    }

    .card-pink {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF0D6E 100%);
    }

    .card-teal {
        background: linear-gradient(135deg, #4DCCFF 0%, #00A8CC 100%);
    }

    .card-purple {
        background: linear-gradient(135deg, #A78BFA 0%, #7E3AF2 100%);
    }

    .card-orange {
        background: linear-gradient(135deg, #FF9A3E 0%, #FF5E0D 100%);
    }

    .card-green {
        background: linear-gradient(135deg, #7CFF6B 0%, #00D100 100%);
    }

    .card-content {
        padding: 25px;
        color: white;
        position: relative;
    }

    .card-icon {
        position: absolute;
        right: 25px;
        top: 25px;
        font-size: 50px;
        opacity: 0.2;
    }

    .card-title {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        margin: 10px 0;
    }

    .card-footer {
        background: rgba(255, 255, 255, 0.15);
        padding: 12px 25px;
        font-size: 13px;
        display: flex;
        align-items: center;
    }

    .card-footer i {
        margin-right: 8px;
    }
</style>

<h1 class="page-title mb-4">Dashboard Ecommerce</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <!-- Baris Pertama -->
            <div class="row">
                <!-- Card 1 - Produk -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-blue">
                        <div class="card-content">
                            <i class="fas fa-box card-icon"></i>
                            <p class="card-title">Total Produk</p>
                            <h3 class="card-value"><?= $total_products ?? '1,254' ?></h3>
                            <p><?= $new_products_this_month ?? '42' ?> baru bulan ini</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-sync-alt"></i>
                            <span>Update real-time</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 - Kategori -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-pink">
                        <div class="card-content">
                            <i class="fas fa-tags card-icon"></i>
                            <p class="card-title">Total Kategori</p>
                            <h3 class="card-value"><?= $total_categories ?? '28' ?></h3>
                            <p><?= $popular_category ?? 'Elektronik' ?> paling laris</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-chart-line"></i>
                            <span>5 kategori baru bulan ini</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Transaksi -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-teal">
                        <div class="card-content">
                            <i class="fas fa-shopping-cart card-icon"></i>
                            <p class="card-title">Total Transaksi</p>
                            <h3 class="card-value"><?= $total_transactions ?? '3,842' ?></h3>
                            <p><?= $transactions_today ?? '24' ?> transaksi hari ini</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-calendar-day"></i>
                            <span><?= date('d F Y') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris Kedua -->
            <div class="row">
                <!-- Card 4 - Pendapatan -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-purple">
                        <div class="card-content">
                            <i class="fas fa-dollar-sign card-icon"></i>
                            <p class="card-title">Pendapatan Bulan Ini</p>
                            <h3 class="card-value">Rp <?= isset($monthly_income) ? number_format($monthly_income, 0, ',', '.') : '56,250,000' ?></h3>
                            <p><?= isset($income_growth) ? $income_growth : '12.5' ?>% dari bulan lalu</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i>
                            <span>Target tercapai <?= isset($target_achievement) ? $target_achievement : '78' ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- Card 5 - Pelanggan -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-orange">
                        <div class="card-content">
                            <i class="fas fa-users card-icon"></i>
                            <p class="card-title">Total Pelanggan</p>
                            <h3 class="card-value"><?= $total_customers ?? '1,845' ?></h3>
                            <p><?= $new_customers_this_month ?? '68' ?> pelanggan baru</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-user-plus"></i>
                            <span><?= $customer_growth_rate ?? '8.2' ?>% pertumbuhan</span>
                        </div>
                    </div>
                </div>

                <!-- Card 6 - Rating -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-green">
                        <div class="card-content">
                            <i class="fas fa-star card-icon"></i>
                            <p class="card-title">Rating Produk</p>
                            <h3 class="card-value"><?= isset($average_rating) ? number_format($average_rating, 1) : '4.7' ?>/5</h3>
                            <p><?= $total_reviews ?? '892' ?> ulasan customer</p>
                        </div>
                        <div class="card-footer">
                            <i class="fas fa-thumbs-up"></i>
                            <span><?= $positive_reviews_percentage ?? '92' ?>% ulasan positif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>