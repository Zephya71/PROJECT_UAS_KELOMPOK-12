<?php
session_start();
include("inc_koneksi.php");
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
}

$nama_campaign          = "";
$kategori       = "";
$deskripsi      ="";
$target_dana    = "";
$status         = "";
$error          = "";
$sukses         = "";

// Memproses form saat tombol 'simpan' ditekan
if(isset($_POST['simpan'])){ 
    $nama_campaign  = $_POST['nama_campaign'];
    $kategori       = $_POST['kategori'];
    $deskripsi      = $_POST['deskripsi'];
    $target_dana    = $_POST['target_dana'];
    $status         = $_POST['status'];

    if($nama_campaign && $kategori && $deskripsi && $target_dana && $status){
        $sql1 = "insert into campaign (nama_campaign,kategori,deskripsi,target_dana, status) values ('$nama_campaign','$kategori','$deskripsi','$target_dana','$status')";
        $q1   = mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses = "Berhasil memasukkan data baru";
        } else{
            $error  = "Gagal memasukkan data";
        }
    } else{
        $error = "Silahkan memasukkan semua data";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Campaign</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 940px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- memasukkan data -->
        <div class="card">
            <div class="card-header">
                Input Data
            </div>
            <div class="card-body">
                <?php
                if($error){
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <?php
                if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nama_campaign" class="col-sm-2 col-form-label">Nama Campaign</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_campaign" name="nama_campaign" value="<?php echo $nama_campaign ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="kategori" id="kategori">
                                <option value="">- Pilih Kategori -</option>
                                <option value="Kesehatan" <?php if($kategori == "kesehatan") echo "selected"?>>Kesehatan</option>
                                <option value="Pendidikan" <?php if($kategori == "pendidikan") echo "selected"?>>Pendidikan</option>
                                <option value="Kemanusiaan" <?php if($kategori == "kemanusiaan") echo "selected"?>>Kemanusiaan</option>
                                <option value="Bencana" <?php if($kategori == "bencana") echo "selected"?>>Bencana Alam</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?php echo $deskripsi ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="target_dana" class="col-sm-2 col-form-label">Target Dana</label>                  
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                            <span for="target_dana" class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" id="target_dana" name="target_dana" value="<?php echo $target_dana ?>">
                            <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status" class="col-sm-2 col-form-label">Status Campaign</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status" id="status">
                                <option value="">- Pilih Status -</option>
                                <option value="Aktif" <?php if($status == "aktif") echo "selected"?>>Aktif</option>
                                <option value="Selesai" <?php if($status == "selesai") echo "selected"?>>Selesai</option>
                                <option value="Gagal" <?php if($status == "gagal") echo "selected"?>>Gagal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan"value="Simpan Data" class="btn btn-primary"/>
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- menampilkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Campaign
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">No.</th>
                            <th scope="col">Nama Campaign</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Target Dana</th>
                            <th scope="col">Status</th>
                        </tr>
                        <tbody>
                            <?php
                            $sql2   = "select * from campaign order by nama_campaign desc";
                            $q2     = mysqli_query($koneksi,$sql2);
                            $urut   = 1;
                            while($r2 = mysqli_fetch_array($q2)){
                                $nama_campaign  = $r2['nama_campaign'];
                                $kategori       = $r2['kategori'];
                                $deskripsi      = $r2['deskripsi']; 
                                $target_dana    = $r2['target_dana'];
                                $status         = $r2['status'];

                                ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++?></th>
                                    <td scope="row"><?php echo $nama_campaign?></td>
                                    <td scope="row"><?php echo $kategori?></td>
                                    <td scope="row"><?php echo $deskripsi?></td>
                                    <td scope="row">Rp. <?php echo number_format($target_dana)?></td>
                                    <td scope="row"><?php echo $status?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </thead>
                </table>
            </div>
        </div><br>
        <form class="logout" action="logout.php">
			<button class="btn btn-danger" type="submit"> Logout </button>
		</form>
    </div>
</body>

</html>