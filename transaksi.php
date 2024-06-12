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

        $sql = "INSERT INTO transaksi (nama_user, jumlah, tanggal_transaksi, bukti_transaksi, id) VALUES ('$nama_user', '$jumlah', '$tanggal_transaksi', '$bukti_transaksi', '$id')";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Transaksi</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="navbar navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="#">Kirim Transaksi</a>
    </header>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-secondary text-white">Kirim Transaksi Anda</div>
            <div class="card-body">
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                <?php } ?>
                <?php if ($sukses) { ?>
                    <div class="alert alert-success" role="alert"><?php echo $sukses; ?></div>
                <?php } ?>
                <form enctype="multipart/form-data" action="" method="POST">
                    <div class="mb-3 row">
                        <label for="user_name" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $nama_user; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggal_transaksi" class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id" class="col-sm-2 col-form-label">Campaign ID</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $id; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="bukti_transaksi" class="col-sm-2 col-form-label">Bukti Transaksi</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="bukti_transaksi" name="bukti_transaksi" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2">
                        <a href="user.php"><button type="button" class="btn btn-warning">kembali</button></a>
                            <button type="submit" name="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
