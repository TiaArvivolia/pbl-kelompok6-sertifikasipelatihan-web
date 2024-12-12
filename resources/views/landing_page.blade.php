<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SkillHub TI | Sistem Pendataan Sertifikasi dan Pelatihan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fc;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    header {
        background-image: url('storage/photos/login.png');
        /* Gambar latar */
        background-size: cover;
        /* Gambar memenuhi seluruh area header */
        background-position: center;
        /* Menempatkan gambar di tengah */
        background-repeat: no-repeat;
        background-color: #0056b3;
        /* Warna fallback jika gambar tidak dimuat */
        height: 400px;
        /* Tinggi header lebih besar */
        text-align: center;
        color: white;
        /* Warna teks */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    header h1 {
        font-size: 56px;
        /* Ukuran font lebih besar untuk judul */
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
    }

    header p {
        font-size: 20px;
        font-weight: 300;
        margin: 15px 0 30px;
    }

    .cta-button {
        background-color: #0056b3;
        color: white;
        padding: 16px 30px;
        font-size: 18px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .cta-button:hover {
        background-color: #CBDCEB;
    }

    .features {
        display: flex;
        justify-content: space-between;
        /* Membuat jarak antar item */
        margin: 50px 0;
        padding: 0 20px;
        flex-wrap: nowrap;
        /* Menjaga elemen tetap sejajar satu baris */
    }

    .feature-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 30%;
        /* Lebar elemen agar pas 3 elemen dalam satu baris */
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin: 0 15px;
        /* Memberikan ruang horizontal antar elemen */
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    .feature-icon {
        font-size: 48px;
        margin-bottom: 20px;
        color: #4f8cd7;
    }

    .feature-card h3 {
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 10px 0;
    }

    .feature-card p {
        font-size: 16px;
        color: #777;
    }

    footer {
        background-color: #0056b3;
        /* Warna navy */
        color: white;
        text-align: center;
        padding: 20px;
        /* Padding lebih kecil */
        font-size: 12px;
        /* Font lebih kecil */
        margin-top: auto;
    }

    footer p {
        margin: 0;
    }

    footer a {
        color: #CBDCEB;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .features {
            flex-direction: column;
            align-items: center;
        }

        .feature-card {
            width: 80%;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 36px;
        }

        .cta-button {
            font-size: 16px;
        }
    }
    </style>

</head>

<body>

    <header>
        <h1>Sistem Pendataan Sertifikasi & Pelatihan</h1>
        <p> Dosen dan Tendik Jurusan Teknologi Informasi Politeknik Negeri Malang</p>
        <a href="{{ route('login') }}" class="cta-button">Masuk ke Sistem</a>
    </header>

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">📜</div>
            <h3>Manajemen Sertifikasi</h3>
            <p>Kelola dan dokumentasikan sertifikasi dosen dan tendik dengan mudah dan efisien.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🎓</div>
            <h3>Pelatihan Berkualitas</h3>
            <p>Ikuti pelatihan terbaru untuk meningkatkan kompetensi dosen dan tendik di bidang teknologi informasi.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Analisis Data</h3>
            <p>Analisis data pelatihan dan sertifikasi untuk perencanaan dan evaluasi lebih lanjut.</p>
        </div>
    </section>

    <footer>
        <p>  &copy; 2024  Kelompok 6 | SIB-3E Politeknik Negeri Malang - Jurusan Teknologi Informasi </p>
    </footer>

</body>

</html>