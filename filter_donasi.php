<?php
// Koneksi ke database
include("inc_koneksi.php");

// Ambil nilai filter kategori jika tersedia
$filterCategory = isset($_POST['filter_category']) ? $_POST['filter_category'] : '';

// Query untuk mengambil data donasi berdasarkan kategori yang dipilih
$sql = "SELECT * FROM campaign";
if (!empty($filterCategory)) {
    $sql .= " WHERE kategori = '$filterCategory'";
}

$result = mysqli_query($koneksi, $sql);

// Check apakah terdapat data
if (mysqli_num_rows($result) > 0) {
    // Loop melalui setiap baris data
    while ($row = mysqli_fetch_assoc($result)) {
        // Tampilkan informasi donasi
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<img src="Foto/' . $row['gambar'] . '" class="card-img-top" alt="Gambar Campaign" style="height: 200px; object-fit: cover;">';
        echo '<div class="card-body d-flex flex-column">';
        echo '<h5 class="card-title fw-bold">' . $row['nama_campaign'] . '</h5>';
        echo '<p class="card-text"><strong>Kategori:</strong> ' . $row['kategori'] . '</p>';
        echo '<p class="card-text flex-grow-1"><strong>Deskripsi:</strong> ' . $row['deskripsi'] . '</p>';
        echo '<p class="card-text"><strong>Target Dana:</strong> Rp. ' . number_format($row['target_dana']) . '</p>';
        echo '<a href="transaksi.php?id=' . $row['id'] . '" class="btn btn-primary mt-auto">Donate Now</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // Jika tidak ada data
    echo '<div class="col-12"><p class="text-center">Tidak ada data donasi.</p></div>';
}

// Tutup koneksi database
mysqli_close($koneksi);
?>
