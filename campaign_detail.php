<?php
include("inc_koneksi.php");

// Get campaign ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Query to get campaign details
    $sql = "SELECT c.*, COALESCE(SUM(t.jumlah), 0) as dana_terkumpul
            FROM campaign c
            LEFT JOIN transaksi t ON c.id = t.id
            WHERE c.id = '$id'
            GROUP BY c.id";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $campaign = mysqli_fetch_assoc($result);
        $persentase_terkumpul = ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100;
        $sisa_target_dana = $campaign['target_dana'] - $campaign['dana_terkumpul'];
    } else {
        echo "Campaign not found.";
        exit;
    }
} else {
    echo "Invalid campaign ID.";
    exit;
}

mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $campaign['nama_campaign']; ?></title>
    <link rel="stylesheet" href="bootstrap/vendors/styles/core.css">
    <link rel="stylesheet" href="bootstrap/vendors/styles/icon-font.min.css">
    <link rel="stylesheet" href="bootstrap/vendors/styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="text-center my-5">
            <a href="user.php">
                <img src="Foto/CAREBRIDGEgede.png" class="img-fluid w-25" alt="CareBridge Logo">
            </a>
        </div>
        <div class="card mb-5">
            <div class="card-header text-center text-white bg-secondary">
                <h4 class="mb-0 text-bold text-white"><?php echo $campaign['nama_campaign']; ?></h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="Foto/<?php echo $campaign['gambar']; ?>" class="img-fluid" alt="Gambar Campaign">
                </div>
                <h5 class="card-title">Kategori: <?php echo $campaign['kategori']; ?></h5>
                <p class="card-text"><?php echo nl2br($campaign['deskripsi']); ?></p>
                <h5 class="fw-bold">Target Dana: Rp <?php echo number_format($campaign['target_dana'], 0, ',', '.'); ?></h5>
                <div class="progress my-3" style="height: 30px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $persentase_terkumpul; ?>%;" aria-valuenow="<?php echo $persentase_terkumpul; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo number_format($persentase_terkumpul, 2); ?>%</div>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="card-text"><strong>Dana Terkumpul:</strong> Rp <?php echo number_format($campaign['dana_terkumpul']); ?></p>
                    <p class="card-text"><strong>Sisa Target Dana:</strong> Rp <?php echo number_format($sisa_target_dana); ?></p>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="user.php" class="btn btn-secondary">Kembali</a>
                    <a href="transaksi.php?id=<?php echo $campaign['id']; ?>" class="btn btn-success">Donasi Sekarang</a>
                </div>
            </div>
        </div>
        <div class="footer-wrap text-center py-3">
            <p>© 2024 CareBridge. <a href="eror403.html" target="_blank" class="text-danger">All rights reserved.</a></p>
        </div>
    </div>
    <script src="bootstrap/vendors/scripts/core.js"></script>
    <script src="bootstrap/vendors/scripts/script.min.js"></script>
    <script src="bootstrap/vendors/scripts/process.js"></script>
    <script src="bootstrap/vendors/scripts/layout-settings.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
