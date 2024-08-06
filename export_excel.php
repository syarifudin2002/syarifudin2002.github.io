<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pencairan
$query = "SELECT Nomor_Registrasi, Jenis_Pembagian, Nama, Nomor_KTP, Alamat, Jenis_Dana, Via, Jumlah_Dana, Keterangan FROM pencairan ORDER BY Submit DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Menetapkan header untuk mendownload file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="data_pencairan.xls"');

    // Membuat tabel HTML
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Nomor Registrasi</th>';
    echo '<th>Jenis Pembagian</th>';
    echo '<th>Nama</th>';
    echo '<th>Nomor KTP</th>';
    echo '<th>Alamat</th>';
    echo '<th>Jenis Dana</th>';
    echo '<th>Via</th>';
    echo '<th>Jumlah Dana</th>';
    echo '<th>Keterangan</th>';
    echo '</tr>';

    // Mengoutput setiap baris data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Nomor_Registrasi']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Jenis_Pembagian']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Nama']) . '</td>';
        echo '<td>\'' . htmlspecialchars($row['Nomor_KTP']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Alamat']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Jenis_Dana']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Via']) . '</td>';
        echo '<td>' . htmlspecialchars(number_format($row['Jumlah_Dana'], 2, ',', '.')) . '</td>';
        echo '<td>' . htmlspecialchars($row['Keterangan']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo "Tidak ada data untuk diekspor.";
}
exit;
