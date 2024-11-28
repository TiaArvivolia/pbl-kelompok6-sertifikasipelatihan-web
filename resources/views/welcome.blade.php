<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SkillHub TI | Dashboard</title>
</head>
@extends('layouts.template')

@section('content')
<h5 class="mb-2 mt-4">Dashboard</h5>
<div class="row">
  <!-- Peserta Sertifikasi Teraktif -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
        <div class="inner">
            <h2 class="fw-bold mb-0">{{ $totalCertificates }}</h2>
            <p>Total Peserta Sertifikasi</p>
        </div>
        <div class="icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <a href="{{ url('/riwayat_sertifikasi') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

  <!-- ./col -->

  <!-- Peserta Pelatihan Teraktif -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
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
  <!-- ./col -->

  <!-- Peserta Terdaftar -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
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
  <!-- ./col -->

  <!-- Masa Aktif Sertifikasi Berakhir -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h2 class="fw-bold mb-0"> {{ $totalCertificationsAllPeriods }}</h2>
        <p>Jumlah Periode Sertifikasi</p>
      </div>
      <div class="icon">
        <i class="fas fa-calendar-times"></i>
      </div>
      <a href="#" class="small-box-footer" data-bs-toggle="modal" data-bs-target="#certificationsPerPeriodModal">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <!-- ./col -->
  <div class="row">
    <!-- Chart for Jumlah Periode Sertifikasi -->
    <div class="col-md-20 mb-20"> <!-- Mengubah ukuran kolom -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="text-center">📊 Jumlah Sertifikasi per Periode</h5>
            </div>
            <div class="card-body">
                <canvas id="certificationsPerPeriodChart" width="300" height="150"></canvas> <!-- Ukuran lebih kecil -->
            </div>
        </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
      var certificationsPerPeriodCtx = document.getElementById('certificationsPerPeriodChart').getContext('2d');
      var certificationsPerPeriodChart = new Chart(certificationsPerPeriodCtx, {
          type: 'line', // Line chart
          data: {
              labels: @json($certificationsPerPeriod->pluck('tahun_periode')), // Label berupa tahun/periode
              datasets: [{
                  label: 'Jumlah Sertifikasi',
                  data: @json($certificationsPerPeriod->pluck('count')), // Jumlah sertifikasi per periode
                  borderColor: '#FF5733', // Custom warna garis
                  backgroundColor: 'rgba(255, 87, 51, 0.2)', // Custom warna area bawah
                  pointBackgroundColor: '#C70039', // Warna titik
                  pointBorderColor: '#900C3F', // Border titik
                  borderWidth: 3,
                  tension: 0.3, // Garis lebih halus
                  pointRadius: 4, // Ukuran titik
                  pointHoverRadius: 6, // Ukuran titik saat hover
                  fill: true // Area bawah garis
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false, // Membuat chart lebih kecil
              plugins: {
                  legend: {
                      display: false // Hilangkan legenda untuk tampilan ringkas
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
                          color: '#555', // Warna teks label X
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
                          color: '#555', // Warna teks label Y
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
  </script>
  

@endsection