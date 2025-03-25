<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varmi_iddia";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

if (isset($_GET['q'])) {
    $aranan = "%" . $_GET['q'] . "%";
    $sql = "SELECT kullanici_adi, profil_fotografi FROM kullanicilar WHERE kullanici_adi LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $aranan);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div onclick="kullaniciSec(\'' . $row['kullanici_adi'] . '\', \'' . $row['profil_fotografi'] . '\')">';
        echo '<img src="uploads/' . $row['profil_fotografi'] . '" width="40" height="40" style="border-radius:50%;"> ';
        echo $row['kullanici_adi'] . '</div>';
    }
    $stmt->close();
}

$conn->close();
?>
