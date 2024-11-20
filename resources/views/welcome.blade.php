<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SkillHub TI | Dashboard</title>
</head>
@extends('layouts.template')

@section('content')

<!-- Small Box (Stat card) -->
<h5 class="mb-2 mt-4">Dashboard</h5>
<div class="row">
  <!-- Peserta Sertifikasi Teraktif -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>85</h3>
        <p>Peserta Sertifikasi Aktif</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->

  <!-- Peserta Pelatihan Teraktif -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>75</h3>
        <p>Peserta Pelatihan Aktif</p>
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
        <h3>200</h3>
        <p>Peserta Terdaftar</p>
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
        <h3>30</h3>
        <p>Masa Aktif Sertifikasi Berakhir</p>
      </div>
      <div class="icon">
        <i class="fas fa-calendar-times"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
</div>


@endsection
