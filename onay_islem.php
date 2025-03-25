<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullanici_adi'])) {
    header('Location: giris.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varmi_iddia";

// Veritabanına bağlan
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Bildirim ID'sini al
$bildirim_id = isset($_POST['bildirim_id']) ? $_POST['bildirim_id'] : null;

if ($bildirim_id) {
    if (isset($_POST['onayla'])) {
        // Bildirimi onayla
        $sql_onayla = "UPDATE bildirimler SET onay = 0 WHERE id = ?";
        $stmt_onayla = $conn->prepare($sql_onayla);
        $stmt_onayla->bind_param("i", $bildirim_id);

        if ($stmt_onayla->execute()) {
            echo "Bildirim onaylandı.";
        } else {
            echo "Bildirim onaylanırken hata oluştu: " . $stmt_onayla->error;
        }
    } elseif (isset($_POST['reddet'])) {
        // Bildirimi reddet
        $sql_reddet = "DELETE FROM bildirimler WHERE id = ?";
        $stmt_reddet = $conn->prepare($sql_reddet);
        $stmt_reddet->bind_param("i", $bildirim_id);

        if ($stmt_reddet->execute()) {
            echo "Bildirim reddedildi.";
        } else {
            echo "Bildirim reddedilirken hata oluştu: " . $stmt_reddet->error;
        }
    }
} else {
    echo "Bildirim ID'si bulunamadı.";
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bildirim İşlemi</title>
    <style>
        body {
            background: linear-gradient(to right, blue, purple);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: white;
            font-family: sans-serif;
        }

        .message {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        a {
            color: white;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="message">
        <?php
        if (isset($_POST['onayla'])) {
            echo "Bildirim onaylandı.";
        } elseif (isset($_POST['reddet'])) {
            echo "Bildirim reddedildi.";
        } else {
            echo "Bildirim ID'si bulunamadı.";
        }
        ?>
        <br>
        <a href="bildirim.php">Bildirimler Sayfasına Geri Dön</a>
    </div>
</body>
</html>