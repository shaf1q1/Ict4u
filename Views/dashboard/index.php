<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --soft-lilac: #f5f3ff;
        --deep-lilac: #8b5cf6;
        --soft-emerald: #ecfdf5;
        --emerald-green: #10b981;
    }

    .stat-card {
        border: none;
        border-radius: 28px;
        padding: 1.8rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.05) !important;
    }

    .bg-lilac-premium { background: var(--soft-lilac); color: var(--deep-lilac); }
    .bg-emerald-premium { background: var(--soft-emerald); color: var(--emerald-green); }

    .icon-box {
        width: 55px; height: 55px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1.2rem;
        background: white;
        box-shadow: 0 8px 15px rgba(0,0,0,0.04);
    }

    .chart-card {
        border-radius: 30px;
        border: none;
        background: white;
        padding: 2rem;
    }

    /* Layout Tambahan: Action Buttons */
    .btn-action-card {
        background: var(--soft-lilac);
        border: 2px dashed var(--deep-lilac);
        border-radius: 20px;
        color: var(--deep-lilac);
        padding: 20px;
        text-align: center;
        transition: 0.3s;
        cursor: pointer;
        display: block;
        text-decoration: none;
    }

    .btn-action-card:hover {
        background: var(--deep-lilac);
        color: white;
    }
</style>

<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card shadow-sm" style="background: var(--deep-lilac); color: white;">
            <div class="icon-box text-purple bg-opacity-20 bg-white text-white">
                <i class="bi bi-patch-check"></i>
            </div>
            <h6 class="fw-700 small text-uppercase opacity-75 mb-1">Servis Kelulusan</h6>
            <h2 class="fw-800 mb-0"><?= number_format($totalServisKelulusan) ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card shadow-sm" style="background: var(--emerald-green); color: white;">
            <div class="icon-box bg-white bg-opacity-20 text-white border-0">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <h6 class="fw-700 small text-uppercase opacity-75 mb-1 text-white">Dokumen Lulus</h6>
            <h2 class="fw-800 mb-0"><?= number_format($dokApproved) ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-emerald-premium shadow-sm border">
            <div class="icon-box text-success">
                <i class="bi bi-folder2-open"></i>
            </div>
            <h6 class="fw-700 small text-uppercase mb-1 opacity-75">Jumlah Dokumen</h6>
            <h2 class="fw-800 mb-0"><?= number_format($totalDokumen) ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-lilac-premium shadow-sm border">
            <div class="icon-box text-purple">
                <i class="bi bi-ui-checks-grid"></i>
            </div>
            <h6 class="fw-700 small text-uppercase mb-1 opacity-75">Perincian Modul</h6>
            <h2 class="fw-800 mb-0"><?= number_format($totalPerincianModul) ?></h2>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-4">
        <div class="chart-card shadow-sm h-100">
            <h5 class="fw-800 mb-4 text-dark">Status Keseluruhan</h5>
            <div style="height: 200px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-bold text-warning">Pending</span>
                    <span class="small fw-bold"><?= $dokPending ?></span>
                </div>
                <div class="progress rounded-pill mb-3" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: <?= ($totalDokumen > 0) ? ($dokPending/$totalDokumen)*100 : 0 ?>%"></div>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-bold text-success">Approved</span>
                    <span class="small fw-bold"><?= $dokApproved ?></span>
                </div>
                <div class="progress rounded-pill mb-3" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: <?= ($totalDokumen > 0) ? ($dokApproved/$totalDokumen)*100 : 0 ?>%"></div>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-bold text-danger">Rejected</span>
                    <span class="small fw-bold"><?= $dokRejected ?></span>
                </div>
                <div class="progress rounded-pill" style="height: 8px;">
                    <div class="progress-bar bg-danger" style="width: <?= ($totalDokumen > 0) ? ($dokRejected/$totalDokumen)*100 : 0 ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch('<?= base_url('dashboard/getData') ?>')
        .then(response => response.json())
        .then(json => {
            const chartData = json.data.charts;

            // 1. Line Chart (Whole Numbers Tick)
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: chartData.monthly.labels,
                    datasets: [{
                        label: 'Documents',
                        data: chartData.monthly.data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // Memastikan paksi Y hanya nombor bulat
                                callback: function(value) { if (value % 1 === 0) { return value; } }
                            }
                        }
                    }
                }
            });

            // 2. Status Doughnut Chart (Pending, Approved, Rejected)
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: chartData.status.data,
                        backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '80%',
                    plugins: { legend: { display: false } },
                    maintainAspectRatio: false
                }
            });
        });
</script>
<?= $this->endSection() ?>
