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

$data_penerima = [];

$tables = ["muallaf", "mahasiswa_luar_negeri", "penelitian"];

foreach ($tables as $table) {
    $query = "SELECT Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Tempat_Lahir, Tanggal_Lahir, Jenis_Kelamin, Pekerjaan, Alamat, Kecamatan, Kabupaten, No_Hp, Submit 
              FROM $table 
              ORDER BY Submit DESC";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $data_penerima[] = $row;
    }
}

// Handle Search
$search_result = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    foreach ($data_penerima as $data) {
        if (
            stripos($data['Nomor_KTP'], $search_term) !== false ||
            stripos($data['Nama'], $search_term) !== false
        ) {
            $search_result[] = $data;
        }
    }
}

// Determine which data to display
$display_data = !empty($search_result) ? $search_result : $data_penerima;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAZNAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        .navbar-brand {
            letter-spacing: 2px;
            /* memberikan jarak antar huruf dari tulisan baznas */
        }

        .navbar-brand span {
            font-size: 12px;
            margin-top: 5px;
            /* Mengurangi jarak atas untuk elemen span */
            display: flex;
            line-height: 1px;
            /* Mengatur tinggi baris untuk elemen span */
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <img src="gambar/baznasntb.png" alt="logo" width="120" height="74">
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
                            <li><a class="dropdown-item" href="daftarpenerima2.php">Kelompok</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Daftar Pencairan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="daftarpencairan.php">Perorangan</a></li>
                            <li><a class="dropdown-item" href="daftarpencairan2.php">Kelompok</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div id="notification" class="alert alert-success" role="alert">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>

        <div class="card mt-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <b>Data Penerima</b>

                <!-- Container untuk Form Pencarian dan Batas Tabel -->
                <div class="search-container">
                    <!-- Form Pencarian -->
                    <form class="search-form" method="GET">
                        <input type="text" id="search" name="search" placeholder="Cari nomor KTP atau nama...">
                        <i data-feather="search" class="search-button"></i>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" style="text-align: center;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Nomor Registrasi</th>
                            <th>Jenis Penerima</th>
                            <th>Nama Penerima</th>
                            <th>Nomor KTP</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Pekerjaan</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten</th>
                            <th>No Hp</th>
                            <th>Submit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($display_data as $data) : ?>
                            <tr class="data-row">
                                <td><?php echo htmlspecialchars($data['Nomor_Registrasi']); ?></td>
                                <td><?php echo htmlspecialchars($data['Jenis_Pembagian']); ?></td>
                                <td>
                                    <a href="crudpenerima.php?Nomor_Registrasi=<?php echo htmlspecialchars($data['Nomor_Registrasi']); ?>&jenispenerima=<?php echo htmlspecialchars($data['Jenis_Pembagian']); ?>">
                                        <?php echo htmlspecialchars($data['Nama']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($data['Nomor_KTP']); ?></td>
                                <td><?php echo htmlspecialchars($data['Tempat_Lahir']); ?></td>
                                <td><?php echo htmlspecialchars($data['Tanggal_Lahir']); ?></td>
                                <td><?php echo htmlspecialchars($data['Jenis_Kelamin']); ?></td>
                                <td><?php echo htmlspecialchars($data['Pekerjaan']); ?></td>
                                <td><?php echo htmlspecialchars($data['Alamat']); ?></td>
                                <td><?php echo htmlspecialchars($data['Kecamatan']); ?></td>
                                <td><?php echo htmlspecialchars($data['Kabupaten']); ?></td>
                                <td><?php echo htmlspecialchars($data['No_Hp']); ?></td>
                                <td><?php echo htmlspecialchars($data['Submit']); ?></td>
                                <td>
                                    <a href="pencairan.php?Nomor_KTP=<?php echo htmlspecialchars($data['Nomor_KTP']); ?>" class="btn btn-primary btn-sm">Pencairan</a>

                                    <a href="delete.php?Nomor_KTP=<?php echo htmlspecialchars($data['Nomor_KTP']); ?>&origin=daftarpenerima" onclick="return confirm('Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        feather.replace();

        // Display notification if exists
        document.addEventListener('DOMContentLoaded', (event) => {
            var notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 5000); // Hide after 5 seconds
            }
            var errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                errorNotification.style.display = 'block';
                setTimeout(function() {
                    errorNotification.style.display = 'none';
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>

</body>

</html>