<?php

include("inc_koneksi.php");

// Initialize variables
$error = "";
$sukses = "";

// Get campaign_id from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (isset($_POST['submit'])) {
    $nama_user = isset($_POST['user_name']) && !empty($_POST['user_name']) ? $_POST['user_name'] : ""; // Default to empty string if not provided
    $jumlah = $_POST['jumlah'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $id = $_POST['id'];

    $extensi = explode(".", $_FILES['bukti_transaksi']['name']);
    $gambar  = "bukti-".round(microtime(true)).".".end($extensi);
    $bukti_transaksi  = $_FILES['bukti_transaksi']['tmp_name'];
    $upload = move_uploaded_file($bukti_transaksi, 'bukti_trf/'.$gambar);

    if ($upload && $jumlah && $tanggal_transaksi && $id) {
        $bukti_path = 'bukti_trf/'.$gambar;

        // Prepare SQL query with placeholders
        $sql = "INSERT INTO transaksi (nama_user, jumlah, tanggal_transaksi, bukti_transaksi, id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'sisss', $nama_user, $jumlah, $tanggal_transaksi, $bukti_path, $id);

        if (mysqli_stmt_execute($stmt)) {
            $sukses = "Transaksi berhasil dikirim.";
        } else {
            $error = "Gagal mengirim transaksi: " . mysqli_error($koneksi);
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
            <a href="index.php">
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
							<label for="user_name" class="col-sm-12 col-md-2 col-form-label">Nama (Opsional)</label>
							<div class="col-sm-12 col-md-10">
								<input type="text" class="form-control" id="user_name" name="user_name">
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
                        <a href="index.php"><button type="button" class="btn btn-danger">Kembali</button></a>
                            <button type="submit" name="submit" class="btn btn-success">Kirim</button>
                        </div>
                    </div>
					</form>
				</div>
				<!-- Input Data End -->
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				Copyright <a href="eror403.html" target="_blank">by Kelompok 12</a>
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
