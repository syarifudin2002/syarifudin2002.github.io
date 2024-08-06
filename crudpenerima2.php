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

$nomor_registrasi = $_GET['Nomor_Registrasi'];
$data_penerima = null;

$tables = ["sabilillah", "tpq"];

foreach ($tables as $table) {
    $query = "SELECT * FROM $table WHERE Nomor_Registrasi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nomor_registrasi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data_penerima = $result->fetch_assoc();
        break;
    }
}

if (!$data_penerima) {
    echo "Data penerima tidak ditemukan.";
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Penerima - BAZNAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <img src="gambar/logo-baznas4.png" alt="logo" width="90" height="74">
            <a class="navbar-brand" href="#"><strong>BAZNAS NTB</strong> <br>
                <span>Badan Amal Zakat Nasional NTB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pendaftaran
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="perorangan.php">Perorangan</a></li>
                            <li><a class="dropdown-item" href="daftarpenerima2.php">Kelompok</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Daftar Penerima
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="daftarpenerima.php">Perorangan</a></li>
                            <li><a class="dropdown-item" href="kelompok.php">Kelompok</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftarpencairan.php">Daftar Pencairan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section>
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <b>Detail Penerima</b>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nomor Registrasi</th>
                        <td><?php echo htmlspecialchars($data_penerima['Nomor_Registrasi']); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Penerima</th>
                        <td><?php echo htmlspecialchars($data_penerima['Jenis_Penerima']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Lembaga</th>
                        <td><?php echo htmlspecialchars($data_penerima['Nama_Lembaga']); ?></td>
                    </tr>
                    <tr>
                        <th>NIK Pimpinan</th>
                        <td><?php echo htmlspecialchars($data_penerima['NIK_Pimpinan']); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Pimpinan</th>
                        <td><?php echo htmlspecialchars($data_penerima['Nama_Pimpinan']); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Lembaga</th>
                        <td><?php echo htmlspecialchars($data_penerima['Jenis_Lembaga']); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Anggota</th>
                        <td><?php echo htmlspecialchars($data_penerima['Jumlah_Anggota']); ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo htmlspecialchars($data_penerima['Alamat']); ?></td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td><?php echo htmlspecialchars($data_penerima['Kecamatan']); ?></td>
                    </tr>
                    <tr>
                        <th>Kabupaten</th>
                        <td><?php echo htmlspecialchars($data_penerima['Kabupaten']); ?></td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td><?php echo htmlspecialchars($data_penerima['No_Hp']); ?></td>
                    </tr>
                    <tr>
                        <th>Submit</th>
                        <td><?php echo htmlspecialchars($data_penerima['Submit']); ?></td>
                    </tr>
                </table>
                <div class="mt-3 text-center">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='daftarpenerima2.php'">Cancel</button>
    </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>
