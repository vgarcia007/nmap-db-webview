-- Adminer 4.8.1 MySQL 10.1.47-MariaDB-1~bionic dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `icons`;
CREATE TABLE `icons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_string` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `match_in` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `icons` (`id`, `search_string`, `match_in`, `icon_file`) VALUES
(1,	'Linux',	'os',	'Linux.png'),
(2,	'Microsoft',	'os',	'microsoft.png'),
(3,	'Fortinet',	'os',	'Fortinet.png'),
(4,	'AVM',	'vendor',	'avm.png'),
(5,	'Microsoft',	'vendor',	'microsoft.png'),
(6,	'Espressif',	'vendor',	'Espressif.png'),
(7,	'Amazon',	'vendor',	'Amazon.png'),
(8,	'Raspberry',	'vendor',	'Raspberry.png'),
(9,	'Nintendo',	'vendor',	'Nintendo.png'),
(10,	'Brother',	'vendor',	'Brother.png'),
(11,	'Tp-link',	'vendor',	'Tp-link.png'),
(12,	'Qnap',	'vendor',	'Qnap.png'),
(13,	'Ubiquiti',	'vendor',	'Ubiquiti.png'),
(14,	'Vorwerk',	'vendor',	'Vorwerk.png'),
(15,	'Samsung',	'vendor',	'Samsung.png'),
(16,	'Google',	'vendor',	'Google.png'),
(17,	'Lenovo',	'vendor',	'Lenovo.png'),
(18,	'Intel',	'vendor',	'Intel.png'),
(19,	'thermomix',	'hostname',	'Vorwerk.png'),
(20,	'KP105',	'hostname',	'Tp-link.png'),
(21,	'Pixel-4a',	'hostname',	'Google.png'),
(22,	'S20-FE',	'hostname',	'Samsung.png'),
(23,	'iPhone',	'hostname',	'Apple.png'),
(24,	'iPad',	'hostname',	'Apple.png'),
(25,	'Lenovo',	'hostname',	'Lenovo.png'),
(26,	'2107113SG',	'hostname',	'xiaomi.png'),
(27,	'Toniebox',	'hostname',	'tonies.png'),
(28,	'Alexa',	'hostname',	'Amazon.png'),
(29,	'Huawei',	'vendor',	'Huawei.png'),
(30,	'Galaxy',	'hostname',	'Samsung.png');

-- 2022-09-01 19:48:07
