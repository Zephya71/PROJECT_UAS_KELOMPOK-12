<?php
session_start();
include("inc_koneksi.php");

// Periksa apakah pengguna sudah login, jika tidak, alihkan ke halaman login
if (!isset($_SESSION['user_username'])) {
	header("location:login.php");
	exit; // Penting untuk menghentikan eksekusi skrip setelah melakukan pengalihan header
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
	<title>Home CrowdFunding</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			// Fungsi untuk menangani perubahan pada filter kategori
			$('#kategori_filter').change(function() {
				var selectedCategory = $(this).val();

				// Kirim permintaan AJAX ke server untuk filter kategori
				$.ajax({
					url: 'filter_donasi.php',
					method: 'POST',
					data: {
						filter_category: selectedCategory
					},
					success: function(response) {
						$('#donasi_container').html(response);
					}
				});
			});
			// Memuat semua donasi pada saat pertama kali halaman dibuka
			$('#kategori_filter').trigger('change');
		});
	</script>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
		<div class="container">
			<div class="navbar-brand" href="#"><span class="text-success">Care</span>Bridge</div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="#">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#kategori">Kategori</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#donasi">Donasi</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#berita">Berita</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#about">Tentang kami</a>
					</li>
				</ul>
				<form class="d-flex">
					<input class="form-control" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-secondary" type="submit">
						<i class="bi bi-search"></i>
					</button>
				</form>

			</div>
		</div>
		<div class="dropdown">
			<a class="btn btn-light dropdown-toggle d-flex align-items-center me-2" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="foto/foto profil v1.png" alt="Profile" class="rounded-circle" width="30" height="30">
				<span class="ms-2"><?php echo $nama_user; ?></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-end bg-danger" aria-labelledby="dropdownMenuLink">
				<li><a class="dropdown-item bg-danger text-white" href="logout.php">Logout</a></li>
			</ul>
		</div>
	</nav>
	<div class="home" id="home">
		<div class="carousel slide" data-bs-ride="carousel" id="carouselExampleIndicators">
			<div class="carousel-indicators">
				<button aria-label="Slide 1" class="active" data-bs-slide-to="0" data-bs-target="#carouselExampleIndicators" type="button"></button>
				<button aria-label="Slide 2" data-bs-slide-to="1" data-bs-target="#carouselExampleIndicators" type="button"></button>
				<button aria-label="Slide 3" data-bs-slide-to="2" data-bs-target="#carouselExampleIndicators" type="button"></button>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img alt="..." class="d-block w-100" src="Foto/home-1.jpeg">
					<div class="carousel-caption">
						<h5>Cancer Fighter</h5>
						<p>Bantu tetangga kita agar segera sembuh dari kanker. Semangatnya yang tak pernah padam dalam
							berjuang harus kita dukung!</p>
						<p><a class="btn btn-success me-sm-3 mt-3" href="tzransaksi.php">Donate Now</a></p>
					</div>
				</div>
				<div class="carousel-item">
					<img alt="..." class="d-block w-100" src="Foto/home-2.jpeg">
					<div class="carousel-caption">
						<h5>Nenek Sumiati</h5>
						<p>Menjadi tulang punggung keluarga di usianya yang sudah lanjut demi untuk menghidupi
							cucu-cucunya. Luangkan sebagian hartamu untuk membantu si nenek!</p>
						<p><a class="btn btn-success me-sm-3 mt-3" href="transaksi.php">Donate Now</a></p>
					</div>
				</div>
				<div class="carousel-item">
					<img alt="..." class="d-block w-100" src="Foto/home-3.jpeg">
					<div class="carousel-caption">
						<h5>Musholla Al-Ikhlas</h5>
						<p>Bismillah. Pembangunan musholla ini akan bertempat di sekitar area Kampung 1. Donasi sekarang
							agar memperoleh sedekah jariyah!</p>
						<p><a class="btn btn-success me-sm-3 mt-3" href="#">Donate Now</a></p>
					</div>
				</div>
			</div><button class="carousel-control-prev" data-bs-slide="prev" data-bs-target="#carouselExampleIndicators" type="button"><span aria-hidden="true" class="carousel-control-prev-icon"></span> <span class="visually-hidden">Previous</span></button>
			<button class="carousel-control-next" data-bs-slide="next" data-bs-target="#carouselExampleIndicators" type="button"><span aria-hidden="true" class="carousel-control-next-icon"></span> <span class="visually-hidden">Next</span></button>
		</div>
	</div><!-- about section starts -->
	<section class="about section-padding" id="about">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-12 col-12">
					<div class="about-img"><img alt="" class="img-fluid" src="Foto/about.jpg"></div>
				</div>
				<div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
					<div class="about-text">
						<h2>Selamat Datang, <?php echo $nama_user; ?>!</h2>
						<p>Yuk, mari kita bersama-sama memberikan kebaikan kepada yang membutuhkan. Setiap sedekah kita
							adalah sinar harapan bagi orang lain.</p><a class="btn btn-outline-success" href="#">Learn
							More</a>
					</div>
				</div>
			</div>
		</div>
	</section><!-- about section Ends -->
	<!-- Section donasi -->
	<section id="donasi" class="py-5">
		<div class="container">
			<h2 class="mb-4">Daftar Donasi</h2>
			<!-- Filter kategori -->
			<div class="mb-4">
				<select id="kategori_filter" class="form-select">
					<option value="">Semua Kategori</option>
					<option value="Kesehatan">Kesehatan</option>
					<option value="Pendidikan">Pendidikan</option>
					<option value="Kemanusiaan">Kemanusiaan</option>
					<option value="Bencana">Bencana Alam</option>
				</select>
			</div>
			<div id="donasi_container" class="row row-cols-1 row-cols-md-3 g-4">
				<!-- Konten donasi akan dimuat di sini -->
			</div>
		</div>
	</section>
	<!-- end section donasi -->
	<!-- berita starts -->
	<section class="section-padding" id="berita">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-header text-center pb-4">
						<h2>Berita</h2>
						<p>Update perkembangan dan berita terkini.</p>
					</div>
				</div>
			</div>
			<div class="berita">
				<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<div class="single-item">
								<div class="row">
									<div class="col-lg-4 col-md-12 col-12">
										<div class="berita-img"><img alt="" class="img-fluid" src="Foto/berita_1.jpg"></div>
									</div>
									<div class="col-lg-8 col-md-12 col-12 ps-lg-4">
										<div class="berita-text">
											<h2>Mewujudkan Mimpi Pendidikan untuk Generasi Masa Depan</h2>
											<p>Campaign ini telah selesai dan telah tersalurkan ke orang yang bersangkutan.</p>
											<a class="btn btn-outline-success" href="berita.php">Baca Selengkapnya</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="single-item">
								<div class="row">
									<div class="col-lg-4 col-md-12 col-12">
										<div class="berita-img"><img alt="" class="img-fluid" src="Foto/berita_3.jpg"></div>
									</div>
									<div class="col-lg-8 col-md-12 col-12 ps-lg-4">
										<div class="berita-text">
											<h2>Membangun Harapan untuk Anak Yatim<br>
											</h2>
											<p>Campaign ini telah selesai dan telah tersalurkan ke orang yang bersangkutan.</p>
											<a class="btn btn-outline-success" href="#">Baca Selengkapnya</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="single-item">
								<div class="row">
									<div class="col-lg-4 col-md-12 col-12">
										<div class="berita-img"><img alt="" class="img-fluid" src="Foto/berita_2.jpg"></div>
									</div>
									<div class="col-lg-8 col-md-12 col-12 ps-lg-4">
										<div class="berita-text">
											<h2>Bangun Jembatan untuk Masa Depan yang Lebih Terhubung<br>
											</h2>
											<p>Campaign ini telah selesai dan telah tersalurkan ke orang yang bersangkutan.</p>
											<a class="btn btn-outline-success" href="#">Baca Selengkapnya</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
				</div>

			</div>
		</div>
	</section><!-- berita ends -->
	<!-- about section Starts -->
	<section id="about" class="py-5 bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<h2 class="mb-4 text-center">Tentang Care Bridge</h2>
					<div class="text-center">
						<img src="foto/about.jpg" alt="Care Bridge" class="img-fluid rounded shadow-sm mb-4" style="max-width: 300px;">
					</div>
					<p class="lead text-justify">
						<strong>Care Bridge</strong> adalah platform inovatif yang bertujuan untuk menghubungkan para dermawan dengan berbagai kampanye donasi di seluruh negeri. Kami berkomitmen untuk memfasilitasi bantuan finansial bagi mereka yang membutuhkan melalui kampanye yang <em>transparan</em> dan <em>dapat diandalkan</em>.
					</p>
					<p class="text-justify">
						Kami percaya bahwa setiap individu memiliki potensi untuk membuat perubahan positif. Melalui <strong>Care Bridge</strong>, kami memberikan kesempatan bagi siapa saja untuk berkontribusi dalam berbagai kategori seperti <span class="text-success">Kesehatan</span>, <span class="text-info">Pendidikan</span>, <span class="text-warning">Kemanusiaan</span>, dan <span class="text-danger">Bencana Alam</span>. Platform kami dirancang untuk memberikan kemudahan akses, kepercayaan, dan kenyamanan bagi para donatur serta penerima manfaat.
					</p>
					<p class="text-justify">
						Kami berdedikasi untuk menyediakan pengalaman donasi yang aman, transparan, dan efisien. Dengan beragam pilihan kampanye yang tersedia, kami berharap dapat menginspirasi lebih banyak orang untuk bergabung dalam misi kami untuk membuat dunia menjadi tempat yang lebih baik.
					</p>
					<div class="text-center mt-4">
						<a href="#donasi" class="btn btn-primary btn-lg btn-success">Bergabung dengan Kami</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	><!-- about section Ends -->
</body>

</html>
