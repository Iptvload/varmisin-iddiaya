<?php
session_start();

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

if (isset($_POST['kullanici_adi']) && isset($_POST['sifre'])) {
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // SQL sorgusu oluştur
    $sql = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi' AND sifre = '$sifre'";

    // Sorguyu çalıştır
    $result = $conn->query($sql);

    // Sonuçları kontrol et
    if ($result->num_rows > 0) {
        // Kullanıcı bulundu, oturumu başlat
        $row = $result->fetch_assoc();
        $_SESSION['kullanici_adi'] = $row['kullanici_adi'];
        header('Location: anasayfa.php?id=' . $row['id']); // Anasayfaya yönlendir
        exit;
    } else {
        // Kullanıcı bulunamadı, hata mesajı göster
        $hata = "Kullanıcı adı veya şifre hatalı.";
    }
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya? - Giriş</title>
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

        .login-container {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .login-container input[type="submit"]:hover {
            background-color: #555;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Giriş Yap</h2>
        <?php if (isset($hata)) { ?>
            <p class="error-message"><?php echo $hata; ?></p>
        <?php } ?>
        <form method="post">
            <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <input type="submit" value="Giriş">
        </form>
        <div class="register-link">
            <a href="kayit.php">Kaydınız Yoksa Kayıt Olun</a>
        </div>
    </div>
</body>
</html>