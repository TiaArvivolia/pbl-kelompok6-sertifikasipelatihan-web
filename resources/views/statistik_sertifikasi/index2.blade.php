@extends('layouts.template')

@section('content')
<div class="container py-3 px-2">
    <div class="row text-center">
        <!-- Card for Total Sertifikasi -->
        <div class="col-md-4 mb-3">
            <div class="card" style="width: 100%; height: 100px;"> <!-- Adjust the height and width as needed -->
                <div class="card-body bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-certificate fa-2x mr-3"></i>
                        <div class="text-center">
                            <h4 class="card-title mb-0">Total Sertifikasi</h4>
                            <h2 class="fw-bold mb-0">{{ $totalCertificates }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adjusted the width to match the Total Sertifikasi card -->
    </div>
    
    <div class="row">
        <!-- First Row: Three Charts -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>📊 Peserta Bersertifikasi</h5>
                </div>
                <div class="card-body">
                    <canvas id="certifiedParticipantsChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>📈 Sertifikasi Berdasarkan Level</h5>
                </div>
                <div class="card-body">
                    <canvas id="certificationsByLevelChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5>🗂️ Sertifikasi Berdasarkan Jenis</h5>
                </div>
                <div class="card-body">
                    <canvas id="certificationsByTypeChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Second Row: Two Charts -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>📅 Periode Sertifikasi</h5>
                </div>
                <div class="card-body">
                    <!-- Adjusted the canvas dimensions to fit the new width -->
                    <canvas id="certificationsPerPeriodChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-dark">Pesebaran Sertifikasi Berdasarkan Bidang Minat</h4>
                    <canvas id="certificationsByFieldChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-dark">Pesebaran Sertifikasi Berdasarkan Mata Kuliah</h4>
                    <canvas id="certificationsBySubjectChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

   

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Doughnut Chart for Peserta Bersertifikasi
    var certifiedParticipantsCtx = document.getElementById('certifiedParticipantsChart').getContext('2d');
    var certifiedParticipantsChart = new Chart(certifiedParticipantsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dosen', 'Tendik'], // Labels for the chart
            datasets: [{
                data: [{{ $totalCertifiedParticipants['dosen'] }}, {{ $totalCertifiedParticipants['tendik'] }}], // Dynamic data
                backgroundColor: ['#34B9A6', '#FF6F61'], // Colors for each segment
                borderColor: '#fff', // Border color
                borderWidth: 1 // Border width
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Position of the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

   // Line Chart for Periode Sertifikasi
    var certificationsPerPeriodCtx = document.getElementById('certificationsPerPeriodChart').getContext('2d');
    var certificationsPerPeriodChart = new Chart(certificationsPerPeriodCtx, {
        type: 'line',
        data: {
            labels: @json($certificationsPerPeriod->pluck('tahun_periode')),
            datasets: [{
                label: 'Jumlah Sertifikasi per Periode',
                data: @json($certificationsPerPeriod->pluck('count')),
                borderColor: '#4B72D1',
                backgroundColor: 'rgba(75, 114, 209, 0.2)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Jumlah Sertifikasi: ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Ensure the Y-axis increments by 1
                        callback: function(value) {
                            return Math.floor(value); // Round down to whole number (removes decimal)
                        },
                        // Ensure the axis labels are aligned properly with rounded values
                        min: 0,  // Set minimum value for Y-axis
                        max: Math.ceil(Math.max(...@json($certificationsPerPeriod->pluck('count')))) // Max value rounded up
                    }
                }
            }
        }
    });



    // Bar Chart for Pesebaran Sertifikasi Berdasarkan Mata Kuliah
    var certificationsBySubjectCtx = document.getElementById('certificationsBySubjectChart').getContext('2d');
    var certificationsBySubjectChart = new Chart(certificationsBySubjectCtx, {
        type: 'bar',
        data: {
            labels: @json($certificationsBySubject->pluck('nama_mk')),
            datasets: [{
                label: 'Jumlah Sertifikasi Berdasarkan Mata Kuliah',
                data: @json($certificationsBySubject->pluck('count')),
                backgroundColor: '#344CB7',
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Horizontal Bar Chart for Pesebaran Sertifikasi Berdasarkan Bidang Minat
    var certificationsByFieldCtx = document.getElementById('certificationsByFieldChart').getContext('2d');
    var certificationsByFieldChart = new Chart(certificationsByFieldCtx, {
        type: 'bar',
        data: {
            labels: @json($certificationsByField->pluck('nama_bidang_minat')),
            datasets: [{
                label: 'Jumlah Sertifikasi Berdasarkan Bidang Minat',
                data: @json($certificationsByField->pluck('count')),
                backgroundColor: '#577BC1',
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Doughnut Chart for Pesebaran Sertifikasi Berdasarkan Level
    var certificationsByLevelCtx = document.getElementById('certificationsByLevelChart').getContext('2d');
    var certificationsByLevelChart = new Chart(certificationsByLevelCtx, {
        type: 'doughnut',
        data: {
            labels: @json($certificationsByLevel->pluck('level_sertifikasi')),
            datasets: [{
                label: 'Jumlah Sertifikasi Berdasarkan Level',
                data: @json($certificationsByLevel->pluck('count')),
                backgroundColor: ['#000957', '#344CB7', '#577BC1', '#EBE645'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Doughnut Chart for Pesebaran Sertifikasi Berdasarkan Jenis
    var certificationsByTypeCtx = document.getElementById('certificationsByTypeChart').getContext('2d');
    var certificationsByTypeChart = new Chart(certificationsByTypeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($certificationsByType->pluck('jenis_sertifikasi')),
            datasets: [{
                label: 'Pesebaran Sertifikasi Berdasarkan Jenis',
                data: @json($certificationsByType->pluck('count')),
                backgroundColor: ['#FFB61E', '#2E6FF6', '#4CCF5F'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

</script>

@endsection
