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
    
    // Fetch gambar filename from the database
    $sql1 = "SELECT gambar FROM campaign WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $gambar = $r1['gambar'];

    // Delete file from directory
    if (file_exists('Foto/' . $gambar)) {
        unlink('Foto/' . $gambar);
    }

    // Delete data from database
    $sql2 = "DELETE FROM campaign WHERE id = '$id'";
    $q2 = mysqli_query($koneksi, $sql2);
    if ($q2) {
        $sukses = "Berhasil menghapus data dan gambar";
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
$kesehatan_query = "SELECT COUNT(*) as count FROM campaign WHERE kategori = 'Kesehatan'";
  $kesehatan_result = mysqli_query($koneksi, $kesehatan_query);
  $kesehatan_count = mysqli_fetch_assoc($kesehatan_result)['count'];

  $pendidikan_query = "SELECT COUNT(*) as count FROM campaign WHERE kategori = 'Pendidikan'";
  $pendidikan_result = mysqli_query($koneksi, $pendidikan_query);
  $pendidikan_count = mysqli_fetch_assoc($pendidikan_result)['count'];

  $kemanusiaan_query = "SELECT COUNT(*) as count FROM campaign WHERE kategori = 'Kemanusiaan'";
  $kemanusiaan_result = mysqli_query($koneksi, $kemanusiaan_query);
  $kemanusiaan_count = mysqli_fetch_assoc($kemanusiaan_result)['count'];

  $bencana_query = "SELECT COUNT(*) as count FROM campaign WHERE kategori = 'Bencana'";
  $bencana_result = mysqli_query($koneksi, $bencana_query);
  $bencana_count = mysqli_fetch_assoc($bencana_result)['count'];

  // Test 6(Start)
  $tanggal_transaksi_query = "SELECT DATE_FORMAT(tanggal_transaksi, '%M %Y') as bulan, SUM(jumlah) as total_pembayaran 
                             FROM transaksi GROUP BY DATE_FORMAT(tanggal_transaksi, '%M %Y')
                             ORDER BY DATE_FORMAT(tanggal_transaksi, '%Y-%m')";
  $tanggal_transaksi_result = mysqli_query($koneksi, $tanggal_transaksi_query);

  // Prepare data arrays
  $bulan_pembayaran = [];
  $total_pembayaran = [];

  while ($row = mysqli_fetch_assoc($tanggal_transaksi_result)) {
      $bulan_pembayaran[] = $row['bulan'];
      $total_pembayaran[] = $row['total_pembayaran'];
  }

  // Convert PHP arrays to JavaScript arrays
  $bulan_pembayaran_js = json_encode($bulan_pembayaran);
  $total_pembayaran_js = json_encode($total_pembayaran);	
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Dashboard Admin</title>

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
	<link rel="stylesheet" type="text/css" href="bootstrap/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/src/plugins/fullcalendar/fullcalendar.css">
	

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
	
	<div class="header d-flex justify-content-end align-items-center">
		<div class="header-right">
			<div class="dashboard-setting user-notification mr-3">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>
			<div class="user-info-dropdown d-flex align-items-center">
				<div class="dropdown">
					<a class="dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="Foto/about.jpg" alt="">
						</span>
						<div class="admin-name ml-2"><?php echo $_SESSION['admin_username']; ?></div>
					</a>
                	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  		<a class="dropdown-item" href="logout.php">Logout</a>
                	</div>
				</div>
			</div>
		</div>
	</div>

	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Layout Settings
				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll">
			<div class="right-sidebar-body-content">
				<h4 class="weight-600 font-18 pb-10">Header Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light ">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
				<div class="sidebar-radio-group pb-10 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
					</div>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
				<div class="sidebar-radio-group pb-30 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
					</div>
				</div>

				<div class="reset-options pt-30 text-center">
					<button class="btn btn-danger" id="reset-settings">Reset Settings</button>
				</div>
			</div>
		</div>
	</div>

	<div class="left-side-bar">
		<div class="brand-logo ml-4">
			<a href="dashboardadmin.php">
				<img src="Foto/CAREBRIDGEgede.png" alt="" class="dark-logo">
				<img src="Foto/CAREBRIDGEgede.png" alt="" class="light-logo">
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-house-1"></span><span class="mtext">Home</span>
						</a>
						<ul class="submenu">
							<li><a href="dashboardadmin.php">Dashboard Admin</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-edit2"></span><span class="mtext">Forms</span>
						</a>
						<ul class="submenu">
							<li><a href="inputdata.php">Input Data</a></li>
							<li><a href="inputdata.php">Data Campaign</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-library"></span><span class="mtext">Rekap Pembayaran</span>
						</a>
						<ul class="submenu">
							<li><a href="tableslaporandonasi.php">Tabel Rekap</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20">
			<div class="card-box pd-20 height-100-p mb-30">
				<div class="row align-items-center">
					<div class="col-md-4">
						<img src="bootstrap/vendors/images/banner-img.png" alt="">
					</div>
					<div class="col-md-8">
						<h4 class="font-20 weight-500 mb-10 text-capitalize">
							Welcome back <div class="weight-600 font-30 text-danger" class="admin-name"><?php echo $_SESSION['admin_username']; ?></div>
						</h4>
						<p class="font-18 max-width-600">Selamat datang di dashboard admin. Di sini Anda dapat mengelola semua aspek situs web, termasuk memperbarui artikel berita, mengelola akun pengguna, dan meninjau analitik situs. Silakan gunakan formulir yang disediakan untuk melakukan perubahan yang diperlukan. </p>
					</div>
				</div>
			</div>

		<!-- Line Chart -->
		<div class="row">
			<div class="col-xl-8 col-lg-7">
				<div class="card shadow mb-4">
		<!-- Card Header -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold ">Nominal Donasi Keseluruhan</h6>
				</div>
		<!-- Card Body -->
				<div class="card-body">
					<div class="chart-area">
						<canvas id="myLineChart"></canvas>
					</div>
				</div>
			</div>
		</div>

		<!-- Pie Chart -->
		<div class="col-xl-4 col-lg-5">
			<div class="card shadow mb-4">
			<!-- Card Header - Dropdown -->
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold">Nomial Donasi Per-Kategori</h6>
			</div>
			<!-- Card Body -->
			<div class="card-body">
			<div class="chart-pie pt-4 pb-2">
				<canvas id="myPieChart"></canvas>
			</div>
			<div class="mt-4 text-center small">
				<span class="mr-4">
					<i class="fas fa-circle text-success"></i> Kesehatan
				</span>
				<span class="mr-4">
					<i class="fas fa-circle text-danger"></i> Pendidikan
				</span>
				<span class="mr-4">
					<i class="fas fa-circle text-primary"></i> Kemanusiaan
				</span>
				<span class="mr-4">
					<i class="fas fa-circle text-warning"></i> Bencana Alam
				</span>
			</div>
			</div>
		</div>
		</div>
		</div>
		<!-- Calender -->
		<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body p-3"> <!-- Menggunakan kelas p-3 untuk padding lebih kecil -->
                    <div class="page-header">
                        <h3 class="mb-0">Kalender Kehidupan</h3>
                    </div>
                    <div class="calendar-wrap">
                        <div id="calendar"></div>
                    </div>
                    <!-- calendar modal -->
                    <div id="modal-view-event" class="modal modal-top fade calendar-modal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h4 class="h4"><span class="event-icon weight-400 mr-3"></span><span class="event-title"></span></h4>
                                    <div class="event-body"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				© 2024 CareBridge. <a href="eror403.html" target="_blank" class="text-danger">All rights reserved.</a>
			</div>
		</div>
	</div>
	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<!-- Collage.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
	<!-- js -->
	<script src="bootstrap/vendors/scripts/core.js"></script>
	<script src="bootstrap/vendors/scripts/script.min.js"></script>
	<script src="bootstrap/vendors/scripts/process.js"></script>
	<script src="bootstrap/vendors/scripts/layout-settings.js"></script>
	<script src="bootstrap/src/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="bootstrap/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="bootstrap/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="bootstrap/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="bootstrap/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="bootstrap/vendors/scripts/dashboard.js"></script>
	<!-- js -->
	<script src="bootstrap/vendors/scripts/core.js"></script>
	<script src="bootstrap/vendors/scripts/script.min.js"></script>
	<script src="bootstrap/vendors/scripts/process.js"></script>
	<script src="bootstrap/vendors/scripts/layout-settings.js"></script>

	<script src="bootstrap/src/plugins/fullcalendar/fullcalendar.min.js"></script>
	<script src="bootstrap/vendors/scripts/calendar-setting.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Bootstrap JS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<script>
  document.addEventListener("DOMContentLoaded", function() {
              const kesahatanCount = <?php echo $kesehatan_count; ?>;
              const pendidikanCount = <?php echo $pendidikan_count; ?>;
			  const kemanusiaanCount = <?php echo $kemanusiaan_count; ?>;
              const bencanaCount = <?php echo $bencana_count; ?>;
  const data = {
                  labels: ['Kesehatan', 'Pendidikan','Kemanusiaan', 'Bencana Alam'],
                  datasets: [{
                      label: '',
                      data: [kesahatanCount, pendidikanCount,kemanusiaanCount, bencanaCount],
                      backgroundColor: [
                          '#059212',
                          '#FF1E1E',
						  '#050C9C',
						  '#FFC700'
                      ],
                      hoverOffset: 4
                  }]
              };
  const config = {
                  type: 'doughnut',
                  data: data,
                  options: {
                      responsive: true,
                      maintainAspectRatio: false
                  }
              };
              const myChart = new Chart(
                  document.getElementById('myPieChart'),
                  config
              );
          });
  </script>

<script>

  document.addEventListener("DOMContentLoaded", function() {
            const bulanPembayaran = <?php echo $bulan_pembayaran_js; ?>;
            const totalPembayaran = <?php echo $total_pembayaran_js; ?>;

            // Format Rupiah values
                const formatRupiah = (value) => {
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            };

        const data = {
            labels: bulanPembayaran,
            datasets: [{
                label: 'Jumlah Pembayaran',
                fill: false,
                borderColor: '#EE4E4E',
                data: totalPembayaran,
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        ticks: {
                            // Format the y-axis ticks
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += formatRupiah(context.raw);
                                return label;
                            }
                        }
                    }
                }
            }
        };

            var myChart = new Chart(
                document.getElementById('myLineChart'),
                config
            );
        });
   
</script>
  
</body>
   
</script>
</body>
</html>