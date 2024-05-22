<?php
session_start();
include("inc_koneksi.php");

if (isset($_SESSION['admin_username'])) {
    header("location:admin.php");
    exit();
}

if (isset($_SESSION['user_username'])) {
    header("location:user.php");
    exit();
}

$username = "";
$password = "";
$err = "";

if (isset($_POST['login'])) {
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    if ($username == '' or $password == '') {
        $err .= "Silahkan Masukkan Username dan Password";
    }

    if (empty($err)) {
        $sql1 = "SELECT * FROM admin WHERE username = '$username'";
        $q1 = mysqli_query($koneksi, $sql1);
        $r1 = mysqli_fetch_array($q1);

        if ($r1 && $r1['password'] == md5($password)) {
            $_SESSION['admin_username'] = $username;
            header("location:admin.php");
            exit();
        } else {
            $sql2 = "SELECT * FROM user WHERE username = '$username'";
            $q2 = mysqli_query($koneksi, $sql2);
            $r2 = mysqli_fetch_array($q2);

            if ($r2 && $r2['password'] == md5($password)) {
                $_SESSION['user_username'] = $username;
                header("location:user.php");
                exit();
            } else {
                $err .= "Akun tidak ditemukan";
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
    <title>Login</title>
    <link rel="stylesheet" href="style_login.css">
</head>

<body>
    <div id="app">
        <h1>Login</h1>
        <?php
        if ($err) {
            echo "<p class='error'>$err</p>";
        }
        ?>
        <form action="" method="post">
            <input type="text" value="<?php echo $username ?>" name="username" class="input" placeholder="Masukkan Username" /><br><br>
            <input type="password" name="password" id="password" class="input" placeholder="Masukkan Password" />
            <input type="checkbox" onclick="showPassword()">Show Password<br><br>
            <input type="submit" name="login" value="Login" />
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </form>
    </div>

    <script>
        function showPassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>

</html>
