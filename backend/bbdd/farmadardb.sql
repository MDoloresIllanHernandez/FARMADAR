-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 02:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
('B30785629', 'Farmacia La Merced', 'Calle de Ceuta, 2, 30003 Murcia', '968239428', 'farmacialamerced@gmail.com', 1),
('B73669911', 'Farmacia Catedral', 'Calle Traperia, 1, 30001 Murcia', '968212829', 'catedral@gmail.com', 2),
('B30884526', 'Farmacia Vistabella', 'Plaza de los Patos, 5, 30003 Murcia', '968258523', 'vistabella@gmail.com', 3),
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
('651024', 1, 'SERTRALINA MABO 50 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG , 60 comprimidos', 5.84, 17),
('651024', 2, 'SERTRALINA MABO 50 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG , 60 comprimidos', 5.84, 0),
('651024', 3, 'SERTRALINA MABO 50 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG , 60 comprimidos', 5.84, 19),
('651024', 4, 'SERTRALINA MABO 50 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG , 60 comprimidos', 5.84, 0),
('656129', 1, 'PARACETAMOL CINFA 650 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG, 40 comprimidos', 1.31, 0),
('656129', 2, 'PARACETAMOL CINFA 650 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG, 40 comprimidos', 1.31, 20),
('656129', 3, 'PARACETAMOL CINFA 650 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG, 40 comprimidos', 1.31, 0),
('656129', 4, 'PARACETAMOL CINFA 650 mg COMPRIMIDOS RECUBIERTOS CON PELICULA EFG, 40 comprimidos', 1.31, 20),
('659730', 1, 'DIAZEPAN LEO 2 mg COMPRIMIDOS, 30 comprimidos', 2.50, 20),
('659730', 2, 'DIAZEPAN LEO 2 mg COMPRIMIDOS, 30 comprimidos', 2.50, 18),
('659730', 3, 'DIAZEPAN LEO 2 mg COMPRIMIDOS, 30 comprimidos', 2.50, 0),
('659730', 4, 'DIAZEPAN LEO 2 mg COMPRIMIDOS, 30 comprimidos', 2.50, 0),
('662879', 1, 'ENANTYUM 25 mg SOLUCIÓN ORAL, 20 sobres de 10 ml', 6.68, 0),
('662879', 2, 'ENANTYUM 25 mg SOLUCIÓN ORAL, 20 sobres de 10 ml', 6.68, 0),
('662879', 3, 'ENANTYUM 25 mg SOLUCIÓN ORAL, 20 sobres de 10 ml', 6.68, 20),
('662879', 4, 'ENANTYUM 25 mg SOLUCIÓN ORAL, 20 sobres de 10 ml', 6.68, 19);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_prod` varchar(50) NOT NULL,
  `id_farm` int(11) NOT NULL,
  `farm_origen` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `otros_datos` varchar(255) NOT NULL,
  `estado` enum('Pendiente','Confirmada','Cancelada','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `id_prod`, `id_farm`, `farm_origen`, `fecha`, `hora_inicio`, `hora_fin`, `cantidad`, `nombre`, `otros_datos`, `estado`) VALUES
(61, '656129', 2, 1, '2024-12-04', '12:20:00', '14:21:00', 2, 'María González ', 'Teléfono- 654123456', 'Cancelada'),
(62, '659730', 2, 1, '2024-12-04', '14:28:00', '16:28:00', 2, 'Carlos Pérez ', 'Teléfono:  600456789', 'Confirmada'),
(63, '662879', 4, 2, '2024-12-04', '14:33:00', '16:33:00', 1, 'Ana Martínez', ' 612789123, ana.martinez@gmail.com', 'Confirmada'),
(64, '651024', 1, 2, '2024-12-06', '14:34:00', '16:34:00', 3, 'Javier Sánchez', 'Teléfono  625654987', 'Confirmada'),
(65, '651024', 3, 1, '2024-12-06', '14:36:00', '16:36:00', 1, 'Elena Ramírez', 'elena.ramirez@gmail.com', 'Confirmada'),
(66, '656129', 2, 3, '2024-12-06', '14:38:00', '16:38:00', 2, 'Miguel Torres ', '622876543', 'Cancelada'),
(67, '651024', 3, 4, '2024-12-04', '14:40:00', '16:40:00', 1, 'Luis Fernández', 'Tlfn. 612789123', 'Cancelada');

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
(100, 0, 'superadmin', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMjAwNDQsImRhdGEiOnsiaWQiOiIxMDAiLCJub21icmUiOiJTXHUwMGZhcGVyIEFkbWluaXN0cmFkb3IifX0.1CDe5YKN85oNC4WtakzpmnZGVcWFobvzOY5M7kdzIVM', 'Súper Administrador', 'superadmin'),
(200, 1, 'adminmerced', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMTkzNjEsImRhdGEiOnsiaWQiOiIyMDAiLCJub21icmUiOiJGYXJtYWNcdTAwZTl1dGljbyBNZXJjZWQifX0.G1JSW4ET1Bfu6TLqW-zVKsnoYWwtdYJOqGzgwBqpCho', 'Administrador Merced', 'admin'),
(201, 1, 'usumerced', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMyNDQzMzQsImRhdGEiOnsiaWQiOiIyMDEiLCJub21icmUiOiJEZXBlbmRpZW50ZSBNZXJjZWQifX0.LYMlBBULgK_GHhIrjuP2dnU0ubz5FzjQilLM923pvbI', 'Auxiliar Merced', 'usu'),
(207, 2, 'admincatedral', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMTk3MDEsImRhdGEiOnsiaWQiOiIyMDciLCJub21icmUiOiJGYXJtYWNcdTAwZTl1dGljbyBDYXRlZHJhbCJ9fQ.CWosfFWXi4N8kk6ya4JzBa5S8UaVcz8F8EDK_lYJvbk', 'Administrador Catedral', 'admin'),
(208, 2, 'usucatedral', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzE2ODYyMzgsImRhdGEiOnsidXNlcm5hbWUiOiJ1c3VjYXRlZHJhbCIsIm5vbWJyZSI6IkRlcGVuZGllbnRlIENhdGVkcmFsIn19.EgTpA1XiuRv9hywoNNfxZFtk0N41ywhmW53TOPPzsfM', 'Auxiliar Catedral', 'usu'),
(209, 3, 'adminvistabella', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMTk4NTcsImRhdGEiOnsiaWQiOiIyMDkiLCJub21icmUiOiJBZG1pbmlzdHJhZG9yIFZpc3RhYmVsbGEifX0.JyRIajWutjfS9DC-gd3I0VOc1AJe_4T7XszDwMVtaBM', 'Administrador Vistabella', 'admin'),
(210, 3, 'usuvistabella', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMDcyNDcsImRhdGEiOnsidXNlcm5hbWUiOiJ1c3V2aXN0YWJlbGxhIiwibm9tYnJlIjoiQXV4aWxpYXIgVmlzdGFiZWxsYSJ9fQ.hJ4cxI37gejP8PrLWrGreWNpdukG-xj07XIUwIxf9N0', 'Auxiliar Vistabella', 'usu'),
(211, 4, 'adminpuxmarina', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMTk1NjcsImRhdGEiOnsiaWQiOiIyMTEiLCJub21icmUiOiJGYXJtYWNcdTAwZTl1dGljbyBQdXhtYXJpbmEifX0.sQGMFvfXURtMv_q8S7v1tkuNHpzNZblGL4lKG7pebEM', 'Administrador Puxmarina', 'admin'),
(212, 4, 'usupuxmarina', '5003f9b63ba86a823794c26e0b6ba90be1340968091fa40b5e0ff1c729ed4637', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzMzMDc2NzMsImRhdGEiOnsiaWQiOiIyMTIiLCJub21icmUiOiJBdXhpbGlhciBQdXhtYXJpbmEifX0.JH9kFj3enZGw0cGHSx8hF2OJujVIo6BI7B8mb6DnowM', 'Auxiliar Puxmarina', 'usu');

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
  ADD KEY `fk_reservas_productos` (`id_prod`,`id_farm`),
  ADD KEY `fk_farm_origen` (`farm_origen`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmacias`
--
ALTER TABLE `farmacias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

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
  ADD CONSTRAINT `fk_farm_origen` FOREIGN KEY (`farm_origen`) REFERENCES `farmacias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservas_productos` FOREIGN KEY (`id_prod`,`id_farm`) REFERENCES `productos` (`id`, `id_farm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
