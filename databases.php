<?php
session_start();
date_default_timezone_set("Asia/Bangkok");

$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "baznas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nomor_Registrasi = $_POST['nomorregistrasi'];
    $Jenis_Pembagian = $_POST['jenispembagian'];
    $Nama = $_POST['nama'];
    $Nomor_KTP = $_POST['nomorktp'];
    $Tempat_Lahir = $_POST['tempatlahir'];
    $Tanggal_Lahir = $_POST['tanggallahir'];
    $Jenis_Kelamin = $_POST['jeniskelamin'];
    $Pekerjaan = $_POST['pekerjaan'];
    $Alamat = $_POST['alamat'];
    $Kecamatan = $_POST['kecamatan'];
    $Kabupaten = $_POST['kabupaten'];
    $No_Hp = $_POST['nomorhp'];
    $Submit = $_POST['submit'];

    // Tentukan tabel berdasarkan Jenis Pembagian
    $table_name = "";
    switch ($Jenis_Pembagian) {
        case "muallaf":
            $table_name = "muallaf";
            break;
        case "mahasiswa_luar_negeri":
            $table_name = "mahasiswa_luar_negeri";
            break;
        case "penelitian":
            $table_name = "penelitian";
            break;
        default:
            // Tambahkan logika untuk jenis pembagian lainnya jika ada
            break;
    }

    if ($table_name != "") {
        // Cek apakah nomor KTP sudah ada di tabel mana pun
        $check_query = "SELECT * FROM muallaf WHERE Nomor_KTP='$Nomor_KTP' UNION 
                        SELECT * FROM mahasiswa_luar_negeri WHERE Nomor_KTP='$Nomor_KTP' UNION 
                        SELECT * FROM penelitian WHERE Nomor_KTP='$Nomor_KTP'";
                       

        $result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['message'] = "Nomor KTP sudah terdata.";
            $_SESSION['message_type'] = "error"; // Set jenis pesan sebagai error
        } else {
            $query = "INSERT INTO $table_name (Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Tempat_Lahir, Tanggal_Lahir, Jenis_Kelamin, Pekerjaan, Alamat, Kecamatan, Kabupaten, No_Hp, Submit) VALUES ('$Nomor_Registrasi', '$Jenis_Pembagian', '$Nama', '$Nomor_KTP', '$Tempat_Lahir', '$Tanggal_Lahir', '$Jenis_Kelamin', '$Pekerjaan', '$Alamat', '$Kecamatan', '$Kabupaten', '$No_Hp', '$Submit')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = "Data berhasil ditambahkan.";
                $_SESSION['message_type'] = "success"; // Set jenis pesan sebagai sukses
            } else {
                $_SESSION['message'] = "Terjadi kesalahan: " . mysqli_error($conn);
                $_SESSION['message_type'] = "error"; // Set jenis pesan sebagai error
            }
        }
    } else {
        $_SESSION['message'] = "Jenis Pembagian tidak valid.";
        $_SESSION['message_type'] = "error"; // Set jenis pesan sebagai error
    }


    header("Location: perorangan.php");
    exit();
}


$conn->close();
