<?php
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

// Adres çubuğundan id parametresini al
$id = $_GET['id'];

// id parametresinin varlığını kontrol et
if (isset($id)) {
    // SQL sorgusu oluştur
    $sql = "SELECT kullanici_adi, bakiye FROM kullanicilar WHERE id = $id";

    // Sorguyu çalıştır
    $result = $conn->query($sql);

    // Sonuçları kontrol et
    if ($result->num_rows > 0) {
        // Kullanıcı bulundu, bilgileri al
        $row = $result->fetch_assoc();
        $kullanici_adi = $row['kullanici_adi'];
        $bakiye = $row['bakiye'];
    } else {
        // Kullanıcı bulunamadı, hata mesajı göster
        $kullanici_adi = "Kullanıcı Bulunamadı";
        $bakiye = "0";
    }
} else {
    // id parametresi yok, hata mesajı göster
    $kullanici_adi = "Geçersiz ID";
    $bakiye = "0";
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya?</title>
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

        .loading-animation {
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
    <div class="user-info">
        <div class="user-details">
            <h2>Arkadaşınız İddiayı onaylaması Bekleniyor</h2>
            <div class="loading-animation"></div>
        </div>
    </div>
</body>
</html>