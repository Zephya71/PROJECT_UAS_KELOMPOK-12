<?php
session_start();
include("inc_koneksi.php");
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
    exit();
}

$SqlPeriode = ""; 
$awalTgl = ""; 
$akhirTgl = ""; 
$tglAwal = ""; 
$tglAkhir = "";

if(isset($_POST['btnTampil'])) {
    $tglAwal = isset($_POST['txtTglAwal']) ? $_POST['txtTglAwal'] : "01-".date('m-Y');
    $tglAkhir = isset($_POST['txtTglAkhir']) ? $_POST['txtTglAkhir'] : date('d-m-Y');
    $SqlPeriode = " WHERE T.tanggal_transaksi BETWEEN '".$tglAwal."' AND '".$tglAkhir."'";
} else {
    $awalTgl = "01-".date('m-Y');
    $akhirTgl = date('d-m-Y'); 
    $SqlPeriode = " WHERE T.tanggal_transaksi BETWEEN '".$awalTgl."' AND '".$akhirTgl."'";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</head>
<body>
<div class="page shopping-cart-page">
    <div class="container-fluid">
        <h2 class="text-dark mt-4 mb-4 text-center" >LAPORAN DONASI</h2>
        
        <div class="card shadow">
            <div class="text-dark card-header py-3 text-lg-start">
            <h4>Periode tanggal <b><?php echo ($tglAwal); ?></b> s/d <b><?php echo ($tglAkhir); ?></b></h4>
            </div>
            <div class="card-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form10" target="_self">
                    <div class="row">
                        <div class="col-lg-4">
                            <input name="txtTglAwal" type="date" class="form-control" value="<?php echo $awalTgl; ?>" size="10"/>
                        </div>
                        <div class="col-lg-4">
                            <input name="txtTglAkhir" type="date" class="form-control" value="<?php echo $akhirTgl; ?>" size="10"/>
                        </div>
                        <div class="col-lg-4">
                            <input name="btnTampil" type="submit" class="btn btn-success" value="Tampilkan"/>
                        </div>
                    </div>
                </form>
                <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable">
                    <table class="table dataTable my-0" id="dataTable1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama User</th>
                                <th>Judul Campaign</th>
                                <th>Kategori Campaign</th>
                                <th>Tanggal Donasi</th>
                                <th>Jumlah Donasi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Adjust SQL query to match table and column names
                            $Sql = "SELECT T.nama_user, T.tanggal_transaksi, T.jumlah, C.nama_campaign, C.kategori 
                                    FROM transaksi T 
                                    JOIN campaign C ON T.id = C.id
                                    $SqlPeriode";                                
                            $q2 = mysqli_query($koneksi, $Sql);
                            $nomor = 0;
                            $jumlah = 0;
                            while ($myData = mysqli_fetch_array($q2)) {    
                                $jumlah += $myData['jumlah'];
                                $nomor++;      
                        ?>
                            <tr>                        
                                <td><?php echo $nomor;?></td>
                                <td><?php echo $myData['nama_user'];?></td>
                                <td><?php echo $myData['nama_campaign'];?></td> 
                                <td><?php echo $myData['kategori'];?></td>                                        
                                <td><?php echo $myData['tanggal_transaksi'];?></td>
                                <td>Rp. <?php echo number_format($myData['jumlah']);?>,-</td> 
                            </tr>
                        <?php
                            } 
                        ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="5" class="text-end"><strong>Total Donasi :  </strong></td>
                                <td><strong>Rp. <?php echo number_format($jumlah); ?>,-</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                    <a href="admin.php"><button type="button" class="btn btn-warning">Kembali</button></a> 
                    <a href="cetak.php?awal=<?php echo $tglAwal; ?>&&akhir=<?php echo $tglAkhir; ?>" target="_blank" alt="Edit Data" class="btn btn-primary">Cetak Laporan</a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
</body>
</html>