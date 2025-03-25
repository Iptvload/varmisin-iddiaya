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

// Kullanıcı bilgilerini çekme
$kullanici_adi = $_SESSION['kullanici_adi'];
$sql_kullanici = "SELECT bakiye FROM kullanicilar WHERE kullanici_adi = '$kullanici_adi'";
$result_kullanici = $conn->query($sql_kullanici);

if ($result_kullanici->num_rows > 0) {
    $row_kullanici = $result_kullanici->fetch_assoc();
    $bakiye = $row_kullanici['bakiye'];
} else {
    $bakiye = 0; // Kullanıcı bulunamazsa varsayılan bakiye
}

// Kullanıcı bildirimlerini çekme
$sql_bildirim = "SELECT * FROM bildirimler WHERE rakip_kullanici_adi = '$kullanici_adi'";
$result_bildirim = $conn->query($sql_bildirim);

if ($result_bildirim === false) {
    // Sorgu başarısız oldu, hata mesajını görüntüle
    echo "SQL sorgusu hatası: " . $conn->error;
    $bildirimler = []; // Boş bir dizi oluştur, hatayı engellemek için
} else {
    $bildirimler = [];
    if ($result_bildirim->num_rows > 0) {
        while ($row_bildirim = $result_bildirim->fetch_assoc()) {
            $bildirimler[] = $row_bildirim;
        }
    }
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya? - Bildirimler</title>
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

        .match-container {
            width: 35%;
            margin-top: 20px;
            text-align: center;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .vs-image {
            width: 50px;
            height: 50px;
            transform: scale(1.3);
        }

        .search-container {
            width: 30%;
            margin-top: 20px;
        }

        .search-container input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container button {
            width: 100%;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #555;
        }

        .bet-title {
            margin-top: 20px;
            text-align: center;
        }

           select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%; /* Liste genişliği %30 olarak ayarlandı */
            background-color: white;
            color: #333;
            margin-top: 10px;
        }

        .bet-submit {
            margin-top: 20px;
            text-align: center;
        }

        .bet-submit button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .bet-submit button:hover {
            background-color: #555;
        }

        .insufficient-balance {
            color: red;
            margin-top: 10px;
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
    <div class="bildirim-container">
        <h2>Bildirimler</h2>
        <?php if (empty($bildirimler)) { ?>
            <p>Henüz bildiriminiz bulunmamaktadır.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Gönderen</th>
                        <th>Tutar</th>
                        <th>Onay</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bildirimler as $bildirim) { ?>
                        <tr>
                            <td><?php echo $bildirim['kullanici_adi']; ?></td>
                            <td><?php echo $bildirim['tutar']; ?> TL</td>
                            <td><?php echo $bildirim['onay'] == 0 ? 'Bekliyor' : 'Onaylandı'; ?></td>
                            <td>
                                <?php if ($bildirim['onay'] == 1) { ?>
                                    <form method="post" action="onay_islem.php">
                                        <input type="hidden" name="bildirim_id" value="<?php echo $bildirim['id']; ?>">
                                        <button type="submit" name="onayla">Onayla</button>
                                        <button type="submit" name="reddet">Reddet</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>
</html>