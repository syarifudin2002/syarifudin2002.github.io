<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil Nomor KTP dari parameter URL
if (isset($_GET['Nomor_KTP'])) {
    $nomor_ktp = $_GET['Nomor_KTP'];
    $origin_page = isset($_GET['origin']) ? $_GET['origin'] : '';

    // Tentukan tabel berdasarkan halaman asal
    if ($origin_page === 'daftarpenerima') {
        $tables = ["muallaf", "mahasiswa_luar_negeri", "penelitian"];
    } elseif ($origin_page === 'daftarpencairan') {
        $tables = ["pencairan"]; // Ganti dengan nama tabel yang sesuai untuk data pencairan
    } else {
        $tables = [];
    }

    foreach ($tables as $table) {
        $query = "DELETE FROM $table WHERE Nomor_KTP = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nomor_ktp);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['message'] = "Data berhasil dihapus.";
} else {
    $_SESSION['message'] = "Nomor KTP tidak ditemukan.";
}

$conn->close();

// Redirect kembali ke halaman asal
if ($origin_page === 'daftarpenerima') {
    header("Location: daftarpenerima.php");
} elseif ($origin_page === 'daftarpencairan') {
    header("Location: daftarpencairan.php");
} else {
    header("Location: home.php");
}
exit();
?>
