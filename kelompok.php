<?php
session_start(); // Pastikan session_start() di bagian paling atas
date_default_timezone_set("Asia/Bangkok");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/// Ambil nomor registrasi terakhir dari semua tabel
$tables = ['sabilillah', 'tpq'];
$last_reg_num = 0;

foreach ($tables as $table) {
    $last_reg_query = "SELECT Nomor_Registrasi FROM $table ORDER BY Nomor_Registrasi DESC LIMIT 1";
    $last_reg_result = $conn->query($last_reg_query);

    if ($last_reg_result && $last_reg_result->num_rows > 0) {
        $row = $last_reg_result->fetch_assoc();
        // Hapus huruf awal (A) sebelum konversi ke integer
        $last_reg_num_table = (int)substr($row['Nomor_Registrasi'], 1);
        if ($last_reg_num_table > $last_reg_num) {
            $last_reg_num = $last_reg_num_table;
        }
    }
}
// Tentukan nomor registrasi berikutnya
$new_reg_num = $last_reg_num + 1;

// Format nomor registrasi dengan tiga digit
$new_reg_num_formatted = 'A' . sprintf('%03d', $new_reg_num);
$conn->close();

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAZNAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
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

    <?php if (isset($_SESSION['message'])) : ?>
        <div id="notification" class="notification <?php echo $_SESSION['message_type']; ?>">
            <?php echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <script>
        // Ambil elemen notifikasi
        var notification = document.getElementById('notification');

        // Tampilkan notifikasi
        notification.style.display = 'block';

        // Sembunyikan notifikasi setelah 5 detik
        setTimeout(function() {
            notification.style.display = 'none';
        }, 5000); // 5000 milidetik = 5 detik
    </script>

</body>
</div>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <img src="gambar/baznasntb.png" alt="logo" width="120" height="74">
        <a class="navbar-brand" href="#"><strong>BAZNAS NTB</strong> <br>
            <span>Badan Amal Zakat Nasional NTB</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center " id="navbarNavDropdown">
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
                        <li><a class="dropdown-item" href="#">Kelompok</a></li>
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


<form action="databases2.php" method="post">
    <div style="text-align: center;">
        <img src="gambar/baznasntb.png" alt="logo" width="210" height="110">
        <h1 class="mt-4">PENDAFTARAN PROGRAM KELOMPOK</h1>
    </div>


    <div class="form-container">
        <!-- Kolom kiri -->
        <div class="form-column">
            <div class="form-group">
                <label for="nomorregistrasi">Nomor Registrasi</label>
                <input type="text" name="nomorregistrasi" id="nomorregistrasi" autocomplete="off" value="<?php echo $new_reg_num_formatted; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jenispenerima">Jenis Penerima</label>
                <select name="jenispenerima" id="jenispenerima" onchange="jenisPembagianChanged()">
                    <option value="sabilillah">Sabililah</option>
                    <option value="tpq">TPQ</option>
                    <!-- Tambahkan opsi lainnya di sini -->
                </select>
            </div>
            <div class="form-group">
                <label for="namalembaga">Nama Lembaga</label>
                <input type="text" name="namalembaga" id="nama" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="nikpimpinan">NIK Pimpinan</label>
                <input type="text" name="nikpimpinan" id="nikpimpinan" autocomplete="off" minlength="16" maxlength="16" pattern="\d{16}" required oninput="validateKTP(this)">
                <span id="ktpError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="namapimpinan">Nama Pimpinan</label>
                <input type="text" name="namapimpinan" id="namapimpinan" autocomplete="off" oninput="validateInput(this)" required>
            </div>

            <div class="form-group">
                <label for="jenislembaga">Jenis Lembaga</label>
                <select name="jenislembaga" id="jenislembaga">
                    <option value="musholla">Musholla</option>
                    <option value="masjid">Masjid</option>
                </select>
            </div>
        </div>

        <!-- Kolom kanan -->
        <div class="form-column">
            <div class="form-group">
                <label for="jumlahanggota">Jumlah Anggota</label>
                <input type="text" name="jumlahanggota" id="jumlahanggota" autocomplete="off" oninput="validateInput(this)" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" autocomplete="off" oninput="validateInput(this)" required>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <input type="text" name="kecamatan" id="kecamatan" autocomplete="off" oninput="validateInput(this)" required>
            </div>
            <div class="form-group">
                <label for="kabupaten">Kabupaten</label>
                <input type="text" name="kabupaten" id="kabupaten" autocomplete="off" oninput="validateInput(this)" required>
            </div>
            <div class="form-group">
                <label for="nomorhp">Nomor HP</label>
                <input type="text" name="nomorhp" id="nomorhp" autocomplete="off" oninput="validateInput(this)" required>
            </div>
        </div>
    </div>
    <div class="button-container">
        <button type="submit" name="submit" value="<?php echo date('Y-m-d'); ?>">Submit</button>
    </div>


</form>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>