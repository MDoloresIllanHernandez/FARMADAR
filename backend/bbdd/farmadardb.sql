-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2024 a las 16:59:24
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
-- Database: `farmadardb`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmacias`
--

CREATE TABLE `farmacias` (
  `cif` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmacias`
--

INSERT INTO `farmacias` (`cif`, `nombre`, `direccion`, `telefono`, `email`, `id`) VALUES
('B30785629', 'Farmacia La Merced', 'Calle Ceuta, 2, 30003 Murcia', '968239428', 'lamerced@gmail.com', 1),
('B73669911', 'Farmacia Catedral', 'Calle Traperia, 1, 30001 Murcia', '968212829', 'catedral@gmail.com', 2),
('B30884526', 'Farmacia Vistabella', 'Plz. de los Patos, 5, 30003 Murcia', '968258523', 'vistabella@gmail.com', 3),
('B74962244', 'Farmacia Puxmarina', 'Plaza Puxmarina, 5, 30004 Murcia', '968213226', 'puxmarina@gmail.com', 4);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` varchar(50) NOT NULL,
  `id_farm` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` float(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `id_farm`, `nombre`, `precio`, `stock`) VALUES
('300000', 1, 'Cañita', 4.00, 5),
('30001234', 1, 'Paracetamol', 2.36, 5),
('30001234', 3, 'Paracetamol', 1.50, 20),
('30004747', 2, 'Amoxicilina', 4.50, 5),
('30005678', 2, 'Aspirina', 3.50, 9),
('30005678', 4, 'Aspirina', 3.40, 7),
('30007732', 3, 'Ibuprofeno', 4.70, 20);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_prod` varchar(50) NOT NULL,
  `id_farm` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `otros_datos` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','pagada','cancelada','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `id_prod`, `id_farm`, `fecha`, `hora_inicio`, `hora_fin`, `cantidad`, `nombre`, `otros_datos`, `estado`) VALUES
(1, '30001234', 1, '2024-11-10', '10:00:00', '12:00:00', 5, 'Pepito', 'Teléfono: 666 666 666', 'pendiente');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `id_farm` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `role` enum('superadmin','admin','usu','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `id_farm`, `username`, `password`, `token`, `nombre`, `role`) VALUES
(100, 0, 'prueba', '655e786674d9d3e77bc05ed1de37b4b6bc89f788829f9f3c679e7687b410c89b', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODYxMzUsImRhdGEiOnsiaWQiOiIxMDAiLCJub21icmUiOiJVc3VhcmlvIGRlIFBydWViYSJ9fQ.uT8KSH738vgTHpw1_sXWl4Er_-U6s2rSyjQzq4j4CYU', 'Usuario de Prueba', 'superadmin'),
(200, 1, 'adminmerced', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODU4MTYsImRhdGEiOnsiaWQiOiIyMDAiLCJub21icmUiOiJGYXJtYWNcdTAwZTl1dGljbyBNZXJjZWQifX0.zD797QVRIoUzW8QFK-7TgjTdG8aRfrb4ayZfaBqluJg', 'Farmacéutico Merced', 'admin'),
(201, 1, 'usumerced', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODU4NzgsImRhdGEiOnsiaWQiOiIyMDEiLCJub21icmUiOiJEZXBlbmRpZW50ZSBNZXJjZWQifX0.1YUqUs15BHjHPAMw-6F2QcDzbMcBlKbq7r3YXMLFbQ4', 'Dependiente Merced', 'usu'),
(207, 2, 'admincatedral', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODYyMTMsImRhdGEiOnsidXNlcm5hbWUiOiJhZG1pbmNhdGVkcmFsIiwibm9tYnJlIjoiRmFybWFjXHUwMGU5dXRpY28gQ2F0ZWRyYWwifX0.wfcbjFSUjPzMmKiXKOpZATnI-REK7Uk_iYTUDhb', 'Farmacéutico Catedral', 'admin'),
(208, 2, 'usucatedral', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODYyMzgsImRhdGEiOnsidXNlcm5hbWUiOiJ1c3VjYXRlZHJhbCIsIm5vbWJyZSI6IkRlcGVuZGllbnRlIENhdGVkcmFsIn19.EgTpA1XiuRv9hywoNNfxZFtk0N41ywhmW53TOPPzsfM', 'Dependiente Catedral', 'usu');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmacias`
--
ALTER TABLE `farmacias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`,`id_farm`),
  ADD KEY `id_farm` (`id_farm`) USING BTREE,
  ADD KEY `idx_productos_id_idfarm` (`id`,`id_farm`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservas_productos` (`id_prod`,`id_farm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmacias`
--
ALTER TABLE `farmacias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_farm`) REFERENCES `farmacias` (`id`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_productos` FOREIGN KEY (`id_prod`,`id_farm`) REFERENCES `productos` (`id`, `id_farm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
