<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['ogrenci_id'])) {
    header("Location: login.php");
    exit();
}

$ogrenci_id = $_SESSION['ogrenci_id'];

// Öğrenci bilgilerini çekme
$stmt = $conn->prepare("SELECT * FROM ogrenciler WHERE id = :ogrenci_id");
$stmt->bindParam(':ogrenci_id', $ogrenci_id);
$stmt->execute();
$ogrenci = $stmt->fetch(PDO::FETCH_ASSOC);

// Ders bilgilerini çekme
$stmt = $conn->prepare("SELECT * FROM `dersler` ");
$stmt->execute();
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Not bilgilerini çekme
$stmt = $conn->prepare("SELECT d.ders_ad, n.not_degeri1, not_değeri2, ogretmen_id FROM notlar n JOIN dersler d ON n.ders_id = d.id WHERE n.ogrenci_id = :ogrenci_id");
$stmt->bindParam(':ogrenci_id', $ogrenci_id);
$stmt->execute();
$notlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Öğrenci Paneli</title>
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

        .panel-table {
            display: table;
            width: 100%;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }


    </style>
</head>
<body>
    <div class="panel-table">
        <div class="panel-row">
            <div class="panel-cell" onclick="showPanel('ogrenci-panel')">Öğrenci Bilgileri</div>
            <div class="panel-cell" onclick="showPanel('ders-panel')">Ders Bilgileri</div>
            <div class="panel-cell" onclick="showPanel('not-panel')">Not Bilgileri</div>
            <div class="panel-cell" onclick="logout()">Çıkış Yap</div>
        </div>
    </div>

    <div id="ogrenci-panel" class="panel-content">
        <h2>Öğrenci Bilgileri</h2>
        <p>Ad: <?php echo $ogrenci['ad']; ?></p>
        <p>Soyad: <?php echo $ogrenci['soyad']; ?></p>
        <p>Öğrenci No: <?php echo $ogrenci['ogrenci_no']; ?></p>
    </div>

    <div id="ders-panel" class="panel-content">
        <h2>Alınan Dersler</h2>
        <ul>
            <?php foreach ($dersler as $ders): ?>
                <li><p>Ders Adı => <?php echo $ders['ders_ad']; ?></p></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="not-panel" class="panel-content">
        <h2>Not Bilgileri</h2>
        <table>
            <tr>
                <th>Ders Adı</th>
                <th>Vize</th>
                <th>Final</th>
                <th>Öğretmen</th>
            </tr>
            <?php foreach ($notlar as $not): ?>
                <tr>
                    <td><?php echo $not['ders_ad']; ?></td>
                    <td><?php echo $not['not_degeri1']; ?></td>
                    <td><?php echo $not['not_değeri2']; ?></td>
                    <td><?php echo $not['ogretmen_id']; ?></td>
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
