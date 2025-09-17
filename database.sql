-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 06:59 PM
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
-- Database: `healthsync_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL,
  `service` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `note` longtext DEFAULT NULL,
  `status` enum('PENDING','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `receipt_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `type`, `time`, `date`, `service`, `description`, `note`, `status`, `receipt_url`, `created_at`, `updated_at`) VALUES
(81, 13, 'clinic', '09:00:00', '2025-07-02', 'General Checkup', 'Routine health check.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(82, 13, 'clinic', '10:30:00', '2025-07-03', 'Blood Test', 'Blood sugar and cholesterol test.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(83, 13, 'clinic', '14:00:00', '2025-07-04', 'X-Ray', 'X-ray for left leg pain.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(84, 13, 'clinic', '11:00:00', '2025-07-05', 'Consultation', 'Discuss recurring headache.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(85, 13, 'clinic', '15:30:00', '2025-07-06', 'Vaccination', 'Scheduled vaccine dose.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-19 12:47:46'),
(86, 13, 'clinic', '08:30:00', '2025-07-07', 'Wound Dressing', 'Follow-up for minor injury.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(87, 13, 'clinic', '13:00:00', '2025-07-08', 'Blood Pressure', 'BP monitoring and logs.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-19 12:47:02'),
(88, 13, 'clinic', '16:00:00', '2025-07-09', 'Urine Test', 'Urinalysis after medication.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(89, 13, 'clinic', '10:00:00', '2025-07-10', 'COVID-19 Test', 'RT-PCR scheduled test.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(90, 13, 'clinic', '11:45:00', '2025-07-11', 'Dermatology', 'Skin rash consultation.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(91, 13, 'clinic', '09:15:00', '2025-06-15', 'ENT', 'Recurring sore throat.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(92, 13, 'clinic', '14:45:00', '2025-06-18', 'Eye Exam', 'Blurred vision issue.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(93, 13, 'clinic', '10:20:00', '2025-06-20', 'Dental', 'Toothache and cleaning.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(94, 13, 'clinic', '12:30:00', '2025-06-25', 'General Checkup', 'Annual checkup.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-19 12:47:25'),
(95, 13, 'clinic', '13:50:00', '2025-06-28', 'Orthopedic', 'Back pain consult.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(96, 13, 'clinic', '09:40:00', '2025-05-05', 'Psychiatry', 'Mental health session.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(97, 13, 'clinic', '11:10:00', '2025-05-10', 'Neurology', 'Memory issue evaluation.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(98, 13, 'clinic', '14:30:00', '2025-05-18', 'Cardiology', 'Heart checkup.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(99, 13, 'clinic', '16:20:00', '2025-05-25', 'Pulmonology', 'Breathing difficulty consult.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(100, 13, 'clinic', '10:00:00', '2025-05-30', 'Vaccination', 'Flu vaccine.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(101, 13, 'labtech', '08:00:00', '2025-07-01', 'CBC Test', 'Complete blood count analysis.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(102, 13, 'labtech', '10:00:00', '2025-07-02', 'Urinalysis', 'Routine urine sample.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(103, 13, 'labtech', '11:30:00', '2025-07-03', 'Blood Typing', 'Determine blood type.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(104, 13, 'labtech', '13:00:00', '2025-07-04', 'Glucose Test', 'Fasting blood sugar test.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(105, 13, 'labtech', '09:45:00', '2025-07-05', 'Cholesterol Test', 'Lipid panel.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(106, 13, 'labtech', '14:30:00', '2025-07-06', 'Stool Test', 'Stool culture test.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(107, 13, 'labtech', '15:15:00', '2025-07-07', 'Pregnancy Test', 'HCG blood test.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(108, 13, 'labtech', '16:00:00', '2025-07-08', 'Thyroid Panel', 'TSH, T3, T4 check.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(109, 13, 'labtech', '08:45:00', '2025-07-09', 'Liver Function', 'Test for ALT/AST.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(110, 13, 'labtech', '10:30:00', '2025-07-10', 'Kidney Function', 'Check BUN and creatinine.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(111, 13, 'labtech', '09:00:00', '2025-06-10', 'Hematocrit', 'Measure red blood cell volume.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(112, 13, 'labtech', '11:00:00', '2025-06-15', 'WBC Count', 'White blood cell test.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(113, 13, 'labtech', '13:30:00', '2025-06-20', 'Platelet Count', 'Platelet measurement.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(114, 13, 'labtech', '15:00:00', '2025-06-22', 'Malaria Smear', 'Check for malaria parasites.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(115, 13, 'labtech', '08:15:00', '2025-06-25', 'Drug Test', 'Pre-employment screening.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(116, 13, 'labtech', '09:10:00', '2025-05-03', 'Electrolyte Panel', 'Na, K, Cl, Bicarb levels.', NULL, 'CONFIRMED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(117, 13, 'labtech', '10:40:00', '2025-05-08', 'Coagulation Test', 'PT/INR tests.', NULL, 'CANCELLED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(118, 13, 'labtech', '12:20:00', '2025-05-12', 'ESR Test', 'Inflammation marker.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(119, 13, 'labtech', '14:10:00', '2025-05-16', 'Blood Gas Test', 'ABG analysis.', NULL, 'COMPLETED', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(120, 13, 'labtech', '16:40:00', '2025-05-28', 'Hormone Panel', 'Hormone imbalance screening.', NULL, 'PENDING', NULL, '2025-07-03 09:03:53', '2025-07-03 09:03:53'),
(121, 13, 'clinic', '09:45:00', '2025-07-17', 'Doctor Consultation', 'dawdawd', NULL, 'CANCELLED', NULL, '2025-07-12 01:42:14', '2025-07-19 12:46:08'),
(122, 13, 'clinic', '10:22:00', '2025-07-24', 'Doctor Consultation', 'bjiafbefiwaw', NULL, 'CONFIRMED', NULL, '2025-07-12 02:20:17', '2025-07-19 12:45:30'),
(123, 13, 'clinic', '00:23:00', '2025-07-23', 'Doctor Consultation', 'obfaob', 'Foo?', 'COMPLETED', NULL, '2025-07-12 02:23:15', '2025-07-12 02:24:53'),
(124, 13, 'labtech', '10:38:00', '2025-07-24', 'Urinalysis', 'lefwl', 'What?', 'COMPLETED', '/healthsync/storage/file_6871c9ece6ef12.97221976.png', '2025-07-12 02:35:24', '2025-07-28 11:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `name`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'BSCS', 'Bachelor of Science in Computer Science', '4', '2025-06-23 12:32:49', '2025-06-28 01:54:23'),
(2, 'BSIT', 'Bachelor of Science in Information Technology', '4', '2025-06-23 12:32:49', '2025-06-28 01:54:26'),
(3, 'BSIS', 'Bachelor of Science in Information Systems', '4', '2025-06-23 12:32:49', '2025-06-28 01:54:28'),
(45, 'CEA', 'College of Engineering and Architecture', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(46, 'CEA', 'College of Engineering and Architecture', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(47, 'CCJE', 'College of Criminal Justice Education', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(48, 'CCJE', 'College of Criminal Justice Education', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(49, 'CAS', 'College of Arts and Sciences', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(50, 'CAS', 'College of Arts and Sciences', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(51, 'CTE', 'College of Teachers Education', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(52, 'CTE', 'College of Teachers Education', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(53, 'CHS', 'College of Human Sciences', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(54, 'CHS', 'College of Human Sciences', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(55, 'CITE', 'College of Information Technology Education', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(56, 'CITE', 'College of Information Technology Education', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(57, 'COP', 'College of Pharmacy', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(58, 'COP', 'College of Pharmacy', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(59, 'CHTM', 'College of Hospitality and Tourism Management', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(60, 'CHTM', 'College of Hospitality and Tourism Management', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(61, 'CBMA', 'College of Business Management and Accountancy', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(62, 'CBMA', 'College of Business Management and Accountancy', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(63, 'COHS', 'College of Health Sciences', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(64, 'COHS', 'College of Health Sciences', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(65, 'HRD', 'Human Resource Department', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(66, 'HRD', 'Human Resource Department', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(67, 'FIN', 'Finance and Accounting Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(68, 'FIN', 'Finance and Accounting Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(69, 'REG', 'Registrar\'s Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(70, 'REG', 'Registrar\'s Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(71, 'LIB', 'Library Services', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(72, 'LIB', 'Library Services', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(73, 'ICT', 'Information and Communications Technology Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(74, 'ICT', 'Information and Communications Technology Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(75, 'SAO', 'Student Affairs Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(76, 'SAO', 'Student Affairs Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(77, 'MAINT', 'Maintenance and Physical Facilities', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(78, 'MAINT', 'Maintenance and Physical Facilities', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(79, 'SEC', 'Campus Security Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(80, 'SEC', 'Campus Security Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(81, 'CLINIC', 'Medical and Dental Clinic', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(82, 'CLINIC', 'Medical and Dental Clinic', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(83, 'RDO', 'Research and Development Office', '5', '2025-06-28 06:48:49', '2025-06-28 06:48:49'),
(84, 'RDO', 'Research and Development Office', '6', '2025-06-28 06:48:49', '2025-06-28 06:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `stocks` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `type`, `stocks`, `image`, `created_at`, `updated_at`) VALUES
(6, 'woewfaowefb', 'obfowaebfo', 2, '/healthsync/storage/file_687b927fe82172.25442068.svg', '2025-07-19 12:41:35', '2025-07-19 12:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `result_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `appointment_id`, `result_url`, `created_at`, `updated_at`) VALUES
(24, 124, '/healthsync/storage/file_6887628a6077e0.71104634.png', '2025-07-28 11:44:10', '2025-07-28 11:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `stocks` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `expiration_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `type`, `stocks`, `image`, `expiration_date`, `created_at`, `updated_at`) VALUES
(14, 'sjfsjf', 'odbqwdouq', 500, NULL, '2022-12-12', '2025-07-19 12:38:47', '2025-07-19 12:40:18');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_complaints`
--

CREATE TABLE `monthly_complaints` (
  `id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `month` varchar(7) NOT NULL,
  `Fever` int(11) DEFAULT 0,
  `Headache` int(11) DEFAULT 0,
  `Cough` int(11) DEFAULT 0,
  `Colds` int(11) DEFAULT 0,
  `Allergy` int(11) DEFAULT 0,
  `Abdominal Cramps` int(11) DEFAULT 0,
  `Menstrual Cramps` int(11) DEFAULT 0,
  `Diarrhea` int(11) DEFAULT 0,
  `Muscle Pain` int(11) DEFAULT 0,
  `Toothache` int(11) DEFAULT 0,
  `Epigastric Pain` int(11) DEFAULT 0,
  `Tonsillitis` int(11) DEFAULT 0,
  `Wound` int(11) DEFAULT 0,
  `Vertigo` int(11) DEFAULT 0,
  `Sprain` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monthly_complaints`
--

INSERT INTO `monthly_complaints` (`id`, `department`, `month`, `Fever`, `Headache`, `Cough`, `Colds`, `Allergy`, `Abdominal Cramps`, `Menstrual Cramps`, `Diarrhea`, `Muscle Pain`, `Toothache`, `Epigastric Pain`, `Tonsillitis`, `Wound`, `Vertigo`, `Sprain`) VALUES
(1, 'College of Engineering & Architecture', '2025-06', 20, 500, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'College of Criminal Justice Education', '2025-06', 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'College of Arts & Sciences', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'College of Teacher Education', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'College of Human Sciences', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'College of Information Technology Education', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'College of Pharmacy', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'College of Hospitality/Tourism Management', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'College of Business Management & Accountancy', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'College of Health Sciences', '2025-06', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'College of Engineering & Architecture', '2025-07', 299, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'College of Criminal Justice Education', '2025-07', 30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 'College of Arts & Sciences', '2025-07', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 'College of Teacher Education', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 'College of Human Sciences', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 'College of Information Technology Education', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 'College of Pharmacy', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 'College of Hospitality/Tourism Management', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 'College of Business Management & Accountancy', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 'College of Health Sciences', '2025-07', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, 'College of Engineering & Architecture', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 'College of Criminal Justice Education', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, 'College of Arts & Sciences', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, 'College of Teacher Education', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(25, 'College of Human Sciences', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 'College of Information Technology Education', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 'College of Pharmacy', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 'College of Hospitality/Tourism Management', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 'College of Business Management & Accountancy', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(30, 'College of Health Sciences', '2025-05', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 'College of Engineering & Architecture', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 'College of Criminal Justice Education', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 'College of Arts & Sciences', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 'College of Teacher Education', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 'College of Human Sciences', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 'College of Information Technology Education', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 'College of Pharmacy', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 'College of Hospitality/Tourism Management', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 'College of Business Management & Accountancy', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 'College of Health Sciences', '2025-04', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 'College of Engineering & Architecture', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 'College of Criminal Justice Education', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 'College of Arts & Sciences', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(44, 'College of Teacher Education', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(45, 'College of Human Sciences', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(46, 'College of Information Technology Education', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(47, 'College of Pharmacy', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(48, 'College of Hospitality/Tourism Management', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(49, 'College of Business Management & Accountancy', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(50, 'College of Health Sciences', '2025-09', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `recipient_id`, `sender_id`, `type`, `content`, `url`, `is_read`, `created_at`, `updated_at`) VALUES
(172, 10, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=93', 1, '2025-07-12 01:42:14', '2025-07-12 01:42:14'),
(173, 12, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=93', 0, '2025-07-12 01:42:14', '2025-07-12 01:42:14'),
(174, 10, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=10', 1, '2025-07-12 02:20:17', '2025-07-12 02:20:17'),
(175, 12, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=12', 0, '2025-07-12 02:20:17', '2025-07-12 02:20:17'),
(176, 10, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=123', 1, '2025-07-12 02:23:15', '2025-07-12 02:23:15'),
(177, 12, 13, 'APPOINTMENT', 'New appointment incoming', './admin/appointments.php?id=123', 0, '2025-07-12 02:23:15', '2025-07-12 02:23:15'),
(178, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=123', 1, '2025-07-12 02:24:48', '2025-07-12 02:24:48'),
(179, 13, 10, 'APPOINTMENT', 'Your appointment has been COMPLETED.', '/users/appointment/view.php?id=123', 1, '2025-07-12 02:24:53', '2025-07-12 02:24:53'),
(180, 14, 13, 'APPOINTMENT', 'New appointment incoming', './users/labtech/appointments.php?id=124', 1, '2025-07-12 02:35:24', '2025-07-12 02:35:24'),
(181, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-12 06:51:28', '2025-07-12 06:51:28'),
(182, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 11:42:40', '2025-07-19 11:42:40'),
(183, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 11:48:09', '2025-07-19 11:48:09'),
(184, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 11:50:37', '2025-07-19 11:50:37'),
(185, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 12:01:09', '2025-07-19 12:01:09'),
(186, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 12:03:09', '2025-07-19 12:03:09'),
(187, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 12:03:52', '2025-07-19 12:03:52'),
(188, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 12:04:38', '2025-07-19 12:04:38'),
(189, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-19 12:06:43', '2025-07-19 12:06:43'),
(190, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:15', '2025-07-19 12:45:15'),
(191, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:16', '2025-07-19 12:45:16'),
(192, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:16', '2025-07-19 12:45:16'),
(193, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:16', '2025-07-19 12:45:16'),
(194, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:16', '2025-07-19 12:45:16'),
(195, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:16', '2025-07-19 12:45:16'),
(196, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(197, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(198, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(199, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(200, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(201, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:17', '2025-07-19 12:45:17'),
(202, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:23', '2025-07-19 12:45:23'),
(203, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:23', '2025-07-19 12:45:23'),
(204, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:24', '2025-07-19 12:45:24'),
(205, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:24', '2025-07-19 12:45:24'),
(206, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=122', 0, '2025-07-19 12:45:30', '2025-07-19 12:45:30'),
(207, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:06', '2025-07-19 12:46:06'),
(208, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(209, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(210, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(211, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(212, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(213, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:07', '2025-07-19 12:46:07'),
(214, 13, 10, 'APPOINTMENT', 'Your appointment has been CANCELLED.', '/users/appointment/view.php?id=121', 0, '2025-07-19 12:46:08', '2025-07-19 12:46:08'),
(215, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=87', 0, '2025-07-19 12:47:02', '2025-07-19 12:47:02'),
(216, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=94', 0, '2025-07-19 12:47:25', '2025-07-19 12:47:25'),
(217, 13, 10, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=85', 0, '2025-07-19 12:47:46', '2025-07-19 12:47:46'),
(218, 13, 14, 'APPOINTMENT', 'Your appointment has been CONFIRMED.', '/users/appointment/view.php?id=124', 0, '2025-07-28 11:43:38', '2025-07-28 11:43:38'),
(219, 13, 14, 'APPOINTMENT', 'Your appointment has been COMPLETED.', '/users/appointment/view.php?id=124', 1, '2025-07-28 11:44:10', '2025-07-28 11:44:10'),
(220, 10, NULL, 'NEW USER', 'New user request pending verification.', NULL, 0, '2025-07-28 11:55:45', '2025-07-28 11:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'doctor', '2025-06-23 12:48:52', '2025-06-23 12:48:52'),
(2, 'Laboratory Technician', '2025-06-23 12:48:52', '2025-06-26 01:57:29'),
(3, 'nurse', '2025-06-23 12:48:52', '2025-06-23 12:48:52'),
(4, 'student', '2025-06-23 12:48:52', '2025-06-23 12:48:52'),
(5, 'employee teaching', '2025-06-23 12:48:52', '2025-06-29 05:23:21'),
(6, 'employee  non-teaching', '2025-06-23 12:48:52', '2025-06-29 05:23:28'),
(7, 'dentist', '2025-09-09 16:56:15', '2025-09-09 16:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` longtext NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` longtext NOT NULL,
  `role` int(11) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE','PENDING','REJECTED') NOT NULL DEFAULT 'PENDING',
  `image_url` varchar(255) DEFAULT NULL,
  `password_reset_token` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_number`, `first_name`, `middle_name`, `last_name`, `address`, `phone_number`, `year`, `course`, `gender`, `email_address`, `password`, `role`, `status`, `image_url`, `password_reset_token`, `created_at`, `updated_at`) VALUES
(10, '2025001', 'Admin', 'Bar', 'Baz', '123 Sample St., Baguio City', '0999999999', 1, 1, 'MALE', 'admin@healthsync.com', '$2y$12$mRAJs/a3dPn00LYusrsgl./JNmTs92NREc.BpFcy5lhTkUF.j.z4y', 1, 'ACTIVE', NULL, NULL, '2025-06-24 10:33:49', '2025-07-02 08:16:15'),
(12, '2025010101', 'Nurse', 'Foo', 'Bar', '123 Sample St., Baguio City', '0999999999', 1, 1, 'MALE', 'nurse@healthsync.com', '$2y$12$JyqDG90rM4WZcxxsnF2PCuWA02269Sbosksd4AgAl/HjSIUE/j9sm', 3, 'ACTIVE', NULL, NULL, '2025-06-25 12:48:49', '2025-06-29 11:13:19'),
(13, '0404040404', 'Student', 'Bar', 'Baz', '123 Sample St., Baguio City', '0999999999', 4, 1, 'MALE', 'student@healthsync.com', '$2y$12$K2qIuQvZPULP2qkk3AHcc.bKqP53x6CoplNxng3wgE2NvrVYgCHY.', 4, 'ACTIVE', NULL, NULL, '2025-06-26 02:40:02', '2025-06-29 11:13:07'),
(14, '23321037', 'Labtech', 'Bar', 'fapehfBaz', '123 Sample St., Baguio City', '0999999999', 3, 1, 'MALE', 'labtech@healthsync.com', '$2y$12$L/SgXS3Tv3/Ix1DkRTUrYuEEjad2PNRnaTNrg94PyyZX05Q4KGLzy', 2, 'ACTIVE', NULL, NULL, '2025-06-26 06:47:24', '2025-06-29 11:13:17'),
(73, '23321032444', 'Dentist', 'Dentist', 'Dentist', '123 Sample St., Baguio City', '0999999999', 3, 1, 'MALE', 'dentist@healthsync.com', '$2y$12$L/SgXS3Tv3/Ix1DkRTUrYuEEjad2PNRnaTNrg94PyyZX05Q4KGLzy', 7, 'ACTIVE', NULL, NULL, '2025-06-26 06:47:24', '2025-06-29 11:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '1st Year', '2025-06-23 12:37:37', '2025-06-23 12:37:37'),
(2, '2nd Year', '2025-06-23 12:37:37', '2025-06-23 12:37:37'),
(3, '3rd Year', '2025-06-23 12:37:37', '2025-06-23 12:37:37'),
(4, '4th Year', '2025-06-23 12:37:37', '2025-06-23 12:37:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_activity_user_id` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lab_appointment_result_id` (`appointment_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_complaints`
--
ALTER TABLE `monthly_complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_entry` (`department`,`month`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recipient_id` (`recipient_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email_address`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD KEY `fk_user_year` (`year`),
  ADD KEY `fk_user_course` (`course`),
  ADD KEY `fk_user_role` (`role`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `monthly_complaints`
--
ALTER TABLE `monthly_complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `fk_lab_appointment_result_id` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_recipient_id` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_course` FOREIGN KEY (`course`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_year` FOREIGN KEY (`year`) REFERENCES `year` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
