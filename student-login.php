<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $ogrenci_no = $_POST['ogrenci_no'];
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT * FROM ogrenciler WHERE ogrenci_no = :ogrenci_no AND sifre = :sifre");
    $stmt->bindParam(':ogrenci_no', $ogrenci_no);
    $stmt->bindParam(':sifre', $sifre);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['ogrenci_id'] = $row['id'];
        header("Location: ogrenci_panel.php");
    } else {
        echo "Hatalı giriş!";
    }
}
?>

<!-- HTML form -->
