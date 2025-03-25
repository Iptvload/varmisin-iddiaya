<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullanici_adi'])) {
    header('Location: giris.php'); // Giriş yapılmamışsa giriş sayfasına yönlendir
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

// Kullanıcı bilgilerini çekme
$kullanici_adi = $_SESSION['kullanici_adi'];
$sql = "SELECT bakiye FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bakiye = $row['bakiye'];
} else {
    $bakiye = "0"; // Kullanıcı bulunamazsa varsayılan bakiye
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya? - Anasayfa</title>
    <style>
        body {
            background: linear-gradient(to right, blue, purple);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            color: white;
            font-family: sans-serif;
        }

        .header {
            width: 100%;
            background: linear-gradient(to right, blue, purple);
            display: flex;
            justify-content: center;
            padding: 10px 0;
            flex-direction: column;
            align-items: center;
        }

        .header-buttons {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .header-buttons a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 5px;
            background-color: #333;
        }

        .header-buttons a:hover {
            background-color: #555;
        }

        .user-info-bar {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        .user-info-bar .username {
            text-align: left;
            margin-right: 6rem;
        }

        .user-info-bar .balance {
            text-align: right;
            margin-left: 6rem;
        }

        .content {
            width: 80%;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-buttons">
            <a href="anasayfa.php">Anasayfa</a>
            <a href="profil.php">Profil</a>
            <a href="idda.php">Mücadele</a>
            <a href="bildirim.php">Bildirimler</a>
            <a href="cikis.php">Çıkış</a>
        </div>
        <div class="user-info-bar">
            <div class="username">Kullanıcı Adı: <?php echo $kullanici_adi; ?></div>
            <div class="balance">Bakiye: <?php echo $bakiye; ?> TL</div>
        </div>
    </div>
    <div class="content">
        <h2>Hoş Geldiniz!</h2>
        <p>Bu sayfada oyunlar ve maçlar hakkında bilgiler bulabilirsiniz.</p>
        </div>
</body>
</html>