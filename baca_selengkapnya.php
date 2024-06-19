<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Selengkapnya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #28a745;
        }
        .navbar a {
            color: #fff !important;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
        }
        .berita-detail {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .berita-img img {
            border-radius: 10px;
        }
        .berita-detail h2 {
            color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Berita</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">Kembali</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="header text-center">
        <div class="container">
            <h1>Berita Selengkapnya</h1>
        </div>
    </div>

    <div class="container my-5">
        <?php
        // Define the news articles
        $news = [
            1 => [
                'title' => 'Mewujudkan Mimpi Pendidikan untuk Generasi Masa Depan',
                'image' => 'Foto/berita_1.jpg',
                'description' => 'Dengan penuh semangat dan kerja keras, kampanye "Mewujudkan Mimpi Pendidikan untuk Generasi Masa Depan" akhirnya mencapai puncaknya. Berkat dukungan besar dari para donatur dan relawan, dana yang terkumpul sebesar RP. 12.000.000,00 telah berhasil disalurkan dengan tepat sasaran. Setiap sumbangan telah memberikan sinar harapan bagi anak-anak untuk meraih pendidikan yang layak, membangun pondasi yang kokoh bagi masa depan yang lebih baik. Saat ini, kita merayakan pencapaian bersama dalam mewujudkan mimpi-mimpi mereka melalui pendidikan yang bermakna dan membanggakan.'
            ],
            2 => [
                'title' => 'Membangun Harapan untuk Anak Yatim',
                'image' => 'Foto/berita_3.jpg',
                'description' => 'Dalam upaya mengubah masa depan mereka, campaign ini mengajak semua elemen masyarakat untuk bersatu dan memberikan kontribusi nyata. Melalui pendekatan yang holistik, campaign ini tidak hanya bertujuan untuk memenuhi kebutuhan materi seperti pendidikan dan pangan, tetapi juga memberikan dukungan emosional dan psikologis yang mereka perlukan. Dengan menyediakan pendidikan yang berkualitas dan pembinaan spiritual yang mendalam, campaign ini membuka jalan bagi mereka untuk tumbuh dan berkembang menjadi generasi penerus bangsa yang tangguh dan berdaya saing. Dukungan dari berbagai pihak diharapkan mampu menciptakan lingkungan yang aman, penuh kasih, dan penuh harapan bagi setiap anak yatim, sehingga mereka dapat menatap masa depan dengan penuh optimisme dan keyakinan.'
            ],
            3 => [
                'title' => 'Bangun Jembatan untuk Masa Depan yang Lebih Terhubung',
                'image' => 'Foto/berita_2.jpg',
                'description' => 'Dengan mempertemukan dua sisi yang terpisah, jembatan ini tidak hanya menghubungkan jalan-jalan fisik, tetapi juga merajut jalinan antarwarga yang lebih kuat. Setiap langkah pembangunan jembatan ini mencerminkan tekad untuk mengatasi hambatan geografis dan sosial, membuka akses baru bagi mobilitas dan peluang bagi masyarakat. Dari fondasi hingga penyelesaian, setiap tahap proyek ini dikerjakan dengan penuh dedikasi dan keinginan untuk memberikan dampak positif jangka panjang bagi seluruh komunitas Sukorejo.'
            ]
        ];

        // Get the news ID from the URL parameter
        $news_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

        // Get the selected news article
        $selected_news = $news[$news_id];
        ?>

        <div class="berita-detail mb-4">
            <h2><?php echo $selected_news['title']; ?></h2>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="berita-img"><img alt="<?php echo $selected_news['title']; ?>" class="img-fluid" src="<?php echo $selected_news['image']; ?>"></div>
                </div>
                <div class="col-lg-8 col-md-12 col-12 ps-lg-4">
                    <p><?php echo $selected_news['description']; ?></p>
                </div>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
