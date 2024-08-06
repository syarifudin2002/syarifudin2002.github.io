<?php
session_start();
date_default_timezone_set("Asia/Bangkok");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangani form submission
if (isset($_POST['submit'])) {
    $nomorregistrasi = $_POST['nomorregistrasi'];
    $jenispenerima = $_POST['jenispenerima'];
    $namalembaga = $_POST['namalembaga'];
    $nikpimpinan = $_POST['nikpimpinan'];
    $namapimpinan = $_POST['namapimpinan'];
    $jenislembaga = $_POST['jenislembaga'];
    $jumlahanggota = $_POST['jumlahanggota'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $kabupaten = $_POST['kabupaten'];
    $nomorhp = $_POST['nomorhp'];
    $submit_date = $_POST['submit']; // Tanggal submit

    // Prevent SQL Injection
    $nomorregistrasi = mysqli_real_escape_string($conn, $nomorregistrasi);
    $jenispenerima = mysqli_real_escape_string($conn, $jenispenerima);
    $namalembaga = mysqli_real_escape_string($conn, $namalembaga);
    $nikpimpinan = mysqli_real_escape_string($conn, $nikpimpinan);
    $namapimpinan = mysqli_real_escape_string($conn, $namapimpinan);
    $jenislembaga = mysqli_real_escape_string($conn, $jenislembaga);
    $jumlahanggota = mysqli_real_escape_string($conn, $jumlahanggota);
    $alamat = mysqli_real_escape_string($conn, $alamat);
    $kecamatan = mysqli_real_escape_string($conn, $kecamatan);
    $kabupaten = mysqli_real_escape_string($conn, $kabupaten);
    $nomorhp = mysqli_real_escape_string($conn, $nomorhp);
    $submit_date = mysqli_real_escape_string($conn, $submit_date);

    // Insert data into appropriate table based on jenis penerima
    $table_name = ($jenispenerima == 'tpq') ? 'tpq' : 'sabilillah';

    // Cek apakah nomor KTP sudah ada di tabel mana pun
    $check_query = "SELECT * FROM sabilillah WHERE NIK_Pimpinan = '$nikpimpinan' UNION 
                    SELECT * FROM tpq WHERE NIK_Pimpinan = '$nikpimpinan'";

    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['message'] = "Nomor KTP sudah terdata.";
        $_SESSION['message_type'] = "error"; // Set jenis pesan sebagai error
    } else {
        $sql = "INSERT INTO $table_name (Nomor_Registrasi, Nama_Lembaga, Jenis_Penerima, NIK_Pimpinan, Nama_Pimpinan, Jenis_Lembaga, Jumlah_Anggota, Alamat, Kecamatan, Kabupaten, No_Hp, Submit) 
                VALUES ('$nomorregistrasi', '$namalembaga', '$jenispenerima', '$nikpimpinan', '$namapimpinan', '$jenislembaga', '$jumlahanggota', '$alamat', '$kecamatan', '$kabupaten', '$nomorhp', '$submit_date')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Data berhasil ditambahkan.";
            $_SESSION['message_type'] = "success"; // Set jenis pesan sebagai sukses
        } else {
            $_SESSION['message'] = "Terjadi kesalahan: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error"; // Set jenis pesan sebagai error
        }
    }

    header("Location: kelompok.php");
    exit();
}

$conn->close();
?>
