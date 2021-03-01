-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-02-2021 a las 21:06:19
-- Versión del servidor: 10.3.27-MariaDB-0+deb10u1
-- Versión de PHP: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `foxior`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) NOT NULL,
  `avatar` text DEFAULT NULL,
  `name` text NOT NULL,
  `type` enum('business','personal') NOT NULL,
  `path` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `description` text DEFAULT NULL,
  `website` text DEFAULT NULL,
  `zip_code` text DEFAULT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `time_zone` text NOT NULL,
  `currency` text NOT NULL,
  `language` text NOT NULL,
  `fiscal` text DEFAULT NULL,
  `work_team` text DEFAULT NULL,
  `permissions` text NOT NULL,
  `settings` text NOT NULL,
  `signup_date` date NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `accounts`
--

INSERT INTO `accounts` (`id`, `avatar`, `name`, `type`, `path`, `email`, `phone`, `description`, `website`, `zip_code`, `country`, `city`, `time_zone`, `currency`, `language`, `fiscal`, `work_team`, `permissions`, `settings`, `signup_date`, `blocked`) VALUES
(4, NULL, 'El Triangulo de las fundas', 'business', 'eltriangulodelasfundas', 'julianrr5@hotmail.com', '', NULL, NULL, NULL, 'MEX', 'Cancún', 'America/Cancun', 'MXN', 'es', '{\"id\":\"\",\"name\":\"\",\"country\":\"\"}', '[\"3\",\"1\"]', '[\"1\",\"2\",\"3\"]', '[]', '2016-01-01', 0),
(5, NULL, 'By Paloma Ceballos', 'business', 'bypalomaceballos', 'ceballospalomas@yahoo.com.mx', '', NULL, NULL, NULL, 'MEX', 'Cancún', 'America/Cancun', 'MXN', 'es', '{\"id\":\"\",\"name\":\"\",\"country\":\"\"}', '[\"4\",\"1\"]', '[\"1\",\"2\",\"3\"]', '[]', '2017-12-21', 0),
(6, NULL, 'Botanero Nacional', 'business', 'botaneronacional', 'german@botaneronacional.com', '', NULL, NULL, NULL, 'MEX', 'Cancún', 'America/Cancun', 'MXN', 'es', '{\"id\":\"\",\"name\":\"\",\"country\":\"\"}', '[\"5\",\"6\",\"1\",\"8\",\"9\",\"10\",\"11\",\"2\"]', '[\"1\",\"2\",\"3\"]', '[]', '2020-01-27', 0),
(8, NULL, 'Hot Restaurant', 'business', 'hotrestaurant', 'contacto@hotrestaurant.com', '', NULL, NULL, NULL, 'MEX', 'Cancún', 'America/Cancun', 'MXN', 'es', '{\"id\":\"\",\"name\":\"\",\"country\":\"\"}', '[\"1\",\"2\"]', '[\"1\",\"2\",\"3\"]', '[]', '2020-01-01', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accounts_permissions`
--

CREATE TABLE `accounts_permissions` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `priority` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `accounts_permissions`
--

INSERT INTO `accounts_permissions` (`id`, `name`, `code`, `priority`) VALUES
(1, '{\"es\":\"Inventarios\",\"en\":\"Inventories\"}', 'inventories', '1.1.1'),
(2, '{\"es\":\"Punto de venta\",\"en\":\"Selling point\"}', 'selling_point', '1.2.1'),
(3, '{\"es\":\"Tienda en linea\",\"en\":\"Online store\"}', 'ecommerce', '1.2.2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `type` enum('bill','ticket') NOT NULL,
  `token` text NOT NULL,
  `payment` text NOT NULL,
  `iva` double NOT NULL,
  `discount` text NOT NULL,
  `created_date` date NOT NULL,
  `created_hour` time NOT NULL,
  `created_user` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `bills`
--

INSERT INTO `bills` (`id`, `account`, `type`, `token`, `payment`, `iva`, `discount`, `created_date`, `created_hour`, `created_user`) VALUES
(1, 6, 'bill', '000011122', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-11-23', '14:50:44', 1),
(4, 6, 'bill', '00012345', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-07', '14:15:43', 8),
(5, 6, 'bill', 'SUC003757', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-07', '14:28:09', 8),
(6, 6, 'ticket', 'REM 802', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-11', '20:37:57', 11),
(7, 6, 'ticket', 'REM 01122020', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-11', '20:46:21', 11),
(8, 6, 'ticket', '01122020', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-11', '20:48:08', 11),
(9, 6, 'bill', 'B 68455936', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '13:55:23', 11),
(10, 6, 'ticket', 'REM 02122020', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '14:01:04', 11),
(11, 6, 'ticket', 'REM 207028', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '14:03:22', 11),
(12, 6, 'bill', 'QA-2899991', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '15:40:55', 11),
(13, 6, 'ticket', 'REM 5529', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '16:34:20', 11),
(14, 6, 'ticket', 'REM 5535', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '16:37:36', 11),
(15, 6, 'ticket', 'REM 5536', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '16:40:02', 11),
(16, 6, 'bill', 'SUC003725', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '17:46:57', 11),
(17, 6, 'bill', 'DU10280', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '17:51:29', 11),
(18, 6, 'ticket', 'REM 831', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '19:32:57', 11),
(19, 6, 'bill', '5277', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '19:36:40', 11),
(20, 6, 'ticket', 'REM 207171', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '19:38:21', 11),
(21, 6, 'ticket', 'REM 04122020', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-12', '19:40:00', 11),
(22, 6, 'bill', 'B 68848446', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-21', '19:23:28', 11),
(23, 6, 'bill', 'DD9D3FB3031D', '{\"way\":\"electronic_fund_transfer\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-21', '19:51:24', 11),
(24, 6, 'bill', 'A 154131', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-21', '20:03:04', 11),
(25, 6, 'bill', '69010851', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-21', '20:31:08', 11),
(26, 6, 'bill', 'B 68913526', '{\"way\":\"nominal_check\",\"method\":\"\"}', 0, '{\"type\":\"\",\"amount\":\"0\"}', '2020-12-21', '20:41:50', 11),
(27, 8, 'bill', 'YvwkD5KS', '{\"way\":\"cash\",\"method\":\"\"}', 23.9, '{\"type\":\"%\",\"amount\":\"10\"}', '2021-01-07', '14:39:32', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `avatar` text DEFAULT NULL,
  `name` text NOT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `fiscal` text DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `branches`
--

INSERT INTO `branches` (`id`, `account`, `avatar`, `name`, `email`, `phone`, `fiscal`, `blocked`) VALUES
(1, 6, NULL, 'Botanero Parque Las Palapas', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(2, 6, NULL, 'Botanero  Zona Hotelera', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(3, 6, NULL, 'Botanero Plaza de toros', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(4, 8, NULL, 'Sucursal 1', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"ABCD123456789\",\"name\":\"Sucursal 1 SA\",\"country\":\"MEX\",\"state\":\"1\",\"address\":\"Mi direcci\\u00f3n\"}', 0),
(5, 8, NULL, 'Sucursal 2', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(6, 4, 'OeoM6BQNCVjCLyBI.png', 'EL TRIANGULO DE LAS FUNDAS (58)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORA9112106R6\",\"name\":\"ADRIAN VALENTE ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 58, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(7, 4, 'mukQlhwNFun9VWEP.png', 'EL TRIANGULO DE LAS FUNDAS II (94-99)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORA9112106R6\",\"name\":\"ADRIAN VALENTE ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 58, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(8, 4, 'zkesywDaCshjqqdC.png', 'EL TRIANGULO VIRTUAL (135)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORA9112106R6\",\"name\":\"ADRIAN VALENTE ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 58, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(9, 4, 'L1eKFKQxOvh2j57z.png', 'EL TRIANGULO DE LAS MICAS (138)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORJ8910101S7\",\"name\":\"JULIAN ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 138, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(10, 4, 'ArchmJWW4Pk9PYRX.png', 'EL TRIANGULO DE LAS FUNDAS III (171)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORJ8910101S7\",\"name\":\"JULIAN ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 138, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(11, 4, 'GxkkiiIEkq1hYJo5.png', 'EL TRIANGULO REPARACIONES (219-220)', 'eltrianguloreparaciones@gmail.com', '{\"country\":\"52\",\"number\":\"9981409636\"}', '{\"id\":\"RORJ8910101S7\",\"name\":\"JULIAN ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 138, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(12, 4, 'yBX3vCYGcDPXyggF.png', 'EL TRIANGULO FRIKI (320)', 'eltraingulodelasfundas@gmail.com', '{\"country\":\"52\",\"number\":\"9985614207\"}', '{\"id\":\"RORJ8910101S7\",\"name\":\"JULIAN ROMERO RIVERA\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"AVENIDA COSTA MAYA, REGION 228, MANZANA 22, LOTE 1, LOCAL SUB ANCLA 3, LOCAL 138, CANC\\u00daN, QUINTANA ROO, C.P. 77516\"}', 0),
(13, 6, NULL, 'Taquería Ha Bárbaro', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"GBA170510BR6\",\"name\":\"Gastronimica Barbaro sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"Blvd. Kukulk\\u00e1n zona hotelera km 9 Mza 48 Lt 08-02\"}', 0),
(14, 6, NULL, 'Botanero Maderos', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"BPC200928TA0\",\"name\":\"BOTANERO PUERTO CANCUN SA DE CV\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"Farrahon #24 SM 15 Cancun, Benito Juarez Q,roo CP77500\"}', 0),
(15, 6, NULL, 'Botanero Las Torres', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(16, 8, NULL, 'Sucursal 3', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(17, 6, NULL, 'Alimentos Procesados Elgeso ', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `branch` bigint(20) NOT NULL,
  `movement` enum('input','output') NOT NULL,
  `date` date NOT NULL,
  `hour` time NOT NULL,
  `type` bigint(20) NOT NULL,
  `product` bigint(20) NOT NULL,
  `quantity` text DEFAULT NULL,
  `cost` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `location` bigint(20) DEFAULT NULL,
  `categories` text DEFAULT NULL,
  `origin` text DEFAULT NULL,
  `provider` bigint(20) DEFAULT NULL,
  `bill` bigint(20) DEFAULT NULL,
  `transfer` text NOT NULL,
  `created_date` date NOT NULL,
  `created_hour` time NOT NULL,
  `created_user` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventories`
--

INSERT INTO `inventories` (`id`, `account`, `branch`, `movement`, `date`, `hour`, `type`, `product`, `quantity`, `cost`, `total`, `location`, `categories`, `origin`, `provider`, `bill`, `transfer`, `created_date`, `created_hour`, `created_user`) VALUES
(117, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 233, '23', '12.00', '276', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(118, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 234, '40', '12.07', '482.8', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(119, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 235, '14', '17.50', '245', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(120, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 236, '11', '13.08', '143.88', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(121, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 245, '12', '17.95', '215.4', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(122, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 240, '46', '10.69', '491.74', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(123, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 241, '184', '10.69', '1966.96', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(124, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 242, '47', '15.89', '746.83', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(125, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 243, '148', '11.51', '1703.48', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(126, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 244, '323', '11.49', '3711.27', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(127, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 111, '0.49', '179.98571428571', '88.193', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".7\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(128, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 112, '0.525', '138.01333333333', '72.457', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".7\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(129, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 115, '1.82', '225.7', '410.774', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.6\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(130, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 116, '1.5', '229', '343.5', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(131, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 117, '0.525', '396', '207.9', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".7\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(132, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 118, '0.6', '494.25333333333', '296.552', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".8\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(133, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 119, '0.75', '237.93333333333', '178.45', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(134, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 120, '1.25', '330.56', '413.2', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(135, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 455, '14.15', '59.34', '839.661', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"14.15\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(136, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 121, '0.375', '562.66666666667', '211', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".5\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(137, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 122, '1.2', '540.22666666667', '648.272', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(138, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 123, '1.8', '634.76', '1142.568', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(139, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 125, '0.245', '480.3', '117.6735', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".35\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(140, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 316, '13.95', '209.2', '2918.34', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"18.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(141, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 130, '2.7', '148.28', '400.356', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(142, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 131, '0.675', '171.26666666667', '115.605', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".90\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(143, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 132, '0.3375', '378.16', '127.629', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".45\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(144, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 134, '2.4', '192.05333333333', '460.928', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(145, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 135, '0.3', '174.70666666667', '52.412', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(146, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 136, '2.775', '228.73333333333', '634.735', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3.7\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(147, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 137, '2.3', '154.31', '354.913', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(148, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 138, '0.45', '198.66666666667', '89.4', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(149, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 127, '0.675', '246.66666666667', '166.5', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".90\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(150, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 128, '0.9', '165.52', '148.968', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(151, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 139, '0.91', '666.5', '606.515', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.3\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(152, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 141, '1.435', '1022.1714285714', '1466.816', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.05\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(153, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 142, '1.90', '439.66', '835.354', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(154, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 143, '0.56', '661.32857142857', '370.344', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".8\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(155, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 145, '0.385', '547.04285714286', '210.6115', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".55\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(156, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 146, '1.715', '572.65714285714', '982.107', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.45\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(157, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 148, '1.19', '257.38571428571', '306.289', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.7\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(158, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 153, '1.47', '651.47142857143', '957.663', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(159, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 151, '0.24325', '297.69784172662', '72.415', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".35\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(160, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 152, '.4', '244', '97.6', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(161, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 317, '10.4', '70.13', '729.352', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"10.4\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(162, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 155, '0.075', '209.33333333333', '15.7', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(163, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 157, '0.225', '228.73333333333', '51.465', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(164, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 156, '1.5', '188.50666666667', '282.76', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(165, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 158, '1.05', '304.6', '319.83', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(166, 6, 1, 'input', '2020-11-18', '16:00:23', 1, 159, '0.9', '175.98666666667', '158.388', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:00:59', 10),
(170, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 162, '2.925', '827.58666666667', '2420.691', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3.90\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(171, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 163, '4.76', '332.51428571429', '1582.768', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"6.80\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(172, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 164, '1.95', '486.66666666667', '949', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(173, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 165, '1.05', '304.6', '319.83', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(174, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 166, '1.19', '184.72857142857', '219.827', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.7\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(175, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 167, '0.15', '195.6', '29.34', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(176, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 169, '0.735', '367', '269.745', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.05\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(177, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 170, '0.3', '188', '56.4', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\".4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(178, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 168, '1.125', '532', '598.5', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.5\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(179, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 247, '34', '7.58', '257.72', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(180, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 248, '125', '6.75', '843.75', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(181, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 326, '172', '5.21', '896.12', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(182, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 251, '44', '14.15', '622.6', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(183, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 252, '5', '7.89', '39.45', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(184, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 253, '108', '7.89', '852.12', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(185, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 256, '108', '7.89', '852.12', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(186, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 255, '8', '7.89', '63.12', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(187, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 257, '12', '7.89', '94.68', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(188, 6, 1, 'input', '2020-11-18', '16:01:00', 1, 258, '140', '7.89', '1104.6', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-18', '16:11:59', 10),
(193, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 233, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(194, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 236, '12', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(195, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 245, '11', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(196, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 238, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(197, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 239, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(198, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 240, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(199, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 241, '609', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(200, 6, 3, 'input', '2020-11-16', '14:00:50', 1, 244, '72', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '10:31:16', 1),
(201, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 130, '2.175', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.9\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(202, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 131, '2.475', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3.3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(203, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 132, '0.15', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(204, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 136, '5.4', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"7.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(205, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 137, '3.45', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"4.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(206, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 138, '0.45', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.6\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(207, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 127, '0.1875', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.25\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(208, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 128, '0.75', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(209, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 129, '1.65', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(210, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 319, '11.8', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"11.80\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(211, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 139, '0.14', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.2\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(212, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 140, '0.49', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.7\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(213, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 141, '1.05', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.5\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(214, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 142, '0.28', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.4\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(215, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 143, '0.84', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.2\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(216, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 145, '0.28', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.4\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(217, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 146, '0.7', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(218, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 148, '1.295', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.85\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(219, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 149, '4.1005', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"5.9\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(220, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 153, '0.75', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(221, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 151, '2.0155', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.9\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(222, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 152, '0.278', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.4\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(223, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 150, '3.2665', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"4.7\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(224, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 317, '5.8', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"5.80\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(225, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 111, '0.56', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.8\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(226, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 112, '0.525', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.7\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(227, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 113, '0.77', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(228, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 115, '0.63', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.9\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(229, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 116, '0.105', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.15\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(230, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 117, '0.525', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.7\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(231, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 118, '0.825', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(232, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 119, '1.575', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(233, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 120, '0.825', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(234, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 453, '0.075', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(235, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 455, '0.1', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.1\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(236, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 121, '1.05', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(237, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 122, '1.725', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(238, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 123, '1.725', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(239, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 451, '0.975', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(240, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 125, '0.63', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.9\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(241, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 316, '12.375', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"16.5\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(242, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 155, '0.675', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.9\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(243, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 157, '3', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(244, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 156, '0.3', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(245, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 159, '0.675', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.9\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(246, 6, 3, 'input', '2020-11-16', '14:01:17', 1, 549, '1.6', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.6\",\"content\":\"4\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:08:49', 1),
(247, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 321, '1.425', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.9\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(248, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 452, '0', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(249, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 169, '1.33', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1.90\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(250, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 168, '0.225', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.3\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(251, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 160, '0.6', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.8\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(252, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 161, '0.15', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(253, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 163, '9.03', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"12.9\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(254, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 164, '1.575', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2.1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(255, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 165, '0.35', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(256, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 166, '0.63', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"0.9\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(257, 6, 3, 'input', '2020-11-16', '02:08:50', 1, 167, '0.75', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:16:05', 1),
(258, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 247, '12', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(259, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 248, '139', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(260, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 324, '32', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"16\",\"content\":\"8\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(261, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 325, '180', '0', '0', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"9\",\"content\":\"7\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(262, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 326, '18', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(263, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 251, '38', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(264, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 252, '12', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(265, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 253, '169', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(266, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 254, '29', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(267, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 255, '13', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(268, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 256, '84', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(269, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 257, '18', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(270, 6, 3, 'input', '2020-11-16', '14:03:06', 1, 258, '11', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '11:20:02', 1),
(271, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 156, '1.5', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(272, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 156, '0.354882', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"12\",\"child\":\"216\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(273, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 158, '0.75', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(274, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 158, '0.6210435', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"21\",\"child\":\"218\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(275, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 157, '1.6265425', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"55\",\"child\":\"217\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(276, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 159, '0.118294', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"4\",\"child\":\"219\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(277, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 139, '0.3253085', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"11\",\"child\":\"199\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(278, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 140, '0.414029', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"14\",\"child\":\"200\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(279, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 141, '0.7', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(280, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 149, '1.39', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(281, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 150, '1.39', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(282, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 151, '4.17', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"6\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(283, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 153, '0.2661615', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"9\",\"child\":\"213\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(284, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 141, '1.596969', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"54\",\"child\":\"201\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(285, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 143, '0.059147', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"2\",\"child\":\"203\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(286, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 147, '0.177441', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"6\",\"child\":\"207\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(287, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 148, '0.946352', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"32\",\"child\":\"208\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(288, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 149, '0.828058', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"28\",\"child\":\"209\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(289, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 150, '4.14029', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"140\",\"child\":\"210\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(290, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 151, '1.8039835', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"61\",\"child\":\"211\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(291, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 127, '0.0887205', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"3\",\"child\":\"187\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(292, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 128, '0.1478675', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"5\",\"child\":\"188\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(293, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 129, '1.1533665', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"39\",\"child\":\"189\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(294, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 129, '0.75', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(295, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 130, '3', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"4\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(296, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 131, '0.75', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(297, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 134, '1.5', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(298, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 130, '0.768911', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"26\",\"child\":\"190\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(299, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 131, '0.118294', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"4\",\"child\":\"191\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(300, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 134, '0.6210435', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"21\",\"child\":\"194\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(301, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 136, '0.1478675', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"5\",\"child\":\"196\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(302, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 138, '0.059147', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"2\",\"child\":\"198\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(303, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 162, '0.75', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(304, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 163, '12.6', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"18\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(305, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 160, '0.1478675', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"5\",\"child\":\"220\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(306, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 163, '4.6430395', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"157\",\"child\":\"223\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(307, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 164, '0.2661615', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"9\",\"child\":\"224\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(308, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 166, '0.0887205', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"3\",\"child\":\"226\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(309, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 167, '0.5027495', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"17\",\"child\":\"227\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(310, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 121, '0.2070145', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"7\",\"child\":\"181\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(311, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 118, '0.1478675', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"5\",\"child\":\"178\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(312, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 119, '0.2661615', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"9\",\"child\":\"179\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(313, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 120, '0.0887205', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"3\",\"child\":\"180\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(314, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 115, '0.7', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(315, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 115, '0.177441', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"6\",\"child\":\"175\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(316, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 123, '0.354882', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"12\",\"child\":\"183\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(317, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 451, '2.1588655', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"73\",\"child\":\"461\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(318, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 125, '0.177441', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"6\",\"child\":\"185\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(319, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 122, '0.0887205', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"3\",\"child\":\"182\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(320, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 234, '24', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(321, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 245, '82', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(322, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 237, '276', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(323, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 240, '161', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(324, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 241, '970', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(325, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 242, '128', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(326, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 243, '101', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(327, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 244, '1389', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(328, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 248, '219', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(329, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 247, '36', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(330, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 251, '33', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(331, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 252, '3', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(332, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 253, '118', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(333, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 254, '7', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(334, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 255, '4', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(335, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 256, '59', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(336, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 257, '18', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(337, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 258, '20', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(338, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 326, '13', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(339, 6, 3, 'output', '2020-11-22', '14:28:47', 2, 169, '0.1478675', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"5\",\"child\":\"228\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:43:11', 1),
(340, 6, 2, 'input', '2020-11-23', '14:49:40', 1, 128, '0.75', '133.33333333333', '100', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 3, 1, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '14:50:44', 1),
(341, 6, 3, 'input', '2020-11-16', '17:17:04', 1, 324, '10', '12', '120', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:19:14', 8),
(342, 6, 3, 'input', '2020-11-16', '17:17:04', 1, 257, '24', '8', '192', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:19:14', 8);
INSERT INTO `inventories` (`id`, `account`, `branch`, `movement`, `date`, `hour`, `type`, `product`, `quantity`, `cost`, `total`, `location`, `categories`, `origin`, `provider`, `bill`, `transfer`, `created_date`, `created_hour`, `created_user`) VALUES
(343, 6, 3, 'input', '2020-11-16', '17:17:04', 1, 248, '48', '7', '336', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:19:14', 8),
(344, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 339, '25', '30', '750', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(345, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 335, '5', '75', '375', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(346, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 332, '7', '75', '525', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(347, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 337, '4', '75', '300', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(348, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 336, '2', '100', '200', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(349, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 334, '2', '100', '200', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(350, 6, 3, 'input', '2020-11-16', '17:19:14', 1, 333, '2', '100', '200', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '17:23:07', 8),
(351, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 240, '160', '12', '1920', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(352, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 241, '460', '12', '5520', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(353, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 243, '100', '13', '1300', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(354, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 239, '24', '11', '264', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(355, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 244, '1344', '13', '17472', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(356, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 323, '24', '32', '768', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(357, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 234, '24', '14', '336', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(358, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 238, '20', '12', '240', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(359, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 245, '168', '19', '3192', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(360, 6, 3, 'input', '2020-11-17', '18:04:01', 1, 242, '96', '18', '1728', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:16:08', 8),
(361, 6, 3, 'input', '2020-11-17', '18:16:09', 1, 451, '4', '326', '1304', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:18:12', 8),
(362, 6, 3, 'input', '2020-11-18', '18:24:45', 1, 247, '24', '7', '168', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:25:04', 8),
(363, 6, 3, 'input', '2020-11-18', '18:24:45', 1, 256, '48', '11', '528', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:25:04', 8),
(364, 6, 3, 'input', '2020-11-18', '18:25:42', 1, 253, '144', '8', '1152', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:26:19', 8),
(366, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 139, '1', '559.48', '559.48', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(367, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 141, '1', '715.52', '715.52', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(368, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 156, '1', '141.38', '141.38', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(369, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 157, '2', '171.55', '343.1', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(370, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 160, '1', '547.41', '547.41', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(371, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 162, '1', '620', '620', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(372, 6, 3, 'input', '2020-11-17', '18:36:27', 1, 165, '1', '228', '228', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:51:42', 8),
(373, 6, 3, 'input', '2020-11-16', '18:51:43', 1, 150, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:53:22', 8),
(374, 6, 3, 'input', '2020-11-16', '18:51:43', 1, 151, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:53:22', 8),
(375, 6, 3, 'input', '2020-11-16', '18:51:43', 1, 158, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '18:53:22', 8),
(376, 6, 3, 'input', '2020-11-19', '18:53:23', 1, 252, '24', '11', '264', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:02:40', 8),
(377, 6, 3, 'input', '2020-11-19', '18:53:23', 1, 247, '24', '7', '168', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:02:40', 8),
(378, 6, 3, 'input', '2020-11-19', '18:53:23', 1, 258, '48', '8', '384', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:02:40', 8),
(379, 6, 3, 'input', '2020-11-20', '19:02:41', 1, 241, '120', '12', '1440', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:09:02', 8),
(380, 6, 3, 'input', '2020-11-20', '19:02:41', 1, 237, '80', '12', '960', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:09:02', 8),
(381, 6, 3, 'input', '2020-11-20', '19:02:41', 1, 244, '408', '13', '5304', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:09:02', 8),
(382, 6, 3, 'input', '2020-11-20', '19:02:41', 1, 242, '144', '18', '2592', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:09:02', 8),
(383, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 115, '1', '155.17', '155.17', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(384, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 121, '2', '362.07', '724.14', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(385, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 130, '3', '111.21', '333.63', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(386, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 129, '4', '125', '500', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(387, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 141, '1', '715', '715', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(388, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 142, '1', '439.66', '439.66', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(389, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 145, '1', '336.21', '336.21', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(390, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 160, '1', '547.41', '547.41', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(391, 6, 3, 'input', '2020-11-20', '19:09:03', 1, 163, '8', '232.76', '1862.08', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:20:48', 8),
(392, 6, 3, 'input', '2020-11-21', '19:21:32', 1, 163, '6', '306', '1836', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:23:17', 8),
(393, 6, 3, 'input', '2020-11-21', '19:21:32', 1, 156, '1', '173.53', '173.53', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:23:17', 8),
(394, 6, 3, 'input', '2020-11-21', '19:21:32', 1, 151, '8', '227', '1816', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-11-23', '19:23:17', 8),
(418, 6, 3, 'input', '2020-11-29', '09:55:07', 7, 141, '0.296969', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-03', '09:55:07', 1),
(419, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 115, '1', '155.17', '155.17', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(420, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 125, '1', '336.21', '336.21', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(421, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 130, '3', '111.21', '333.63', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(422, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 139, '1', '559.48', '559.48', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(423, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 141, '2', '715.52', '1431.04', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(424, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 147, '1', '176.72', '176.72', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(425, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 149, '5', '120.69', '603.45', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(426, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 151, '3', '206.9', '620.7', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(427, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 160, '1', '547.41', '547.41', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(428, 6, 3, 'input', '2020-11-30', '16:59:56', 1, 163, '12', '232.76', '2793.12', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:14:26', 8),
(430, 6, 3, 'input', '2020-12-01', '17:14:26', 1, 240, '500', '12.4', '6200', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:31:08', 8),
(431, 6, 3, 'input', '2020-12-01', '17:14:26', 1, 243, '100', '13.35', '1335', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:31:08', 8),
(432, 6, 3, 'input', '2020-12-01', '17:14:26', 1, 237, '200', '12.4', '2480', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:31:08', 8),
(433, 6, 3, 'input', '2020-12-01', '17:14:26', 1, 241, '888', '13.13', '11659.44', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:31:08', 8),
(434, 6, 3, 'input', '2020-12-04', '17:31:09', 1, 234, '12', '14', '168', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:34:14', 8),
(435, 6, 3, 'input', '2020-12-04', '17:31:09', 1, 245, '48', '19.62', '941.76', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:34:14', 8),
(436, 6, 3, 'input', '2020-12-04', '17:31:09', 1, 242, '48', '18.41', '883.68', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:34:14', 8),
(437, 6, 3, 'input', '2020-12-04', '17:34:15', 1, 253, '96', '8.75', '840', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:37:50', 8),
(438, 6, 3, 'input', '2020-12-04', '17:34:15', 1, 258, '24', '8.75', '210', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:37:50', 8),
(439, 6, 3, 'input', '2020-12-04', '17:34:15', 1, 256, '48', '12.16', '583.68', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:37:50', 8),
(440, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 161, '1', '485.90', '485.9', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(441, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 148, '2', '175.47', '350.94', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(442, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 150, '6', '230.84', '1385.04', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(443, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 153, '1', '554', '554', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(444, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 121, '2', '378.23', '756.46', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(445, 6, 3, 'input', '2020-12-02', '17:37:50', 1, 122, '1', '427.66', '427.66', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:42:15', 8),
(446, 6, 3, 'input', '2020-12-04', '17:42:15', 1, 240, '160', '12.4', '1984', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:47:17', 8),
(447, 6, 3, 'input', '2020-12-04', '17:42:15', 1, 241, '300', '12.40', '3720', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:47:17', 8),
(448, 6, 3, 'input', '2020-12-04', '17:42:15', 1, 237, '200', '12.40', '2480', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '17:47:17', 8),
(449, 6, 3, 'input', '2020-12-04', '17:50:08', 1, 238, '20', '12.4', '248', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-04', '18:07:59', 8),
(459, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 121, '0.75', '482.76', '362.07', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(460, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 169, '1.4', '367', '513.8', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"1\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(461, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 130, '1.5', '148.28', '222.42', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(462, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 149, '2.085', '173.65467625899', '362.07', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3\",\"content\":\"3\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(463, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 153, '0.75', '688.50666666667', '516.38', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(464, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 150, '4.17', '310.10071942446', '1293.12', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"6\",\"content\":\"3\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(465, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 151, '4.17', '297.69784172662', '1241.4', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"6\",\"content\":\"3\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(466, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 156, '1.5', '188.50666666667', '282.76', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(467, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 157, '2.25', '228.73333333333', '514.65', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"3\",\"content\":\"2\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(468, 6, 3, 'input', '2020-12-04', '14:18:27', 1, 163, '12.6', '332.51428571429', '4189.68', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"18\",\"content\":\"1\"}', NULL, 5, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-07', '14:28:09', 8),
(469, 6, 1, 'input', '2020-12-01', '20:34:02', 1, 264, '10', '157', '1570', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 7, 6, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '20:37:57', 11),
(470, 6, 1, 'input', '2020-12-01', '20:37:58', 1, 299, '.400', '112.50', '45', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 7, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '20:46:21', 11),
(471, 6, 1, 'input', '2020-12-01', '20:46:22', 1, 303, '.3', '100', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '20:48:08', 11),
(472, 6, 1, 'input', '2020-12-01', '20:48:09', 1, 305, '1', '15', '15', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '21:05:20', 11),
(473, 6, 1, 'input', '2020-12-01', '20:48:09', 1, 306, '2', '15', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '21:05:20', 11),
(474, 6, 1, 'input', '2020-12-01', '20:48:09', 1, 299, '.4', '112.50', '45', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '21:05:20', 11),
(475, 6, 1, 'input', '2020-12-01', '20:48:09', 1, 303, '.3', '100', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '21:05:20', 11),
(476, 6, 1, 'input', '2020-12-01', '20:48:09', 1, 309, '2', '20', '40', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 8, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-11', '21:05:20', 11),
(477, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 241, '160', '10.85', '1736', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(478, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 243, '160', '11.68', '1868.8', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(479, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 237, '100', '10.85', '1085', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(480, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 244, '888', '9.46', '8400.48', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(481, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 323, '12', '28.44', '341.28', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(482, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 234, '24', '12.25', '294', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(483, 6, 1, 'input', '2020-12-01', '13:34:21', 1, 238, '72', '10.75', '774', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 9, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '13:55:23', 11),
(484, 6, 1, 'input', '2020-12-02', '13:55:24', 1, 284, '3', '15', '45', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:01:04', 11),
(485, 6, 1, 'input', '2020-12-02', '13:55:24', 1, 285, '2', '15', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:01:04', 11),
(486, 6, 1, 'input', '2020-12-02', '13:55:24', 1, 290, '2', '12', '24', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:01:04', 11),
(487, 6, 1, 'input', '2020-12-02', '14:01:05', 1, 315, '10', '9', '90', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 5, 11, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:03:22', 11),
(488, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 306, '5', '15', '75', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(489, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 294, '1', '27', '27', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(490, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 314, '2', '16', '32', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(491, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 293, '4', '29', '116', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(492, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 482, '.2', '150', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(493, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 305, '1', '15', '15', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(494, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 307, '1', '10', '10', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(495, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 308, '1', '20', '20', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(496, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 283, '.5', '110', '55', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(497, 6, 1, 'input', '2020-12-02', '14:03:23', 1, 299, '.2', '75', '15', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 10, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '14:32:11', 11),
(498, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 253, '144', '8.6889', '1251.2016', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(499, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 254, '24', '8.6889', '208.5336', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(500, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 255, '24', '8.68', '208.32', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(501, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 258, '72', '8.688', '625.536', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(502, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 257, '48', '8.688', '417.024', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(503, 6, 1, 'input', '2020-12-02', '15:25:45', 1, 256, '96', '12.0833', '1159.9968', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 12, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '15:40:55', 11),
(504, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 277, '1', '107.12', '107.12', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(505, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 519, '4', '43.48', '173.92', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(506, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 287, '2', '11.67', '23.34', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(507, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 372, '4', '18.82', '75.28', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(508, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 370, '1', '26.66', '26.66', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(509, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 373, '1', '43.02', '43.02', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(510, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 262, '1', '126.72', '126.72', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(511, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 273, '1', '88.54', '88.54', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(512, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 286, '96', '1.6043', '154.0128', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(513, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 371, '3.78', '29.25', '110.565', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(514, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 288, '4', '9.90', '39.6', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(515, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 343, '1', '10.95', '10.95', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(516, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 377, '3.78', '46.3', '175.014', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(517, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 347, '20000', '0.02366', '473.2', NULL, '[\"5\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(518, 6, 1, 'input', '2020-12-02', '15:40:56', 1, 279, '500', '0.05857', '29.285', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 13, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:34:20', 11),
(519, 6, 1, 'input', '2020-12-02', '16:34:21', 1, 261, '30.8', '74', '2279.2', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 14, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:37:36', 11),
(520, 6, 1, 'input', '2020-12-02', '16:37:36', 1, 338, '12', '34.9975', '419.97', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 8, 15, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '16:40:02', 11),
(521, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 153, '1.5', '688.50666666667', '1032.76', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(522, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 150, '5.56', '310.10071942446', '1724.16', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"8\",\"content\":\"3\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(523, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 151, '5.56', '297.69784172662', '1655.2', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"8\",\"content\":\"3\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(524, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 152, '.75', '614.9466', '461.20995', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(525, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 158, '0.75', '304.6', '228.45', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(526, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 159, '0.75', '155.17333333333', '116.38', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(527, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 157, '3.75', '228.73333333333', '857.75', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"5\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(528, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 160, '1.5', '729.88', '1094.82', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(529, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 162, '0.75', '827.58666666667', '620.69', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(530, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 163, '8.4', '332.51428571429', '2793.12', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"12\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(531, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 166, '0.7', '184.72857142857', '129.31', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(532, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 167, '1.5', '156.32', '234.48', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(533, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 115, '0.7', '221.67142857143', '155.17', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(534, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 116, '1.4', '320.2', '448.28', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(535, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 121, '2.25', '482.76', '1086.21', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"3\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(536, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 122, '1.5', '540.22666666667', '810.34', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(537, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 123, '1.5', '718.38666666667', '1077.58', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(538, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 129, '2.25', '166.66666666667', '375', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"3\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(539, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 130, '3', '148.28', '444.84', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"4\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(540, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 135, '0.75', '187.36', '140.52', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(541, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 138, '0.75', '655.17333333333', '491.38', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"2\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(542, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 141, '2.8', '1022.1714285714', '2862.08', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"4\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(543, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 142, '2.1', '628.08571428571', '1318.98', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"3\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(544, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 148, '4.2', '257.38571428571', '1081.02', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"6\",\"content\":\"1\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(545, 6, 1, 'input', '2020-12-02', '16:40:03', 1, 149, '5.56', '173.65467625899', '965.52', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"8\",\"content\":\"3\"}', 1, 16, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:46:57', 11),
(546, 6, 1, 'input', '2020-12-02', '17:46:57', 1, 563, '0.7', '247.22857142857', '173.06', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', 3, 17, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:51:29', 11),
(547, 6, 1, 'input', '2020-12-02', '17:46:57', 1, 319, '12', '62.155', '745.86', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"12\",\"content\":\"4\"}', 3, 17, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:51:29', 11),
(548, 6, 1, 'input', '2020-12-02', '17:46:57', 1, 556, '1', '381.92', '381.92', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"4\"}', 3, 17, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:51:29', 11),
(549, 6, 1, 'input', '2020-12-02', '17:46:57', 1, 317, '12', '70.129', '841.548', NULL, '[\"4\"]', '{\"type\":\"cnt\",\"quantity\":\"12\",\"content\":\"4\"}', 3, 17, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '17:51:29', 11),
(550, 6, 1, 'input', '2020-12-03', '19:27:22', 1, 494, '5', '80', '400', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 7, 18, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:32:57', 11),
(551, 6, 1, 'input', '2020-12-03', '19:27:22', 1, 263, '2.43', '192.6', '468.018', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 7, 18, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:32:57', 11),
(552, 6, 1, 'input', '2020-12-03', '19:32:58', 1, 451, '2', '281.61', '563.22', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 19, 19, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:36:40', 11),
(553, 6, 1, 'input', '2020-12-04', '19:36:41', 1, 315, '20', '9', '180', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 5, 20, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:38:21', 11),
(554, 6, 1, 'input', '2020-12-04', '19:38:22', 1, 284, '3', '15', '45', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:40:00', 11),
(555, 6, 1, 'input', '2020-12-04', '19:38:22', 1, 285, '2', '15', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:40:00', 11),
(556, 6, 1, 'input', '2020-12-04', '19:38:22', 1, 290, '3', '12', '36', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 4, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:40:00', 11),
(557, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 306, '7', '15', '105', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(558, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 314, '3', '16', '48', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(559, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 293, '3', '27', '81', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(560, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 282, '.1', '130', '13', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(561, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 305, '2', '15', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(562, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 307, '1', '10', '10', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(563, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 298, '.2', '35', '7', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(564, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 297, '.4', '30', '12', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(565, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 283, '.5', '110', '55', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(566, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 299, '.3', '100', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(567, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 310, '1', '40', '40', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(568, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 309, '3', '20', '60', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(569, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 311, '3', '25', '75', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(570, 6, 1, 'input', '2020-12-04', '19:40:00', 1, 292, '1', '30', '30', NULL, '[\"3\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 6, 21, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-12', '19:49:15', 11),
(572, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 233, '3', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(573, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 234, '51', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(574, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 235, '4', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(575, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 236, '5', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(576, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 245, '1', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(577, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 237, '485', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(578, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 238, '84', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(579, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 239, '62', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(580, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 240, '175', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(581, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 241, '575', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(582, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 242, '49', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(583, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 243, '692', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(584, 6, 1, 'output', '2020-12-16', '15:48:06', 2, 244, '1940', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '15:51:56', 10),
(585, 6, 1, 'output', '2020-12-14', '16:12:20', 3, 236, '4', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '16:14:25', 10),
(586, 6, 1, 'output', '2020-12-14', '16:12:20', 3, 243, '2', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '16:14:25', 10),
(587, 6, 1, 'output', '2020-12-16', '16:19:27', 2, 248, '242', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-16', '16:20:13', 10),
(588, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 240, '100', '10.69', '1069', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(589, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 241, '300', '10.69', '3207', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(590, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 243, '300', '11.51', '3453', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(591, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 237, '220', '10.69', '2351.8', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(592, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 244, '888', '11.49', '10203.12', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(593, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 323, '24', '28.0175', '672.42', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(594, 6, 1, 'input', '2020-12-15', '19:13:51', 1, 238, '24', '10.5966', '254.3184', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 22, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:23:28', 11),
(595, 6, 1, 'input', '2020-12-16', '19:23:29', 1, 562, '1', '113.793', '113.793', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 3, 23, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:51:24', 11),
(596, 6, 1, 'input', '2020-12-16', '19:23:29', 1, 560, '1', '50.655', '50.655', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 3, 23, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:51:24', 11),
(597, 6, 1, 'input', '2020-12-16', '19:23:29', 1, 319, '12', '62.155', '745.86', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 3, 23, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:51:24', 11);
INSERT INTO `inventories` (`id`, `account`, `branch`, `movement`, `date`, `hour`, `type`, `product`, `quantity`, `cost`, `total`, `location`, `categories`, `origin`, `provider`, `bill`, `transfer`, `created_date`, `created_hour`, `created_user`) VALUES
(598, 6, 1, 'input', '2020-12-16', '19:23:29', 1, 317, '12', '70.129', '841.548', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 3, 23, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '19:51:24', 11),
(599, 6, 1, 'input', '2020-12-17', '19:51:25', 1, 331, '22728', '0.0288', '654.5664', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 24, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:03:04', 11),
(600, 6, 1, 'input', '2020-12-17', '19:51:25', 1, 248, '192', '4.53', '869.76', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 24, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:03:04', 11),
(601, 6, 1, 'input', '2020-12-17', '19:51:25', 1, 324, '48', '6.788', '325.824', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 15, 24, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:03:04', 11),
(602, 6, 1, 'input', '2020-12-19', '20:03:05', 1, 241, '40', '10.689', '427.56', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 25, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:31:08', 11),
(603, 6, 1, 'input', '2020-12-19', '20:03:05', 1, 243, '200', '11.5085', '2301.7', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 25, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:31:08', 11),
(604, 6, 1, 'input', '2020-12-19', '20:03:05', 1, 244, '744', '11.4941', '8551.6104', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 25, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:31:08', 11),
(605, 6, 1, 'input', '2020-12-19', '20:03:05', 1, 323, '48', '28.0175', '1344.84', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 25, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:31:08', 11),
(606, 6, 1, 'input', '2020-12-19', '20:03:05', 1, 242, '24', '15.8762', '381.0288', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 25, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:31:08', 11),
(607, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 240, '200', '10.689', '2137.8', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(608, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 241, '300', '10.689', '3206.7', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(609, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 243, '40', '11.509', '460.36', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(610, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 237, '200', '10.689', '2137.8', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(611, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 239, '96', '9.8062', '941.3952', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(612, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 244, '288', '11.4941', '3310.3008', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(613, 6, 1, 'input', '2020-12-19', '20:31:09', 1, 238, '60', '10.689', '641.34', NULL, '[\"4\"]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', 11, 26, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '20:41:50', 11),
(614, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 153, '0.03', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\".04\",\"content\":\"2\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(615, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 141, '2.877', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"4.11\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(616, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 142, '0.182', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\".26\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(617, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 143, '0.21', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\".3\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(618, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 146, '0.7', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(619, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 147, '0.028', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\".04\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(620, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 148, '2.73', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"3.90\",\"content\":\"1\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(621, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 149, '6.10905', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"8.79\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(622, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 150, '1.77225', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"2.55\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(623, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 151, '2.62015', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"3.77\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(624, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 152, '0.50735', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\".73\",\"content\":\"3\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(625, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 233, '20', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(626, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 234, '47', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(627, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 235, '1', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(628, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 236, '5', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(629, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 245, '23', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(630, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 237, '606', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(631, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 238, '156', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(632, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 239, '246', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(633, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 240, '405', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(634, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 241, '730', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(635, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 242, '40', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(636, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 243, '640', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(637, 6, 1, 'output', '2020-12-23', '11:53:27', 2, 244, '2026', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:03:15', 10),
(639, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 145, '0.665', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(640, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 139, '0.42', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(641, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 141, '1.113', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(642, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 142, '1.228', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(643, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 143, '0.63', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(644, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 146, '0.77', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(645, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 148, '2.205', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(646, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 149, '1.52205', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(647, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 150, '3.23175', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(648, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 151, '2.2101', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(649, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 152, '0.29515', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(650, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 153, '2.715', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(651, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 234, '82', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(652, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 235, '48', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(653, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 236, '52', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(654, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 237, '731', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(655, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 238, '156', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(656, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 240, '294', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(657, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 241, '481', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(658, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 242, '92', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(659, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 244, '1230', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(660, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 243, '658', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(661, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 245, '60', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(662, 6, 1, 'input', '2020-12-20', '12:34:10', 7, 323, '5', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(663, 6, 1, 'output', '2020-12-20', '12:34:10', 7, 317, '26.3', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-23', '12:34:10', 10),
(665, 8, 4, 'input', '2020-12-21', '12:12:57', 1, 538, '2.8', '142.85714285714', '400', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"4\",\"content\":\"9\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '12:14:18', 1),
(666, 8, 4, 'input', '2020-12-21', '12:12:57', 1, 538, '1.5', '160', '240', NULL, '[]', '{\"type\":\"cnt\",\"quantity\":\"2\",\"content\":\"10\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '12:14:18', 1),
(667, 8, 4, 'input', '2020-12-21', '12:12:57', 1, 534, '10', '40', '400', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-21', '12:14:19', 1),
(668, 8, 4, 'output', '2020-12-25', '12:14:21', 2, 538, '0.295735', NULL, NULL, NULL, NULL, '{\"type\":\"chd\",\"quantity\":\"10\",\"child\":\"539\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-25', '12:16:38', 1),
(669, 8, 4, 'output', '2020-12-25', '12:14:21', 2, 538, '0.7', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"9\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-25', '12:16:38', 1),
(670, 8, 4, 'output', '2020-12-25', '12:14:21', 2, 538, '0.75', NULL, NULL, NULL, NULL, '{\"type\":\"cnt\",\"quantity\":\"1\",\"content\":\"10\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-25', '12:16:38', 1),
(671, 8, 4, 'output', '2020-12-25', '12:14:21', 2, 534, '4', NULL, NULL, NULL, NULL, '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2020-12-25', '12:16:38', 1),
(695, 8, 4, 'input', '2021-01-07', '18:51:52', 1, 578, '100', '100', '10000', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-07', '18:52:27', 1),
(708, 4, 8, 'input', '2021-01-18', '14:18:41', 1, 846, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:19:08', 3),
(709, 4, 8, 'input', '2021-01-18', '14:19:09', 1, 828, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:19:25', 3),
(710, 4, 8, 'input', '2021-01-18', '14:19:26', 1, 583, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:19:38', 3),
(712, 4, 8, 'input', '2021-01-18', '14:19:50', 1, 581, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:19:59', 3),
(713, 4, 8, 'input', '2021-01-18', '14:20:00', 1, 588, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:20:09', 3),
(714, 4, 8, 'input', '2021-01-18', '14:20:10', 1, 610, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '14:20:31', 3),
(717, 4, 8, 'input', '2021-01-18', '15:09:45', 1, 642, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:10:11', 3),
(718, 4, 8, 'input', '2021-01-18', '15:11:23', 1, 863, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:11:36', 3),
(719, 4, 8, 'input', '2021-01-18', '15:11:37', 1, 657, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:12:18', 3),
(720, 4, 8, 'input', '2021-01-18', '15:12:19', 1, 656, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:12:30', 3),
(721, 4, 8, 'input', '2021-01-18', '15:14:03', 1, 864, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:14:44', 3),
(722, 4, 8, 'input', '2021-01-18', '15:14:45', 1, 806, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:15:14', 3),
(723, 4, 8, 'input', '2021-01-18', '15:15:14', 1, 856, '5', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:16:19', 3),
(724, 4, 8, 'input', '2021-01-18', '15:17:04', 1, 855, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:17:15', 3),
(725, 4, 8, 'input', '2021-01-18', '15:27:23', 1, 866, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:27:40', 3),
(726, 4, 8, 'input', '2021-01-18', '15:27:41', 1, 681, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:29:29', 3),
(727, 4, 8, 'input', '2021-01-18', '15:29:30', 1, 639, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:30:28', 3),
(728, 4, 8, 'input', '2021-01-18', '15:30:29', 1, 671, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:30:59', 3),
(729, 4, 8, 'input', '2021-01-18', '15:31:00', 1, 775, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:34:03', 3),
(730, 4, 8, 'input', '2021-01-18', '15:34:04', 1, 682, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:34:46', 3),
(731, 4, 8, 'input', '2021-01-18', '15:45:40', 1, 660, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:45:56', 3),
(732, 4, 8, 'input', '2021-01-18', '15:45:57', 1, 661, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:46:16', 3),
(733, 4, 8, 'input', '2021-01-18', '15:46:17', 1, 680, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:46:40', 3),
(734, 4, 8, 'input', '2021-01-18', '15:46:40', 1, 678, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:50:13', 3),
(735, 4, 8, 'input', '2021-01-18', '15:50:13', 1, 677, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:50:28', 3),
(736, 4, 8, 'input', '2021-01-18', '15:50:29', 1, 670, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:50:48', 3),
(737, 4, 8, 'input', '2021-01-18', '15:50:49', 1, 680, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '15:51:00', 3),
(739, 4, 8, 'input', '2021-01-18', '15:51:20', 1, 658, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:02:44', 3),
(740, 4, 8, 'input', '2021-01-18', '16:04:37', 1, 663, '7', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:04:47', 3),
(741, 4, 8, 'input', '2021-01-18', '16:04:47', 1, 664, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:05:00', 3),
(742, 4, 8, 'input', '2021-01-18', '16:05:01', 1, 662, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:05:13', 3),
(743, 4, 8, 'input', '2021-01-18', '16:05:14', 1, 665, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:05:39', 3),
(744, 4, 8, 'input', '2021-01-18', '16:05:39', 1, 663, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:07:46', 3),
(745, 4, 8, 'input', '2021-01-18', '16:07:46', 1, 659, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:08:07', 3),
(746, 4, 8, 'input', '2021-01-18', '16:08:08', 1, 655, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-18', '16:08:20', 3),
(747, 4, 8, 'input', '2021-01-21', '18:27:34', 1, 638, '7', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:28:26', 3),
(748, 4, 8, 'input', '2021-01-21', '18:28:26', 1, 640, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:34:01', 3),
(749, 4, 8, 'input', '2021-01-21', '18:36:18', 1, 742, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:36:49', 3),
(750, 4, 8, 'input', '2021-01-21', '18:36:49', 1, 629, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:37:32', 3),
(751, 4, 8, 'input', '2021-01-21', '18:37:32', 1, 633, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:37:52', 3),
(752, 4, 8, 'input', '2021-01-21', '18:37:53', 1, 829, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '18:38:03', 3),
(753, 4, 8, 'input', '2021-01-21', '18:59:55', 1, 690, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:00:14', 3),
(754, 4, 8, 'input', '2021-01-21', '19:00:14', 1, 738, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:00:39', 3),
(755, 4, 8, 'input', '2021-01-21', '19:00:40', 1, 759, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:01:26', 3),
(756, 4, 8, 'input', '2021-01-21', '19:01:27', 1, 601, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:01:45', 3),
(758, 4, 8, 'input', '2021-01-21', '19:02:13', 1, 580, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:02:24', 3),
(759, 4, 8, 'input', '2021-01-21', '19:02:25', 1, 599, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:02:36', 3),
(761, 4, 8, 'input', '2021-01-21', '19:03:04', 1, 602, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:03:16', 3),
(762, 4, 8, 'input', '2021-01-21', '19:03:17', 1, 582, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:03:29', 3),
(763, 4, 8, 'input', '2021-01-21', '19:03:30', 1, 581, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:03:41', 3),
(764, 4, 8, 'input', '2021-01-21', '19:03:42', 1, 805, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:03:54', 3),
(766, 4, 8, 'input', '2021-01-21', '19:04:19', 1, 618, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:04:30', 3),
(767, 4, 8, 'input', '2021-01-21', '19:04:31', 1, 619, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:04:51', 3),
(768, 4, 8, 'input', '2021-01-21', '19:04:51', 1, 621, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:05:05', 3),
(769, 4, 8, 'input', '2021-01-21', '19:05:05', 1, 620, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:05:27', 3),
(770, 4, 8, 'input', '2021-01-21', '19:05:28', 1, 624, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:05:41', 3),
(771, 4, 8, 'input', '2021-01-21', '19:05:42', 1, 625, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:06:11', 3),
(772, 4, 8, 'input', '2021-01-21', '19:06:12', 1, 856, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:06:28', 3),
(773, 4, 8, 'input', '2021-01-21', '19:06:29', 1, 774, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:06:41', 3),
(776, 4, 8, 'input', '2021-01-21', '19:08:00', 1, 628, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:08:11', 3),
(777, 4, 8, 'input', '2021-01-21', '19:08:12', 1, 630, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:08:31', 3),
(778, 4, 8, 'input', '2021-01-21', '19:08:32', 1, 838, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:08:41', 3),
(779, 4, 8, 'input', '2021-01-21', '19:08:43', 1, 631, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:08:57', 3),
(781, 4, 8, 'input', '2021-01-21', '19:09:07', 1, 638, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:09:23', 3),
(782, 4, 8, 'input', '2021-01-21', '19:09:24', 1, 637, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:09:34', 3),
(783, 4, 8, 'input', '2021-01-21', '19:09:34', 1, 648, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:10:01', 3),
(784, 4, 8, 'input', '2021-01-21', '19:10:02', 1, 649, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:10:29', 3),
(785, 4, 8, 'input', '2021-01-21', '19:10:29', 1, 650, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:10:44', 3),
(786, 4, 8, 'input', '2021-01-21', '19:10:45', 1, 654, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:11:06', 3),
(787, 4, 8, 'input', '2021-01-21', '19:11:07', 1, 653, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:11:16', 3),
(788, 4, 8, 'input', '2021-01-21', '19:14:15', 1, 586, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:14:31', 3),
(789, 4, 8, 'input', '2021-01-21', '19:14:32', 1, 700, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:14:57', 3),
(790, 4, 8, 'input', '2021-01-21', '19:14:58', 1, 750, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:15:08', 3),
(791, 4, 8, 'input', '2021-01-21', '19:15:08', 1, 701, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:15:20', 3),
(792, 4, 8, 'input', '2021-01-21', '19:22:48', 1, 875, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:23:15', 3),
(793, 4, 8, 'input', '2021-01-21', '19:23:16', 1, 792, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:23:44', 3),
(794, 4, 8, 'input', '2021-01-21', '19:23:44', 1, 806, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:23:54', 3),
(795, 4, 8, 'input', '2021-01-21', '19:23:55', 1, 793, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:24:27', 3),
(796, 4, 8, 'input', '2021-01-21', '19:24:28', 1, 703, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:25:22', 3),
(799, 4, 8, 'input', '2021-01-21', '19:26:14', 1, 695, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:26:31', 3),
(800, 4, 8, 'input', '2021-01-21', '19:26:31', 1, 699, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:26:44', 3),
(801, 4, 8, 'input', '2021-01-21', '19:26:44', 1, 586, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:26:58', 3),
(802, 4, 8, 'input', '2021-01-21', '19:26:59', 1, 705, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:27:56', 3),
(803, 4, 8, 'input', '2021-01-21', '19:27:57', 1, 708, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:28:08', 3),
(804, 4, 8, 'input', '2021-01-21', '19:28:09', 1, 706, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:28:57', 3),
(805, 4, 8, 'input', '2021-01-21', '19:28:57', 1, 707, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:29:10', 3),
(806, 4, 8, 'input', '2021-01-21', '19:29:11', 1, 714, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:29:31', 3),
(807, 4, 8, 'input', '2021-01-21', '19:29:32', 1, 710, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:29:58', 3),
(808, 4, 8, 'input', '2021-01-21', '19:29:59', 1, 730, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:30:16', 3),
(809, 4, 8, 'input', '2021-01-21', '19:30:17', 1, 839, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:30:38', 3),
(810, 4, 8, 'input', '2021-01-21', '19:30:38', 1, 733, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:30:56', 3),
(811, 4, 8, 'input', '2021-01-21', '19:30:56', 1, 870, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:31:13', 3),
(812, 4, 8, 'input', '2021-01-21', '19:31:13', 1, 741, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:31:25', 3),
(813, 4, 8, 'input', '2021-01-21', '19:31:25', 1, 844, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:31:45', 3),
(814, 4, 8, 'input', '2021-01-21', '19:31:46', 1, 704, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:32:35', 3),
(816, 4, 8, 'input', '2021-01-21', '19:33:07', 1, 692, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:34:20', 3),
(817, 4, 8, 'input', '2021-01-21', '19:34:21', 1, 744, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:35:14', 3),
(818, 4, 8, 'input', '2021-01-21', '19:35:15', 1, 823, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:35:35', 3),
(819, 4, 8, 'input', '2021-01-21', '19:35:36', 1, 820, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:36:20', 3),
(820, 4, 8, 'input', '2021-01-21', '19:36:21', 1, 795, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:36:42', 3),
(821, 4, 8, 'input', '2021-01-21', '19:36:42', 1, 754, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:37:03', 3),
(822, 4, 8, 'input', '2021-01-21', '19:37:03', 1, 743, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:37:39', 3),
(824, 4, 8, 'input', '2021-01-21', '19:38:03', 1, 751, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:38:23', 3),
(825, 4, 8, 'input', '2021-01-21', '19:38:23', 1, 686, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:39:58', 3),
(826, 4, 8, 'input', '2021-01-21', '19:39:58', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:46:55', 3),
(827, 4, 6, 'input', '2021-01-21', '19:50:18', 1, 759, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:50:36', 3),
(828, 4, 6, 'input', '2021-01-21', '19:50:36', 1, 784, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:50:55', 3),
(829, 4, 6, 'input', '2021-01-21', '19:50:56', 1, 785, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:51:07', 3),
(831, 4, 6, 'input', '2021-01-21', '19:51:22', 1, 746, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:51:36', 3),
(832, 4, 6, 'input', '2021-01-21', '19:51:37', 1, 772, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:51:56', 3),
(833, 4, 6, 'input', '2021-01-21', '19:51:57', 1, 828, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:52:14', 3),
(834, 4, 6, 'input', '2021-01-21', '19:52:15', 1, 740, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:52:33', 3),
(835, 4, 6, 'input', '2021-01-21', '19:52:34', 1, 824, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:52:45', 3),
(836, 4, 6, 'input', '2021-01-21', '19:52:46', 1, 823, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:52:53', 3),
(837, 4, 6, 'input', '2021-01-21', '19:55:20', 1, 877, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:55:29', 3),
(838, 4, 6, 'input', '2021-01-21', '19:55:30', 1, 827, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:55:48', 3),
(839, 4, 6, 'input', '2021-01-21', '19:55:48', 1, 599, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:56:14', 3),
(840, 4, 6, 'input', '2021-01-21', '19:56:14', 1, 596, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:56:28', 3),
(841, 4, 6, 'input', '2021-01-21', '19:56:29', 1, 598, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:56:40', 3),
(842, 4, 6, 'input', '2021-01-21', '19:59:21', 1, 593, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '19:59:30', 3),
(843, 4, 6, 'input', '2021-01-21', '19:59:31', 1, 580, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:00:03', 3),
(844, 4, 6, 'input', '2021-01-21', '20:01:35', 1, 878, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:01:42', 3),
(845, 4, 6, 'input', '2021-01-21', '20:01:43', 1, 602, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:01:55', 3),
(846, 4, 6, 'input', '2021-01-21', '20:01:56', 1, 605, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:02:06', 3),
(847, 4, 6, 'input', '2021-01-21', '20:02:07', 1, 584, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:02:18', 3),
(848, 4, 6, 'input', '2021-01-21', '20:02:18', 1, 583, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:02:32', 3),
(849, 4, 6, 'input', '2021-01-21', '20:02:33', 1, 602, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:02:50', 3),
(850, 4, 6, 'input', '2021-01-21', '20:02:51', 1, 581, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:03:01', 3),
(851, 4, 6, 'input', '2021-01-21', '20:03:02', 1, 574, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:03:22', 3),
(852, 4, 6, 'input', '2021-01-21', '20:03:22', 1, 582, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:03:31', 3),
(853, 4, 6, 'input', '2021-01-21', '20:03:32', 1, 582, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:03:47', 3),
(854, 4, 6, 'input', '2021-01-21', '20:03:48', 1, 737, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:04:31', 3),
(855, 4, 6, 'input', '2021-01-21', '20:04:32', 1, 697, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:05:16', 3),
(856, 4, 6, 'input', '2021-01-21', '20:04:32', 1, 617, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:05:16', 3),
(859, 4, 6, 'input', '2021-01-21', '20:05:16', 1, 585, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:06:38', 3),
(860, 4, 6, 'input', '2021-01-21', '20:05:16', 1, 586, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:06:38', 3),
(861, 4, 6, 'input', '2021-01-21', '20:06:39', 1, 686, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:07:10', 3),
(862, 4, 6, 'input', '2021-01-21', '20:07:11', 1, 846, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:08:47', 3),
(864, 4, 6, 'input', '2021-01-21', '20:08:48', 1, 807, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:09:31', 3),
(865, 4, 6, 'input', '2021-01-21', '20:09:32', 1, 708, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:10:15', 3),
(866, 4, 6, 'input', '2021-01-21', '20:09:32', 1, 707, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:10:15', 3),
(867, 4, 6, 'input', '2021-01-21', '20:09:32', 1, 706, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:10:15', 3),
(868, 4, 6, 'input', '2021-01-21', '20:09:32', 1, 705, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:10:15', 3),
(870, 4, 6, 'input', '2021-01-21', '20:10:38', 1, 831, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:10:53', 3),
(872, 4, 6, 'input', '2021-01-21', '20:11:14', 1, 875, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:11:30', 3),
(873, 4, 6, 'input', '2021-01-21', '20:11:31', 1, 711, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:11:41', 3),
(874, 4, 6, 'input', '2021-01-21', '20:11:42', 1, 776, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:12:34', 3),
(875, 4, 6, 'input', '2021-01-21', '20:11:42', 1, 777, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:12:34', 3),
(876, 4, 6, 'input', '2021-01-21', '20:11:42', 1, 778, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:12:34', 3),
(877, 4, 6, 'input', '2021-01-21', '20:12:35', 1, 719, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:13:31', 3),
(878, 4, 6, 'input', '2021-01-21', '20:12:35', 1, 718, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:13:31', 3),
(879, 4, 6, 'input', '2021-01-21', '20:12:35', 1, 783, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:13:31', 3),
(880, 4, 6, 'input', '2021-01-21', '20:12:35', 1, 627, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:13:31', 3),
(882, 4, 6, 'input', '2021-01-21', '20:12:35', 1, 780, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:13:31', 3);
INSERT INTO `inventories` (`id`, `account`, `branch`, `movement`, `date`, `hour`, `type`, `product`, `quantity`, `cost`, `total`, `location`, `categories`, `origin`, `provider`, `bill`, `transfer`, `created_date`, `created_hour`, `created_user`) VALUES
(883, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 750, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(884, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 773, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(885, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 701, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(886, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(888, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 782, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(889, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 631, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(891, 4, 6, 'input', '2021-01-21', '20:13:32', 1, 764, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:15:14', 3),
(892, 4, 6, 'input', '2021-01-21', '20:15:15', 1, 771, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:16:52', 3),
(893, 4, 6, 'input', '2021-01-21', '20:15:15', 1, 765, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:16:52', 3),
(894, 4, 6, 'input', '2021-01-21', '20:15:15', 1, 766, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:16:52', 3),
(895, 4, 6, 'input', '2021-01-21', '20:15:15', 1, 853, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:16:52', 3),
(896, 4, 6, 'input', '2021-01-21', '20:16:53', 1, 757, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:17:12', 3),
(897, 4, 6, 'input', '2021-01-21', '20:17:13', 1, 686, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:17:57', 3),
(898, 4, 6, 'input', '2021-01-21', '20:17:13', 1, 692, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:17:57', 3),
(899, 4, 6, 'input', '2021-01-21', '20:17:58', 1, 677, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:19:51', 3),
(900, 4, 6, 'input', '2021-01-21', '20:17:58', 1, 678, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:19:51', 3),
(901, 4, 6, 'input', '2021-01-21', '20:17:58', 1, 680, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:19:51', 3),
(902, 4, 6, 'input', '2021-01-21', '20:20:07', 1, 879, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:20:35', 3),
(903, 4, 6, 'input', '2021-01-21', '20:20:35', 1, 663, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:21:49', 3),
(904, 4, 6, 'input', '2021-01-21', '20:20:35', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:21:49', 3),
(905, 4, 6, 'input', '2021-01-21', '20:20:35', 1, 662, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:21:49', 3),
(906, 4, 6, 'input', '2021-01-21', '20:23:55', 1, 880, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:24:07', 3),
(907, 4, 6, 'input', '2021-01-21', '20:24:08', 1, 856, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:25:16', 3),
(908, 4, 6, 'input', '2021-01-21', '20:25:17', 1, 855, '9', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:26:37', 3),
(909, 4, 6, 'input', '2021-01-21', '20:26:37', 1, 682, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:29:42', 3),
(910, 4, 6, 'input', '2021-01-21', '20:26:37', 1, 684, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:29:42', 3),
(911, 4, 6, 'input', '2021-01-21', '20:29:43', 1, 662, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:30:04', 3),
(912, 4, 6, 'input', '2021-01-21', '20:30:05', 1, 647, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:30:27', 3),
(913, 4, 6, 'input', '2021-01-21', '20:30:28', 1, 638, '6', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:31:40', 3),
(915, 4, 6, 'input', '2021-01-21', '20:32:20', 1, 684, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:32:56', 3),
(916, 4, 6, 'input', '2021-01-21', '20:32:57', 1, 866, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:33:07', 3),
(917, 4, 6, 'input', '2021-01-21', '20:33:07', 1, 663, '9', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:33:42', 3),
(918, 4, 6, 'input', '2021-01-21', '20:33:43', 1, 660, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:34:21', 3),
(919, 4, 6, 'input', '2021-01-21', '20:34:21', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:34:46', 3),
(920, 4, 6, 'input', '2021-01-21', '20:34:47', 1, 856, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:36:43', 3),
(921, 4, 6, 'input', '2021-01-21', '20:36:43', 1, 655, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:36:59', 3),
(922, 4, 6, 'input', '2021-01-21', '20:37:00', 1, 775, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:38:11', 3),
(923, 4, 6, 'input', '2021-01-21', '20:38:11', 1, 665, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:38:26', 3),
(924, 4, 6, 'input', '2021-01-21', '20:38:27', 1, 668, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:38:36', 3),
(925, 4, 6, 'input', '2021-01-21', '20:38:37', 1, 666, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:38:51', 3),
(926, 4, 6, 'input', '2021-01-21', '20:38:52', 1, 864, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:39:08', 3),
(927, 4, 6, 'input', '2021-01-21', '20:39:08', 1, 661, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-21', '20:39:21', 3),
(928, 4, 9, 'input', '2021-01-22', '13:48:02', 1, 583, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:08', 3),
(929, 4, 9, 'input', '2021-01-22', '13:48:02', 1, 584, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:08', 3),
(930, 4, 9, 'input', '2021-01-22', '13:48:02', 1, 628, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:08', 3),
(931, 4, 9, 'input', '2021-01-22', '13:48:02', 1, 774, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:08', 3),
(932, 4, 9, 'input', '2021-01-22', '13:48:02', 1, 621, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:08', 3),
(933, 4, 9, 'input', '2021-01-22', '13:49:09', 1, 714, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:53', 3),
(934, 4, 9, 'input', '2021-01-22', '13:49:09', 1, 713, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:49:53', 3),
(935, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 727, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(936, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 776, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(937, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 637, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(938, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 708, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(940, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 707, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(941, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 706, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(942, 4, 9, 'input', '2021-01-22', '13:49:53', 1, 797, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:51:43', 3),
(944, 4, 9, 'input', '2021-01-22', '13:51:44', 1, 588, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:52:54', 3),
(945, 4, 9, 'input', '2021-01-22', '13:51:44', 1, 785, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:52:54', 3),
(946, 4, 9, 'input', '2021-01-22', '13:51:44', 1, 786, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:52:54', 3),
(947, 4, 9, 'input', '2021-01-22', '13:51:44', 1, 789, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:52:54', 3),
(948, 4, 9, 'input', '2021-01-22', '13:51:44', 1, 784, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:52:54', 3),
(950, 4, 9, 'input', '2021-01-22', '13:52:55', 1, 696, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:53:35', 3),
(951, 4, 9, 'input', '2021-01-22', '13:53:35', 1, 804, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:53:48', 3),
(952, 4, 9, 'input', '2021-01-22', '13:53:49', 1, 803, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:54:15', 3),
(953, 4, 9, 'input', '2021-01-22', '13:54:16', 1, 630, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:54:56', 3),
(954, 4, 9, 'input', '2021-01-22', '13:55:50', 1, 881, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:56:01', 3),
(955, 4, 9, 'input', '2021-01-22', '13:56:01', 1, 587, '5', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:57:32', 3),
(957, 4, 9, 'input', '2021-01-22', '13:56:01', 1, 585, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:57:32', 3),
(958, 4, 9, 'input', '2021-01-22', '13:56:01', 1, 586, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:57:32', 3),
(959, 4, 9, 'input', '2021-01-22', '13:57:33', 1, 696, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:57:59', 3),
(960, 4, 9, 'input', '2021-01-22', '13:58:00', 1, 788, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:58:51', 3),
(961, 4, 9, 'input', '2021-01-22', '13:58:00', 1, 790, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '13:58:51', 3),
(963, 4, 9, 'input', '2021-01-22', '14:01:12', 1, 802, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:11:19', 3),
(964, 4, 9, 'input', '2021-01-22', '14:11:19', 1, 773, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:12:50', 3),
(965, 4, 9, 'input', '2021-01-22', '14:11:19', 1, 747, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:12:50', 3),
(966, 4, 9, 'input', '2021-01-22', '14:11:19', 1, 775, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:12:50', 3),
(968, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 735, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(969, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 845, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(970, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 875, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(971, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(972, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 703, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(973, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 840, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(974, 4, 9, 'input', '2021-01-22', '14:12:51', 1, 690, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:16:20', 3),
(976, 4, 9, 'input', '2021-01-22', '14:16:24', 1, 692, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:17:32', 3),
(977, 4, 9, 'input', '2021-01-22', '14:17:34', 1, 810, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:18:58', 3),
(978, 4, 9, 'input', '2021-01-22', '14:17:34', 1, 853, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:18:58', 3),
(979, 4, 9, 'input', '2021-01-22', '14:17:34', 1, 704, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:18:58', 3),
(980, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 605, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(981, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 827, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(982, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 591, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(983, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 826, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(984, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 582, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(985, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 580, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(987, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 574, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(988, 4, 9, 'input', '2021-01-22', '14:18:59', 1, 596, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:21:35', 3),
(989, 4, 9, 'input', '2021-01-22', '14:21:36', 1, 686, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:22:29', 3),
(990, 4, 9, 'input', '2021-01-22', '14:21:36', 1, 754, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:22:29', 3),
(991, 4, 9, 'input', '2021-01-22', '14:21:36', 1, 811, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:22:29', 3),
(992, 4, 9, 'input', '2021-01-22', '14:21:36', 1, 801, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:22:29', 3),
(993, 4, 9, 'input', '2021-01-22', '14:23:05', 1, 817, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:12', 3),
(994, 4, 9, 'input', '2021-01-22', '14:23:05', 1, 766, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:12', 3),
(995, 4, 9, 'input', '2021-01-22', '14:23:05', 1, 822, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:12', 3),
(996, 4, 9, 'input', '2021-01-22', '14:23:05', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:12', 3),
(997, 4, 9, 'input', '2021-01-22', '14:23:05', 1, 816, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:12', 3),
(998, 4, 9, 'input', '2021-01-22', '14:24:14', 1, 738, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:24:46', 3),
(999, 4, 9, 'input', '2021-01-22', '14:24:47', 1, 608, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:26:25', 3),
(1000, 4, 9, 'input', '2021-01-22', '14:24:47', 1, 692, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:26:25', 3),
(1001, 4, 9, 'input', '2021-01-22', '14:24:47', 1, 744, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:26:25', 3),
(1002, 4, 9, 'input', '2021-01-22', '14:26:26', 1, 742, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:26:56', 3),
(1003, 4, 9, 'input', '2021-01-22', '14:26:57', 1, 682, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:28:36', 3),
(1004, 4, 9, 'input', '2021-01-22', '14:26:57', 1, 663, '5', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:28:36', 3),
(1005, 4, 9, 'input', '2021-01-22', '14:26:57', 1, 661, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:28:36', 3),
(1006, 4, 9, 'input', '2021-01-22', '14:26:57', 1, 855, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:28:36', 3),
(1007, 4, 9, 'input', '2021-01-22', '14:26:57', 1, 662, '6', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:28:36', 3),
(1008, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 661, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1009, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1010, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 676, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1011, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 678, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1012, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 677, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1013, 4, 9, 'input', '2021-01-22', '14:28:36', 1, 680, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:29:35', 3),
(1014, 4, 9, 'input', '2021-01-22', '14:36:20', 1, 882, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:36:36', 3),
(1015, 4, 9, 'input', '2021-01-22', '14:36:37', 1, 880, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:39:26', 3),
(1017, 4, 9, 'input', '2021-01-22', '14:36:37', 1, 656, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:39:26', 3),
(1018, 4, 9, 'input', '2021-01-22', '14:39:27', 1, 664, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:40:24', 3),
(1019, 4, 9, 'input', '2021-01-22', '14:39:27', 1, 670, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:40:24', 3),
(1020, 4, 9, 'input', '2021-01-22', '14:39:27', 1, 683, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:40:24', 3),
(1021, 4, 9, 'input', '2021-01-22', '14:39:27', 1, 660, '5', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:40:24', 3),
(1022, 4, 9, 'input', '2021-01-22', '14:40:25', 1, 681, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:42:40', 3),
(1025, 4, 9, 'input', '2021-01-22', '14:40:25', 1, 855, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:42:40', 3),
(1026, 4, 9, 'input', '2021-01-22', '14:40:25', 1, 639, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:42:40', 3),
(1027, 4, 9, 'input', '2021-01-22', '14:40:25', 1, 682, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:42:40', 3),
(1028, 4, 9, 'input', '2021-01-22', '14:40:25', 1, 656, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:42:40', 3),
(1029, 4, 9, 'input', '2021-01-22', '14:42:41', 1, 671, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:46:22', 3),
(1030, 4, 9, 'input', '2021-01-22', '14:42:41', 1, 665, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:46:22', 3),
(1033, 4, 9, 'input', '2021-01-22', '14:48:18', 1, 883, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:48:31', 3),
(1034, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 710, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1035, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 721, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1036, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 724, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1037, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 725, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1038, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 838, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1039, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 832, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1040, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 628, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1041, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 868, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1042, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 622, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1043, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 630, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1044, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 783, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1045, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 827, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1047, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 582, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1048, 4, 10, 'input', '2021-01-22', '14:56:25', 1, 580, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '14:59:53', 3),
(1051, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 695, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1052, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 773, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1053, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 743, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1054, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 610, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1055, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 697, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1056, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 651, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1059, 4, 10, 'input', '2021-01-22', '14:59:53', 1, 602, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:02:57', 3),
(1060, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 869, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1061, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 813, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1062, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 819, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1063, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1064, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 686, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1065, 4, 10, 'input', '2021-01-22', '15:02:57', 1, 744, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:05:17', 3),
(1066, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 677, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1067, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 678, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1068, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 663, '6', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1069, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 664, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1070, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 657, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1071, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 684, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1072, 4, 10, 'input', '2021-01-22', '15:05:18', 1, 662, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:07:20', 3),
(1074, 4, 10, 'input', '2021-01-22', '15:07:21', 1, 660, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:08:40', 3),
(1075, 4, 10, 'input', '2021-01-22', '15:07:21', 1, 585, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:08:40', 3),
(1076, 4, 10, 'input', '2021-01-22', '15:07:21', 1, 683, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:08:40', 3),
(1077, 4, 10, 'input', '2021-01-22', '15:09:50', 1, 885, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:10:07', 3),
(1078, 4, 10, 'input', '2021-01-22', '15:10:08', 1, 639, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:10:41', 3),
(1079, 4, 10, 'input', '2021-01-22', '15:10:08', 1, 680, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:10:41', 3),
(1080, 4, 10, 'input', '2021-01-22', '15:10:42', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:11:14', 3),
(1081, 4, 10, 'input', '2021-01-22', '15:11:15', 1, 663, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:12:03', 3),
(1082, 4, 10, 'input', '2021-01-22', '15:11:15', 1, 855, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:12:03', 3),
(1083, 4, 10, 'input', '2021-01-22', '15:12:04', 1, 660, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:12:30', 3),
(1084, 4, 10, 'input', '2021-01-22', '15:12:31', 1, 825, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:13:20', 3),
(1085, 4, 10, 'input', '2021-01-22', '15:12:31', 1, 658, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:13:20', 3),
(1086, 4, 10, 'input', '2021-01-22', '15:12:31', 1, 775, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:13:20', 3),
(1087, 4, 10, 'input', '2021-01-22', '15:12:31', 1, 638, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:13:20', 3),
(1088, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 828, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1089, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 872, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1090, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 752, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1091, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 652, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1092, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 738, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1093, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 739, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1094, 4, 10, 'input', '2021-01-22', '15:13:20', 1, 634, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:15:34', 3),
(1098, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 706, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1099, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 705, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1100, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 707, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1101, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 599, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1102, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 574, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1104, 4, 10, 'input', '2021-01-22', '15:15:35', 1, 875, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:17:33', 3),
(1105, 4, 10, 'input', '2021-01-22', '15:17:34', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:20:00', 3),
(1106, 4, 10, 'input', '2021-01-22', '15:17:34', 1, 701, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:20:00', 3),
(1108, 4, 10, 'input', '2021-01-22', '15:20:01', 1, 870, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:21:28', 3),
(1109, 4, 10, 'input', '2021-01-22', '15:20:01', 1, 773, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:21:28', 3),
(1110, 4, 10, 'input', '2021-01-22', '15:20:01', 1, 700, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:21:28', 3),
(1111, 4, 10, 'input', '2021-01-22', '15:20:01', 1, 596, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:21:28', 3),
(1112, 4, 10, 'input', '2021-01-22', '15:20:01', 1, 747, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:21:28', 3),
(1113, 4, 10, 'input', '2021-01-22', '15:22:34', 1, 886, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:22:49', 3),
(1116, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 871, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1117, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 802, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1118, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 730, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1119, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 841, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1120, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 653, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1121, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 785, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1122, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 806, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1123, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 741, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1124, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 822, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1125, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 823, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1126, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 824, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1127, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 704, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1128, 4, 10, 'input', '2021-01-22', '15:22:50', 1, 854, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:26:32', 3),
(1129, 4, 10, 'input', '2021-01-22', '15:26:39', 1, 608, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:27:35', 3),
(1130, 4, 10, 'input', '2021-01-22', '15:26:39', 1, 756, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:27:35', 3),
(1132, 4, 10, 'input', '2021-01-22', '15:26:39', 1, 690, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:27:35', 3),
(1133, 4, 10, 'input', '2021-01-22', '15:26:39', 1, 691, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:27:35', 3),
(1134, 4, 10, 'input', '2021-01-22', '15:28:18', 1, 887, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:28:44', 3),
(1135, 4, 10, 'input', '2021-01-22', '15:28:45', 1, 843, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:30:00', 3),
(1136, 4, 10, 'input', '2021-01-22', '15:28:45', 1, 644, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:30:00', 3),
(1137, 4, 10, 'input', '2021-01-22', '15:28:45', 1, 863, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:30:00', 3),
(1138, 4, 10, 'input', '2021-01-22', '15:28:45', 1, 846, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:30:00', 3),
(1139, 4, 10, 'input', '2021-01-22', '15:28:45', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:30:00', 3),
(1142, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 574, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1143, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 598, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3);
INSERT INTO `inventories` (`id`, `account`, `branch`, `movement`, `date`, `hour`, `type`, `product`, `quantity`, `cost`, `total`, `location`, `categories`, `origin`, `provider`, `bill`, `transfer`, `created_date`, `created_hour`, `created_user`) VALUES
(1144, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 826, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1146, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 708, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1147, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 706, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1148, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 705, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1149, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 707, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1150, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 637, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1151, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 712, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1152, 4, 7, 'input', '2021-01-22', '15:42:59', 1, 718, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:45:28', 3),
(1153, 4, 7, 'input', '2021-01-22', '15:46:17', 1, 888, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:46:30', 3),
(1154, 4, 7, 'input', '2021-01-22', '15:46:30', 1, 757, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:47:20', 3),
(1155, 4, 7, 'input', '2021-01-22', '15:46:30', 1, 740, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:47:20', 3),
(1156, 4, 7, 'input', '2021-01-22', '15:47:21', 1, 651, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:47:47', 3),
(1157, 4, 7, 'input', '2021-01-22', '15:47:47', 1, 686, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:48:26', 3),
(1158, 4, 7, 'input', '2021-01-22', '15:48:27', 1, 859, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:49:23', 3),
(1159, 4, 7, 'input', '2021-01-22', '15:48:27', 1, 853, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:49:23', 3),
(1160, 4, 7, 'input', '2021-01-22', '15:48:27', 1, 751, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:49:23', 3),
(1161, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 704, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1162, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 773, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1163, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 653, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1164, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 774, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1165, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 690, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1166, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 764, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1167, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 747, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1168, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 887, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1169, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 740, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1170, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 728, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1171, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 686, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1172, 4, 7, 'input', '2021-01-22', '15:49:24', 1, 860, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:09', 3),
(1173, 4, 7, 'input', '2021-01-22', '15:52:09', 1, 692, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:35', 3),
(1174, 4, 7, 'input', '2021-01-22', '15:52:09', 1, 807, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:52:35', 3),
(1175, 4, 7, 'input', '2021-01-22', '15:52:36', 1, 586, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:54:24', 3),
(1179, 4, 7, 'input', '2021-01-22', '15:52:36', 1, 791, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:54:24', 3),
(1180, 4, 7, 'input', '2021-01-22', '15:57:35', 1, 845, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:58:30', 3),
(1181, 4, 7, 'input', '2021-01-22', '15:58:31', 1, 589, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:59:53', 3),
(1182, 4, 7, 'input', '2021-01-22', '15:58:31', 1, 668, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:59:53', 3),
(1183, 4, 7, 'input', '2021-01-22', '15:58:31', 1, 588, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:59:53', 3),
(1184, 4, 7, 'input', '2021-01-22', '15:58:31', 1, 587, '9', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:59:53', 3),
(1185, 4, 7, 'input', '2021-01-22', '15:58:31', 1, 696, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '15:59:54', 3),
(1186, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 665, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1187, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 685, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1188, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 638, '10', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1189, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 681, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1190, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 682, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1191, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 662, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1192, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1193, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 660, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1194, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 647, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1195, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 668, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1196, 4, 7, 'input', '2021-01-22', '15:59:55', 1, 673, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:03:38', 3),
(1197, 4, 7, 'input', '2021-01-22', '16:03:39', 1, 645, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:04:42', 3),
(1198, 4, 7, 'input', '2021-01-22', '16:03:39', 1, 658, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:04:42', 3),
(1199, 4, 7, 'input', '2021-01-22', '16:03:39', 1, 656, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:04:42', 3),
(1200, 4, 7, 'input', '2021-01-22', '16:03:39', 1, 864, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:04:42', 3),
(1201, 4, 7, 'input', '2021-01-22', '16:04:44', 1, 855, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:06:12', 3),
(1202, 4, 7, 'input', '2021-01-22', '16:04:44', 1, 825, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:06:12', 3),
(1203, 4, 7, 'input', '2021-01-22', '16:04:44', 1, 671, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:06:12', 3),
(1204, 4, 7, 'input', '2021-01-22', '16:04:44', 1, 775, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:06:12', 3),
(1205, 4, 7, 'input', '2021-01-22', '16:06:13', 1, 608, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:06:54', 3),
(1206, 4, 7, 'input', '2021-01-22', '16:06:54', 1, 692, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:07:23', 3),
(1208, 4, 7, 'input', '2021-01-22', '16:06:54', 1, 686, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:07:23', 3),
(1209, 4, 7, 'input', '2021-01-22', '16:06:54', 1, 610, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:07:23', 3),
(1210, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 857, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1211, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 858, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1214, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 584, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1215, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 802, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1216, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 698, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1217, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 785, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1218, 4, 7, 'input', '2021-01-22', '16:07:24', 1, 788, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:09:47', 3),
(1219, 4, 7, 'input', '2021-01-22', '16:09:48', 1, 586, '6', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:10:32', 3),
(1221, 4, 7, 'input', '2021-01-22', '16:11:17', 1, 889, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:11:43', 3),
(1222, 4, 7, 'input', '2021-01-22', '16:11:44', 1, 633, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:13:44', 3),
(1223, 4, 7, 'input', '2021-01-22', '16:11:44', 1, 742, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:13:44', 3),
(1224, 4, 7, 'input', '2021-01-22', '16:11:44', 1, 663, '12', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:13:44', 3),
(1225, 4, 7, 'input', '2021-01-22', '16:13:45', 1, 662, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:17:56', 3),
(1226, 4, 7, 'input', '2021-01-22', '16:13:45', 1, 661, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:17:56', 3),
(1227, 4, 7, 'input', '2021-01-22', '16:13:45', 1, 664, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:17:56', 3),
(1228, 4, 7, 'input', '2021-01-22', '16:13:45', 1, 660, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:17:56', 3),
(1229, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 676, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1230, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 677, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1231, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 665, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1232, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 855, '7', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1233, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 856, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1234, 4, 7, 'input', '2021-01-22', '16:17:56', 1, 880, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:19:34', 3),
(1235, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 601, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1236, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 599, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1237, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 604, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1238, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 605, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1239, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 827, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1240, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 581, '4', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1241, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 582, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1242, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 706, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1243, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 707, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1244, 4, 7, 'input', '2021-01-22', '16:19:35', 1, 705, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:22:17', 3),
(1245, 4, 7, 'input', '2021-01-22', '16:22:18', 1, 708, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:24:01', 3),
(1246, 4, 7, 'input', '2021-01-22', '16:22:18', 1, 692, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:24:01', 3),
(1247, 4, 7, 'input', '2021-01-22', '16:22:18', 1, 744, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:24:01', 3),
(1248, 4, 7, 'input', '2021-01-22', '16:22:18', 1, 842, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:24:01', 3),
(1249, 4, 7, 'input', '2021-01-22', '16:22:18', 1, 854, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:24:01', 3),
(1250, 4, 7, 'input', '2021-01-22', '16:24:02', 1, 796, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:25:01', 3),
(1251, 4, 7, 'input', '2021-01-22', '16:24:02', 1, 853, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:25:01', 3),
(1252, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 773, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1253, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 849, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1254, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 622, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1255, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 774, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1256, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 705, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1257, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 706, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1258, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 765, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1259, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 738, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1260, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 830, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1261, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 628, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1262, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 819, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1263, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 583, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1264, 4, 7, 'input', '2021-01-22', '16:25:01', 1, 584, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:27:47', 3),
(1265, 4, 7, 'input', '2021-01-22', '16:27:48', 1, 850, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:28:09', 3),
(1266, 4, 7, 'input', '2021-01-22', '16:27:48', 1, 758, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:28:09', 3),
(1267, 4, 7, 'input', '2021-01-22', '16:28:10', 1, 739, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '16:28:30', 3),
(1270, 4, 10, 'input', '2021-01-22', '18:21:44', 1, 708, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-22', '18:21:55', 3),
(1272, 4, 9, 'input', '2021-01-24', '13:22:46', 1, 705, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-24', '13:22:57', 3),
(1273, 4, 10, 'input', '2021-01-24', '13:54:26', 1, 586, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-24', '13:54:47', 3),
(1274, 4, 7, 'input', '2021-01-24', '15:00:18', 1, 617, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-24', '15:00:37', 3),
(1276, 4, 10, 'input', '2021-01-27', '02:14:27', 1, 584, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:14:37', 3),
(1278, 4, 6, 'input', '2021-01-27', '02:17:17', 1, 588, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:17:29', 3),
(1279, 4, 7, 'input', '2021-01-27', '02:21:01', 1, 581, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:21:12', 3),
(1280, 4, 8, 'input', '2021-01-27', '02:24:48', 1, 827, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:24:57', 3),
(1281, 4, 8, 'input', '2021-01-27', '02:25:07', 1, 773, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:25:16', 3),
(1282, 4, 9, 'input', '2021-01-27', '02:25:45', 1, 638, '6', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-27', '02:26:01', 3),
(1283, 4, 9, 'input', '2021-01-29', '04:07:43', 1, 685, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '04:07:52', 3),
(1286, 4, 9, 'input', '2021-01-29', '04:15:19', 1, 669, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '04:15:28', 3),
(1287, 4, 9, 'input', '2021-01-29', '04:26:08', 1, 684, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '04:26:20', 3),
(1288, 4, 9, 'input', '2021-01-29', '04:26:54', 1, 581, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '04:27:06', 3),
(1289, 4, 6, 'input', '2021-01-29', '18:03:25', 1, 894, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:04:26', 3),
(1290, 4, 6, 'input', '2021-01-29', '18:03:25', 1, 893, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:04:26', 3),
(1291, 4, 6, 'input', '2021-01-29', '18:03:25', 1, 900, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:04:26', 3),
(1292, 4, 6, 'input', '2021-01-29', '18:03:25', 1, 898, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:04:26', 3),
(1293, 4, 6, 'input', '2021-01-29', '18:03:25', 1, 895, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:04:26', 3),
(1294, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 894, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1295, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 893, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1296, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 896, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1297, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 902, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1298, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 897, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1299, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 899, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1300, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 898, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1301, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 901, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1302, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 900, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1303, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 895, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1304, 4, 7, 'input', '2021-01-29', '18:05:20', 1, 891, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:06:59', 3),
(1305, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 894, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1306, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 893, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1307, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 896, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1308, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 897, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1309, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 895, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1310, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 902, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1311, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 892, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1312, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 900, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1313, 4, 8, 'input', '2021-01-29', '18:07:41', 1, 899, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:09:06', 3),
(1314, 4, 9, 'input', '2021-01-29', '18:09:44', 1, 894, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:10:25', 3),
(1315, 4, 9, 'input', '2021-01-29', '18:09:44', 1, 893, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:10:25', 3),
(1316, 4, 9, 'input', '2021-01-29', '18:09:44', 1, 901, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:10:25', 3),
(1317, 4, 9, 'input', '2021-01-29', '18:09:44', 1, 899, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:10:25', 3),
(1318, 4, 9, 'input', '2021-01-29', '18:09:44', 1, 895, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:10:25', 3),
(1319, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 893, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1320, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 896, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1321, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 897, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1322, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 895, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1323, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 894, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1324, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 902, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1325, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 901, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1326, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 898, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1327, 4, 10, 'input', '2021-01-29', '18:11:00', 1, 891, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:12:01', 3),
(1328, 4, 6, 'input', '2021-01-29', '18:13:55', 1, 608, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:14:22', 3),
(1329, 4, 7, 'input', '2021-01-29', '18:14:29', 1, 608, '3', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:14:43', 3),
(1330, 4, 8, 'input', '2021-01-29', '18:14:51', 1, 608, '2', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:15:02', 3),
(1331, 4, 10, 'input', '2021-01-29', '18:15:23', 1, 608, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:15:38', 3),
(1332, 4, 8, 'input', '2021-01-29', '18:15:44', 1, 732, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:16:18', 3),
(1333, 4, 10, 'input', '2021-01-29', '18:16:25', 1, 732, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:16:39', 3),
(1334, 4, 7, 'input', '2021-01-29', '18:16:45', 1, 732, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:16:55', 3),
(1335, 4, 7, 'input', '2021-01-29', '18:21:33', 1, 607, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:21:47', 3),
(1336, 4, 10, 'input', '2021-01-29', '18:21:56', 1, 607, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:22:06', 3),
(1337, 4, 8, 'input', '2021-01-29', '18:22:13', 1, 607, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:22:23', 3),
(1338, 4, 6, 'input', '2021-01-29', '18:23:11', 1, 826, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:23:33', 3),
(1339, 4, 10, 'input', '2021-01-29', '18:23:57', 1, 826, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:24:10', 3),
(1340, 4, 8, 'input', '2021-01-29', '18:24:25', 1, 826, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:24:50', 3),
(1341, 4, 6, 'input', '2021-01-29', '18:25:21', 1, 591, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:26:02', 3),
(1342, 4, 7, 'input', '2021-01-29', '18:26:06', 1, 592, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:26:41', 3),
(1343, 4, 7, 'input', '2021-01-29', '18:26:06', 1, 591, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:26:41', 3),
(1344, 4, 7, 'input', '2021-01-29', '18:26:06', 1, 593, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:26:41', 3),
(1345, 4, 8, 'input', '2021-01-29', '18:26:50', 1, 593, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:27:31', 3),
(1346, 4, 8, 'input', '2021-01-29', '18:26:50', 1, 594, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:27:31', 3),
(1347, 4, 8, 'input', '2021-01-29', '18:26:50', 1, 592, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:27:31', 3),
(1348, 4, 8, 'input', '2021-01-29', '18:26:50', 1, 591, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:27:31', 3),
(1349, 4, 9, 'input', '2021-01-29', '18:27:42', 1, 594, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:28:14', 3),
(1350, 4, 9, 'input', '2021-01-29', '18:27:42', 1, 593, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:28:14', 3),
(1351, 4, 10, 'input', '2021-01-29', '18:28:32', 1, 593, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:28:53', 3),
(1352, 4, 10, 'input', '2021-01-29', '18:28:32', 1, 594, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:28:53', 3),
(1353, 4, 10, 'input', '2021-01-29', '18:28:32', 1, 591, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:28:53', 3),
(1354, 4, 6, 'input', '2021-01-29', '18:31:03', 1, 905, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:31:33', 3),
(1355, 4, 7, 'input', '2021-01-29', '18:31:45', 1, 905, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:32:12', 3),
(1356, 4, 7, 'input', '2021-01-29', '18:31:45', 1, 904, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:32:12', 3),
(1357, 4, 8, 'input', '2021-01-29', '18:32:18', 1, 903, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:32:40', 3),
(1358, 4, 9, 'input', '2021-01-29', '18:32:52', 1, 904, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:33:03', 3),
(1359, 4, 10, 'input', '2021-01-29', '18:33:09', 1, 903, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:33:20', 3),
(1360, 4, 6, 'input', '2021-01-29', '18:44:31', 1, 906, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:44:42', 3),
(1361, 4, 7, 'input', '2021-01-29', '18:44:48', 1, 906, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:45:05', 3),
(1362, 4, 7, 'input', '2021-01-29', '18:44:48', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:45:05', 3),
(1364, 4, 8, 'input', '2021-01-29', '18:45:11', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:45:35', 3),
(1365, 4, 9, 'input', '2021-01-29', '18:46:07', 1, 906, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:46:27', 3),
(1366, 4, 10, 'input', '2021-01-29', '18:46:37', 1, 906, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:46:58', 3),
(1367, 4, 7, 'input', '2021-01-29', '18:47:50', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:48:02', 3),
(1368, 4, 8, 'input', '2021-01-29', '18:49:25', 1, 702, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:49:45', 3),
(1369, 4, 8, 'input', '2021-01-29', '18:49:25', 1, 906, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '18:49:45', 3),
(1370, 4, 7, 'input', '2021-01-29', '19:03:08', 1, 589, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '19:04:28', 3),
(1371, 4, 7, 'input', '2021-01-29', '19:04:28', 1, 589, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-29', '19:04:58', 3),
(1372, 4, 6, 'input', '2021-01-30', '13:15:12', 1, 800, '1', '0', '0', NULL, '[]', '{\"type\":\"unt\",\"quantity\":\"\",\"content\":\"\"}', NULL, NULL, '{\"branch\":\"\",\"parent\":\"\"}', '2021-01-30', '13:15:49', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_audits`
--

CREATE TABLE `inventories_audits` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `branch` bigint(20) NOT NULL,
  `started_date` date NOT NULL,
  `end_date` date NOT NULL,
  `physical` longtext NOT NULL,
  `products` longtext NOT NULL,
  `adjustments` longtext NOT NULL,
  `comment` text DEFAULT NULL,
  `saved` enum('adjust','draft') NOT NULL,
  `created_date` date NOT NULL,
  `created_hour` time NOT NULL,
  `created_user` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventories_audits`
--

INSERT INTO `inventories_audits` (`id`, `account`, `branch`, `started_date`, `end_date`, `physical`, `products`, `adjustments`, `comment`, `saved`, `created_date`, `created_hour`, `created_user`) VALUES
(2, 6, 3, '2020-11-23', '2020-11-29', '{\"141\":{\"product\":{\"id\":\"141\",\"type\":\"sale_menu\",\"name\":\"Botella Don Julio 70\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"808\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\"1.5\",1.05],\"content\":[\"cnt\",\"1\"]}}}}', '{\"141\":{\"theoretical\":0.753031,\"physical\":1.05,\"variation\":0.29696900000000004,\"status\":\"success\",\"comment\":\"\"}}', '[\"418\"]', NULL, 'adjust', '2020-12-03', '09:55:07', 1),
(3, 6, 3, '2020-11-01', '2020-11-30', '{\"141\":{\"product\":{\"id\":\"141\",\"type\":\"sale_menu\",\"name\":\"Botella Don Julio 70\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"808\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\"1.05\",0.735],\"content\":[\"cnt\",\"1\"]}}}}', '[]', '[]', NULL, 'adjust', '2020-12-03', '10:05:30', 1),
(5, 6, 1, '2020-12-14', '2020-12-20', '{\"234\":{\"product\":{\"id\":\"234\",\"type\":\"sale_menu\",\"name\":\"Bohemia Oscura\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"48\",\"48\"],\"content\":[]}}},\"235\":{\"product\":{\"id\":\"235\",\"type\":\"sale_menu\",\"name\":\"Bohemia Weizen\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"57\",\"57\"],\"content\":[]}}},\"236\":{\"product\":{\"id\":\"236\",\"type\":\"sale_menu\",\"name\":\"Coors Ligth\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"49\",\"49\"],\"content\":[]}}},\"245\":{\"product\":{\"id\":\"245\",\"type\":\"sale_menu\",\"name\":\"Heineken\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"48\",\"48\"],\"content\":[]}}},\"237\":{\"product\":{\"id\":\"237\",\"type\":\"sale_menu\",\"name\":\"Indio\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"160\",\"160\"],\"content\":[]}}},\"238\":{\"product\":{\"id\":\"238\",\"type\":\"sale_menu\",\"name\":\"Sol\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"72\",\"72\"],\"content\":[]}}},\"240\":{\"product\":{\"id\":\"240\",\"type\":\"sale_menu\",\"name\":\"Tecate \",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"60\",\"60\"],\"content\":[]}}},\"241\":{\"product\":{\"id\":\"241\",\"type\":\"sale_menu\",\"name\":\"Tecate Light\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"160\",\"160\"],\"content\":[]}}},\"242\":{\"product\":{\"id\":\"242\",\"type\":\"sale_menu\",\"name\":\"Amster Ultra\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"74\",\"74\"],\"content\":[]}}},\"243\":{\"product\":{\"id\":\"243\",\"type\":\"sale_menu\",\"name\":\"XX Ambar\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"172\",\"172\"],\"content\":[]}}},\"244\":{\"product\":{\"id\":\"244\",\"type\":\"sale_menu\",\"name\":\"XX Lagger\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"395\",\"395\"],\"content\":[]}}},\"323\":{\"product\":{\"id\":\"323\",\"type\":\"supply\",\"name\":\"Caguama de cerveza\",\"unity_code\":\"HY763H8O\",\"unity_name\":{\"es\":\"Piezas\",\"en\":\"Pieces\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":null,\"recipes\":null,\"formula\":null,\"inventory\":\"1\"},\"list\":{\"0-0-0-0\":{\"quantity\":[\"89\",\"89\"],\"content\":[]}}},\"139\":{\"product\":{\"id\":\"139\",\"type\":\"sale_menu\",\"name\":\"Botella 1800 Cristalino\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":[]}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\".7\",0.48999999999999994],\"content\":[\"cnt\",\"1\"]}}},\"142\":{\"product\":{\"id\":\"142\",\"type\":\"sale_menu\",\"name\":\"Botella Don Julio Blanco\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":[]}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\"3.7\",2.59],\"content\":[\"cnt\",\"1\"]}}},\"141\":{\"product\":{\"id\":\"141\",\"type\":\"sale_menu\",\"name\":\"Botella Don Julio 70\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"808\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\".35\",0.24499999999999997],\"content\":[\"cnt\",\"1\"]}}},\"143\":{\"product\":{\"id\":\"143\",\"type\":\"sale_menu\",\"name\":\"Botella Don Julio Reposado\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"702\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\"1.4\",0.9799999999999999],\"content\":[\"cnt\",\"1\"]}}},\"145\":{\"product\":{\"id\":\"145\",\"type\":\"sale_menu\",\"name\":\"Botella Herradura Blanco\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"397\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\"1.5\",1.05],\"content\":[\"cnt\",\"1\"]}}},\"146\":{\"product\":{\"id\":\"146\",\"type\":\"sale_menu\",\"name\":\"Botella Herradura Reposado\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"867\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\".35\",0.24499999999999997],\"content\":[\"cnt\",\"1\"]}}},\"148\":{\"product\":{\"id\":\"148\",\"type\":\"sale_menu\",\"name\":\"Botella Jimador Reposado\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"1\":{\"content\":{\"amount\":\"700\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"496\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"1-0-0-0\":{\"quantity\":[\".65\",0.455],\"content\":[\"cnt\",\"1\"]}}},\"149\":{\"product\":{\"id\":\"149\",\"type\":\"sale_menu\",\"name\":\"Botella Jos\\u00e9 Cuervo Especial\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"3\":{\"content\":{\"amount\":\"695\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"475\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"3-0-0-0\":{\"quantity\":[\"1.4\",0.9729999999999999],\"content\":[\"cnt\",\"3\"]}}},\"153\":{\"product\":{\"id\":\"153\",\"type\":\"sale_menu\",\"name\":\"Botella Maestro Dobel Diamante\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"2\":{\"content\":{\"amount\":\"750\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"658\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"2-0-0-0\":{\"quantity\":[\".3\",0.225],\"content\":[\"cnt\",\"2\"]}}},\"150\":{\"product\":{\"id\":\"150\",\"type\":\"sale_menu\",\"name\":\"Botella Jos\\u00e9 Cuervo Tradicional Reposado\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"3\":{\"content\":{\"amount\":\"695\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"567\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"3-0-0-0\":{\"quantity\":[\".8\",0.556],\"content\":[\"cnt\",\"3\"]}}},\"151\":{\"product\":{\"id\":\"151\",\"type\":\"sale_menu\",\"name\":\"Botella Jos\\u00e9 Cuervo Tradicional Plata\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"3\":{\"content\":{\"amount\":\"695\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":{\"amount\":\"567\",\"unity_code\":\"SHBJ9876\",\"unity_name\":{\"es\":\"Gramos\",\"en\":\"Grams\"},\"unity_system\":\"1\"}}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"3-0-0-0\":{\"quantity\":[\"1.4\",0.9729999999999999],\"content\":[\"cnt\",\"3\"]}}},\"152\":{\"product\":{\"id\":\"152\",\"type\":\"sale_menu\",\"name\":\"Botella Jos\\u00e9 Cuervo Tradicional Cristalino\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"3\":{\"content\":{\"amount\":\"695\",\"unity_code\":\"AXDE5TB2\",\"unity_name\":{\"es\":\"Mililitros\",\"en\":\"Mililiters\"},\"unity_system\":\"1\"},\"weight\":[]}},\"supplies\":[],\"recipes\":[],\"formula\":null,\"inventory\":\"1\"},\"list\":{\"3-0-0-0\":{\"quantity\":[\".5\",0.3475],\"content\":[\"cnt\",\"3\"]}}},\"317\":{\"product\":{\"id\":\"317\",\"type\":\"supply\",\"name\":\"Botella de batalla de Tequila\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\",\"contents\":{\"4\":{\"content\":{\"amount\":\"1\",\"unity_code\":\"JU76GF59\",\"unity_name\":{\"es\":\"Litros\",\"en\":\"Liters\"},\"unity_system\":\"1\"},\"weight\":[]}},\"supplies\":null,\"recipes\":null,\"formula\":null,\"inventory\":\"1\"},\"list\":{\"4-0-0-0\":{\"quantity\":[\"8.1\",8.1],\"content\":[\"cnt\",\"4\"]}}}}', '{\"145\":{\"theoretical\":0.385,\"physical\":1.05,\"variation\":0.665,\"status\":\"error\",\"comment\":\"\"},\"139\":{\"theoretical\":0.91,\"physical\":0.48999999999999994,\"variation\":0.4200000000000001,\"status\":\"error\",\"comment\":\"\"},\"141\":{\"theoretical\":1.3579999999999997,\"physical\":0.24499999999999997,\"variation\":1.1129999999999998,\"status\":\"error\",\"comment\":\"\"},\"142\":{\"theoretical\":3.818,\"physical\":2.59,\"variation\":1.2280000000000002,\"status\":\"error\",\"comment\":\"\"},\"143\":{\"theoretical\":0.3500000000000001,\"physical\":0.9799999999999999,\"variation\":0.6299999999999998,\"status\":\"success\",\"comment\":\"\"},\"146\":{\"theoretical\":1.0150000000000001,\"physical\":0.24499999999999997,\"variation\":0.7700000000000001,\"status\":\"error\",\"comment\":\"\"},\"148\":{\"theoretical\":2.6600000000000006,\"physical\":0.455,\"variation\":2.2050000000000005,\"status\":\"error\",\"comment\":\"\"},\"149\":{\"theoretical\":-0.5490500000000003,\"physical\":0.9729999999999999,\"variation\":1.5220500000000001,\"status\":\"error\",\"comment\":\"\"},\"150\":{\"theoretical\":3.7877499999999995,\"physical\":0.556,\"variation\":3.2317499999999995,\"status\":\"error\",\"comment\":\"\"},\"151\":{\"theoretical\":3.183099999999999,\"physical\":0.9729999999999999,\"variation\":2.2100999999999993,\"status\":\"error\",\"comment\":\"\"},\"152\":{\"theoretical\":0.6426499999999999,\"physical\":0.3475,\"variation\":0.29514999999999997,\"status\":\"error\",\"comment\":\"\"},\"153\":{\"theoretical\":2.94,\"physical\":0.225,\"variation\":2.715,\"status\":\"error\",\"comment\":\"\"},\"234\":{\"theoretical\":-34,\"physical\":48,\"variation\":82,\"status\":\"success\",\"comment\":\"\"},\"235\":{\"theoretical\":9,\"physical\":57,\"variation\":48,\"status\":\"success\",\"comment\":\"\"},\"236\":{\"theoretical\":-3,\"physical\":49,\"variation\":52,\"status\":\"success\",\"comment\":\"\"},\"237\":{\"theoretical\":-571,\"physical\":160,\"variation\":731,\"status\":\"success\",\"comment\":\"\"},\"238\":{\"theoretical\":-84,\"physical\":72,\"variation\":156,\"status\":\"success\",\"comment\":\"\"},\"240\":{\"theoretical\":-234,\"physical\":60,\"variation\":294,\"status\":\"success\",\"comment\":\"\"},\"241\":{\"theoretical\":-321,\"physical\":160,\"variation\":481,\"status\":\"success\",\"comment\":\"\"},\"242\":{\"theoretical\":-18,\"physical\":74,\"variation\":92,\"status\":\"success\",\"comment\":\"\"},\"244\":{\"theoretical\":-835,\"physical\":395,\"variation\":1230,\"status\":\"success\",\"comment\":\"\"},\"243\":{\"theoretical\":-486,\"physical\":172,\"variation\":658,\"status\":\"success\",\"comment\":\"\"},\"245\":{\"theoretical\":-12,\"physical\":48,\"variation\":60,\"status\":\"success\",\"comment\":\"\"},\"323\":{\"theoretical\":84,\"physical\":89,\"variation\":5,\"status\":\"success\",\"comment\":\"\"},\"317\":{\"theoretical\":34.4,\"physical\":8.1,\"variation\":26.299999999999997,\"status\":\"error\",\"comment\":\"\"}}', '[\"639\",\"640\",\"641\",\"642\",\"643\",\"644\",\"645\",\"646\",\"647\",\"648\",\"649\",\"650\",\"651\",\"652\",\"653\",\"654\",\"655\",\"656\",\"657\",\"658\",\"659\",\"660\",\"661\",\"662\",\"663\"]', NULL, 'adjust', '2020-12-23', '12:05:50', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_categories`
--

CREATE TABLE `inventories_categories` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `level` text NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventories_categories`
--

INSERT INTO `inventories_categories` (`id`, `account`, `name`, `level`, `blocked`) VALUES
(1, 8, 'Alimentos', '1', 0),
(2, 8, 'Bebidas', '1', 0),
(3, 6, 'Alimentos ', '1', 0),
(4, 6, 'Bebidas', '2', 0),
(5, 6, 'Suministros de Costo', '3', 0),
(6, 6, 'Suministros de limpieza', '6', 0),
(7, 8, 'Oficina', '1', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_locations`
--

CREATE TABLE `inventories_locations` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_periods`
--

CREATE TABLE `inventories_periods` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `branch` bigint(20) NOT NULL,
  `started_date` date NOT NULL,
  `end_date` date NOT NULL,
  `physical` longtext NOT NULL,
  `products` longtext NOT NULL,
  `last` tinyint(1) NOT NULL,
  `previous` int(11) DEFAULT NULL,
  `initials` longtext NOT NULL,
  `saved` enum('closed','draft') NOT NULL,
  `created_date` date NOT NULL,
  `created_hour` time NOT NULL,
  `created_user` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_transfers`
--

CREATE TABLE `inventories_transfers` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `output_branch` bigint(20) NOT NULL,
  `input_branch` bigint(20) NOT NULL,
  `products` longtext NOT NULL,
  `status` enum('pending','success','rejected','canceled') NOT NULL,
  `created_date` date NOT NULL,
  `created_hour` time NOT NULL,
  `created_user` bigint(20) NOT NULL,
  `success_date` date DEFAULT NULL,
  `success_hour` time DEFAULT NULL,
  `success_user` bigint(20) DEFAULT NULL,
  `rejected_date` date DEFAULT NULL,
  `rejected_hour` time DEFAULT NULL,
  `rejected_user` bigint(20) DEFAULT NULL,
  `cancel_date` date DEFAULT NULL,
  `cancel_hour` time DEFAULT NULL,
  `cancel_user` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventories_transfers`
--

INSERT INTO `inventories_transfers` (`id`, `account`, `output_branch`, `input_branch`, `products`, `status`, `created_date`, `created_hour`, `created_user`, `success_date`, `success_hour`, `success_user`, `rejected_date`, `rejected_hour`, `rejected_user`, `cancel_date`, `cancel_hour`, `cancel_user`) VALUES
(2, 6, 17, 14, '{\"572\":{\"product\":{\"id\":\"572\",\"type\":\"supply\",\"name\":\"Kastak\'an \",\"unity_code\":\"MKJHTYIA\",\"unity_name\":{\"es\":\"Kilogramos\",\"en\":\"Kilograms\"},\"unity_system\":\"1\",\"contents\":[],\"supplies\":null,\"recipes\":null,\"formula\":null,\"inventory\":\"1\"},\"list\":[{\"quantity\":[\"1\",\"1\"],\"content\":[]}],\"supplies\":[],\"recipes\":[]}}', 'pending', '2021-01-05', '10:59:33', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories_types`
--

CREATE TABLE `inventories_types` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) DEFAULT NULL,
  `name` text NOT NULL,
  `movement` enum('input','output') DEFAULT NULL,
  `order` text DEFAULT NULL,
  `system` tinyint(1) NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventories_types`
--

INSERT INTO `inventories_types` (`id`, `account`, `name`, `movement`, `order`, `system`, `blocked`) VALUES
(1, NULL, '{\"es\":\"Compra\",\"en\":\"Purchase\"}', 'input', '1', 1, 0),
(2, NULL, '{\"es\":\"Venta\",\"en\":\"Sale\"}', 'output', '1', 1, 0),
(3, NULL, '{\"es\":\"Merma o pérdida\",\"en\":\"Decrease or lost\"}', 'output', '2', 1, 0),
(4, NULL, '{\"es\":\"Traspaso\",\"en\":\"Transfer\"}', NULL, NULL, 1, 0),
(5, NULL, '{\"es\":\"Devolución al proveedor\",\"en\":\"Return to supplier\"}', 'output', '3', 1, 0),
(6, NULL, '{\"es\":\"Devolución del cliente\",\"en\":\"Return of client\"}', 'input', '2', 1, 0),
(7, NULL, '{\"es\":\"Ajuste\",\"en\":\"Adjustment\"}', NULL, NULL, 1, 0),
(8, NULL, '{\"es\":\"Cortesía\",\"en\":\"Courtesy\"}', 'output', '4', 1, 0),
(9, NULL, '{\"es\":\"Inicial\",\"en\":\"Initial\"}', 'input', '3', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `type` enum('sale_menu','supply','recipe','work_material') NOT NULL,
  `avatar` text DEFAULT NULL,
  `name` text NOT NULL,
  `token` text NOT NULL,
  `inventory` tinyint(1) NOT NULL,
  `unity` bigint(20) DEFAULT NULL,
  `price` text DEFAULT NULL,
  `portion` text DEFAULT NULL,
  `formula` text DEFAULT NULL,
  `contents` text DEFAULT NULL,
  `supplies` text DEFAULT NULL,
  `categories` text NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `account`, `type`, `avatar`, `name`, `token`, `inventory`, `unity`, `price`, `portion`, `formula`, `contents`, `supplies`, `categories`, `blocked`) VALUES
(1, 6, 'sale_menu', NULL, 'Aros de cebolla', 'AAPS24GW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(2, 6, 'sale_menu', NULL, 'Esquite', 'UU9TLSNU', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"486\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"301\":{\"quantity\":\"0.180\",\"unity\":\"2\"},\"362\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"282\":{\"quantity\":\"0.010\",\"unity\":\"2\"}}', '[]', 0),
(3, 6, 'sale_menu', NULL, 'Palomitas de pollo', '2M4FD9BX', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(4, 6, 'sale_menu', NULL, 'Papas a la francesa', '5ZDWFCR4', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"519\":{\"quantity\":\"0.120\",\"unity\":\"2\"}}', '[]', 0),
(5, 6, 'sale_menu', NULL, 'Papas gajo', '4AW7GKVN', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"520\":{\"quantity\":\"0.120\",\"unity\":\"2\"}}', '[]', 0),
(6, 6, 'sale_menu', NULL, 'Verduras con chile', 'DPSUUYCJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(7, 6, 'sale_menu', NULL, 'Taco de camarón capeado', '5AO2XTMR', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(8, 6, 'sale_menu', NULL, 'Taco de arrachera', '7FRLNWR9', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(9, 6, 'sale_menu', NULL, 'Taco de carnitas', 'T0937YK5', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(10, 6, 'sale_menu', NULL, 'Taco de chicharron', 'TRILIZHL', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(11, 6, 'sale_menu', NULL, 'Taco de chorizo con papas', 'OFANGVS9', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(12, 6, 'sale_menu', NULL, 'Taco de cochinita', 'BECPTLQX', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(13, 6, 'sale_menu', NULL, 'Taco de lechon', 'YXBGXLAC', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(14, 6, 'sale_menu', NULL, 'Taco de pastor', '8AYKTCPJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(15, 6, 'sale_menu', NULL, 'Taco de pescado capeado', 'Z9XWVXTL', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(16, 6, 'sale_menu', NULL, 'Taco de tinga de pollo', 'Q3W2S5FK', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(17, 6, 'sale_menu', NULL, 'Taco de tinga de res', 'D7HV2YPO', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(18, 6, 'sale_menu', NULL, 'Sope de arrachera', 'WXDRDILW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(19, 6, 'sale_menu', NULL, 'Sope de carnitas', '9QFGLZ8P', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(20, 6, 'sale_menu', NULL, 'Sope de chicharron', 'PZNE5A9X', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(21, 6, 'sale_menu', NULL, 'Sope de chorizo con papas', 'DF5ACIKI', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(22, 6, 'sale_menu', NULL, 'Sope de cochinita', 'G1OEH2UR', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(23, 6, 'sale_menu', NULL, 'Sope de tinga de pollo', '289PEXFB', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(24, 6, 'sale_menu', NULL, 'Sope de tinga de res', 'U37TICYV', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(25, 6, 'sale_menu', NULL, 'Sope de pastor', 'BWRTIOFT', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(26, 6, 'sale_menu', NULL, 'Tostada de aguachile de camarón', '42G2RDGA', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(27, 6, 'sale_menu', NULL, 'Tostada de atún oriental', 'M9NTNL0H', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(28, 6, 'sale_menu', NULL, 'Tostada de ceviche de camarón', 'MITFSKA2', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(29, 6, 'sale_menu', NULL, 'Tostada de ceviche de pescado', 'UXKOR6WA', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(30, 6, 'sale_menu', NULL, 'Tostada de cochinita', 'QVOWXHCF', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(31, 6, 'sale_menu', NULL, 'Tostada de tinga de pollo', '3M29PAC0', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(32, 6, 'sale_menu', NULL, 'Tostada de tinga de res', '3XR2KVNA', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(33, 6, 'sale_menu', NULL, 'Caldo de camarón', 'HBXMJHPG', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(34, 6, 'sale_menu', NULL, 'Consomé de pollo', 'RRSJJ5GS', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(35, 6, 'sale_menu', NULL, 'Pescadillas', 'BP7WERUO', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(36, 6, 'sale_menu', NULL, 'Taco de lechuga con aguachile de camarón', 'VDMOSTVM', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(37, 6, 'sale_menu', NULL, 'Taco de lechuga con arrachera', '5PQAJOTW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(38, 6, 'sale_menu', NULL, 'Taco de lechuga con atún oriental', 'UUO07CBX', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(39, 6, 'sale_menu', NULL, 'Taco de lechuga con ceviche de camarón', 'KAO7XXHI', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(40, 6, 'sale_menu', NULL, 'Taco de lechuga con ceviche de pescado', 'A2ZCJFVH', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(41, 6, 'sale_menu', NULL, 'Flatas de chicharron', 'GQLRLMKJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(42, 6, 'sale_menu', NULL, 'Flautas de chorizo con papas', 'YJGVZV5H', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(43, 6, 'sale_menu', NULL, 'Flautas de cochinita', 'ODFMBNOE', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(44, 6, 'sale_menu', NULL, 'Flautas de frijol', 'COCUJ8NY', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(45, 6, 'sale_menu', NULL, 'Flautas de queso', '0DLA6BZS', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(46, 6, 'sale_menu', NULL, 'Flautas de tinga de pollo', '3SMBX7KA', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(47, 6, 'sale_menu', NULL, 'Empanadas de tinga de res', '5H3CKZQN', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(48, 6, 'sale_menu', NULL, 'Empanadas de chorizo con papas', 'RTIUURGY', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(49, 6, 'sale_menu', NULL, 'Empanadas de cochinitas', 'DARCLXRV', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(50, 6, 'sale_menu', NULL, 'Empanadas de frijo', '0TTG32HU', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(51, 6, 'sale_menu', NULL, 'Empanadas de queso', 'GAT1YUSP', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(52, 6, 'sale_menu', NULL, 'Empanadas de tinga de pollo', 'BRL3MVWW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(53, 6, 'sale_menu', NULL, 'Flautas de tinga de res', 'VTNCRCKJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(54, 6, 'sale_menu', NULL, 'Tortita de arrachera', '3BMCJ9AH', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(55, 6, 'sale_menu', NULL, 'Tortita de cochinita', 'KFLDQRWZ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(56, 6, 'sale_menu', NULL, 'Tortita de lechon', 'IMHPWGWQ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(57, 6, 'sale_menu', NULL, 'Hamburguesa BBQ', 'W8DRCHDS', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(58, 6, 'sale_menu', NULL, 'Hamburguesa de camarón', 'MDTSWTAP', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(59, 6, 'sale_menu', NULL, 'Hamburguesa de res', 'VZCCEYJE', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(60, 6, 'sale_menu', NULL, 'Hamburguesa nacional', 'MBCYJG3S', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(61, 6, 'sale_menu', NULL, 'Arroz con leche', '2GTBFXMV', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(62, 6, 'sale_menu', NULL, 'Flan casero', 'THIGXJKT', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(63, 6, 'sale_menu', NULL, 'Gordita de chicharron en salsa verde', 'APNLILRJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(64, 6, 'sale_menu', NULL, 'Gordita de chorizo con papas', 'MDG8QED6', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(65, 6, 'sale_menu', NULL, 'Gordita de cochinita', 'KOLPKYA4', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(66, 6, 'sale_menu', NULL, 'Gordita de frijol', 'MJMGLX0N', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(67, 6, 'sale_menu', NULL, 'Gordita de queso', 'J5RJVULR', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(68, 6, 'sale_menu', NULL, 'Gordita de tinga de pollo', 'HWR5YCR0', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(69, 6, 'sale_menu', NULL, 'Gordita de tinga de res', 'TNO09WWR', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(70, 6, 'sale_menu', NULL, 'Alitas de pollo BBQ', 'QAIG788F', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', NULL, '{\"348\":{\"quantity\":\"10\",\"unity\":\"7\"},\"261\":{\"quantity\":\"160\",\"unity\":\"3\"}}', '[]', 0),
(71, 6, 'sale_menu', NULL, 'Alitas de pollo búfalo', 'RK3W4HG0', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"261\":{\"quantity\":\"3\",\"unity\":\"2\"}}', '[]', 0),
(72, 6, 'sale_menu', NULL, 'Alitas de pollo naturales', 'J97FGPZB', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"261\":{\"quantity\":\"3\",\"unity\":\"2\"}}', '[]', 0),
(73, 6, 'sale_menu', NULL, 'Chelada', 'WRKMA5BB', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(74, 6, 'sale_menu', NULL, 'Chelada nacional', 'MGPPG1OG', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(75, 6, 'sale_menu', NULL, 'Michelada', 'LSFAHPVJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(76, 6, 'sale_menu', NULL, 'Michelada de fresa', 'ILGGPCOC', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(77, 6, 'sale_menu', NULL, 'Michelada de guanabana', 'BFPNTCGE', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(78, 6, 'sale_menu', NULL, 'Michelada de mago', 'VRMA4PNI', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(79, 6, 'sale_menu', NULL, 'Michelada de maracuya', 'M8PG8KDG', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(80, 6, 'sale_menu', NULL, 'Michelada de tamarindo', '0SPCDYHB', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(81, 6, 'sale_menu', NULL, 'Michelada nacional', 'OU58TIST', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(82, 6, 'sale_menu', NULL, 'Ojo rojo', '4GTA8J1H', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '{\"323\":{\"quantity\":\"3\",\"unity\":\"2\"},\"341\":{\"quantity\":\"1\",\"unity\":\"2\"},\"331\":{\"quantity\":\"3\",\"unity\":\"2\"},\"339\":{\"quantity\":\"1\",\"unity\":\"2\"}}', '[]', 0),
(83, 6, 'sale_menu', NULL, 'Ojo rojo nacional', 'XAIRAKHI', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(84, 6, 'sale_menu', NULL, 'Citrus gin', 'ISMB6FVJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(85, 6, 'sale_menu', NULL, 'Mezcaladas', 'UD4ZCICT', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(86, 6, 'sale_menu', NULL, 'Mezsabina', 'EHPCI8AN', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(87, 6, 'sale_menu', NULL, 'Ron tropi', 'XMQJPZWI', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(88, 6, 'sale_menu', NULL, 'Tequila fresh', 'LGVDU8EM', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(89, 6, 'sale_menu', NULL, 'Café', 'UM4Y9ZOT', 0, 1, '42', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(90, 6, 'sale_menu', NULL, 'Mojito clásico', 'PHPEZN4S', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(91, 6, 'sale_menu', NULL, 'Mojito de fresa', 'PAWAFI5A', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(92, 6, 'sale_menu', NULL, 'Mojito de guanabana', '24UPPX98', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(93, 6, 'sale_menu', NULL, 'Mojito de kiwi', 'LUVQ7WX8', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(94, 6, 'sale_menu', NULL, 'Mojito de mango', 'CQY33GJJ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(95, 6, 'sale_menu', NULL, 'Mojito de maracuya', 'LOYMPDHX', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(96, 6, 'sale_menu', NULL, 'Mojito de tamarindo', 'JO14QYCS', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(97, 6, 'sale_menu', NULL, 'Daiquiri de fresa', '0G4134GV', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(98, 6, 'sale_menu', NULL, 'Daiquiri de guanabana', 'J2FJSYLN', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(99, 6, 'sale_menu', NULL, 'Daiquiri de kiwi', 'BOANEHOH', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(100, 6, 'sale_menu', NULL, 'Daiquiri de limón|', 'EO78WUWA', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(101, 6, 'sale_menu', NULL, 'Daiquiri de mago', 'TXEX6HKD', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(102, 6, 'sale_menu', NULL, 'Daiquiri de maracuya', 'VFJBHVCN', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(103, 6, 'sale_menu', NULL, 'Daiquiri de tamarindo', 'WW2JSUWE', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(104, 6, 'sale_menu', NULL, 'Margarita clásica', 'W12NF5IW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(105, 6, 'sale_menu', NULL, 'Margarita de fresa', 'UYAV4EBP', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(106, 6, 'sale_menu', NULL, 'Margarita de guanabana', 'BFSONKL2', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(107, 6, 'sale_menu', NULL, 'Margarita de kiwi', 'YBCROWVF', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(108, 6, 'sale_menu', NULL, 'Margarita de mango', '0XI1YBYW', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(109, 6, 'sale_menu', NULL, 'Margarita de maracuya', 'XKLOXNAX', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(110, 6, 'sale_menu', NULL, 'Margarita de tamarindo', 'Q24BNPAZ', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(111, 6, 'sale_menu', NULL, 'Botella Azteca De Oro', '7503023578349', 1, 6, '349', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(112, 6, 'sale_menu', NULL, 'Botella Don Pedro', '7503023578066', 1, 6, '349', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(113, 6, 'sale_menu', NULL, 'Botella Terry', '8410162011028', 1, 6, '390', NULL, NULL, '{\"1\":{\"weight\":\"500\",\"unity\":\"3\"}}', '[]', '[]', 0),
(114, 6, 'sale_menu', NULL, 'Botella Terry 1900', 'EBNKHMUA', 1, 6, '490', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(115, 6, 'sale_menu', NULL, 'Botella Torres 5', '8410113000019', 1, 6, '350', NULL, NULL, '{\"1\":{\"weight\":\"505\",\"unity\":\"3\"}}', '[]', '[]', 0),
(116, 6, 'sale_menu', NULL, 'Botella Torres 10', '8410113000071', 1, 6, '490', NULL, NULL, '{\"1\":{\"weight\":\"505\",\"unity\":\"3\"}}', '[]', '[]', 0),
(117, 6, 'sale_menu', NULL, 'Botella Befeater', '5000329002216', 1, 6, '650', NULL, NULL, '{\"2\":{\"weight\":\"510\",\"unity\":\"3\"}}', '[]', '[]', 0),
(118, 6, 'sale_menu', NULL, 'Botella Bombay', '5010677715003', 1, 6, '750', NULL, NULL, '{\"2\":{\"weight\":\"563\",\"unity\":\"3\"}}', '[]', '[]', 0),
(119, 6, 'sale_menu', NULL, 'Botella Larios', '8411144100662', 1, 6, '399', NULL, NULL, '{\"2\":{\"weight\":\"415\",\"unity\":\"3\"}}', '[]', '[]', 0),
(120, 6, 'sale_menu', NULL, 'Botella Tanqueray', '5000291020706', 1, 6, '750', NULL, NULL, '{\"2\":{\"weight\":\"526\",\"unity\":\"3\"}}', '[]', '[]', 0),
(121, 6, 'sale_menu', NULL, 'Botella 400 Conejos Joven', '7506351811010', 1, 6, '699', NULL, NULL, '{\"2\":{\"weight\":\"614\",\"unity\":\"3\"}}', '[]', '[]', 0),
(122, 6, 'sale_menu', NULL, 'Botella 400 Conejos Reposado', '7506351811126', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"597\",\"unity\":\"3\"}}', '[]', '[]', 0),
(123, 6, 'sale_menu', NULL, 'Botella 400 Conejos Tobala', '75063518110587506351811058', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"590\",\"unity\":\"3\"}}', '[]', '[]', 0),
(124, 6, 'sale_menu', NULL, 'Botella Bruxo', '7502261500532', 1, 6, '550', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(125, 6, 'sale_menu', NULL, 'Botella Unión Joven', '7503016230001', 1, 6, '650', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(126, 6, 'sale_menu', NULL, 'Botella Recuerdo de Oaxaca', '7503009838337', 1, 6, '420', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(127, 6, 'sale_menu', NULL, 'Botella Appleton State', '7501035020207', 1, 6, '599', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(128, 6, 'sale_menu', NULL, 'Botella Appleton Special', '7501035020429', 1, 6, '350', NULL, NULL, '{\"2\":{\"weight\":\"453\",\"unity\":\"3\"}}', '[]', '[]', 0),
(129, 6, 'sale_menu', NULL, 'Botella Bacardi Blanco', '7501008660195', 1, 6, '320', NULL, NULL, '{\"2\":{\"weight\":\"444\",\"unity\":\"3\"}}', '[]', '[]', 0),
(130, 6, 'sale_menu', NULL, 'Botella Captain Morgan', '5000281055374', 1, 6, '350', NULL, NULL, '{\"2\":{\"weight\":\"469\",\"unity\":\"3\"}}', '[]', '[]', 0),
(131, 6, 'sale_menu', NULL, 'Botella Havanna 3 Años', '8501110080248', 1, 6, '349', NULL, NULL, '{\"2\":{\"weight\":\"427\",\"unity\":\"3\"}}', '[]', '[]', 0),
(132, 6, 'sale_menu', NULL, 'Botella Havanna 7 Años', '8501110080439', 1, 6, '650', NULL, NULL, '{\"2\":{\"weight\":\"514\",\"unity\":\"3\"}}', '[]', '[]', 0),
(134, 6, 'sale_menu', NULL, 'Botella Kraken Spiced', '7501035047204', 1, 6, '389', NULL, NULL, '{\"2\":{\"weight\":\"741\",\"unity\":\"3\"}}', '[]', '[]', 0),
(135, 6, 'sale_menu', NULL, 'Botella Kraken Ghost', '7501035043299', 1, 6, '389', NULL, NULL, '{\"2\":{\"weight\":\"707\",\"unity\":\"3\"}}', '[]', '[]', 0),
(136, 6, 'sale_menu', NULL, 'Botella Matusalem Clásico', '7501035045019', 1, 6, '350', NULL, NULL, '{\"2\":{\"weight\":\"478\",\"unity\":\"3\"}}', '[]', '[]', 0),
(137, 6, 'sale_menu', NULL, 'Botella Matusalem Platino', '7501035045002', 1, 6, '320', NULL, NULL, '{\"2\":{\"weight\":\"471\",\"unity\":\"3\"}}', '[]', '[]', 0),
(138, 6, 'sale_menu', NULL, 'Botella Zacapa Ámbar', '7401005010583', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(139, 6, 'sale_menu', NULL, 'Botella 1800 Cristalino', '7501035013483', 1, 6, '999', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(140, 6, 'sale_menu', NULL, 'Botella Antiguo Reposado', '744607004107', 1, 6, '349', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(141, 6, 'sale_menu', NULL, 'Botella Don Julio 70', '5000281056265', 1, 6, '999', NULL, NULL, '{\"1\":{\"weight\":\"808\",\"unity\":\"3\"}}', '[]', '[]', 0),
(142, 6, 'sale_menu', NULL, 'Botella Don Julio Blanco', '5000281056272', 1, 6, '800', NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(143, 6, 'sale_menu', NULL, 'Botella Don Julio Reposado', '5000281056241', 1, 6, '800', NULL, NULL, '{\"1\":{\"weight\":\"702\",\"unity\":\"3\"}}', '[]', '[]', 0),
(145, 6, 'sale_menu', NULL, 'Botella Herradura Blanco', '744607000109', 1, 6, '650', NULL, NULL, '{\"1\":{\"weight\":\"397\",\"unity\":\"3\"}}', '[]', '[]', 0),
(146, 6, 'sale_menu', NULL, 'Botella Herradura Reposado', '744607002103', 1, 6, '690', NULL, NULL, '{\"1\":{\"weight\":\"867\",\"unity\":\"3\"}}', '[]', '[]', 0),
(147, 6, 'sale_menu', NULL, 'Botella Jimador Blanco', '744607048101', 1, 6, '320', NULL, NULL, '{\"1\":{\"weight\":\"489\",\"unity\":\"3\"}}', '[]', '[]', 0),
(148, 6, 'sale_menu', NULL, 'Botella Jimador Reposado', '744607049108', 1, 6, '320', NULL, NULL, '{\"1\":{\"weight\":\"496\",\"unity\":\"3\"}}', '[]', '[]', 0),
(149, 6, 'sale_menu', NULL, 'Botella José Cuervo Especial', '7501035010116', 1, 6, '320', NULL, NULL, '{\"3\":{\"weight\":\"475\",\"unity\":\"3\"}}', '[]', '[]', 0),
(150, 6, 'sale_menu', NULL, 'Botella José Cuervo Tradicional Reposado', '7501035012028', 1, 6, '349', NULL, NULL, '{\"3\":{\"weight\":\"567\",\"unity\":\"3\"}}', '[]', '[]', 0),
(151, 6, 'sale_menu', NULL, 'Botella José Cuervo Tradicional Plata', 'PVF2WUU7', 1, 6, '349', NULL, NULL, '{\"3\":{\"weight\":\"567\",\"unity\":\"3\"}}', '[]', '[]', 0),
(152, 6, 'sale_menu', NULL, 'Botella José Cuervo Tradicional Cristalino', '7501035012356', 1, 6, '949', NULL, NULL, '{\"3\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(153, 6, 'sale_menu', NULL, 'Botella Maestro Dobel Diamante', '7501035014022', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"658\",\"unity\":\"3\"}}', '[]', '[]', 0),
(155, 6, 'sale_menu', NULL, 'Botella Gotland', '7501035022003', 1, 6, '350', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(156, 6, 'sale_menu', NULL, 'Botella Smirnoff', '082000000068', 1, 6, '350', NULL, NULL, '{\"2\":{\"weight\":\"420\",\"unity\":\"3\"}}', '[]', '[]', 0),
(157, 6, 'sale_menu', NULL, 'Botella Smirnoff Tamarindo', 'T3MRSRDY', 1, 6, '489', NULL, NULL, '{\"2\":{\"weight\":\"497\",\"unity\":\"3\"}}', '[]', '[]', 0),
(158, 6, 'sale_menu', NULL, 'Botella Stolichnnaya', '4750021000157', 1, 6, '450', NULL, NULL, '{\"2\":{\"weight\":\"434\",\"unity\":\"3\"}}', '[]', '[]', 0),
(159, 6, 'sale_menu', NULL, 'Botella Wyboroba', '5900685006050', 1, 6, '320', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(160, 6, 'sale_menu', NULL, 'Botella Buchanans 12', '50196388', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"502\",\"unity\":\"3\"}}', '[]', '[]', 0),
(161, 6, 'sale_menu', NULL, 'Botella Chivas Regal 12', '080432400395', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(162, 6, 'sale_menu', NULL, 'Botella Johnnie Walker Black Label', '5000267024004', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"365\",\"unity\":\"3\"}}', '[]', '[]', 0),
(163, 6, 'sale_menu', NULL, 'Botella Johnnie Walker Red Label', '5000267014203', 1, 6, '450', NULL, NULL, '{\"1\":{\"weight\":\"356\",\"unity\":\"3\"}}', '[]', '[]', 0),
(164, 6, 'sale_menu', NULL, 'Botella Jack Daniels', '082184090473', 1, 6, '999', NULL, NULL, '{\"2\":{\"weight\":\"400\",\"unity\":\"3\"}}', '[]', '[]', 0),
(165, 6, 'sale_menu', NULL, 'Botella Jim Beam', '080686002291', 1, 6, '450', NULL, NULL, '{\"2\":{\"weight\":\"388\",\"unity\":\"3\"}}', '[]', '[]', 0),
(166, 6, 'sale_menu', NULL, 'Botella Passport', '080432402870', 1, 6, '389', NULL, NULL, '{\"1\":{\"weight\":\"388\",\"unity\":\"3\"}}', '[]', '[]', 0),
(167, 6, 'sale_menu', NULL, 'Botella William Lawsons', '5010752000321', 1, 6, '399', NULL, NULL, '{\"2\":{\"weight\":\"391\",\"unity\":\"3\"}}', '[]', '[]', 0),
(168, 6, 'sale_menu', NULL, 'Botella Licor 43', '8410221110075', 1, 6, '750', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(169, 6, 'sale_menu', NULL, 'Botella Jagermeister', '4067700014047', 1, 6, '750', NULL, NULL, '{\"1\":{\"weight\":\"585\",\"unity\":\"3\"}}', '[]', '[]', 0),
(170, 6, 'sale_menu', NULL, 'Botella Kahlúa', '4XIDYQE7', 1, 6, '389', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(171, 6, 'sale_menu', NULL, 'Shot de Azteca De Oro', 'IF9FZQG2', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"111\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(172, 6, 'sale_menu', NULL, 'Shot de Don Pedro', 'OQTN6TJM', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"112\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(173, 6, 'sale_menu', NULL, 'Shot de Terry', 'WOL5EEUJ', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"113\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(174, 6, 'sale_menu', NULL, 'Shot de Terry 1900', '0QJVTRWX', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"114\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(175, 6, 'sale_menu', NULL, 'Shot de Torres 5', 'WW1PFJXS', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"115\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(176, 6, 'sale_menu', NULL, 'Shot de Torres 10', 'YFYNLZ6P', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"116\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(177, 6, 'sale_menu', NULL, 'Shot de Befeater', 'FJOUGBY4', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"117\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(178, 6, 'sale_menu', NULL, 'Shot de Bombay', '4CMV9EFL', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"118\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(179, 6, 'sale_menu', NULL, 'Shot de Larios', 'H57PAA5R', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"119\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(180, 6, 'sale_menu', NULL, 'Shot de Tanqueray', '3SL0AL5Z', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"120\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(181, 6, 'sale_menu', NULL, 'Shot de 400 Conejos Joven', 'SP2ODXAS', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"121\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(182, 6, 'sale_menu', NULL, 'Shot de 400 Conejos Reposado', '1WEKJECM', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"122\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(183, 6, 'sale_menu', NULL, 'Shot de 400 Conejos Tobala', 'VC8PBZ0B', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"123\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(184, 6, 'sale_menu', NULL, 'Shot de Bruxo', 'OFIB9EXP', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"124\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(185, 6, 'sale_menu', NULL, 'Shot de Unión Joven', '6DCROPFL', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"125\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(186, 6, 'sale_menu', NULL, 'Shot de Recuerdo de Oaxaca', 'YCSSLQQ4', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"126\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(187, 6, 'sale_menu', NULL, 'Shot de de Appleton State', 'KGS3SRDF', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"127\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(188, 6, 'sale_menu', NULL, 'Shot de Appleton Special', 'XTNSX1WJ', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"128\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(189, 6, 'sale_menu', NULL, 'Shot de Bacardi Blanco', 'RYCVDDCD', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"129\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(190, 6, 'sale_menu', NULL, 'Shot de Captain Morgan', '0CI2WWUJ', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"130\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(191, 6, 'sale_menu', NULL, 'Shot de Havanna 3 Años', '7WJQZTWU', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"131\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(192, 6, 'sale_menu', NULL, 'Shot de Havanna 7 Años', 'LKNZDLK9', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"132\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(194, 6, 'sale_menu', NULL, 'Shot de Kraken Spiced', 'YGPRTSQE', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"134\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(195, 6, 'sale_menu', NULL, 'Shot de Kraken Ghost', '936IOYK5', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"135\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(196, 6, 'sale_menu', NULL, 'Shot de Matusalem Clásico', '5SDX1UJU', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"136\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(197, 6, 'sale_menu', NULL, 'Shot de Matusalem Platino', 'HSHL6V0S', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"137\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(198, 6, 'sale_menu', NULL, 'Shot de Zacapa Ámbar', 'E5JYQHUO', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"138\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(199, 6, 'sale_menu', NULL, 'Shot de 1800 Cristalino', '0EE4G29H', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"139\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(200, 6, 'sale_menu', NULL, 'Shot de Antiguo Reposado', 'YEMMYET9', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"140\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(201, 6, 'sale_menu', NULL, 'Shot de Don Julio 70', 'ER2SDIPV', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"141\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(202, 6, 'sale_menu', NULL, 'Shot de Don Julio Blanco', '5M6LLQVJ', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"142\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(203, 6, 'sale_menu', NULL, 'Shot de Don Julio Reposado', 'VGIWLKFF', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"143\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(205, 6, 'sale_menu', NULL, 'Shot de Herradura Blanco', 'FDWW09PM', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"145\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(206, 6, 'sale_menu', NULL, 'Shot de Herradura Reposado', 'YF9UOI8I', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"146\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(207, 6, 'sale_menu', NULL, 'Shot de Jimador Blanco', 'DVPAAJN0', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"147\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(208, 6, 'sale_menu', NULL, 'Shot de Jimador Reposado', 'GFAOD8KT', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"148\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(209, 6, 'sale_menu', NULL, 'Shot de José Cuervo Especial', 'WXYD2YXA', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"149\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(210, 6, 'sale_menu', NULL, 'Shot de José Cuervo Tradicional Reposado', '55WXGUWY', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"150\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(211, 6, 'sale_menu', NULL, 'Shot de José Cuervo Tradicional Plata', 'OWZ3ELRJ', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"151\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(212, 6, 'sale_menu', NULL, 'Shot de José Cuervo Tradicional Cristalino', 'LF0XBVQX', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"152\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(213, 6, 'sale_menu', NULL, 'Shot de Maestro Dobel Diamante', 'F3LNRTMA', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"153\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(215, 6, 'sale_menu', NULL, 'Shot de Gotland', '0PKCO2IN', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"155\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(216, 6, 'sale_menu', NULL, 'Shot de Smirnoff', 'XORYGX0S', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"156\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(217, 6, 'sale_menu', NULL, 'Shot de Smirnoff Tamarindo', 'G503G9B2', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"157\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(218, 6, 'sale_menu', NULL, 'Shot de Stolichnnaya', 'H8YZG4ZB', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"158\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(219, 6, 'sale_menu', NULL, 'Shot de Wyboroba', 'XHLNA5I6', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"159\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(220, 6, 'sale_menu', NULL, 'Shot de Buchanans 12', 'RUVGZVLH', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"160\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(221, 6, 'sale_menu', NULL, 'Shot de Chivas Regal 12', '51RW7FPW', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"161\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(222, 6, 'sale_menu', NULL, 'Shot de Johnnie Walker Black Label', 'N76COROP', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"162\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(223, 6, 'sale_menu', NULL, 'Shot de Johnnie Walker Red Label', '71EEUFWF', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"163\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(224, 6, 'sale_menu', NULL, 'Shot de Jack Daniels', 'KIVF32OV', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"164\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(225, 6, 'sale_menu', NULL, 'Shot de Jim Beam', 'SHRJY5AD', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"165\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(226, 6, 'sale_menu', NULL, 'Shot de Passport', 'HK2UPYGM', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"166\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(227, 6, 'sale_menu', NULL, 'Shot de William Lawsons', 'NBYD0FY5', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"167\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(228, 6, 'sale_menu', NULL, 'Shot de Jagermeister', 'ISBOZM9N', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"169\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(229, 6, 'sale_menu', NULL, 'Shot de Kahlúa', 'NSKNFMIW', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"170\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(230, 6, 'sale_menu', NULL, 'Cucaracha de Kahlúa', 'M5YRAYOP', 0, 1, '42', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(231, 6, 'sale_menu', NULL, 'Shot de Licor 43', '2TRB2CZY', 0, NULL, '42', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"168\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(232, 6, 'sale_menu', NULL, 'Carajillo de Licor 43', 'QC5VEKY4', 0, 1, '42', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(233, 6, 'sale_menu', NULL, 'Bohemia Clara', '75005214', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(234, 6, 'sale_menu', NULL, 'Bohemia Oscura', '75024741', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(235, 6, 'sale_menu', NULL, 'Bohemia Weizen', 'PXXLLD2U', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(236, 6, 'sale_menu', NULL, 'Coors Ligth', '07199044', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(237, 6, 'sale_menu', NULL, 'Indio', 'KDWDF9SM', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(238, 6, 'sale_menu', NULL, 'Sol', '75001629', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(239, 6, 'sale_menu', NULL, 'Superior', 'HFTS38SF', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(240, 6, 'sale_menu', NULL, 'Tecate ', '75005191', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(241, 6, 'sale_menu', NULL, 'Tecate Light', '75005306', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(242, 6, 'sale_menu', NULL, 'Amster Ultra', '7503024416107', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(243, 6, 'sale_menu', NULL, 'XX Ambar', '75005276', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(244, 6, 'sale_menu', NULL, 'XX Lagger', '75005290', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(245, 6, 'sale_menu', NULL, 'Heineken', '7501049999261', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(246, 6, 'sale_menu', NULL, 'Agua del día', '8AAOG34X', 0, NULL, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(247, 6, 'sale_menu', NULL, 'Agua natural', 'ET9U4QJY', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(248, 6, 'sale_menu', NULL, 'Agua mineral', 'GSJ8DBWN', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(249, 6, 'sale_menu', NULL, 'Limonada', '9SKGKCLM', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(250, 6, 'sale_menu', NULL, 'Naranjada', 'AMDJ49RN', 0, 1, '21', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(251, 6, 'sale_menu', NULL, 'Boost', '7501035046016', 1, 1, '42', NULL, NULL, '[]', '[]', '[]', 0),
(252, 6, 'sale_menu', NULL, 'Coca Cola 0', 'H9ZXFHAL', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(253, 6, 'sale_menu', NULL, 'Coca Cola', '759ZNVK9', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(254, 6, 'sale_menu', NULL, 'Coca Cola Light', 'BOFNRVXZ', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(255, 6, 'sale_menu', NULL, 'Fanta', 'HFQGZ3IJ', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(256, 6, 'sale_menu', NULL, 'Fresca', '0HCJLFQG', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(257, 6, 'sale_menu', NULL, 'Sidral Mundet', 'LHPWCYYW', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(258, 6, 'sale_menu', NULL, 'Sprite', 'CXS7EC2B', 1, 1, '21', NULL, NULL, '[]', '[]', '[]', 0),
(259, 6, 'sale_menu', NULL, 'Descorche premium', 'KWZZ59RL', 0, 1, '200', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(260, 6, 'sale_menu', NULL, 'Descorche nacional', 'SRLD209J', 0, 1, '400', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', '[]', '[]', '[]', 0),
(261, 6, 'supply', NULL, 'Alitas de pollo', 'J1I2YM6Z', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(262, 6, 'supply', NULL, 'Arrachera', 'L5ZGEGR7', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(263, 6, 'supply', NULL, 'Atún', 'BIDG1VD3', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(264, 6, 'supply', NULL, 'Camarón 41/50', '8SNNDFDP', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(265, 6, 'supply', NULL, 'Carnitas', 'F9W3FFO5', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(266, 6, 'supply', NULL, 'Chicharrón ', 'FBIASMAF', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(267, 6, 'supply', NULL, 'Chorizo español', 'LZYRHN1R', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(268, 6, 'supply', NULL, 'Cochinita', 'OIBTP2FM', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(270, 6, 'supply', NULL, 'Lechón normal', 'QP5BGMRZ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(271, 6, 'supply', NULL, 'Molida de cerdo', 'EI1XFKTR', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(272, 6, 'supply', NULL, 'Molida de res ', 'EUMDZATH', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(273, 6, 'supply', NULL, 'Pastor', '1NABINR3', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(274, 6, 'supply', NULL, 'Pechuga de pollo con hueso', 'MR6X4LLO', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(275, 6, 'supply', NULL, 'Pollo', 'HOJVKGY1', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(276, 6, 'supply', NULL, 'Tinga de pollo', 'RYY07QNI', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(277, 6, 'supply', NULL, 'Tinga de res', 'Y89XOKNQ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(278, 6, 'supply', NULL, 'Carnation', 'LXHEAXWF', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(279, 6, 'supply', NULL, 'Crema', 'GYIRNKD3', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(280, 6, 'supply', NULL, 'Leche entera', 'ILEV4K82', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(281, 6, 'supply', NULL, 'Lechera', 'ROFBMOAG', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(282, 6, 'supply', NULL, 'Queso Cotija', 'H92XIG9H', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(283, 6, 'supply', NULL, 'Queso Oaxaca', 'PPUMD1BB', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(284, 6, 'supply', NULL, 'Tortilla #14 blanca', 'JXYES9ZJ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(285, 6, 'supply', NULL, 'Tortilla #12 amarilla', '0EN43WH0', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(286, 6, 'supply', NULL, 'Pan para hamburguesa de 10 cm', '8O5KML0X', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(287, 6, 'supply', NULL, 'Pan para torta de 8x8 cm  ', 'OTMCIYAZ', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(288, 6, 'supply', NULL, 'Harina de trigo', 'HGQYRIOQ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(289, 6, 'supply', NULL, 'Maseca', 'RFH5LQT0', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(290, 6, 'supply', NULL, 'Masa nixtamal', 'YKRGBWE8', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(291, 6, 'supply', NULL, 'Ajo pelado', 'I3070O1N', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(292, 6, 'supply', NULL, 'Apio', '8MQRTQHB', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(293, 6, 'supply', NULL, 'Cebolla blanca', 'MNLWX1RZ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(294, 6, 'supply', NULL, 'Cebolla morada', 'GJ9TJVI0', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(295, 6, 'supply', NULL, 'Chile Guajillo', '6D4MO8WX', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(296, 6, 'supply', NULL, 'Chile Habanero', 'JOGHAWAC', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(297, 6, 'supply', NULL, 'Chile Jalapeño', 'RTSVH8VJ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(298, 6, 'supply', NULL, 'Chile Serrano', '5PY3FAHL', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(299, 6, 'supply', NULL, 'Cilantro', 'Q9OCYVD2', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(300, 6, 'supply', NULL, 'Col morada', 'HPYEZIO9', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(301, 6, 'supply', NULL, 'Grano de elote', 'OQX6ZVZY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(302, 6, 'supply', NULL, 'Epazote', 'HVVPJXHD', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(303, 6, 'supply', NULL, 'Hierbabuena', 'CGM17SHY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(305, 6, 'supply', NULL, 'Lechuga romana', '7MRE3RDH', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(306, 6, 'supply', NULL, 'Limón', 'PGNR9JBK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(307, 6, 'supply', NULL, 'Naranja dulce', 'HO07OYV4', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(308, 6, 'supply', NULL, 'Papa blanca', 'GRCBDDIK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(309, 6, 'supply', NULL, 'Pepino verde', 'U80WNMVY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(310, 6, 'supply', NULL, 'Piña', 'KGBE6WCL', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(311, 6, 'supply', NULL, 'Tomate saladet', 'P2MSLOER', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(312, 6, 'supply', NULL, 'Tomate verde', 'DSTM9DV4', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(313, 6, 'supply', NULL, 'Toronja', 'SA37R0Z9', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(314, 6, 'supply', NULL, 'Zanahoria', '413XJPPO', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(315, 6, 'supply', NULL, 'Bolsa de hielo purificado', '1TAXX7Y3', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(316, 6, 'supply', NULL, 'Botella de batalla de Mezcal', '2GOGZ0IK', 1, 6, NULL, NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(317, 6, 'supply', NULL, 'Botella de batalla de Tequila', 'KVIJSPTB', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(319, 6, 'supply', NULL, 'Botella de batalla de Ron', 'NJUPFE3U', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(321, 6, 'supply', NULL, 'Botella Controy', '7501043709705', 1, 6, NULL, NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(322, 6, 'supply', NULL, 'Botella Gibson', 'ZKUQBVJ5', 1, 7, NULL, NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(323, 6, 'supply', NULL, 'Caguama de cerveza', 'KI5NFGCV', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(324, 6, 'supply', NULL, 'Agua mineral', 'JCWQKO6R', 1, 6, NULL, NULL, NULL, '{\"8\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[\"2\",\"14\"]', 0),
(325, 6, 'supply', NULL, 'Garrafón de agua purificada', 'PMO99LG0', 1, 6, NULL, NULL, NULL, '{\"7\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(326, 6, 'supply', NULL, 'Agua tónica', 'OCBRJGKN', 1, 1, NULL, NULL, NULL, '[]', NULL, '[\"2\"]', 0),
(327, 6, 'supply', NULL, 'Azúcar', 'JGNSDIYL', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(328, 6, 'supply', NULL, 'Cápsulas de café', '8DZCXKSX', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(329, 6, 'supply', NULL, 'Chamoy', '6L0QVAJE', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(330, 6, 'supply', NULL, 'Chile en polvo', '3QP8SHGF', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(331, 6, 'supply', NULL, 'Clamato', 'XQYNVXYI', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(332, 6, 'supply', NULL, 'Concentrado de fresa', 'XNUCGM2A', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(333, 6, 'supply', NULL, 'Concentrado de guanabana', 'C4VNBTIY', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(334, 6, 'supply', NULL, 'Concentrado de kiwi', 'Z145VVUF', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(335, 6, 'supply', NULL, 'Concentrado de mango', 'GYODY7FX', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(336, 6, 'supply', NULL, 'Concentrado de maracuya', '5SJPLJZB', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(337, 6, 'supply', NULL, 'Concentrado de tamarindo', 'R636IYHY', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(338, 6, 'supply', NULL, 'Jarabe natural', 'T1QOCF5M', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(339, 6, 'supply', NULL, 'Jugo de limón', '0JGQ8NNA', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(340, 6, 'supply', NULL, 'Jugo de naranja', 'P4YNNNQM', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(341, 6, 'supply', NULL, 'Chirimico', 'WF9GKDQY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(342, 6, 'supply', NULL, 'Sal de mar', 'CSIW1PTG', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(343, 6, 'supply', NULL, 'Sal fina', 'VTS5HXEB', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(345, 6, 'supply', NULL, 'Salsa Inglesa', 'VZK3WGBI', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(346, 6, 'supply', NULL, 'Salsa Tabasco', 'CUFGG6CU', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(347, 6, 'supply', NULL, 'Aceite de cocina ', 'UORJ62LO', 1, 7, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(348, 6, 'supply', NULL, 'Aceite para freidora', 'MEAPOAFC', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(349, 6, 'supply', NULL, 'Aderezo ranch', '5TI8ZTEK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(350, 6, 'supply', NULL, 'Ajo en polvo', 'BHKFQWUV', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(351, 6, 'supply', NULL, 'Arroz', 'PR4CM15M', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(353, 6, 'supply', NULL, 'Canela en rajas', 'CXIQDW9E', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(354, 6, 'supply', NULL, 'Canela molida', 'OZ8AMNTK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(355, 6, 'supply', NULL, 'Cebolla en polvo', 'SW77YUMF', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(356, 6, 'supply', NULL, 'Consomé de camarón', 'FT1KGYNV', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(357, 6, 'supply', NULL, 'Consomé de pollo', '6T8YTNPW', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(358, 6, 'supply', NULL, 'Frijol negro', 'LCDPB4B7', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(359, 6, 'supply', NULL, 'Huevo', 'XPFP1GWE', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(360, 6, 'supply', NULL, 'Hojas de laurel', '30FCANNA', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(361, 6, 'supply', NULL, 'Maizena', 'LXBVYDOA', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(362, 6, 'supply', NULL, 'Mayonesa', 'WPEKWRAU', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0);
INSERT INTO `products` (`id`, `account`, `type`, `avatar`, `name`, `token`, `inventory`, `unity`, `price`, `portion`, `formula`, `contents`, `supplies`, `categories`, `blocked`) VALUES
(363, 6, 'supply', NULL, 'Mostaza amarilla', 'O9ZKWXIK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(365, 6, 'supply', NULL, 'Orégano molido', 'QKYLRFKK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(366, 6, 'supply', NULL, 'Paprika', 'HYZNSO3L', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(367, 6, 'supply', NULL, 'Pepinillos', 'PJGURKO9', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(368, 6, 'supply', NULL, 'Pimienta blanca molida', 'GM9DC61M', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(369, 6, 'supply', NULL, 'Pimienta negra molida', '2I7ZHHNB', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(370, 6, 'supply', NULL, 'Salsa Árbol de 100 Gr', 'MR5EE205', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(371, 6, 'supply', NULL, 'Salsa Bbq', 'YGIB5PKR', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(372, 6, 'supply', NULL, 'Salsa Catsup 4 lts', 'DXWHGUHL', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(373, 6, 'supply', NULL, 'Salsa Chipotle de 100 Gr', 'AAN0ENZ7', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(374, 6, 'supply', NULL, 'Salsa de soya', 'QJT0ZBWU', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(375, 6, 'supply', NULL, 'Salsa Habanero de 100 Gr', 'AQ0O9ANK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(376, 6, 'supply', NULL, 'Jugo Maggi', 'LNEPT9AB', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(377, 6, 'supply', NULL, 'Salsa Red Hot', 'NMSXBHFK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(378, 6, 'supply', NULL, 'Salsa Verde de 100 Gr', 'FXVYT2XY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(379, 6, 'supply', NULL, 'Vainilla', 'JZWKSXD9', 1, 7, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(380, 6, 'supply', NULL, 'Vinagre blanco', 'ZUYXXM7F', 1, 6, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(385, 6, 'supply', NULL, 'Jicama', '2DP16E5M', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(388, 6, 'work_material', NULL, 'Caja de Aluminio En Rollo', 'O5ZWOUTA', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(389, 6, 'work_material', NULL, 'Rollo de Bolsa Nat. 10X20', 'CXVTBO4G', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(390, 6, 'work_material', NULL, 'Rollo de Bolsa Nat. 25X35', 'BVP4OFH6', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(391, 6, 'work_material', NULL, 'Rollo de Bolsa 70 X 90 Natural Transp. Basura', 'NSTAWEW1', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(392, 6, 'work_material', NULL, 'Paquete de Bolsa B.D. 90X120 Negra ', 'LY9U4RHR', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(393, 6, 'work_material', NULL, 'Paquete de Bolsa Camiseta', 'YAAKIJ4L', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(394, 6, 'work_material', NULL, 'Botiquín', 'CLMDSEZ0', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(395, 6, 'work_material', NULL, 'Carpetas Carta', '2OPGYWEA', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(396, 6, 'work_material', NULL, 'Caja de Clips', 'HB9IBW8D', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(397, 6, 'work_material', NULL, 'Paquete de Charola Grande', 'WXOXEUAW', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(398, 6, 'work_material', NULL, 'Charola Chica', 'JJ0TDO5B', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(399, 6, 'work_material', NULL, 'Paquete de Grapas', 'V4J3OJJ8', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(400, 6, 'work_material', NULL, 'Caja de Guante De Látex/Vinil', 'I43UM8DD', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(401, 6, 'work_material', NULL, 'Paquete de Hojas De Máquina', 'LAT05R9Y', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(402, 6, 'work_material', NULL, 'Paquete de Ligas', 'VQLL9UWZ', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(403, 6, 'work_material', NULL, 'Paquete de Palillos', 'UVCCKNAE', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(404, 6, 'work_material', NULL, 'Rollo de Papel Rollo P/Baños', '5ZUGDSKL', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(405, 6, 'work_material', NULL, 'Caja de Redecilla  Negra ', '6FIXNQB4', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(406, 6, 'work_material', NULL, 'Rollo Miniprinter', 'WHIOMF0G', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(407, 6, 'work_material', NULL, 'Rollo P/Terminal Tc', 'BO4XRWNK', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(408, 6, 'work_material', NULL, 'Servilleta  ', '6DTUSB3J', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(409, 6, 'work_material', NULL, 'Paquete Sobre Bolsa', 'KEN3GP89', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(410, 6, 'work_material', NULL, 'Rollo de Toallamátic Papel Higienico ', 'XYKPTTQJ', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(411, 6, 'work_material', NULL, 'Tira de Vaso Desechable', 'MHARQKCD', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(412, 6, 'work_material', NULL, 'Rollo de Vitafilm', '5D1YTCT7', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(413, 6, 'work_material', NULL, 'Aromatizante en aerosol', 'WZEJOV0L', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(414, 6, 'work_material', NULL, 'Brite Sarricida', 'NEP32AXE', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(415, 6, 'work_material', NULL, 'Bactericida', 'UJ70XOKK', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(416, 6, 'work_material', NULL, 'Cloro ', 'IGJXF8AS', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(417, 6, 'work_material', NULL, 'Líquido Para Madera', '3PF2SNT2', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(418, 6, 'work_material', NULL, 'Desincrustador', 'UOCUVWHL', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(419, 6, 'work_material', NULL, 'Escobas Peñolera', 'OG79QSRV', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(420, 6, 'work_material', NULL, 'Escobas Plastico', '8WDEEQI6', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(421, 6, 'work_material', NULL, 'Gel Antibacterial', 'BY9LEI1A', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(422, 6, 'work_material', NULL, 'Fabuloso ', 'GV73YFKI', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(423, 6, 'work_material', NULL, 'Fibra Alambre', '079TW3SW', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(424, 6, 'work_material', NULL, 'Fibras Negras', 'R8SZ64YV', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(425, 6, 'work_material', NULL, 'Fibras Esponja Scotch Brite', 'EDJYRSS2', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(426, 6, 'work_material', NULL, 'Cepillo Para Baño', 'ZQFQFQZX', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(427, 6, 'work_material', NULL, 'Detergente En Polvo', 'RV0XHJYC', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(428, 6, 'work_material', NULL, 'Fibras Verde', 'XILFT7GW', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(429, 6, 'work_material', NULL, 'Raid', 'SBUC2X1L', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(430, 6, 'work_material', NULL, 'Franela/Jerga Cocina', 'BHE2LD0Q', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(431, 6, 'work_material', NULL, 'Gel Dermo-Wipe  (Antibacterial)', 'MEV1Z4CB', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(432, 6, 'work_material', NULL, 'Jabon P Trastes ', 'C6YVSNBE', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(433, 6, 'work_material', NULL, 'Parricel', 'BYSI6YGH', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(434, 6, 'work_material', NULL, 'Quitacochambre', 'VDMKR6XQ', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(435, 6, 'work_material', NULL, 'Shampoo P Manos ', '3IN0FTUA', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(436, 6, 'work_material', NULL, 'Tapete / Pastilla Para Mingitorio', 'MWV33OPL', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(437, 6, 'work_material', NULL, 'Trapeadores', 'GHKRMBDZ', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(438, 6, 'work_material', NULL, 'Trapos Magitel', 'MWKJOSVH', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(451, 6, 'sale_menu', NULL, 'Botella Elegido', '7503015137042', 1, 6, '450', NULL, NULL, '{\"2\":{\"weight\":\"882\",\"unity\":\"3\"}}', '[]', '[]', 0),
(452, 6, 'supply', NULL, 'Botella Curacao azul', '7502213349349', 1, 1, NULL, NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(453, 6, 'sale_menu', NULL, 'Botella Boodles', 'mxGrNBvN', 1, 6, '450', NULL, NULL, '{\"2\":{\"weight\":\"\",\"unity\":\"\"}}', '[]', '[]', 0),
(455, 6, 'supply', NULL, 'Botella de batalla de Ginebra', 'YpoYPKq8', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(461, 6, 'sale_menu', NULL, 'Shot Elegido', 'JñqgLW3Y', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"451\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(473, 6, 'recipe', NULL, 'Esquites', 'hOu6UWjÑ', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"2\",\"unity\":\"2\"},\"302\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"301\":{\"quantity\":\"1\",\"unity\":\"2\"}}', '[]', 0),
(474, 6, 'recipe', NULL, 'Pasta tempura para capear', 'ÑV0E2vEP', 0, NULL, NULL, NULL, NULL, NULL, '{\"323\":{\"quantity\":\"1\",\"unity\":\"2\"},\"288\":{\"quantity\":\"0.800\",\"unity\":\"2\"},\"363\":{\"quantity\":\"0.120\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.002\",\"unity\":\"2\"}}', '[]', 0),
(475, 6, 'recipe', NULL, 'Masa de nixtamal', '7AAO1rAe', 0, NULL, NULL, NULL, NULL, NULL, '{\"288\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"290\":{\"quantity\":\"1\",\"unity\":\"2\"},\"289\":{\"quantity\":\"0.200\",\"unity\":\"2\"}}', '[]', 0),
(476, 6, 'recipe', NULL, 'Chicharron en salsa verde', 'ClGofTGn', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"0.300\",\"unity\":\"2\"},\"291\":{\"quantity\":\"0.015\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"266\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"298\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"299\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"312\":{\"quantity\":\"0.500\",\"unity\":\"2\"}}', '[]', 0),
(477, 6, 'recipe', NULL, 'Consomé de pollo', 'V2paZLñl', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"5\",\"unity\":\"2\"},\"291\":{\"quantity\":\"0.040\",\"unity\":\"2\"},\"292\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"478\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"360\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"482\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"274\":{\"quantity\":\"1.500\",\"unity\":\"2\"},\"314\":{\"quantity\":\"0.100\",\"unity\":\"2\"}}', '[]', 0),
(478, 6, 'supply', NULL, 'Codimix de pollo', 'HCurZ2jP', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(479, 6, 'recipe', NULL, 'Caldo de camarón', 'huuaBwQU', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"10\",\"unity\":\"2\"},\"291\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"292\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"480\":{\"quantity\":\"0.600\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.200\",\"unity\":\"2\"},\"295\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"302\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"495\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"360\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"482\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"481\":{\"quantity\":\"1.200\",\"unity\":\"2\"},\"314\":{\"quantity\":\"0.200\",\"unity\":\"2\"}}', '[]', 0),
(480, 6, 'supply', NULL, 'Cascara de camarón', 'ÑWFOkVUw', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(481, 6, 'supply', NULL, 'Tomate guaje', 'SrksKñTc', 1, 5, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(482, 6, 'supply', NULL, 'Oregano seco (hojas)', 'lJ46c7JY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(483, 6, 'recipe', NULL, 'Harina para alitas de pollo', 'ñ6zHDGHZ', 0, NULL, NULL, NULL, NULL, NULL, '{\"350\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"355\":{\"quantity\":\"0.015\",\"unity\":\"2\"},\"288\":{\"quantity\":\"1\",\"unity\":\"2\"},\"366\":{\"quantity\":\"0.020\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.005\",\"unity\":\"2\"}}', '[]', 0),
(485, 6, 'supply', NULL, 'Mantequilla sin sal', 'h4WwHnv6', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(486, 6, 'supply', NULL, 'Chile Tajin', 'IJLWsCia', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(489, 6, 'recipe', NULL, 'Ceviche de camarón', 'xaWHrlKE', 0, NULL, NULL, NULL, NULL, NULL, '{\"264\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.250\",\"unity\":\"2\"},\"299\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.003\",\"unity\":\"2\"},\"481\":{\"quantity\":\"0.400\",\"unity\":\"2\"}}', '[]', 0),
(494, 6, 'supply', NULL, 'Filete de pescado basa', 'YlcI8ZTp', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(495, 6, 'supply', NULL, 'Granulado de camarón', 'EdaaK3HK', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(496, 6, 'recipe', NULL, 'Relleno para pescadillas', 'jHamÑWg4', 0, NULL, NULL, NULL, NULL, NULL, '{\"291\":{\"quantity\":\"0.020\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"302\":{\"quantity\":\"0.030\",\"unity\":\"2\"},\"494\":{\"quantity\":\"0.650\",\"unity\":\"2\"},\"495\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"360\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"482\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.003\",\"unity\":\"2\"},\"481\":{\"quantity\":\"0.500\",\"unity\":\"2\"}}', '[]', 0),
(497, 6, 'recipe', NULL, 'Salsa de aguachile', 'rB3uiXgm', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"298\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"299\":{\"quantity\":\"0.030\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.150\",\"unity\":\"2\"},\"309\":{\"quantity\":\"0.300\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.004\",\"unity\":\"2\"}}', '[]', 0),
(498, 6, 'recipe', NULL, 'Gorditas de chicharron', '43cMZ266', 0, NULL, NULL, NULL, NULL, NULL, '{\"266\":{\"quantity\":\"0.120\",\"unity\":\"2\"},\"290\":{\"quantity\":\"1\",\"unity\":\"2\"}}', '[]', 0),
(499, 6, 'recipe', NULL, 'Salsa para tostada de atún oriental', 'KchE8usV', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.300\",\"unity\":\"2\"},\"376\":{\"quantity\":\"0.015\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"345\":{\"quantity\":\"0.020\",\"unity\":\"2\"}}', '[]', 0),
(500, 6, 'recipe', NULL, 'Cebolla morada curtida', 'ñvQU3YuÑ', 0, NULL, NULL, NULL, NULL, NULL, '{\"325\":{\"quantity\":\"1\",\"unity\":\"2\"},\"294\":{\"quantity\":\"2.500\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.600\",\"unity\":\"2\"},\"482\":{\"quantity\":\"0.003\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.008\",\"unity\":\"2\"},\"380\":{\"quantity\":\"0.500\",\"unity\":\"2\"}}', '[]', 0),
(501, 6, 'recipe', NULL, 'Verduras con chile', 'jA73VlOF', 0, NULL, NULL, NULL, NULL, NULL, '{\"385\":{\"quantity\":\"0.080\",\"unity\":\"2\"},\"309\":{\"quantity\":\"0.080\",\"unity\":\"2\"},\"314\":{\"quantity\":\"0.080\",\"unity\":\"2\"}}', '[]', 0),
(503, 6, 'supply', NULL, 'Pechuga de pollo marinada', 'dKwYYDtI', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(504, 6, 'recipe', NULL, 'Palomitas de pollo', 'cpUkURmC', 0, NULL, NULL, NULL, NULL, NULL, '{\"478\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"288\":{\"quantity\":\"0.005\",\"unity\":\"2\"},\"503\":{\"quantity\":\"0.060\",\"unity\":\"2\"}}', '[]', 0),
(505, 6, 'recipe', NULL, 'Papa con chorizo', 'JnrwKÑN3', 0, NULL, NULL, NULL, NULL, NULL, '{\"291\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"267\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"308\":{\"quantity\":\"1\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"343\":{\"quantity\":\"0.002\",\"unity\":\"2\"}}', '[]', 0),
(506, 6, 'supply', NULL, 'Carne molida de cerdo 80/20', 'cju2bzuk', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(507, 6, 'supply', NULL, 'Carne molida de res 80/20', 'XñRc6Ka0', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(508, 6, 'recipe', NULL, 'Carne de hamburguesa', 'rlñayYEy', 0, NULL, NULL, NULL, NULL, NULL, '{\"291\":{\"quantity\":\"0.020\",\"unity\":\"2\"},\"292\":{\"quantity\":\"0.250\",\"unity\":\"2\"},\"506\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"507\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"293\":{\"quantity\":\"0.300\",\"unity\":\"2\"},\"299\":{\"quantity\":\"0.030\",\"unity\":\"2\"},\"376\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"482\":{\"quantity\":\"0.002\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.004\",\"unity\":\"2\"},\"345\":{\"quantity\":\"0.010\",\"unity\":\"2\"},\"314\":{\"quantity\":\"0.450\",\"unity\":\"2\"}}', '[]', 0),
(509, 6, 'recipe', NULL, 'Curtido de camarón', 'AfqOLU3E', 0, NULL, NULL, NULL, NULL, NULL, '{\"264\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.350\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.001\",\"unity\":\"2\"}}', '[]', 0),
(510, 6, 'supply', NULL, 'Oregano molido', 'r6aDiBCa', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(511, 6, 'recipe', NULL, 'Curtido de pescado', 'VIoNk8F8', 0, NULL, NULL, NULL, NULL, NULL, '{\"494\":{\"quantity\":\"0.600\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.350\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.001\",\"unity\":\"2\"}}', '[]', 0),
(512, 6, 'sale_menu', NULL, 'Shot de Boodles', 'qHCycxñM', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"453\",\"quantity\":\"0.0295735\"}', '[]', '[]', '[]', 0),
(513, 6, 'recipe', NULL, 'Ensalada de col morada (pendiente)', 'Rmg2OKm3', 0, NULL, NULL, NULL, NULL, NULL, '{\"327\":{\"quantity\":\"0.080\",\"unity\":\"2\"},\"300\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"279\":{\"quantity\":\"0.060\",\"unity\":\"2\"},\"362\":{\"quantity\":\"0.150\",\"unity\":\"2\"},\"363\":{\"quantity\":\"0.100\",\"unity\":\"2\"},\"314\":{\"quantity\":\"0.500\",\"unity\":\"2\"}}', '[]', 0),
(514, 6, 'recipe', NULL, 'Curtido de aguachile', 'JicQ7Ybz', 0, NULL, NULL, NULL, NULL, NULL, '{\"264\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"339\":{\"quantity\":\"0.350\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"343\":{\"quantity\":\"0.030\",\"unity\":\"2\"}}', '[]', 0),
(515, 6, 'recipe', NULL, 'Arroz con leche', 'YjLOqWWq', 0, NULL, NULL, NULL, NULL, NULL, '{\"351\":{\"quantity\":\"0.150\",\"unity\":\"2\"},\"327\":{\"quantity\":\"0.030\",\"unity\":\"2\"},\"353\":{\"quantity\":\"0.003\",\"unity\":\"2\"},\"280\":{\"quantity\":\"1.500\",\"unity\":\"2\"},\"281\":{\"quantity\":\"0.500\",\"unity\":\"2\"}}', '[]', 0),
(516, 6, 'recipe', NULL, 'Flan', 'sLLmsLOb', 0, NULL, NULL, NULL, NULL, NULL, '{\"327\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"517\":{\"quantity\":\"0.300\",\"unity\":\"2\"},\"359\":{\"quantity\":\"8\",\"unity\":\"2\"},\"280\":{\"quantity\":\"1\",\"unity\":\"2\"},\"281\":{\"quantity\":\"1\",\"unity\":\"2\"},\"361\":{\"quantity\":\"0.020\",\"unity\":\"2\"},\"379\":{\"quantity\":\"0.020\",\"unity\":\"2\"}}', '[]', 0),
(517, 6, 'supply', NULL, 'Azucar para caramelo', '9DggmZf3', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(518, 6, 'recipe', NULL, 'Ceviche de pescado', 'pgIlwF0q', 0, NULL, NULL, NULL, NULL, NULL, '{\"293\":{\"quantity\":\"0.250\",\"unity\":\"2\"},\"299\":{\"quantity\":\"0.050\",\"unity\":\"2\"},\"494\":{\"quantity\":\"0.500\",\"unity\":\"2\"},\"510\":{\"quantity\":\"0.001\",\"unity\":\"2\"},\"369\":{\"quantity\":\"0.003\",\"unity\":\"2\"},\"481\":{\"quantity\":\"0.400\",\"unity\":\"2\"}}', '[]', 0),
(519, 6, 'supply', NULL, 'Papas a la francesa', 'lEoqY7ug', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(520, 6, 'supply', NULL, 'Papas gajo marinadas', 'sJoSrDiL', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(534, 8, 'supply', NULL, 'Producto Insumo 1', 'rtjWoJzS', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(536, 8, 'recipe', NULL, 'Producto Receta 1', 'tBQ9V67h', 0, 2, NULL, '10', NULL, NULL, '{\"534\":{\"quantity\":\"0.10\",\"unity\":\"2\"},\"578\":{\"quantity\":\"0.10\",\"unity\":\"2\"}}', '[]', 0),
(538, 8, 'sale_menu', NULL, 'Producto Menú de venta Botella', 'nPpsCruP', 1, 6, '400', NULL, NULL, '{\"9\":{\"weight\":\"60\",\"unity\":\"3\"},\"10\":{\"weight\":\"65\",\"unity\":\"3\"}}', '[]', '[]', 0),
(539, 8, 'sale_menu', NULL, 'Producto Menú de venta Shot', 'z7QA6ñM6', 0, NULL, '21', NULL, '{\"code\":\"SHG78K9H\",\"parent\":\"538\",\"quantity\":\"0.0295735\"}', NULL, '[]', '[]', 0),
(543, 8, 'sale_menu', NULL, 'Producto Menú de venta Comida', 'JMM5NMaK', 0, NULL, '40', NULL, '{\"code\":\"\",\"parent\":\"\",\"quantity\":\"\"}', NULL, '{\"534\":{\"quantity\":\"0.10\",\"unity\":\"2\"},\"578\":{\"quantity\":\"0.10\",\"unity\":\"2\"}}', '[]', 0),
(548, 6, 'supply', NULL, 'Botella Caroline', 'uJrrTqad', 1, 6, NULL, NULL, NULL, '{\"2\":{\"weight\":\"495\",\"unity\":\"6\"}}', NULL, '[]', 0),
(549, 6, 'supply', NULL, 'Vodka de batalla', '70RRnJfl', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(550, 6, 'recipe', NULL, 'Birria de res ', '1zTzLso9', 0, 2, NULL, '6', NULL, NULL, '{\"293\":{\"quantity\":\".3\",\"unity\":\"2\"},\"551\":{\"quantity\":\"6\",\"unity\":\"2\"},\"552\":{\"quantity\":\"6\",\"unity\":\"2\"},\"325\":{\"quantity\":\"10\",\"unity\":\"6\"}}', '[]', 0),
(551, 6, 'supply', NULL, 'Chambarete', 'dhpZw5W3', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(552, 6, 'supply', NULL, 'Falda de res', '16BGzIJB', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(553, 6, 'supply', NULL, 'Hoja de aguacate', 'lnLG63VP', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(554, 6, 'supply', NULL, 'Clavo de olor', 'wJPMVJrn', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(555, 6, 'supply', NULL, 'Botella de batalla Licor de durazno', 'pvjLWñQ1', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[\"2\"]', 0),
(556, 6, 'supply', NULL, 'Botella de batalla Licor de cafe', 'JY3MKwnz', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(557, 6, 'supply', NULL, 'Botella de batalla Licor de coco', 'oÑyrKrXE', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(558, 6, 'supply', NULL, 'Botella de batalla Licor de melón', 'AYi6F7mR', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(559, 6, 'supply', NULL, 'Botella de batalla Licor de triple sec', 'szZoS6d3', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(560, 6, 'supply', NULL, 'Botella de batalla Daristi de manzana', 'gAXWJGNO', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(561, 6, 'supply', NULL, 'Botella de batalla Daristi de durazno', 'NRyB6dTr', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(562, 6, 'supply', NULL, 'Botella de batalla Don Pancho', 'lI50XxSd', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(563, 6, 'supply', NULL, 'Botella de batalla Crema irlandesa', 'ueD6tz8y', 1, 6, NULL, NULL, NULL, '{\"1\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(564, 6, 'supply', NULL, 'Botella de batalla Licor de naranja', 'AñtHNvzp', 1, 6, NULL, NULL, NULL, '{\"4\":{\"weight\":\"\",\"unity\":\"\"}}', NULL, '[]', 0),
(565, 6, 'supply', NULL, 'Queso Mozzarella', 'uLmlx9jS', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(566, 6, 'supply', NULL, 'Queso Parmesano Rallado', 'hQgFBmAz', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(567, 6, 'supply', NULL, 'Queso Panela', '5ZN0mymd', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(568, 6, 'supply', NULL, 'Mango congelado', 'Tuy5cYwE', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(569, 6, 'supply', NULL, 'Pimientos morron verde', 'NrVb5y5D', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(570, 6, 'supply', NULL, 'Pimiento morron rojo', 'HfaZP27V', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(571, 6, 'supply', NULL, 'pimiento morron amarillo', 'oñv9q0XY', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(572, 6, 'supply', NULL, 'Kastak\'an ', 'yZOJF0q4', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(573, 6, 'supply', NULL, 'Rib-eye', 'PmI3vCSG', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(574, 4, 'sale_menu', NULL, 'Audifonos manos libres k38 rojo', 'zlsOCPT4', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(575, 8, 'work_material', NULL, 'Producto Material de trabajo 1', '4rutwzHz', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(577, 8, 'work_material', NULL, 'Producto Material de trabajo 2', 'lxOxFFjE', 1, 1, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(578, 8, 'supply', NULL, 'Producto Insumo 2', 'H12gKJÑ0', 1, 2, NULL, NULL, NULL, '[]', NULL, '[]', 0),
(579, 8, 'recipe', NULL, 'Producto Receta 2', 'w29YVztG', 0, 2, NULL, '10', NULL, NULL, '{\"534\":{\"quantity\":\"1\",\"unity\":\"2\"},\"578\":{\"quantity\":\"1\",\"unity\":\"2\"}}', '[]', 0),
(580, 4, 'sale_menu', NULL, 'Audifonos manos libres k38 verde', 'OoyJKmZC', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(581, 4, 'sale_menu', NULL, 'Audifonos manos libres k38 negro', 'Nqe1zQÑA', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(582, 4, 'sale_menu', NULL, 'Audifonos manos libres k38 blanco', 'GCxQnpV4', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(583, 4, 'sale_menu', NULL, 'Limpiador de pantallas Prolicom', 'UEQñ8ZvD', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(584, 4, 'sale_menu', NULL, 'Alcohol isopropilico Prolicom', 'Twñ9YsIc', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(585, 4, 'sale_menu', NULL, 'Cable 1hora Usb 2.0 carga y sincroniza Tipo C Negro', '95qZ3fñc', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(586, 4, 'sale_menu', NULL, 'Cable 1hora Usb 2.0 carga y sincroniza V8 Negro', 'nrB3hi86', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(587, 4, 'sale_menu', NULL, 'Cable 1hora Usb 2.0 carga y sincroniza Lightning (Iphone)', 'bWESFZCL', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(588, 4, 'sale_menu', NULL, 'Combo cargador 1hora lightning (Iphone) blanco', 'h1dJpra5', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(589, 4, 'sale_menu', NULL, 'Combo cargador 1hora V8 Negro', '06flxDrz', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(590, 4, 'sale_menu', NULL, 'Combo cargador 1hora Tipo C Negro', 'OHLaKoZW', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(591, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-820 Negro', 'ÑpFbo959', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(592, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-820 Plata', 'eB4znT6U', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(593, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-820 Rojo', 'CcTmw0q0', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(594, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-820 Azul', 'iWKuOÑGY', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(595, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-830 Negro', 'sob198ft', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(596, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-830 Plata', 'c6QDYLIc', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(597, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-830 Rojo', 'JoCYO3Ap', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(598, 4, 'sale_menu', NULL, 'Audifonos inalambricos Ridgeway EAR-830 Azul', 'ÑH2l9Xrn', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(599, 4, 'sale_menu', NULL, 'Audifonos alambricos tipo c Piston Rojo', 'jAGMVW5K', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(600, 4, 'sale_menu', NULL, 'Audifonos alambricos tipo c Piston Negro', '3bYmyjmp', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(601, 4, 'sale_menu', NULL, 'Audifonos alambricos tipo c Piston Azul', 'XPuEjVHg', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(602, 4, 'sale_menu', NULL, 'Audifonos alambricos Ridgeway cierre EAR-78M Blanco', 'IWOjcKVU', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(603, 4, 'sale_menu', NULL, 'Audifonos alambricos Ridgeway cierre EAR-78M Negro', '1G9GpRx3', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(604, 4, 'sale_menu', NULL, 'Audifonos alambricos Ridgeway cierre EAR-78M Rojo', 'sxpvQ1cj', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(605, 4, 'sale_menu', NULL, 'Audifonos alambricos Ridgeway cierre EAR-78M Azul', 'JWP6B2cy', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(606, 4, 'sale_menu', NULL, 'Audifonos alambricos Ridgeway cierre EAR-78M Verde', 'wupEZeaJ', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(607, 4, 'sale_menu', NULL, 'Bluetooth Headset manos libres Ejecutivo v5.0 Maiz ', 'ci5XXkwI', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(608, 4, 'sale_menu', NULL, 'Soporte para celular Holder Maiz HD-006', 'kf2knYGH', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(609, 4, 'sale_menu', NULL, 'Cable HDMI 3 en 1 HDTV', 'hSNhLlaS', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(610, 4, 'sale_menu', NULL, 'Soporte para celular ATVIO pinza V0003', 'Q19f92EQ', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(611, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Azul', 'dCOAjfvD', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(612, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Rojo', 'eziLjbwr', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(613, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Verde', 'elvPvjKM', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(614, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Negro', 'lw7AbFUT', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(615, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Naranja', '950n4HDj', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(616, 4, 'sale_menu', NULL, 'Combo cargador 1hora V8 Blanco', 'lJoEF0Oy', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(617, 4, 'sale_menu', NULL, 'Combo cargador 1hora Tipo C Blanco', 'GuzhnRWÑ', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(618, 4, 'sale_menu', NULL, 'Vacuum food sealer Sellador de comida al vacio', 'WqLsRvaS', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(619, 4, 'sale_menu', NULL, 'Foco ahorrador de energia 72 W Opti 360', 'DYUQF6vQ', 1, 1, '40', NULL, NULL, '[]', '[]', '[]', 0),
(620, 4, 'sale_menu', NULL, 'Linterna Sencilla D-C36 Rojo', 'Uyz56G1B', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(621, 4, 'sale_menu', NULL, 'Linterna Luz UV DT-002', 'TpGYau9D', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(622, 4, 'sale_menu', NULL, 'LInterna Modelo 8810 Type', 'qrfbae8b', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(623, 4, 'sale_menu', NULL, 'Linterna 4 LEDS T9 LJK', 'q88Lixpb', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(624, 4, 'sale_menu', NULL, 'Pila Recargable 1/2 AA ', 'iX23uczL', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(625, 4, 'sale_menu', NULL, 'Cargador Adaptable para pilas LJK DT-808', 'aghQc5S9', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(626, 4, 'sale_menu', NULL, 'Linterna Zoom Headlamp T-S08 Naranja', 'FBGWÑVÑS', 1, 1, '320', NULL, NULL, '[]', '[]', '[]', 0),
(627, 4, 'sale_menu', NULL, 'Protector Gas', 'Cdgjq4eX', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(628, 4, 'sale_menu', NULL, 'Linterna Dongxin A-K03-2B Negro', 'i6omUXHñ', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(629, 4, 'sale_menu', NULL, 'Linterna High power zoom headlamp negro', 'Ad6yxfRh', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(630, 4, 'sale_menu', NULL, 'Linterna pequeña led lateral N Tenck Modelo 20940 Negro', 'CNtqvpqA', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(631, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Blanco', '5mxEZSzx', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(632, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Negro', '1SLVOSZl', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(633, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Azul', '8GIMñrYt', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(634, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Rojo', 'EMkSWC3I', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(635, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Verde', 'IOqqgzñW', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(636, 4, 'sale_menu', NULL, 'Soporte pequeño manitas Morado', 'jlghkbñs', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(637, 4, 'sale_menu', NULL, 'Herramienta Multiuso', 'MGJ7Z6fñ', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(638, 4, 'sale_menu', NULL, 'Soporte  para celular Pop Diseño', 'HzVwNBxx', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(639, 4, 'sale_menu', NULL, 'Soporte  para celular Pop Anillo', 'cCduBLSy', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(640, 4, 'sale_menu', NULL, 'Soporte  para celular Pop 3D', 'ejPsGxñN', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(641, 4, 'sale_menu', NULL, 'Soporte  para celular Pop Anillo Diseño', 'EkWNR6fU', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(642, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Blanco', '1uzÑG2WD', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(643, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Negro', 'weD2kñjS', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(644, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Naranja', 'Dq7N0Gfo', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(645, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Rosa', 'OwFO8m6y', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(646, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Morado', '7ilT5i2f', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(647, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Verde', 'FwHRg3te', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(648, 4, 'sale_menu', NULL, 'Lampara solar Modelo 2098', 'ÑmwGynX0', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(649, 4, 'sale_menu', NULL, 'Lampara solar pequeña sensor yjd-300', '3joToZEÑ', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(650, 4, 'sale_menu', NULL, 'Lampara solar led deformable XF-701', 'qCqndJhE', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(651, 4, 'sale_menu', NULL, 'Bocina bluetooth P5 Plata ', 'RzqGF2IC', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(652, 4, 'sale_menu', NULL, 'Bocina bluetooth P5 Negra', 'tbjfTyHo', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(653, 4, 'sale_menu', NULL, 'USB 2.0 Hub hub-04', 'xkmLC1Fa', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(654, 4, 'sale_menu', NULL, 'Lampara eliminadora de mosquitos', 'CIeP1jzx', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(655, 4, 'sale_menu', NULL, 'Funda Silicon sencilla Airpods Diseño', 'IAhWZFZ3', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(656, 4, 'sale_menu', NULL, 'OTG N Tenck Tipo C Negro', 'VLtDJf0K', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(657, 4, 'sale_menu', NULL, 'OTG N Tenck Tipo C Blanco', 'OrE5mLIW', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(658, 4, 'sale_menu', NULL, 'Cable OTG Tipo C S-k07 Blanco', 'whdZoRcY', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(659, 4, 'sale_menu', NULL, 'Cable USB Hembra a Hembra', '6lqvj942', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(660, 4, 'sale_menu', NULL, 'Cable hibrido V8 y Lightning Rojo', 'asNCzvkc', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(661, 4, 'sale_menu', NULL, 'Cable hibrido V8 y Lightning Morado', 'xBcs9ApS', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(662, 4, 'sale_menu', NULL, 'Cable hibrido V8 y Lightning Verde', 'E6e8e1o7', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(663, 4, 'sale_menu', NULL, 'Cable hibrido V8 y Lightning Blanco', '6qC5U4Cs', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(664, 4, 'sale_menu', NULL, 'Cable hibrido V8 y Lightning Azul', 'MtCcEI7S', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(665, 4, 'sale_menu', NULL, 'Cable 1Hora Blister Tipo C CAB164 Negro', 'glBCJqDl', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(666, 4, 'sale_menu', NULL, 'Cable 1Hora Blister Tipo C CAB164 Blanco', 'ZoaUUDNK', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(667, 4, 'sale_menu', NULL, 'Cable 1Hora Blister CAB186 V8 Blanco', 'PY101qxp', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(668, 4, 'sale_menu', NULL, 'Cable 1Hora Blister CAB186 V8 Negro', 'nmMaqFPñ', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(669, 4, 'sale_menu', NULL, 'Cable 1Hora Blister CAB187  Lightning Iphone Blanco', 'up37Ñnvs', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(670, 4, 'sale_menu', NULL, 'Cable auxiliar tela Rojo', 'rOR7GNO3', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(671, 4, 'sale_menu', NULL, 'Cable 1Hora caja CAB251 Tipo C 3A Blanco', 'csdijzny', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(672, 4, 'sale_menu', NULL, 'Cable auxiliar tela Azul', 'LpPbzdUy', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(673, 4, 'sale_menu', NULL, 'Cable auxiliar tela Blanco', 'GkKjDCni', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(674, 4, 'sale_menu', NULL, 'Cable auxiliar tela Negro', 'yW6qiyMD', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(675, 4, 'sale_menu', NULL, 'Cable auxiliar tela Verde', 'zv9cNWWu', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(676, 4, 'sale_menu', NULL, 'Cable auxiliar resorte Blanco', 'nEvtTAew', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(677, 4, 'sale_menu', NULL, 'Cable auxiliar resorte Rojo', 'JJ863g8O', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(678, 4, 'sale_menu', NULL, 'Cable auxiliar resorte Azul', 'ah8HcfIp', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(679, 4, 'sale_menu', NULL, 'Cable auxiliar resorte Negro', 'tuxKJcqñ', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(680, 4, 'sale_menu', NULL, 'Cable auxiliar Metalico Rojo', 'n9ZkrLDT', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(681, 4, 'sale_menu', NULL, 'Cable Sencillo 3 Metros V8 Blanco', 'Yx860Rsm', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(682, 4, 'sale_menu', NULL, 'Cable Sencillo 3 Metros V8 Negro', '4jbb0UaE', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(683, 4, 'sale_menu', NULL, 'Cable Sencillo 3 Metros V8 Rosa', 'a4pCfS2I', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(684, 4, 'sale_menu', NULL, 'Cable Sencillo 3 Metros V8 Verde', 'M7qdzSJC', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(685, 4, 'sale_menu', NULL, 'Cable Sencillo 3 Metros V8 Azul', '5Dfi9d7i', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(686, 4, 'sale_menu', NULL, 'Dashcam Camara para carro CAM002', 'xj1SfDÑf', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(687, 4, 'sale_menu', NULL, 'Selfie stick Tripoide Maiz JG-012 Rosa', 'MoBZmIRh', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(688, 4, 'sale_menu', NULL, 'Selfie stick Tripoide Maiz JG-012 Azul', '2iazLlHB', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(689, 4, 'sale_menu', NULL, 'Selfie stick Tripoide Maiz JG-012 Blanco', 'zJhuKlKx', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(690, 4, 'sale_menu', NULL, 'Selfie stick Tripoide Maiz JG-012 Negro', 'qiJuS2ud', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(691, 4, 'sale_menu', NULL, 'Selfie stick Yunfeng  1288 ', 'CxTXDpYI', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(692, 4, 'sale_menu', NULL, 'Soporte para celular Pinza KW13', '8kTW6Gvq', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(693, 4, 'sale_menu', NULL, 'Locion limpiadora Prolicom', 'JzUNvw6T', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(694, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable Dorado', 'KLrfiwÑz', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(695, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable  Negro', 'wPatÑg0U', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(696, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable Plata', 'QUjLFv85', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(697, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable  Rojo', 'KAtAb8DM', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(698, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable  Azul', '3MCYEciw', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(699, 4, 'sale_menu', NULL, 'Cable HDTV Plug And Play  HDMI Iphone', 'qzSbrFnt', 1, 1, '500', NULL, NULL, '[]', '[]', '[]', 0),
(700, 4, 'sale_menu', NULL, 'Transmisor y receptor Bluetooth Maiz CM-014', '3BvtcbSo', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(701, 4, 'sale_menu', NULL, 'Turbo cargador para auto 1 Hora Tipo C 3.0A GAR116', 'IX7hnNHl', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(702, 4, 'sale_menu', NULL, 'Cubo Cargador Diciembre V8 2.1A M-801 Negro', 'tgrrnSxC', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(703, 4, 'sale_menu', NULL, 'Cubo cargador doble entrada 3.1A Maiz CH-069 Blanco', 'Eu72yWrY', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(704, 4, 'sale_menu', NULL, 'Estacion de carga General Electric 3.1A 15 Watt Modelo 13461', 'fzKTWuWr', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(705, 4, 'sale_menu', NULL, 'SIM AT&T', 'stV1Q6hx', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(706, 4, 'sale_menu', NULL, 'SIM UNEFON ', 'e2hLQXLW', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(707, 4, 'sale_menu', NULL, 'SIM Movistar ', '6WQMDfbw', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(708, 4, 'sale_menu', NULL, 'SIM Telcel', '17Sub9kC', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(709, 4, 'sale_menu', NULL, 'SIM WIMO', 'fatqbymX', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(710, 4, 'sale_menu', NULL, 'Encendedor electrico prisma Tornasol', '96hO2QNi', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(711, 4, 'sale_menu', NULL, 'Encendedor electrico prisma Plata', 'IuPyy1OJ', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(712, 4, 'sale_menu', NULL, 'Encendedor electrico prisma Negro', '4N8UMeW1', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(713, 4, 'sale_menu', NULL, 'Encendedor electrico prisma Dorado', '29zbyTfX', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(714, 4, 'sale_menu', NULL, 'Encendedor electrico sensor Plata', 'BS9sÑSWa', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(715, 4, 'sale_menu', NULL, 'Encendedor electrico sensor Dorado', 'Cjgnn1yK', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(716, 4, 'sale_menu', NULL, 'Encendedor electrico sensor Negro', '7VbmRY6z', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(717, 4, 'sale_menu', NULL, 'Encendedor electrico sensor Azul', 'aaMEwHpy', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(718, 4, 'sale_menu', NULL, 'Encendedor electrico sensor Tornasol', 'RL5PXrCG', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(719, 4, 'sale_menu', NULL, 'Encendedor electrico explorer Negro', 'f40ckV0Y', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(720, 4, 'sale_menu', NULL, 'Encendedor electrico explorer Rojo', 'yyk6gnwl', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(721, 4, 'sale_menu', NULL, 'Encendedor electrico explorer Azul', '3TtnMt5S', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(722, 4, 'sale_menu', NULL, 'Encendedor electrico explorer Camuflaje', 'BfP3UEmb', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(723, 4, 'sale_menu', NULL, 'Encendedor electrico rectangular Dorado', 'bVñzD5wH', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(724, 4, 'sale_menu', NULL, 'Encendedor electrico rectangular Plata', 'sZndvr5q', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(725, 4, 'sale_menu', NULL, 'Encendedor electrico rectangular Azul', 'BHD0Wmb8', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(726, 4, 'sale_menu', NULL, 'Encendedor electrico rectangular Negro', 'ñP1YTLhW', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(727, 4, 'sale_menu', NULL, 'Encendedor electrico rectangular Tornasol', '50caVMDH', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(728, 4, 'sale_menu', NULL, 'Mini fan espejo ventilador Aguacate ', 'f6kW16Ku', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(729, 4, 'sale_menu', NULL, 'Mini fan espejo ventilador Perro', 'FÑLu9H7O', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(730, 4, 'sale_menu', NULL, 'Mini fan espejo ventilador Oso', 'LnIkxñvL', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(731, 4, 'sale_menu', NULL, 'Mini fan espejo ventilador Cerdito', 'eNzyUcxz', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(732, 4, 'sale_menu', NULL, 'Soporte gamepad gatillos y ventilador SP+', '6vW6raNÑ', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(733, 4, 'sale_menu', NULL, 'Dispensador para agua FOL FW-04 Blanco', 'GZKUFwXu', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(734, 4, 'sale_menu', NULL, 'Dispensador para agua FOL FW-04 Negro', '2gj3VzQE', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(735, 4, 'sale_menu', NULL, 'Bateria portatil 5000 mah 1Hora GAR123 Negro', 'wP3w4Pwz', 1, 1, '280', NULL, NULL, '[]', '[]', '[]', 0),
(736, 4, 'sale_menu', NULL, 'Bateria portatil 5000 mah 1Hora GAR123 Blanco', 'rTF0xuK0', 1, 1, '280', NULL, NULL, '[]', '[]', '[]', 0),
(737, 4, 'sale_menu', NULL, 'Bateria portatil 10000 mah 1Hora GAR140 Negro', 'UIMxSwvv', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(738, 4, 'sale_menu', NULL, 'Tripoide pequeño modelo h-08 Rojo', 'ñbe1m9Cd', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(739, 4, 'sale_menu', NULL, 'Tripoide pequeño modelo h-08 Azul', 'ROyñv3Uv', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(740, 4, 'sale_menu', NULL, 'Tripoide pequeño modelo h-08 Negro', 'pRgAPYmY', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(741, 4, 'sale_menu', NULL, 'Bascula gramera modelo 10054', 'W9piULoE', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(742, 4, 'sale_menu', NULL, 'Soporte para celular tipo stand microfono HD-28', 'PSyqeedñ', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(743, 4, 'sale_menu', NULL, 'Estacion de carga General Electric 2.1A modelo 13450', '6yviH4qm', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(744, 4, 'sale_menu', NULL, 'Soporte para celular en retrovisor HD-097', 'ñVl6tler', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(745, 4, 'sale_menu', NULL, 'Receptor Bluetooth Maiz CM-013 Negro', 'ÑW43ROEC', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(746, 4, 'sale_menu', NULL, 'Receptor Bluetooth Maiz CM-013 Blanco', '7xjtv4rO', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(747, 4, 'sale_menu', NULL, 'Receptor Bluetooth Maiz CM-013 Rojo', 'sWM7jkUT', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(748, 4, 'sale_menu', NULL, 'Receptor Bluetooth Maiz CM-013 Verde', 'Jgr6PTyI', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(749, 4, 'sale_menu', NULL, 'Receptor Bluetooth Maiz CM-013 Azul', '9M6jcyhH', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(750, 4, 'sale_menu', NULL, 'Kit de viaje Cables Mophie', '0udxBWmh', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(751, 4, 'sale_menu', NULL, 'Lampara Proyector Star Master Morado', '44ogdDkx', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(752, 4, 'sale_menu', NULL, 'Lampara Proyector Star Master Rosa', 'rsOÑaZ5N', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(753, 4, 'sale_menu', NULL, 'Lampara Proyector Star Master Azul', 'YV9Uz8ir', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(754, 4, 'sale_menu', NULL, 'Bocina Bluetooth One Der V2 Blanca ', 'wkcG4vña', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(755, 4, 'sale_menu', NULL, 'Bocina Bluetooth One Der V2 Cafe', 'cBliK2VD', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(756, 4, 'sale_menu', NULL, 'Bocina Bluetooth One Der V2 Negro', 'JaAzjbzp', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(757, 4, 'sale_menu', NULL, 'Bocina Bluetooth Yuhai U82 Vino ', 'hTÑX9UiV', 1, 1, '500', NULL, NULL, '[]', '[]', '[]', 0),
(758, 4, 'sale_menu', NULL, 'Bocina Bluetooth Yuhai U82 Cafe', 't83Fy6Il', 1, 1, '500', NULL, NULL, '[]', '[]', '[]', 0),
(759, 4, 'sale_menu', NULL, 'Soporte para celular flexible de pinza Negro', 'PoFM3H8h', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(760, 4, 'sale_menu', NULL, 'Soporte para celular flexible de pinza Blanco', 'WEV0E8b1', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(761, 4, 'sale_menu', NULL, 'Soporte para celular flexible de pinza Azul', 'GwAORTCA', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(762, 4, 'sale_menu', NULL, 'Soporte para celular flexible de pinza Rosa', 'dr8HkV8V', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(763, 4, 'sale_menu', NULL, 'Soporte para celular flexible de pinza Morado', 'wClQHi1h', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(764, 4, 'sale_menu', NULL, 'Mouse alambrico resplandeciente Buytiti V02', 'ItUQ2ojb', 1, 1, '170', NULL, NULL, '[]', '[]', '[]', 0),
(765, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Verde', 'nARre93U', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(766, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Naranja', 'nsQTFQZC', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(767, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Azul', 'O59sfwYG', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(768, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Negro', 'RfJ3rÑeh', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(769, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Blanco', '31vkfy7n', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(770, 4, 'sale_menu', NULL, 'Fundas para celular de brazo Rojo', '7ZK5dR8s', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(771, 4, 'sale_menu', NULL, 'Bascula Portable electronica AAA-2', '38vgF07S', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(772, 4, 'sale_menu', NULL, 'Soporte para celular clip Solidex ST-4', 'I3SuBdDZ', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(773, 4, 'sale_menu', NULL, 'Gel pad', 'ByeOXZVK', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(774, 4, 'sale_menu', NULL, 'Linterna 288', 'CYXgKwqh', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(775, 4, 'sale_menu', NULL, 'Cable Adaptador tipo C a jack 3.5 Hembra Socket converter', 'RXGvHkw6', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(776, 4, 'sale_menu', NULL, 'Encendedor electrico sencillo resistencia cafe', '5n8SYRlG', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(777, 4, 'sale_menu', NULL, 'Encendedor electrico sencillo resistencia Azul', 'ñN1JmFWG', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(778, 4, 'sale_menu', NULL, 'Encendedor electrico sencillo resistencia Rojo', 'QXF4P97R', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(779, 4, 'sale_menu', NULL, 'Encendedor electrico sencillo resistencia Negro', 'lplAvLzQ', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(780, 4, 'sale_menu', NULL, 'Soporte chupon VIP Dorado', 'lZZ3hteK', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(781, 4, 'sale_menu', NULL, 'Soporte chupon VIP Rosa', '6zaDTI0g', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(782, 4, 'sale_menu', NULL, 'Estacion de carga General Electric 2.1A modelo 13456', 'oWnYiqjr', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(783, 4, 'sale_menu', NULL, 'Linterna pequeña led lateral Camuflaje', 'Eno8czbG', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(784, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Morado', '7OEMxiñG', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0);
INSERT INTO `products` (`id`, `account`, `type`, `avatar`, `name`, `token`, `inventory`, `unity`, `price`, `portion`, `formula`, `contents`, `supplies`, `categories`, `blocked`) VALUES
(785, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Dorado', '8wonrBñA', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(786, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Plata', 'XÑ0cñg52', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(787, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Cafe', 'jTB81ñKf', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(788, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Negro', 'OIKYPñqB', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(789, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Rojo', 'tW5KkXXB', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(790, 4, 'sale_menu', NULL, 'Bala cargador de carro GAR089 Azul', 'ZAQVrVi0', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(791, 4, 'sale_menu', NULL, 'Cubo cargador led Maiz Fashion 3.0 CH-064', '97KtZtNo', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(792, 4, 'sale_menu', NULL, 'Combo cargador Diciembre 2.0A V8 Negro', 'sOvDoqXQ', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(793, 4, 'sale_menu', NULL, 'Combo cargador Diciembre 2.0A V8 Blanco', 'sq3b118H', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(794, 4, 'sale_menu', NULL, 'Cable Lyzon 3A Lightning (Iphone) CBV8A2 Negro ', 'XKñK1rsU', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(795, 4, 'sale_menu', NULL, 'Bocina Bluetooth XM2 Naranja', 'ñOGFNElq', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(796, 4, 'sale_menu', NULL, 'Bocina Bluetooth XM2 Cafe', 'zx5ÑÑmQb', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(797, 4, 'sale_menu', NULL, 'Aro Luz pequeño SG-11 Blanco', 'yMNciOdC', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(798, 4, 'sale_menu', NULL, 'Aro Luz pequeño SG-11 Negro', 'm2vg33lP', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(799, 4, 'sale_menu', NULL, 'Aro Luz pequeño SG-11Rosa', 'rZIers8l', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(800, 4, 'sale_menu', NULL, 'Aro Luz pequeño SG-11 Azul', 'jJfTk0Rd', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(801, 4, 'sale_menu', NULL, 'Foco ahorrador de energia HL-LED 12W-A ', 'Dzb2tu3g', 1, 1, '70', NULL, NULL, '[]', '[]', '[]', 0),
(802, 4, 'sale_menu', NULL, 'Cable 1Hora 2 Metros CAB185 Tipo C Negro', 'elXg3uAT', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(803, 4, 'sale_menu', NULL, 'Cable carga Lightning Elux BO-X70 Beige', 'jr2kÑhjB', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(804, 4, 'sale_menu', NULL, 'Cable Carga Fashion LED tipo C Verde', 'ZU0Rhq3D', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(805, 4, 'sale_menu', NULL, 'Gamepad 5 en 1 GM-51', 'TWzBLA8H', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(806, 4, 'sale_menu', NULL, 'Cable 1Hora 2 Metros CAB178 V8 Negro', 'Xme7K5QU', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(807, 4, 'sale_menu', NULL, 'Bocina Bluetooth  Lampara Kinkete T7', 'Mj7E4wtq', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(808, 4, 'sale_menu', NULL, 'Dispensador para agua TY-01 Blanco', '047DDCHW', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(809, 4, 'sale_menu', NULL, 'Dispensador para agua TY-01 Negro', 'eñ4PCIft', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(810, 4, 'sale_menu', NULL, 'Control Analogico para Playstation 2 beotes', 'ÑGMfQEwq', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(811, 4, 'sale_menu', NULL, 'Alcancia Casa perro ATM09', '7a47F9tU', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(812, 4, 'sale_menu', NULL, 'Soporte para celular para Motocicleta Bicicleta estuche ZB-01', 'LubEl608', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(813, 4, 'sale_menu', NULL, 'Soporte para celular elastico k3119 Negro', 'tjHCMBA2', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(814, 4, 'sale_menu', NULL, 'Soporte para celular elastico k3119 Azul', 'BVgjWytK', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(815, 4, 'sale_menu', NULL, 'Soporte para celular elastico k3119 Rojo', 'ckLUñWF5', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(816, 4, 'sale_menu', NULL, 'Soporte Wireless Car Phone Holder  Rojo', 'EXabmWXR', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(817, 4, 'sale_menu', NULL, '	Fundas para celular de brazo Morado', 'TzxuhgTu', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(818, 4, 'sale_menu', NULL, 'Cable 1Hora 2 Metros CAB185 Tipo C Blanco', 'b55qeipe', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(819, 4, 'sale_menu', NULL, 'Lampara solar pequeña sensor TYN-BD-2W', 'Jvtt1yKy', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(820, 4, 'sale_menu', NULL, 'Mochilita para celular TD-031 Negro', 'CW2aL2RJ', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(822, 4, 'sale_menu', NULL, 'Mochilita para celular TD-031 Rojo', '38Qgqf7W', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(823, 4, 'sale_menu', NULL, 'Mochilita para celular TD-031 Gris', 'D0MMfñgr', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(824, 4, 'sale_menu', NULL, 'Mochilita para celular TD-031 Azul', 'FEWÑvmcf', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(825, 4, 'sale_menu', NULL, '	Cable 1Hora caja CAB252 Tipo C a Tipo C 3A Blanco', 'KWom6OsU', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(826, 4, 'sale_menu', NULL, 'Audifonos alambricos Maiz EJ-125 Negro', 'AhqJLfLl', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(827, 4, 'sale_menu', NULL, 'Audifonos bluetooth Maiz Metal Stereo Pink Yarrow BEJ-032 Negro', 'iiVV1kWF', 1, 1, '220', NULL, NULL, '[]', '[]', '[]', 0),
(828, 4, 'sale_menu', NULL, 'Lente lupa para celular cartera Negro', 'JqgBjD3h', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(829, 4, 'sale_menu', NULL, 'Lente lupa para celular cartera Azul', 'wzcGCmq3', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(830, 4, 'sale_menu', NULL, 'Linterna 1202 Type Rosa', 'bpT1B5Yc', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(831, 4, 'sale_menu', NULL, 'Linterna 1202 Type Negro', 'mmzja9si', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(832, 4, 'sale_menu', NULL, 'Linterna 1202 Type Azul', 'VÑpJcxSR', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(833, 4, 'sale_menu', NULL, 'Linterna 1202 Type Rojo', 'bmalkAv7', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(834, 4, 'sale_menu', NULL, 'Linterna 1202 Type Dorado', '58qSDYU8', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(835, 4, 'sale_menu', NULL, 'Audifonos Inalambricos Stelau B-20 Negro', 'EL0Q0WSc', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(836, 4, 'sale_menu', NULL, 'Audifonos Inalambricos Stelau B-20 Blanco', 'ñyxhD0f1', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(837, 4, 'sale_menu', NULL, 'Audifonos inalambricos estuche 1Hora AUT114 Negro', 'kbgYh4sY', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(838, 4, 'sale_menu', NULL, 'Laser Pointer D.X H851', 'ñxDj0hFN', 1, 1, '320', NULL, NULL, '[]', '[]', '[]', 0),
(839, 4, 'sale_menu', NULL, 'Mouse Inalambrico Weibo RF-2830B Azul', '8dMDGAes', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(840, 4, 'sale_menu', NULL, 'Mouse Inalambrico Weibo RF-2830B Rojo', 'nKñv3z84', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(841, 4, 'sale_menu', NULL, 'Mouse Inalambrico Weibo RF-2830B Negro', 'DSgxBfP8', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(842, 4, 'sale_menu', NULL, 'Cargador adaptador para Laptop multipuntas JY-120w', 'Y6VdIkcX', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(843, 4, 'sale_menu', NULL, 'Cargador adaptador para Laptop multipuntas SHH-70w', '7tNobFño', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(844, 4, 'sale_menu', NULL, 'Bateria portatil 10000 mah 1Hora GAR140 Azul', 'o0V75pCC', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(845, 4, 'sale_menu', NULL, 'Bateria portatil  Powerbank 10000 mah 1Hora GAR141 Negro', 'UgL7Qbq1', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(846, 4, 'sale_menu', NULL, 'Soporte para celular para Motocicleta Bicicleta estuche HD-005', 'aFmRmo3o', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(847, 6, 'supply', NULL, 'Queso feta', 'hGLF9Ewñ', 1, 2, NULL, NULL, NULL, '[]', NULL, '[\"1\"]', 0),
(848, 4, 'sale_menu', NULL, '	Linterna Sencilla D-C36 Negro', 'teVq5Mñ9', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(849, 4, 'sale_menu', NULL, 'Bocina Kinkete J12 Azul', 'W7WiQÑiH', 1, 1, '350', NULL, NULL, '[]', '[]', '[]', 0),
(850, 4, 'sale_menu', NULL, '	Bocina Bluetooth XM6 Series Cafe', '7ZICP7B7', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(851, 4, 'sale_menu', NULL, 'Bocina Bluetooth XM6 Series Naranja', 'qGpjQYRx', 1, 1, '550', NULL, NULL, '[]', '[]', '[]', 0),
(852, 4, 'sale_menu', NULL, 'Ventilador de mano SS-2 Eternal classics Azul', 'D7gyvjSF', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(853, 4, 'sale_menu', NULL, 'Automatic Screen Attach Machine', 'oE58ZbjÑ', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(854, 4, 'sale_menu', NULL, 'Multicontacto Heng Lian Converter HL6N', 'DrQlfjTy', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(855, 4, 'sale_menu', NULL, 'Cable generico sencillo V8 Negro', '8g6lToHñ', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(856, 4, 'sale_menu', NULL, 'Cable generico sencillo V8 Blanco', 'E6ñVHnGW', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(857, 4, 'sale_menu', NULL, 'Linterna de cabeza Multifunction headlights S628B Camuflage', '2gqTK20u', 1, 1, '320', NULL, NULL, '[]', '[]', '[]', 0),
(858, 4, 'sale_menu', NULL, 'Soporte para bicicleta motocicleta TL-01A', '9bYIvbMu', 1, 1, '180', NULL, NULL, '[]', '[]', '[]', 0),
(859, 4, 'sale_menu', NULL, 'Lampara de escritorio Creative Led Lamp Rosa', 'mYh1A78O', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(860, 4, 'sale_menu', NULL, 'Mochilita para celular Verde k.com', 'KXYLiA6l', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(861, 4, 'sale_menu', NULL, 'Audifonos Inalambricos Stelau 24H AUT091 Negro', 'n1FGEx4t', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(862, 4, 'sale_menu', NULL, 'Audifonos Inalambricos Stelau 24H AUT091 Blanco', 'SoHsDGO1', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(863, 4, 'sale_menu', NULL, 'Bolsa impermeable para celular Azul', 'AOkuhejq', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(864, 4, 'sale_menu', NULL, 'OTG Heng Lian V8 Negro', 'n8xagJAG', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(865, 4, 'sale_menu', NULL, 'OTG Heng Lian V8 Blanco', 'kLl4iRMC', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(866, 4, 'sale_menu', NULL, 'Patito broma', 'xqES8EmC', 1, 1, '70', NULL, NULL, '[]', '[]', '[]', 0),
(867, 4, 'sale_menu', NULL, 'Cable auxiliar con microfono Negro', 'JGeO5Wh1', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(868, 4, 'sale_menu', NULL, 'Linterna 4 LEDS D.X P70 A-733', 'Q0ajzlJO', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(869, 4, 'sale_menu', NULL, 'Foco ahorrador de energia HL-LED 24W-A', '8cUOsO8p', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(870, 4, 'sale_menu', NULL, 'Receptor Bluetooth y cargador para carro K broad KCB-910', 'H6ceHxMc', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(871, 4, 'sale_menu', NULL, 'Bateria portatil Powerbank 5000 mah Guess ', 'V1HX5MHO', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(872, 4, 'sale_menu', NULL, 'Bocina Bluetooth Mobile TV Peterhot M55 Gris', 'LW6Y6I6v', 1, 1, '400', NULL, NULL, '[]', '[]', '[]', 0),
(873, 4, 'sale_menu', NULL, 'Cable 1hora Usb 2.0 carga y sincroniza Tipo C Blanco', 'hgsmCCtz', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(874, 4, 'sale_menu', NULL, 'Cable 1hora Usb 2.0 carga y sincroniza V8 Blanco', '7SRebmhZ', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(875, 4, 'sale_menu', NULL, 'Cubo Cargador K3 Kingboard Negro 2.0', 'a0yLkREZ', 1, 1, '80', NULL, NULL, '[]', '[]', '[]', 0),
(876, 4, 'sale_menu', NULL, 'Dispensador para agua WP-01 Blanco', 'o13QWUVn', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(877, 4, 'sale_menu', NULL, 'Dispensador para agua WP-01 Negro', '3xkrGwPS', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(878, 4, 'sale_menu', NULL, 'Audifonos manos libres Owii OW-37 Crema', 'Ñ8P4bW3h', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(879, 4, 'sale_menu', NULL, 'Cable auxiliar Metalico Rosa', 'YBE5XnZS', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(880, 4, 'sale_menu', NULL, 'Cable generico sencillo Lightning (iphone) Blanco', 'KsZh57WL', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(881, 4, 'sale_menu', NULL, 'Cable Magnetico tipo c v8 Lightning X-Cable Verde', 'SsuyVou1', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(882, 4, 'sale_menu', NULL, 'Cable auxiliar Metalico Plata', 'MuUBdÑC7', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(883, 4, 'sale_menu', NULL, 'Cable generico sencillo V3 Negro', 'XL0EDERd', 1, 1, '50', NULL, NULL, '[]', '[]', '[]', 0),
(884, 4, 'sale_menu', NULL, 'OTG Fashion Android Bugdroid V8 Naranja', 'MEc2ñ2e2', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(885, 4, 'sale_menu', NULL, 'Cable generico sencillo multi salidas V8 Jack 3.5 Negro', 'FqzUh1o0', 1, 1, '100', NULL, NULL, '[]', '[]', '[]', 0),
(886, 4, 'sale_menu', NULL, 'Cargador Universal LCD TT-7001 Blanco', 'uT5kHoÑq', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(887, 4, 'sale_menu', NULL, 'Nivel Levelpro4 Laser', '6w1rKNKw', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(888, 4, 'sale_menu', NULL, 'Encendedor electrico cilindro Dorado', 'vdÑ4d6ñA', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(889, 4, 'sale_menu', NULL, 'Luz para bicicleta Laser tail light', '0YehYGmo', 1, 1, '120', NULL, NULL, '[]', '[]', '[]', 0),
(891, 4, 'sale_menu', NULL, 'AUdifonos inalambricos CAT STN-28 Negro', '7B3KÑ07x', 1, 1, '600', NULL, NULL, '[]', '[]', '[]', 0),
(892, 4, 'sale_menu', NULL, 'AUdifonos inalambricos CAT STN-28 Lila', '0o4rDoEt', 1, 1, '600', NULL, NULL, '[]', '[]', '[]', 0),
(893, 4, 'sale_menu', NULL, 'Soporte para celular M12 Multi-position Blanco', 'sYmvxVIq', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(894, 4, 'sale_menu', NULL, 'Selfie stick SelfieCom Y9 Negro', 'pkuSOA4t', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0),
(895, 4, 'sale_menu', NULL, 'Soporte gamepad gatillos W11+', 'BpNtnÑ5K', 1, 1, '200', NULL, NULL, '[]', '[]', '[]', 0),
(896, 4, 'sale_menu', NULL, 'Mouse alambrico resplandeciente Diciembre D-8', 'DUñIKuzA', 1, 1, '220', NULL, NULL, '[]', '[]', '[]', 0),
(897, 4, 'sale_menu', NULL, 'Audifonos inalambricos estuche Ridgeway EAR-B311', 'BG86sSLd', 1, 1, '450', NULL, NULL, '[]', '[]', '[]', 0),
(898, 4, 'sale_menu', NULL, 'Audifonos alambricos Super Bass SM-02 Rojo', 'V2IL824m', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(899, 4, 'sale_menu', NULL, 'Audifonos alambricos Super Bass SM-02 Azul', 'qUXVJNjU', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(900, 4, 'sale_menu', NULL, 'Audifonos alambricos Super Bass SM-02 Negro', 'Tf0Tfz5W', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(901, 4, 'sale_menu', NULL, 'Audifonos alambricos Super Bass SM-02 Blanco', 'cswx9FCD', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(902, 4, 'sale_menu', NULL, 'Linterna Mediana led lateral Power Style Modelo A-737 Negro', 'f06agb8m', 1, 1, '300', NULL, NULL, '[]', '[]', '[]', 0),
(903, 4, 'sale_menu', NULL, 'Soporte magnetico para celular 1Hora PJ033 Negro', 'WQHgikak', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(904, 4, 'sale_menu', NULL, 'Soporte magnetico para celular 1Hora PJ033 Plata', 'scygvPE3', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(905, 4, 'sale_menu', NULL, 'Soporte magnetico para celular 1Hora PJ033 Dorado', 'XSiMjQOu', 1, 1, '150', NULL, NULL, '[]', '[]', '[]', 0),
(906, 4, 'sale_menu', NULL, 'Cubo Cargador Diciembre V8 2.1A M-801 Blanco', 'gA6lh2Gp', 1, 1, '250', NULL, NULL, '[]', '[]', '[]', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_categories`
--

CREATE TABLE `products_categories` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `level` text NOT NULL,
  `sale_menu` tinyint(1) NOT NULL,
  `supply` tinyint(1) NOT NULL,
  `recipe` tinyint(1) NOT NULL,
  `work_material` tinyint(1) NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products_categories`
--

INSERT INTO `products_categories` (`id`, `account`, `name`, `level`, `sale_menu`, `supply`, `recipe`, `work_material`, `blocked`) VALUES
(1, 6, 'Alimentos', '1', 1, 1, 0, 0, 0),
(2, 6, 'Bebidas', '2', 1, 1, 0, 0, 0),
(3, 6, 'Licores y cremas', '2', 1, 0, 0, 0, 0),
(4, 6, 'Cerveza', '2', 1, 0, 0, 0, 0),
(5, 6, 'Vodka', '2', 1, 0, 0, 0, 0),
(6, 6, 'Tequila', '2', 1, 0, 0, 0, 0),
(7, 6, 'Ron', '2', 1, 0, 0, 0, 0),
(8, 6, 'Whisky', '2', 1, 0, 0, 0, 0),
(9, 6, 'Ginebras', '2', 1, 0, 0, 0, 0),
(10, 6, 'Mezcal', '2', 1, 0, 0, 0, 0),
(11, 6, 'Brandy', '2', 1, 0, 0, 0, 0),
(12, 6, 'Micheladas', '2', 1, 0, 0, 0, 0),
(13, 6, 'Cocktelería', '2', 1, 0, 0, 0, 0),
(14, 6, 'Sin alcohol', '2', 1, 1, 0, 0, 0),
(15, 6, 'Mojitos', '2', 1, 0, 0, 0, 0),
(16, 6, 'Daiquiris', '2', 1, 0, 0, 0, 0),
(17, 6, 'Margaritas', '2', 1, 0, 0, 0, 0),
(20, 8, 'Alimentos', '1', 1, 1, 1, 0, 0),
(21, 8, 'Bebidas', '1', 1, 1, 1, 0, 0),
(22, 8, 'Oficina', '1', 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_contents`
--

CREATE TABLE `products_contents` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `amount` text NOT NULL,
  `unity` bigint(20) NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products_contents`
--

INSERT INTO `products_contents` (`id`, `account`, `amount`, `unity`, `blocked`) VALUES
(1, 6, '700', 7, 0),
(2, 6, '750', 7, 0),
(3, 6, '695', 7, 0),
(4, 6, '1', 6, 0),
(7, 6, '20', 6, 0),
(8, 6, '2', 6, 0),
(9, 8, '700', 7, 0),
(10, 8, '750', 7, 0),
(11, 8, '695', 4, 0),
(12, 8, '20', 6, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_unities`
--

CREATE TABLE `products_unities` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) DEFAULT NULL,
  `code` char(8) NOT NULL,
  `name` text NOT NULL,
  `order` text DEFAULT NULL,
  `system` tinyint(1) NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products_unities`
--

INSERT INTO `products_unities` (`id`, `account`, `code`, `name`, `order`, `system`, `blocked`) VALUES
(1, NULL, 'HY763H8O', '{\"es\":\"Piezas\",\"en\":\"Pieces\"}', '1', 1, 0),
(2, NULL, 'MKJHTYIA', '{\"es\":\"Kilogramos\",\"en\":\"Kilograms\"}', '2', 1, 0),
(3, NULL, 'SHBJ9876', '{\"es\":\"Gramos\",\"en\":\"Grams\"}', '3', 1, 0),
(4, NULL, '098YH65W', '{\"es\":\"Miligramos\",\"en\":\"Miligrams\"}', '4', 1, 0),
(5, NULL, '456789HY', '{\"es\":\"Kilolitros\",\"en\":\"Kiloliters\"}', '5', 1, 0),
(6, NULL, 'JU76GF59', '{\"es\":\"Litros\",\"en\":\"Liters\"}', '6', 1, 0),
(7, NULL, 'AXDE5TB2', '{\"es\":\"Mililitros\",\"en\":\"Mililiters\"}', '7', 1, 0),
(10, NULL, 'WDTG34CF', '{\"es\":\"Onza Fluida\",\"en\":\"Fluid ounce\"}', '8', 1, 0),
(11, NULL, '4FT5BQ7K', '{\"es\":\"Onza de peso\",\"en\":\"Weight ounce\"}', '9', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `providers`
--

CREATE TABLE `providers` (
  `id` bigint(20) NOT NULL,
  `account` bigint(20) NOT NULL,
  `avatar` text DEFAULT NULL,
  `name` text NOT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `fiscal` text DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `providers`
--

INSERT INTO `providers` (`id`, `account`, `avatar`, `name`, `email`, `phone`, `fiscal`, `blocked`) VALUES
(1, 6, NULL, 'International Brand Spirits', NULL, '{\"country\":\"52\",\"number\":\"58246419\"}', '{\"id\":\"IBS1711295E1\",\"name\":\"IBS\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"ZONA HOTELERA\"}', 0),
(2, 6, NULL, 'Mezcal Elejido', NULL, '{\"country\":\"52\",\"number\":\"9981015056\"}', '{\"id\":\"FDAG760809S96\",\"name\":\"Gerardo Fabian Davila\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"Privada de cozumel Mza 4 lote 1 casa 28 smza 47 \"}', 0),
(3, 6, NULL, 'DUERO', NULL, '{\"country\":\"52\",\"number\":\"9988882084\"}', '{\"id\":\"ADU800131T10\",\"name\":\"ABARROTERA DEL DUERO SA DE CV\",\"country\":\"MEX\",\"state\":\"23\",\"address\":\"Avenida sayil manzana 05 loten02 interior mod120.121pb, sm 6 \"}', 0),
(4, 6, NULL, 'Oscar Andres', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Oscar Andres Lopez\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(5, 6, NULL, 'Goyeo', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Grupo Goyeo sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(6, 6, NULL, 'Benito', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Benito Andres Sanchez Palma\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(7, 6, NULL, 'Veronica', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Veronica Patricia Almazan Elizondo\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(8, 6, NULL, 'Cedis', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Alimentos Elgeso sa de cv\",\"country\":\"MEX\",\"state\":\"23\",\"address\":\"cancun\"}', 0),
(9, 6, NULL, 'Oxxo', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Cadena Comercial Oxxo sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(10, 6, NULL, 'Zacbeh', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Comercializadora Sachbe sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(11, 6, NULL, 'cervezas', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Cervecer\\u00eda Cuauht\\u00e9moc Moctezuma sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(12, 6, NULL, 'Mauricio', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Mauricio S\\u00e1nchez Anaya\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(13, 6, NULL, 'Boxito', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Grupo Boxito sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(14, 6, NULL, 'El candado', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Tlapaler\\u00eda El Candado sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(15, 6, NULL, 'Refrescos', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Bepensa Bebidas sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(16, 6, NULL, 'Fernando', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Fernando Salazar Avila\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(17, 6, NULL, 'Rod', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Rod Jepte  Garcia Chiapas\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(18, 6, NULL, 'Gas', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Sonigas sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(19, 6, NULL, 'Gerardo', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Gerardo Fabian Avila\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(20, 6, NULL, 'Equipohotel', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"Equipohotel sa de cv\",\"country\":\"MEX\",\"state\":\"\",\"address\":\"cancun\"}', 0),
(21, 8, NULL, 'Proveedor 1', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(22, 8, NULL, 'Proveedor 2', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0),
(23, 8, NULL, 'Proveedor 3', NULL, '{\"country\":\"\",\"number\":\"\"}', '{\"id\":\"\",\"name\":\"\",\"country\":\"\",\"state\":\"\",\"address\":\"\"}', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_countries`
--

CREATE TABLE `system_countries` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `lada` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `system_countries`
--

INSERT INTO `system_countries` (`id`, `name`, `code`, `lada`) VALUES
(1, '{\"es\":\"Afganistán\",\"en\":\"Afganistán\"}', 'AFG', '93'),
(2, '{\"es\":\"Albania\",\"en\":\"Albania\"}', 'ALB', '355'),
(3, '{\"es\":\"Alemania\",\"en\":\"Alemania\"}', 'DEU', '49'),
(4, '{\"es\":\"Argelia\",\"en\":\"Argelia\"}', 'DZA', '213'),
(5, '{\"es\":\"Andorra\",\"en\":\"Andorra\"}', 'AND', '376'),
(6, '{\"es\":\"Angola\",\"en\":\"Angola\"}', 'AGO', '244'),
(7, '{\"es\":\"Anguila\",\"en\":\"Anguila\"}', 'AIA', '264'),
(8, '{\"es\":\"Antártida\",\"en\":\"Antártida\"}', 'ATA', '672'),
(9, '{\"es\":\"Antigua y Barbuda\",\"en\":\"Antigua y Barbuda\"}', 'ATG', '268'),
(10, '{\"es\":\"Antillas Neerlandesas\",\"en\":\"Antillas Neerlandesas\"}', 'ANT', '599'),
(11, '{\"es\":\"Arabia Saudita\",\"en\":\"Arabia Saudita\"}', 'SAU', '966'),
(12, '{\"es\":\"Argentina\",\"en\":\"Argentina\"}', 'ARG', '54'),
(13, '{\"es\":\"Armenia\",\"en\":\"Armenia\"}', 'ARM', '374'),
(14, '{\"es\":\"Aruba\",\"en\":\"Aruba\"}', 'ABW', '297'),
(15, '{\"es\":\"Australia\",\"en\":\"Australia\"}', 'AUS', '61'),
(16, '{\"es\":\"Austria\",\"en\":\"Austria\"}', 'AUT', '43'),
(17, '{\"es\":\"Azerbayán\",\"en\":\"Azerbayán\"}', 'AZE', '994'),
(18, '{\"es\":\"Bahamas\",\"en\":\"Bahamas\"}', 'BHS', '242'),
(19, '{\"es\":\"Bahrein\",\"en\":\"Bahrein\"}', 'BHR', '973'),
(20, '{\"es\":\"Bangladesh\",\"en\":\"Bangladesh\"}', 'BGD', '880'),
(21, '{\"es\":\"Barbados\",\"en\":\"Barbados\"}', 'BRB', '246'),
(22, '{\"es\":\"Bélgica\",\"en\":\"Bélgica\"}', 'BEL', '32'),
(23, '{\"es\":\"Belice\",\"en\":\"Belice\"}', 'BLZ', '501'),
(24, '{\"es\":\"Ben\\u00edn\",\"en\":\"Ben\\u00edn\"}', 'BEN', '229'),
(25, '{\"es\":\"Bhut\\u00e1n\",\"en\":\"Bhut\\u00e1n\"}', 'BTN', '975'),
(26, '{\"es\":\"Bielorrusia\",\"en\":\"Bielorrusia\"}', 'BLR', '375'),
(27, '{\"es\":\"Birmania\",\"en\":\"Birmania\"}', 'MMR', '95'),
(28, '{\"es\":\"Bolivia\",\"en\":\"Bolivia\"}', 'BOL', '591'),
(29, '{\"es\":\"Bosnia y Herzegovina\",\"en\":\"Bosnia y Herzegovina\"}', 'BIH', '387'),
(30, '{\"es\":\"Botsuana\",\"en\":\"Botsuana\"}', 'BWA', '267'),
(31, '{\"es\":\"Brasil\",\"en\":\"Brasil\"}', 'BRA', '55'),
(32, '{\"es\":\"Brun\\u00e9i\",\"en\":\"Brun\\u00e9i\"}', 'BRN', '673'),
(33, '{\"es\":\"Bulgaria\",\"en\":\"Bulgaria\"}', 'BGR', '359'),
(34, '{\"es\":\"Burkina Faso\",\"en\":\"Burkina Faso\"}', 'BFA', '226'),
(35, '{\"es\":\"Burundi\",\"en\":\"Burundi\"}', 'BDI', '257'),
(36, '{\"es\":\"Cabo Verde\",\"en\":\"Cabo Verde\"}', 'CPV', '238'),
(37, '{\"es\":\"Camboya\",\"en\":\"Camboya\"}', 'KHM', '855'),
(38, '{\"es\":\"Camer\\u00fan\",\"en\":\"Camer\\u00fan\"}', 'CMR', '237'),
(39, '{\"es\":\"Canad\\u00e1\",\"en\":\"Canad\\u00e1\"}', 'CAN', '1'),
(40, '{\"es\":\"Chad\",\"en\":\"Chad\"}', 'TCD', '235'),
(41, '{\"es\":\"Chile\",\"en\":\"Chile\"}', 'CHL', '56'),
(42, '{\"es\":\"China\",\"en\":\"China\"}', 'CHN', '86'),
(43, '{\"es\":\"Chipre\",\"en\":\"Chipre\"}', 'CYP', '357'),
(44, '{\"es\":\"Ciudad del Vaticano\",\"en\":\"Ciudad del Vaticano\"}', 'VAT', '39'),
(45, '{\"es\":\"Colombia\",\"en\":\"Colombia\"}', 'COL', '57'),
(46, '{\"es\":\"Comoras\",\"en\":\"Comoras\"}', 'COM', '269'),
(47, '{\"es\":\"Congo\",\"en\":\"Congo\"}', 'COG', '242'),
(48, '{\"es\":\"Corea del Norte\",\"en\":\"Corea del Norte\"}', 'PRK', '850'),
(49, '{\"es\":\"Corea del Sur\",\"en\":\"Corea del Sur\"}', 'KOR', '82'),
(50, '{\"es\":\"Costa de Marfil\",\"en\":\"Costa de Marfil\"}', 'CIV', '225'),
(51, '{\"es\":\"Costa Rica\",\"en\":\"Costa Rica\"}', 'CRI', '506'),
(52, '{\"es\":\"Croacia\",\"en\":\"Croacia\"}', 'HRV', '385'),
(53, '{\"es\":\"Cuba\",\"en\":\"Cuba\"}', 'CUB', '53'),
(54, '{\"es\":\"Dinamarca\",\"en\":\"Dinamarca\"}', 'DNK', '45'),
(55, '{\"es\":\"Dominica\",\"en\":\"Dominica\"}', 'DMA', '767'),
(56, '{\"es\":\"Ecuador\",\"en\":\"Ecuador\"}', 'ECU', '593'),
(57, '{\"es\":\"Egipto\",\"en\":\"Egipto\"}', 'EGY', '20'),
(58, '{\"es\":\"El Salvador\",\"en\":\"El Salvador\"}', 'SLV', '503'),
(59, '{\"es\":\"Emiratos \\u00c1rabes Unidos\",\"en\":\"Emiratos \\u00c1rabes Unidos\"}', 'ARE', '971'),
(60, '{\"es\":\"Eritrea\",\"en\":\"Eritrea\"}', 'ERI', '291'),
(61, '{\"es\":\"Eslovaquia\",\"en\":\"Eslovaquia\"}', 'SVK', '421'),
(62, '{\"es\":\"Eslovenia\",\"en\":\"Eslovenia\"}', 'SVN', '386'),
(63, '{\"es\":\"Espa\\u00f1a\",\"en\":\"Espa\\u00f1a\"}', 'ESP', '34'),
(64, '{\"es\":\"Estados Unidos de Am\\u00e9rica\",\"en\":\"Estados Unidos de Am\\u00e9rica\"}', 'USA', '1'),
(65, '{\"es\":\"Estonia\",\"en\":\"Estonia\"}', 'EST', '372'),
(66, '{\"es\":\"Etiop\\u00eda\",\"en\":\"Etiop\\u00eda\"}', 'ETH', '251'),
(67, '{\"es\":\"Filipinas\",\"en\":\"Filipinas\"}', 'PHL', '63'),
(68, '{\"es\":\"Finlandia\",\"en\":\"Finlandia\"}', 'FIN', '358'),
(69, '{\"es\":\"Fiyi\",\"en\":\"Fiyi\"}', 'FJI', '679'),
(70, '{\"es\":\"Francia\",\"en\":\"Francia\"}', 'FRA', '33'),
(71, '{\"es\":\"Gab\\u00f3n\",\"en\":\"Gab\\u00f3n\"}', 'GAB', '241'),
(72, '{\"es\":\"Gambia\",\"en\":\"Gambia\"}', 'GMB', '220'),
(73, '{\"es\":\"Georgia\",\"en\":\"Georgia\"}', 'GEO', '995'),
(74, '{\"es\":\"Ghana\",\"en\":\"Ghana\"}', 'GHA', '233'),
(75, '{\"es\":\"Gibraltar\",\"en\":\"Gibraltar\"}', 'GIB', '350'),
(76, '{\"es\":\"Granada\",\"en\":\"Granada\"}', 'GRD', '473'),
(77, '{\"es\":\"Grecia\",\"en\":\"Grecia\"}', 'GRC', '30'),
(78, '{\"es\":\"Groenlandia\",\"en\":\"Groenlandia\"}', 'GRL', '299'),
(79, '{\"es\":\"Guadalupe\",\"en\":\"Guadalupe\"}', 'GLP', '0'),
(80, '{\"es\":\"Guam\",\"en\":\"Guam\"}', 'GUM', '671'),
(81, '{\"es\":\"Guatemala\",\"en\":\"Guatemala\"}', 'GTM', '502'),
(82, '{\"es\":\"Guayana Francesa\",\"en\":\"Guayana Francesa\"}', 'GUF', '0'),
(83, '{\"es\":\"Guernsey\",\"en\":\"Guernsey\"}', 'GGY', '0'),
(84, '{\"es\":\"Guinea\",\"en\":\"Guinea\"}', 'GIN', '224'),
(85, '{\"es\":\"Guinea Ecuatorial\",\"en\":\"Guinea Ecuatorial\"}', 'GNQ', '240'),
(86, '{\"es\":\"Guinea-Bissau\",\"en\":\"Guinea-Bissau\"}', 'GNB', '245'),
(87, '{\"es\":\"Guyana\",\"en\":\"Guyana\"}', 'GUY', '592'),
(88, '{\"es\":\"Hait\\u00ed\",\"en\":\"Hait\\u00ed\"}', 'HTI', '509'),
(89, '{\"es\":\"Honduras\",\"en\":\"Honduras\"}', 'HND', '504'),
(90, '{\"es\":\"Hong kong\",\"en\":\"Hong kong\"}', 'HKG', '852'),
(91, '{\"es\":\"Hungr\\u00eda\",\"en\":\"Hungr\\u00eda\"}', 'HUN', '36'),
(92, '{\"es\":\"India\",\"en\":\"India\"}', 'IND', '91'),
(93, '{\"es\":\"Indonesia\",\"en\":\"Indonesia\"}', 'IDN', '62'),
(94, '{\"es\":\"Irak\",\"en\":\"Irak\"}', 'IRQ', '964'),
(95, '{\"es\":\"Ir\\u00e1n\",\"en\":\"Ir\\u00e1n\"}', 'IRN', '98'),
(96, '{\"es\":\"Irlanda\",\"en\":\"Irlanda\"}', 'IRL', '353'),
(97, '{\"es\":\"Isla Bouvet\",\"en\":\"Isla Bouvet\"}', 'BVT', '0'),
(98, '{\"es\":\"Isla de Man\",\"en\":\"Isla de Man\"}', 'IMN', '44'),
(99, '{\"es\":\"Isla de Navidad\",\"en\":\"Isla de Navidad\"}', 'CXR', '61'),
(100, '{\"es\":\"Isla Norfolk\",\"en\":\"Isla Norfolk\"}', 'NFK', '0'),
(101, '{\"es\":\"Islandia\",\"en\":\"Islandia\"}', 'ISL', '354'),
(102, '{\"es\":\"Islas Bermudas\",\"en\":\"Islas Bermudas\"}', 'BMU', '441'),
(103, '{\"es\":\"Islas Caim\\u00e1n\",\"en\":\"Islas Caim\\u00e1n\"}', 'CYM', '345'),
(104, '{\"es\":\"Islas Cocos (Keeling)\",\"en\":\"Islas Cocos (Keeling)\"}', 'CCK', '61'),
(105, '{\"es\":\"Islas Cook\",\"en\":\"Islas Cook\"}', 'COK', '682'),
(106, '{\"es\":\"Islas de \\u00c5land\",\"en\":\"Islas de \\u00c5land\"}', 'ALA', '0'),
(107, '{\"es\":\"Islas Feroe\",\"en\":\"Islas Feroe\"}', 'FRO', '298'),
(108, '{\"es\":\"Islas Georgias del Sur y Sandwich del Sur\",\"en\":\"Islas Georgias del Sur y Sandwich del Sur\"}', 'SGS', '0'),
(109, '{\"es\":\"Islas Heard y McDonald\",\"en\":\"Islas Heard y McDonald\"}', 'HMD', '0'),
(110, '{\"es\":\"Islas Maldivas\",\"en\":\"Islas Maldivas\"}', 'MDV', '960'),
(111, '{\"es\":\"Islas Malvinas\",\"en\":\"Islas Malvinas\"}', 'FLK', '500'),
(112, '{\"es\":\"Islas Marianas del Norte\",\"en\":\"Islas Marianas del Norte\"}', 'MNP', '670'),
(113, '{\"es\":\"Islas Marshall\",\"en\":\"Islas Marshall\"}', 'MHL', '692'),
(114, '{\"es\":\"Islas Pitcairn\",\"en\":\"Islas Pitcairn\"}', 'PCN', '870'),
(115, '{\"es\":\"Islas Salom\\u00f3n\",\"en\":\"Islas Salom\\u00f3n\"}', 'SLB', '677'),
(116, '{\"es\":\"Islas Turcas y Caicos\",\"en\":\"Islas Turcas y Caicos\"}', 'TCA', '649'),
(117, '{\"es\":\"Islas Ultramarinas Menores de Estados Unidos\",\"en\":\"Islas Ultramarinas Menores de Estados Unidos\"}', 'UMI', '0'),
(118, '{\"es\":\"Islas V\\u00edrgenes Brit\\u00e1nicas\",\"en\":\"Islas V\\u00edrgenes Brit\\u00e1nicas\"}', 'VG', '284'),
(119, '{\"es\":\"Islas V\\u00edrgenes de los Estados Unidos\",\"en\":\"Islas V\\u00edrgenes de los Estados Unidos\"}', 'VIR', '340'),
(120, '{\"es\":\"Israel\",\"en\":\"Israel\"}', 'ISR', '972'),
(121, '{\"es\":\"Italia\",\"en\":\"Italia\"}', 'ITA', '39'),
(122, '{\"es\":\"Jamaica\",\"en\":\"Jamaica\"}', 'JAM', '876'),
(123, '{\"es\":\"Jap\\u00f3n\",\"en\":\"Jap\\u00f3n\"}', 'JPN', '81'),
(124, '{\"es\":\"Jersey\",\"en\":\"Jersey\"}', 'JEY', '0'),
(125, '{\"es\":\"Jordania\",\"en\":\"Jordania\"}', 'JOR', '962'),
(126, '{\"es\":\"Kazajist\\u00e1n\",\"en\":\"Kazajist\\u00e1n\"}', 'KAZ', '7'),
(127, '{\"es\":\"Kenia\",\"en\":\"Kenia\"}', 'KEN', '254'),
(128, '{\"es\":\"Kirgizst\\u00e1n\",\"en\":\"Kirgizst\\u00e1n\"}', 'KGZ', '996'),
(129, '{\"es\":\"Kiribati\",\"en\":\"Kiribati\"}', 'KIR', '686'),
(130, '{\"es\":\"Kuwait\",\"en\":\"Kuwait\"}', 'KWT', '965'),
(131, '{\"es\":\"Laos\",\"en\":\"Laos\"}', 'LAO', '856'),
(132, '{\"es\":\"Lesoto\",\"en\":\"Lesoto\"}', 'LSO', '266'),
(133, '{\"es\":\"Letonia\",\"en\":\"Letonia\"}', 'LVA', '371'),
(134, '{\"es\":\"L\\u00edbano\",\"en\":\"L\\u00edbano\"}', 'LBN', '961'),
(135, '{\"es\":\"Liberia\",\"en\":\"Liberia\"}', 'LBR', '231'),
(136, '{\"es\":\"Libia\",\"en\":\"Libia\"}', 'LBY', '218'),
(137, '{\"es\":\"Liechtenstein\",\"en\":\"Liechtenstein\"}', 'LIE', '423'),
(138, '{\"es\":\"Lituania\",\"en\":\"Lituania\"}', 'LTU', '370'),
(139, '{\"es\":\"Luxemburgo\",\"en\":\"Luxemburgo\"}', 'LUX', '352'),
(140, '{\"es\":\"Macao\",\"en\":\"Macao\"}', 'MAC', '853'),
(141, '{\"es\":\"Maced\\u00f4nia\",\"en\":\"Maced\\u00f4nia\"}', 'MKD', '389'),
(142, '{\"es\":\"Madagascar\",\"en\":\"Madagascar\"}', 'MDG', '261'),
(143, '{\"es\":\"Malasia\",\"en\":\"Malasia\"}', 'MYS', '60'),
(144, '{\"es\":\"Malawi\",\"en\":\"Malawi\"}', 'MWI', '265'),
(145, '{\"es\":\"Mali\",\"en\":\"Mali\"}', 'MLI', '223'),
(146, '{\"es\":\"Malta\",\"en\":\"Malta\"}', 'MLT', '356'),
(147, '{\"es\":\"Marruecos\",\"en\":\"Marruecos\"}', 'MAR', '212'),
(148, '{\"es\":\"Martinica\",\"en\":\"Martinica\"}', 'MTQ', '0'),
(149, '{\"es\":\"Mauricio\",\"en\":\"Mauricio\"}', 'MUS', '230'),
(150, '{\"es\":\"Mauritania\",\"en\":\"Mauritania\"}', 'MRT', '222'),
(151, '{\"es\":\"Mayotte\",\"en\":\"Mayotte\"}', 'MYT', '262'),
(152, '{\"es\":\"M\\u00e9xico\",\"en\":\"M\\u00e9xico\"}', 'MEX', '52'),
(153, '{\"es\":\"Micronesia\",\"en\":\"Micronesia\"}', 'FSM', '691'),
(154, '{\"es\":\"Moldavia\",\"en\":\"Moldavia\"}', 'MDA', '373'),
(155, '{\"es\":\"M\\u00f3naco\",\"en\":\"M\\u00f3naco\"}', 'MCO', '377'),
(156, '{\"es\":\"Mongolia\",\"en\":\"Mongolia\"}', 'MNG', '976'),
(157, '{\"es\":\"Montenegro\",\"en\":\"Montenegro\"}', 'MNE', '382'),
(158, '{\"es\":\"Montserrat\",\"en\":\"Montserrat\"}', 'MSR', '664'),
(159, '{\"es\":\"Mozambique\",\"en\":\"Mozambique\"}', 'MOZ', '258'),
(160, '{\"es\":\"Namibia\",\"en\":\"Namibia\"}', 'NAM', '264'),
(161, '{\"es\":\"Nauru\",\"en\":\"Nauru\"}', 'NRU', '674'),
(162, '{\"es\":\"Nepal\",\"en\":\"Nepal\"}', 'NPL', '977'),
(163, '{\"es\":\"Nicaragua\",\"en\":\"Nicaragua\"}', 'NIC', '505'),
(164, '{\"es\":\"Niger\",\"en\":\"Niger\"}', 'NER', '227'),
(165, '{\"es\":\"Nigeria\",\"en\":\"Nigeria\"}', 'NGA', '234'),
(166, '{\"es\":\"Niue\",\"en\":\"Niue\"}', 'NIU', '683'),
(168, '{\"es\":\"Noruega\",\"en\":\"Noruega\"}', 'NOR', '47'),
(169, '{\"es\":\"Nueva Caledonia\",\"en\":\"Nueva Caledonia\"}', 'NCL', '687'),
(170, '{\"es\":\"Nueva Zelanda\",\"en\":\"Nueva Zelanda\"}', 'NZL', '64'),
(171, '{\"es\":\"Om\\u00e1n\",\"en\":\"Om\\u00e1n\"}', 'OMN', '968'),
(172, '{\"es\":\"Pa\\u00edses Bajos\",\"en\":\"Pa\\u00edses Bajos\"}', 'NLD', '31'),
(173, '{\"es\":\"Pakist\\u00e1n\",\"en\":\"Pakist\\u00e1n\"}', 'PAK', '92'),
(174, '{\"es\":\"Palau\",\"en\":\"Palau\"}', 'PLW', '680'),
(175, '{\"es\":\"Palestina\",\"en\":\"Palestina\"}', 'PSE', '0'),
(176, '{\"es\":\"Panam\\u00e1\",\"en\":\"Panam\\u00e1\"}', 'PAN', '507'),
(177, '{\"es\":\"Pap\\u00faa Nueva Guinea\",\"en\":\"Pap\\u00faa Nueva Guinea\"}', 'PNG', '675'),
(178, '{\"es\":\"Paraguay\",\"en\":\"Paraguay\"}', 'PRY', '595'),
(179, '{\"es\":\"Per\\u00fa\",\"en\":\"Per\\u00fa\"}', 'PER', '51'),
(180, '{\"es\":\"Polinesia Francesa\",\"en\":\"Polinesia Francesa\"}', 'PYF', '689'),
(181, '{\"es\":\"Polonia\",\"en\":\"Polonia\"}', 'POL', '48'),
(182, '{\"es\":\"Portugal\",\"en\":\"Portugal\"}', 'PRT', '351'),
(183, '{\"es\":\"Puerto Rico\",\"en\":\"Puerto Rico\"}', 'PRI', '787'),
(184, '{\"es\":\"Qatar\",\"en\":\"Qatar\"}', 'QAT', '974'),
(185, '{\"es\":\"Reino Unido\",\"en\":\"Reino Unido\"}', 'GBR', '44'),
(186, '{\"es\":\"Rep\\u00fablica Centroafricana\",\"en\":\"Rep\\u00fablica Centroafricana\"}', 'CAF', '236'),
(187, '{\"es\":\"Rep\\u00fablica Checa\",\"en\":\"Rep\\u00fablica Checa\"}', 'CZE', '420'),
(188, '{\"es\":\"Rep\\u00fablica Dominicana\",\"en\":\"Rep\\u00fablica Dominicana\"}', 'DOM', '809'),
(189, '{\"es\":\"Reuni\\u00f3n\",\"en\":\"Reuni\\u00f3n\"}', 'REU', '0'),
(190, '{\"es\":\"Ruanda\",\"en\":\"Ruanda\"}', 'RWA', '250'),
(191, '{\"es\":\"Ruman\\u00eda\",\"en\":\"Ruman\\u00eda\"}', 'ROU', '40'),
(192, '{\"es\":\"Rusia\",\"en\":\"Rusia\"}', 'RUS', '7'),
(193, '{\"es\":\"Sahara Occidental\",\"en\":\"Sahara Occidental\"}', 'ESH', '0'),
(194, '{\"es\":\"Samoa\",\"en\":\"Samoa\"}', 'WSM', '685'),
(195, '{\"es\":\"Samoa Americana\",\"en\":\"Samoa Americana\"}', 'ASM', '684'),
(196, '{\"es\":\"San Bartolom\\u00e9\",\"en\":\"San Bartolom\\u00e9\"}', 'BLM', '590'),
(197, '{\"es\":\"San Crist\\u00f3bal y Nieves\",\"en\":\"San Crist\\u00f3bal y Nieves\"}', 'KNA', '869'),
(198, '{\"es\":\"San Marino\",\"en\":\"San Marino\"}', 'SMR', '378'),
(199, '{\"es\":\"San Mart\\u00edn (Francia)\",\"en\":\"San Mart\\u00edn (Francia)\"}', 'MAF', '599'),
(200, '{\"es\":\"San Pedro y Miquel\\u00f3n\",\"en\":\"San Pedro y Miquel\\u00f3n\"}', 'SPM', '508'),
(201, '{\"es\":\"San Vicente y las Granadinas\",\"en\":\"San Vicente y las Granadinas\"}', 'VCT', '784'),
(202, '{\"es\":\"Santa Elena\",\"en\":\"Santa Elena\"}', 'SHN', '290'),
(203, '{\"es\":\"Santa Luc\\u00eda\",\"en\":\"Santa Luc\\u00eda\"}', 'LCA', '758'),
(204, '{\"es\":\"Santo Tom\\u00e9 y Pr\\u00edncipe\",\"en\":\"Santo Tom\\u00e9 y Pr\\u00edncipe\"}', 'STP', '239'),
(205, '{\"es\":\"Senegal\",\"en\":\"Senegal\"}', 'SEN', '221'),
(206, '{\"es\":\"Serbia\",\"en\":\"Serbia\"}', 'SRB', '381'),
(207, '{\"es\":\"Seychelles\",\"en\":\"Seychelles\"}', 'SYC', '248'),
(208, '{\"es\":\"Sierra Leona\",\"en\":\"Sierra Leona\"}', 'SLE', '232'),
(209, '{\"es\":\"Singapur\",\"en\":\"Singapur\"}', 'SGP', '65'),
(210, '{\"es\":\"Siria\",\"en\":\"Siria\"}', 'SYR', '963'),
(211, '{\"es\":\"Somalia\",\"en\":\"Somalia\"}', 'SOM', '252'),
(212, '{\"es\":\"Sri lanka\",\"en\":\"Sri lanka\"}', 'LKA', '94'),
(213, '{\"es\":\"Sud\\u00e1frica\",\"en\":\"Sud\\u00e1frica\"}', 'ZAF', '27'),
(214, '{\"es\":\"Sud\\u00e1n\",\"en\":\"Sud\\u00e1n\"}', 'SDN', '249'),
(215, '{\"es\":\"Suecia\",\"en\":\"Suecia\"}', 'SWE', '46'),
(216, '{\"es\":\"Suiza\",\"en\":\"Suiza\"}', 'CHE', '41'),
(217, '{\"es\":\"Surin\\u00e1m\",\"en\":\"Surin\\u00e1m\"}', 'SUR', '597'),
(218, '{\"es\":\"Svalbard y Jan Mayen\",\"en\":\"Svalbard y Jan Mayen\"}', 'SJM', '0'),
(219, '{\"es\":\"Swazilandia\",\"en\":\"Swazilandia\"}', 'SWZ', '268'),
(220, '{\"es\":\"Tadjikist\\u00e1n\",\"en\":\"Tadjikist\\u00e1n\"}', 'TJK', '992'),
(221, '{\"es\":\"Tailandia\",\"en\":\"Tailandia\"}', 'THA', '66'),
(222, '{\"es\":\"Taiw\\u00e1n\",\"en\":\"Taiw\\u00e1n\"}', 'TWN', '886'),
(223, '{\"es\":\"Tanzania\",\"en\":\"Tanzania\"}', 'TZA', '255'),
(224, '{\"es\":\"Territorio Brit\\u00e1nico del Oc\\u00e9ano \\u00cdndico\",\"en\":\"Territorio Brit\\u00e1nico del Oc\\u00e9ano \\u00cdndico\"}', 'IOT', '0'),
(225, '{\"es\":\"Territorios Australes y Ant\\u00e1rticas Franceses\",\"en\":\"Territorios Australes y Ant\\u00e1rticas Franceses\"}', 'ATF', '0'),
(226, '{\"es\":\"Timor Oriental\",\"en\":\"Timor Oriental\"}', 'TLS', '670'),
(227, '{\"es\":\"Togo\",\"en\":\"Togo\"}', 'TGO', '228'),
(228, '{\"es\":\"Tokelau\",\"en\":\"Tokelau\"}', 'TKL', '690'),
(229, '{\"es\":\"Tonga\",\"en\":\"Tonga\"}', 'TON', '676'),
(230, '{\"es\":\"Trinidad y Tobago\",\"en\":\"Trinidad y Tobago\"}', 'TTO', '868'),
(231, '{\"es\":\"Tunez\",\"en\":\"Tunez\"}', 'TUN', '216'),
(232, '{\"es\":\"Turkmenist\\u00e1n\",\"en\":\"Turkmenist\\u00e1n\"}', 'TKM', '993'),
(233, '{\"es\":\"Turqu\\u00eda\",\"en\":\"Turqu\\u00eda\"}', 'TUR', '90'),
(234, '{\"es\":\"Tuvalu\",\"en\":\"Tuvalu\"}', 'TUV', '688'),
(235, '{\"es\":\"Ucrania\",\"en\":\"Ucrania\"}', 'UKR', '380'),
(236, '{\"es\":\"Uganda\",\"en\":\"Uganda\"}', 'UGA', '256'),
(237, '{\"es\":\"Uruguay\",\"en\":\"Uruguay\"}', 'URY', '598'),
(238, '{\"es\":\"Uzbekist\\u00e1n\",\"en\":\"Uzbekist\\u00e1n\"}', 'UZB', '998'),
(239, '{\"es\":\"Vanuatu\",\"en\":\"Vanuatu\"}', 'VUT', '678'),
(240, '{\"es\":\"Venezuela\",\"en\":\"Venezuela\"}', 'VEN', '58'),
(241, '{\"es\":\"Vietnam\",\"en\":\"Vietnam\"}', 'VNM', '84'),
(242, '{\"es\":\"Wallis y Futuna\",\"en\":\"Wallis y Futuna\"}', 'WLF', '681'),
(243, '{\"es\":\"Yemen\",\"en\":\"Yemen\"}', 'YEM', '967'),
(244, '{\"es\":\"Yibuti\",\"en\":\"Yibuti\"}', 'DJI', '253'),
(245, '{\"es\":\"Zambia\",\"en\":\"Zambia\"}', 'ZMB', '260'),
(246, '{\"es\":\"Zimbabue\",\"en\":\"Zimbabue\"}', 'ZWE', '263');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_formulas`
--

CREATE TABLE `system_formulas` (
  `id` bigint(20) NOT NULL,
  `code` char(8) NOT NULL,
  `name` text NOT NULL,
  `equation` text DEFAULT NULL,
  `order` text NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `system_formulas`
--

INSERT INTO `system_formulas` (`id`, `code`, `name`, `equation`, `order`, `blocked`) VALUES
(1, 'SHG78K9H', '{\"es\":\"Descuento de cantidad\",\"en\":\"Quantity discount\"}', NULL, '1', 0),
(2, 'LOPU65A3', '{\"es\":\"Descuento de proporción\",\"en\":\"Ratio discount\"}', '([Número de vasos]*0.03)/([Contenido neto] / 1000)', '2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_payments_ways`
--

CREATE TABLE `system_payments_ways` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `order` text NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `system_payments_ways`
--

INSERT INTO `system_payments_ways` (`id`, `name`, `code`, `order`, `blocked`) VALUES
(1, '{\"es\":\"Efectivo\",\"en\":\"Cash\"}', 'cash', '1', 0),
(2, '{\"es\":\"Cheque nominativo\",\"en\":\"Nominal check\"}', 'nominal_check', '2', 0),
(3, '{\"es\":\"Transferencia electrónica de fondos (Incluye SPEI)\",\"en\":\"Electronic Fund Transfer (Including SPEI)\"}', 'electronic_fund_transfer', '3', 0),
(4, '{\"es\":\"Tarjeta de crédito\",\"en\":\"Credit card\"}', 'credit_card', '4', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_states`
--

CREATE TABLE `system_states` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `country` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `system_states`
--

INSERT INTO `system_states` (`id`, `name`, `country`) VALUES
(1, '{\"es\":\"Aguascalientes\",\"en\":\"Aguascalientes\"}', 'MEX'),
(2, '{\"es\":\"Baja California\",\"en\":\"Baja California\"}', 'MEX'),
(3, '{\"es\":\"Baja California Sur\",\"en\":\"Baja California Sur\"}', 'MEX'),
(4, '{\"es\":\"Campeche\",\"en\":\"Campeche\"}', 'MEX'),
(5, '{\"es\":\"Chiapas\",\"en\":\"Chiapas\"}', 'MEX'),
(6, '{\"es\":\"Chihuahua\",\"en\":\"Chihuahua\"}', 'MEX'),
(7, '{\"es\":\"Coahuila\",\"en\":\"Coahuila\"}', 'MEX'),
(8, '{\"es\":\"Colima\",\"en\":\"Colima\"}', 'MEX'),
(9, '{\"es\":\"Distrito Federal\",\"en\":\"Distrito Federal\"}', 'MEX'),
(10, '{\"es\":\"Durango\",\"en\":\"Durango\"}', 'MEX'),
(11, '{\"es\":\"Estado de México\",\"en\":\"Estado de México\"}', 'MEX'),
(12, '{\"es\":\"Guanajuato\",\"en\":\"Guanajuato\"}', 'MEX'),
(13, '{\"es\":\"Guerrero\",\"en\":\"Guerrero\"}', 'MEX'),
(14, '{\"es\":\"Hidalgo\",\"en\":\"Hidalgo\"}', 'MEX'),
(15, '{\"es\":\"Jalisco\",\"en\":\"Jalisco\"}', 'MEX'),
(16, '{\"es\":\"Michoacán\",\"en\":\"Michoacán\"}', 'MEX'),
(17, '{\"es\":\"Morelos\",\"en\":\"Morelos\"}', 'MEX'),
(18, '{\"es\":\"Nayarit\",\"en\":\"Nayarit\"}', 'MEX'),
(19, '{\"es\":\"Nuevo León\",\"en\":\"Nuevo León\"}', 'MEX'),
(20, '{\"es\":\"Oaxaca\",\"en\":\"Oaxaca\"}', 'MEX'),
(21, '{\"es\":\"Puebla\",\"en\":\"Puebla\"}', 'MEX'),
(22, '{\"es\":\"Querétaro\",\"en\":\"Querétaro\"}', 'MEX'),
(23, '{\"es\":\"Quintana Roo\",\"en\":\"Quintana Roo\"}', 'MEX'),
(24, '{\"es\":\"San Luis Potosí\",\"en\":\"San Luis Potosí\"}', 'MEX'),
(25, '{\"es\":\"Sinaloa\",\"en\":\"Sinaloa\"}', 'MEX'),
(26, '{\"es\":\"Sonora\",\"en\":\"Sonora\"}', 'MEX'),
(27, '{\"es\":\"Tabasco\",\"en\":\"Tabasco\"}', 'MEX'),
(28, '{\"es\":\"Tamaulipas\",\"en\":\"Tamaulipas\"}', 'MEX'),
(29, '{\"es\":\"Tlaxcala\",\"en\":\"Tlaxcala\"}', 'MEX'),
(30, '{\"es\":\"Veracruz\",\"en\":\"Veracruz\"}', 'MEX'),
(31, '{\"es\":\"Yucatán\",\"en\":\"Yucatán\"}', 'MEX'),
(32, '{\"es\":\"Zacatecas\",\"en\":\"Zacatecas\"}', 'MEX');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `avatar` text DEFAULT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `language` text NOT NULL,
  `accounts` text NOT NULL,
  `settings` text NOT NULL,
  `signup_date` date NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `avatar`, `firstname`, `lastname`, `email`, `password`, `language`, `accounts`, `settings`, `signup_date`, `blocked`) VALUES
(1, NULL, 'Gersón A.', 'Gómez Macías', 'gergomez18@gmail.com', '16d18801eb78a089e7b912cdafdb13dc247467ee:YfTaah8vfhNwrRlucgnfrsr1z2cjIoo36W6FROJ4TVLvm4U9KY0EfF1gPLSsi0Fv', 'es', '[{\"id\":8,\"permissions\":\"all\",\"branches\":\"all\"},{\"id\":6,\"permissions\":\"all\",\"branches\":\"all\"},{\"id\":4,\"permissions\":\"all\",\"branches\":\"all\"},{\"id\":5,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2016-01-01', 0),
(2, NULL, 'David M.', 'Gómez Macías', 'davidgomezmacias@gmail.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[{\"id\":8,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2021-01-01', 0),
(3, NULL, 'Julián', 'Romero', 'julianrr5@hotmail.com', '9479f168a95fa5ca7ad0dc8dcd196c78322fa19a:5M5TvLQDPznBc5asNLchpRbbl3qUxnT3Oy08XSXtwc3W9cW1NnVmlCEKRveFQG2i', 'es', '[{\"id\":4,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2016-01-01', 0),
(4, NULL, 'Paloma', 'Ceballos', 'ceballospaloma@yahoo.com.mx', 'ef70a65d5a14ab0ecbcaf165d893222b42ea339b:DXgTr1dR2xcuXNYDSFSNbxgH5C1Ra8ph8MLPr31uTDiJcx0K5InH1RhDmIwPLhc6', 'es', '[{\"id\":5,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2017-12-21', 0),
(5, NULL, 'Germán', 'Solana', 'german@botaneronacional.com', '41a41824ef698c60bb5c0fd0cc1552cbd9a7ecfb:Z6ER6idKI4T6qpG64tfHwsNDtiQTpzVTYLjxAqozOynlJtnmxmSJ2smPugNjRAgl', 'es', '[{\"id\":6,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2020-01-27', 0),
(6, NULL, 'Alfredo', 'Vidal', 'alfredo@botaneronacional.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[]', '[]', '2020-01-27', 0),
(8, NULL, 'Vladimir', 'Vladimir', 'vladimir@botaneronacional.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[{\"id\":6,\"permissions\":[\"30\",\"2\"],\"branches\":[\"3\"]}]', '[]', '2020-01-27', 0),
(9, NULL, 'Edson', 'Edson', 'edson@botaneronacional.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[{\"id\":6,\"permissions\":[\"30\",\"2\"],\"branches\":[\"3\"]}]', '[]', '2020-01-27', 0),
(10, NULL, 'Daniel', 'Itzá', 'daniel@botaneronacional.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[{\"id\":6,\"permissions\":\"all\",\"branches\":\"all\"}]', '[]', '2020-01-27', 0),
(11, NULL, 'Eduardo', 'Cahuich', 'ecahuich@botaneronacional.com', '62b3beac6d75db79310433c47fdceb10c6f6c5ec:XHJrPdbTbM6g8Cxnk8DQwjJgA5mnEIHTqvxu9vD2MgXuGLMw6HDDvrvUpQ1cfgGM', 'es', '[{\"id\":6,\"permissions\":[\"30\",\"2\"],\"branches\":[\"1\"]}]', '[]', '2020-01-27', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_permissions`
--

CREATE TABLE `users_permissions` (
  `id` bigint(20) NOT NULL,
  `code` text NOT NULL,
  `group` text NOT NULL,
  `priority` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_permissions`
--

INSERT INTO `users_permissions` (`id`, `code`, `group`, `priority`) VALUES
(1, 'view_all', 'view', '1.2.1'),
(2, 'view_assigned_branches', 'view', '1.2.2'),
(3, 'view_own', 'view', '1.2.3'),
(4, 'create_products', 'products', '3.1.1'),
(5, 'update_products', 'products', '3.1.2'),
(6, 'block_products', 'products', '3.1.3'),
(7, 'unblock_products', 'products', '3.1.4'),
(8, 'delete_products', 'products', '3.1.5'),
(9, 'create_products_categories', 'products', '3.2.1'),
(10, 'update_products_categories', 'products', '3.2.2'),
(11, 'block_products_categories', 'products', '3.2.3'),
(12, 'unblock_products_categories', 'products', '3.2.4'),
(13, 'delete_products_categories', 'products', '3.2.5'),
(15, 'create_branches', 'branches', '8.1.1'),
(16, 'update_branches', 'branches', '8.1.2'),
(17, 'block_branches', 'branches', '8.1.3'),
(18, 'unblock_branches', 'branches', '8.1.4'),
(19, 'delete_branches', 'branches', '8.1.5'),
(20, 'create_providers', 'providers', '9.1.1'),
(21, 'update_providers', 'providers', '9.1.2'),
(22, 'block_providers', 'providers', '9.1.3'),
(23, 'unblock_providers', 'providers', '9.1.4'),
(24, 'delete_providers', 'providers', '9.1.5'),
(25, 'create_products_unities', 'products', '3.3.1'),
(26, 'update_products_unities', 'products', '3.3.2'),
(27, 'block_products_unities', 'products', '3.3.3'),
(28, 'unblock_products_unities', 'products', '3.3.4'),
(29, 'delete_products_unities', 'products', '3.3.5'),
(30, 'create_inventories_inputs', 'inventories', '2.1.1'),
(31, 'create_inventories_outputs', 'inventories', '2.1.4'),
(32, 'create_inventories_transfers', 'inventories', '2.1.7'),
(33, 'create_inventories_types', 'inventories', '2.4.1'),
(34, 'update_inventories_types', 'inventories', '2.4.2'),
(35, 'block_inventories_types', 'inventories', '2.4.3'),
(36, 'unblock_inventories_types', 'inventories', '2.4.4'),
(37, 'delete_inventories_types', 'inventories', '2.4.5'),
(38, 'create_inventories_locations', 'inventories', '2.5.1'),
(39, 'update_inventories_locations', 'inventories', '2.5.2'),
(40, 'block_inventories_locations', 'inventories', '2.5.3'),
(41, 'unblock_inventories_locations', 'inventories', '2.5.4'),
(42, 'delete_inventories_locations', 'inventories', '2.5.5'),
(43, 'create_inventories_categories', 'inventories', '2.6.1'),
(44, 'update_inventories_categories', 'inventories', '2.6.2'),
(45, 'block_inventories_categories', 'inventories', '2.6.3'),
(46, 'unblock_inventories_categories', 'inventories', '2.6.4'),
(47, 'delete_inventories_categories', 'inventories', '2.6.5'),
(48, 'update_inventories_inputs', 'inventories', '2.1.2'),
(49, 'delete_inventories_inputs', 'inventories', '2.1.3'),
(50, 'update_inventories_outputs', 'inventories', '2.1.5'),
(51, 'delete_inventories_outputs', 'inventories', '2.1.6'),
(52, 'create_inventories_periods', 'inventories', '2.2.1'),
(54, 'delete_inventories_periods', 'inventories', '2.2.3'),
(55, 'create_inventories_audits', 'inventories', '2.3.1'),
(57, 'delete_inventories_audits', 'inventories', '2.3.3'),
(59, 'create_products_contents', 'products', '3.4.1'),
(60, 'update_products_contents', 'products', '3.4.2'),
(61, 'block_products_contents', 'products', '3.4.3'),
(62, 'unblock_products_contents', 'products', '3.4.4'),
(63, 'delete_products_contents', 'products', '3.4.5'),
(64, 'update_inventories_audits', 'inventories', '2.3.2'),
(65, 'update_inventories_periods', 'inventories', '2.2.2'),
(66, 'payment_account', 'account', '1.1.2'),
(67, 'update_account', 'account', '1.1.1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- Indices de la tabla `accounts_permissions`
--
ALTER TABLE `accounts_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `created_user` (`created_user`);

--
-- Indices de la tabla `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `branch` (`branch`),
  ADD KEY `type` (`type`),
  ADD KEY `product` (`product`),
  ADD KEY `bill` (`bill`),
  ADD KEY `provider` (`provider`),
  ADD KEY `location` (`location`),
  ADD KEY `created_user` (`created_user`);

--
-- Indices de la tabla `inventories_audits`
--
ALTER TABLE `inventories_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `branch` (`branch`),
  ADD KEY `created_user` (`created_user`);

--
-- Indices de la tabla `inventories_categories`
--
ALTER TABLE `inventories_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `inventories_locations`
--
ALTER TABLE `inventories_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `inventories_periods`
--
ALTER TABLE `inventories_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `branch` (`branch`),
  ADD KEY `user` (`created_user`);

--
-- Indices de la tabla `inventories_transfers`
--
ALTER TABLE `inventories_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `output_branch` (`output_branch`),
  ADD KEY `input_branch` (`input_branch`),
  ADD KEY `created_user` (`created_user`),
  ADD KEY `success_user` (`success_user`),
  ADD KEY `rejected_user` (`rejected_user`),
  ADD KEY `cancel_user` (`cancel_user`);

--
-- Indices de la tabla `inventories_types`
--
ALTER TABLE `inventories_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `unity` (`unity`);

--
-- Indices de la tabla `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `products_contents`
--
ALTER TABLE `products_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `unity` (`unity`);

--
-- Indices de la tabla `products_unities`
--
ALTER TABLE `products_unities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Indices de la tabla `system_countries`
--
ALTER TABLE `system_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `system_formulas`
--
ALTER TABLE `system_formulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `system_payments_ways`
--
ALTER TABLE `system_payments_ways`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `system_states`
--
ALTER TABLE `system_states`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `accounts_permissions`
--
ALTER TABLE `accounts_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1373;

--
-- AUTO_INCREMENT de la tabla `inventories_audits`
--
ALTER TABLE `inventories_audits`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventories_categories`
--
ALTER TABLE `inventories_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `inventories_locations`
--
ALTER TABLE `inventories_locations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventories_periods`
--
ALTER TABLE `inventories_periods`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventories_transfers`
--
ALTER TABLE `inventories_transfers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inventories_types`
--
ALTER TABLE `inventories_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=907;

--
-- AUTO_INCREMENT de la tabla `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `products_contents`
--
ALTER TABLE `products_contents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `products_unities`
--
ALTER TABLE `products_unities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `providers`
--
ALTER TABLE `providers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `system_countries`
--
ALTER TABLE `system_countries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT de la tabla `system_formulas`
--
ALTER TABLE `system_formulas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `system_payments_ways`
--
ALTER TABLE `system_payments_ways`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `system_states`
--
ALTER TABLE `system_states`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `users_permissions`
--
ALTER TABLE `users_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `inventories_ibfk_2` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `inventories_ibfk_3` FOREIGN KEY (`type`) REFERENCES `inventories_types` (`id`),
  ADD CONSTRAINT `inventories_ibfk_4` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `inventories_ibfk_5` FOREIGN KEY (`bill`) REFERENCES `bills` (`id`),
  ADD CONSTRAINT `inventories_ibfk_6` FOREIGN KEY (`provider`) REFERENCES `providers` (`id`),
  ADD CONSTRAINT `inventories_ibfk_7` FOREIGN KEY (`location`) REFERENCES `inventories_locations` (`id`),
  ADD CONSTRAINT `inventories_ibfk_9` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `inventories_audits`
--
ALTER TABLE `inventories_audits`
  ADD CONSTRAINT `inventories_audits_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `inventories_audits_ibfk_2` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `inventories_audits_ibfk_3` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `inventories_categories`
--
ALTER TABLE `inventories_categories`
  ADD CONSTRAINT `inventories_categories_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `inventories_locations`
--
ALTER TABLE `inventories_locations`
  ADD CONSTRAINT `inventories_locations_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `inventories_periods`
--
ALTER TABLE `inventories_periods`
  ADD CONSTRAINT `inventories_periods_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `inventories_periods_ibfk_2` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `inventories_periods_ibfk_3` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `inventories_transfers`
--
ALTER TABLE `inventories_transfers`
  ADD CONSTRAINT `inventories_transfers_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_2` FOREIGN KEY (`output_branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_3` FOREIGN KEY (`input_branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_4` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_5` FOREIGN KEY (`success_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_6` FOREIGN KEY (`rejected_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventories_transfers_ibfk_7` FOREIGN KEY (`cancel_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `inventories_types`
--
ALTER TABLE `inventories_types`
  ADD CONSTRAINT `inventories_types_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`unity`) REFERENCES `products_unities` (`id`);

--
-- Filtros para la tabla `products_categories`
--
ALTER TABLE `products_categories`
  ADD CONSTRAINT `products_categories_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `products_contents`
--
ALTER TABLE `products_contents`
  ADD CONSTRAINT `products_contents_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `products_contents_ibfk_2` FOREIGN KEY (`unity`) REFERENCES `products_unities` (`id`);

--
-- Filtros para la tabla `products_unities`
--
ALTER TABLE `products_unities`
  ADD CONSTRAINT `products_unities_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);

--
-- Filtros para la tabla `providers`
--
ALTER TABLE `providers`
  ADD CONSTRAINT `providers_ibfk_1` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
