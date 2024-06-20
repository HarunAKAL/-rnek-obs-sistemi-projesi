<?php
// Ders bilgilerini çekme
$stmt = $conn->prepare("SELECT * FROM dersler ");
$stmt->execute();
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ders Paneli</title>
</head>
<body>
    <div class="panel-container">
        <div class="ders-panel" onclick="togglePanel('ders-panel-content')">
            <h2>Ders Bilgileri</h2>
            <ul>
                <?php foreach ($dersler as $ders): ?>
                    <li><?php echo $ders['ders_ad']; ?></li>
                <?php endforeach; ?>
            </ul>
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
