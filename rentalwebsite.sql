-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 09:17 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentalwebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('paid','unpaid') DEFAULT 'unpaid',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL,
  `car_type` varchar(50) NOT NULL,
  `additional_features` text DEFAULT NULL,
  `license` varchar(50) NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `vehicle_id`, `start_date`, `end_date`, `status`, `created_at`, `payment_status`, `full_name`, `email`, `phone`, `pickup_location`, `dropoff_location`, `car_type`, `additional_features`, `license`, `age`) VALUES
(59, 1, 17, '2025-03-01', '2025-03-08', 'Approved', '2025-02-25 21:16:03', 'paid', 'Aonosumika', 'Aonosumika@gmail.com', '080872112321', 'Downtown Manitoba', 'Downtown Manitoba', '', NULL, '098121321312', 23),
(60, 1, 17, '2025-03-06', '2025-03-08', 'Approved', '2025-02-26 20:06:29', 'paid', 'Closer', 'closer@gmail.com', '091863943', 'Polo Park', 'Polo Park', '', NULL, '78212312321', 20),
(61, 1, 17, '2025-03-10', '2025-03-12', 'Approved', '2025-02-26 20:16:49', 'paid', 'Damn', 'damn@gmail.com', '092123131543', 'Garden City', 'Garden City', '', NULL, '82642453', 24),
(62, 1, 17, '2025-03-07', '2025-03-08', 'Pending', '2025-02-27 03:00:54', 'unpaid', 'Aggreement testing', 'Aggreement@gmail.com', '0912842421', 'Outlet Collection', 'Outlet Collection', '', NULL, '62347121', 21);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `orderID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `vehicle_id`, `amount`, `payment_date`, `orderID`) VALUES
(40, 1, 17, '100.00', '2025-02-26 19:58:55', NULL),
(41, 1, 17, '100.00', '2025-02-26 20:09:08', NULL),
(42, 1, 17, '100.00', '2025-02-26 20:18:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('renter','host') NOT NULL DEFAULT 'renter',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'Josh Josol', 'joshjosol20@gmail.com', '$2y$10$lXBQnwfe1kZx8YV32bJXv.7VpJQLPmfF8G61/McvyeRIHUOmdJrt.', 'renter', '2024-12-30 21:39:09', '2024-12-30 21:39:09', 0),
(2, 'Josh Nathan', 'josoljosh00@gmail.com', '$2y$10$w/YgNUtZT6c3vNaX60sgOuG6e7ixn3De/HCHvOKlCTpda6qapk2Du', 'host', '2024-12-30 21:40:03', '2024-12-30 21:40:03', 0),
(3, 'Nelson Mailom', 'japtee22@gmail.com', '$2y$10$aJ9pq.XrCys6zQTS6Usz.uPdQzQPl.SGjB.cRi.mro0xA//blG2pS', '', '2025-01-02 00:55:35', '2025-01-02 00:58:00', 1),
(4, 'after changes', 'afterchanges@gmail.com', '$2y$10$FYU3SULgHHv8SL0.CrzJx.7Lb1Gzv.DUOdHOOjQU4oLOlZv9R5222', 'renter', '2025-01-05 21:11:40', '2025-01-05 21:11:40', 0),
(5, 'Don Mailom', 'don@gmail.com', '$2y$10$4EWMkKCa6ORvbG8Y5do6/edrbjaIJM9T7SkvirehJtQTKoWktM2Pa', 'host', '2025-01-05 21:20:16', '2025-01-05 21:20:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `ramp_type` varchar(100) NOT NULL,
  `securement_system` varchar(100) NOT NULL,
  `num_wheelchair` int(11) NOT NULL,
  `height_clearance` int(11) NOT NULL,
  `seating_config` int(11) NOT NULL,
  `drive_from_wc` varchar(10) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `availability` tinyint(1) NOT NULL DEFAULT 0,
  `category` varchar(255) NOT NULL,
  `vin` varchar(255) NOT NULL,
  `accessibility_type` varchar(255) NOT NULL,
  `interior_height` decimal(10,2) NOT NULL,
  `door_clearance` decimal(10,2) NOT NULL,
  `ramp_lift_width` decimal(10,2) NOT NULL,
  `registration_document` varchar(255) NOT NULL,
  `insurance_document` varchar(255) NOT NULL,
  `inspection_report` varchar(255) NOT NULL,
  `vehicle_history` text DEFAULT NULL,
  `wheelchair_securement_system` varchar(255) NOT NULL,
  `num_wheelchair_positions` int(11) NOT NULL,
  `ramp_or_lift_functional` tinyint(1) NOT NULL,
  `safety_features` text NOT NULL,
  `emergency_equipment` text NOT NULL,
  `roadworthiness` tinyint(1) NOT NULL,
  `photos` text NOT NULL,
  `rental_rates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`rental_rates`)),
  `rules_and_restrictions` text NOT NULL,
  `availability_schedule` text NOT NULL,
  `approval_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `user_id`, `make`, `year`, `model`, `fuel_type`, `transmission`, `ramp_type`, `securement_system`, `num_wheelchair`, `height_clearance`, `seating_config`, `drive_from_wc`, `date_added`, `availability`, `category`, `vin`, `accessibility_type`, `interior_height`, `door_clearance`, `ramp_lift_width`, `registration_document`, `insurance_document`, `inspection_report`, `vehicle_history`, `wheelchair_securement_system`, `num_wheelchair_positions`, `ramp_or_lift_functional`, `safety_features`, `emergency_equipment`, `roadworthiness`, `photos`, `rental_rates`, `rules_and_restrictions`, `availability_schedule`, `approval_status`) VALUES
(6, 2, 'DGC', 1905, 'new TESTING', 'Electric', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'Yes', '2025-01-02 10:02:35', 1, 'MINIVAN', '1HGCM82633A123456', 'Rear-entry lift', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'TESTING', 'Docking system', 1, 0, '2', '2', 1, 'uploads/car seat.jpg', '1', '1', '1', 'approved'),
(7, 2, 'DGC', 1905, 'JOSH', 'Electric', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'Yes', '2025-01-02 10:03:05', 1, 'FULL SIZE  VAN', '1HGCM82633A123456', 'Rear-entry lift', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'TESTING', 'Docking system', 1, 0, '2', '2', 1, 'uploads/car seat.jpg', '1', '1', '1', 'pending'),
(8, 2, 'DGC', 1900, 'TESTING APPROVAL', 'Gasoline', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'Yes', '2025-01-03 02:49:22', 1, 'MINIVAN', '1HGCM82633A123456', 'Rear-entry lift', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'TESTING', 'Docking system', 1, 0, 'TESTING', 'TESTING', 0, 'uploads/car seat.jpg', '2', 'TESTING', '1', 'approved'),
(9, 2, 'Honda', 1916, 'Testing Nelson', 'Diesel', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'No', '2025-01-03 03:00:57', 1, 'FULL SIZE  VAN', '1HGCM82633A123456', 'Rear-entry lift', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', '1', 'Docking system', 1, 0, '1', '1', 1, 'uploads/car seat.jpg', '2', '1', '1', 'approved'),
(13, 5, 'Honda', 2021, 'Odyssey', 'Gasoline', 'Automatic', 'Foldable', 'Tie-down straps', 1, 56, 2, 'Yes', '2025-01-03 06:13:05', 8, 'MINIVAN', '2HKRM3H71MH123456', 'Side-entry ramp', '56.00', '30.00', '32.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'One previous owner, regularly maintained, no accidents.', 'Tie-down straps', 1, 0, 'Airbags, ABS, Stability control', 'Fire extinguisher, first aid kit, hazard lights', 0, 'uploads/car seat.jpg', '80', 'No smoking, no pets allowed, must return vehicle with full tank of gas', '8 AM to 6 PM', 'approved'),
(14, 5, 'Toyota', 2020, 'Sienna', 'Gasoline', 'Automatic', 'Foldable', 'Tie-down straps', 2, 58, 3, 'No', '2025-01-03 06:18:29', 9, 'FULL SIZE  VAN', '5TDYZ3DC4LS123789', 'Side-entry ramp', '60.00', '33.00', '34.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'Previously owned by a family, accident-free, regularly serviced.', 'Tie-down straps', 2, 0, 'Airbags, Anti-lock brakes (ABS), Lane departure warning, Blind-spot monitoring', 'Fire extinguisher, first aid kit, warning triangle', 0, 'uploads/drive.jpg', '95', 'No smoking, no off-road driving, must return vehicle with full tank of gas', '9 AM to 5 PM', 'approved'),
(15, 5, 'Honda', 1917, 'Upload', 'Electric', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'Yes', '2025-01-06 04:21:47', 1, 'FULL SIZE  VAN', '5TDYZ3DC4LS123789', 'Rear-entry lift', '1.00', '1.00', '1.00', 'uploads/HOST REQUIREMENTS.pdf', 'uploads/HOST REQUIREMENTS.pdf', 'uploads/HOST REQUIREMENTS.pdf', '1', 'Docking system', 1, 0, '1', '1', 1, 'uploads/drive.jpg', '1', '1', '1', 'approved'),
(16, 2, 'Honda', 1900, 'TESTING', 'Diesel', 'Automatic', 'Fixed', 'Tie-down straps', 1, 1, 1, 'No', '2025-01-09 10:09:58', 1, 'FULL SIZE  VAN', '5TDYZ3DC4LS123789', 'Side-entry ramp', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', '1', 'Docking system', 1, 0, '1', '1', 1, 'uploads/inventory.png', '1', '1', '1', 'approved'),
(17, 2, 'Other', 1900, 'Come back', 'Gasoline', 'Automatic', 'Foldable', 'Tie-down straps', 1, 1, 1, 'Yes', '2025-02-02 06:23:18', 2, 'MINIVAN', '5TDYZ3DC4LS123789', 'Side-entry ramp', '1.00', '1.00', '1.00', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', 'uploads/file-sample_150kB.pdf', '1', 'Tie-down straps', 1, 0, '1', '1', 1, 'uploads/infinity car.jpg', '1', '1', '1', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
