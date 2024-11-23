@extends('layouts.template')

@section('content')
<div class="container py-3 px-2">
    <div class="row text-center">
<!-- Card for Total Sertifikasi -->
<div class="col-md-3 mb-3">
  <div class="card mb-3" style="min-height: 80px;">
      <div class="card-body bg-primary text-white">
          <div class="d-flex align-items-center">
              <i class="fas fa-certificate fa-2x mr-3"></i>
              <div>
                  <h4 class="card-title mb-0">Total Sertifikasi</h4>
                  <h2 class="fw-bold mb-0">{{ $totalCertificates }}</h2>
              </div>
          </div>
      </div>
  </div>
  <!-- Card for Total Peserta Bersertifikasi -->
  <div class="card mb-3 mb-3" style="min-height: 80px;">
      <div class="card-body bg-success text-white">
          <div class="d-flex align-items-center">
              <i class="fas fa-users fa-2x mr-3"></i>
              <div>
                  <h4 class="card-title mb-0">Peserta Bersertifikasi</h4>
                  <p class="mb-0">Dosen: <strong>{{ $totalCertifiedParticipants['dosen'] }}</strong></p>
                  <p class="mb-0">Tendik: <strong>{{ $totalCertifiedParticipants['tendik'] }}</strong></p>
              </div>
          </div>
      </div>
  </div>
  <!-- Card for Total Sertifikasi Per Periode -->
  <div class="card mb-3 mb-3" style="min-height: 80px;">
      <div class="card-body bg-info text-white">
          <div class="d-flex align-items-center">
              <i class="fas fa-calendar fa-2x mr-3"></i>
              <div>
                  <h4 class="card-title mb-0">Periode Sertifikasi</h4>
                  @foreach($certificationsPerPeriod as $tahun_periode)
                  <!-- Check if tahun_periode has a value and display it properly -->
                  @if($tahun_periode->tahun_periode)
                      <p class="mb-0">{{ $tahun_periode->tahun_periode }}: <strong>{{ $tahun_periode->count }}</strong> Sertifikasi</p>
                  @else
                  @endif
              @endforeach
                         
              </div>
          </div>
      </div>
  </div>
</div>

        
        <!-- Cards for Charts -->
        <div class="col-md-9 mb-3">
            <div class="row">
                <!-- Chart for Pesebaran Sertifikasi Berdasarkan Mata Kuliah -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body chart-container">
                            <h4 class="text-dark">Pesebaran Sertifikasi Berdasarkan Mata Kuliah</h4>
                            <canvas id="certificationsBySubjectChart" width="1200" height="1050"></canvas> <!-- Increased size -->
                        </div>
                    </div>
                </div>
                <!-- Chart for Pesebaran Sertifikasi Berdasarkan Bidang Minat -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body chart-container">
                            <h4 class="text-dark">Pesebaran Sertifikasi Berdasarkan Bidang Minat</h4>
                            <canvas id="certificationsByFieldChart" width="1200" height="1050"></canvas> <!-- Increased size -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart for Pesebaran Sertifikasi Berdasarkan Level -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>📈 Pesebaran Sertifikasi Berdasarkan Level</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="certificationsByLevelChart" width="500" height="300"></canvas> <!-- Increased size -->
                </div>
            </div>
        </div>

        <!-- Chart for Pesebaran Sertifikasi Berdasarkan Jenis -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5>🗂️ Pesebaran Sertifikasi Berdasarkan Jenis</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="certificationsByTypeChart" width="500" height="300"></canvas> <!-- Increased size -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart for Pesebaran Sertifikasi Berdasarkan Level
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

    // Chart for Pesebaran Sertifikasi Berdasarkan Jenis
    var certificationsByTypeCtx = document.getElementById('certificationsByTypeChart').getContext('2d');
    var certificationsByTypeChart = new Chart(certificationsByTypeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($certificationsByType->pluck('jenis_sertifikasi')),
            datasets: [{
                label: 'Jumlah Sertifikasi Berdasarkan Jenis',
                data: @json($certificationsByType->pluck('count')),
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

    // Chart for Pesebaran Sertifikasi Berdasarkan Mata Kuliah (Bar Chart)
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

    // Chart for Pesebaran Sertifikasi Berdasarkan Bidang Minat (Horizontal Bar Chart)
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
</script>
@endsection
