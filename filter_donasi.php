<?php
// Koneksi ke database
include("inc_koneksi.php");

// Ambil nilai filter kategori jika tersedia
$filterCategory = isset($_POST['filter_category']) ? $_POST['filter_category'] : '';

// Query untuk mengambil data donasi berdasarkan kategori yang dipilih
$sql = "SELECT c.*, COALESCE(SUM(t.jumlah), 0) as dana_terkumpul
        FROM campaign c
        LEFT JOIN transaksi t ON c.id = t.id";

if (!empty($filterCategory)) {
    $sql .= " WHERE c.kategori = '$filterCategory'";
}

$sql .= " GROUP BY c.id";

$result = mysqli_query($koneksi, $sql);

// Check apakah terdapat data
if (mysqli_num_rows($result) > 0) {
    // Loop melalui setiap baris data
    while ($row = mysqli_fetch_assoc($result)) {
        // Hitung persentase dana yang terkumpul
        $persentase_terkumpul = ($row['dana_terkumpul'] / $row['target_dana']) * 100;
        $sisa_target_dana = $row['target_dana'] - $row['dana_terkumpul'];

        // Tampilkan informasi donasi
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<img src="Foto/' . $row['gambar'] . '" class="card-img-top" alt="Gambar Campaign" style="height: 200px; object-fit: cover;">';
        echo '<div class="card-body d-flex flex-column">';
        echo '<h5 class="card-title fw-bold">' . $row['nama_campaign'] . '</h5>';
        echo '<p class="card-text">' . $row['kategori'] . '</p>';
        echo '<div class="progress" style="height: 20px;">';
        echo '<div class="progress-bar" role="progressbar" style="width: ' . $persentase_terkumpul . '%; background-color: #6495ed; color: white;" aria-valuenow="' . $persentase_terkumpul . '" aria-valuemin="0" aria-valuemax="100">' . number_format($persentase_terkumpul, 2) . '%</div>';
        echo '</div>';
        echo '<p class="card-text mt-2"><strong>Dana Terkumpul:</strong> Rp. ' . number_format($row['dana_terkumpul']) . '</p>';
        echo '<a href="campaign_detail.php?id=' . $row['id'] . '" class="btn btn-success mt-auto">Lihat Donasi</a>';
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
