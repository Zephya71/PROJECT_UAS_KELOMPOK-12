<?php
session_start();
include("inc_koneksi.php");
if(!isset($_SESSION['user_username'])) {
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Fundraising Desa user</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div class="fullcontainer banner" id="homeSection">
            <header>
                <div class="container">
                    <div class="logo">
                        <img src="Foto/logo2.jpeg" alt="Fundraising logo" />
                    </div>
                    <nav>
                        <ul>
                            <li>
                                <a href="#homeSection">Halaman Utama</a>
                            </li>
                            <li>
                                <a href="Javascript:void(0);">Tentang Kami</a>
                            </li>
                            <li>
                                <a href="Javascript:void(0);">Galeri</a>
                            </li>
                            <li>
                                <a href="logout.php">Log-out</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </header>

            <div class="container">
                <h1>Hai Faradita, Selamat Datang di Fundraising Desa</h1>
                <p>Yuk, mari kita bersama-sama berikan kebaikan kepada yang membutuhkan. Setiap sedekah kita adalah sinar harapan bagi orang lain.</p>
                <p>Nikmati berbagai fitur menarik:</p>
                <ul>
                    <li>Galang dana untuk berbagai kegiatan amal yang mulia.</li>
                    <li>Ikuti program penggalangan dana khusus dengan tujuan yang spesifik.</li>
                    <li>Terhubung dengan komunitas yang peduli untuk berbagi ide dan dukungan.</li>
                    <li>Lihat galeri untuk melihat dampak positif dari donasi Anda.</li>
                </ul>
                <a href="donasi/donasi.html"><button>Mulai Dengan Yang Kecil</button></a>
            </div>
        </div>
    </body>
</html>