
<?php
// Veritabanı bağlantı bilgileri
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

// Form gönderilmişse veritabanı işlemlerini gerçekleştir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $kullanici_adi = $_POST['kullanici_adi'];
    $isim = $_POST['isim'];
    $soyisim = $_POST['soyisim'];
    $dogum_tarihi = $_POST['dogum_tarihi'];
    $email = $_POST['email'];
    $papara_numarasi = $_POST['papara_numarasi'];
    $sifre = $_POST['sifre'];
    $profil_fotografi = "https://r.resimlink.com/Z8V9s.png"; // Varsayılan profil fotoğrafı URL'si

    // Yaş kontrolü
    $dogum_tarihi_obj = new DateTime($dogum_tarihi);
    $bugun = new DateTime();
    $yas = $bugun->diff($dogum_tarihi_obj)->y;

    // Kullanıcı adı ve e-posta kontrolü
    $kullanici_adi_kontrol_sql = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
    $kullanici_adi_kontrol_stmt = $conn->prepare($kullanici_adi_kontrol_sql);
    $kullanici_adi_kontrol_stmt->bind_param("s", $kullanici_adi);
    $kullanici_adi_kontrol_stmt->execute();
    $kullanici_adi_kontrol_sonuc = $kullanici_adi_kontrol_stmt->get_result();

    $email_kontrol_sql = "SELECT * FROM kullanicilar WHERE email = ?";
    $email_kontrol_stmt = $conn->prepare($email_kontrol_sql);
    $email_kontrol_stmt->bind_param("s", $email);
    $email_kontrol_stmt->execute();
    $email_kontrol_sonuc = $email_kontrol_stmt->get_result();

    if ($kullanici_adi_kontrol_sonuc->num_rows > 0 || $email_kontrol_sonuc->num_rows > 0 || $yas < 18) {
        // Hata mesajı göster ve kayit.php'ye yönlendir
        echo "<script>alert('AYNI KULLANICI ADI YADA E-POSTA İLE KULLANICI MEVCUT VEYA 18 YAŞINDAN BÜYÜK DEĞİLSİNİZ. LÜTFEN TEKRAR DENEYİN'); window.location.href = 'kayit.php';</script>";
    } else {
        // Kullanıcı adı ve e-posta uygunsa ve yaş 18'den büyükse kayıt işlemini gerçekleştir
        $sql = "INSERT INTO kullanicilar (kullanici_adi, isim, soyisim, dogum_tarihi, email, papara_numarasi, sifre, profil_fotografi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $kullanici_adi, $isim, $soyisim, $dogum_tarihi, $email, $papara_numarasi, $sifre, $profil_fotografi);

        if ($stmt->execute()) {
            // Kayıt başarılı, giris.php'ye yönlendir
            header("Location: giris.php");
            exit; // Yönlendirmeden sonra betiği sonlandır
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    }

    // Bağlantıları kapat
    $kullanici_adi_kontrol_stmt->close();
    $email_kontrol_stmt->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Varmısın İddaya? - Kayıt Ol</title>
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
            width: 90%;
            text-align: center;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .buttons {
            margin-top: 10px;
            margin-left: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .register-form {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            text-align: center;
            width: 90%;
            max-width: 350px;
        }

        .register-form input[type="text"],
        .register-form input[type="password"],
        .register-form input[type="date"],
        .register-form input[type="email"] {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            width: 90%;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.5em;
            }

            .button {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Varmısın İddaya?</h1>
    </div>

    <div class="register-form">
        <h2>Kayıt Ol</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required><br>
            <input type="text" name="isim" placeholder="Ad" required><br>
            <input type="text" name="soyisim" placeholder="Soyad" required><br>
            <input type="date" name="dogum_tarihi" placeholder="Doğum Tarihi" required><br>
            <input type="email" name="email" placeholder="E-posta" required><br>
            <input type="text" name="papara_numarasi" placeholder="Papara Numarası (Opsiyonel)"><br>
            <input type="password" name="sifre" placeholder="Şifre" required><br>
            <button type="submit" class="button">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>