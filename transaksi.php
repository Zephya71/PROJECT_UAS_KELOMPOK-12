<?php
session_start();
include("inc_koneksi.php");

$error = "";
$sukses = "";

// Periksa apakah sesi sudah diatur dan pengguna telah login
if (!isset($_SESSION['user_username'])) {
    // Jika tidak ada sesi, arahkan pengguna ke halaman login
    header("Location: login.php");
    exit;
}

// Ambil nama user dari database
$user_username = $_SESSION['user_username'];
$sql = "SELECT nama_user FROM user WHERE username = '$user_username'";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nama_user = $row['nama_user'];
} else {
    $nama_user = "Nama Pengguna"; // Atur nama default jika tidak ditemukan dalam database
}

// Get campaign_id from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (isset($_POST['submit'])) {
    $jumlah = $_POST['jumlah'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $id = $_POST['id'];

    $extensi = explode(".", $_FILES['bukti_transaksi']['name']);
    $gambar  = "bukti-".round(microtime(true)).".".end($extensi);
    $bukti_transaksi  = $_FILES['bukti_transaksi']['tmp_name'];
    $upload = move_uploaded_file($bukti_transaksi, 'bukti_trf/'.$gambar);

    if ($upload && $nama_user && $jumlah && $tanggal_transaksi && $id) {
        $bukti_data = addslashes(file_get_contents('bukti_trf/'.$gambar));

        $sql = "INSERT INTO transaksi (nama_user, jumlah, tanggal_transaksi, bukti_transaksi, id) VALUES ('$nama_user', '$jumlah', '$tanggal_transaksi', '$gambar', '$id')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            $sukses = "Transaksi berhasil dikirim.";
        } else {
            $error = "Gagal mengirim transaksi.";
        }
    } else {
        $error = "Harap mengisi semua kolom.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Transaksi</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="Foto/about.jpg">
    <link rel="icon" type="image/png" sizes="32x32" href="Foto/about.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="Foto/about.jpg">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="bootstrap/vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/vendors/styles/style.css">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>
<body>
    <!-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo"><img src="Foto/CAREBRIDGE.png" alt=""></div>
            <div class='loader-progress' id="progress_div">
                <div class='bar' id='bar1'></div>
            </div>
            <div class='percent' id='percent1'>0%</div>
            <div class="loading-text">
                Loading...
            </div>
        </div>
    </div> -->    
    <div class="container-fluid col-11">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
            <a href="campaign_detail.php">
                <div class="text-center mb-4">
                    <img src="Foto/CAREBRIDGEgede.png" class="w-25" alt="Gambar Campaign">
                </div>
            </a>
                <!-- Transaksi Start -->
                <div class="pd-20 card-box mb-30" id="inputData">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-danger h4">Kirim Donasi</h4>
                            <p class="mb-30">Masukkan Donasi Anda</p>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" action="" method="POST">
                        <?php if ($error) { ?>
                            <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                         <?php } ?>
                        <?php if ($sukses) { ?>
                            <div class="alert alert-success" role="alert"><?php echo $sukses; ?></div>
                        <?php } ?>
                        <div class="form-group row">
                            <label for="user_name" class="col-sm-12 col-md-2 col-form-label">Nama</label>
                            <div class="col-sm-12 col-md-10">
                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $nama_user; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-12 col-md-2 col-form-label">Campaign ID</label>
                            <div class="col-sm-12 col-md-10">
                                <input type="number" class="form-control" id="id" name="id" value="<?php echo $id; ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_transaksi" class="col-sm-12 col-md-2 col-form-label">Tanggal Transaksi</label>
                            <div class="col-sm-12 col-md-10">
                                <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" required>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <label for="bukti_transaksi" class="col-sm-12 col-md-2 col-form-label">Bukti Transaksi</label>
                            <div class="col-sm-12 col-md-10">
                                <input type="file" class="form-control" id="bukti_transaksi" name="bukti_transaksi" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2">
                            <a href="campaign_detail.php?id=<?php echo $id; ?>"><button type="button" class="btn btn-secondary">Kembali</button></a>
                            <button type="submit" name="submit" class="btn btn-success">Kirim</button>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- Input Data End -->
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">
                © 2024 CareBridge. <a href="eror403.html" target="_blank" class="text-danger">All rights reserved.</a>
            </div>
        </div>
    </div>
    <!-- js -->
    <script src="bootstrap/vendors/scripts/core.js"></script>
    <script src="bootstrap/vendors/scripts/script.min.js"></script>
    <script src="bootstrap/vendors/scripts/process.js"></script>
    <script src="bootstrap/vendors/scripts/layout-settings.js"></script>
</body>
</html>
