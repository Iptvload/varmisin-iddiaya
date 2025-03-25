<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['kullanici_adi'])) {
    header('Location: giris.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varmi_iddia";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$kullanici_adi = $_SESSION['kullanici_adi'];
$sql = "SELECT bakiye, profil_fotografi FROM kullanicilar WHERE kullanici_adi = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kullanici_adi);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$bakiye = $row['bakiye'];
$profil_fotografi = $row['profil_fotografi'];
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rakip_kullanici_adi = $_POST['rakip_kullanici_adi'];
    $tutar = $_POST['tutar'];
    
    if (!empty($rakip_kullanici_adi)) {
        $sql = "INSERT INTO bildirimler (kullanici_adi, rakip_kullanici_adi, tutar) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $kullanici_adi, $rakip_kullanici_adi, $tutar);
        if ($stmt->execute()) {
            echo "Mücadele başarıyla gönderildi!";
        } else {
            echo "Hata oluştu: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lütfen bir rakip seçin.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mücadele Oluştur</title>
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
    </style>
    <script>
        function kullaniciAra() {
            var arama = document.getElementById('rakip').value;
            if (arama.length < 3) {
                document.getElementById('sonuc').innerHTML = '';
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'kullanici_ara.php?q=' + arama, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('sonuc').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
        function kullaniciSec(kullanici, profil) {
            document.getElementById('rakip').value = kullanici;
            document.getElementById('profil_resmi').src = 'uploads/' + profil;
            document.getElementById('profil_resmi').style.display = 'block';
            document.getElementById('rakip_kullanici_adi').value = kullanici;
        }
    </script>
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
            <div>Kullanıcı Adı: <?php echo $kullanici_adi; ?></div>
            <div>Bakiye: <?php echo $bakiye; ?> TL</div>
        </div>
    </div>
    <div class="container">
        <h2>Mücadele Oluştur</h2>
        <input type="text" id="rakip" placeholder="Rakip kullanıcı adını girin">
        <button onclick="kullaniciAra()">Ara</button>
        <div id="sonuc"></div>
        <img id="profil_resmi" src="uploads/<?php echo $profil_fotografi; ?>" width="50" height="50" style="display:none;">
        <form method="POST">
            <input type="hidden" name="rakip_kullanici_adi" id="rakip_kullanici_adi">
            <select name="tutar">
                <option value="10">10 TL</option>
                <option value="20">20 TL</option>
                <option value="50">50 TL</option>
                <option value="100">100 TL</option>
            </select>
            <button type="submit">İddiaya Gir</button>
        </form>
    </div>
</body>
</html>
