<?php
// Not bilgilerini çekme
$stmt = $conn->prepare("SELECT o.ad, o.soyad, d.ders_ad, n.not_degeri1, n.not_değeri2   
                      FROM notlar n 
                      JOIN dersler d ON n.ders_id = d.id 
                      JOIN ogrenciler o ON n.ogrenci_id = o.id 
                      WHERE n.ogretmen_id = :ogretmen_id");;
$stmt->bindParam(':ogrenci_id', $ogrenci_id);
$stmt->execute();
$notlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Not Paneli</title>
</head>
<body>
    <div class="panel-container">
        <div class="not-panel" onclick="togglePanel('not-panel-content')">
            <h2>Not Bilgileri</h2>
            <table>
                <tr>
                    <th>Ders Adı</th>
                    <th>Not Değeri</th>
                </tr>
                <?php foreach ($notlar as $not): ?>
                    <tr>
                        <td><?php echo $not['ders_ad']; ?></td>
                        <td><?php echo $not['not_degeri']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        function togglePanel(panelId) {
            var panelContent = document.getElementById(panelId);
            panelContent.style.display = (panelContent.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</body>
</html>
