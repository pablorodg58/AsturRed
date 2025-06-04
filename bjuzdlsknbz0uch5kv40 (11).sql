-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: bjuzdlsknbz0uch5kv40-mysql.services.clever-cloud.com:3306
-- Tiempo de generación: 21-05-2025 a las 14:51:57
-- Versión del servidor: 8.0.22-13
-- Versión de PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bjuzdlsknbz0uch5kv40`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blogs`
--

CREATE TABLE `blogs` (
  `id` int NOT NULL,
  `pueblo` varchar(100) NOT NULL,
  `blog_index` tinyint NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `imagen_cabecera` varchar(255) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `businesses`
--

CREATE TABLE `businesses` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `description` text,
  `profile_pic` varchar(255) DEFAULT 'default-profile.jpg',
  `banner_pic` varchar(255) DEFAULT 'default-banner.jpg',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('ayuntamiento','negocio') DEFAULT 'negocio',
  `tipo_negocio` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `businesses`
--

INSERT INTO `businesses` (`id`, `username`, `password`, `business_name`, `email`, `address`, `location`, `phone`, `description`, `profile_pic`, `banner_pic`, `created_at`, `role`, `tipo_negocio`, `latitude`, `longitude`) VALUES
(2, 'La Terraza', '$2y$10$.akYuRKY9Q653z/6nlvu1ursEKTUZgX50PKbJk4Vk9VsS8eWnU3me', 'La Terraza', 'LaTerraza@gmail.com', 'C. Amor de Dios, 2, 33740 Tapia de Casariego, Asturias', 'Tapia', '123456789', 'Un Restaurante para comer comida asturiana en familia', 'default-profile.jpg', 'default-banner.jpg', '2025-03-11 19:06:57', 'negocio', 'Restaurante', 43.57010988, -6.94448805),
(4, 'Tienduca', '$2y$10$kzify8YzFmCOLx4W5EaukOVskYd13/8idoWmcgIH3VqD7KLnFa/bC', 'Tienduca', 'Tienduca@gmail', 'Vegadeo', 'Vegadeo', '123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-11 19:43:33', 'negocio', 'Tienda', NULL, NULL),
(5, 'Ayuntamiento de Tapia', '$2y$10$ntBcwC3/fUZc6qyukWjDueQ600CyE3pe4F8dXFTKRsxXZ8NVzlkVS', 'Ayuntamiento de Tapia', 'AyuntamientoTapia@gmail.com', 'Tapia', NULL, '123456789', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-14 19:07:23', 'ayuntamiento', 'Ayuntamiento', NULL, NULL),
(6, 'Ayuntamiento de Navia', '$2y$10$KsO6lQA3EzetrMl79qZK5.SXhlTTiIjy/M/uJCIBuq8gncUFnAKia', 'Ayuntamiento de Navia', 'AyuntamientoNavia@gmail.com', 'Navia', NULL, '123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-17 17:56:39', 'ayuntamiento', 'Ayuntamiento', NULL, NULL),
(7, 'Ayuntamiento de Castropol', '$2y$10$tH1R0mcy6XR6jjT1fa6fe.jTZ8cS8LdpKZxQ0acADGn9c4w32w65m', 'Ayuntamiento de Castropol', 'AyuntamientoCastropol@gmail.com', 'Castropol', NULL, '123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-17 17:57:33', 'ayuntamiento', 'Ayuntamiento', NULL, NULL),
(8, 'Ayuntamiento de Vegadeo', '$2y$10$0/wPWXDdaS7WDZalf6c8luRow7ZgZ2ycK3jM3pSuUutqeFFYoKSA6', 'Ayuntamiento de Vegadeo', 'AyuntamientoVegadeo@gmail.com', 'Vegadeo', NULL, '123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-17 17:58:16', 'ayuntamiento', 'Ayuntamiento', NULL, NULL),
(9, 'Ayuntamiento de Taramundi', '$2y$10$ifJKxhIC1ULomCH4TsKFV.wZHvmWPf8dyPW.0TBh2uHz8EdnejpPO', 'Ayuntamiento de Taramundi', 'AyuntamientoTaramundi@gmail.com', 'Taramundi', NULL, '123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-17 17:59:18', 'ayuntamiento', 'Ayuntamiento', NULL, NULL),
(10, 'Kartodromo de Tapia', '$2y$10$vFlUHjLJFQUdmgrL.cPNROisLKdzoSjzZVT4mt5PtFI/fWvOVGYgy', 'Kartodromo de Tapia', 'kartodromoTapia@gmail.com', 'Carretera TC-2 Salave La Roda P.K. 1.2. Tapia de Casariego ASTURIAS', 'Tapia', '123456789', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-18 08:40:13', 'negocio', 'Karting', NULL, NULL),
(12, 'Museo de la Cuchillería', '$2y$10$GuaHNcq4jielz1Tq9yq70OJAv/77T/Y6aGo4Sv6Uc583RL09Ylhza', 'Museo de la Cuchillería', 'MuseoCuchillosTaramundi@gmail.com', 'Pardiñas, s/n, 33775 Taramundi, Asturias', 'Taramundi', '123456789', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-18 18:11:59', 'negocio', 'Museo', NULL, NULL),
(13, 'Casa Vicente', '$2y$10$i/7iB1Kn6TCEjfA5nRM4Re3goC32A2GLAIpKPDCUysfFPt9vY8EzC', 'Casa Vicente', 'casaVicente@gmail.com', 'Av. Galicia, 33760 Castropol, Asturias', 'Castropol', '987654321', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-19 10:03:32', 'negocio', 'Restaurante', NULL, NULL),
(14, 'Hípica La Frontera', '$2y$10$jpMPBCqqmRg2gd2gGPzTDuRjTbrXxrCN2zQEjTY3Jxa40QLnn5R.q', 'Hípica La Frontera', 'HipicaFrontera@gmail.com', 'LA SENRA DEL VALIN, S/N, 33769 El Valin, Asturias', 'Castropol', '555666777', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-19 10:23:05', 'negocio', 'Hipica', NULL, NULL),
(15, 'Heladería Ferrera', '$2y$10$4tWINqQISSpXgMo.EuFfv.utOLreGCqKUsnJ5sGDCHABueslusxQq', 'Heladería Ferrera', 'HeladeriaFerrera@gmail.com', 'C. Marqués de Casariego, 8, 33740 Tapia de Casariego, Asturias', 'Tapia', '333222111', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:28:24', 'negocio', 'Heladería', NULL, NULL),
(16, 'Ludoteca Chiribitas', '$2y$10$lHSK55v2VRzb2zLrFAoytecgzbG7LmHpRF0bwLxPBk/aOCXGaotwa', 'Ludoteca Chiribitas', 'LudotecaChiribitas@gmail.com', 'Calle Arquitecto, C. Francisco González Villamil, nº 22, 33740 Tapia de Casariego, Asturias', 'Tapia', '987656789', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:32:22', 'negocio', 'Ludoteca', NULL, NULL),
(17, 'El Puerto', '$2y$10$s4xrKqSSptJSFBWX5RiNdOYxPYUlt/.daj0dqmjEUh5JLzbX6wkk.', 'El Puerto', 'ElPuerto@gmail.com', 'Av. del Muelle, 20, 33740 Tapia de Casariego, Asturias', 'Tapia', '456789123', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:33:52', 'negocio', 'Restaurante', NULL, NULL),
(18, 'Panaderia Suso', '$2y$10$sWvHjFg2wYIjT7.NrAUZseXkjUqOXpVaFbJfQTWwDYcHUUbFeN/SW', 'Panaderia Suso', 'PanSuso@gmail.com', 'C. Peñón, 0, 33740 Tapia de Casariego, Asturias', 'Tapia', '98897667', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:35:01', 'negocio', 'Panadería', NULL, NULL),
(19, 'Cafe Bar La Plaza', '$2y$10$oT5h8LKorwcpof6Mvb/JAuCnhteIis92W.m4zIB6Lz4WQpCUEkP.O', 'Cafe Bar La Plaza', 'CafeBLP@gmail.com', 'Pl. del Campogrande, 33740 Tapia de Casariego, Asturias', 'Tapia', '009988776', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:36:30', 'negocio', 'Cafeteria', NULL, NULL),
(20, 'El Bodegón', '$2y$10$/Y004R/vnpSDxoqsbCG93evXBYEs9SGnXHK16xjFkxdkMuiPRq0.2', 'El Bodegón', 'Bodegon23@gmail.com', 'C. Amor de Dios, 8, 33740 Tapia de Casariego, Asturias', 'Tapia', '991122880', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:37:52', 'negocio', 'Bar', NULL, NULL),
(21, 'Apartamentos Turísticos Playa de Tapia', '$2y$10$iBAw7BI8uu/BytO6TMCGjO2mSXT.6ldukz8qrfTcYy8l0ZwJtzylK', 'Apartamentos Turísticos Playa de Tapia', 'ATPT@gmail.com', 'La Seara, 9, 33740 Tapia de Casariego, Asturias', 'Tapia', '765432109', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:39:35', 'negocio', 'Hotel', NULL, NULL),
(22, 'Cafetería Margis', '$2y$10$PEvMki1puG7898SV3Jq3guMVNT7GGel3wgkp05Wkx6IcsvBwMhza.', 'Cafetería Margis', 'CafeteriaMargis@gmail.com', 'Av. del Casino, 8, 33790 Puerto de Vega, Asturias', 'Puerto de Vega', '333555777', '', 'default-profile.jpg', 'default-banner.jpg', '2025-03-22 17:56:36', 'negocio', 'Cafeteria', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `business_gallery`
--

CREATE TABLE `business_gallery` (
  `id` int NOT NULL,
  `business_username` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `business_gallery`
--

INSERT INTO `business_gallery` (`id`, `business_username`, `image_path`, `uploaded_at`) VALUES
(6, 'la Terraza', 'gallery_la Terraza_1741801477_0.jpg', '2025-03-12 17:44:36'),
(7, 'la Terraza', 'gallery_la Terraza_1741801477_1.jpg', '2025-03-12 17:44:36'),
(8, 'la Terraza', 'gallery_la Terraza_1741801477_2.jpg', '2025-03-12 17:44:37'),
(9, 'la Terraza', 'gallery_la Terraza_1741801478_3.jpg', '2025-03-12 17:44:38'),
(10, 'La Terraza', 'gallery_La Terraza_1742837081_0.jpg', '2025-03-24 17:24:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `location`, `created_by`, `created_at`) VALUES
(6, 'Fiesta del Primer Día de Verano', 'Da la bienvenida al verano en Tapia. Podrás encontrar puestos artesanales y de comida, juegos y actividades', '2025-06-21 12:00:00', 'Plaza Constitución, número 1, 33740 Tapia de Casariego, Asturias', 'ayuntamiento de Tapia', '2025-05-15 06:44:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id`, `username`, `business_name`, `review_text`, `rating`, `created_at`, `image_path`) VALUES
(1, 'Pablo', 'La Terraza', 'Gran sitio para comer', 5, '2025-03-14 16:39:54', NULL),
(3, 'Pablo1', 'La Terraza', 'Gran Servicio', 4, '2025-03-14 16:47:15', NULL),
(4, 'Alberto', 'La Terraza', 'Muy amables', 4, '2025-03-14 17:35:29', 'review_Alberto_1741973729.jpg'),
(5, 'Nacho', 'Kartodromo de Tapia', 'La pista estaba muy sucia', 4, '2025-03-21 10:56:26', 'review_Nacho_1742554585.jpg'),
(6, 'Pablo', 'Casa Vicente', 'Un poco caro', 3, '2025-03-22 15:15:51', 'review_Pablo_1742656549.jpg'),
(7, 'Pablo', 'Apartamentos Turísticos Playa de Tapia', 'Muy comodos, mucha tranquilidad', 4, '2025-03-22 17:49:09', NULL),
(8, 'Pablo', 'Cafetería Margis', 'Un sitio comodo donde tomar un café', 4, '2025-05-13 15:33:21', NULL),
(9, 'Pablo', 'Ludoteca Chiribitas', 'buen lugar', 4, '2025-05-14 18:04:54', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-profile.jpg',
  `banner_pic` varchar(255) DEFAULT 'default-banner.jpg',
  `description` text,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `profile_pic`, `banner_pic`, `description`, `location`) VALUES
(4, 'Pablo', '$2y$10$M9gojB6Puw1v1TgjONTrkuJXZkLS1P.Ys1WGoHn8eV19R8NYd4Nw2', 'profile_Pablo1_1741635399.png', 'banner_Pablo1_1741636647.jpg', 'Un chico aventurero ', 'El Berrón'),
(6, 'Alberto', '$2y$10$hXU/CsIndknFXG8K8jku9O7nY0kWPEn4K5uVf0ZRBOwZzB.KMqm0W', 'default-profile.jpg', 'default-banner.jpg', 'De Proaza', 'Proaza'),
(9, 'monica', '$2y$10$yntGNtloALSSFYfChpPdj.5HrX/017NC6R0EIXPKRRyst.QGruK1m', 'default-profile.jpg', 'default-banner.jpg', '', ''),
(10, 'monica alonso', '$2y$10$5XWdRNQrz6GINzW5oSeSt.RRvMgIPHapw5BQkEF4c.DGGPB.WhLke', 'default-profile.jpg', 'default-banner.jpg', '', ''),
(12, 'Nacho', '$2y$10$XW2qsU5eQD2ty3iZVMQlBO6QCbZfm8WYZL.DWRxU1A0Z7Sh1Ychz.', 'profile_nacho_1741637457.jpg', 'banner_nacho_1741637524.jpg', '', 'El Berrón'),
(29, 'angeles', '$2y$10$bwthU.qe15KOLb19WvHbBuZdiVxwmUF2UE1Jq2DjZwz8TmH0O4qMe', 'default-profile.jpg', 'default-banner.jpg', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_pueblo_index` (`pueblo`,`blog_index`);

--
-- Indices de la tabla `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `business_gallery`
--
ALTER TABLE `business_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_username` (`business_username`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `business_gallery`
--
ALTER TABLE `business_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `business_gallery`
--
ALTER TABLE `business_gallery`
  ADD CONSTRAINT `business_gallery_ibfk_1` FOREIGN KEY (`business_username`) REFERENCES `businesses` (`username`) ON DELETE CASCADE;

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `businesses` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
