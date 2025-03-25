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

// Kullanıcı bilgilerini çekme (bakiye dahil)
$kullanici_adi = $_SESSION['kullanici_adi'];
$sql = "SELECT *, bakiye FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profil_fotografi = $row['profil_fotografi'];
    $id = $row['id'];
    $isim = $row['isim'];
    $soyisim = $row['soyisim'];
    $dogum_tarihi = $row['dogum_tarihi'];
    $email = $row['email'];
    $papara_numarasi = $row['papara_numarasi'];
    $bakiye = $row['bakiye']; // Bakiye bilgisini çek
} else {
    // Kullanıcı bulunamazsa varsayılan değerler
    $profil_fotografi = "varsayilan.jpg";
    $id = "Bulunamadı";
    $isim = "Bulunamadı";
    $soyisim = "Bulunamadı";
    $dogum_tarihi = "Bulunamadı";
    $email = "Bulunamadı";
    $papara_numarasi = "Bulunamadı";
    $bakiye = "0"; // Varsayılan bakiye
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya? - Profil</title>
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

        .profile-container {
            width: 35%; /* Tablo genişliğini %35 olarak ayarla */
            margin-top: 20px;
            text-align: center;
            padding: 0 10px; /* Soldan ve sağdan padding ekle */
        }

        .profile-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .profile-container table {
            width: 100%; /* Tablo genişliğini profile-container'a göre ayarla */
            border-collapse: collapse;
            margin-top: 20px;
        }

        .profile-container th, .profile-container td {
            border: 1px solid white;
            padding: 10px;
            text-align: left;
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
    <div class="profile-container">
        <img src="<?php echo $profil_fotografi; ?>" alt="Profil Fotoğrafı">
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo $id; ?></td>
            </tr>
            <tr>
                <th>Kullanıcı Adı</th>
                <td><?php echo $kullanici_adi; ?></td>
            </tr>
            <tr>
                <th>İsim</th>
                <td><?php echo $isim; ?></td>
            </tr>
            <tr>
                <th>Soyisim</th>
                <td><?php echo $soyisim; ?></td>
            </tr>
            <tr>
                <th>Doğum Tarihi</th>
                <td><?php echo $dogum_tarihi; ?></td>
            </tr>
            <tr>
                <th>E-posta</th>
                <td><?php echo $email; ?></td>
            </tr>
            <tr>
                <th>Papara No</th>
                <td><?php echo $papara_numarasi; ?></td>
            </tr>
            <tr>
                <th>Bakiye</th>
                <td><?php echo $bakiye; ?> TL</td>
            </tr>
        </table>
    </div>
</body>
</html>