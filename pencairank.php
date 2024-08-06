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

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nomor_Registrasi = $_POST['Nomor_Registrasi'];
    $Jenis_Penerima = $_POST['Jenis_Penerima'];
    $Nama_Lembaga = $_POST['Nama_Lembaga'];
    $NIK_Pimpinan = $_POST['NIK_Pimpinan'];
    $Nama_Pimpinan = $_POST['Nama_Pimpinan'];
    $Alamat = $_POST['Alamat'];
    $Jenis_Dana = $_POST['Jenis_Dana'];
    $Via = $_POST['Via'];
    $Jumlah_Dana = str_replace('.', '', $_POST['Jumlah_Dana']); // Remove dots before inserting into the database
    $Keterangan = $_POST['Keterangan'];
    

    $query = "INSERT INTO pencairank (Nomor_Registrasi, Jenis_Penerima, Nama_Lembaga, NIK_Pimpinan, Nama_Pimpinan, Alamat, Jenis_Dana, Via, Jumlah_Dana, Keterangan) 
              VALUES ('$Nomor_Registrasi', '$Jenis_Penerima', '$Nama_Lembaga', '$NIK_Pimpinan', '$Nama_Pimpinan', '$Alamat', '$Jenis_Dana', '$Via', '$Jumlah_Dana', '$Keterangan')";

    if ($conn->query($query) === TRUE) {
        $_SESSION['message'] = "Data pencairan berhasil disimpan.";
        header("Location: daftarpenerima2.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Fetch data of the recipient based on NIK_Pimpinan from $_GET
$NIK_Pimpinan = $_GET['NIK_Pimpinan'] ?? '';

$query_get_penerima = "SELECT Nomor_Registrasi, Jenis_Penerima, Nama_Lembaga, NIK_Pimpinan, Nama_Pimpinan, Alamat 
                       FROM sabilillah 
                       WHERE NIK_Pimpinan = '$NIK_Pimpinan'

                       UNION 

                       SELECT Nomor_Registrasi, Jenis_Penerima, Nama_Lembaga, NIK_Pimpinan, Nama_Pimpinan, Alamat 
                       FROM tpq 
                       WHERE NIK_Pimpinan = '$NIK_Pimpinan'";

$result_get_penerima = $conn->query($query_get_penerima);

if ($result_get_penerima->num_rows > 0) {
    $row = $result_get_penerima->fetch_assoc();
    $Nomor_Registrasi = $row['Nomor_Registrasi'];
    $Jenis_Penerima = $row['Jenis_Penerima'];
    $Nama_Lembaga = $row['Nama_Lembaga'];
    $NIK_Pimpinan = $row['NIK_Pimpinan'];
    $Nama_Pimpinan = $row['Nama_Pimpinan'];
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
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                FORM PENCAIRAN BAZNAS
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Nomor_Registrasi" class="form-label">Nomor Registrasi</label>
                                <input type="text" class="form-control" id="Nomor_Registrasi" name="Nomor_Registrasi" value="<?php echo htmlspecialchars($Nomor_Registrasi); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Jenis_Penerima" class="form-label">Jenis Penerima</label>
                                <input type="text" class="form-control" id="Jenis_Penerima" name="Jenis_Penerima" value="<?php echo htmlspecialchars($Jenis_Penerima); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Nama_Lembaga" class="form-label">Nama Lembaga</label>
                                <input type="text" class="form-control" id="Nama_Lembaga" name="Nama_Lembaga" value="<?php echo htmlspecialchars($Nama_Lembaga); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="NIK_Pimpinan" class="form-label">NIK Pimpinan</label>
                                <input type="text" class="form-control" id="NIK_Pimpinan" name="NIK_Pimpinan" value="<?php echo htmlspecialchars($NIK_Pimpinan); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Nama_Pimpinan" class="form-label">Nama Pimpinan</label>
                                <input type="text" class="form-control" id="Nama_Pimpinan" name="Nama_Pimpinan" value="<?php echo htmlspecialchars($Nama_Pimpinan); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="Alamat" name="Alamat" rows="3" readonly><?php echo htmlspecialchars($Alamat); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Jenis_Dana" class="form-label">Jenis Dana</label>
                                <input type="text" class="form-control" id="Jenis_Dana" name="Jenis_Dana" required>
                            </div>
                            <div class="mb-3">
                                <label for="Via" class="form-label">Via</label>
                                <input type="text" class="form-control" id="Via" name="Via" required>
                            </div>
                            <div class="mb-3">
                                <label for="Jumlah_Dana" class="form-label">Jumlah Dana</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp.</span>
                                    <input type="text" class="form-control" id="Jumlah_Dana" name="Jumlah_Dana" required oninput="formatRupiah(this)">
                                </div>
                            <div class="mb-3">
                                <label for="Keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="Keterangan" name="Keterangan" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Pencairan</button>
                            <a href="daftarpenerima.php" class="btn btn-secondary">Cancel</a>
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
