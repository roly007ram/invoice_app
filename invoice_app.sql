-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-10-2025 a las 02:33:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `invoice_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `localidad` varchar(255) DEFAULT NULL,
  `tipo_iva` varchar(255) DEFAULT NULL,
  `cuit` varchar(20) DEFAULT NULL,
  `vendedor` varchar(50) NOT NULL,
  `condicion_venta_default` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `domicilio`, `localidad`, `tipo_iva`, `cuit`, `vendedor`, `condicion_venta_default`) VALUES
(2, 'SENGER EVALDO CUIT', 'V. DE LAS AMERICA 283', 'Aristobulo Del Valle', 'responsable inscripto', ' 20-24033611-2', '', 'cuenta corrientes'),
(5, 'VERON LEONARDO ISMAEL', 'NARCISO LAPRIDA MZ ,17 LOTE 1 ', 'COLONIA LIE', 'responsable inscripto ', '20-41153874-6', '', 'cuenta corrientes'),
(6, 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', 'responsable inscripto ', '30-71186763-1', '', 'cuenta corrientes'),
(7, 'RIOS ARIEL ALFREDO', '6 28 Piso:0 Dpto:0 S:0 T:0 M:0 - BARRIO : B G SAN MARTIN', 'ITUZAINGO - Corrientes', 'Responsable inscripto', '20379503625', '', 'Contado'),
(8, 'Andrea Magali Alcalde', '', '', 'responsable incripto', '27402611443', '', ''),
(9, 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', 'Responsable Inscripto', '20285527660', '', ''),
(10, ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', 'Responsable Inscropto', '33718922939', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles`
--

CREATE TABLE `detalles` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `rubro_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `rubro` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalles`
--

INSERT INTO `detalles` (`id`, `descripcion`, `rubro_id`, `precio`, `rubro`, `created_at`, `updated_at`) VALUES
(11, 'Motorola G75 8ram 256BG.', 3, 207272.00, 3, '2025-08-21 01:10:26', '2025-10-22 06:52:20'),
(12, 'Motorola G35 8ram 256GB.', 3, 147272.00, 3, '2025-08-21 01:11:27', '2025-10-22 06:52:20'),
(13, 'servicio de flete de Hoja verde de yerba Mate', NULL, 1100000.00, 7, '2025-08-22 00:51:38', '2025-10-15 00:44:45'),
(14, 'Combst. Liquido son por Cuenta y Orden de YPF SA Gas Oil', 2, 1590.12, 4, '2025-09-23 22:47:59', '2025-10-22 06:52:20'),
(15, 'Chapa 1 x 2 Metros x 0.7 Mm', 1, 159854.34, 1, '2025-10-01 11:58:36', '2025-10-22 06:52:20'),
(16, 'Cemento Loma Negra 25kg Gris', NULL, 5200.00, 2, '2025-10-01 13:21:36', '2025-10-15 00:44:45'),
(17, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', NULL, 6200.00, 1, '2025-10-01 13:24:58', '2025-10-15 00:44:45'),
(18, 'Grupo Electrógeno Generador Niwa Inverter 2000w Gnw-2000-is', 1, 845612.00, 2, '2025-10-01 13:28:54', '2025-10-22 06:52:20'),
(19, 'Inodoro largo redonda Ferrum Andina Inodoro largo + mochila blanco', 1, 232102.00, 1, '2025-10-01 13:36:53', '2025-10-22 06:52:20'),
(20, 'Combo Griferia Fv Newport Plus 0900.03/B2P Baño Completo Color Plateado', 1, 458050.00, 1, '2025-10-01 13:38:34', '2025-10-22 06:52:20'),
(21, 'FERCOL H68 200L', 2, 646000.00, 4, '2025-10-01 13:46:57', '2025-10-22 06:52:20'),
(22, 'ANGULO HIERRO 31 8X3.2MM X 6 MT', 1, 500169.29, 1, '2025-10-01 14:36:36', '2025-10-22 06:52:20'),
(23, 'Hierro Liso Macizo Redondo De 25mm X 12mtHierro', 1, 99160.00, 1, '2025-10-01 14:45:15', '2025-10-22 06:52:20'),
(24, 'Varilla Hierro Para Construcción Acindar 12mm X 12mts (x5un)', 1, 121600.00, 1, '2025-10-01 14:53:55', '2025-10-22 06:52:20'),
(25, 'Compresor de Aire Pektra PK50L Kit Color Rojo', 1, 222487.15, 2, '2025-10-01 15:03:17', '2025-10-22 06:52:20'),
(26, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 1, 190877.13, 2, '2025-10-01 15:13:02', '2025-10-22 06:52:20'),
(27, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 1, 386099.43, 2, '2025-10-01 15:17:15', '2025-10-22 06:52:20'),
(28, 'Turbo Compresor Volkswagen Amarok 2.0 Biturbo 163hp', 1, 1765214.00, 5, '2025-10-01 15:28:59', '2025-10-22 06:52:20'),
(29, 'Caja De Cambios Volkswagen Amarok 2.0 Tdi 101974', 1, 1026815.87, 5, '2025-10-01 15:45:11', '2025-10-22 06:52:20'),
(30, 'Palier Para Mercedes Benz 1518-1620-1624', 2, 659125.00, 5, '2025-10-01 15:49:55', '2025-10-22 06:52:20'),
(31, 'Radiador M.benz 1112 / 1114 / 1518 1984 En Adelan *aluminio*', NULL, 1236540.21, 5, '2025-10-01 15:56:13', '2025-10-15 00:44:45'),
(32, 'Electrodo Soldar 2.5mm 308l Acero inoxidable x 1kl', NULL, 38409.89, 2, '2025-10-01 16:16:54', '2025-10-15 00:44:45'),
(33, 'Motorola G84 8ram 256GB', 3, 180000.00, 3, '2025-10-02 14:56:12', '2025-10-22 06:52:20'),
(34, 'Xiaomi redmi note 14 8ram 256GB', 3, 169090.00, 3, '2025-10-02 14:56:37', '2025-10-22 06:52:20'),
(35, 'Motorola G05 4ram 128GB', 3, 110000.00, 3, '2025-10-02 14:56:56', '2025-10-22 06:52:20'),
(36, 'Estereos Pionner', 3, 81818.00, 3, '2025-10-02 14:57:21', '2025-10-22 06:52:20'),
(37, 'Motorola edge 50 fusión 8ram 256Gb', 3, 245826.00, 3, '2025-10-02 14:59:03', '2025-10-22 06:52:20'),
(38, 'Motorola Edge 50 fusión 12ram y 512gb', 3, 317000.00, 3, '2025-10-02 16:24:23', '2025-10-22 06:52:20'),
(39, 'LUSQTOFF GABINETE Y CRIQUE  3 TON CON HERRAMIENTAS', NULL, 1274275.00, 2, '2025-10-02 22:04:50', '2025-10-15 00:44:45'),
(40, 'Hidrolavadora eléctrica Powerclean NX180/11M gris y negra de 3.7kW con 180bar de presión máxima', 1, 1821907.50, 2, '2025-10-02 22:09:18', '2025-10-22 06:52:20'),
(41, 'Set Herramientas Llaves Tubo Bremen Mechaniker 160pz', 1, 510536.00, 2, '2025-10-02 22:27:21', '2025-10-22 06:52:20'),
(42, 'Rimula R3 15w40 Turbo X 209', 2, 983010.00, 4, '2025-10-06 23:31:35', '2025-10-22 06:52:20'),
(43, 'Aceite Helix Ultra 5w40 Sintetico 209L', 2, 1597304.00, 4, '2025-10-06 23:32:35', '2025-10-22 06:52:20'),
(44, 'Helix Hx7 Semisintetico 10w40 X 209 L', 2, 1027172.00, 4, '2025-10-06 23:33:49', '2025-10-22 06:52:20'),
(45, 'Grasa Litio Blanca Extrema Presion 250 Gr Gama Alta X 180KG', 2, 1461498.00, 4, '2025-10-06 23:35:08', '2025-10-22 06:52:20'),
(46, 'Shell Spirax S2 A 80w-90 X 209 Lt', 2, 1177802.00, 4, '2025-10-06 23:37:06', '2025-10-22 06:52:20'),
(47, 'Malla De Construcción Q-188 150x150x6mm. 2.4x6mts. Polimetal $164.463,90', 2, 146372.00, 2, '2025-10-14 00:54:11', '2025-10-22 06:52:20'),
(48, 'Xiaomi Redmi 14C 8+8 RAM 256GB', 3, 158460.00, 0, '2025-10-21 22:35:30', '2025-10-22 06:52:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `tipo_contribuyente` varchar(255) DEFAULT NULL,
  `actividad` varchar(255) DEFAULT NULL,
  `cuit` varchar(20) DEFAULT NULL,
  `ingresos_brutos` varchar(255) DEFAULT NULL,
  `inicio_actividad` date DEFAULT NULL,
  `registradora_fiscal` varchar(255) DEFAULT NULL,
  `codigo_barra_cai` varchar(255) DEFAULT NULL,
  `fecha_vencimiento_cai` date DEFAULT NULL,
  `modelo_pdf` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `direccion`, `codigo_postal`, `tipo_contribuyente`, `actividad`, `cuit`, `ingresos_brutos`, `inicio_actividad`, `registradora_fiscal`, `codigo_barra_cai`, `fecha_vencimiento_cai`, `modelo_pdf`) VALUES
(5, 'RAUL E. ALFONSO', 'AV. LOPEZ Y PLANES 3524', '3300', 'IVA Responsable Inscripto', NULL, '30710383436', '30710383436', '2006-01-01', 'HSHSAB0000015605', '1212121212214454', '2025-09-24', NULL),
(7, 'EL CHANGO S.R.L', 'AVENIDA QUARANTA 5200', '3300', 'REPONABLE INC', 'ferreteria', '30-71186286-9', '30-71186286-9', '2011-03-11', 'CF HSHAB000012338', '', NULL, NULL),
(8, 'BOLSAPLAST S. R. L. ', 'JUAN J PASO Y RIVADA 0 - LEANDRO N. ALEM ', '3315', 'RESPO INC.', NULL, '30-68786852-4', '30-68786852-4', '0000-00-00', 'CF HSHSAB000017623', '', '1996-08-30', NULL),
(9, 'FERRETERIA CENTRO', 'SAN LORENZO 336', '3300', 'REPOSABLE INSC.', NULL, '3072018341-0', '270657485', '2007-03-28', 'CF HSHPL0000027631', '', '0000-00-00', NULL),
(10, 'FERROMISIONES S.R.L', 'AV. SAN MARTIN 4918 - POSADAS', '3300', 'RESP. INSC.', 'ferreteria', '30-71513654-2', '0214378609', '2015-10-27', 'CF HSHSAB0000053737', '', NULL, NULL),
(11, 'SERVICENTRO TAMBOR DE TACUARI', 'Av. Tambor de Tacuari  4951 - Posadas', '3300', 'Resp. Insc.', NULL, '30-62562461-0', '30625624610', '1988-06-08', 'CF EPEPAA0000026718', '', '0000-00-00', NULL),
(12, 'RENTACAR IGUAZU SRL SHELL', 'AV MISIONES 06 - IGUAZU', '3370', 'Resp. Insc.', NULL, '30599476608', '30599476608', '2021-05-20', 'CF HSHSAB0000016680', '', '0000-00-00', NULL),
(13, 'PETROMISIONES SA', 'AV. SANTA  CATALINA 4672 - POSADAS', '3300', 'Resp. insc.', NULL, '30-64430562-3', '30644305623', '1991-09-01', 'EPEPAA0000024470', '', '0000-00-00', NULL),
(14, 'J.R. GONZALEZ SRL', 'COLCOMBET 52 ELDORADO', '3380', 'RESP. INSC.', NULL, '30-71232293-0', '30712322930', '2011-09-17', 'EPEPAA000026501', '', '0000-00-00', NULL),
(15, 'CENTRAL  REPUESTO S.A', 'AV.JUAN MANUEL DE ROSAS 6315', '3300', 'RESP. INSC.', 'CASA', '30-67241590-6', '30672415906', '1994-10-14', 'CF 000460034005H033A', '', '0000-00-00', NULL),
(16, 'COMERCIALIZADORA E IMPORTADORA DEL NEA', 'Zabala 117 - Salta, Salta', '', 'responsable incripto', NULL, '20373272486', '20373272486', '2017-12-01', '', '75400280270789', '2025-10-02', NULL),
(17, 'FLORES MARIO CESAR', 'CORRIENTES 0 ', '3302', 'Responsable Inscripto', NULL, '20127425672', '20127425672', '0000-00-00', '', '', '0000-00-00', NULL),
(18, 'FAGUNDEZ JORGE LUIS', '58 Casa 6375 Piso:0 Dpto:0', '3300', 'Responsanle inscripto', NULL, '23420039549', '23420039549', '2025-04-01', '', '', '0000-00-00', NULL),
(19, 'vallego alejandro maximo', 'zabala 117- salta', '4400', 'REsponsable Incrito', NULL, '20-37327248-6', '20373272486', '0000-00-00', '', '', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `numero_factura` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `cliente_domicilio` varchar(255) DEFAULT NULL,
  `localidad` varchar(255) DEFAULT NULL,
  `cliente_cuit` varchar(255) DEFAULT NULL,
  `cliente_iva` varchar(255) DEFAULT NULL,
  `condicion_venta` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `empresa_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `numero_factura`, `fecha`, `cliente_nombre`, `cliente_domicilio`, `localidad`, `cliente_cuit`, `cliente_iva`, `condicion_venta`, `total`, `subtotal`, `iva`, `empresa_id`, `cliente_id`) VALUES
(31, '00012-00000006', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 16940.00, 14000.00, 2940.00, NULL, NULL),
(32, '00012-00000008', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 11495.00, 9500.00, 1995.00, NULL, NULL),
(33, '00012-00000008', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 11495.00, 9500.00, 1995.00, NULL, NULL),
(35, '00012-00000010', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Contado', 16940.00, 14000.00, 2940.00, NULL, NULL),
(36, '00012-00000011', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Contado', 57475.00, 47500.00, 9975.00, NULL, NULL),
(37, '00012-00000012', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 11495.00, 9500.00, 1995.00, NULL, NULL),
(38, '00012-00000013', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 16940.00, 14000.00, 2940.00, NULL, NULL),
(39, '00012-00000014', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Contado', 11495.00, 9500.00, 1995.00, NULL, NULL),
(40, '00012-00000013', '2025-06-25', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Contado', 16940.00, 14000.00, 2940.00, NULL, NULL),
(41, '00012-00000014', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(42, '00012-00000015', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(43, '00012-00000016', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(44, '00012-00000017', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(45, '00012-00000018', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(46, '00012-00000019', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, NULL),
(47, '00012-00000020', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 655113.36, 541416.00, 113697.00, NULL, NULL),
(48, '00012-00000022', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 16940.00, 14000.00, 2940.00, NULL, NULL),
(49, '00012-00000023', '2025-06-27', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Cuenta Corriente', 35211.00, 29100.00, 6111.00, NULL, NULL),
(50, '00012-00000024', '2025-07-12', 'SENGER EVALDO CUIT', 'V. DE LAS AMERICA 283', 'Aristobulo Del Valle', ' 20-24033611-2', 'Resp. Inscripto', 'Cuenta Corriente', 21780.00, 18000.00, 3780.00, NULL, 2),
(51, '00012-00000025', '2025-08-07', 'ddgdsgsdfg', 'dfgdsfgd', 'posadas', '27407918857', 'Resp. Inscripto', 'Contado', 204611.00, 169100.00, 35511.00, NULL, NULL),
(52, '00012-00000028', '2025-08-07', 'SENGER EVALDO CUIT', 'V. DE LAS AMERICA 283', 'Aristobulo Del Valle', ' 20-24033611-2', 'Resp. Inscripto', 'Contado', 12100.00, 10000.00, 2100.00, NULL, 2),
(53, '00012-00000030', '2025-08-07', 'SENGER EVALDO CUIT', 'V. DE LAS AMERICA 283', 'Aristobulo Del Valle', ' 20-24033611-2', 'Resp. Inscripto', 'Cuenta Corriente', 193600.00, 160000.00, 33600.00, NULL, 2),
(54, '00012-00000032', '2025-08-19', 'SENGER EVALDO CUIT', 'V. DE LAS AMERICA 283', 'Aristobulo Del Valle', ' 20-24033611-2', 'Resp. Inscripto', 'Cuenta Corriente', 12100.00, 10000.00, 2100.00, NULL, 2),
(55, '00012-00000035', '2025-08-21', ' ALCALDE ANDREA MAGALI ', 'CORDOBA Y PUEYRREDON 0 - BARRIO : CENTRO', 'Ituzaingo', '27402611443', 'Resp. Inscripto', 'Cuenta Corriente', 1966792.08, 1625448.00, 341344.00, NULL, NULL),
(56, '00012-00000037', '2025-08-21', 'GOMEZ DIEGO DONATO', 'LOTE 135-R. PCIAL 71', 'COLONIA LIEBIG\\\\\\\'S ', '20-32284301-2', 'Resp. Inscripto', 'Cuenta Corriente', 13068000.00, 10800000.00, 2268000.00, NULL, NULL),
(57, '00012-00000037', '2025-08-21', 'GOMEZ DIEGO DONATO', 'LOTE 135-R. PCIAL 71', 'COLONIA LIEBIG\\\\\\\'S ', '20-32284301-2', 'Resp. Inscripto', 'Cuenta Corriente', 13068000.00, 10800000.00, 2268000.00, NULL, NULL),
(58, '00012-00000039', '2025-08-21', ' ALCALDE ANDREA MAGALI  el los telefonos', 'CORDOBA Y PUEYRREDON 0 - BARRIO : CENTRO', 'Ituzaingo', '27402611443', 'Consumidor Final', 'Contado', 207272.00, 207272.00, 0.00, NULL, NULL),
(59, '00012-00000041', '2025-09-23', 'VERON LEONARDO ISMAEL', 'NARCISO LAPRIDA MZ ,17 LOTE 1 ', 'COLONIA LIE', '20-41153874-6', 'Resp. Inscripto', 'Cuenta Corriente', 28858500.00, 23850000.00, 5008500.00, 5, 5),
(60, '00012-00000043', '2025-09-02', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 3481627.53, 2877378.12, 604249.41, 7, 6),
(61, '00012-00000045', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1251140.00, 1034000.00, 217140.00, 7, 6),
(62, '00012-00000047', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1023190.52, 845612.00, 177578.52, 7, 6),
(63, '00012-00000049', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 835083.92, 690152.00, 144931.92, 7, 6),
(64, '00012-00000050', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1801403.96, 1488763.60, 312640.36, 13, 6),
(65, '00012-00000052', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1154427.12, 954072.00, 200355.12, 13, 6),
(66, '00070-00008101', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1989240.00, 1644000.00, 345240.00, 13, 6),
(67, '00012-00008102', '2025-09-17', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1028097.00, 849666.94, 178430.06, 14, 6),
(68, '00012-00008102', '2025-09-17', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1028097.00, 849666.94, 178430.06, 14, 6),
(69, '00012-00008104', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1210409.68, 1000338.58, 210071.10, 14, 6),
(70, '00012-00008104', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1210409.68, 1000338.58, 210071.10, 14, 6),
(71, '00012-00008106', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 719901.60, 594960.00, 124941.60, 14, 6),
(72, '00012-00008106', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1199836.00, 991600.00, 208236.00, 14, 6),
(73, '00012-00008108', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1029952.00, 851200.00, 178752.00, 14, 6),
(74, '00012-00008110', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 269209.45, 222487.15, 46722.30, 9, 6),
(75, '00012-00008112', '2025-09-26', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1847690.62, 1527017.04, 320673.58, 9, 6),
(76, '00012-00008114', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1868721.24, 1544397.72, 324323.52, 9, 6),
(77, '0004-00245564', '2025-09-18', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2135908.94, 1765214.00, 370694.94, 15, 6),
(78, '0004-00245564', '2025-09-18', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Contado', 2135908.94, 1765214.00, 370694.94, 15, 6),
(79, '00012-00245566', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1242447.20, 1026815.87, 215631.33, 15, 6),
(80, '00012-00245568', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 797541.25, 659125.00, 138416.25, 15, 6),
(81, '00012-00245570', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1496213.65, 1236540.21, 259673.44, 15, 6),
(82, '00012-00245572', '2025-09-19', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 929519.34, 768197.80, 161321.54, 10, 6),
(83, '00012-00245576', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 977558.82, 807899.85, 169658.97, 10, 6),
(84, '00070-00007101', '2025-09-10', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1154427.12, 954072.00, 200355.12, 13, 6),
(85, '00070-00007356', '2025-09-12', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1989240.00, 1644000.00, 345240.00, 13, 6),
(86, '00012-00007359', '2025-10-02', 'RIOS ARIEL ALFREDO', '6 28 Piso:0 Dpto:0 S:0 T:0 M:0 - BARRIO : B G SAN MARTIN', 'ITUZAINGO - Corrientes', '20379503625', 'Resp. Inscripto', 'Cuenta Corriente', 2470394.08, 2041648.00, 428746.08, 16, 7),
(87, '00012-00007361', '2025-10-02', 'Andrea Magali Alcalde', '', '', '27402611443', 'Resp. Inscripto', 'Cuenta Corriente', 5670921.52, 4686712.00, 984209.52, 16, 8),
(88, '00010-00160923', '2025-09-09', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1541872.75, 1274275.00, 267597.75, 10, 6),
(89, '00010-00160924', '2025-10-03', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2204508.08, 1821907.50, 382600.58, 10, 6),
(90, '00010-00160925', '2025-10-03', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 617748.56, 510536.00, 107212.56, 10, 6),
(91, '00010-00160926', '2025-09-09', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2159621.31, 1784811.00, 374810.31, 10, 6),
(92, '00010-00160926', '2025-09-09', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2159621.31, 1784811.00, 374810.31, 10, 6),
(93, '00012-00160928', '2025-09-23', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1413576.45, 1168245.00, 245331.45, 15, 6),
(94, '00010-00160926', '2025-09-09', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1377276.45, 1138245.00, 239031.45, 15, 6),
(95, '00012-00160928', '2025-10-06', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1768412.58, 1461498.00, 306914.58, 13, 6),
(96, '00012-00160930', '2025-09-15', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1189442.10, 983010.00, 206432.10, 13, 6),
(97, '00012-00160931', '2025-09-29', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1230778.12, 1017172.00, 213606.12, 13, 6),
(98, '00012-00160932', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 1934237.51, 1598543.40, 335694.11, 9, 9),
(99, '00012-00160934', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 1887600.00, 1560000.00, 327600.00, 9, 9),
(100, '00012-00160934', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 943800.00, 780000.00, 163800.00, 9, 9),
(101, '00012-00160936', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 450120.00, 372000.00, 78120.00, 9, 9),
(102, '00012-00160936', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 1500400.00, 1240000.00, 260400.00, 9, 9),
(103, '00012-00160936', '2025-10-13', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 1650440.00, 1364000.00, 286440.00, 9, 9),
(104, '00012-00160937', '2025-10-14', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 1799754.00, 1487400.00, 312354.00, 9, 9),
(105, '00012-00160939', '2025-10-14', 'MARTINS GABRIEL', 'CH.113-CASA 31', 'POSADAS', '20285527660', 'Resp. Inscripto', 'Cuenta Corriente', 885550.60, 731860.00, 153690.60, 9, 9),
(106, '00012-00160940', '2025-10-22', ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', '33718922939', 'Resp. Inscripto', 'Cuenta Corriente', 13712930.00, 11333000.00, 2379930.00, 18, 10),
(107, '00012-00160940', '2025-10-22', ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', '33718922939', 'Resp. Inscripto', 'Cuenta Corriente', 13712930.00, 11333000.00, 2379930.00, 19, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `detalle` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `factura_id`, `cantidad`, `detalle`, `precio_unitario`) VALUES
(1, 31, 1, 'arena', 14000.00),
(2, 32, 1, 'cemento', 9500.00),
(3, 33, 1, 'cemento', 9500.00),
(4, 35, 1, 'arena', 14000.00),
(5, 36, 5, 'cemento', 9500.00),
(6, 37, 1, 'cemento', 9500.00),
(7, 38, 1, 'arena', 14000.00),
(8, 39, 1, 'cemento', 9500.00),
(9, 40, 1, 'arena', 14000.00),
(10, 41, 1, 'cemento', 10000.00),
(11, 42, 1, 'cemento', 10000.00),
(12, 43, 1, 'cemento', 10000.00),
(13, 44, 1, 'cemento', 10000.00),
(14, 45, 1, 'cemento', 10000.00),
(15, 46, 1, 'cemneto', 10000.00),
(16, 47, 1, 'otor Burro Arranque Scani4 Serie 5 P G R T P340 P380', 527416.00),
(17, 47, 1, 'arena', 14000.00),
(18, 48, 1, 'arena', 14000.00),
(19, 49, 1, 'eryyu', 9100.00),
(20, 49, 1, 'cemento', 10000.00),
(21, 49, 1, 'cemento', 10000.00),
(22, 50, 2, 'cemesnntos', 9000.00),
(23, 51, 1, 'eryyu', 9100.00),
(24, 51, 1, 'cemento', 10000.00),
(25, 51, 1, 'flete hoja de yerba', 150000.00),
(26, 52, 1, 'cemento', 10000.00),
(27, 53, 1, 'cemento', 10000.00),
(28, 53, 1, 'flete hoja de yerba', 150000.00),
(29, 54, 1, 'cemento', 10000.00),
(30, 55, 5, 'Motorola G75 8ram 256BG.', 207272.00),
(31, 55, 4, 'Motorola G35 8ram 256GB.', 147272.00),
(32, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 1100000.00),
(33, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 1700000.00),
(34, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 1900000.00),
(35, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 1800000.00),
(36, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 1950000.00),
(37, 56, 1, 'servicio de flete de Hoja verde de yerba Mate', 2350000.00),
(38, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 1100000.00),
(39, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 1700000.00),
(40, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 1900000.00),
(41, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 1800000.00),
(42, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 1950000.00),
(43, 57, 1, 'servicio de flete de Hoja verde de yerba Mate', 2350000.00),
(44, 58, 1, 'Motorola G75 8ram 256BG.', 207272.00),
(45, 59, 15000, 'Combst. Liquido son por Cuenta y Orden de YPF SA Gas Oil', 1590.00),
(46, 60, 18, 'Chapa 1 x 2 Metros x 0.7 Mm Acero Inox', 159854.34),
(47, 61, 20, 'Cemento Loma Negra 25kg Gris', 5200.00),
(48, 61, 150, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', 6200.00),
(49, 62, 1, 'Grupo Electrógeno Generador Niwa Inverter 2000w Gnw-2000-is', 845612.00),
(50, 63, 1, 'Inodoro largo redonda Ferrum Andina Inodoro largo + mochila blanco', 232102.00),
(51, 63, 1, 'Combo Griferia Fv Newport Plus 0900.03/B2P Baño Completo Color Plateado', 458050.00),
(52, 64, 530, 'Combst. Liquido son por Cuenta y Orden de YPF SA Gas Oil', 1590.12),
(53, 64, 1, 'FERCOL H68 200L ', 646000.00),
(54, 65, 600, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1590.12),
(55, 66, 1, 'Aceite Fercol Para Motos 20w50 4 Tiempos Tambor De 200 Lt', 1644000.00),
(56, 67, 1, 'PERFIL U HIERRO DEL 16 X 16', 849666.94),
(57, 68, 1, 'PERFIL U HIERRO DEL 16 X 16', 849666.94),
(58, 69, 2, 'ANGULO HIERRO 31 8X3.2MM X 6 MT', 500169.29),
(59, 70, 2, 'ANGULO HIERRO 31 8X3.2MM X 6 MT', 500169.29),
(60, 71, 6, 'Hierro Liso Macizo Redondo De 25mm X 12mtHierro', 99160.00),
(61, 72, 10, 'Hierro Liso Macizo Redondo De 25mm X 12mtHierro', 99160.00),
(62, 73, 7, 'Varilla Hierro Para Construcción Acindar 12mm X 12mts (x5un)', 121600.00),
(63, 74, 1, 'Compresor de Aire Pektra PK50L Kit Color Rojo', 222487.15),
(64, 75, 8, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(65, 76, 4, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(66, 77, 1, 'Turbo Compresor Volkswagen Amarok 2.0 Biturbo 163hp', 1765214.00),
(67, 78, 1, 'Turbo Compresor Volkswagen Amarok 2.0 Biturbo 163hp', 1765214.00),
(68, 79, 1, 'Caja De Cambios Volkswagen Amarok 2.0 Tdi 101974', 1026815.87),
(69, 80, 1, 'Palier Para Mercedes Benz 1518-1620-1624', 659125.00),
(70, 81, 1, 'Radiador M.benz 1112 / 1114 / 1518 1984 En Adelan *aluminio*', 1236540.21),
(71, 82, 20, 'Electrodo Soldar 2.5mm 308l Acero inoxidable x 1kl', 38409.89),
(72, 83, 9, 'Electrodo Soldar 2.5mm 308l Acero inoxidable Conarco x 1kg', 89766.65),
(73, 84, 600, 'Combst. Liquido son por Cuenta y Orden de Gas Oil', 1590.12),
(74, 85, 1, 'Aceite Fercol Oleum Larga Vida 15w40 200 LT', 1644000.00),
(75, 86, 2, 'Motorola G84 8ram 256GB', 180000.00),
(76, 86, 3, 'Xiaomi redmi note 14 8ram 256GB', 169090.00),
(77, 86, 2, 'Motorola edge 50 fusión 8ram 256Gb', 245826.00),
(78, 86, 1, 'Motorola G05 4ram 128GB', 110000.00),
(79, 86, 7, 'Estereos Pionner', 81818.00),
(80, 87, 2, 'Motorola Edge 50 fusión 12ram y 512gb', 317000.00),
(81, 87, 16, 'Motorola G75 8ram 256BG.', 207272.00),
(82, 87, 5, 'Motorola G35 8ram 256GB.', 147272.00),
(83, 88, 1, 'LUSQTOFF GABINETE Y CRIQUE  3 TON CON HERRAMIENTAS', 1274275.00),
(84, 89, 1, 'Hidrolavadora eléctrica Powerclean NX180/11M gris y negra de 3.7kW con 180bar de presión máxima', 1821907.50),
(85, 90, 1, 'Set Herramientas Llaves Tubo Bremen Mechaniker 160pz', 510536.00),
(86, 91, 1, 'Set Herramientas Llaves Tubo Bremen Mechaniker 160pz', 510536.00),
(87, 91, 1, 'LUSQTOFF GABINETE Y CRIQUE  3 TON CON HERRAMIENTAS', 1274275.00),
(88, 92, 1, 'Set Herramientas Llaves Tubo Bremen Mechaniker 160pz', 510536.00),
(89, 92, 1, 'LUSQTOFF GABINETE Y CRIQUE  3 TON CON HERRAMIENTAS', 1274275.00),
(90, 93, 1, 'BOMBA DE ACEITE EUROCARGO IVECO', 1168245.00),
(91, 94, 1, 'TAPA DE CILINDRO FIAT 6119 N° ', 1138245.00),
(92, 95, 1, 'Grasa Litio Blanca Extrema Presion 250 Gr Gama Alta X 180KG', 1461498.00),
(93, 96, 1, 'Rimula R3 15w40 Turbo X 209', 983010.00),
(94, 97, 1, 'Helix Hx7 Semisintetico 10w40 X 209 L', 1017172.00),
(95, 98, 10, 'Chapa 1 x 2 Metros x 0.7 Mm', 159854.34),
(96, 99, 300, 'Cemento Loma Negra 25kg Gris', 5200.00),
(97, 100, 150, 'Cemento Loma Negra 25kg Gris', 5200.00),
(98, 101, 60, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', 6200.00),
(99, 102, 200, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', 6200.00),
(100, 103, 220, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', 6200.00),
(101, 104, 15, 'Hierro Liso Macizo Redondo De 25mm X 12mtHierro', 99160.00),
(102, 105, 5, 'Malla De Construcción Q-188 150x150x6mm. 2.4x6mts. Polimetal $164.463,90', 146372.00),
(103, 106, 20, 'Xiaomi Redmi 14C 8+8 RAM 256GB', 158460.00),
(104, 106, 20, 'Samsung Galaxy A16 4RAM 128GB', 172430.00),
(105, 106, 10, ' Xiaomi Redmi 15C 16 RAM 256GB', 169420.00),
(106, 106, 10, 'Motorola Edge 50 Fusion 5G 256GB 8GB RAM', 302100.00),
(107, 107, 20, 'Xiaomi Redmi 14C 8+8 RAM 256GB', 158460.00),
(108, 107, 20, 'Samsung Galaxy A16 4RAM 128GB', 172430.00),
(109, 107, 10, ' Xiaomi Redmi 15C 16 RAM 256GB', 169420.00),
(110, 107, 10, 'Motorola Edge 50 Fusion 5G 256GB 8GB RAM', 302100.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_cuenta_corriente`
--

CREATE TABLE `movimientos_cuenta_corriente` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `tipo` enum('factura','pago') NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `factura_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `movimientos_cuenta_corriente`
--

INSERT INTO `movimientos_cuenta_corriente` (`id`, `cliente_id`, `tipo`, `monto`, `fecha`, `observacion`, `factura_id`) VALUES
(1, 5, 'factura', 28858500.00, '2025-09-23', 'Factura N° 00012-00000041', 59),
(2, 6, 'factura', 3481627.53, '2025-09-02', 'Factura N° 00012-00000043', 60),
(3, 6, 'factura', 1251140.00, '2025-10-01', 'Factura N° 00012-00000045', 61),
(4, 6, 'factura', 1023190.52, '2025-10-01', 'Factura N° 00012-00000047', 62),
(5, 6, 'factura', 835083.92, '2025-10-01', 'Factura N° 00012-00000049', 63),
(6, 6, 'factura', 1801403.96, '2025-10-01', 'Factura N° 00012-00000050', 64),
(7, 6, 'factura', 1154427.12, '2025-10-01', 'Factura N° 00012-00000052', 65),
(8, 6, 'factura', 1989240.00, '2025-10-01', 'Factura N° 00070-00008101', 66),
(9, 6, 'factura', 1028097.00, '2025-09-17', 'Factura N° 00012-00008102', 67),
(10, 6, 'factura', 1028097.00, '2025-09-17', 'Factura N° 00012-00008102', 68),
(11, 6, 'factura', 1210409.68, '2025-10-01', 'Factura N° 00012-00008104', 69),
(12, 6, 'factura', 1210409.68, '2025-10-01', 'Factura N° 00012-00008104', 70),
(13, 6, 'pago', 1210409.68, '2025-10-01', 'DUPLICADP', NULL),
(14, 6, 'pago', 0.73, '2025-10-01', 'DAS', NULL),
(15, 6, 'factura', 719901.60, '2025-10-01', 'Factura N° 00012-00008106', 71),
(16, 6, 'factura', 1199836.00, '2025-10-01', 'Factura N° 00012-00008106', 72),
(17, 6, 'factura', 1029952.00, '2025-10-01', 'Factura N° 00012-00008108', 73),
(18, 6, 'factura', 269209.45, '2025-10-01', 'Factura N° 00012-00008110', 74),
(19, 6, 'factura', 1847690.62, '2025-09-26', 'Factura N° 00012-00008112', 75),
(20, 6, 'factura', 1868721.24, '2025-10-01', 'Factura N° 00012-00008114', 76),
(21, 6, 'factura', 2135908.94, '2025-09-18', 'Factura N° 0004-00245564', 77),
(22, 6, 'factura', 1242447.20, '2025-10-01', 'Factura N° 00012-00245566', 79),
(23, 6, 'factura', 797541.25, '2025-10-01', 'Factura N° 00012-00245568', 80),
(24, 6, 'factura', 1496213.65, '2025-10-01', 'Factura N° 00012-00245570', 81),
(25, 6, 'factura', 929519.34, '2025-09-19', 'Factura N° 00012-00245572', 82),
(26, 6, 'factura', 977558.82, '2025-10-01', 'Factura N° 00012-00245576', 83),
(27, 6, 'factura', 1154427.12, '2025-09-10', 'Factura N° 00070-00007101', 84),
(28, 6, 'factura', 1989240.00, '2025-09-12', 'Factura N° 00070-00007356', 85),
(29, 7, 'factura', 2470394.08, '2025-10-02', 'Factura N° 00012-00007359', 86),
(30, 8, 'factura', 5670921.52, '2025-10-02', 'Factura N° 00012-00007361', 87),
(31, 6, 'pago', 32460883.23, '2025-10-02', 'cerra cuanta', NULL),
(32, 6, 'factura', 1541872.75, '2025-09-09', 'Factura N° 00010-00160923', 88),
(33, 6, 'factura', 2204508.08, '2025-10-03', 'Factura N° 00010-00160924', 89),
(34, 6, 'factura', 617748.56, '2025-10-03', 'Factura N° 00010-00160925', 90),
(35, 6, 'pago', 4364129.39, '2025-10-02', 'pagado', NULL),
(36, 6, 'factura', 2159621.31, '2025-09-09', 'Factura N° 00010-00160926', 91),
(37, 6, 'factura', 2159621.31, '2025-09-09', 'Factura N° 00010-00160926', 92),
(38, 6, 'factura', 1413576.45, '2025-09-23', 'Factura N° 00012-00160928', 93),
(39, 6, 'factura', 1377276.45, '2025-09-09', 'Factura N° 00010-00160926', 94),
(40, 6, 'pago', 7110095.52, '2025-10-06', 'BUECA FAC', NULL),
(41, 6, 'factura', 1768412.58, '2025-10-06', 'Factura N° 00012-00160928', 95),
(42, 6, 'factura', 1189442.10, '2025-09-15', 'Factura N° 00012-00160930', 96),
(43, 6, 'factura', 1230778.12, '2025-09-29', 'Factura N° 00012-00160931', 97),
(44, 9, 'factura', 1934237.51, '2025-10-13', 'Factura N° 00012-00160932', 98),
(45, 9, 'factura', 1887600.00, '2025-10-13', 'Factura N° 00012-00160934', 99),
(46, 9, 'factura', 943800.00, '2025-10-13', 'Factura N° 00012-00160934', 100),
(47, 9, 'factura', 450120.00, '2025-10-13', 'Factura N° 00012-00160936', 101),
(48, 9, 'factura', 1500400.00, '2025-10-13', 'Factura N° 00012-00160936', 102),
(49, 9, 'factura', 1650440.00, '2025-10-13', 'Factura N° 00012-00160936', 103),
(50, 9, 'factura', 1799754.00, '2025-10-14', 'Factura N° 00012-00160937', 104),
(51, 9, 'factura', 885550.60, '2025-10-14', 'Factura N° 00012-00160939', 105),
(52, 10, 'factura', 13712930.00, '2025-10-22', 'Factura N° 00012-00160940', 106),
(53, 10, 'factura', 13712930.00, '2025-10-22', 'Factura N° 00012-00160940', 107);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_facturas`
--

CREATE TABLE `pagos_facturas` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `rubro`
--

INSERT INTO `rubro` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'ferreteria', 'Ferreteria y  electricidad', '2025-10-22 06:35:44'),
(2, 'lubricante ', 'lubricante y estacion de servicio', '2025-10-22 06:37:16'),
(3, 'telefonia ', 'celulares', '2025-10-22 06:45:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubros`
--

CREATE TABLE `rubros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`) VALUES
(1, 'admin', '0a0f303b5598e9f69fe9b8ae775bd29051bf5a025e3f09d681b6c551f49d8fce', 'Administrador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubro_id` (`rubro_id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_cuenta_corriente`
--
ALTER TABLE `movimientos_cuenta_corriente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `factura_id` (`factura_id`);

--
-- Indices de la tabla `pagos_facturas`
--
ALTER TABLE `pagos_facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`);

--
-- Indices de la tabla `rubro`
--
ALTER TABLE `rubro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rubros`
--
ALTER TABLE `rubros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalles`
--
ALTER TABLE `detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de la tabla `movimientos_cuenta_corriente`
--
ALTER TABLE `movimientos_cuenta_corriente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `pagos_facturas`
--
ALTER TABLE `pagos_facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rubros`
--
ALTER TABLE `rubros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles`
--
ALTER TABLE `detalles`
  ADD CONSTRAINT `detalles_ibfk_1` FOREIGN KEY (`rubro_id`) REFERENCES `rubro` (`id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `fk_facturas_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facturas_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_cuenta_corriente`
--
ALTER TABLE `movimientos_cuenta_corriente`
  ADD CONSTRAINT `movimientos_cuenta_corriente_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `movimientos_cuenta_corriente_ibfk_2` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`);

--
-- Filtros para la tabla `pagos_facturas`
--
ALTER TABLE `pagos_facturas`
  ADD CONSTRAINT `pagos_facturas_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
