-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2025 a las 05:44:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

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
(10, ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', 'Responsable Inscropto', '33718922939', '', ''),
(11, 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', 'Responsable Incripto', '27-38187875-4', '', ''),
(12, 'CASELLI ROSA ANA ', 'CODIGO 041 S/N, LOTE 11 PARC 37 KM 0 - CASI RUTA 103', 'OBERA-MISIONES', '', '27-22085329-8', '', ''),
(13, 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '', '20-23429111-5', '', ''),
(14, 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '', '33-71546440-9', '', ''),
(15, 'Salvador Joaquin Comparin', 'FELIX DE AZARA 0 - BARRIO : CENTRO', 'GOB. ING. V. VIRASORO ', 'responsable inscripto ', '23420039549', '', ''),
(16, 'GOMEZ DIEGO DONATO', 'LOTE 135-R. PCIAL 71 3358', 'COLONIA LIEBIGS ', 'responsable inscripto ', '20322843012', '', ''),
(17, 'roly', 'vila cabello', 'posads', 'responsable inc', '20247012754', '', ''),
(18, 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', 'Responsable inpcrito', '20211797186', '', '');

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
(13, 'servicio de flete de Hoja verde de yerba Mate', 5, 1100000.00, 7, '2025-08-22 00:51:38', '2025-11-14 04:52:02'),
(14, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 2, 1314.14, 4, '2025-09-23 22:47:59', '2025-10-30 20:33:06'),
(15, 'Chapa 1 x 2 Metros x 0.7 Mm', 1, 159854.34, 1, '2025-10-01 11:58:36', '2025-10-22 06:52:20'),
(16, 'Cemento Loma Negra 25kg Gris', 1, 5200.00, 2, '2025-10-01 13:21:36', '2025-11-14 04:50:45'),
(17, 'Cerámica Oslo Símil Carrara/calacatta 35x35 x mt', 1, 6200.00, 1, '2025-10-01 13:24:58', '2025-11-14 04:50:10'),
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
(28, 'Turbo Compresor Volkswagen Amarok 2.0 Biturbo 163hp', 4, 1765214.00, 5, '2025-10-01 15:28:59', '2025-11-14 04:54:03'),
(29, 'Caja De Cambios Volkswagen Amarok 2.0 Tdi 101974', 4, 1026815.87, 5, '2025-10-01 15:45:11', '2025-11-14 04:53:36'),
(30, 'Palier Para Mercedes Benz 1518-1620-1624', 2, 659125.00, 5, '2025-10-01 15:49:55', '2025-10-22 06:52:20'),
(31, 'Radiador M.benz 1112 / 1114 / 1518 1984 En Adelan *aluminio*', 4, 1236540.21, 5, '2025-10-01 15:56:13', '2025-11-14 04:49:23'),
(32, 'Electrodo Soldar 2.5mm 308l Acero inoxidable x 1kl', 1, 38409.89, 2, '2025-10-01 16:16:54', '2025-11-14 04:49:47'),
(33, 'Motorola G84 8ram 256GB', 3, 180000.00, 3, '2025-10-02 14:56:12', '2025-10-22 06:52:20'),
(34, 'Xiaomi redmi note 14 8ram 256GB', 3, 169090.00, 3, '2025-10-02 14:56:37', '2025-10-22 06:52:20'),
(35, 'Motorola G05 4ram 128GB', 3, 110000.00, 3, '2025-10-02 14:56:56', '2025-10-22 06:52:20'),
(36, 'Estereos Pionner', 3, 81818.00, 3, '2025-10-02 14:57:21', '2025-10-22 06:52:20'),
(37, 'Motorola edge 50 fusión 8ram 256Gb', 3, 245826.00, 3, '2025-10-02 14:59:03', '2025-10-22 06:52:20'),
(38, 'Motorola Edge 50 fusión 12ram y 512gb', 3, 317000.00, 3, '2025-10-02 16:24:23', '2025-10-22 06:52:20'),
(39, 'LUSQTOFF GABINETE Y CRIQUE  3 TON CON HERRAMIENTAS', 1, 1274275.00, 2, '2025-10-02 22:04:50', '2025-11-14 04:48:26'),
(40, 'Hidrolavadora eléctrica Powerclean NX180/11M gris y negra de 3.7kW con 180bar de presión máxima', 1, 1821907.50, 2, '2025-10-02 22:09:18', '2025-10-22 06:52:20'),
(41, 'Set Herramientas Llaves Tubo Bremen Mechaniker 160pz', 1, 510536.00, 2, '2025-10-02 22:27:21', '2025-10-22 06:52:20'),
(42, 'Rimula R3 15w40 Turbo X 209', 2, 983010.00, 4, '2025-10-06 23:31:35', '2025-10-22 06:52:20'),
(43, 'Aceite Helix Ultra 5w40 Sintetico 209L', 2, 1597304.00, 4, '2025-10-06 23:32:35', '2025-11-14 04:52:47'),
(44, 'Helix Hx7 Semisintetico 10w40 X 209 L', 2, 1027172.00, 4, '2025-10-06 23:33:49', '2025-10-22 06:52:20'),
(45, 'Grasa Litio Blanca Extrema Presion 250 Gr Gama Alta X 180KG', 2, 1461498.00, 4, '2025-10-06 23:35:08', '2025-10-22 06:52:20'),
(46, 'Shell Spirax S2 A 80w-90 X 209 Lt', 2, 1177802.00, 4, '2025-10-06 23:37:06', '2025-10-22 06:52:20'),
(47, 'Malla De Construcción Q-188 150x150x6mm. 2.4x6mts. Polimetal $164.463,90', 2, 146372.00, 2, '2025-10-14 00:54:11', '2025-10-22 06:52:20'),
(48, 'Xiaomi Redmi 14C 8+8 RAM 256GB', 3, 158460.00, 0, '2025-10-21 22:35:30', '2025-10-22 06:52:20'),
(49, 'Caño Corrugado Blanco Genrod 3/4 20mm Pack X3 Rollos X25mts', 1, 21326.00, 0, '2025-10-30 21:50:58', '2025-10-30 21:50:58'),
(50, 'Taladro Percutor Inalambrico 20v Dewalt Dcd985l2 Baterias Ma Amarillo', 1, 999000.00, 0, '2025-10-30 22:27:09', '2025-10-30 22:27:09'),
(51, 'Piedra Partida Construccion En Bolson X M3', 1, 81000.00, 0, '2025-10-30 22:30:17', '2025-10-30 22:30:17'),
(52, 'Arena En Bolson X 900kg', 1, 35000.00, 0, '2025-10-30 22:31:31', '2025-10-30 22:31:31'),
(53, 'Llave Trifasica Termica Tetrapolar Baw 4x40', 1, 15234.00, 0, '2025-10-30 22:43:22', '2025-10-30 22:43:22'),
(54, 'Llave Termica Bipolar Easy9 2x16 4,5ka Curva C Schneider', 1, 17547.00, 0, '2025-10-30 22:45:36', '2025-10-30 22:45:36'),
(55, 'Combst. Liquido Infinia Diesel', 2, 1478.51, 0, '2025-11-04 12:07:58', '2025-11-04 12:07:58'),
(56, 'Combo Juego Roca Baño Griferia Vanitory C2 6c', 1, 1017047.97, 0, '2025-11-04 12:15:01', '2025-11-04 12:15:01'),
(57, 'Caja De Dirección Chevrolet S10 S/10 Trailblazer Original', 4, 5450446.00, 0, '2025-11-04 12:39:00', '2025-11-14 04:51:08'),
(58, 'Cubiertas Toyota Hilux Original Dunlop 265 60 Rodado 18 At25 H', 6, 700020.66, 0, '2025-11-20 00:26:14', '2025-11-20 00:26:14'),
(59, 'Alternador Tipo Bosch Iveco Scania 112 113 24v 80a 1c', 4, 376033.06, 0, '2025-11-20 00:42:54', '2025-11-20 00:42:54');

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
  `tipo_fac` varchar(50) DEFAULT 'Modelo PDF',
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

INSERT INTO `empresas` (`id`, `nombre`, `direccion`, `codigo_postal`, `tipo_contribuyente`, `actividad`, `tipo_fac`, `cuit`, `ingresos_brutos`, `inicio_actividad`, `registradora_fiscal`, `codigo_barra_cai`, `fecha_vencimiento_cai`, `modelo_pdf`) VALUES
(5, 'RAUL E. ALFONSO', 'AV. LOPEZ Y PLANES 3524', '3300', 'IVA Responsable Inscripto', 'estacion de servicio ypf', 'Modelo PDF', '30710383436', '30710383436', '2006-01-01', 'HSHSAB0000015605', '1212121212214454', '2025-09-24', ''),
(7, 'EL CHANGO S.R.L', 'AVENIDA QUARANTA 5200', '3300', 'REPONABLE INC', 'ferreteria', 'Tique', '30-71186286-9', '30-71186286-9', '2011-03-11', 'CF HSHAB000012338', '', NULL, ''),
(8, 'BOLSAPLAST S. R. L. ', 'JUAN J PASO Y RIVADA 0 - LEANDRO N. ALEM ', '3315', 'RESPO INC.', '', 'Tique', '30-68786852-4', '30-68786852-4', NULL, 'CF HSHSAB000017623', '', '2025-11-14', 'modelo_8_1764561954.pdf'),
(9, 'FERRETERIA CENTRO', 'SAN LORENZO 336', '3300', 'REPOSABLE INSC.', 'FERRETERIA', 'Modelo PDF', '3072018341-0', '270657485', '2007-03-28', 'CF HSHPL0000027631', '', NULL, ''),
(10, 'FERROMISIONES S.R.L', 'AV. SAN MARTIN 4918 - POSADAS', '3300', 'RESP. INSC.', 'ferreteria', 'Modelo PDF', '30-71513654-2', '0214378609', '2015-10-27', 'CF HSHSAB0000053737', '', NULL, NULL),
(11, 'SERVICENTRO TAMBOR DE TACUARI', 'Av. Tambor de Tacuari  4951 - Posadas', '3300', 'Resp. Insc.', NULL, 'Modelo PDF', '30-62562461-0', '30625624610', '1988-06-08', 'CF EPEPAA0000026718', '', '0000-00-00', NULL),
(12, 'RENTACAR IGUAZU SRL SHELL', 'AV MISIONES 06 - IGUAZU', '3370', 'Resp. Insc.', NULL, 'Modelo PDF', '30599476608', '30599476608', '2021-05-20', 'CF HSHSAB0000016680', '', '0000-00-00', NULL),
(13, 'PETROMISIONES SA', 'AV. SANTA  CATALINA 4672 - POSADAS', '3300', 'Resp. insc.', 'Estacion de servicio', 'Modelo PDF', '30-64430562-3', '30644305623', '1991-09-01', 'EPEPAA0000024470', '', NULL, ''),
(14, 'J.R. GONZALEZ SRL', 'COLCOMBET 52 ELDORADO', '3380', 'RESP. INSC.', NULL, 'Modelo PDF', '30-71232293-0', '30712322930', '2011-09-17', 'EPEPAA000026501', '', '0000-00-00', NULL),
(15, 'CENTRAL  REPUESTO S.A', 'AV.JUAN MANUEL DE ROSAS 6315', '3300', 'RESP. INSC.', 'CASA', 'Tique', '30-67241590-6', '30672415906', '1994-10-14', 'CF 000460034005H033A', '', NULL, 'pdfmodelo/plantillacentral_respuesto.pdf'),
(16, 'COMERCIALIZADORA E IMPORTADORA DEL NEA', 'Zabala 117 - Salta, Salta', '', 'responsable incripto', '', 'Electrónica', '20373272486', '20373272486', '2017-12-01', '', '75400280270789', '2025-10-02', ''),
(17, 'FLORES MARIO CESAR', 'CORRIENTES 0 ', '3302', 'Responsable Inscripto', NULL, 'Modelo PDF', '20127425672', '20127425672', '0000-00-00', '', '', '0000-00-00', NULL),
(18, 'FAGUNDEZ JORGE LUIS', '58 Casa 6375 Piso:0 Dpto:0', '3300', 'Responsanle inscripto', NULL, 'Modelo PDF', '23420039549', '23420039549', '2025-04-01', '', '', '0000-00-00', NULL),
(19, 'vallego alejandro maximo', 'zabala 117- salta', '4400', 'REsponsable Incrito', NULL, 'Modelo PDF', '20-37327248-6', '20373272486', '0000-00-00', '', '', '0000-00-00', NULL),
(20, 'NEUMATICO NORTE SRL', 'RUTA 12 KM 50', '3300', 'IVA Responsable Incripto', 'Venta de camara y nemativo automotor', 'Modelo PDF', '30-7094709-7', '021-342254-7', '2005-06-30', '', '', NULL, '');

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
(107, '00012-00160940', '2025-10-22', ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', '33718922939', 'Resp. Inscripto', 'Cuenta Corriente', 13712930.00, 11333000.00, 2379930.00, 19, 10),
(108, '00012-00160941', '2025-10-30', 'Andrea Magali Alcalde', '', '', '27402611443', 'Resp. Inscripto', 'Cuenta Corriente', 1932737.84, 1597304.00, 335433.84, 15, 8),
(109, '00012-00160941', '2025-10-30', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 467180.31, 386099.43, 81080.88, 7, 11),
(110, '00012-00160942', '2025-10-30', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 119258.21, 98560.50, 20697.71, 13, 11),
(111, '00012-00160942', '2025-10-30', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 116077.99, 95932.22, 20145.77, 13, 11),
(112, '00012-00160943', '2025-10-30', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 17061.00, 14100.00, 2961.00, 7, 11),
(113, '00012-00160942', '2025-10-30', 'CASELLI ROSA ANA ', 'CODIGO 041 S/N, LOTE 11 PARC 37 KM 0 - CASI RUTA 103', 'OBERA-MISIONES', '27-22085329-8', 'Resp. Inscripto', 'Cuenta Corriente', 2335901.55, 1930497.15, 405404.40, 9, 12),
(114, '00012-00160943', '2025-10-30', 'CASELLI ROSA ANA ', 'CODIGO 041 S/N, LOTE 11 PARC 37 KM 0 - CASI RUTA 103', 'OBERA-MISIONES', '27-22085329-8', 'Resp. Inscripto', 'Cuenta Corriente', 692883.98, 572631.39, 120252.59, 7, 12),
(115, '00012-00160944', '2025-10-30', 'CASELLI ROSA ANA ', 'CODIGO 041 S/N, LOTE 11 PARC 37 KM 0 - CASI RUTA 103', 'OBERA-MISIONES', '27-22085329-8', 'Resp. Inscripto', 'Cuenta Corriente', 490284.74, 405194.00, 85090.74, 7, 12),
(116, '00012-00160944', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 516089.20, 426520.00, 89569.20, 7, 13),
(117, '00012-00160945', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 934360.84, 772199.04, 162161.80, 7, 13),
(118, '00012-00160946', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 568700.00, 470000.00, 98700.00, 7, 13),
(119, '00012-00160947', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 1208790.00, 999000.00, 209790.00, 7, 13),
(120, '00012-00160948', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 490050.00, 405000.00, 85050.00, 7, 13),
(121, '00012-00160949', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 423500.00, 350000.00, 73500.00, 7, 13),
(122, '00012-00160950', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 692883.98, 572631.39, 120252.59, 7, 13),
(123, '00012-00160951', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 368662.80, 304680.00, 63982.80, 7, 13),
(124, '00012-00160952', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 424637.40, 350940.00, 73697.40, 7, 13),
(125, '00012-00160953', '2025-10-30', 'KACHUK ALBERTO FABIAN', 'AMBROSETTI 2135', 'POSADAS', '20-23429111-5', 'Resp. Inscripto', 'Cuenta Corriente', 191086.83, 157923.00, 33163.83, 7, 13),
(126, '00012-00160953', '2025-10-30', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 382173.66, 315846.00, 66327.66, 7, 14),
(127, '00012-00160954', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 829491.30, 685530.00, 143961.30, 7, 14),
(128, '00012-00160955', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 2335901.55, 1930497.15, 405404.40, 7, 14),
(129, '00012-00160956', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 1058750.00, 875000.00, 183750.00, 7, 14),
(130, '00012-00160957', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 980100.00, 810000.00, 170100.00, 7, 14),
(131, '00012-00160958', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 2417580.00, 1998000.00, 419580.00, 7, 14),
(132, '00012-00160959', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 1132560.00, 936000.00, 196560.00, 7, 14),
(133, '00012-00160960', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 1154806.64, 954385.65, 200420.99, 7, 14),
(134, '00012-00160961', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 1419245.30, 1172930.00, 246315.30, 7, 14),
(135, '00012-00160962', '2025-10-31', 'INTERCOM S.R.L', 'AV. ITALIA 742', 'OBERA', '33-71546440-9', 'Resp. Inscripto', 'Cuenta Corriente', 127050.00, 105000.00, 22050.00, 7, 14),
(136, '00012-00160964', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 3519996.48, 2909088.00, 610908.48, 20, 6),
(137, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1272087.52, 1051312.00, 220775.52, 13, 6),
(138, '00012-00160964', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1932737.84, 1597304.00, 335433.84, 13, 6),
(139, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 127208.75, 105131.20, 22077.55, 13, 6),
(140, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 119258.21, 98560.50, 20697.71, 13, 6),
(141, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 159010.94, 131414.00, 27596.94, 13, 6),
(142, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 159010.94, 131414.00, 27596.94, 13, 6),
(143, '00012-00160965', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 715598.84, 591404.00, 124194.84, 13, 6),
(144, '00012-00160966', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2461256.09, 2034095.94, 427160.15, 9, 6),
(145, '00012-00160967', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1770273.56, 1463036.00, 307237.56, 9, 6),
(146, '00012-00008106', '2025-10-01', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 2115297.80, 1748180.00, 367117.80, 14, 6),
(147, '00012-00008108', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 3448645.20, 2850120.00, 598525.20, 7, 6),
(148, '00012-00008109', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 6595039.66, 5450446.00, 1144593.66, 15, 6),
(149, '00012-00008110', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1374229.67, 1135727.00, 238502.67, 10, 6),
(150, '00012-00008111', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 1559489.14, 1288834.00, 270655.14, 10, 6),
(151, '00012-00008112', '2025-11-04', 'COOP.DE PROVISION DE SERVI. AGRO TURI. LA COSTERA LIMITAD', '30-71186763-1', 'Soberbio ', '30-71186763-1', 'Resp. Inscripto', 'Cuenta Corriente', 268349.57, 221776.50, 46573.06, 13, 6),
(152, '00012-00008113', '2025-11-07', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 715598.84, 591404.00, 124194.84, 13, 11),
(153, '00012-00008114', '2025-11-07', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 1401540.93, 1158298.29, 243242.64, 9, 11),
(154, '00012-00008115', '2025-11-07', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 692883.98, 572631.39, 120252.59, 9, 11),
(155, '00012-00008116', '2025-11-07', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 503360.00, 416000.00, 87360.00, 10, 11),
(156, '00012-00008116', '2025-11-07', 'MARKENDORF MICAELA', 'CATRIEL 28 - ENTRE LAS CALLES : VILLA GUAY Y AV LOS INMIGRANTES', 'OBERA- MISIONES', '27-38187875-4', 'Resp. Inscripto', 'Cuenta Corriente', 692120.00, 572000.00, 120120.00, 10, 11),
(157, '00012-00008117', '2025-11-08', 'Salvador Joaquin Comparin', '', '', '23420039549', 'Resp. Inscripto', 'Cuenta Corriente', 3093088.76, 2556271.70, 536817.06, 9, 15),
(158, '00012-00008118', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 357799.42, 295702.00, 62097.42, 13, 16),
(159, '00012-00008119', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 1242447.20, 1026815.87, 215631.33, 15, 16),
(160, '00012-00008120', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 268349.57, 221776.50, 46573.06, 13, 16),
(161, '00012-00008121', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 214679.65, 177421.20, 37258.45, 13, 16),
(162, '00012-00008122', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 1932737.84, 1597304.00, 335433.84, 13, 16),
(163, '00012-00008123', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 934360.62, 772198.86, 162161.76, 9, 16),
(164, '00012-00008124', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 1815614.52, 1500507.87, 315106.65, 9, 16),
(165, '00012-00008125', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 1108481.00, 916100.00, 192381.00, 9, 16),
(166, '00012-00008126', '2025-11-13', 'GOMEZ DIEGO DONATO', '20-32284301-2', 'COLONIA LIEBIG\\\\\\\\\\\\\\\'S ', '20322843012', 'Resp. Inscripto', 'Cuenta Corriente', 923845.31, 763508.52, 160336.79, 10, 16),
(168, '00012-00008128', '2025-11-14', 'roly', 'vila cabello', 'posads', '20247012754', 'Resp. Inscripto', 'Cuenta Corriente', 605204.84, 500169.29, 105035.55, 8, 17),
(176, '00012-00008129', '2025-11-20', 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', '20211797186', 'Resp. Inscripto', 'Cuenta Corriente', 847025.00, 700020.66, 147004.34, 20, 18),
(177, '00012-00008130', '2025-11-20', 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', '20211797186', 'Resp. Inscripto', 'Cuenta Corriente', 455000.00, 376033.06, 78966.94, 15, 18),
(178, '00012-00008131', '2025-11-20', 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', '20211797186', 'Resp. Inscripto', 'Cuenta Corriente', 357799.42, 295702.00, 62097.42, 11, 18),
(179, '00012-00008131', '2025-11-20', 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', '20211797186', 'Resp. Inscripto', 'Cuenta Corriente', 282661.54, 233604.58, 49056.96, 11, 18),
(180, '00012-00008131', '2025-11-20', 'LEMES NESTOR', 'Martin M.M. De Guemes 2531', 'Gob. Ing. V. Virasoro', '20211797186', 'Resp. Inscripto', 'Cuenta Corriente', 143119.77, 118280.80, 24838.97, 11, 18),
(181, '00012-00008132', '2025-11-25', ' EUSTORE S. A. S.', 'BEETHOVEN 1650 Piso:13 Dpto:A', 'POSADAS ', '33718922939', 'Resp. Inscripto', 'Cuenta Corriente', 1932737.84, 1597304.00, 335433.84, 8, 10),
(182, '00012-00008134', '2025-11-25', 'Andrea Magali Alcalde', '', '', '27402611443', 'Resp. Inscripto', 'Contado', 1932737.84, 1597304.00, 335433.84, 8, 8),
(183, '00012-00008135', '2025-12-01', 'roly', 'vila cabello', 'posads', '20247012754', 'Resp. Inscripto', 'Contado', 3865475.68, 3194608.00, 670867.68, 8, 17);

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
(110, 107, 10, 'Motorola Edge 50 Fusion 5G 256GB 8GB RAM', 302100.00),
(111, 108, 1, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00),
(112, 109, 1, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(113, 110, 75, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(114, 111, 73, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(115, 112, 3, 'Cemento Loma Negra 25kg Gris', 4700.00),
(116, 113, 5, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(117, 114, 3, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(118, 115, 19, 'Caño Corrugado Blanco Genrod 3/4 20mm Pack X3 Rollos X25mts', 21326.00),
(119, 116, 20, 'Caño Corrugado Blanco Genrod 3/4 20mm Pack X3 Rollos X25mts', 21326.00),
(120, 117, 2, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.52),
(121, 118, 100, 'Cemento Loma Negra 25kg Gris', 4700.00),
(122, 119, 1, 'Taladro Percutor Inalambrico 20v Dewalt Dcd985l2 Baterias Ma Amarillo', 999000.00),
(123, 120, 5, 'Piedra Partida Construccion En Bolson X M3', 81000.00),
(124, 121, 10, 'Arena En Bolson X 900kg', 35000.00),
(125, 122, 3, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(126, 123, 20, 'Llave Trifasica Termica Tetrapolar Baw 4x40', 15234.00),
(127, 124, 20, 'Llave Termica Bipolar Easy9 2x16 4,5ka Curva C Schneider', 17547.00),
(128, 125, 9, 'Llave Termica Bipolar Easy9 2x16 4,5ka Curva C Schneider', 17547.00),
(129, 126, 18, 'Llave Termica Bipolar Easy9 2x16 4,5ka Curva C Schneider', 17547.00),
(130, 127, 45, 'Llave Trifasica Termica Tetrapolar Baw 4x40', 15234.00),
(131, 128, 5, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(132, 129, 25, 'Arena En Bolson X 900kg', 35000.00),
(133, 130, 10, 'Piedra Partida Construccion En Bolson X M3', 81000.00),
(134, 131, 2, 'Taladro Percutor Inalambrico 20v Dewalt Dcd985l2 Baterias Ma Amarillo', 999000.00),
(135, 132, 180, 'Cemento Loma Negra 25kg Gris', 5200.00),
(136, 133, 5, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(137, 134, 55, 'Caño Corrugado Blanco Genrod 3/4 20mm Pack X3 Rollos X25mts', 21326.00),
(138, 135, 3, 'Arena En Bolson X 900kg', 35000.00),
(139, 136, 4, 'Cubierta taco 295 M 765', 727272.00),
(140, 137, 800, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(141, 138, 1, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00),
(142, 139, 80, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(143, 140, 75, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(144, 141, 100, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(145, 142, 100, 'Combst. Liquido son por Cuenta y Orden de  Gas Oil', 1314.14),
(146, 143, 400, 'Combst. Liquido Infinia Diesel', 1478.51),
(147, 144, 2, 'Combo Juego Roca Baño Griferia Vanitory C2 6c', 1017047.97),
(148, 145, 1, 'Combo griferias Fv dominiic monovomando de cromo ', 1463036.00),
(149, 146, 35, 'Hierro Liso Macizo Redondo De 12mm X 12mtHierro', 49948.00),
(150, 147, 30, 'Porcelanato Portiinari Lumina Bk', 95004.00),
(151, 148, 1, 'Caja De Dirección Chevrolet S10 S/10 Trailblazer Original', 5450446.00),
(152, 149, 1, 'amoladora angular bosch proconal 2.4 KW 220 V ', 1135727.00),
(153, 150, 1, 'Sierra circular de mesa Bosch transp ', 1288834.00),
(154, 151, 150, 'Combst. Liquido Infinia Diesel', 1478.51),
(155, 152, 400, 'Combst. Liquido Infinia Diesel', 1478.51),
(156, 153, 3, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(157, 154, 3, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(158, 155, 80, 'Cemento Loma Negra 25kg Gris', 5200.00),
(159, 156, 110, 'Cemento Loma Negra 25kg Gris', 5200.00),
(160, 157, 50, 'Cemento Loma Negra 25kg Gris', 5200.00),
(161, 157, 15, 'Arena En Bolson X 900kg', 35000.00),
(162, 157, 12, 'Piedra Partida Construccion En Bolson X M3', 81000.00),
(163, 157, 5, 'Chapa 1 x 2 Metros x 0.7 Mm', 159854.34),
(164, 158, 200, 'Combst. Liquido Infinia Diesel', 1478.51),
(165, 159, 1, 'Caja De Cambios Volkswagen Amarok 2.0 Tdi 101974', 1026815.87),
(166, 160, 150, 'Combst. Liquido Infinia Diesel', 1478.51),
(167, 161, 120, 'Combst. Liquido Infinia Diesel', 1478.51),
(168, 162, 1, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00),
(169, 163, 2, 'Cable Subterraneo nombali  sello 2x6mm x 100m', 386099.43),
(170, 164, 3, 'ANGULO HIERRO 31 8X3.2MM X 6 MT', 500169.29),
(171, 165, 2, 'Combo Griferia Fv Newport Plus 0900.03/B2P Baño Completo Color Plateado', 458050.00),
(172, 166, 4, 'Cable Unipolar Kaop 4mm cat 5 x200mts Norma Iram', 190877.13),
(174, 168, 1, 'ANGULO HIERRO 31 8X3.2MM X 6 MT', 500169.29),
(182, 176, 1, 'Cubiertas Toyota Hilux Original Dunlop 265 60 Rodado 18 At25 H', 700020.66),
(183, 177, 1, 'Alternador Tipo Bosch Iveco Scania 112 113 24v 80a 1c', 376033.06),
(184, 178, 200, 'Combst. Liquido Infinia Diesel', 1478.51),
(185, 179, 158, 'Combst. Liquido Infinia Diesel', 1478.51),
(186, 180, 80, 'Combst. Liquido Infinia Diesel', 1478.51),
(187, 181, 1, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00),
(188, 182, 1, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00),
(189, 183, 2, 'Aceite Helix Ultra 5w40 Sintetico 209L', 1597304.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo_config`
--

CREATE TABLE `modelo_config` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `page_width_mm` int(11) NOT NULL DEFAULT 80,
  `font_name` varchar(100) NOT NULL DEFAULT 'Helvetica',
  `font_size` int(3) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `font_bold` tinyint(1) NOT NULL DEFAULT 0,
  `font_italic` tinyint(1) NOT NULL DEFAULT 0,
  `font_underline` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo_config`
--

INSERT INTO `modelo_config` (`id`, `empresa_id`, `page_width_mm`, `font_name`, `font_size`, `created_at`, `updated_at`, `font_bold`, `font_italic`, `font_underline`) VALUES
(1, 8, 80, 'Helvetica', 10, '2025-11-25 07:23:08', '2025-12-01 04:07:49', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo_posiciones`
--

CREATE TABLE `modelo_posiciones` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `x_pct` decimal(8,4) NOT NULL,
  `y_pct` decimal(8,4) NOT NULL,
  `page` int(3) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo_posiciones`
--

INSERT INTO `modelo_posiciones` (`id`, `empresa_id`, `key_name`, `label`, `x_pct`, `y_pct`, `page`, `created_at`, `updated_at`) VALUES
(9, 8, 'clienteNombre', 'Nombre y Apellido', 51.0706, 36.2464, 1, '2025-12-01 04:07:49', '2025-12-01 04:07:49'),
(10, 8, 'clienteCuit', 'Cuit', 55.5316, 38.6819, 1, '2025-12-01 04:07:49', '2025-12-01 04:07:49');

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
(53, 10, 'factura', 13712930.00, '2025-10-22', 'Factura N° 00012-00160940', 107),
(54, 8, 'factura', 1932737.84, '2025-10-30', 'Factura N° 00012-00160941', 108),
(55, 11, 'factura', 467180.31, '2025-10-30', 'Factura N° 00012-00160941', 109),
(56, 11, 'factura', 119258.21, '2025-10-30', 'Factura N° 00012-00160942', 110),
(57, 11, 'factura', 116077.99, '2025-10-30', 'Factura N° 00012-00160942', 111),
(58, 11, 'pago', 119258.21, '2025-10-28', '', NULL),
(59, 11, 'factura', 17061.00, '2025-10-30', 'Factura N° 00012-00160943', 112),
(60, 12, 'factura', 2335901.55, '2025-10-30', 'Factura N° 00012-00160942', 113),
(61, 12, 'factura', 692883.98, '2025-10-30', 'Factura N° 00012-00160943', 114),
(62, 12, 'factura', 490284.74, '2025-10-30', 'Factura N° 00012-00160944', 115),
(63, 13, 'factura', 516089.20, '2025-10-30', 'Factura N° 00012-00160944', 116),
(64, 13, 'factura', 934360.84, '2025-10-30', 'Factura N° 00012-00160945', 117),
(65, 13, 'factura', 568700.00, '2025-10-30', 'Factura N° 00012-00160946', 118),
(66, 13, 'factura', 1208790.00, '2025-10-30', 'Factura N° 00012-00160947', 119),
(67, 13, 'factura', 490050.00, '2025-10-30', 'Factura N° 00012-00160948', 120),
(68, 13, 'factura', 423500.00, '2025-10-30', 'Factura N° 00012-00160949', 121),
(69, 13, 'factura', 692883.98, '2025-10-30', 'Factura N° 00012-00160950', 122),
(70, 13, 'factura', 368662.80, '2025-10-30', 'Factura N° 00012-00160951', 123),
(71, 13, 'factura', 424637.40, '2025-10-30', 'Factura N° 00012-00160952', 124),
(72, 13, 'factura', 191086.83, '2025-10-30', 'Factura N° 00012-00160953', 125),
(73, 14, 'factura', 382173.66, '2025-10-30', 'Factura N° 00012-00160953', 126),
(74, 14, 'factura', 829491.30, '2025-10-31', 'Factura N° 00012-00160954', 127),
(75, 14, 'factura', 2335901.55, '2025-10-31', 'Factura N° 00012-00160955', 128),
(76, 14, 'factura', 1058750.00, '2025-10-31', 'Factura N° 00012-00160956', 129),
(77, 14, 'factura', 980100.00, '2025-10-31', 'Factura N° 00012-00160957', 130),
(78, 14, 'factura', 2417580.00, '2025-10-31', 'Factura N° 00012-00160958', 131),
(79, 14, 'factura', 1132560.00, '2025-10-31', 'Factura N° 00012-00160959', 132),
(80, 14, 'factura', 1154806.64, '2025-10-31', 'Factura N° 00012-00160960', 133),
(81, 14, 'factura', 1419245.30, '2025-10-31', 'Factura N° 00012-00160961', 134),
(82, 14, 'factura', 127050.00, '2025-10-31', 'Factura N° 00012-00160962', 135),
(83, 6, 'pago', 4188632.80, '2025-11-04', '', NULL),
(84, 6, 'factura', 3519996.48, '2025-11-04', 'Factura N° 00012-00160964', 136),
(85, 6, 'factura', 1272087.52, '2025-11-04', 'Factura N° 00012-00160965', 137),
(86, 6, 'factura', 1932737.84, '2025-11-04', 'Factura N° 00012-00160964', 138),
(87, 6, 'factura', 127208.75, '2025-11-04', 'Factura N° 00012-00160965', 139),
(88, 6, 'factura', 119258.21, '2025-11-04', 'Factura N° 00012-00160965', 140),
(89, 6, 'factura', 159010.94, '2025-11-04', 'Factura N° 00012-00160965', 141),
(90, 6, 'factura', 159010.94, '2025-11-04', 'Factura N° 00012-00160965', 142),
(91, 6, 'factura', 715598.84, '2025-11-04', 'Factura N° 00012-00160965', 143),
(92, 6, 'factura', 2461256.09, '2025-11-04', 'Factura N° 00012-00160966', 144),
(93, 6, 'factura', 1770273.56, '2025-11-04', 'Factura N° 00012-00160967', 145),
(94, 6, 'factura', 2115297.80, '2025-10-01', 'Factura N° 00012-00008106', 146),
(95, 6, 'factura', 3448645.20, '2025-11-04', 'Factura N° 00012-00008108', 147),
(96, 6, 'factura', 6595039.66, '2025-11-04', 'Factura N° 00012-00008109', 148),
(97, 6, 'factura', 1374229.67, '2025-11-04', 'Factura N° 00012-00008110', 149),
(98, 6, 'factura', 1559489.14, '2025-11-04', 'Factura N° 00012-00008111', 150),
(99, 6, 'factura', 268349.57, '2025-11-04', 'Factura N° 00012-00008112', 151),
(100, 11, 'factura', 715598.84, '2025-11-07', 'Factura N° 00012-00008113', 152),
(101, 11, 'factura', 1401540.93, '2025-11-07', 'Factura N° 00012-00008114', 153),
(102, 11, 'factura', 692883.98, '2025-11-07', 'Factura N° 00012-00008115', 154),
(103, 11, 'factura', 503360.00, '2025-11-07', 'Factura N° 00012-00008116', 155),
(104, 11, 'factura', 692120.00, '2025-11-07', 'Factura N° 00012-00008116', 156),
(105, 15, 'factura', 3093088.76, '2025-11-08', 'Factura N° 00012-00008117', 157),
(106, 16, 'factura', 357799.42, '2025-11-13', 'Factura N° 00012-00008118', 158),
(107, 16, 'factura', 1242447.20, '2025-11-13', 'Factura N° 00012-00008119', 159),
(108, 16, 'factura', 268349.57, '2025-11-13', 'Factura N° 00012-00008120', 160),
(109, 16, 'factura', 214679.65, '2025-11-13', 'Factura N° 00012-00008121', 161),
(110, 16, 'factura', 1932737.84, '2025-11-13', 'Factura N° 00012-00008122', 162),
(111, 16, 'factura', 934360.62, '2025-11-13', 'Factura N° 00012-00008123', 163),
(112, 16, 'factura', 1815614.52, '2025-11-13', 'Factura N° 00012-00008124', 164),
(113, 16, 'factura', 1108481.00, '2025-11-13', 'Factura N° 00012-00008125', 165),
(114, 16, 'factura', 923845.31, '2025-11-13', 'Factura N° 00012-00008126', 166),
(116, 17, 'factura', 605204.84, '2025-11-14', 'Factura N° 00012-00008128', 168),
(124, 18, 'factura', 847025.00, '2025-11-20', 'Factura N° 00012-00008129', 176),
(125, 18, 'factura', 455000.00, '2025-11-20', 'Factura N° 00012-00008130', 177),
(126, 18, 'factura', 357799.42, '2025-11-20', 'Factura N° 00012-00008131', 178),
(127, 18, 'factura', 282661.54, '2025-11-20', 'Factura N° 00012-00008131', 179),
(128, 18, 'factura', 143119.77, '2025-11-20', 'Factura N° 00012-00008131', 180),
(129, 10, 'factura', 1932737.84, '2025-11-25', 'Factura N° 00012-00008132', 181);

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
(3, 'telefonia ', 'celulares', '2025-10-22 06:45:52'),
(4, 'respuesto de auto', 'respuesto de auto', '2025-11-14 04:49:08'),
(5, 'agro y yerba', 'agro y yerba', '2025-11-14 04:51:54'),
(6, 'gomeria', '', '2025-11-20 00:23:05');

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
-- Indices de la tabla `modelo_config`
--
ALTER TABLE `modelo_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `modelo_posiciones`
--
ALTER TABLE `modelo_posiciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalles`
--
ALTER TABLE `detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT de la tabla `modelo_config`
--
ALTER TABLE `modelo_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `modelo_posiciones`
--
ALTER TABLE `modelo_posiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `movimientos_cuenta_corriente`
--
ALTER TABLE `movimientos_cuenta_corriente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT de la tabla `pagos_facturas`
--
ALTER TABLE `pagos_facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Filtros para la tabla `modelo_posiciones`
--
ALTER TABLE `modelo_posiciones`
  ADD CONSTRAINT `fk_modelo_posiciones_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
