@extends('layouts.template')

@section('content')
<h5 class="mb-2 mt-4">Dashboard</h5>
<div class="row">
  <!-- Kolom Kiri: Box dan Chart -->
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
          <h2 class="fw-bold mb-0">{{ $totalPengajuanPelatihan }}</h2>
          <p>Total Pengajuan</p>
        </div>
        <div class="icon">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <a href="#" class="small-box-footer">
          <a href="{{ url('/pengajuan_pelatihan') }}" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <!-- Kotak 4 -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h2 class="fw-bold mb-0">{{ $pengajuanPelatihanMenunggu }}</h2>
          <p>Total Menunggu Persetujuan</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        <a href="{{ url('/pengajuan_pelatihan') }}" class="small-box-footer">
        <a href="#" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    var certificationsPerPeriodCtx = document.getElementById('certificationsPerPeriodChart').getContext('2d');
    var certificationsPerPeriodChart = new Chart(certificationsPerPeriodCtx, {
        type: 'line',
        // data: {
        //     // labels: @json($certificationsPerPeriod->pluck('tahun_periode')),
        //     datasets: [{
        //         label: 'Jumlah Sertifikasi',
        //         data: @json($certificationsPerPeriod->pluck('count')),
        //         borderColor: '#FF5733',
        //         backgroundColor: 'rgba(255, 87, 51, 0.2)',
        //         pointBackgroundColor: '#C70039',
        //         pointBorderColor: '#900C3F',
        //         borderWidth: 3,
        //         tension: 0.3,
        //         pointRadius: 4,
        //         pointHoverRadius: 6,
        //         fill: true
        //     }]
        // },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return 'Jumlah: ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Periode Sertifikasi',
                        color: '#333',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#555',
                        font: {
                            size: 10
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Sertifikasi',
                        color: '#333',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#555',
                        font: {
                            size: 10
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });
});
</script> --}}

@endsection
