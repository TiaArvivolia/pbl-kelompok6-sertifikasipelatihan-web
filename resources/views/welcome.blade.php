@extends('layouts.template')

@section('content')
<h5 class="mb-2 mt-4">Dashboard</h5>
<div class="row">
  <!-- Kolom Kiri: Box dan Chart -->
  <div class="col-lg-12">
    <div class="row">
      <div class="col-md-3 mb-3">
        <!-- Kotak 1 -->
        <div class="small-box bg-primary">
          <div class="inner">
            <h2 class="fw-bold mb-0">{{ $totalCertificates }}</h2>
            <p>Total Sertifikasi Terdata</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
          <a href="{{ url('/riwayat_sertifikasi') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <!-- Kotak 2 -->
        <div class="small-box bg-success">
          <div class="inner">
            <h2 class="fw-bold mb-0">{{ $totalPelatihanTerdata }}</h2>
            <p>Total Pelatihan Terdata</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
          <a href="{{ url('/riwayat_pelatihan') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <!-- Kotak 3 -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h2 class="fw-bold mb-0">{{ $totalCertifiedParticipants['dosen'] }}</h2>
            <p>Total Sertifikasi Dosen</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <!-- Kotak 4 -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h2 class="fw-bold mb-0">{{ $totalCertifiedParticipants['tendik'] }}</h2>
            <p>Total Sertifikasi Tendik</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Second Row: Two Charts -->
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header bg-info text-white">
            <h5>📅 Periode Sertifikasi</h5>
          </div>
          <div class="card-body">
            <canvas id="certificationsPerPeriodChart" width="300" height="300" style="max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header bg-info text-white">
            <h5>📅 Periode Pelatihan</h5>
          </div>
          <div class="card-body">
            <canvas id="pelatihanPerPeriodChart" width="300" height="300" style="max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Chart for Certifications Per Period
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
      maintainAspectRatio: false,
      aspectRatio: 2,
      plugins: {
        legend: { position: 'top' },
        tooltip: {
          callbacks: {
            label: tooltipItem => 'Jumlah Sertifikasi: ' + tooltipItem.raw
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  // Chart for Pelatihan Per Period
  var pelatihanPerPeriodCtx = document.getElementById('pelatihanPerPeriodChart').getContext('2d');
  var pelatihanPerPeriodChart = new Chart(pelatihanPerPeriodCtx, {
    type: 'line',
    data: {
      labels: @json($pelatihanPerPeriod->pluck('tahun_periode')),
      datasets: [{
        label: 'Jumlah Pelatihan per Periode',
        data: @json($pelatihanPerPeriod->pluck('count')),
        borderColor: '#D14B72',
        backgroundColor: 'rgba(209, 75, 114, 0.2)',
        borderWidth: 2,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      aspectRatio: 2,
      plugins: {
        legend: { position: 'top' },
        tooltip: {
          callbacks: {
            label: tooltipItem => 'Jumlah Pelatihan: ' + tooltipItem.raw
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
</script>
@endsection
