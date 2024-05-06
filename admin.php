<?php
session_start();
include("inc_koneksi.php");
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fundraising Desa</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" />
    <link rel="stylesheet" href="style_login.css" />
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
            <h1>Hai Admin, Selamat Datang!</h1>
            <p>Selamat datang kembali di dashboard admin. Anda memiliki kendali penuh untuk mengelola platform kami.</p>
        </div>
    </div>
</body>

</html>