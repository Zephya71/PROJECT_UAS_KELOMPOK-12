<?php 
session_start();

include("inc_koneksi.php");

$awal = $_GET['awal'];
$akhir = $_GET['akhir'];

function IndonesiaTgl($tanggal) {
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

$tglAwal = isset($_GET['awal']) ? $_GET['awal'] : "01-" . date('m-Y');
$tglAkhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('d-m-Y');
$SqlPeriode = "WHERE T.tanggal_transaksi BETWEEN '$awal' AND '$akhir'";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Transaksi Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body onload="print()">
    <div class="container-fluid mt-4 mb-4">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmedit">
            <center>
                <h2 class="mb-4">DAFTAR LAPORAN DONASI</h2>
                <hr class="mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 text-dark text-lg-start">
                        <?php if (!empty($tglAwal)) { ?>
                            <h4>Periode tanggal <b><?php echo IndonesiaTgl($awal); ?></b> s/d <b><?php echo IndonesiaTgl($akhir); ?></b></h4>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color: #ffeeee;">
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Nama Campaign</th>
                                    <th>Kategori Campaign</th>
                                    <th>Tanggal Donasi</th>
                                    <th>Jumlah Donasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Sql = "SELECT T.nama_user, T.tanggal_transaksi, T.jumlah, C.nama_campaign, C.kategori 
                                        FROM transaksi T 
                                        JOIN campaign C ON T.id = C.id $SqlPeriode";
                                $myQry = mysqli_query($koneksi, $Sql) or die("Query salah: " . mysqli_error($koneksi));
                                $nomor = 0;
                                $jumlah = 0;
                                while ($myData = mysqli_fetch_array($myQry)) {
                                    $jumlah += $myData['jumlah'];
                                    $nomor++;
                                ?>
                                    <tr>
                                        <td><?php echo $nomor; ?></td>
                                        <td><?php echo $myData['nama_user']; ?></td>
                                        <td><?php echo $myData['nama_campaign']; ?></td>
                                        <td><?php echo $myData['kategori']; ?></td>
                                        <td><?php echo IndonesiaTgl($myData['tanggal_transaksi']); ?></td>
                                        <td>Rp. <?php echo number_format($myData['jumlah']); ?>,-</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #ffeeee;">
                                    <th colspan="5" class="text-end">Total Donasi :  </th>
                                    <th>Rp. <?php echo number_format($jumlah); ?>,-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </center>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
