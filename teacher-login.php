<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $ogretmen_no = $_POST['ogretmen_no'];
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT * FROM ogretmenler WHERE ogretmen_no = :ogretmen_no AND sifre = :sifre");
    $stmt->bindParam(':ogretmen_no', $ogretmen_no);
    $stmt->bindParam(':sifre', $sifre);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['ogretmen_id'] = $row['ogretmen_id'];
        header("Location: ogretmen_panel.php");
    } else {
        echo "Hatalı giriş!";
    }
}
?>

<!-- HTML form -->

