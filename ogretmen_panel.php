<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['ogretmen_id'])) {
    header("Location: teacher-login.php");
    exit();
}

$ogretmen_id = $_SESSION['ogretmen_id'];

// Öğretmen bilgilerini çekme
$stmt = $conn->prepare("SELECT * FROM ogretmenler WHERE ogretmen_id = :ogretmen_id");
$stmt->bindParam(':ogretmen_id', $ogretmen_id, PDO::PARAM_INT);
$stmt->execute();
$ogretmen = $stmt->fetch(PDO::FETCH_ASSOC);

// Ders bilgilerini çekme
$stmt = $conn->prepare("SELECT * FROM dersler WHERE ogretmen_bilgileri LIKE :ogretmen_bilgileri");
$ogretmen_bilgileri = '%' . $ogretmen['ad'] . ' ' . $ogretmen['soyad'] . '%';
$stmt->bindParam(':ogretmen_bilgileri', $ogretmen_bilgileri, PDO::PARAM_STR);
$stmt->execute();
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Not ve öğrenci bilgilerini çekme
$stmt = $conn->prepare("SELECT o.ad as ogrenci_ad, o.soyad as ogrenci_soyad, d.ders_ad, n.not_degeri1, n.not_değeri2   
                      FROM notlar n 
                      JOIN dersler d ON n.ders_id = d.id 
                      JOIN ogrenciler o ON n.ogrenci_id = o.id 
                      WHERE n.ogretmen_id = :ogretmen_id");
$stmt->bindParam(':ogretmen_id', $ogretmen_id, PDO::PARAM_INT);
$stmt->execute();
$notlar = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Öğretmen Paneli</title>
    <style>
        
        body {
            background-image: url('comu_logo.png');
            background-repeat: no-repeat;
            background-position: center top;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .panel-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .panel-table {
            display: table;
            width: 100%;
            margin: auto;
            border-spacing: 0;
        }

        .panel-row {
            display: table-row;
        }

        .panel-cell {
            display: table-cell;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .panel-cell:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
        }

        .panel-content {
            display: none;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 8px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="panel-table">
        <div class="panel-row">
            <div class="panel-cell" onclick="showPanel('ogretmen-panel')">Öğretmen Bilgileri</div>
            <div class="panel-cell" onclick="showPanel('ders-panel')">Verdiği Dersler</div>
            <div class="panel-cell" onclick="showPanel('not-panel')">Öğrenci Notları</div>
            <div class="panel-cell" onclick="logout()">Çıkış Yap</div>
        </div>
    </div>

    <div id="ogretmen-panel" class="panel-content">
        <h2>Öğretmen Bilgileri</h2>
        <p>Ad: <?php echo $ogretmen['ad']; ?></p>
        <p>Soyad: <?php echo $ogretmen['soyad']; ?></p>
        <p>Öğretmen No: <?php echo $ogretmen['ogretmen_no']; ?></p>
    </div>

    <div id="ders-panel" class="panel-content">
        <h2>Verdiği Dersler</h2>
        <ul>
            <?php foreach ($dersler as $ders): ?>
                <p>Ders => <?php echo $ders['ders_ad']; ?></p>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="not-panel" class="panel-content">
        <h2>Öğrenci Notları</h2>
        <table>
            <tr>
                <th>Öğrenci Adı</th>
                <th>Öğrenci Soyadı</th>
                <th>Ders Adı</th>
                <th>Vize</th>
                <th>Final</th>
            </tr>
            <?php foreach ($notlar as $not): ?>
                <tr>
                    <td><?php echo $not['ogrenci_ad']; ?></td>
                    <td><?php echo $not['ogrenci_soyad']; ?></td>
                    <td><?php echo $not['ders_ad']; ?></td>
                    <td><?php echo $not['not_degeri1']; ?></td>
                    <td><?php echo $not['not_değeri2']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function showPanel(panelId) {
            hideAllPanels();
            document.getElementById(panelId).style.display = 'block';
        }

        function hideAllPanels() {
            var panels = document.getElementsByClassName('panel-content');
            for (var i = 0; i < panels.length; i++) {
                panels[i].style.display = 'none';
            }
        }

        function logout() {
            window.location.href = 'giriş.php';
        }
    </script>
</body>

</html>
