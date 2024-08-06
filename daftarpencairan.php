<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$data_penerima = [];

$tables = ["pencairan"];

foreach ($tables as $table) {
    $query = "SELECT  Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat, Jenis_Dana, Via, Jumlah_Dana, Keterangan
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


// Query untuk mengambil data pencairan
$query_get_pencairan = "SELECT * FROM pencairan";
$result_get_pencairan = $conn->query($query_get_pencairan);

// Inisialisasi array untuk menampung data pencairan
$display_data = [];

if ($result_get_pencairan->num_rows > 0) {
    while ($row = $result_get_pencairan->fetch_assoc()) {
        $display_data[] = $row;
    }
} else {
    echo "Tidak ada data yang ditemukan.";
}



// Determine which data to display
$display_data = !empty($search_result) ? $search_result : $data_penerima;

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pencairan BAZNAS</title>
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

        .header-actions {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }
        .export-icon {
            cursor: pointer;
            margin-right: 20px;
        }

        .export-icon img {
            width: 32px;
            height: 32px;
        }

        .table thead th {
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }

        .table tbody td.keterangan {
            text-align: left;
            width: 400px;
            /* Set your desired width here */
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
                            <li><a class="dropdown-item" href="kelompok.php">Kelompok</a></li>
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
                <b>Data Pencairan Program</b>

                <!-- Container untuk Form Pencarian dan Batas Tabel -->
                <div class="header-actions">
                    <!-- Ikon Excel untuk Ekspor -->
                    <a href="export_excel.php" class="export-icon" title="Ekspor ke Excel">
                        <img src="gambar/excell.png" alt="Excel Icon"/>
                    </a>
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
                            <th>Nama</th>
                            <th>Nomor KTP</th>
                            <th>Alamat</th>
                            <th>Jenis Dana</th>
                            <th>Via</th>
                            <th>Jumlah Dana</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($display_data)) {
                            foreach ($display_data as $data) {
                        ?>
                                <tr class="data-row">
                                    <td><?php echo htmlspecialchars($data['Nomor_Registrasi']); ?></td>
                                    <td><?php echo htmlspecialchars($data['Jenis_Pembagian']); ?></td>
                                    <td>
                                        <a href="crudpenerima.php?Nomor_Registrasi=<?php echo htmlspecialchars($data['Nomor_Registrasi']); ?>&jenispenerima=<?php echo htmlspecialchars($data['Jenis_Pembagian']); ?>">
                                            <?php echo htmlspecialchars($data['Nama']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($data['Nomor_KTP']); ?></td>
                                    <td><?php echo htmlspecialchars($data['Alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($data['Jenis_Dana']); ?></td>
                                    <td><?php echo htmlspecialchars($data['Via']); ?></td>
                                    <td><?php echo htmlspecialchars($data['Jumlah_Dana']); ?></td>
                                    <td class="keterangan"><?php echo htmlspecialchars($data['Keterangan']); ?></td>
                                    <td>
                                        <a href="#" onclick="printKwitansi('<?php echo htmlspecialchars($data['Nomor_KTP']); ?>')" class="btn btn-primary btn-sm">Cetak</a>
                                        <a href="delete.php?Nomor_KTP=<?php echo htmlspecialchars($data['Nomor_KTP']); ?>&origin=daftarpencairan" onclick="return confirm('Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            // Handle jika data tidak ditemukan
                            ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data yang ditemukan.</td>
                            </tr>
                        <?php
                        }
                        ?>
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
        });
    </script>
    <script>
         function printKwitansi(nomorKTP) {
            // Buat URL untuk halaman cetak
            var url = 'cetak.php?Nomor_KTP=' + encodeURIComponent(nomorKTP);
            
            // Buka halaman cetak di jendela baru
            var printWindow = window.open(url, '_blank');
            
            // Tunggu hingga halaman selesai dimuat
            printWindow.onload = function() {
                // Panggil dialog cetak setelah halaman selesai dimuat
                printWindow.print();
            };
        }

    </script>

</body>

</html>