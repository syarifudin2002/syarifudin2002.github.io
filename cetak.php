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
$nomor_ktp = isset($_GET['Nomor_KTP']) ? $_GET['Nomor_KTP'] : null;

$data = null; // Set default value

if ($nomor_ktp) {
    // Query untuk mengambil data pencairan berdasarkan Nomor KTP
    $query_get_pencairan = "SELECT * FROM pencairan WHERE Nomor_KTP = ?";
    $stmt = $conn->prepare($query_get_pencairan);
    $stmt->bind_param("s", $nomor_ktp);
    $stmt->execute();
    $result_get_pencairan = $stmt->get_result();

    if ($result_get_pencairan->num_rows > 0) {
        $data = $result_get_pencairan->fetch_assoc();
    }
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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kwitansi</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0mm;
            /* Atur margin menjadi 0mm untuk menggunakan seluruh area kertas */
        }

        body {
            width: 210mm;
            /* Lebar A5 dalam lanskap */
            height: 148mm;
            /* Tinggi A5 dalam lanskap */
            margin: 4mm;
            /* Memberikan jarak antara border kwitansi dan pinggiran kertas */
            padding: 0;
            border: 1px solid #000;
            /* Menambahkan garis persegi empat di luar kwitansi */
        }

        .receipt {
            width: 100%;
            height: 100%;
            padding: 5mm;
            /* Sesuaikan padding jika perlu */
            box-sizing: border-box;
        }

        .page {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            width: 100%;
        }


        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
            /* Mengatur margin untuk header */
            padding: 0;
            /* Mengatur padding untuk header */
            height: 4mm;
            /* Tinggi header yang sesuai */
            margin-bottom: 1.1cm;
            margin-top: 40px;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-left img {
            width: 150px;
            height: auto;
            margin-right: 10px;
        }

        .header-center {
            flex-grow: 1;
            text-align: center;
            font-size: 29px;
            font-weight: bold;
            color: blue;

        }

        .header-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .header-right .details {
            font-size: 12px;
            color: blue;
        }

        .header-right .details .field {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 5px;
        }

        .header-right .details .field label {
            font-weight: bold;
            margin-right: 10px;
        }

        .header-right .details .field .box {
            width: 200px;
            height: 20px;
            border: 1px solid blue;
            text-align: center;
            padding-top: 2px;

        }

        .content .label {
            width: 30%;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .separator {
            border-top: 1px solid blue;
            margin: 15px 0;
            margin-top: 10px;
        }

        .content {
            font-size: 12px;
            color: blue;
            line-height: 2.0;
        }

        .content table {
            width: 97%;
            border-collapse: collapse;
            margin-bottom: 0;

        }

        .content td {
            padding: 5px 0;
            vertical-align: top;
        }

        .content .line {
            border-bottom: 1px solid blue;
            width: 100%;
            display: flex;
            margin-bottom: 2px;
            color: #000;
            font-family: 'Courier New', Courier, monospace;
        }

        .content .value .line {
            height: 20px;
            text-align: justify;

        }

        .content .box {
            height: auto;
            background-color: #d3d3d3;
            border: 1px solid blue;
            display: inline-block;
            width: 100%;
            margin-bottom: 5px;
            padding: 10px;
            text-align: justify;
            flex-direction: column;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
        }

        .content .short-box {
            height: auto;
            border: 1px solid blue;
            display: inline-block;
            width: 40%;
            margin-left: -4.5cm;
            background-color: #d3d3d3;
            font-family: 'Courier New', Courier, monospace;
        }

        .valuerp {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #000;
        }

        .content .date {
            text-align: right;
            margin-left: auto;
        }

        .kwitansi-footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: blue;
        }

        .kwitansi-footer .left,
        .kwitansi-footer .center,
        .kwitansi-footer .right {
            line-height: 1.5;
            text-align: center;
        }

        .kwitansi-footer .left {
            width: 33%;
            font-weight: bold;
            text-align: justify;
            margin-top: 20px;
        }

        .kwitansi-footer .ntb {
            font-size: 12px;
        }

        .kwitansi-footer .center {
            width: 33%;
            margin-top: 45px;
            text-align: center;
        }

        .kwitansi-footer .right {
            width: 33%;
            margin-top: 45px;
        }

        .kwitansi-footer p {
            margin: 0;
            padding: 0;
        }

        .kwitansi-footer .right p {
            margin-bottom: 1px;
        }

        .separator-footer {
            border-top: 1px solid blue;
            width: 90%;
            margin: 15px auto;
        }

        .separator-footerurl {
            border-top: 1px solid blue;
            width: 100%;
            margin: 5px auto;
            font-size: 8px;
            padding: 2px 0;
            text-align: center;
        }

        @media print {
            .content .box {
                background-color: #d3d3d3 !important;
                -webkit-print-color-adjust: exact;
                /* Untuk browser berbasis WebKit */
                print-color-adjust: exact;
                /* Properti yang benar untuk browser lainnya */
            }

            .content .short-box {
                background-color: #d3d3d3 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .receipt {

                box-sizing: border-box;
                margin-top: 2mm;
                /* Margin untuk memastikan garis di atas tidak terpotong */
                margin-bottom: 2mm;
                /* Margin untuk footer */
            }

            body {
                width: auto;
                height: auto;
            }

        }
    </style>
</head>

<body>

    <table class="receipt">
        <tr>
            <td>
                <div class="header">
                    <div class="header-left">
                        <img src="gambar/baznasntb.png" alt="Logo">
                    </div>
                    <div class="header-center">
                        <u>KUITANSI</u>
                    </div>
                    <div class="header-right">
                        <div class="details">
                            <div class="field">
                                <label for="nomor">No :</label>
                                <div class="box"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="content">
                    <table>
                        <tr>
                            <td class="label">
                                <div>Dibayarkan Kepada</div>
                                <i>
                                    <div style="font-size: 10px;">Paid to</div>
                                </i>
                            </td>
                            <td class="value">
                                <div class="line"><?php echo isset($data['Nama']) ? htmlspecialchars($data['Nama']) : 'N/A'; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <div>Jumlah</div>
                                <i>
                                    <div style="font-size: 10px;">Amount</div>
                                </i>
                            </td>
                            <td class="value">
                                <div class="box" style="display: flex; align-items: left; justify-content: center; padding: 5px;"><?php echo isset($data['Jumlah_Dana']) ? ucwords(terbilang($data['Jumlah_Dana'])) . ' rupiah' : 'N/A'; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <div>Untuk Pembayaran</div>
                                <i>
                                    <div style="font-size: 10px;">Payment for</div>
                                </i>
                            </td>
                            <td class="value">
                                <div class="line spaced-cell"><?php echo isset($data['Keterangan']) ? htmlspecialchars($data['Keterangan']) : 'N/A'; ?></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Rp</td>
                            <td class="valuerp">
                                <div class="short-box"><?php echo isset($data['Jumlah_Dana']) ? number_format($data['Jumlah_Dana'], 2, ',', '.') : 'N/A'; ?></div>
                                <u>
                                    <div class="date">Mataram <?php echo date("d-m-Y"); ?></div>
                                </u>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="kwitansi-footer">
                    <div class="left">
                        <b>
                            BADAN AMIL ZAKAT NASIONAL <br>
                            Provinsi Nusa Tenggara Barat
                        </b>
                        <div class="ntb">
                            Jl. Catur Warga No.37 - Kel. Mataram Barat <br>
                            Telp. 083333797063 Fax. 03707504733
                        </div>
                    </div>
                    <div class="center">
                        <p><?php echo isset($data['Nama']) ? htmlspecialchars($data['Nama']) : 'N/A'; ?></p>
                        <p class="separator-footer">Penerima Program</p>
                    </div>
                    <div class="right">
                        <p>Humaidi</p>
                        <p class="separator-footer">Bendahara Pengeluaran</p>
                    </div>
                </div>
                <div class="separator-footerurl">

                </div>
                </div>
                </div>
            </td>
        </tr>
    </table>
    <script>
        const keteranganElements = document.querySelectorAll('.spaced-cell');
        keteranganElements.forEach(keteranganElement => {
            const lineHeight = parseFloat(getComputedStyle(keteranganElement).lineHeight);
            const maxLines = 3;
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
        });

        function printPage(nomorKTP) {
            var printWindow = window.open('cetak.php?Nomor_KTP=' + encodeURIComponent(nomorKTP), '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>



</body>

</html>