<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<h1 class="page-title mb-4">Dashboard</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">

            <div class="row">
                <!-- Card 1 - Pengajuan -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-blue">
                        <div class="card-content">
                            <i class="fas fa-box card-icon"></i>
                            <p class="card-title">Total Pengajuan</p>
                            <h3 class="card-value total-pengajuan"></h3>

                        </div>

                    </div>
                </div>

                <!-- Card 2 - Proses -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-pink">
                        <div class="card-content">
                            <i class="fas fa-tags card-icon"></i>
                            <p class="card-title">Sedang Proses Pengiriman</p>
                            <h3 class="card-value total-proses"></h3>

                        </div>

                    </div>
                </div>

                <!-- Card 3 - Selesai -->
                <div class="col s12 m4">
                    <div class="dashboard-card card-teal">
                        <div class="card-content">
                            <i class="fas fa-shopping-cart card-icon"></i>
                            <p class="card-title">Total Pengiriman Selesai</p>
                            <h3 class="card-value total-selesai"></h3>

                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?= $this->endSection() ?>