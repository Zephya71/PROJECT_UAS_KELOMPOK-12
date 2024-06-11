<?php
// Koneksi ke database
include("inc_koneksi.php");

// Query untuk mengambil data dari tabel campaign
$sql = "SELECT * FROM campaign";
$result = mysqli_query($koneksi, $sql);

// Check apakah terdapat data
if (mysqli_num_rows($result) > 0) {
    // Loop melalui setiap baris data
    while ($row = mysqli_fetch_assoc($result)) {
        // Tampilkan informasi donasi dalam card Bootstrap
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow rounded">';
        echo '<img src="Foto/' . $row['gambar'] . '" class="card-img-top img-fluid" alt="Gambar Campaign">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title fw-bold">' . $row['nama_campaign'] . '</h5>';
        echo '<p class="card-text"><strong>Kategori:</strong> ' . $row['kategori'] . '</p>';
        echo '<p class="card-text"><strong>Deskripsi:</strong> ' . $row['deskripsi'] . '</p>';
        echo '<p class="card-text"><strong>Target Dana:</strong> Rp. ' . number_format($row['target_dana']) . '</p>';
        echo '</div>';
        echo '<div class="card-footer bg-primary">';
        echo '<a href="transaksi.php?id=' . $row['id'] . '" class="btn btn-light">Donate Now</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // Jika tidak ada data
    echo "Tidak ada data donasi.";
}

// Tutup koneksi database
mysqli_close($koneksi);
?>
