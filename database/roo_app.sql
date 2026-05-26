-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 11:17 AM
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
-- Database: `roo_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `id` int(10) UNSIGNED NOT NULL,
  `city` varchar(100) NOT NULL,
  `name` varchar(180) NOT NULL,
  `accommodation_type` enum('hotel_motel','hostel_apartment') NOT NULL,
  `max_price_per_night` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accommodations`
--

INSERT INTO `accommodations` (`id`, `city`, `name`, `accommodation_type`, `max_price_per_night`, `image`, `description`, `created_at`) VALUES
(1, 'Zagreb', 'Admiral Hotel', 'hotel_motel', 140, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Zagrebu.', '2026-04-29 10:47:10'),
(2, 'Zagreb', 'Amadria Park Hotel Capital', 'hotel_motel', 210, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u centru Zagreba.', '2026-04-29 10:47:10'),
(3, 'Zagreb', 'Best Western Premier Hotel Astoria', 'hotel_motel', 150, 'media/slike/smjestaj-placeholder.jpg', 'Poznati gradski hotel.', '2026-04-29 10:47:10'),
(4, 'Zagreb', 'Canopy by Hilton Zagreb City Centre', 'hotel_motel', 180, 'media/slike/smjestaj-placeholder.jpg', 'Moderan hotel u centru.', '2026-04-29 10:47:10'),
(5, 'Zagreb', 'DoubleTree by Hilton Zagreb', 'hotel_motel', 170, 'media/slike/smjestaj-placeholder.jpg', 'Hotel poslovnog tipa.', '2026-04-29 10:47:10'),
(6, 'Zagreb', 'Hotel Dubrovnik Zagreb', 'hotel_motel', 170, 'media/slike/smjestaj-placeholder.jpg', 'Hotel na glavnom trgu.', '2026-04-29 10:47:10'),
(7, 'Zagreb', 'Hotel International', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Hotel blizu poslovne zone.', '2026-04-29 10:47:10'),
(8, 'Zagreb', 'Hotel Jadran', 'hotel_motel', 110, 'media/slike/smjestaj-placeholder.jpg', 'Jednostavan gradski hotel.', '2026-04-29 10:47:10'),
(9, 'Zagreb', 'Hotel Central Zagreb', 'hotel_motel', 100, 'media/slike/smjestaj-placeholder.jpg', 'Hotel blizu kolodvora.', '2026-04-29 10:47:10'),
(10, 'Zagreb', 'Art Hotel Like', 'hotel_motel', 95, 'media/slike/smjestaj-placeholder.jpg', 'Manji gradski hotel.', '2026-04-29 10:47:10'),
(11, 'Zagreb', 'Chillout Hostel Zagreb', 'hostel_apartment', 45, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u centru.', '2026-04-29 10:47:10'),
(12, 'Zagreb', 'Hostel Temza', 'hostel_apartment', 35, 'media/slike/smjestaj-placeholder.jpg', 'Budget hostel.', '2026-04-29 10:47:10'),
(13, 'Zagreb', 'Funk Lounge Hostel', 'hostel_apartment', 35, 'media/slike/smjestaj-placeholder.jpg', 'Hostel za mlade putnike.', '2026-04-29 10:47:10'),
(14, 'Zagreb', 'HI Hostel Zagreb', 'hostel_apartment', 40, 'media/slike/smjestaj-placeholder.jpg', 'Klasični hostel.', '2026-04-29 10:47:10'),
(15, 'Zagreb', 'Hostel Centar', 'hostel_apartment', 45, 'media/slike/smjestaj-placeholder.jpg', 'Centralni hostel.', '2026-04-29 10:47:10'),
(16, 'Zagreb', 'Zagreb Speeka Hostel', 'hostel_apartment', 40, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u Donjem gradu.', '2026-04-29 10:47:10'),
(17, 'Zagreb', 'Rooms at Zajčeva 34', 'hostel_apartment', 60, 'media/slike/smjestaj-placeholder.jpg', 'Privatne sobe.', '2026-04-29 10:47:10'),
(18, 'Zagreb', 'Dezman Luxury Apartments', 'hostel_apartment', 120, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani u centru.', '2026-04-29 10:47:10'),
(19, 'Zagreb', '3on7 Apartments', 'hostel_apartment', 100, 'media/slike/smjestaj-placeholder.jpg', 'Apartmanski smještaj.', '2026-04-29 10:47:10'),
(20, 'Zagreb', 'B&B Cool Centre Zagreb', 'hostel_apartment', 90, 'media/slike/smjestaj-placeholder.jpg', 'B&B u centru.', '2026-04-29 10:47:10'),
(21, 'Karlovac', 'Aminess Florian & Godler Hotel', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Karlovcu.', '2026-04-29 10:47:10'),
(22, 'Karlovac', 'Aminess Kadoor Hotel', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Karlovcu.', '2026-04-29 10:47:10'),
(23, 'Karlovac', 'Boutique Hotel Korana Srakovcic', 'hotel_motel', 160, 'media/slike/smjestaj-placeholder.jpg', 'Hotel uz Koranu.', '2026-04-29 10:47:10'),
(24, 'Karlovac', 'Hotel Europa Karlovac', 'hotel_motel', 110, 'media/slike/smjestaj-placeholder.jpg', 'Hotel uz prometnu rutu.', '2026-04-29 10:47:10'),
(25, 'Karlovac', 'Hotel Carlstadt', 'hotel_motel', 100, 'media/slike/smjestaj-placeholder.jpg', 'Gradski hotel.', '2026-04-29 10:47:10'),
(26, 'Karlovac', 'Motel Kod Bakija', 'hotel_motel', 80, 'media/slike/smjestaj-placeholder.jpg', 'Motel u okolici Karlovca.', '2026-04-29 10:47:10'),
(27, 'Karlovac', 'Hotel Florian & Godler', 'hotel_motel', 125, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u centru.', '2026-04-29 10:47:10'),
(28, 'Karlovac', 'Motel Roganac', 'hotel_motel', 75, 'media/slike/smjestaj-placeholder.jpg', 'Motel opcija.', '2026-04-29 10:47:10'),
(29, 'Karlovac', 'Rooms Šu Šu', 'hotel_motel', 65, 'media/slike/smjestaj-placeholder.jpg', 'Sobe za kraći boravak.', '2026-04-29 10:47:10'),
(30, 'Karlovac', 'Sobe Žalac', 'hotel_motel', 60, 'media/slike/smjestaj-placeholder.jpg', 'Privatni smještaj.', '2026-04-29 10:47:10'),
(31, 'Karlovac', 'Hostel Bedem', 'hostel_apartment', 35, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u Karlovcu.', '2026-04-29 10:47:10'),
(32, 'Karlovac', 'Apartment Queen', 'hostel_apartment', 80, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(33, 'Karlovac', 'Feels Like Home', 'hostel_apartment', 80, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(34, 'Karlovac', 'Happy Sparrow', 'hostel_apartment', 75, 'media/slike/smjestaj-placeholder.jpg', 'Apartmanski smještaj.', '2026-04-29 10:47:10'),
(35, 'Karlovac', 'Happy Apartment', 'hostel_apartment', 85, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(36, 'Karlovac', 'Studio Apartman Marina', 'hostel_apartment', 70, 'media/slike/smjestaj-placeholder.jpg', 'Studio apartman.', '2026-04-29 10:47:10'),
(37, 'Karlovac', 'Apartman Gobac', 'hostel_apartment', 75, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(38, 'Karlovac', 'Apartman Kupa', 'hostel_apartment', 75, 'media/slike/smjestaj-placeholder.jpg', 'Apartman uz rijeku.', '2026-04-29 10:47:10'),
(39, 'Karlovac', 'Apartman Korana', 'hostel_apartment', 80, 'media/slike/smjestaj-placeholder.jpg', 'Apartman uz Koranu.', '2026-04-29 10:47:10'),
(40, 'Karlovac', 'Apartman Mrežnica', 'hostel_apartment', 85, 'media/slike/smjestaj-placeholder.jpg', 'Apartman za prirodni odmor.', '2026-04-29 10:47:10'),
(41, 'Osijek', 'Hotel Osijek', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Osijeku.', '2026-04-29 10:47:10'),
(42, 'Osijek', 'Hotel Waldinger', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Poznati hotel.', '2026-04-29 10:47:10'),
(43, 'Osijek', 'Boutique Hotel Tvrđa', 'hotel_motel', 140, 'media/slike/smjestaj-placeholder.jpg', 'Boutique hotel u Tvrđi.', '2026-04-29 10:47:10'),
(44, 'Osijek', 'Hotel Millennium', 'hotel_motel', 105, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Osijeku.', '2026-04-29 10:47:10'),
(45, 'Osijek', 'Hotel Central', 'hotel_motel', 100, 'media/slike/smjestaj-placeholder.jpg', 'Centralni hotel.', '2026-04-29 10:47:10'),
(46, 'Osijek', 'Zoo Hotel', 'hotel_motel', 110, 'media/slike/smjestaj-placeholder.jpg', 'Hotel blizu ZOO-a.', '2026-04-29 10:47:10'),
(47, 'Osijek', 'Hotel Vila Ariston', 'hotel_motel', 100, 'media/slike/smjestaj-placeholder.jpg', 'Vila hotel.', '2026-04-29 10:47:10'),
(48, 'Osijek', 'Hotel Lug', 'hotel_motel', 120, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u blizini Osijeka.', '2026-04-29 10:47:10'),
(49, 'Osijek', 'Hotel Materra', 'hotel_motel', 150, 'media/slike/smjestaj-placeholder.jpg', 'Premium hotel.', '2026-04-29 10:47:10'),
(50, 'Osijek', 'District Boutique Hotel', 'hotel_motel', 140, 'media/slike/smjestaj-placeholder.jpg', 'Boutique opcija.', '2026-04-29 10:47:10'),
(51, 'Osijek', 'Hostel Lega House', 'hostel_apartment', 35, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u Osijeku.', '2026-04-29 10:47:10'),
(52, 'Osijek', 'Home Apartmani i Sobe', 'hostel_apartment', 90, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani i sobe.', '2026-04-29 10:47:10'),
(53, 'Osijek', 'Apartments Osijek by the River', 'hostel_apartment', 85, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani.', '2026-04-29 10:47:10'),
(54, 'Osijek', 'Apartman Tvrđa', 'hostel_apartment', 80, 'media/slike/smjestaj-placeholder.jpg', 'Apartman blizu Tvrđe.', '2026-04-29 10:47:10'),
(55, 'Osijek', 'Apartman Drava', 'hostel_apartment', 75, 'media/slike/smjestaj-placeholder.jpg', 'Apartman uz Dravu.', '2026-04-29 10:47:10'),
(56, 'Osijek', 'Apartman Lena', 'hostel_apartment', 70, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(57, 'Osijek', 'Apartman Pampas', 'hostel_apartment', 70, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(58, 'Osijek', 'Studio Apartman Osijek', 'hostel_apartment', 65, 'media/slike/smjestaj-placeholder.jpg', 'Studio apartman.', '2026-04-29 10:47:10'),
(59, 'Osijek', 'Apartman Centrum Osijek', 'hostel_apartment', 85, 'media/slike/smjestaj-placeholder.jpg', 'Centralni apartman.', '2026-04-29 10:47:10'),
(60, 'Osijek', 'Sobe Merlon', 'hostel_apartment', 75, 'media/slike/smjestaj-placeholder.jpg', 'Sobe u Osijeku.', '2026-04-29 10:47:10'),
(61, 'Split', 'Cornaro Hotel', 'hotel_motel', 230, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Splitu.', '2026-04-29 10:47:10'),
(62, 'Split', 'Dioklecijan Hotel & Residence', 'hotel_motel', 170, 'media/slike/smjestaj-placeholder.jpg', 'Hotel i residence.', '2026-04-29 10:47:10'),
(63, 'Split', 'Radisson Blu Resort & Spa Split', 'hotel_motel', 240, 'media/slike/smjestaj-placeholder.jpg', 'Resort hotel.', '2026-04-29 10:47:10'),
(64, 'Split', 'Hotel Luxe', 'hotel_motel', 190, 'media/slike/smjestaj-placeholder.jpg', 'Hotel blizu centra.', '2026-04-29 10:47:10'),
(65, 'Split', 'Hotel Park Split', 'hotel_motel', 260, 'media/slike/smjestaj-placeholder.jpg', 'Premium hotel.', '2026-04-29 10:47:10'),
(66, 'Split', 'Hotel Vestibul Palace', 'hotel_motel', 240, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u povijesnoj jezgri.', '2026-04-29 10:47:10'),
(67, 'Split', 'Hotel Kastel 1700', 'hotel_motel', 180, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u centru.', '2026-04-29 10:47:10'),
(68, 'Split', 'Marvie Hotel & Health', 'hotel_motel', 180, 'media/slike/smjestaj-placeholder.jpg', 'Hotel s wellness sadržajem.', '2026-04-29 10:47:10'),
(69, 'Split', 'Hotel Globo', 'hotel_motel', 140, 'media/slike/smjestaj-placeholder.jpg', 'Gradski hotel.', '2026-04-29 10:47:10'),
(70, 'Split', 'Hotel Corner', 'hotel_motel', 150, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Splitu.', '2026-04-29 10:47:10'),
(71, 'Split', 'En Route Hostel', 'hostel_apartment', 40, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u Splitu.', '2026-04-29 10:47:10'),
(72, 'Split', 'Design Hostel 101 Dalmatinac', 'hostel_apartment', 35, 'media/slike/smjestaj-placeholder.jpg', 'Hostel.', '2026-04-29 10:47:10'),
(73, 'Split', 'Ciri Biri Bela Boutique Hostel', 'hostel_apartment', 45, 'media/slike/smjestaj-placeholder.jpg', 'Boutique hostel.', '2026-04-29 10:47:10'),
(74, 'Split', 'Design Hostel One', 'hostel_apartment', 40, 'media/slike/smjestaj-placeholder.jpg', 'Hostel.', '2026-04-29 10:47:10'),
(75, 'Split', 'Divota Apartment Hotel', 'hostel_apartment', 150, 'media/slike/smjestaj-placeholder.jpg', 'Apartmanski hotel.', '2026-04-29 10:47:10'),
(76, 'Split', 'Dream Luxury Rooms', 'hostel_apartment', 120, 'media/slike/smjestaj-placeholder.jpg', 'Privatne sobe.', '2026-04-29 10:47:10'),
(77, 'Split', 'Elegant Residence Rooms & Apartments', 'hostel_apartment', 130, 'media/slike/smjestaj-placeholder.jpg', 'Sobe i apartmani.', '2026-04-29 10:47:10'),
(78, 'Split', 'Fortuna Luxury Rooms', 'hostel_apartment', 120, 'media/slike/smjestaj-placeholder.jpg', 'Privatne sobe.', '2026-04-29 10:47:10'),
(79, 'Split', 'Diocletian Palace Apartment', 'hostel_apartment', 140, 'media/slike/smjestaj-placeholder.jpg', 'Apartman u centru.', '2026-04-29 10:47:10'),
(80, 'Split', 'Apartment Tokic', 'hostel_apartment', 100, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(81, 'Dubrovnik', 'Hotel Adriatic Dubrovnik', 'hotel_motel', 130, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Dubrovniku.', '2026-04-29 10:47:10'),
(82, 'Dubrovnik', 'Hotel More', 'hotel_motel', 260, 'media/slike/smjestaj-placeholder.jpg', 'Hotel uz more.', '2026-04-29 10:47:10'),
(83, 'Dubrovnik', 'Berkeley Hotel & Spa', 'hotel_motel', 200, 'media/slike/smjestaj-placeholder.jpg', 'Hotel i spa.', '2026-04-29 10:47:10'),
(84, 'Dubrovnik', 'Grand Hotel Park', 'hotel_motel', 210, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Dubrovniku.', '2026-04-29 10:47:10'),
(85, 'Dubrovnik', 'The Pucic Palace', 'hotel_motel', 300, 'media/slike/smjestaj-placeholder.jpg', 'Premium hotel.', '2026-04-29 10:47:10'),
(86, 'Dubrovnik', 'Boutique & Beach Hotel Villa Wolff', 'hotel_motel', 220, 'media/slike/smjestaj-placeholder.jpg', 'Boutique hotel.', '2026-04-29 10:47:10'),
(87, 'Dubrovnik', 'Hotel Lero', 'hotel_motel', 180, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Dubrovniku.', '2026-04-29 10:47:10'),
(88, 'Dubrovnik', 'Hotel Lapad', 'hotel_motel', 190, 'media/slike/smjestaj-placeholder.jpg', 'Hotel u Lapadu.', '2026-04-29 10:47:10'),
(89, 'Dubrovnik', 'Hotel Kompas Dubrovnik', 'hotel_motel', 240, 'media/slike/smjestaj-placeholder.jpg', 'Hotel uz more.', '2026-04-29 10:47:10'),
(90, 'Dubrovnik', 'Hotel Excelsior Dubrovnik', 'hotel_motel', 320, 'media/slike/smjestaj-placeholder.jpg', 'Luksuzni hotel.', '2026-04-29 10:47:10'),
(91, 'Dubrovnik', 'Hostel 365 For U', 'hostel_apartment', 50, 'media/slike/smjestaj-placeholder.jpg', 'Hostel u Dubrovniku.', '2026-04-29 10:47:10'),
(92, 'Dubrovnik', 'Hostel Saint Ursula Rooms', 'hostel_apartment', 45, 'media/slike/smjestaj-placeholder.jpg', 'Hostel/sobe.', '2026-04-29 10:47:10'),
(93, 'Dubrovnik', 'Anchi Guesthouse', 'hostel_apartment', 65, 'media/slike/smjestaj-placeholder.jpg', 'Guesthouse.', '2026-04-29 10:47:10'),
(94, 'Dubrovnik', 'Apartment Sokol', 'hostel_apartment', 120, 'media/slike/smjestaj-placeholder.jpg', 'Apartman.', '2026-04-29 10:47:10'),
(95, 'Dubrovnik', 'Apartments Adaleta', 'hostel_apartment', 115, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani.', '2026-04-29 10:47:10'),
(96, 'Dubrovnik', 'Guest House Mara', 'hostel_apartment', 80, 'media/slike/smjestaj-placeholder.jpg', 'Guest house.', '2026-04-29 10:47:10'),
(97, 'Dubrovnik', 'Apartments Sandra Solitudo', 'hostel_apartment', 110, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani.', '2026-04-29 10:47:10'),
(98, 'Dubrovnik', 'Apartments Sv. Jakov', 'hostel_apartment', 130, 'media/slike/smjestaj-placeholder.jpg', 'Apartmani.', '2026-04-29 10:47:10'),
(99, 'Dubrovnik', 'B&B Villa Dubrovnik Garden', 'hostel_apartment', 100, 'media/slike/smjestaj-placeholder.jpg', 'B&B smještaj.', '2026-04-29 10:47:10'),
(100, 'Dubrovnik', 'Bed and Breakfast Villa Klaic', 'hostel_apartment', 95, 'media/slike/smjestaj-placeholder.jpg', 'B&B smještaj.', '2026-04-29 10:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `adventures`
--

CREATE TABLE `adventures` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(150) NOT NULL DEFAULT 'Moja avantura',
  `trip_type` varchar(100) DEFAULT NULL,
  `travel_with` varchar(100) DEFAULT NULL,
  `budget_type` varchar(50) DEFAULT NULL,
  `budget_per_day` int(11) DEFAULT NULL,
  `destination` varchar(150) DEFAULT NULL,
  `daily_plan` text DEFAULT NULL,
  `transport_mode` varchar(100) DEFAULT NULL,
  `transport_priority` varchar(100) DEFAULT NULL,
  `accommodation_type` varchar(100) DEFAULT NULL,
  `stars` tinyint(4) DEFAULT NULL,
  `accommodation_location` varchar(100) DEFAULT NULL,
  `host_languages` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `adventure_image` varchar(255) DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `distance_km` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adventures`
--

INSERT INTO `adventures` (`id`, `user_id`, `naziv`, `trip_type`, `travel_with`, `budget_type`, `budget_per_day`, `destination`, `daily_plan`, `transport_mode`, `transport_priority`, `accommodation_type`, `stars`, `accommodation_location`, `host_languages`, `created_at`, `updated_at`, `adventure_image`, `status`, `distance_km`) VALUES
(27, 32, 'Avantura po Hrvatskoj sa Ivekom', 'Opuštanje, Avantura, Istraživanje gradova, Noćni život', 'Korisnik', 'Srednji', 150, 'Zagreb → Karlovac → Split', 'Zagreb: 1 dana\nKarlovac: 2 dana\nSplit: 3 dana\n', 'Rent-a-car', NULL, 'Motel', NULL, NULL, NULL, '2026-05-19 07:47:36', '2026-05-19 07:47:36', 'uploads/adventures/adventure_32_1779176856.png', 'active', 0),
(28, 33, 'Do karlovca', 'Opuštanje, Avantura', 'Korisnik', 'Low budget', 80, 'Zagreb → Karlovac', 'Zagreb: 1 dana\nKarlovac: 2 dana\n', 'Osobni auto', NULL, 'Motel', NULL, NULL, NULL, '2026-05-22 13:16:14', '2026-05-22 13:16:14', 'uploads/adventures/adventure_33_1779455774.png', 'active', 0),
(29, 34, 'S Jozom do Zagreba', 'Opuštanje, Avantura, Istraživanje gradova, Gastro putovanje, Noćni život', 'Korisnik', 'Low budget', 80, 'Osijek → Zagreb → Karlovac', 'Osijek: 1 dana\nZagreb: 3 dana\nKarlovac: 2 dana\n', 'Rent-a-car', NULL, 'Motel', NULL, NULL, NULL, '2026-05-24 10:52:21', '2026-05-24 10:52:21', 'uploads/adventures/adventure_34_1779619941.jpg', 'active', 0),
(30, 35, 'Pedro i ti', 'Opuštanje, Avantura, Istraživanje gradova, Gastro putovanje', 'Korisnik', 'Luksuz', 220, 'Dubrovnik → Split', 'Dubrovnik: 2 dana\nSplit: 4 dana\n', 'Osobni auto', NULL, 'Hotel', NULL, NULL, NULL, '2026-05-24 10:56:16', '2026-05-24 10:56:16', 'uploads/adventures/adventure_35_1779620176.jpg', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `adventure_participants`
--

CREATE TABLE `adventure_participants` (
  `id` int(10) UNSIGNED NOT NULL,
  `adventure_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` enum('owner','participant') DEFAULT 'participant',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adventure_posts`
--

CREATE TABLE `adventure_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `adventure_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adventure_post_images`
--

CREATE TABLE `adventure_post_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adventure_tags`
--

CREATE TABLE `adventure_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `adventure_id` int(10) UNSIGNED NOT NULL,
  `tag_type` varchar(50) NOT NULL,
  `tag_value` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adventure_tags`
--

INSERT INTO `adventure_tags` (`id`, `adventure_id`, `tag_type`, `tag_value`, `created_at`) VALUES
(575, 27, 'buddy_slots', '4', '2026-05-19 07:47:36'),
(576, 27, 'travel_buddy_open', '1', '2026-05-19 07:47:36'),
(577, 27, 'location', 'Zagreb', '2026-05-19 07:47:36'),
(578, 27, 'location_days', 'Zagreb|1', '2026-05-19 07:47:36'),
(579, 27, 'location', 'Karlovac', '2026-05-19 07:47:36'),
(580, 27, 'location_days', 'Karlovac|2', '2026-05-19 07:47:36'),
(581, 27, 'location', 'Split', '2026-05-19 07:47:36'),
(582, 27, 'location_days', 'Split|3', '2026-05-19 07:47:36'),
(583, 27, 'trip_type', 'Opuštanje', '2026-05-19 07:47:36'),
(584, 27, 'trip_type', 'Avantura', '2026-05-19 07:47:36'),
(585, 27, 'trip_type', 'Istraživanje gradova', '2026-05-19 07:47:36'),
(586, 27, 'trip_type', 'Noćni život', '2026-05-19 07:47:36'),
(587, 27, 'activity', 'Plaža', '2026-05-19 07:47:36'),
(588, 27, 'activity', 'Wellness', '2026-05-19 07:47:36'),
(589, 27, 'activity', 'Sunset spot', '2026-05-19 07:47:36'),
(590, 27, 'activity', 'Šetnja uz more', '2026-05-19 07:47:36'),
(591, 27, 'activity', 'Kafići', '2026-05-19 07:47:36'),
(592, 27, 'activity', 'Termalni bazeni', '2026-05-19 07:47:36'),
(593, 27, 'activity', 'Fotografija', '2026-05-19 07:47:36'),
(594, 27, 'activity', 'Planinarenje', '2026-05-19 07:47:36'),
(595, 27, 'activity', 'Zipline', '2026-05-19 07:47:36'),
(596, 27, 'activity', 'Kajaking', '2026-05-19 07:47:36'),
(597, 27, 'activity', 'Rafting', '2026-05-19 07:47:36'),
(598, 27, 'activity', 'Biciklizam', '2026-05-19 07:47:36'),
(599, 27, 'activity', 'Jahanje', '2026-05-19 07:47:36'),
(600, 27, 'activity', 'Road trip', '2026-05-19 07:47:36'),
(601, 27, 'activity', 'Off-road tura', '2026-05-19 07:47:36'),
(602, 27, 'activity', 'Vodopadi', '2026-05-19 07:47:36'),
(603, 27, 'activity', 'Muzeji', '2026-05-19 07:47:36'),
(604, 27, 'activity', 'Street photo ruta', '2026-05-19 07:47:36'),
(605, 27, 'activity', 'Lokalne tržnice', '2026-05-19 07:47:36'),
(606, 27, 'activity', 'Galerije', '2026-05-19 07:47:36'),
(607, 27, 'activity', 'Hidden gems', '2026-05-19 07:47:36'),
(608, 27, 'activity', 'Vidikovac', '2026-05-19 07:47:36'),
(609, 27, 'activity', 'Povijesne četvrti', '2026-05-19 07:47:36'),
(610, 27, 'activity', 'Parkovi', '2026-05-19 07:47:36'),
(611, 27, 'activity', 'Klubovi', '2026-05-19 07:47:36'),
(612, 27, 'activity', 'Rooftop bar', '2026-05-19 07:47:36'),
(613, 27, 'activity', 'Live music', '2026-05-19 07:47:36'),
(614, 27, 'activity', 'Pub crawl', '2026-05-19 07:47:36'),
(615, 27, 'activity', 'Beach party', '2026-05-19 07:47:36'),
(616, 27, 'activity', 'Stand-up show', '2026-05-19 07:47:36'),
(617, 27, 'activity', 'Kasna večera', '2026-05-19 07:47:36'),
(618, 27, 'location_activity', 'Zagreb|Jahanje u okolici Zagreba', '2026-05-19 07:47:36'),
(619, 27, 'location_activity', 'Zagreb|Lauba - Kuća za ljude i umjetnost', '2026-05-19 07:47:36'),
(620, 27, 'location_activity', 'Zagreb|Medvedgrad', '2026-05-19 07:47:36'),
(621, 27, 'location_activity', 'Zagreb|Muzej mamurluka', '2026-05-19 07:47:36'),
(622, 27, 'location_activity', 'Karlovac|Biciklom kroz četiri rijeke', '2026-05-19 07:47:36'),
(623, 27, 'location_activity', 'Karlovac|Foginovo noćno kupanje', '2026-05-19 07:47:36'),
(624, 27, 'location_activity', 'Karlovac|Planinarska ruta Vinica', '2026-05-19 07:47:36'),
(625, 27, 'location_activity', 'Karlovac|Aquatika', '2026-05-19 07:47:36'),
(626, 27, 'location_activity', 'Karlovac|Muzej grada Karlovca', '2026-05-19 07:47:36'),
(627, 27, 'location_activity', 'Karlovac|Zipline Duga Resa', '2026-05-19 07:47:36'),
(628, 27, 'location_activity', 'Split|Bene plaža', '2026-05-19 07:47:36'),
(629, 27, 'location_activity', 'Split|Sunset na Marjanu', '2026-05-19 07:47:36'),
(630, 27, 'location_activity', 'Split|Kajaking oko Marjana', '2026-05-19 07:47:36'),
(631, 27, 'location_activity', 'Split|Muzej grada Splita', '2026-05-19 07:47:36'),
(632, 27, 'location_activity', 'Split|Spa centar', '2026-05-19 07:47:36'),
(633, 27, 'stay_option', '{\"Karlovac\":\"Karlovac|Hostel Bedem\",\"Split\":\"Split|Ciri Biri Bela Boutique Hostel\",\"Zagreb\":\"Zagreb|', '2026-05-19 07:47:36'),
(634, 27, 'start_date', '2026-05-19', '2026-05-19 07:47:36'),
(635, 27, 'end_date', '2026-05-24', '2026-05-19 07:47:36'),
(636, 28, 'buddy_slots', '3', '2026-05-22 13:16:14'),
(637, 28, 'travel_buddy_open', '1', '2026-05-22 13:16:14'),
(638, 28, 'location', 'Zagreb', '2026-05-22 13:16:14'),
(639, 28, 'location_days', 'Zagreb|1', '2026-05-22 13:16:14'),
(640, 28, 'location', 'Karlovac', '2026-05-22 13:16:14'),
(641, 28, 'location_days', 'Karlovac|2', '2026-05-22 13:16:14'),
(642, 28, 'trip_type', 'Opuštanje', '2026-05-22 13:16:14'),
(643, 28, 'trip_type', 'Avantura', '2026-05-22 13:16:14'),
(644, 28, 'activity', 'Wellness', '2026-05-22 13:16:14'),
(645, 28, 'activity', 'Termalni bazeni', '2026-05-22 13:16:14'),
(646, 28, 'activity', 'Piknik', '2026-05-22 13:16:14'),
(647, 28, 'activity', 'Fotografija', '2026-05-22 13:16:14'),
(648, 28, 'activity', 'Lokalna hrana', '2026-05-22 13:16:14'),
(649, 28, 'activity', 'Planinarenje', '2026-05-22 13:16:14'),
(650, 28, 'activity', 'Biciklizam', '2026-05-22 13:16:14'),
(651, 28, 'activity', 'Ronjenje', '2026-05-22 13:16:14'),
(652, 28, 'activity', 'Jahanje', '2026-05-22 13:16:14'),
(653, 28, 'activity', 'Safari', '2026-05-22 13:16:14'),
(654, 28, 'activity', 'Kampiranje', '2026-05-22 13:16:14'),
(655, 28, 'activity', 'Road trip', '2026-05-22 13:16:14'),
(656, 28, 'activity', 'Off-road tura', '2026-05-22 13:16:14'),
(657, 28, 'activity', 'Vodopadi', '2026-05-22 13:16:14'),
(658, 28, 'location_activity', 'Zagreb|Medvednica / Sljeme', '2026-05-22 13:16:14'),
(659, 28, 'location_activity', 'Karlovac|Biciklističke staze', '2026-05-22 13:16:14'),
(660, 28, 'location_activity', 'Karlovac|Planinarska ruta Vinica', '2026-05-22 13:16:14'),
(661, 28, 'location_activity', 'Karlovac|Planinarski dom Dubovac', '2026-05-22 13:16:14'),
(662, 28, 'location_activity', 'Karlovac|Šuma Kozjača', '2026-05-22 13:16:14'),
(663, 28, 'stay_option', '{\"Karlovac\":\"Karlovac|Hostel Bedem\",\"Zagreb\":\"Zagreb|Nije potreban smještaj\"}', '2026-05-22 13:16:14'),
(664, 28, 'start_date', '2026-05-22', '2026-05-22 13:16:14'),
(665, 28, 'end_date', '2026-05-24', '2026-05-22 13:16:14'),
(666, 29, 'buddy_slots', '2', '2026-05-24 10:52:21'),
(667, 29, 'travel_buddy_open', '1', '2026-05-24 10:52:21'),
(668, 29, 'location', 'Osijek', '2026-05-24 10:52:21'),
(669, 29, 'location_days', 'Osijek|1', '2026-05-24 10:52:21'),
(670, 29, 'location', 'Zagreb', '2026-05-24 10:52:21'),
(671, 29, 'location_days', 'Zagreb|3', '2026-05-24 10:52:21'),
(672, 29, 'location', 'Karlovac', '2026-05-24 10:52:21'),
(673, 29, 'location_days', 'Karlovac|2', '2026-05-24 10:52:21'),
(674, 29, 'trip_type', 'Opuštanje', '2026-05-24 10:52:21'),
(675, 29, 'trip_type', 'Avantura', '2026-05-24 10:52:21'),
(676, 29, 'trip_type', 'Istraživanje gradova', '2026-05-24 10:52:21'),
(677, 29, 'trip_type', 'Gastro putovanje', '2026-05-24 10:52:21'),
(678, 29, 'trip_type', 'Noćni život', '2026-05-24 10:52:21'),
(679, 29, 'activity', 'Plaža', '2026-05-24 10:52:21'),
(680, 29, 'activity', 'Wellness', '2026-05-24 10:52:21'),
(681, 29, 'activity', 'Spa', '2026-05-24 10:52:21'),
(682, 29, 'activity', 'Yoga', '2026-05-24 10:52:21'),
(683, 29, 'activity', 'Sunset spot', '2026-05-24 10:52:21'),
(684, 29, 'activity', 'Šetnja uz more', '2026-05-24 10:52:21'),
(685, 29, 'activity', 'Boat tour', '2026-05-24 10:52:21'),
(686, 29, 'activity', 'Lagani shopping', '2026-05-24 10:52:21'),
(687, 29, 'activity', 'Kafići', '2026-05-24 10:52:21'),
(688, 29, 'activity', 'Termalni bazeni', '2026-05-24 10:52:21'),
(689, 29, 'activity', 'Piknik', '2026-05-24 10:52:21'),
(690, 29, 'activity', 'Fotografija', '2026-05-24 10:52:21'),
(691, 29, 'activity', 'Lokalna hrana', '2026-05-24 10:52:21'),
(692, 29, 'activity', 'Planinarenje', '2026-05-24 10:52:21'),
(693, 29, 'activity', 'Zipline', '2026-05-24 10:52:21'),
(694, 29, 'activity', 'Kajaking', '2026-05-24 10:52:21'),
(695, 29, 'activity', 'Rafting', '2026-05-24 10:52:21'),
(696, 29, 'activity', 'Biciklizam', '2026-05-24 10:52:21'),
(697, 29, 'activity', 'Ronjenje', '2026-05-24 10:52:21'),
(698, 29, 'activity', 'Jahanje', '2026-05-24 10:52:21'),
(699, 29, 'activity', 'Safari', '2026-05-24 10:52:21'),
(700, 29, 'activity', 'Kampiranje', '2026-05-24 10:52:21'),
(701, 29, 'activity', 'Road trip', '2026-05-24 10:52:21'),
(702, 29, 'activity', 'Penjanje', '2026-05-24 10:52:21'),
(703, 29, 'activity', 'Paragliding', '2026-05-24 10:52:21'),
(704, 29, 'activity', 'Off-road tura', '2026-05-24 10:52:21'),
(705, 29, 'activity', 'Vodopadi', '2026-05-24 10:52:21'),
(706, 29, 'activity', 'Razgledavanje', '2026-05-24 10:52:21'),
(707, 29, 'activity', 'Muzeji', '2026-05-24 10:52:21'),
(708, 29, 'activity', 'Stari grad', '2026-05-24 10:52:21'),
(709, 29, 'activity', 'Street photo ruta', '2026-05-24 10:52:21'),
(710, 29, 'activity', 'Lokalne tržnice', '2026-05-24 10:52:21'),
(711, 29, 'activity', 'Arhitektura', '2026-05-24 10:52:21'),
(712, 29, 'activity', 'Galerije', '2026-05-24 10:52:21'),
(713, 29, 'activity', 'Walking tour', '2026-05-24 10:52:21'),
(714, 29, 'activity', 'Hidden gems', '2026-05-24 10:52:21'),
(715, 29, 'activity', 'Vidikovac', '2026-05-24 10:52:21'),
(716, 29, 'activity', 'Shopping', '2026-05-24 10:52:21'),
(717, 29, 'activity', 'Street food', '2026-05-24 10:52:21'),
(718, 29, 'activity', 'Povijesne četvrti', '2026-05-24 10:52:21'),
(719, 29, 'activity', 'Parkovi', '2026-05-24 10:52:21'),
(720, 29, 'activity', 'Vinarije', '2026-05-24 10:52:21'),
(721, 29, 'activity', 'Fine dining', '2026-05-24 10:52:21'),
(722, 29, 'activity', 'Food tour', '2026-05-24 10:52:21'),
(723, 29, 'activity', 'Tržnica', '2026-05-24 10:52:21'),
(724, 29, 'activity', 'Kuharski tečaj', '2026-05-24 10:52:21'),
(725, 29, 'activity', 'Craft pivovare', '2026-05-24 10:52:21'),
(726, 29, 'activity', 'Degustacija sira', '2026-05-24 10:52:21'),
(727, 29, 'activity', 'Degustacija vina', '2026-05-24 10:52:21'),
(728, 29, 'activity', 'Slatkiši', '2026-05-24 10:52:21'),
(729, 29, 'activity', 'Seafood', '2026-05-24 10:52:21'),
(730, 29, 'activity', 'Tradicionalni restoran', '2026-05-24 10:52:21'),
(731, 29, 'activity', 'Klubovi', '2026-05-24 10:52:21'),
(732, 29, 'activity', 'Cocktail bar', '2026-05-24 10:52:21'),
(733, 29, 'activity', 'Rooftop bar', '2026-05-24 10:52:21'),
(734, 29, 'activity', 'Live music', '2026-05-24 10:52:21'),
(735, 29, 'activity', 'Pub crawl', '2026-05-24 10:52:21'),
(736, 29, 'activity', 'Festivali', '2026-05-24 10:52:21'),
(737, 29, 'activity', 'Koncerti', '2026-05-24 10:52:21'),
(738, 29, 'activity', 'Karaoke', '2026-05-24 10:52:21'),
(739, 29, 'activity', 'Noćna šetnja', '2026-05-24 10:52:21'),
(740, 29, 'activity', 'Beach party', '2026-05-24 10:52:21'),
(741, 29, 'activity', 'Stand-up show', '2026-05-24 10:52:21'),
(742, 29, 'activity', 'Kasna večera', '2026-05-24 10:52:21'),
(743, 29, 'activity', 'DJ event', '2026-05-24 10:52:21'),
(744, 29, 'location_activity', 'Osijek|Bicikliranje uz Dravu', '2026-05-24 10:52:21'),
(745, 29, 'location_activity', 'Osijek|Europska avenija', '2026-05-24 10:52:21'),
(746, 29, 'location_activity', 'Osijek|Kupanje na Kopiki', '2026-05-24 10:52:21'),
(747, 29, 'location_activity', 'Osijek|Pješački most', '2026-05-24 10:52:21'),
(748, 29, 'location_activity', 'Osijek|Trg Ante Starčevića', '2026-05-24 10:52:21'),
(749, 29, 'location_activity', 'Zagreb|Britanski trg', '2026-05-24 10:52:21'),
(750, 29, 'location_activity', 'Zagreb|Cvjetni trg', '2026-05-24 10:52:21'),
(751, 29, 'location_activity', 'Zagreb|Medvednica / Sljeme', '2026-05-24 10:52:21'),
(752, 29, 'location_activity', 'Karlovac|Biciklističke staze', '2026-05-24 10:52:21'),
(753, 29, 'location_activity', 'Karlovac|Biciklom kroz četiri rijeke', '2026-05-24 10:52:21'),
(754, 29, 'location_activity', 'Karlovac|Karlovačka Zvijezda', '2026-05-24 10:52:21'),
(755, 29, 'location_activity', 'Karlovac|Karlovački parkovi foto šetnja', '2026-05-24 10:52:21'),
(756, 29, 'location_activity', 'Karlovac|Šuma Kozjača', '2026-05-24 10:52:21'),
(757, 29, 'stay_option', '{\"Karlovac\":\"Karlovac|Hostel Bedem\",\"Osijek\":\"Osijek|Nije potreban smještaj\",\"Zagreb\":\"Zagreb|Zagreb', '2026-05-24 10:52:21'),
(758, 29, 'start_date', '2026-05-24', '2026-05-24 10:52:21'),
(759, 29, 'end_date', '2026-05-29', '2026-05-24 10:52:21'),
(760, 30, 'buddy_slots', '1', '2026-05-24 10:56:16'),
(761, 30, 'travel_buddy_open', '1', '2026-05-24 10:56:16'),
(762, 30, 'location', 'Dubrovnik', '2026-05-24 10:56:16'),
(763, 30, 'location_days', 'Dubrovnik|2', '2026-05-24 10:56:16'),
(764, 30, 'location', 'Split', '2026-05-24 10:56:16'),
(765, 30, 'location_days', 'Split|4', '2026-05-24 10:56:16'),
(766, 30, 'trip_type', 'Opuštanje', '2026-05-24 10:56:16'),
(767, 30, 'trip_type', 'Avantura', '2026-05-24 10:56:16'),
(768, 30, 'trip_type', 'Istraživanje gradova', '2026-05-24 10:56:16'),
(769, 30, 'trip_type', 'Gastro putovanje', '2026-05-24 10:56:16'),
(770, 30, 'activity', 'Sunset spot', '2026-05-24 10:56:16'),
(771, 30, 'activity', 'Lagani shopping', '2026-05-24 10:56:16'),
(772, 30, 'activity', 'Kafići', '2026-05-24 10:56:16'),
(773, 30, 'activity', 'Fotografija', '2026-05-24 10:56:16'),
(774, 30, 'activity', 'Lokalna hrana', '2026-05-24 10:56:16'),
(775, 30, 'activity', 'Zipline', '2026-05-24 10:56:16'),
(776, 30, 'activity', 'Kajaking', '2026-05-24 10:56:16'),
(777, 30, 'activity', 'Ronjenje', '2026-05-24 10:56:16'),
(778, 30, 'activity', 'Kampiranje', '2026-05-24 10:56:16'),
(779, 30, 'activity', 'Off-road tura', '2026-05-24 10:56:16'),
(780, 30, 'activity', 'Stari grad', '2026-05-24 10:56:16'),
(781, 30, 'activity', 'Arhitektura', '2026-05-24 10:56:16'),
(782, 30, 'activity', 'Walking tour', '2026-05-24 10:56:16'),
(783, 30, 'activity', 'Povijesne četvrti', '2026-05-24 10:56:16'),
(784, 30, 'activity', 'Vinarije', '2026-05-24 10:56:16'),
(785, 30, 'activity', 'Fine dining', '2026-05-24 10:56:16'),
(786, 30, 'activity', 'Tržnica', '2026-05-24 10:56:16'),
(787, 30, 'activity', 'Kuharski tečaj', '2026-05-24 10:56:16'),
(788, 30, 'activity', 'Degustacija vina', '2026-05-24 10:56:16'),
(789, 30, 'activity', 'Slatkiši', '2026-05-24 10:56:16'),
(790, 30, 'activity', 'Seafood', '2026-05-24 10:56:16'),
(791, 30, 'activity', 'Tradicionalni restoran', '2026-05-24 10:56:16'),
(792, 30, 'location_activity', 'Dubrovnik|Snorkeling', '2026-05-24 10:56:16'),
(793, 30, 'location_activity', 'Dubrovnik|Sunset na Srđu', '2026-05-24 10:56:16'),
(794, 30, 'location_activity', 'Dubrovnik|Sea kayaking Lokrum', '2026-05-24 10:56:16'),
(795, 30, 'location_activity', 'Dubrovnik|Zipline Dubrovnik', '2026-05-24 10:56:16'),
(796, 30, 'location_activity', 'Split|Sunset na Marjanu', '2026-05-24 10:56:16'),
(797, 30, 'location_activity', 'Split|Kajaking oko Marjana', '2026-05-24 10:56:16'),
(798, 30, 'location_activity', 'Split|Snorkeling Marjan', '2026-05-24 10:56:16'),
(799, 30, 'stay_option', '{\"Dubrovnik\":\"Dubrovnik|Nije potreban smještaj\",\"Split\":\"Split|Hotel Globo\"}', '2026-05-24 10:56:16'),
(800, 30, 'start_date', '2026-05-25', '2026-05-24 10:56:16'),
(801, 30, 'end_date', '2026-05-30', '2026-05-24 10:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `city_activities`
--

CREATE TABLE `city_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `city` varchar(100) NOT NULL,
  `name` varchar(180) NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `budget_level` enum('low','mid','high','all') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city_activities`
--

INSERT INTO `city_activities` (`id`, `city`, `name`, `activity_type`, `budget_level`, `description`, `created_at`) VALUES
(568, 'Zagreb', 'Jezero Jarun - gradska plaža', 'Plaža', 'low', 'Besplatna šljunčana plaža uz jezero Jarun, omiljeno kupalište Zagrepčana.', '2026-05-26 08:41:03'),
(569, 'Zagreb', 'Jutarnja yoga u Maksimiru', 'Yoga', 'low', 'Besplatne javne yoga sesije u parku Maksimir vikendom ujutro.', '2026-05-26 08:41:03'),
(570, 'Zagreb', 'Lotrščak - zalazak sunca', 'Sunset spot', 'low', 'Gledanje zalaska sunca s tornja Lotrščak iznad Gornjeg grada.', '2026-05-26 08:41:03'),
(571, 'Zagreb', 'Šetnja uz Savu', 'Šetnja uz more', 'low', 'Opuštena šetnja zelenim nasipom uz rijeku Savu.', '2026-05-26 08:41:03'),
(572, 'Zagreb', 'Cvjetni trg - špica', 'Kafići', 'low', 'Jutarnja kava na Cvjetnom trgu poput pravog Zagrepčanina.', '2026-05-26 08:41:03'),
(573, 'Zagreb', 'Piknik u Maksimiru', 'Piknik', 'low', 'Piknik u najstarijem gradskom parku u Hrvatskoj s jezerom i zelenilom.', '2026-05-26 08:41:03'),
(574, 'Zagreb', 'Gornji grad u zlatnom satu', 'Fotografija', 'low', 'Fotografiranje krovova, crkava i uličica Gornjeg grada u zalasku sunca.', '2026-05-26 08:41:03'),
(575, 'Zagreb', 'Štrukli na Dolcu', 'Lokalna hrana', 'low', 'Probaj originalne zagrebačke štrukle na ili kraj tržnice Dolac.', '2026-05-26 08:41:03'),
(576, 'Zagreb', 'Sljeme - Medvednica', 'Planinarenje', 'low', 'Pješačenje na najviši vrh Medvednice s pogledom na Zagreb i Alpe.', '2026-05-26 08:41:03'),
(577, 'Zagreb', 'Jarun biciklističke staze', 'Biciklizam', 'low', 'Kružna biciklistička ruta oko jezera Jarun dugačka 5 km.', '2026-05-26 08:41:03'),
(578, 'Zagreb', 'Kamp Medvednica', 'Kampiranje', 'low', 'Kampiranje u podnožju Medvednice, svega 20 minuta od centra.', '2026-05-26 08:41:03'),
(579, 'Zagreb', 'Road trip Zagorje', 'Road trip', 'low', 'Kratki road trip do dvoraca i brežuljaka Hrvatskog zagorja.', '2026-05-26 08:41:03'),
(580, 'Zagreb', 'Penjanje na Medvednici', 'Penjanje', 'low', 'Prirodne penjačke smjerove na stijenama Medvednice.', '2026-05-26 08:41:03'),
(581, 'Zagreb', 'Slap Bliznec', 'Vodopadi', 'low', 'Mali skriveni slap u šumi Medvednice, dostupan pješice.', '2026-05-26 08:41:03'),
(582, 'Zagreb', 'Kaptol i Gornji grad', 'Stari grad', 'low', 'Šetnja povijesnim srcem Zagreba - katedrala, crkva sv. Marka, uličice.', '2026-05-26 08:41:03'),
(583, 'Zagreb', 'Street photo - Donji grad', 'Street photo ruta', 'low', 'Fotografska šetnja Donjim gradom, od Trga bana Jelačića do Zrinjevca.', '2026-05-26 08:41:03'),
(584, 'Zagreb', 'Dolac tržnica', 'Lokalne tržnice', 'low', 'Najpoznatija zagrebačka tržnica svježe hrane na stepenicama iznad trga.', '2026-05-26 08:41:03'),
(585, 'Zagreb', 'Zelena potkova šetnja', 'Arhitektura', 'low', 'Šetnja nizom parkova i reprezentativnih zgrada u obliku potkove.', '2026-05-26 08:41:03'),
(586, 'Zagreb', 'Tunel Grič', 'Hidden gems', 'low', 'Tajni tunel iz WW2 ispod Gornjeg grada, danas javno dostupan.', '2026-05-26 08:41:03'),
(587, 'Zagreb', 'Vidikovac Sljeme', 'Vidikovac', 'low', 'Panoramski pogled na Zagreb, Zagorje i po lijepom vremenu Alpe.', '2026-05-26 08:41:03'),
(588, 'Zagreb', 'Langoši na Dolcu', 'Street food', 'low', 'Popularne fritule i langoši od lokalnih prodavača na Dolcu.', '2026-05-26 08:41:03'),
(589, 'Zagreb', 'Šetnja Gornjim gradom', 'Povijesne četvrti', 'low', 'Istraživanje uskih uličica, prolaza i skrivenih kutaka Gornjeg grada.', '2026-05-26 08:41:03'),
(590, 'Zagreb', 'Park Zrinjevac', 'Parkovi', 'low', 'Paviljon s glazbom, fontana i drvoredi u srcu Donjeg grada.', '2026-05-26 08:41:03'),
(591, 'Zagreb', 'Britanac tržnica', 'Tržnica', 'low', 'Manja, ali autentična lokalna tržnica u Donjem gradu.', '2026-05-26 08:41:03'),
(592, 'Zagreb', 'Vincek - sladoled i kolači', 'Slatkiši', 'low', 'Legendarna zagrebačka slastičarna s najboljim sladoledom u gradu.', '2026-05-26 08:41:03'),
(593, 'Zagreb', 'Konoba Didov san', 'Tradicionalni restoran', 'low', 'Domaća hrvatska kuhinja po pristupačnim cijenama u Gornjem gradu.', '2026-05-26 08:41:03'),
(594, 'Zagreb', 'Zagrebačka katedrala', 'Crkve i katedrale', 'low', 'Najveća hrvatska crkva s neo-gotičkim tornjevima i riznicom.', '2026-05-26 08:41:03'),
(595, 'Zagreb', 'Trg bana Jelačića', 'Spomenici', 'low', 'Simbolično srce Zagreba s konjaničkim kipom bana Jelačića.', '2026-05-26 08:41:03'),
(596, 'Zagreb', 'Nedjeljna špica na Cvjetnom', 'Lokalni običaji', 'low', 'Jutarnje okupljanje Zagrepčana uz kavu i novine - pravi lokalni ritual.', '2026-05-26 08:41:03'),
(597, 'Zagreb', 'Crkva sv. Marka', 'Stari grad', 'low', 'Ikonična crkva s oslikanim krovom u bojama hrvatskog grba.', '2026-05-26 08:41:03'),
(598, 'Zagreb', 'Ilica i Gornji grad noću', 'Noćna šetnja', 'low', 'Večernja šetnja od Trga kroz Ilicu do Gornjeg grada pod rasvjetom.', '2026-05-26 08:41:03'),
(599, 'Zagreb', 'Besplatni koncerti na Zrinjevcu', 'Koncerti', 'low', 'Ljetni besplatni koncerti u glazbenom paviljonu parka Zrinjevac.', '2026-05-26 08:41:03'),
(600, 'Zagreb', 'INmusic festival', 'Festivali', 'low', 'Najveći open-air glazbeni festival u Hrvatskoj na otočiću jezera Jarun.', '2026-05-26 08:41:03'),
(601, 'Zagreb', 'Močvara - stand-up večer', 'Stand-up show', 'low', 'Jeftine stand-up comedy večeri u klubu Močvara uz rijeku Savu.', '2026-05-26 08:41:03'),
(602, 'Zagreb', 'Karaoke bar Old Pharmacy', 'Karaoke', 'low', 'Zabavne karaoke noći u popularnom baru u centru Zagreba.', '2026-05-26 08:41:03'),
(603, 'Zagreb', 'Tkalčićeva pub crawl', 'Pub crawl', 'low', 'Obilazak barova na najživljoj zagrebačkoj ulici Tkalčićevoj.', '2026-05-26 08:41:03'),
(604, 'Zagreb', 'Nocni roštilj na Jarunu', 'Kasna večera', 'low', 'Kasna večera uz roštilj na terasama jaruna, popularno do kasno u noć.', '2026-05-26 08:41:03'),
(605, 'Zagreb', 'Razgledavanje Donjeg grada pješice', 'Razgledavanje', 'low', 'Besplatna šetnja i razgledavanje austrougarskih palača, fontana i trgova Donjeg grada.', '2026-05-26 08:41:03'),
(606, 'Zagreb', 'Slobodno ronjenje - jezero Jarun', 'Ronjenje', 'low', 'Snorkeling i slobodno ronjenje u bistrim vodama jezera Jarun bez opreme.', '2026-05-26 08:41:03'),
(607, 'Zagreb', 'Terme Sveti Martin', 'Wellness', 'mid', 'Termalni park s bazenima, saunama i wellness programima, sat vožnje od Zagreba.', '2026-05-26 08:41:03'),
(608, 'Zagreb', 'Spa centar Hotel Antunović', 'Spa', 'mid', 'Gradski spa s bazenom, saunama i masažama po razumnoj cijeni.', '2026-05-26 08:41:03'),
(609, 'Zagreb', 'Aquapark Terme Tuhelj', 'Termalni bazeni', 'mid', 'Termalni bazeni, tobogani i wellness na 40 minuta od Zagreba.', '2026-05-26 08:41:03'),
(610, 'Zagreb', 'Brodska tura Sava', 'Boat tour', 'mid', 'Razgledavanje Zagreba brodom po Savi s vodičem i pićem.', '2026-05-26 08:41:03'),
(611, 'Zagreb', 'Ilica boutique shopping', 'Lagani shopping', 'mid', 'Istraživanje boutique dućana, dizajnerskih komada i lokalnih brändova uz Ilicu.', '2026-05-26 08:41:03'),
(612, 'Zagreb', 'Zipline Medvednica', 'Zipline', 'mid', 'Adrenalinski zipline kroz šumu Medvednice s platforme na platformu.', '2026-05-26 08:41:03'),
(613, 'Zagreb', 'Kajak na Savi', 'Kajaking', 'mid', 'Vođena kajakaška tura rijekom Savom uz Zagreb.', '2026-05-26 08:41:03'),
(614, 'Zagreb', 'Rafting Kupa - Karlovac day trip', 'Rafting', 'mid', 'Jednodnevni izlet na rafting rijekom Kupom, 50 km od Zagreba.', '2026-05-26 08:41:03'),
(615, 'Zagreb', 'Konjički klub Zagreb', 'Jahanje', 'mid', 'Jahanje i dresura u konjičkom klubu u Sesvetama blizu Zagreba.', '2026-05-26 08:41:03'),
(616, 'Zagreb', 'Zoološki vrt Zagreb', 'Safari', 'mid', 'Posjet jednom od najstarijih zooloških vrtova u ovom dijelu Europe.', '2026-05-26 08:41:03'),
(617, 'Zagreb', 'Paragliding Medvednica', 'Paragliding', 'mid', 'Tandem paraglajding polet s Medvednice prema gradu.', '2026-05-26 08:41:03'),
(618, 'Zagreb', 'Off-road Žumberak', 'Off-road tura', 'mid', 'Terenska vožnja kroz park prirode Žumberak-Samoborsko gorje.', '2026-05-26 08:41:03'),
(619, 'Zagreb', 'Muzej prekinutih veza', 'Muzeji', 'mid', 'Jedinstven muzej s predmetima i pričama o prekinutim ljubavnim vezama.', '2026-05-26 08:41:03'),
(620, 'Zagreb', 'Moderna galerija Zagreb', 'Galerije', 'mid', 'Hrvatska moderna i suvremena likovna umjetnost od 19. st. do danas.', '2026-05-26 08:41:03'),
(621, 'Zagreb', 'Zagreb free walking tour premium', 'Walking tour', 'mid', 'Temeljitija vođena šetnja s privatnim vodičem kroz sve kvartove centra.', '2026-05-26 08:41:03'),
(622, 'Zagreb', 'Avenue Mall Zagreb', 'Shopping', 'mid', 'Najveći shopping centar u Zagrebu s više od 100 dućana.', '2026-05-26 08:41:03'),
(623, 'Zagreb', 'Vinarija Šember - Prigorje', 'Vinarije', 'mid', 'Degustacija prigorskih vina u vinariji na obroncima Medvednice.', '2026-05-26 08:41:03'),
(624, 'Zagreb', 'Zagreb food tour', 'Food tour', 'mid', 'Vođeni gastro obilazak tržnica, pekarnica i konoba u centru.', '2026-05-26 08:41:03'),
(625, 'Zagreb', 'Tečaj kuhanja - štrukli i sarma', 'Kuharski tečaj', 'mid', 'Naučite kuhati tradicionalna zagrebačka jela u domaćoj radionici.', '2026-05-26 08:41:03'),
(626, 'Zagreb', 'Craft pivovara Zmajska', 'Craft pivovare', 'mid', 'Tura i degustacija domaćeg craft piva u pivovari Zmajska.', '2026-05-26 08:41:03'),
(627, 'Zagreb', 'Sir i vino - degustacija Dolac', 'Degustacija sira', 'mid', 'Organizirana degustacija domaćih sireva i vina na tržnici Dolac.', '2026-05-26 08:41:03'),
(628, 'Zagreb', 'Vinska bar Bornstein', 'Degustacija vina', 'mid', 'Degustacija hrvatskih vina u jednom od najstarijih vinskih barova u gradu.', '2026-05-26 08:41:03'),
(629, 'Zagreb', 'Riblja tržnica i konoba', 'Seafood', 'mid', 'Svježa jadranska riba i plodovi mora u konobama uz riblju tržnicu.', '2026-05-26 08:41:03'),
(630, 'Zagreb', 'HNK Zagreb - opera ili balet', 'Kazalište', 'mid', 'Večer u reprezentativnoj zgradi Hrvatskog narodnog kazališta.', '2026-05-26 08:41:03'),
(631, 'Zagreb', 'Tura po kriptama i katakombama', 'Povijesna tura', 'mid', 'Vodena tura ispod Gornjeg grada kroz stare podrume i hodnike.', '2026-05-26 08:41:03'),
(632, 'Zagreb', 'Andautonija - rimski grad', 'Arheološka nalazišta', 'mid', 'Ostaci rimskog municipija Andautonije u Ščitarjevu kraj Zagreba.', '2026-05-26 08:41:03'),
(633, 'Zagreb', 'Advent u Zagrebu', 'Kulturni festival', 'mid', 'Višestruko nagrađivani božićni advent s klizanjem, hranom i glazbom.', '2026-05-26 08:41:03'),
(634, 'Zagreb', 'Dvorac Januševec', 'Dvorci', 'mid', 'Neogotički dvorac s parkovima u okolici Zagreba, pravi mali Versailles.', '2026-05-26 08:41:03'),
(635, 'Zagreb', 'Privatna tura Gornjim gradom', 'Vođena tura', 'mid', 'Privatni vodič kroz sve sakralne i svjetovne znamenitosti Gornjeg grada.', '2026-05-26 08:41:03'),
(636, 'Zagreb', 'Koncert u Lisinski dvorani', 'Koncert klasične glazbe', 'mid', 'Simfonijski koncerti u najvećoj konzertnoj dvorani u Hrvatskoj.', '2026-05-26 08:41:03'),
(637, 'Zagreb', 'Aquarius klub - Jarun', 'Klubovi', 'mid', 'Jedan od najdugovječnijih i najpopularnijih klubova u regiji na jezeru Jarun.', '2026-05-26 08:41:03'),
(638, 'Zagreb', 'Mundoaka Cocktail bar', 'Cocktail bar', 'mid', 'Kreativni kokteli i relaxed atmosfera u srcu Donjeg grada.', '2026-05-26 08:41:03'),
(639, 'Zagreb', 'Rooftop Skyscraper bar', 'Rooftop bar', 'mid', 'Pogled na noćni Zagreb s terase na vrhu Nebodere u centru grada.', '2026-05-26 08:41:03'),
(640, 'Zagreb', 'Jazz klub Sax', 'Live music', 'mid', 'Živa jazz glazba svake večeri u najstarijem jazz klubu u Zagrebu.', '2026-05-26 08:41:03'),
(641, 'Zagreb', 'Tretman klub - DJ noć', 'DJ event', 'mid', 'Prestižni DJ nastupi u jednom od top klubova u regiji.', '2026-05-26 08:41:03'),
(642, 'Zagreb', 'Jarun beach party ljeto', 'Beach party', 'mid', 'Ljetne beach party zabave na šljunčanim plažama jezera Jarun.', '2026-05-26 08:41:03'),
(643, 'Zagreb', 'Restoran Noel', 'Fine dining', 'high', 'Michelin preporučeni restoran s modernom interpretacijom hrvatske kuhinje.', '2026-05-26 08:41:03'),
(644, 'Zagreb', 'Esplanade Spa & Wellness', 'Spa', 'high', 'Luksuzni spa u kultnom Art Deco hotelu Esplanade, simbol Zagreba.', '2026-05-26 08:41:03'),
(645, 'Zagreb', 'Rooftop bar Westin Zagreb', 'Rooftop bar', 'high', 'Ekskluzivni rooftop bar na vrhu hotela Westin s panoramom cijelog grada.', '2026-05-26 08:41:03'),
(646, 'Zagreb', 'Ilica luxury boutiques', 'Shopping', 'high', 'Ekskluzivni boutici međunarodnih dizajnera duž Ilice i okolnih ulica.', '2026-05-26 08:41:03'),
(647, 'Zagreb', 'Hotel Palace Spa paket', 'Wellness', 'high', 'Cjelodnevni luksuzni spa i wellness paket u centru grada.', '2026-05-26 08:41:03'),
(648, 'Zagreb', 'Premijera HNK - loža', 'Kazalište', 'high', 'Premijerno izvođenje u ekskluzivnoj loži Hrvatskog narodnog kazališta.', '2026-05-26 08:41:03'),
(649, 'Zagreb', 'Privatni koncert u palači', 'Koncert klasične glazbe', 'high', 'Ekskluzivni privatni koncerti u povijesnim palačama Gornjeg grada.', '2026-05-26 08:41:03'),
(650, 'Zagreb', 'Premium gastro tura s chefom', 'Food tour', 'high', 'Privatna gastro tura po restoranima s Michelin preporukama uz osobnog chefa.', '2026-05-26 08:41:03'),
(651, 'Zagreb', 'Premium degustacija - top vinarije', 'Vinarije', 'high', 'Privatna degustacija premium hrvatskih vina u odabiru somelijera.', '2026-05-26 08:41:03'),
(652, 'Zagreb', 'Privatni jahački sat - Lipizzaner', 'Jahanje', 'high', 'Sat privatnog jahanja na lipizzaner konjima s iskusnim instruktorom.', '2026-05-26 08:41:03'),
(653, 'Zagreb', 'Privatni avanturistički paket Medvednica', 'Zipline', 'high', 'Kombinacija ziplinea, penjanja i off-roada s privatnim vodičem na Medvednici.', '2026-05-26 08:41:03'),
(654, 'Zagreb', 'Privatna brodska tura Sava i Jarun', 'Boat tour', 'high', 'Ekskluzivna večernja tura privatnim brodom sa živom glazbom i večerom.', '2026-05-26 08:41:03'),
(655, 'Zagreb', 'Dvorac Trakošćan - VIP tura', 'Dvorci', 'high', 'Privatna VIP tura jednim od najljepših dvoraca u Hrvatskoj.', '2026-05-26 08:41:03'),
(656, 'Zagreb', 'Helikopterska tura nad Zagrebom', 'Vođena tura', 'high', 'Helikopterski let iznad Zagreba, Medvednice i Zagorja s privatnim pilotom.', '2026-05-26 08:41:03'),
(657, 'Zagreb', 'VIP paket - Zagreb Advent', 'Kulturni festival', 'high', 'VIP pristup svim Advent lokacijama s rezerviranim stolovima i degustacijom.', '2026-05-26 08:41:03'),
(658, 'Zagreb', 'Masterclass s poznatim chefom', 'Kuharski tečaj', 'high', 'Privatni kuharski masterclass s jednim od vodećih chefova u Hrvatskoj.', '2026-05-26 08:41:03'),
(659, 'Zagreb', 'Fine dining seafood - restoran 4', 'Seafood', 'high', 'Premium tasting menu s jadranskim plodovima mora u fine dining restoranu.', '2026-05-26 08:41:03'),
(660, 'Zagreb', 'Privatni paraglajding paket', 'Paragliding', 'high', 'Privatni tandem let s fotografijom i video snimanjem cijelog iskustva.', '2026-05-26 08:41:03'),
(661, 'Zagreb', 'Privatni mixology tečaj', 'Cocktail bar', 'high', 'Privatni tečaj izrade koktela s profesionalnim barmenskim majstorom.', '2026-05-26 08:41:03'),
(662, 'Zagreb', 'Privatna jazz večera - Esplanade', 'Live music', 'high', 'Ekskluzivna večera uz live jazz band u dvorani hotela Esplanade.', '2026-05-26 08:41:03'),
(663, 'Zagreb', 'Razgledavanje Donjeg grada pješice', 'Razgledavanje', 'low', 'Šetnja i razgledavanje austrougarskih palača, fontana i trgova Donjeg grada.', '2026-05-26 08:41:03'),
(664, 'Zagreb', 'Organised pub crawl Tkalča', 'Pub crawl', 'mid', 'Organizirani pub crawl s vodičem i ulaznicama kroz barove Tkalčićeve ulice.', '2026-05-26 08:41:03'),
(665, 'Zagreb', 'Dom sportova - koncerti', 'Koncerti', 'mid', 'Koncerti domaćih i stranih glazbenika u Domu sportova.', '2026-05-26 08:41:03'),
(666, 'Zagreb', 'Kasna večera - Vinodol restoran', 'Kasna večera', 'mid', 'Kasna večera uz dalmatinske specijalitete u kultnom Vinodol restoranu.', '2026-05-26 08:41:03'),
(667, 'Zagreb', 'Stand-up večer - Kulušić', 'Stand-up show', 'mid', 'Tjedne stand-up comedy večeri u legendarnom zagrebačkom klubu Kulušić.', '2026-05-26 08:41:03'),
(668, 'Split', 'Plaža Bačvice', 'Plaža', 'low', 'Kultna gradska plaža tik uz centar, poznata po igri picigin u plitkom moru.', '2026-05-26 08:46:55'),
(669, 'Split', 'Jutarnja yoga na Marjanu', 'Yoga', 'low', 'Besplatne javne yoga sesije na travnatim terasama parka Marjan.', '2026-05-26 08:46:55'),
(670, 'Split', 'Marjan - zalazak sunca', 'Sunset spot', 'low', 'Spektakularan pogled na zalazak sunca nad otocima Brač i Šolta s vrha Marjana.', '2026-05-26 08:46:55'),
(671, 'Split', 'Riva - splitska šetnica', 'Šetnja uz more', 'low', 'Večernja šetnja palmovom rivom uz more s pogledom na Kaštelanski zaljev.', '2026-05-26 08:46:55'),
(672, 'Split', 'Kava na Peristilu', 'Kafići', 'low', 'Jutarnja kava na Peristilu, srcu Dioklecijanove palače okruženom rimskim stupovima.', '2026-05-26 08:46:55'),
(673, 'Split', 'Piknik na Marjanu', 'Piknik', 'low', 'Piknik u zelenilu parka šume Marjan s pogledom na more i otoke.', '2026-05-26 08:46:55'),
(674, 'Split', 'Fotografija - Dioklecijanova palača', 'Fotografija', 'low', 'Fotografiranje uličica, prolaza i detalja unutar živuće rimske palače.', '2026-05-26 08:46:55'),
(675, 'Split', 'Peka i buzara na Pazaru', 'Lokalna hrana', 'low', 'Probaj lokalne delicije - ribu, pašticadu i fritule na tržnici Pazar.', '2026-05-26 08:46:55'),
(676, 'Split', 'Marjan - pješačke staze', 'Planinarenje', 'low', 'Mreža laganih i zahtjevnijih staza kroz park šumu Marjan iznad mora.', '2026-05-26 08:46:55'),
(677, 'Split', 'Marjan bike trail', 'Biciklizam', 'low', 'Biciklistička staza kroz park šumu Marjan s nekoliko vidikovaca.', '2026-05-26 08:46:55'),
(678, 'Split', 'Kamp Trstenik', 'Kampiranje', 'low', 'Gradski kamp tik uz more na rubu Splita, dostupan biciklom iz centra.', '2026-05-26 08:46:55'),
(679, 'Split', 'Road trip Cetina kanjon', 'Road trip', 'low', 'Kratki road trip do kanjona rijeke Cetine i tvrđave Omiš, 30 km od Splita.', '2026-05-26 08:46:55'),
(680, 'Split', 'Penjanje - Marjan stijena', 'Penjanje', 'low', 'Prirodne penjačke smjerove na vapnenačkim stijenama Marjana iznad mora.', '2026-05-26 08:46:55'),
(681, 'Split', 'Slap Gubavica - Cetina', 'Vodopadi', 'low', 'Najviši slap u Hrvatskoj dostupan kratkim izletom iz Splita prema Omišu.', '2026-05-26 08:46:55'),
(682, 'Split', 'Dioklecijanova palača', 'Stari grad', 'low', 'Najočuvanija rimska palača na svijetu, UNESCO baština, živi kvart u srcu Splita.', '2026-05-26 08:46:55'),
(683, 'Split', 'Street photo - palača i Varoš', 'Street photo ruta', 'low', 'Fotografska ruta kroz rimske uličice palače i staru kamenitu četvrt Varoš.', '2026-05-26 08:46:55'),
(684, 'Split', 'Tržnica Pazar', 'Lokalne tržnice', 'low', 'Najživlja splitska tržnica svježe ribe, povrća i domaćih proizvoda.', '2026-05-26 08:46:55'),
(685, 'Split', 'Rimska arhitektura palače', 'Arhitektura', 'low', 'Šetnja kroz 1700 godina staru rimsku arhitekturu unutar živog gradskog tkiva.', '2026-05-26 08:46:55'),
(686, 'Split', 'Vestibul palače', 'Hidden gems', 'low', 'Tajna kupola unutar palače koja akustički savršeno pojačava glazbu.', '2026-05-26 08:46:55'),
(687, 'Split', 'Vidikovac Klis tvrđava', 'Vidikovac', 'low', 'Pogled na Split, more i zaleđe s tvrđave Klis iznad grada.', '2026-05-26 08:46:55'),
(688, 'Split', 'Burek i gablec na Pazaru', 'Street food', 'low', 'Domaći burek, sir i svježe pecivo na štandovima oko splitske tržnice.', '2026-05-26 08:46:55'),
(689, 'Split', 'Četvrt Varoš', 'Povijesne četvrti', 'low', 'Stari kameniti kvart uz Marjan s autentičnim splitskim životom i konobama.', '2026-05-26 08:46:55'),
(690, 'Split', 'Park šuma Marjan', 'Parkovi', 'low', 'Zelena oaza iznad Splita - borovi, šetnice, crkvice i pogled na Jadran.', '2026-05-26 08:46:55'),
(691, 'Split', 'Ribarska tržnica', 'Tržnica', 'low', 'Svježe ulovljena jadranska riba i plodovi mora svako jutro u srcu palače.', '2026-05-26 08:46:55'),
(692, 'Split', 'Fritule i kroštule - Pazar', 'Slatkiši', 'low', 'Tradicionalni dalmatinski slatkiši - fritule, kroštule i rafioli s tržnice.', '2026-05-26 08:46:55'),
(693, 'Split', 'Konoba Fetivi', 'Tradicionalni restoran', 'low', 'Autentična dalmatinska kuhinja u konobi unutar zidina Dioklecijanove palače.', '2026-05-26 08:46:55'),
(694, 'Split', 'Katedrala sv. Duje', 'Crkve i katedrale', 'low', 'Najstarija katedrala na svijetu smještena u Dioklecijanovom mauzoleju iz 4. st.', '2026-05-26 08:46:55'),
(695, 'Split', 'Grgur Ninski - kip', 'Spomenici', 'low', 'Kultni brončani kip biskupa Grgura Ninskog čiji se palac hvata za sreću.', '2026-05-26 08:46:55'),
(696, 'Split', 'Večernja korzo šetnja', 'Lokalni običaji', 'low', 'Splitski ritual - večernja šetnja rivom i Peristilom s poznatima i susjedima.', '2026-05-26 08:46:55'),
(697, 'Split', 'Obilazak zidina palače', 'Razgledavanje', 'low', 'Šetnja vanjskim i unutarnjim perimetrom rimskih zidina Dioklecijanove palače.', '2026-05-26 08:46:55'),
(698, 'Split', 'Riva i palača noću', 'Noćna šetnja', 'low', 'Večernja šetnja osvijetljenom rivom i rimskim uličicama palače noću.', '2026-05-26 08:46:55'),
(699, 'Split', 'Besplatni koncerti na Peristilu', 'Koncerti', 'low', 'Ljetni besplatni glazbeni nastupi na Peristilu, najljepšoj pozornici u regiji.', '2026-05-26 08:46:55'),
(700, 'Split', 'Splitsko ljeto - besplatni program', 'Festivali', 'low', 'Besplatni dio programa festivala Splitsko ljeto na otvorenim pozornicama.', '2026-05-26 08:46:55'),
(701, 'Split', 'Comedy večer - Kocka bar', 'Stand-up show', 'low', 'Jeftine stand-up comedy večeri u popularnom baru blizu palače.', '2026-05-26 08:46:55'),
(702, 'Split', 'Karaoke - Ghetto club', 'Karaoke', 'low', 'Karaoke noći u najstarijem alternativnom klubu unutar zidina palače.', '2026-05-26 08:46:55'),
(703, 'Split', 'Palača pub crawl', 'Pub crawl', 'low', 'Samostalni obilazak barova i konobi unutar i oko Dioklecijanove palače.', '2026-05-26 08:46:55'),
(704, 'Split', 'Kasna večera - Nostromo konoba', 'Kasna večera', 'low', 'Kasna večera uz svježu ribu u ribičkom kvartu Matejuška blizu centra.', '2026-05-26 08:46:55'),
(705, 'Split', 'Snorkeling - Bene plaža', 'Ronjenje', 'low', 'Slobodni snorkeling uz bistro more na plaži Bene na poluotoku Marjan.', '2026-05-26 08:46:55'),
(706, 'Split', 'Wellness - Hotel Cornaro', 'Wellness', 'mid', 'Gradski wellness sa saunom, bazenom i masažama u boutique hotelu blizu palače.', '2026-05-26 08:46:55'),
(707, 'Split', 'Spa centar - Hotel Park', 'Spa', 'mid', 'Spa s tretmanima i bazenom s pogledom na more u hotelu Park na Marjanu.', '2026-05-26 08:46:55'),
(708, 'Split', 'Aquapark Solaris - Šibenik', 'Termalni bazeni', 'mid', 'Termalni vodeni park na sat vožnje od Splita prema Šibeniku.', '2026-05-26 08:46:55'),
(709, 'Split', 'Izlet na Brač i Hvar', 'Boat tour', 'mid', 'Jednodnevni brodski izlet do plaže Zlatni rat na Braču i Hvara.', '2026-05-26 08:46:55'),
(710, 'Split', 'Shopping - Meštrović šetalište', 'Lagani shopping', 'mid', 'Boutique dućani, galerije i dizajnerski komadi uz obalno šetalište.', '2026-05-26 08:46:55'),
(711, 'Split', 'Zipline Omiš - kanjon Cetine', 'Zipline', 'mid', 'Adrenalinski zipline iznad kanjona rijeke Cetine kod Omiša, 30 km od Splita.', '2026-05-26 08:46:55'),
(712, 'Split', 'Kajak tura - Marjan i otočići', 'Kajaking', 'mid', 'Vođena kajakaška tura oko poluotoka Marjan i do bližih otočića.', '2026-05-26 08:46:55'),
(713, 'Split', 'Rafting Cetina', 'Rafting', 'mid', 'Rafting avantura divljim kanjonom rijeke Cetine s vodilatom uključenom.', '2026-05-26 08:46:55'),
(714, 'Split', 'Konjički klub Kaštela', 'Jahanje', 'mid', 'Jahanje uz dalmatinsku obalu u konjičkom klubu u Kaštelima kraj Splita.', '2026-05-26 08:46:55'),
(715, 'Split', 'Jeepski safari - Dinara', 'Safari', 'mid', 'Terenski safari kroz planinu Dinaru i dalmatinsko zaleđe.', '2026-05-26 08:46:55'),
(716, 'Split', 'Paragliding Mosor', 'Paragliding', 'mid', 'Tandem paraglajding s planine Mosor prema moru s pogledom na Split.', '2026-05-26 08:46:55'),
(717, 'Split', 'Off-road Mosor i Dinara', 'Off-road tura', 'mid', 'Terenska vožnja kroz planinu Mosor i dalmatinsko zaleđe s vodičem.', '2026-05-26 08:46:55'),
(718, 'Split', 'Galerija Meštrović', 'Muzeji', 'mid', 'Skulpture Ivana Meštrovića u mediteranskoj vili s vrtom i pogledom na more.', '2026-05-26 08:46:55'),
(719, 'Split', 'Galerija umjetnina Split', 'Galerije', 'mid', 'Bogata zbirka hrvatske likovne umjetnosti od 14. do 20. stoljeća.', '2026-05-26 08:46:55'),
(720, 'Split', 'Privatni walking tour - palača', 'Walking tour', 'mid', 'Privatni vodič kroz sve slojeve Dioklecijanove palače - rimski, medieval, modern.', '2026-05-26 08:46:55'),
(721, 'Split', 'City Center One Split', 'Shopping', 'mid', 'Najveći shopping centar u Dalmaciji s međunarodnim i domaćim brendovima.', '2026-05-26 08:46:55'),
(722, 'Split', 'Tura - mletačka arhitektura', 'Arhitektura', 'mid', 'Vođena tura mletačkim kućama, kulama i fontanama Splita izvan palače.', '2026-05-26 08:46:55'),
(723, 'Split', 'Vinarija Stina - Brač day trip', 'Vinarije', 'mid', 'Degustacija dalmatinskih vina u vinariji Stina na otoku Braču.', '2026-05-26 08:46:55'),
(724, 'Split', 'Split food tour', 'Food tour', 'mid', 'Vođeni gastro obilazak tržnice, konoba i pasticerija s lokalnim vodičem.', '2026-05-26 08:46:55'),
(725, 'Split', 'Tečaj kuhanja - peka i pašticada', 'Kuharski tečaj', 'mid', 'Naučite kuhati pašticadu i ribu pod pekon u dalmatinskoj kući.', '2026-05-26 08:46:55'),
(726, 'Split', 'Craft pivovara Sinj', 'Craft pivovare', 'mid', 'Tura i degustacija dalmatinskog craft piva u pivovari u Sinju.', '2026-05-26 08:46:55'),
(727, 'Split', 'Paški sir degustacija', 'Degustacija sira', 'mid', 'Organizirana degustacija autohtonog paškog sira i domaćih prehrambenih proizvoda.', '2026-05-26 08:46:55'),
(728, 'Split', 'Vinska tura Kaštela', 'Degustacija vina', 'mid', 'Degustacija dalmatinskih vina u starim kaštelanskim vinarijama uz more.', '2026-05-26 08:46:55'),
(729, 'Split', 'Konoba Matejuška', 'Seafood', 'mid', 'Svježa jadranska riba i plodovi mora u ribičkoj četvrti Matejuška.', '2026-05-26 08:46:55'),
(730, 'Split', 'HNK Split - drama ili opera', 'Kazalište', 'mid', 'Večer u Hrvatskom narodnom kazalištu u Splitu, jednoj od najljepših kazališnih zgrada.', '2026-05-26 08:46:55'),
(731, 'Split', 'Tura podzemlja palače', 'Povijesna tura', 'mid', 'Vođena tura kroz podrumske prostorije Dioklecijanove palače, bolje očuvane od gornjeg dijela.', '2026-05-26 08:46:55'),
(732, 'Split', 'Solin - antička Salona', 'Arheološka nalazišta', 'mid', 'Ruševine Salone, glavnog grada rimske provincije Dalmacije, 5 km od Splita.', '2026-05-26 08:46:55'),
(733, 'Split', 'Splitsko ljeto - ulaznice', 'Kulturni festival', 'mid', 'Operne, baletne i dramske predstave festivala Splitsko ljeto u palači.', '2026-05-26 08:46:55'),
(734, 'Split', 'Klis tvrđava - tura', 'Dvorci', 'mid', 'Vođena tura tvrđavom Klis s prikazom mačevanja i povijesnih bitaka.', '2026-05-26 08:46:55'),
(735, 'Split', 'Privatna tura s arheologom', 'Vođena tura', 'mid', 'Privatna tura Dioklecijanovom palačom s licenciranim arheologom vodilatom.', '2026-05-26 08:46:55'),
(736, 'Split', 'Glazbeni festival - Peristil', 'Koncert klasične glazbe', 'mid', 'Koncerti klasične glazbe na rimskom Peristilu, jednoj od najdramatičnijih pozornica.', '2026-05-26 08:46:55'),
(737, 'Split', 'Klub Gaga - Split', 'Klubovi', 'mid', 'Popularan noćni klub blizu Bačvica s domaćim i međunarodnim DJ-evima.', '2026-05-26 08:46:55'),
(738, 'Split', 'Cocktail bar Teak', 'Cocktail bar', 'mid', 'Kreativni kokteli i morska atmosfera u baru s pogledom na Kaštelanski zaljev.', '2026-05-26 08:46:55'),
(739, 'Split', 'Rooftop bar Vestibul Palace', 'Rooftop bar', 'mid', 'Kokteli s pogledom na Peristil i rimske zidine s terase hotela Vestibul.', '2026-05-26 08:46:55'),
(740, 'Split', 'Jazz i blues - Fluid bar', 'Live music', 'mid', 'Živa glazba nekoliko večeri tjedno u popularnom baru blizu palače.', '2026-05-26 08:46:55'),
(741, 'Split', 'Lvl Split - DJ noć', 'DJ event', 'mid', 'Prestiži DJ nastupi u jednom od najvećih dalmatinskih noćnih klubova.', '2026-05-26 08:46:55'),
(742, 'Split', 'Bačvice beach party ljeto', 'Beach party', 'mid', 'Ljetne beach party zabave uz DJ setove na plaži Bačvice.', '2026-05-26 08:46:55'),
(743, 'Split', 'Stand-up Split - Kocka', 'Stand-up show', 'mid', 'Tjedne stand-up comedy večeri s poznatim i novim imenima splitske scene.', '2026-05-26 08:46:55'),
(744, 'Split', 'Restoran Zinfandel - Radisson', 'Fine dining', 'high', 'Moderna dalmatinska kuhinja s premium vinima i pogledom na more.', '2026-05-26 08:46:55'),
(745, 'Split', 'Le Méridien Lav Spa', 'Spa', 'high', 'Luksuzni spa resort s bazenom, tretmanima i pogledom na Kaštelanski zaljev.', '2026-05-26 08:46:55'),
(746, 'Split', 'Sky bar - Radisson Blu', 'Rooftop bar', 'high', 'Ekskluzivni rooftop bar s panoramom Splita, otoka i mora.', '2026-05-26 08:46:55'),
(747, 'Split', 'Luxury boutiques - Marmontova', 'Shopping', 'high', 'Ekskluzivni boutici međunarodnih dizajnerskih brendova uz Marmontovu ulicu.', '2026-05-26 08:46:55'),
(748, 'Split', 'Le Méridien full-day wellness', 'Wellness', 'high', 'Cjelodnevni luksuzni wellness paket s privatnim tretmanima u resort hotelu.', '2026-05-26 08:46:55'),
(749, 'Split', 'Privatna jahta - otoci Dalmacije', 'Boat tour', 'high', 'Privatna jednodnevna tura jahtom do Hvara, Brača i Šolte s kapetanom.', '2026-05-26 08:46:55'),
(750, 'Split', 'Premium vinska tura helikopterom', 'Vinarije', 'high', 'Helikopterski let do vinarija na Hvaru i Braču s privatnom degustacijom.', '2026-05-26 08:46:55'),
(751, 'Split', 'Chef\'s table - Michelin iskustvo', 'Food tour', 'high', 'Ekskluzivni chef\'s table dinner s degustacijskim menijem i pogrebanim vinom.', '2026-05-26 08:46:55'),
(752, 'Split', 'Privatno jahanje uz more - Kaštela', 'Jahanje', 'high', 'Privatni sat jahanja uz dalmatinsku obalu s fotografiranjem iz sedla.', '2026-05-26 08:46:55'),
(753, 'Split', 'VIP avanturistički paket Cetina', 'Zipline', 'high', 'Zipline, rafting i kanjoning kombinacija s privatnim vodičem i fotografijom.', '2026-05-26 08:46:55'),
(754, 'Split', 'Privatni paraglajding - Mosor', 'Paragliding', 'high', 'Privatni tandem let s video snimanjem i fotografijom cijelog iskustva.', '2026-05-26 08:46:55'),
(755, 'Split', 'Privatna tura - Klis i Salona', 'Dvorci', 'high', 'Ekskluzivna privatna tura tvrđavom Klis i antičkom Salonom s arqueologom.', '2026-05-26 08:46:55'),
(756, 'Split', 'Privatna helikopterska tura', 'Vođena tura', 'high', 'Helikopterski let nad Splitom, Marjanom, otocima i kanjonom Cetine.', '2026-05-26 08:46:55'),
(757, 'Split', 'VIP loža - Splitsko ljeto', 'Kulturni festival', 'high', 'Ekskluzivna VIP loža na premijernoj večeri Splitskog ljeta u palači.', '2026-05-26 08:46:55'),
(758, 'Split', 'Privatni masterclass - dalmatinska kuhinja', 'Kuharski tečaj', 'high', 'Privatni kuharski masterclass s poznatim splitskim chefom u privatnoj kuhinji.', '2026-05-26 08:46:55'),
(759, 'Split', 'Privatna večera na brodu uz more', 'Seafood', 'high', 'Ekskluzivna večera svježim morskim plodovima na privatnom brodu uz Marjan.', '2026-05-26 08:46:55'),
(760, 'Split', 'Premijera - HNK Split VIP', 'Kazalište', 'high', 'VIP mjesta na premijernoj izvedbi s domjenkom i susretom s glumcima.', '2026-05-26 08:46:55'),
(761, 'Split', 'Privatni koncert - Peristil', 'Koncert klasične glazbe', 'high', 'Ekskluzivni privatni glazbeni nastup na rimskom Peristilu za posebne prigode.', '2026-05-26 08:46:55'),
(762, 'Split', 'Privatni mixology tečaj - Riva', 'Cocktail bar', 'high', 'Privatni tečaj izrade dalmatinski inspiriranih koktela s master barmenskim.', '2026-05-26 08:46:55'),
(763, 'Split', 'Privatna večera uz live band - Lav', 'Live music', 'high', 'Ekskluzivna večera uz live jazz i dalmatinska jela u restoranu hotela Lav.', '2026-05-26 08:46:55'),
(764, 'Split', 'Open-air koncerti - Bačvice', 'Koncerti', 'low', 'Besplatni ljetni koncerti domaćih izvođača na open-air pozornici uz plažu Bačvice.', '2026-05-26 08:46:55'),
(765, 'Split', 'Plaža Žnjan - besplatni DJ', 'DJ event', 'low', 'Besplatni ljetni DJ setovi na plaži Žnjan na rubu Splita.', '2026-05-26 08:46:55'),
(766, 'Split', 'Kasna večera - Villa Spiza', 'Kasna večera', 'mid', 'Kasna večera autentičnom dalmatinskom kuhinjom u maloj konobi unutar palače.', '2026-05-26 08:46:55'),
(767, 'Split', 'Organizirani pub crawl - palača i Bačvice', 'Pub crawl', 'mid', 'Organizirani večernji obilazak barova od palače do plaže Bačvice s vodičem.', '2026-05-26 08:46:55'),
(768, 'Dubrovnik', 'Plaža Banje', 'Plaža', 'low', 'Najpopularnija gradska plaža s pogledom na zidine starog grada i otok Lokrum.', '2026-05-26 08:52:07'),
(769, 'Dubrovnik', 'Jutarnja yoga - Gradac park', 'Yoga', 'low', 'Besplatne jutarnje yoga sesije u parku Gradac s pogledom na more i zidine.', '2026-05-26 08:52:07'),
(770, 'Dubrovnik', 'Srđ - zalazak sunca', 'Sunset spot', 'low', 'Spektakularan zalazak sunca s vrha brda Srđ iznad Dubrovnika i Elafitskih otoka.', '2026-05-26 08:52:07'),
(771, 'Dubrovnik', 'Stradun - večernja šetnja', 'Šetnja uz more', 'low', 'Šetnja najljepšom pješačkom ulicom na Jadranu okruženom baroknim palačama.', '2026-05-26 08:52:07'),
(772, 'Dubrovnik', 'Kava na Stradunu', 'Kafići', 'low', 'Jutarnja kava na terasi uz Stradun i gledanje gradskog života u starom gradu.', '2026-05-26 08:52:07'),
(773, 'Dubrovnik', 'Piknik - park Gradac', 'Piknik', 'low', 'Piknik u mirnom parku Gradac na rubu starog grada s pogledom na more.', '2026-05-26 08:52:07'),
(774, 'Dubrovnik', 'Fotografija - zidine i Stradun', 'Fotografija', 'low', 'Fotografiranje crvenih krovova, zidina i jadranskog mora iz različitih kutova.', '2026-05-26 08:52:07'),
(775, 'Dubrovnik', 'Zeljanica i soparnik na tržnici', 'Lokalna hrana', 'low', 'Probaj lokalne pite i dalmatinske specijalitete na jutarnjoj tržnici u starom gradu.', '2026-05-26 08:52:07'),
(776, 'Dubrovnik', 'Pješačenje na Srđ', 'Planinarenje', 'low', 'Pješačka staza od starog grada do vrha Srđa s panoramskim pogledom na Dubrovnik.', '2026-05-26 08:52:07'),
(777, 'Dubrovnik', 'Biciklizam - Konavle', 'Biciklizam', 'low', 'Biciklistička ruta kroz mirnu dolinu Konavle jugoistočno od Dubrovnika.', '2026-05-26 08:52:07'),
(778, 'Dubrovnik', 'Kamp Solitudo - Babin kuk', 'Kampiranje', 'low', 'Kampiranje u zelenilu poluotoka Babin kuk na rubu Dubrovnika uz more.', '2026-05-26 08:52:07'),
(779, 'Dubrovnik', 'Road trip - Pelješac i Ston', 'Road trip', 'low', 'Kratki road trip do Stona s najduljim zidinama u Europi i oštrigarnicama.', '2026-05-26 08:52:07'),
(780, 'Dubrovnik', 'Penjanje - stijena Srđ', 'Penjanje', 'low', 'Prirodne penjačke smjerove na vapnenačkim stijenama iznad Dubrovnika.', '2026-05-26 08:52:07'),
(781, 'Dubrovnik', 'Slap Konavoski dvori', 'Vodopadi', 'low', 'Mali slikoviti slap u selu Ljuta u Konavlima, 20 km od Dubrovnika.', '2026-05-26 08:52:07'),
(782, 'Dubrovnik', 'Šetnja starim gradom', 'Stari grad', 'low', 'Istraživanje ulica, crkava, fontana i skrivenih kutaka UNESCO zaštićenog starog grada.', '2026-05-26 08:52:07'),
(783, 'Dubrovnik', 'Street photo - stari grad', 'Street photo ruta', 'low', 'Fotografska ruta kroz uske kaldrmom popločane uličice i skrivene prolaze starog grada.', '2026-05-26 08:52:07'),
(784, 'Dubrovnik', 'Tržnica Gundulićeva poljana', 'Lokalne tržnice', 'low', 'Jutarnja tržnica svježeg voća, povrća, sira i lavande na Gundulićevoj poljani.', '2026-05-26 08:52:07'),
(785, 'Dubrovnik', 'Barokna arhitektura starog grada', 'Arhitektura', 'low', 'Šetnja i promatranje baroknih palača, crkava i fontana izgrađenih nakon potresa 1667.', '2026-05-26 08:52:07'),
(786, 'Dubrovnik', 'Buža - rupa u zidu', 'Hidden gems', 'low', 'Tajna rupa u gradskim zidinama koja vodi do barem nad morem na stijeni.', '2026-05-26 08:52:07'),
(787, 'Dubrovnik', 'Vidikovac - vrh zidina', 'Vidikovac', 'low', 'Pogled na cijeli stari grad, luku i more sa sjeveroistočnog dijela gradskih zidina.', '2026-05-26 08:52:07'),
(788, 'Dubrovnik', 'Rozata i pršut - lokalni dućani', 'Street food', 'low', 'Probaj rozatu, pršut i sir iz lokalnih dućana unutar zidina starog grada.', '2026-05-26 08:52:07'),
(789, 'Dubrovnik', 'Četvrt Prijeko', 'Povijesne četvrti', 'low', 'Paralelna ulica uz Stradun s autentičnim konobarama i lokalnim životom.', '2026-05-26 08:52:07'),
(790, 'Dubrovnik', 'Arboretum Trsteno', 'Parkovi', 'low', 'Jedan od najstarijih arboretuma u Europi, 20 km od Dubrovnika, besplatno uz minimalnu ulaznicu.', '2026-05-26 08:52:07'),
(791, 'Dubrovnik', 'Tržnica Gruž', 'Tržnica', 'low', 'Svježa riba, voće i povrće na živoj tržnici u luci Gruž izvan starog grada.', '2026-05-26 08:52:07'),
(792, 'Dubrovnik', 'Rozata - dubrovački desert', 'Slatkiši', 'low', 'Probaj rozatu, tradicionalni dubrovački karamel puding, u lokalnim slastičarnama.', '2026-05-26 08:52:07'),
(793, 'Dubrovnik', 'Konoba Dalmatino', 'Tradicionalni restoran', 'low', 'Autentična dubrovačka kuhinja po pristupačnim cijenama u ulicama starog grada.', '2026-05-26 08:52:07'),
(794, 'Dubrovnik', 'Katedrala Uznesenja Marijina', 'Crkve i katedrale', 'low', 'Barokna katedrala sa zbirkom relikvija sv. Vlaha i Titijanovim djelima.', '2026-05-26 08:52:07'),
(795, 'Dubrovnik', 'Orlandov stup', 'Spomenici', 'low', 'Simbol slobode Dubrovačke Republike na Luži, središte javnog života grada.', '2026-05-26 08:52:07'),
(796, 'Dubrovnik', 'Festa sv. Vlaha', 'Lokalni običaji', 'low', 'Proslava zaštitnika Dubrovnika 3. veljače s procesijama i folklором - besplatno.', '2026-05-26 08:52:07'),
(797, 'Dubrovnik', 'Šetnja lukom Stare luke', 'Razgledavanje', 'low', 'Razgledavanje tvrđave Sv. Ivana, lučice i ribara u staroj gradskoj luci.', '2026-05-26 08:52:07'),
(798, 'Dubrovnik', 'Stari grad noću', 'Noćna šetnja', 'low', 'Večernja šetnja osvjetljenim Stradunom i zidinama - romantičan i besplatan doživljaj.', '2026-05-26 08:52:07'),
(799, 'Dubrovnik', 'Besplatni koncerti - Lazareti', 'Koncerti', 'low', 'Besplatni ljetni koncerti u prostoru Lazareta, starog karantanog kompleksa.', '2026-05-26 08:52:07'),
(800, 'Dubrovnik', 'Dubrovnik Summer Festival - ulica', 'Festivali', 'low', 'Besplatni ulični program Dubrovačkih ljetnih igara na otvorenim prostorima.', '2026-05-26 08:52:07'),
(801, 'Dubrovnik', 'Comedy večer - D\'Vino bar', 'Stand-up show', 'low', 'Jeftine komedijaste večeri u popularnom vinskom baru unutar zidina.', '2026-05-26 08:52:07'),
(802, 'Dubrovnik', 'Karaoke - Revelin club terasa', 'Karaoke', 'low', 'Zabavne karaoke noći u baru ispod tvrđave Revelin.', '2026-05-26 08:52:07'),
(803, 'Dubrovnik', 'Stari grad pub crawl', 'Pub crawl', 'low', 'Samostalni obilazak barova unutar i oko zidina starog grada.', '2026-05-26 08:52:07'),
(804, 'Dubrovnik', 'Kasna večera - Konoba Bonaca', 'Kasna večera', 'low', 'Kasna večera uz svježu ribu u konobi blizu luke Gruž po razumnoj cijeni.', '2026-05-26 08:52:07'),
(805, 'Dubrovnik', 'Snorkeling - Lokrum', 'Ronjenje', 'low', 'Slobodni snorkeling uz kristalno čisto more oko otoka Lokrum.', '2026-05-26 08:52:07'),
(806, 'Dubrovnik', 'Besplatni DJ - plaža Banje', 'DJ event', 'low', 'Besplatni ljetni DJ setovi na plaži Banje uz pogled na zidine starog grada.', '2026-05-26 08:52:07'),
(807, 'Dubrovnik', 'Ulični glazbenici - Stradun', 'Koncerti', 'low', 'Spontani nastupi glazbenika na Stradunu i trgu Luža svake večeri ljeti.', '2026-05-26 08:52:07'),
(808, 'Dubrovnik', 'Wellness - Hotel Excelsior', 'Wellness', 'mid', 'Spa s bazenom i masažama s pogledom na more u hotelu Excelsior.', '2026-05-26 08:52:07'),
(809, 'Dubrovnik', 'Spa centar - Hotel Hilton', 'Spa', 'mid', 'Moderni spa s tretmanima i bazenom unutar zidina starog grada.', '2026-05-26 08:52:07'),
(810, 'Dubrovnik', 'Infinity bazen - Hotel More', 'Termalni bazeni', 'mid', 'Infinity bazen s pogledom na Jadran i stari grad u boutique hotelu More.', '2026-05-26 08:52:07'),
(811, 'Dubrovnik', 'Izlet na Elafitske otoke', 'Boat tour', 'mid', 'Jednodnevni brodski izlet do Lopuda, Šipana i Koločepa s kupanjem.', '2026-05-26 08:52:07'),
(812, 'Dubrovnik', 'Boutique dućani - stari grad', 'Lagani shopping', 'mid', 'Istraživanje boutique galerija, rukotvorina i dizajnerskih komada unutar zidina.', '2026-05-26 08:52:07'),
(813, 'Dubrovnik', 'Zipline - Srđ', 'Zipline', 'mid', 'Adrenalinski zipline s vrha Srđa prema gradu s pogledom na stari grad i more.', '2026-05-26 08:52:07'),
(814, 'Dubrovnik', 'Sea kayaking - oko zidina', 'Kajaking', 'mid', 'Kajakaška tura morem oko gradskih zidina i do otoka Lokruma.', '2026-05-26 08:52:07'),
(815, 'Dubrovnik', 'Rafting Neretva - day trip', 'Rafting', 'mid', 'Jednodnevni izlet na rafting rijekom Neretvom u Bosni i Hercegovini.', '2026-05-26 08:52:07'),
(816, 'Dubrovnik', 'Jahanje - Konavle dolina', 'Jahanje', 'mid', 'Jahanje kroz mirnu i zelenu dolinu Konavle s pogledom na more.', '2026-05-26 08:52:07'),
(817, 'Dubrovnik', 'Jeepski safari - Konavle', 'Safari', 'mid', 'Terenski safari kroz sela, vinograde i šume doline Konavle.', '2026-05-26 08:52:07'),
(818, 'Dubrovnik', 'Paragliding - Srđ', 'Paragliding', 'mid', 'Tandem paraglajding s brda Srđ s pogledom na stari grad i otoke.', '2026-05-26 08:52:07'),
(819, 'Dubrovnik', 'Off-road - Konavle i Popovo polje', 'Off-road tura', 'mid', 'Terenska vožnja kroz Konavle i kraški teren Popova polja.', '2026-05-26 08:52:07'),
(820, 'Dubrovnik', 'Knežev dvor - muzej', 'Muzeji', 'mid', 'Muzej Dubrovačke Republike u gotičko-renesansnoj palači nekadašnjih knezova.', '2026-05-26 08:52:07'),
(821, 'Dubrovnik', 'Galerija umjetnina Dubrovnik', 'Galerije', 'mid', 'Bogata zbirka dubrovačkog slikarstva od 14. do 20. stoljeća.', '2026-05-26 08:52:07'),
(822, 'Dubrovnik', 'Game of Thrones walking tour', 'Walking tour', 'mid', 'Vođena tura lokacijama snimanja Igre prijestolja unutar i oko starog grada.', '2026-05-26 08:52:07'),
(823, 'Dubrovnik', 'Importanne centar Dubrovnik', 'Shopping', 'mid', 'Moderni shopping centar s međunarodnim brendovima blizu gradskih vrata.', '2026-05-26 08:52:07'),
(824, 'Dubrovnik', 'Tura - renesansna arhitektura', 'Arhitektura', 'mid', 'Privatna tura renesansnim palačama, ljetnikovcima i perivojem dubrovačke vlastele.', '2026-05-26 08:52:07'),
(825, 'Dubrovnik', 'Vinarija Miloš - Pelješac', 'Vinarije', 'mid', 'Degustacija Dingača i Postupa u vinariji na poluotoku Pelješcu, 80 km od Dubrovnika.', '2026-05-26 08:52:07'),
(826, 'Dubrovnik', 'Dubrovnik food tour', 'Food tour', 'mid', 'Vođeni gastro obilazak tržnica, slastičarna i konoba s lokalnim vodičem.', '2026-05-26 08:52:07'),
(827, 'Dubrovnik', 'Tečaj kuhanja - crni rižot i brudet', 'Kuharski tečaj', 'mid', 'Naučite kuhati crni rižot i brudet u autentičnoj dubrovačkoj kući.', '2026-05-26 08:52:07'),
(828, 'Dubrovnik', 'Craft pivo - Pelješac brewery', 'Craft pivovare', 'mid', 'Degustacija lokalnog craft piva s pogledom na vinograde Pelješca.', '2026-05-26 08:52:07'),
(829, 'Dubrovnik', 'Sir i prošek - lokalna degustacija', 'Degustacija sira', 'mid', 'Degustacija domaćeg sira, pršuta i prošeka iz Konavala i Pelješca.', '2026-05-26 08:52:07'),
(830, 'Dubrovnik', 'Vinska tura - Pelješac i Konavle', 'Degustacija vina', 'mid', 'Tura po vinarijama Pelješca i Konavala s degustacijom autohtonih sorti.', '2026-05-26 08:52:07'),
(831, 'Dubrovnik', 'Konoba Ribar - svježa riba', 'Seafood', 'mid', 'Svježa jadranska riba i dagnje u konobi blizu ribarske luke Gruž.', '2026-05-26 08:52:07'),
(832, 'Dubrovnik', 'Kazalište Marina Držića', 'Kazalište', 'mid', 'Predstave u kazalištu posvećenom najvećem dubrovačkom komediografu.', '2026-05-26 08:52:07'),
(833, 'Dubrovnik', 'Tura - Dubrovačka Republika', 'Povijesna tura', 'mid', 'Vođena tura kroz povijest najdugovječnije republike na Jadranu s licenciranim vodičem.', '2026-05-26 08:52:07'),
(834, 'Dubrovnik', 'Cavtat - antički lokaliteti', 'Arheološka nalazišta', 'mid', 'Posjet antičkim ostatcima i muzejima u Cavtatu, 20 km od Dubrovnika.', '2026-05-26 08:52:07'),
(835, 'Dubrovnik', 'Dubrovačke ljetne igre', 'Kulturni festival', 'mid', 'Ulaznice za predstave i koncerte najstarijeg festivala na Jadranu.', '2026-05-26 08:52:07'),
(836, 'Dubrovnik', 'Tvrđava Lovrijenac - tura', 'Dvorci', 'mid', 'Vođena tura tvrđavom Lovrijenac, dubrovačkim Gibraltarom, s pogledom na more.', '2026-05-26 08:52:07'),
(837, 'Dubrovnik', 'Privatna tura povjesničara', 'Vođena tura', 'mid', 'Privatna tura starim gradom s licenciranim povjesničarem i stručnjakom.', '2026-05-26 08:52:07'),
(838, 'Dubrovnik', 'Koncerti u crkvi sv. Spasa', 'Koncert klasične glazbe', 'mid', 'Koncerti klasične glazbe u renesansnoj crkvi sv. Spasa na ulazu u Stradun.', '2026-05-26 08:52:07'),
(839, 'Dubrovnik', 'Revelin klub', 'Klubovi', 'mid', 'Jedan od najljepših noćnih klubova na Jadranu unutar tvrđave Revelin.', '2026-05-26 08:52:07'),
(840, 'Dubrovnik', 'Cocktail bar D\'Vino', 'Cocktail bar', 'mid', 'Odabir dalmatinskih i međunarodnih vina te koktela u srcu starog grada.', '2026-05-26 08:52:07'),
(841, 'Dubrovnik', 'Above 5 Rooftop bar', 'Rooftop bar', 'mid', 'Kokteli i pogled na krovove i more s rooftopa u srcu starog grada.', '2026-05-26 08:52:07'),
(842, 'Dubrovnik', 'Jazz bar Troubadour', 'Live music', 'mid', 'Živa jazz glazba svake večeri u jednom od najstarijih i najromantičnijih barova u gradu.', '2026-05-26 08:52:07'),
(843, 'Dubrovnik', 'Revelin - DJ noć', 'DJ event', 'mid', 'Prestižni međunarodni DJ nastupi u dvorani tvrđave Revelin.', '2026-05-26 08:52:07'),
(844, 'Dubrovnik', 'Banje Beach Club party', 'Beach party', 'mid', 'Ljetne beach party zabave s DJ setovima na ekskluzivnoj plaži Banje.', '2026-05-26 08:52:07'),
(845, 'Dubrovnik', 'Comedy night - Lazareti', 'Stand-up show', 'mid', 'Tjedne stand-up comedy večeri u kulturnom centru Lazareti.', '2026-05-26 08:52:07'),
(846, 'Dubrovnik', 'Kasna večera - Proto restoran', 'Kasna večera', 'mid', 'Kasna večera uz dalmatinske specijalitete u jednom od najstarijih restorana u gradu.', '2026-05-26 08:52:07'),
(847, 'Dubrovnik', 'Organizirani pub crawl - stari grad', 'Pub crawl', 'mid', 'Organizirani večernji obilazak barova i kletova unutar zidina s vodičem.', '2026-05-26 08:52:07'),
(848, 'Dubrovnik', 'Restoran 360° Dubrovnik', 'Fine dining', 'high', 'Michelin zvjezdica, tasting menu i pogled na gradsku luku s gradskih zidina.', '2026-05-26 08:52:07'),
(849, 'Dubrovnik', 'Villa Dubrovnik Spa', 'Spa', 'high', 'Ekskluzivni spa s privatnim bazenom i pogledom na more u boutique hotelu.', '2026-05-26 08:52:07'),
(850, 'Dubrovnik', 'Bar - Hotel Pucić Palace', 'Rooftop bar', 'high', 'Ekskluzivni rooftop s panoramom na Stradun i krovove starog grada.', '2026-05-26 08:52:07'),
(851, 'Dubrovnik', 'Luxury boutiques - stari grad', 'Shopping', 'high', 'Ekskluzivni boutici međunarodnih dizajnerskih brendova unutar gradskih zidina.', '2026-05-26 08:52:07'),
(852, 'Dubrovnik', 'Boutique Spa - Hotel Excelsior', 'Wellness', 'high', 'Cjelodnevni luksuzni wellness paket s privatnim tretmanima i pogledom na more.', '2026-05-26 08:52:07'),
(853, 'Dubrovnik', 'Privatna jahta - Elafiti i Mljet', 'Boat tour', 'high', 'Privatna višednevna tura jahtom do Elafitskih otoka i Nacionalnog parka Mljet.', '2026-05-26 08:52:07'),
(854, 'Dubrovnik', 'Helikopter - vinska tura Pelješac', 'Vinarije', 'high', 'Helikopterski let do vinarija Pelješca s privatnom degustacijom Dingača.', '2026-05-26 08:52:07'),
(855, 'Dubrovnik', 'Chef\'s table - 360° Dubrovnik', 'Food tour', 'high', 'Ekskluzivni chef\'s table s degustacijskim menijem u jedinom Michelin restoranu.', '2026-05-26 08:52:07'),
(856, 'Dubrovnik', 'Privatno jahanje - Konavle sunset', 'Jahanje', 'high', 'Privatni zalazak sunca na konju kroz dolinu Konavle s fotografiranjem.', '2026-05-26 08:52:07'),
(857, 'Dubrovnik', 'VIP avanturistički paket Srđ', 'Zipline', 'high', 'Zipline, penjanje i off-road kombinacija s privatnim vodičem i fotografijom.', '2026-05-26 08:52:07'),
(858, 'Dubrovnik', 'Privatni paraglajding - Srđ', 'Paragliding', 'high', 'Privatni tandem let s video snimanjem i fotografijom nad starim gradom.', '2026-05-26 08:52:07'),
(859, 'Dubrovnik', 'Privatna tura - Lovrijenac i Revelin', 'Dvorci', 'high', 'Ekskluzivna privatna tura svim tvrđavama Dubrovnika s povjesničarem.', '2026-05-26 08:52:07'),
(860, 'Dubrovnik', 'Helikopterska tura - Dubrovnik i otoci', 'Vođena tura', 'high', 'Helikopterski let nad starim gradom, Elafitima i poluotokom Pelješcem.', '2026-05-26 08:52:07'),
(861, 'Dubrovnik', 'VIP loža - Ljetne igre', 'Kulturni festival', 'high', 'Ekskluzivna VIP loža na otvorenju Dubrovačkih ljetnih igara s domjenkom.', '2026-05-26 08:52:07'),
(862, 'Dubrovnik', 'Privatni masterclass - dubrovačka kuhinja', 'Kuharski tečaj', 'high', 'Privatni kuharski masterclass s poznatim dubrovačkim chefom u privatnoj palači.', '2026-05-26 08:52:07'),
(863, 'Dubrovnik', 'Privatna večera na brodu - Elafiti', 'Seafood', 'high', 'Ekskluzivna večera svježim morskim plodovima na privatnom brodu uz Elafite.', '2026-05-26 08:52:07'),
(864, 'Dubrovnik', 'Premijera - Ljetne igre VIP', 'Kazalište', 'high', 'VIP mjesta na premijernoj izvedbi s domjenkom i susretom s izvođačima.', '2026-05-26 08:52:07'),
(865, 'Dubrovnik', 'Privatni koncert - Knežev dvor', 'Koncert klasične glazbe', 'high', 'Ekskluzivni privatni glazbeni nastup u dvorištu Kneževog dvora za posebne prigode.', '2026-05-26 08:52:07'),
(866, 'Dubrovnik', 'Privatni mixology tečaj - stari grad', 'Cocktail bar', 'high', 'Privatni tečaj izrade dubrovački inspiriranih koktela s master barmenskim majstorom.', '2026-05-26 08:52:07'),
(867, 'Dubrovnik', 'Privatna večera uz live jazz - Excelsior', 'Live music', 'high', 'Ekskluzivna večera uz live jazz trio i dalmatinska jela s pogledom na more.', '2026-05-26 08:52:07');

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_one` int(10) UNSIGNED NOT NULL,
  `user_two` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `selector` varchar(24) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `selector`, `token_hash`, `expires_at`, `created_at`) VALUES
(27, 35, '84693b08776e', '374945ac0419dd3a8969eaeaf0f6a2716e9e3d65bee4699240dd7ec23d3bb626', '2026-06-23 12:53:11', '2026-05-24 10:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `ime` varchar(100) NOT NULL,
  `prezime` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token_hash` varchar(255) DEFAULT NULL,
  `verification_expires_at` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `reset_token_hash` varchar(255) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `korisnicko_ime` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `idealno_putovanje` varchar(50) DEFAULT NULL,
  `budget` varchar(50) DEFAULT NULL,
  `putuje_s_kim` varchar(50) DEFAULT NULL,
  `profilna_slika` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `title` varchar(100) DEFAULT 'Dnevni sanjar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ime`, `prezime`, `email`, `password_hash`, `is_verified`, `verification_token_hash`, `verification_expires_at`, `verified_at`, `reset_token_hash`, `reset_expires_at`, `last_login_at`, `korisnicko_ime`, `bio`, `idealno_putovanje`, `budget`, `putuje_s_kim`, `profilna_slika`, `created_at`, `updated_at`, `title`) VALUES
(32, 'Ivan', 'Zdelar', 'ivanzdelar2909@gmail.com', '$2y$10$bYxE/o2S5HOsK3fv3PQQ8O5PhO6uKndPfikrB/SVc8.AS6EB2Pm.m', 1, NULL, NULL, '2026-05-17 12:38:56', NULL, NULL, '2026-05-22 14:21:06', 'ivan', 'ja sam ivan volim putovati, jesti, spavati i to sve zna se', 'sve', 'srednji', 'svi', 'uploads/user_32_1779017984.jpg', '2026-05-17 11:38:46', '2026-05-22 13:21:06', 'Dnevni sanjar'),
(33, 'Ivan', 'Zdelar', 'ivan@mail.com', '$2y$10$pMBhzAUbLnEPpiZn2PcbteoQyjiOsb/C85DmFg6d2MNpUB.t8xAOm', 1, NULL, NULL, '2026-05-22 14:14:09', NULL, NULL, '2026-05-22 14:14:09', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-22 13:14:02', '2026-05-22 13:16:50', 'Dnevni sanjar'),
(34, 'Josip', 'Jospicic', 'josip@mail.com', '$2y$10$jutvMF6.o4Dy6Sp7MwxvP.cyrr2v2gC3ZH.jVm0aqUza5E0sW.XU6', 1, NULL, NULL, '2026-05-24 11:33:25', NULL, NULL, '2026-05-24 11:33:25', 'JozoBozo', 'Ja sam Jozo i ti budi moj Bozo', 'grad', 'srednji', 'prijatelji', 'uploads/user_34_1779618859.jpg', '2026-05-24 10:33:19', '2026-05-24 10:34:19', 'Dnevni sanjar'),
(35, 'Pedro', 'Pascal', 'pedro@mail.com', '$2y$10$h.sQ/icHgHOxxOqqq0H7Ket0NrTJmuZrzkedl7z4m8ZZK6jvVQ4V2', 1, NULL, NULL, '2026-05-24 11:53:11', NULL, NULL, '2026-05-24 11:53:11', 'PedroPapa', 'Ja sam Pedro  da', 'avantura', 'srednji', 'svi', 'uploads/user_35_1779620065.jpg', '2026-05-24 10:53:01', '2026-05-24 10:54:25', 'Dnevni sanjar');

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

CREATE TABLE `user_interests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `interest_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_interests`
--

INSERT INTO `user_interests` (`id`, `user_id`, `interest_name`, `created_at`) VALUES
(89, 32, 'plaze', '2026-05-17 11:39:44'),
(90, 32, 'planine', '2026-05-17 11:39:44'),
(91, 32, 'muzika', '2026-05-17 11:39:44'),
(92, 32, 'sport', '2026-05-17 11:39:44'),
(93, 32, 'hiking', '2026-05-17 11:39:44'),
(94, 32, 'arhitektura', '2026-05-17 11:39:44'),
(95, 32, 'nocni', '2026-05-17 11:39:44'),
(96, 32, 'kampiranje', '2026-05-17 11:39:44'),
(97, 32, 'biciklizam', '2026-05-17 11:39:44'),
(98, 32, 'eko', '2026-05-17 11:39:44'),
(103, 34, 'hrana', '2026-05-24 10:34:19'),
(104, 34, 'fotografija', '2026-05-24 10:34:19'),
(105, 34, 'priroda', '2026-05-24 10:34:19'),
(106, 34, 'kampiranje', '2026-05-24 10:34:19'),
(107, 35, 'plaze', '2026-05-24 10:54:25'),
(108, 35, 'sport', '2026-05-24 10:54:25'),
(109, 35, 'hiking', '2026-05-24 10:54:25'),
(110, 35, 'priroda', '2026-05-24 10:54:25'),
(111, 35, 'arhitektura', '2026-05-24 10:54:25'),
(112, 35, 'nocni', '2026-05-24 10:54:25'),
(113, 35, 'kampiranje', '2026-05-24 10:54:25'),
(114, 35, 'biciklizam', '2026-05-24 10:54:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adventures`
--
ALTER TABLE `adventures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adventures_user` (`user_id`);

--
-- Indexes for table `adventure_participants`
--
ALTER TABLE `adventure_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_participant_adventure` (`adventure_id`),
  ADD KEY `fk_participant_user` (`user_id`);

--
-- Indexes for table `adventure_posts`
--
ALTER TABLE `adventure_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_user` (`user_id`),
  ADD KEY `fk_post_adventure` (`adventure_id`);

--
-- Indexes for table `adventure_post_images`
--
ALTER TABLE `adventure_post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_image` (`post_id`);

--
-- Indexes for table `adventure_tags`
--
ALTER TABLE `adventure_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adventure_tags_adventure` (`adventure_id`);

--
-- Indexes for table `city_activities`
--
ALTER TABLE `city_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_friendship_one` (`user_one`),
  ADD KEY `fk_friendship_two` (`user_two`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_friend_sender` (`sender_id`),
  ADD KEY `fk_friend_receiver` (`receiver_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_time` (`email`,`attempted_at`),
  ADD KEY `idx_ip_time` (`ip_address`,`attempted_at`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `fk_remember_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_interests_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `adventures`
--
ALTER TABLE `adventures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `adventure_participants`
--
ALTER TABLE `adventure_participants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adventure_posts`
--
ALTER TABLE `adventure_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adventure_post_images`
--
ALTER TABLE `adventure_post_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adventure_tags`
--
ALTER TABLE `adventure_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=802;

--
-- AUTO_INCREMENT for table `city_activities`
--
ALTER TABLE `city_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=868;

--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_interests`
--
ALTER TABLE `user_interests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adventures`
--
ALTER TABLE `adventures`
  ADD CONSTRAINT `fk_adventures_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adventure_participants`
--
ALTER TABLE `adventure_participants`
  ADD CONSTRAINT `fk_participant_adventure` FOREIGN KEY (`adventure_id`) REFERENCES `adventures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_participant_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adventure_posts`
--
ALTER TABLE `adventure_posts`
  ADD CONSTRAINT `fk_post_adventure` FOREIGN KEY (`adventure_id`) REFERENCES `adventures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adventure_post_images`
--
ALTER TABLE `adventure_post_images`
  ADD CONSTRAINT `fk_post_image` FOREIGN KEY (`post_id`) REFERENCES `adventure_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adventure_tags`
--
ALTER TABLE `adventure_tags`
  ADD CONSTRAINT `fk_adventure_tags_adventure` FOREIGN KEY (`adventure_id`) REFERENCES `adventures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `fk_friendship_one` FOREIGN KEY (`user_one`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_friendship_two` FOREIGN KEY (`user_two`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `fk_friend_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_friend_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `fk_remember_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD CONSTRAINT `fk_user_interests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
