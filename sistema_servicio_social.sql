-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2026 at 09:05 PM
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
-- Database: `sistema_servicio_social`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `no_control` varchar(20) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `carrera` varchar(150) NOT NULL,
  `periodo_actual` varchar(50) DEFAULT 'Ago-Dic 2023',
  `porcentaje_creditos` decimal(5,2) NOT NULL,
  `horas_completadas` int(11) DEFAULT 0,
  `estado_servicio` enum('Sin Iniciar','En curso','Liberado','Interrumpido') DEFAULT 'Sin Iniciar',
  `notificacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `id_usuario`, `no_control`, `nombre_completo`, `carrera`, `periodo_actual`, `porcentaje_creditos`, `horas_completadas`, `estado_servicio`, `notificacion`) VALUES
(1, 3, '22560001', 'Juan Carlos Pérez López', 'Ing. en Sistemas Computacionales', 'Ene-Jun 2026', 75.50, 500, 'En curso', NULL),
(2, 4, '23390085', 'Saul Misael Colli Kumul', 'Ingeniería en Sistemas Computacionales', 'Ene-Jun 2026', 100.00, 0, 'Sin Iniciar', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id_asignacion` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `estado_asignacion` enum('Pendiente','Activo','Concluido','Baja') DEFAULT 'Pendiente',
  `motivo_baja` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asignaciones`
--

INSERT INTO `asignaciones` (`id_asignacion`, `id_alumno`, `id_programa`, `fecha_asignacion`, `estado_asignacion`) VALUES
(1, 1, 1, '2025-08-15', 'Activo');

-- --------------------------------------------------------

--
-- Table structure for table `ciclos`
--

CREATE TABLE `ciclos` (
  `id_ciclo` int(11) NOT NULL,
  `nombre_ciclo` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ciclos`
--

INSERT INTO `ciclos` (`id_ciclo`, `nombre_ciclo`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 'Enero - Junio 2026', '2026-01-01', '2026-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `ciclos_programas`
--

CREATE TABLE `ciclos_programas` (
  `id_programa` int(11) NOT NULL,
  `id_ciclo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ciclos_programas`
--

INSERT INTO `ciclos_programas` (`id_programa`, `id_ciclo`) VALUES
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dependencias`
--

CREATE TABLE `dependencias` (
  `id_dependencia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_organizacion` varchar(150) NOT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dependencias`
--

INSERT INTO `dependencias` (`id_dependencia`, `id_usuario`, `nombre_organizacion`, `direccion`) VALUES
(1, 1, 'CONAFOR - Comisión Nacional Forestal', 'Av. Insurgentes Sur 123, Chetumal, Q. Roo');

-- --------------------------------------------------------

--
-- Table structure for table `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `tipo_documento` varchar(100) NOT NULL,
  `nombre_archivo_original` varchar(255) NOT NULL,
  `ruta_servidor` varchar(255) NOT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp(),
  `estado_validacion` enum('Falta Subir','Nuevo','En Revisión','Corregido','Aprobado','Rechazado') DEFAULT 'Nuevo',
  `comentarios_dgtyv` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documentos`
--

INSERT INTO `documentos` (`id_documento`, `id_alumno`, `tipo_documento`, `nombre_archivo_original`, `ruta_servidor`, `fecha_subida`, `estado_validacion`, `comentarios_dgtyv`) VALUES
(1, 1, 'Carta de Aceptación', 'UNIDAD 3_Programacion. Equipo Colli, Medina, Puc, Tun.pdf', '/uploads/documentos/1779817539_3791_UNIDAD_3_Programacion._Equipo_Colli__Medina__Puc__Tun.pdf', '2026-05-26 12:45:39', 'Aprobado', 'Todo bien');

-- --------------------------------------------------------

--
-- Table structure for table `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id_evaluacion` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `tipo_evaluacion` varchar(100) NOT NULL,
  `nivel_desempeno` enum('Excelente','Notable','Bueno','Suficiente','Insuficiente') NOT NULL,
  `comentarios_supervisor` text DEFAULT NULL,
  `fecha_evaluacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `horarios_programas`
--

CREATE TABLE `horarios_programas` (
  `id_horario` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `horarios_programas`
--

INSERT INTO `horarios_programas` (`id_horario`, `id_programa`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(2, 4, 'Lunes', '12:01:00', '20:01:00'),
(3, 4, 'Martes', '12:01:00', '20:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `folio_programa` varchar(50) NOT NULL,
  `nombre_programa` varchar(150) NOT NULL,
  `modalidad` enum('Presencial','Virtual','Híbrida') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `perfiles_requeridos` text DEFAULT NULL,
  `cupos_totales` int(11) NOT NULL,
  `cupos_ocupados` int(11) DEFAULT 0,
  `ubicacion` varchar(150) DEFAULT NULL,
  `responsable_nombre` varchar(100) NOT NULL,
  `responsable_contacto` varchar(150) DEFAULT NULL,
  `estado_aprobacion` enum('En Revisión por DGTyV','Aprobado','Rechazado') DEFAULT 'En Revisión por DGTyV',
  `fecha_envio` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programas`
--

INSERT INTO `programas` (`id_programa`, `id_dependencia`, `folio_programa`, `nombre_programa`, `modalidad`, `descripcion`, `perfiles_requeridos`, `cupos_totales`, `cupos_ocupados`, `ubicacion`, `responsable_nombre`, `responsable_contacto`, `estado_aprobacion`, `fecha_envio`) VALUES
(1, 1, 'SS-2026-001', 'Reforestación y Cuidado Ambiental', 'Presencial', 'Apoyo en actividades de reforestación en zonas de selva baja en Othón P. Blanco.', 'Biología, Arquitectura, Ingeniería Civil', 10, 0, NULL, 'Ing. Carlos Mendoza', 'cmendoza@conafor.gob.mx', 'Aprobado', '2026-05-25'),
(2, 1, 'SS-2026-002', 'Desarrollo de Sistema de Inventario', 'Virtual', 'Creación de software web para control de inventario de flora rescatada.', 'Ingeniería en Sistemas Computacionales, Informática', 3, 0, NULL, 'Lic. Ana Torres', 'atorres@conafor.gob.mx', 'Aprobado', '2026-05-25'),
(3, 1, 'SS-2026-003', 'Campaña de Concientización Digital', 'Híbrida', 'Diseño multimedia para redes sociales fomentando la prevención de incendios.', 'Lic. en Administración, Arquitectura', 2, 0, NULL, 'Mtro. Roberto Ruiz', 'rruiz@conafor.gob.mx', 'Aprobado', '2026-05-25'),
(4, 1, 'PRG-05B9B8', 'Test', 'Presencial', 'Pruebitassss', 'Ser chingón en todo', 15, 0, 'Mi casa jajakla', 'Saulini', 'saulmisaelcolli@gmail.com', 'Aprobado', '2026-05-27');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('Alumno','DGTyV','Dependencia') NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `correo`, `password_hash`, `rol`, `fecha_creacion`) VALUES
(1, 'contacto@conafor.gob.mx', '$2y$10$eYbjZ7HywQhM17XjczG57eqqk6UZdvFSshJND20PMRAhQAR6ad5s.', 'Dependencia', '2026-05-25 19:42:21'),
(2, 'admin@itch.edu.mx', '$2y$10$58Vc/gWyrxD0GD/.MErrbuxQMduhsnMypeyhKjq27F.v6GbnDLkA6', 'DGTyV', '2026-05-25 19:42:36'),
(3, 'alumno@itch.edu.mx', '$2y$10$/Brb8VFLzdwrSHnKkp.hC.NcbBqoMt5BtEsiZ5eEF95o/SIKvy8le', 'Alumno', '2026-05-26 17:26:40'),
(4, 'saul@gmail.com', '$2y$10$JwDAqZK4bSLk2dkK4.6UQexAsh1lCy/rl8ZjjehImfUEnT8P/lAay', 'Alumno', '2026-05-27 18:19:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `no_control` (`no_control`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_no_control` (`no_control`);

--
-- Indexes for table `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indexes for table `ciclos`
--
ALTER TABLE `ciclos`
  ADD PRIMARY KEY (`id_ciclo`);

--
-- Indexes for table `ciclos_programas`
--
ALTER TABLE `ciclos_programas`
  ADD PRIMARY KEY (`id_programa`,`id_ciclo`),
  ADD KEY `id_ciclo` (`id_ciclo`);

--
-- Indexes for table `dependencias`
--
ALTER TABLE `dependencias`
  ADD PRIMARY KEY (`id_dependencia`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `idx_estado_doc` (`estado_validacion`);

--
-- Indexes for table `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `id_alumno` (`id_alumno`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indexes for table `horarios_programas`
--
ALTER TABLE `horarios_programas`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indexes for table `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`),
  ADD UNIQUE KEY `folio_programa` (`folio_programa`),
  ADD KEY `id_dependencia` (`id_dependencia`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ciclos`
--
ALTER TABLE `ciclos`
  MODIFY `id_ciclo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dependencias`
--
ALTER TABLE `dependencias`
  MODIFY `id_dependencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `horarios_programas`
--
ALTER TABLE `horarios_programas`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE;

--
-- Constraints for table `ciclos_programas`
--
ALTER TABLE `ciclos_programas`
  ADD CONSTRAINT `ciclos_programas_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE,
  ADD CONSTRAINT `ciclos_programas_ibfk_2` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos` (`id_ciclo`) ON DELETE CASCADE;

--
-- Constraints for table `dependencias`
--
ALTER TABLE `dependencias`
  ADD CONSTRAINT `dependencias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE;

--
-- Constraints for table `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluaciones_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE;

--
-- Constraints for table `horarios_programas`
--
ALTER TABLE `horarios_programas`
  ADD CONSTRAINT `horarios_programas_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE;

--
-- Constraints for table `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `programas_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id_dependencia`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
