<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan Nomor KTP dari parameter URL
if (isset($_GET['Nomor_KTP'])) {
    $nomor_ktp = $_GET['Nomor_KTP'];

    // Query untuk mengambil data pencairan berdasarkan Nomor KTP
    $query_get_pencairan = "SELECT * FROM pencairan WHERE Nomor_KTP = ?";
    $stmt = $conn->prepare($query_get_pencairan);
    $stmt->bind_param("s", $nomor_ktp);
    $stmt->execute();
    $result_get_pencairan = $stmt->get_result();

    if ($result_get_pencairan->num_rows > 0) {
        $data = $result_get_pencairan->fetch_assoc();
    } else {
        $data = null; // Set $data to null if no data is found
    }
} else {
    $data = null; // Set $data to null if Nomor_KTP is not found in URL
}

$conn->close();

// Fungsi untuk mengonversi angka menjadi kata-kata
function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }

    return $terbilang;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi</title>
    <style>
        @page {
            size: 210mm 148mm;
            /* Set page size to half of A4 */
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
        }

        .kwitansi {
            width: 210mm;
            /* Set width to half of A4 size */
            height: 148mm;
            /* Set height to half of A4 size */
            padding: 20px;
            border: 1px solid #000;
            margin: 0 auto;
            box-sizing: border-box;
            position: relative;
            text-align: center;
            /* Center content */
        }

        .kwitansi .logo {
            position: absolute;
            left: 29px;
            /* Position logo on the left */
            top: 20px;
            width: 110px;
        }

        .kwitansi .title {
            position: absolute;
            top: 40px;
            width: 100%;
            font-size: 29px;
            font-weight: bold;
            text-align: center;
            /* Center title text */
        }

        .kwitansi .details {
            position: absolute;
            right: 20px;
            /* Position details on the right */
            top: 20px;
            text-align: right;
        }

        .kwitansi .details p {
            margin: 0;
            font-size: 14px;
        }

        .kwitansi-content {
            margin-top: 100px;
            /* Space for header */
        }

        .kwitansi-content p {
            margin: 5px 0;
            font-size: 14px;
            text-align: left;
            /* Align description text to the left */
        }

        .kwitansi-footer {
            display: flex;
            justify-content: space-between;
            /* Align items to the left and center */
            margin-top: 40px;
        }

        .kwitansi-footer .left {
            text-align: left;
            width: 50%;
            /* Adjust as needed */
            line-height: 1.5;
            font-size: 14px;
        }

        .kwitansi-footer .left .ntb {
            font-size: 10px;
        }

        .kwitansi-footer .center {
            text-align: center;
            width: 50%;
            /* Adjust as needed */
            margin-top: 18px;
        }

        .kwitansi-footer .right {
            text-align: right;
            width: 50%;
            /* Adjust as needed */
            margin-top: 15px;
        }

        .kwitansi-footer p {
            margin: 0;
            font-size: 14px;
            text-align: center;
            margin-top: 5px;
        }


        .kwitansi-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* Space between table and footer */
        }

        .kwitansi-table th,
        .kwitansi-table td {
            border: 1px solid #000;
            padding: 9px;
            /* Reduce padding */
            text-align: left;
            vertical-align: top;
        }

        .kwitansi-table td {
            width: 70%;
            /* Column width example, adjust as needed */
        }

        .kwitansi-table .comment {
            font-size: 12px;
            /* Smaller font size for comments */
            color: #777;
            /* Light grey color */
            margin-top: 2px;
            /* Small margin to separate from main text */
        }

        .kwitansi-table .colon {
            display: inline-block;
            margin-left: 10px;
            /* Space between text and colon */
        }

        .kwitansi-table th .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kwitansi-table .spaced-cell {
            line-height: 1.5;
            /* Add space between lines */

        }

        .kwitansi-table th.extra-padding {
            padding-top: 30px;
            /* Adjust padding as needed */
            background-color: #f2f2f2;
        }

        .kwitansi-table th.extra-padding,
        .kwitansi-table td.extra-padding {
            padding-top: 20px;
            /* Align text in th and td */
            background-color: #f2f2f2;
        }

        .alamat {
            margin-top: 20px;
            text-align: right;
            background-color: #f2f2f2;
        }

        .spaced-cell {
            max-height: 3.6em;
            /* Estimasi tinggi tiga baris teks */
            overflow: hidden;
            position: relative;
        }

        .spaced-cell::after {
            content: attr(data-fulltext);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: none;
        }
    </style>
</head>

<body>

    <div class="kwitansi">
        <img src="gambar/logo-baznas4.png" alt="logo BAZNAS" class="logo">
        <div class="title">Kwitansi</div>

        <div class="details">
            <tr>
                <p>No. Kwitansi: <?php echo isset($data['Nomor_Registrasi']) ? htmlspecialchars($data['Nomor_Registrasi']) : 'N/A'; ?></p>
                <p>Tanggal: <?php echo date("d-m-Y"); ?></p>
            </tr>
        </div>


        <div class="kwitansi-content">
            <table class="kwitansi-table">
                <tbody>
                    <tr>
                        <th>
                            <div class="header-content">
                                <span>Dibayarkan Kepada</span>
                                <span class="colon">:</span>
                            </div>
                            <div class="comment">Paid to</div>
                        </th>
                        <td><?php echo isset($data['Nama']) ? htmlspecialchars($data['Nama']) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>
                            <div class="header-content">
                                <span>Jumlah</span>
                                <span class="colon">:</span>
                            </div>
                            <div class="comment">Amount</div>
                        </th>
                        <td><?php echo isset($data['Jumlah_Dana']) ? ucwords(terbilang($data['Jumlah_Dana'])) . ' rupiah' : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>
                            <div class="header-content">
                                <span>Untuk Pembayaran</span>
                                <span class="colon">:</span>
                            </div>
                            <div class="comment">Payment For</div>
                        </th>
                        <td class="spaced-cell"><?php echo isset($data['Keterangan']) ? htmlspecialchars($data['Keterangan']) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th class="extra-padding">Rp.</th>
                        <td class="extra-padding">
                            <strong><?php echo isset($data['Jumlah_Dana']) ? number_format($data['Jumlah_Dana'], 2, ',', '.') : 'N/A'; ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alamat">
            <p>Mataram <?php echo date("d-m-Y"); ?></p>
        </div>

        <div class="kwitansi-footer">
            <b>
                <div class="left">
                    BADAN AMIL ZAKAT NASIONAL <br>
                    Provinsi Nusa Tenggara Barat
            </b>
            <div class="ntb">
                Jl. Catur Warga No.37 - Kel. Mataram Barat <br>
                Telp. 083333797063 Fax. 03707504733
            </div>

        </div>
        <div class="center">
            <td><?php echo isset($data['Nama']) ? htmlspecialchars($data['Nama']) : 'N/A'; ?></td>
            <p>---------------------------</p>
            <p>Penerima:</p>
        </div>
        <div class="right">
            <p style="margin-bottom: 1px;">Humaidi</p>
            <p>---------------------------</p>
            <p>Petugas</p>
        </div>

    </div>
    </div>

    <script>
        window.onload = function() {
            const keteranganElement = document.querySelector('.spaced-cell');
            const lineHeight = parseFloat(getComputedStyle(keteranganElement).lineHeight);
            const maxLines = 4;
            const maxHeight = lineHeight * maxLines;
            const text = keteranganElement.textContent.trim();
            const words = text.split(' ');
            let result = '';

            for (let i = 0; i < words.length; i++) {
                const newText = result + words[i] + ' ';
                keteranganElement.textContent = newText;

                if (keteranganElement.scrollHeight > maxHeight) {
                    keteranganElement.textContent = result.trim() + '...';
                    break;
                }

                result += words[i] + ' ';
            }
        }
    </script>

</body>

</html>