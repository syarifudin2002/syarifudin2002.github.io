<?php
session_start();
date_default_timezone_set("Asia/Bangkok");

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baznas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil tahun saat ini
$current_year = date('Y');

// Daftar bulan untuk tahun saat ini
$all_months = [];
for ($m = 1; $m <= 12; $m++) {
  $all_months[] = $current_year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
}

// Ambil data per bulan untuk setiap jenis penerima
$data_per_bulan = [];
$jenis_penerima = ["muallaf", "mahasiswa_luar_negeri", "penelitian", "sabilillah", "tpq"];

foreach ($jenis_penerima as $jenis) {
  $query = "
        SELECT DATE_FORMAT(Submit, '%Y-%m') AS bulan, COUNT(*) AS jumlah
        FROM $jenis
        GROUP BY DATE_FORMAT(Submit, '%Y-%m')
        ORDER BY bulan
    ";
  $result = $conn->query($query);

  // Inisialisasi semua bulan dengan 0
  $data_per_bulan[$jenis] = array_fill_keys($all_months, 0);

  while ($row = $result->fetch_assoc()) {
    $data_per_bulan[$jenis][$row['bulan']] = $row['jumlah'];
  }
}

// Ambil data Kabupaten dari semua tabel
$kabupaten_query = "
    SELECT Kabupaten, COUNT(*) AS jumlah 
    FROM (
        SELECT Kabupaten FROM muallaf
        UNION ALL
        SELECT Kabupaten FROM mahasiswa_luar_negeri
        UNION ALL
        SELECT Kabupaten FROM penelitian
        UNION ALL
        SELECT Kabupaten FROM sabilillah
        UNION ALL
        SELECT Kabupaten FROM tpq
    ) AS combined
    GROUP BY Kabupaten
";
$kabupaten_result = $conn->query($kabupaten_query);

$kabupaten_data = [];
while ($row = $kabupaten_result->fetch_assoc()) {
  $kabupaten_data[] = [
    'kabupaten' => $row['Kabupaten'],
    'jumlah' => $row['jumlah']
  ];
}

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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .navbar-brand {
      letter-spacing: 2px;
    }

    .navbar-brand span {
      font-size: 12px;
      margin-top: 5px;
      display: flex;
      line-height: 1px;
    }

    .welcome-message {
      opacity: 0;
      transition: opacity 1s ease-in-out;
      text-align: center;
    }

    .welcome-message.show {
      opacity: 1;
    }

    .chart-container {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 100px
    }

    .chart-container>div {
      flex: 1;
    }

    .chart-container canvas {
      width: 100% !important;
      height: 400px !important;
    }

    .blue-header {
      background-color: #007bff;
      color: white;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-light navbar-custom">
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
              <li><a class="dropdown-item" href="kelompok.php">Kelompok</a></li>
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
  <div class="container mt-5">
    <h1 class="welcome-message">PORTAL BAZNAS NTB</h1>
    <div class="chart-container">
      <div>
        <h2>Grafik Data Penerima per Bulan</h2>
        <canvas id="barChart"></canvas>

      </div>
      <div>
        <h2>Grafik Distribusi Kabupaten</h2>
        <canvas id="pieChart" class="pie-chart-container"></canvas>
      </div>
    </div>
    <div>

      <!-- Tabel keterangan jumlah penerima -->
      <h3 class="mt-5">Tabel Keterangan Jumlah Penerima</h3>
      <table class="table table-bordered mt-4" style="text-align: center;">
        <thead class="blue-header">
          <tr>
            <th>Bulan</th>
            <?php foreach ($jenis_penerima as $jenis) : ?>
              <th><?php echo ucfirst($jenis); ?></th>
            <?php endforeach; ?>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($all_months as $month) {
            $total_per_month = 0;
            echo "<tr>";
            echo "<td>" . date("F", strtotime($month . "-01")) . "</td>";
            foreach ($jenis_penerima as $jenis) {
              $jumlah = $data_per_bulan[$jenis][$month];
              $total_per_month += $jumlah;
              echo "<td>$jumlah</td>";
            }
            echo "<td>$total_per_month</td>";
            echo "</tr>";
          }
          ?>
          <tr>
            <td><strong>Total</strong></td>
            <?php
            foreach ($jenis_penerima as $jenis) {
              $total_per_jenis = array_sum($data_per_bulan[$jenis]);
              echo "<td><strong>$total_per_jenis</strong></td>";
            }
            $grand_total = array_sum(array_map('array_sum', $data_per_bulan));
            echo "<td><strong>$grand_total</strong></td>";
            ?>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', (event) => {
      document.querySelector('.welcome-message').classList.add('show');
    });

    // Data dari PHP
    const dataPerBulan = <?php echo json_encode($data_per_bulan); ?>;
    const kabupatenData = <?php echo json_encode($kabupaten_data); ?>;

    // Daftar bulan Januari sampai Desember
    const labels = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Warna yang sama untuk kedua grafik
    const colors = [
      'rgba(255, 99, 132, 10)', // Red
      '#FFFF33', // Yellow
      '#33FF57', // Green
      '#3357FF', // Blue
      'rgba(201, 203, 207, 10)' // Gray
    ];

    // Warna untuk border grafik
    const borderColors = [
      'rgba(255, 99, 132, 10)', // Red
      'rgba(255, 206, 86, 10)', // Yellow
      'rgba(75, 192, 192, 10)', // Green
      'rgba(54, 162, 235, 10)', // Blue
      'rgba(201, 203, 207, 10)' // Gray
    ];

    // Data format for each jenis penerima per month
    const barDatasets = Object.keys(dataPerBulan).map((jenis, index) => {
      return {
        label: jenis.charAt(0).toUpperCase() + jenis.slice(1),
        data: labels.map((_, i) => dataPerBulan[jenis][`${new Date().getFullYear()}-${String(i+1).padStart(2, '0')}`] || 0),
        backgroundColor: colors[index % colors.length],
        borderColor: borderColors[index % borderColors.length],
        borderWidth: 1
      };
    });

    // Bar Chart Configuration
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: barDatasets
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Pie Chart Configuration
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: kabupatenData.map(item => item.kabupaten),
        datasets: [{
          data: kabupatenData.map(item => item.jumlah),
          backgroundColor: colors,
          borderColor: borderColors,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.label + ': ' + tooltipItem.raw;
              }
            }
          }
        }
      }
    });
  </script>

</body>

</html>