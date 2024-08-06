<?php
session_start();
date_default_timezone_set("Asia/Bangkok");

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

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nomor_Registrasi = $_POST['Nomor_Registrasi'];
    $Jenis_Pembagian = $_POST['Jenis_Pembagian'];
    $Nama = $_POST['Nama'];
    $Nomor_KTP = $_POST['Nomor_KTP'];
    $Alamat = $_POST['Alamat'];
    $Jenis_Dana = $_POST['Jenis_Dana'];
    $Via = $_POST['Via'];
    $Jumlah_Dana = str_replace('.', '', $_POST['Jumlah_Dana']); // Remove dots before inserting into the database
    $Keterangan = $_POST['Keterangan'];

    // Insert data into database
    $query = "INSERT INTO pencairan (Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat, Jenis_Dana, Via, Jumlah_Dana, Keterangan) 
              VALUES ('$Nomor_Registrasi', '$Jenis_Pembagian', '$Nama', '$Nomor_KTP', '$Alamat', '$Jenis_Dana', '$Via', '$Jumlah_Dana', '$Keterangan')";

    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Data pencairan berhasil disimpan.";
        header("Location: daftarpenerima.php?id=" . $conn->insert_id);
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Fetch data of the recipient based on Nomor KTP from $_GET
$Nomor_KTP = $_GET['Nomor_KTP'] ?? '';

// Query to retrieve recipient data based on Nomor KTP
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

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Pencairan BAZNAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .form-control {
            padding: .25rem .5rem;
            margin-bottom: .75rem;
        }

        .card-header,
        .card-body {
            padding: .5rem 1rem;
        }

        .btn {
            padding: .25rem .5rem;
        }
    </style>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

    <div class="container mt-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                FORM PENCAIRAN BAZNAS
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="Nomor_Registrasi" class="form-label">Nomor Registrasi</label>
                                <input type="text" class="form-control" id="Nomor_Registrasi" name="Nomor_Registrasi" value="<?php echo htmlspecialchars($Nomor_Registrasi); ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="Jenis_Pembagian" class="form-label">Jenis Penerima</label>
                                <input type="text" class="form-control" id="Jenis_Pembagian" name="Jenis_Pembagian" value="<?php echo htmlspecialchars($Jenis_Pembagian); ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="Nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="Nama" name="Nama" value="<?php echo htmlspecialchars($Nama); ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="Nomor_KTP" class="form-label">Nomor KTP</label>
                                <input type="text" class="form-control" id="Nomor_KTP" name="Nomor_KTP" value="<?php echo htmlspecialchars($Nomor_KTP); ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="Alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="Alamat" name="Alamat" rows="2" readonly><?php echo htmlspecialchars($Alamat); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="Jenis_Dana" class="form-label">Jenis Dana</label>
                                <input type="text" class="form-control" id="Jenis_Dana" name="Jenis_Dana" required>
                            </div>
                            <div class="mb-2">
                                <label for="Via" class="form-label">Via</label>
                                <input type="text" class="form-control" id="Via" name="Via" required>
                            </div>
                            <div class="mb-3">
                                <label for="Jumlah_Dana" class="form-label">Jumlah Dana</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" class="form-control" id="Jumlah_Dana" name="Jumlah_Dana" required oninput="formatRupiah(this)">
                                </div>
                            <div class="mb-2">
                                <label for="Keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="Keterangan" name="Keterangan" rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Pencairan</button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='daftarpenerima.php'">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        feather.replace();

        function formatRupiah(element) {
            let value = element.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            element.value = rupiah;
        }
    </script>
</body>

</html>