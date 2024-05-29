<?php
session_start();
include("inc_koneksi.php");
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
    exit();
}

$gambar        = "";
$nama_campaign = "";
$kategori = "";
$deskripsi = "";
$target_dana = "";
$status = "";
$error = "";
$sukses = "";

$op = isset($_GET['op']) ? $_GET['op'] : "";

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM campaign WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil menghapus data";
    } else {
        $error = "Gagal menghapus data";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM campaign WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($r1 = mysqli_fetch_array($q1)) {
        $gambar  = $r1['gambar'];
        $nama_campaign = $r1['nama_campaign'];
        $kategori = $r1['kategori'];
        $deskripsi = $r1['deskripsi'];
        $target_dana = $r1['target_dana'];
        $status = $r1['status'];
    } else {
        $error = "Gagal edit data";
    }
}

if (isset($_POST['simpan'])) {
    $nama_campaign = $_POST['nama_campaign'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $target_dana = $_POST['target_dana'];
    $status = $_POST['status'];

    $extensi = explode(".", $_FILES['gambar']['name']);
    $gambar  = "foto-".round(microtime(true)).".".end($extensi);
    $sumber  = $_FILES['gambar']['tmp_name'];
    $upload = move_uploaded_file($sumber,'Foto/'.$gambar);

    if ($gambar && $nama_campaign && $kategori && $deskripsi && $target_dana && $status) {
        if ($op == 'edit') {
            $id = $_GET['id'];
            $sql1 = "UPDATE campaign SET gambar = '$gambar', nama_campaign = '$nama_campaign', kategori = '$kategori', deskripsi = '$deskripsi', target_dana = '$target_dana', status = '$status' WHERE id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO campaign (gambar, nama_campaign, kategori, deskripsi, target_dana, status) VALUES ('$gambar', '$nama_campaign', '$kategori', '$deskripsi', '$target_dana', '$status')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silahkan memasukkan semua data";
    }
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
    <header class="navbar navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="admin-info d-flex align-items-center">
            <img src="foto/about.jpg" alt="Admin Photo" class="admin-photo dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="admin-name"><?php echo $_SESSION['admin_username']; ?></div>
            <ul class="dropdown-menu dropdown-menu-end bg-danger" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item bg-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

        <!-- memasukkan data -->
        <div class="card mt-4">
            <div class="card-header">
                Input Data
            </div>
            <div class="card-body">
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=admin.php");
                }
                ?>
                <?php if ($sukses) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=admin.php");
                }
                ?>
                <form enctype="multipart/form-data" action="" method="POST">
                    <div class="mb-3 row">
                        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                        <div class="input-group">
                            <input type="file" class="form-control" id="inputGroupFile04" name="gambar" value="<?php echo $gambar ?>">
                        </div>
                        </div>
                    </div>
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
                                <option value="Kesehatan" <?php if ($kategori == "Kesehatan") echo "selected" ?>>Kesehatan</option>
                                <option value="Pendidikan" <?php if ($kategori == "Pendidikan") echo "selected" ?>>Pendidikan</option>
                                <option value="Kemanusiaan" <?php if ($kategori == "Kemanusiaan") echo "selected" ?>>Kemanusiaan</option>
                                <option value="Bencana" <?php if ($kategori == "Bencana") echo "selected" ?>>Bencana Alam</option>
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
                                <input type="number" class="form-control" id="target_dana" name="target_dana" value="<?php echo $target_dana ?>">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status" class="col-sm-2 col-form-label">Status Campaign</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="status" id="status">
                                <option value="">- Pilih Status -</option>
                                <option value="Aktif" <?php if ($status == "Aktif") echo "selected" ?>>Aktif</option>
                                <option value="Selesai" <?php if ($status == "Selesai") echo "selected" ?>>Selesai</option>
                                <option value="Gagal" <?php if ($status == "Gagal") echo "selected" ?>>Gagal</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- menampilkan data -->
        <div class="card mt-4">
            <div class="card-header text-white bg-secondary">
                Data Campaign
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">No.</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Nama Campaign</th>
                            <th scope="col">Kategori</th>
                            <th scope="col" width="30%">Deskripsi</th>
                            <th scope="col">Target Dana</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * from campaign order by gambar desc";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $gambar = $r2['gambar'];
                            $nama_campaign = $r2['nama_campaign'];
                            $kategori = $r2['kategori'];
                            $deskripsi = $r2['deskripsi'];
                            $target_dana = $r2['target_dana'];
                            $status = $r2['status'];
                        ?>
                            <tr class="text-center">
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td>
                                    <img src="Foto/<?php echo $gambar ?>" width="160px">
                                </td>
                                <td><?php echo $nama_campaign ?></td>
                                <td><?php echo $kategori ?></td>
                                <td><?php echo $deskripsi ?></td>
                                <td>Rp. <?php echo number_format($target_dana) ?></td>
                                <td><?php echo $status ?></td>
                                <td>
                                    <a href="admin.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="admin.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Apakah yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</body>

</html>