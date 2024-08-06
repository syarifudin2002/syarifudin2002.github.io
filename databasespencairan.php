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

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nomor_Registrasi = $_POST['Nomor_Registrasi'];
    $Jenis_Pembagian = $_POST['Jenis_Pembagian'];
    $Nama = $_POST['Nama'];
    $Nomor_KTP = $_POST['Nomor_KTP'];
    $Alamat = $_POST['Alamat'];
    $Jenis_Dana = $_POST['Jenis_Dana'];
    $Via = $_POST['Via'];
    $Jumlah_Dana = $_POST['Jumlah_Dana'];
    $Keterangan = $_POST['Keterangan'];
    $Submit = $_POST['submit'];
    

    // Simpan data ke dalam database atau lakukan proses lainnya sesuai kebutuhan
    // Misalnya, Anda bisa melakukan INSERT ke tabel pencairan

    // Contoh insert query (disesuaikan dengan struktur tabel Anda)
    $query = "INSERT INTO pencairan (Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat, Jenis_Dana, Via, Jumlah_Dana, Keterangan, Submit) 
              VALUES ('$Nomor_Registrasi', '$Jenis_Pembagian', '$Nama', '$Nomor_KTP', '$Alamat', '$Jenis_Dana', '$Via', '$Jumlah_Dana', '$Keterangan' $Submit')";

    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Data pencairan berhasil disimpan.";
        header("Location: daftarpenerima.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Ambil data penerima yang akan dicairkan (dari $_GET)
$Nomor_KTP = $_GET['Nomor_KTP'] ?? '';

// Query untuk mendapatkan data penerima berdasarkan Nomor KTP
$query_get_penerima = "SELECT Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat 
                       FROM muallaf 
                       WHERE Nomor_KTP = '$Nomor_KTP'
         
                       UNION 
                       
                       SELECT Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat 
                       FROM mahasiswa_luar_negeri 
                       WHERE Nomor_KTP = '$Nomor_KTP'
                       
                       UNION 
                       
                       SELECT Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat 
                       FROM penelitian 
                       WHERE Nomor_KTP = '$Nomor_KTP'";

$result_get_penerima = $conn->query($query_get_penerima);

if ($result_get_penerima->num_rows > 0) {
    $row = $result_get_penerima->fetch_assoc();
    $Nomor_Registrasi = $row['Nomor_Registrasi'];
    $Jenis_Pembagian = $row['Jenis_Pembagian'];
    $Nama = $row['Nama'];
    $Nomor_KTP = $row['Nomor_KTP'];
    $Alamat = $row['Alamat'];
} else {
    echo "Data penerima tidak ditemukan.";
    exit();
}
?>