-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 25 Mar 2025, 19:26:05
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `varmi_iddia`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bildirimler`
--

CREATE TABLE `bildirimler` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(255) NOT NULL,
  `rakip_kullanici_adi` varchar(255) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `onay` int(11) NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `bildirimler`
--

INSERT INTO `bildirimler` (`id`, `kullanici_adi`, `rakip_kullanici_adi`, `tutar`, `onay`, `tarih`) VALUES
(36, 'osman32', 'osman32', 100.00, 0, '2025-03-25 17:17:29'),
(37, 'osman32', 'osman32', 10.00, 0, '2025-03-25 17:21:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(255) NOT NULL,
  `isim` varchar(255) NOT NULL,
  `soyisim` varchar(255) NOT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `papara_numarasi` varchar(255) DEFAULT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `profil_fotografi` varchar(255) DEFAULT NULL,
  `bakiye` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `isim`, `soyisim`, `dogum_tarihi`, `email`, `papara_numarasi`, `sifre`, `kayit_tarihi`, `profil_fotografi`, `bakiye`) VALUES
(100000, 'admin', 'admin', 'admin', '0000-00-00', 'admin@admin', '', 'admin123', '0000-00-00 00:00:00', 'admin.png', 0.00),
(100001, 'osman32', 'osman', 'kapçı', '1992-09-17', 'osman@gmail.com', '12335466', 'osman.3203', '0000-00-00 00:00:00', 'osman32.png', 1000.00),
(100002, 'Cihat16', 'Cihat', 'Varhan', '1997-01-17', 'cihat@gmail.com', '88888888', 'cihat123', '2025-03-23 23:27:06', 'osman32.png', 1000.00),
(100003, 'onuraydi', 'Onurhan', 'Aydilek', '2001-05-11', 'onurhanaydilek21@gmail.com', '11223344', '11223344', '2025-03-23 22:09:15', 'osman32.png', 1000.00),
(100004, 'serdar32', 'serdar', 'demirer', '1992-07-15', 'serdar@gmail.com', '12312122', 'serdar123', '2025-03-23 21:25:47', 'osman32.png', 1000.00);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bildirimler`
--
ALTER TABLE `bildirimler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `papara_numarasi` (`papara_numarasi`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bildirimler`
--
ALTER TABLE `bildirimler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100008;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
