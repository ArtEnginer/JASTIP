<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Jastip - JASTIP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff 0%, #ffe5b4 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.08);
            margin-bottom: 30px;
        }

        .search-section {
            background: linear-gradient(135deg, #ff9800 0%, #fff3e0 100%);
            color: #fff;
            border-radius: 20px;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.08);
        }

        .search-input {
            border-radius: 25px;
            border: 1px solid #ff9800;
            padding: 15px 25px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(255, 140, 0, 0.08);
            background: #fff;
            color: #333;
        }

        .search-btn {
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: 600;
            border: none;
            background: linear-gradient(90deg, #ff9800 0%, #ffb74d 100%);
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 140, 0, 0.08);
        }

        .search-btn:hover {
            background: linear-gradient(90deg, #fb8c00 0%, #ff9800 100%);
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 5px 15px rgba(255, 140, 0, 0.15);
        }

        .content-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.08);
            border: none;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .content-card .card-header {
            background: linear-gradient(90deg, #ff9800 0%, #ffb74d 100%);
            color: #fff;
            border: none;
            padding: 20px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .content-card .card-body {
            padding: 30px;
        }

        .info-item {
            margin-bottom: 20px;
            padding: 15px;
            background: #fff8f0;
            border-radius: 10px;
            border-left: 4px solid #ff9800;
        }

        .info-item label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ff9800;
            margin-bottom: 5px;
            display: block;
        }

        .info-item .value {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .status-card {
            background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
            color: #fff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.08);
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #ff9800, #ffb74d);
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -32px;
            top: 8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ff9800;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #ff9800;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-content {
            background: #fff8f0;
            padding: 20px;
            border-radius: 15px;
            border-left: 4px solid #ff9800;
            transition: transform 0.3s ease;
        }

        .timeline-content:hover {
            transform: translateX(5px);
        }

        .timeline-content h6 {
            margin-bottom: 8px;
            color: #ff9800;
            font-weight: 600;
        }

        .timeline-content .time {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .loading-spinner {
            text-align: center;
            padding: 60px 20px;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #ff9800;
        }

        .error-alert {
            border-radius: 15px;
            border: none;
            padding: 30px;
            background: linear-gradient(135deg, #ff7043 0%, #ff9800 100%);
            color: white;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.08);
        }

        .error-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .brand-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(255, 140, 0, 0.15);
            color: #fff;
        }

        .brand-subtitle {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
            color: #fff;
        }

        @media (max-width: 768px) {
            .brand-title {
                font-size: 2.5rem;
            }

            .search-section {
                padding: 40px 15px;
            }

            .content-card .card-body {
                padding: 20px;
            }

            .status-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="search-section">
            <div class="container">
                <h1 class="brand-title">
                    <i class="fas fa-shipping-fast me-3"></i>
                    JASTIP TRACKER
                </h1>
                <p class="brand-subtitle">Lacak status pengiriman jastip Anda dengan mudah dan real-time</p>

                <!-- Search Form -->
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="input-group input-group-lg">
                            <input type="text"
                                class="form-control search-input"
                                id="trackingInput"
                                placeholder="Masukkan nomor resi Anda di sini..."
                                autocomplete="off">
                            <button class="btn search-btn"
                                type="button"
                                id="trackButton">
                                <i class="fas fa-search me-2"></i>Lacak Sekarang
                            </button>
                        </div>
                        <small class="text-white-50 mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Contoh: 123xx, ABC123, dll.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Section -->
        <div id="loadingSection" class="d-none">
            <div class="content-card">
                <div class="card-body loading-spinner">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="text-primary">Mencari data pengiriman...</h5>
                    <p class="text-muted">Harap tunggu sebentar</p>
                </div>
            </div>
        </div>

        <!-- Error Section -->
        <div id="errorSection" class="d-none">
            <div class="alert error-alert text-center" role="alert">
                <i class="fas fa-exclamation-triangle error-icon"></i>
                <h4 class="alert-heading">Data Tidak Ditemukan</h4>
                <p class="mb-0" id="errorMessage">Nomor resi yang Anda masukkan tidak ditemukan. Pastikan nomor resi benar dan coba lagi.</p>
            </div>
        </div>

        <!-- Result Section -->
        <div id="resultSection" class="d-none">
            <!-- Current Status Card -->
            <div class="status-card" id="statusCard">
                <div class="status-icon">
                    <i class="fas fa-truck" id="statusIcon"></i>
                </div>
                <h2 class="mb-3" id="currentStatus">Status Terkini</h2>
                <p class="mb-0 opacity-75" id="lastUpdate">Terakhir diupdate: -</p>
            </div>

            <!-- Package Info Card -->
            <div class="content-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2"></i>
                        Informasi Pengiriman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="info-item">
                                <label>Nomor Resi</label>
                                <div class="value" id="displayResi">-</div>
                            </div>
                            <div class="info-item">
                                <label>Nama Penerima</label>
                                <div class="value" id="displayNama">-</div>
                            </div>
                            <div class="info-item">
                                <label>No. Telepon</label>
                                <div class="value" id="displayTelepon">-</div>
                            </div>
                            <div class="info-item">
                                <label>Biaya Pengiriman</label>
                                <div class="value text-success" id="displayBiaya">-</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-item">
                                <label>Alamat Penerima</label>
                                <div class="value" id="displayAlamat">-</div>
                            </div>
                            <div class="info-item">
                                <label>Bobot Paket</label>
                                <div class="value" id="displayBobot">-</div>
                            </div>
                            <div class="info-item">
                                <label>Keterangan</label>
                                <div class="value" id="displayKeterangan">-</div>
                            </div>
                            <div class="info-item">
                                <label>Catatan</label>
                                <div class="value" id="displayCatatan">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline/History Card -->
            <div class="content-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Status Pengiriman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline" id="statusTimeline">
                        <!-- Timeline items will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackingInput = document.getElementById('trackingInput');
            const trackButton = document.getElementById('trackButton');
            const loadingSection = document.getElementById('loadingSection');
            const errorSection = document.getElementById('errorSection');
            const resultSection = document.getElementById('resultSection');

            // Event listeners
            trackButton.addEventListener('click', performTracking);
            trackingInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performTracking();
                }
            });

            // Auto focus on input
            trackingInput.focus();

            function performTracking() {
                const resi = trackingInput.value.trim();

                if (!resi) {
                    showAlert('Silakan masukkan nomor resi');
                    trackingInput.focus();
                    return;
                }

                // Hide all sections
                hideAllSections();

                // Show loading
                loadingSection.classList.remove('d-none');

                // Call API
                fetch(`<?= base_url() ?>/api/v2/jastip/track/${encodeURIComponent(resi)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Data tidak ditemukan');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideAllSections();
                        displayTrackingResult(data);
                    })
                    .catch(error => {
                        hideAllSections();
                        showError(error.message);
                        console.error('Error:', error);
                    });
            }

            function hideAllSections() {
                loadingSection.classList.add('d-none');
                errorSection.classList.add('d-none');
                resultSection.classList.add('d-none');
            }

            function showError(message) {
                document.getElementById('errorMessage').textContent = message;
                errorSection.classList.remove('d-none');
                // Scroll to error
                errorSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function showAlert(message) {
                alert(message);
            }

            function displayTrackingResult(data) {
                // Populate package info
                document.getElementById('displayResi').textContent = data.nomor_resi || '-';
                document.getElementById('displayNama').textContent = data.nama_penerima || '-';
                document.getElementById('displayTelepon').textContent = data.no_telp_penerima || '-';
                document.getElementById('displayBiaya').textContent = data.biaya ? `Rp ${formatNumber(data.biaya)}` : '-';
                document.getElementById('displayAlamat').textContent = data.alamat_penerima || '-';
                document.getElementById('displayBobot').textContent = data.bobot ? `${data.bobot} kg` : '-';
                document.getElementById('displayKeterangan').textContent = data.keterangan || '-';
                document.getElementById('displayCatatan').textContent = data.catatan || '-';

                // Update current status
                document.getElementById('currentStatus').textContent = data.status || 'Status Tidak Diketahui';
                document.getElementById('lastUpdate').textContent = `Terakhir diupdate: ${formatDate(data.updated_at)}`;

                // Update status icon and color based on status
                updateStatusDisplay(data.status);

                // Populate timeline
                populateTimeline(data.status_history || []);

                // Show result section
                resultSection.classList.remove('d-none');

                // Scroll to result
                resultSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function updateStatusDisplay(status) {
                const statusIcon = document.getElementById('statusIcon');
                const statusCard = document.getElementById('statusCard');

                const statusConfig = {
                    'Proses Pengiriman': {
                        icon: 'fas fa-truck',
                        gradient: 'linear-gradient(135deg, #ff9800 0%, #ffb74d 100%)'
                    },
                    'Dalam Perjalanan': {
                        icon: 'fas fa-shipping-fast',
                        gradient: 'linear-gradient(135deg, #ffb74d 0%, #ff9800 100%)'
                    },
                    'Tiba di Tujuan': {
                        icon: 'fas fa-map-marker-alt',
                        gradient: 'linear-gradient(135deg, #fff3e0 0%, #ff9800 100%)'
                    },
                    'Selesai': {
                        icon: 'fas fa-check-circle',
                        gradient: 'linear-gradient(135deg, #43a047 0%, #ff9800 100%)'
                    },
                    'Dibatalkan': {
                        icon: 'fas fa-times-circle',
                        gradient: 'linear-gradient(135deg, #ff7043 0%, #ff9800 100%)'
                    }
                };

                const config = statusConfig[status] || {
                    icon: 'fas fa-question-circle',
                    gradient: 'linear-gradient(135deg, #bdbdbd 0%, #ff9800 100%)'
                };

                statusIcon.className = `${config.icon}`;
                statusCard.style.background = config.gradient;
            }

            function populateTimeline(statusHistory) {
                const timeline = document.getElementById('statusTimeline');
                timeline.innerHTML = '';

                if (!statusHistory || statusHistory.length === 0) {
                    timeline.innerHTML = '<div class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>Belum ada riwayat status</div>';
                    return;
                }

                // Sort by created_at descending (newest first)
                const sortedHistory = statusHistory.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                sortedHistory.forEach((item, index) => {
                    const timelineItem = document.createElement('div');
                    timelineItem.className = 'timeline-item';

                    timelineItem.innerHTML = `
                        <div class="timeline-content">
                            <h6>${item.status}</h6>
                            <div class="time">${formatDate(item.created_at)}</div>
                        </div>
                    `;

                    timeline.appendChild(timelineItem);
                });
            }

            function formatDate(dateString) {
                if (!dateString) return '-';

                try {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    return dateString;
                }
            }

            function formatNumber(number) {
                if (!number) return '0';

                try {
                    return new Intl.NumberFormat('id-ID').format(number);
                } catch (e) {
                    return number.toString();
                }
            }
        });
    </script>
</body>

</html>