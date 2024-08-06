<?php
session_start();

$NIK_Pimpinan = $_GET['NIK_Pimpinan'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil Nomor KTP dari parameter URL
if (isset($_GET['NIK_Pimpinan'])) {
    $nomor_ktp = $_GET['NIK_Pimpinan'];
    $origin_page = isset($_GET['origin']) ? $_GET['origin'] : '';

    // Tentukan tabel berdasarkan halaman asal
    if ($origin_page === 'daftarpenerima2') {
        $tables = ["sabilillah", "tpq"];
    } elseif ($origin_page === 'daftarpencairan2') {
        $tables = ["pencairank"]; // Ganti dengan nama tabel yang sesuai untuk data pencairan
    } else {
        $tables = [];
    }

    foreach ($tables as $table) {
        $query = "DELETE FROM $table WHERE NIK_Pimpinan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $NIK_Pimpinan);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['message'] = "Data berhasil dihapus.";
} else {
    $_SESSION['message'] = "NIK Pimpinan tidak ditemukan.";
}

$conn->close();

// Redirect kembali ke halaman asal
if ($origin_page === 'daftarpenerima2') {
    header("Location: daftarpenerima2.php");
} elseif ($origin_page === 'daftarpencairan2') {
    header("Location: daftarpencairan2.php");
} else {
    header("Location: home.php");
}
exit();
?>
