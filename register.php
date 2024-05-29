<?php
session_start();
include("inc_koneksi.php");

$username = "";
$password = "";
$confirm_password = "";
$nama_user = "";
$err = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_user = $_POST['nama_user'];

    if ($username == '' || $password == '' || $confirm_password == '' || $nama_user == '') {
        $err .= "Silahkan lengkapi semua data.<br>";
    }

    if ($password !== $confirm_password) {
        $err .= "Konfirmasi password tidak cocok.<br>";
    }

    if (empty($err)) {
        $sql_check = "SELECT * FROM user WHERE username = '$username'";
        $q_check = mysqli_query($koneksi, $sql_check);
        
        if (mysqli_num_rows($q_check) > 0) {
            $err .= "Username sudah digunakan.<br>";
        } else {
            $hashed_password = md5($password);
            $sql_insert = "INSERT INTO user (username, password, nama_user) VALUES ('$username', '$hashed_password', '$nama_user')";
            $q_insert = mysqli_query($koneksi, $sql_insert);

            if ($q_insert) {
                header("location:login.php");
                exit();
            } else {
                $err .= "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style_login.css">
</head>

<body>
    <div id="app">
        <h1>Register</h1>
        <?php
        if ($err) {
            echo "<p class='error'>$err</p>";
        }
        ?>
        <form action="" method="post">
            <input type="text" value="<?php echo htmlspecialchars($username); ?>" name="username" class="input" placeholder="Masukkan Username" /><br>
            <input type="password" name="password" id="password" class="input" placeholder="Masukkan Password" /><br>
            <input type="password" name="confirm_password" id="confirm_password" class="input" placeholder="Konfirmasi Password" /><br>
            <input type="text" value="<?php echo htmlspecialchars($nama_user); ?>" name="nama_user" class="input" placeholder="Masukkan Nama Lengkap" /><br>
            <input type="submit" name="register" value="Register" />
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </form>
        <button class="back-button" onclick="location.href='index.html'">ke Halaman Utama</button>
    </div>
</body>

</html>
