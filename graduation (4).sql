-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 12:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `graduation`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'معرف المستخدم',
  `action` varchar(255) NOT NULL COMMENT 'الإجراء المنفذ',
  `table_name` varchar(100) DEFAULT NULL COMMENT 'اسم الجدول',
  `record_id` int(11) DEFAULT NULL COMMENT 'معرف السجل',
  `details` text DEFAULT NULL COMMENT 'تفاصيل إضافية (JSON)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'عنوان IP',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `table_name`, `record_id`, `details`, `ip_address`, `created_at`) VALUES
(1, 20, 'UPDATE', 'dorms', 40, 'تم تحديث السكن: The book', '::1', '2025-12-12 20:44:02'),
(2, 20, 'UPDATE', 'dorms', 40, 'تم تحديث السكن: The book', '::1', '2025-12-12 20:44:08'),
(3, 20, 'UPDATE', 'booking', 12, 'تم تحديث حالة الحجز إلى: Confirmed', '::1', '2025-12-12 21:51:31'),
(4, 20, 'UPDATE', 'booking', 16, 'تم تحديث حالة الحجز إلى: Cancelled', '::1', '2025-12-12 21:52:03'),
(5, 20, 'UPDATE', 'booking', 14, 'تم تحديث حالة الحجز إلى: Cancelled', '::1', '2025-12-12 21:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `amenity_id` int(11) NOT NULL,
  `amenity_name` varchar(100) NOT NULL,
  `amenity_name_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amenity_id`, `amenity_name`, `amenity_name_ar`) VALUES
(1, 'TV', 'تكييف'),
(2, '24/7 Security', 'حراسة 24/7'),
(3, 'Gym', 'صالة رياضية'),
(4, 'Pool', 'مسبح'),
(5, 'Parking', 'مواقف سيارات'),
(6, 'WIFI', 'واي فاي'),
(7, 'Pet friendly', 'غسالة ملابس'),
(8, 'Beds', 'تدفئة'),
(9, 'Kitchen', 'مطبخ'),
(10, 'Private Bathrooms', 'تلفزيون');

-- --------------------------------------------------------

--
-- Stand-in structure for view `available_rooms_view`
-- (See below for the actual view)
--
CREATE TABLE `available_rooms_view` (
`room_id` int(11)
,`dorm_id` int(11)
,`dorm_name` varchar(255)
,`floor_number` int(11)
,`floor_name` varchar(50)
,`room_number` varchar(20)
,`room_type` varchar(50)
,`position` enum('front','back','side','corner')
,`view_type` varchar(50)
,`capacity` int(11)
,`current_occupancy` int(11)
,`price_per_month` decimal(10,2)
,`available_spots` bigint(12)
);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `dorm_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `booking_duration_months` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `email_sent` tinyint(1) DEFAULT 0,
  `notification_sent` tinyint(1) DEFAULT 0,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `national_id` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `booking_type` enum('full_room','shared_spot') DEFAULT 'full_room' COMMENT 'full_room = حجز الغرفة كاملة, shared_spot = حجز مكان واحد فقط',
  `looking_for_roommate` tinyint(1) DEFAULT 0 COMMENT '1 = يبحث عن زميل غرفة, 0 = لا يبحث',
  `roommate_preferences` text DEFAULT NULL COMMENT 'تفضيلات زميل الغرفة (JSON format)',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_notes` text DEFAULT NULL COMMENT 'ملاحظات الإدارة',
  `owner_notes` text DEFAULT NULL COMMENT 'ملاحظات صاحب السكن'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `dorm_id`, `student_id`, `room_type`, `room_id`, `check_in_date`, `check_out_date`, `booking_duration_months`, `total_price`, `payment_method`, `booking_date`, `status`, `email_sent`, `notification_sent`, `full_name`, `email`, `national_id`, `gender`, `phone`, `booking_type`, `looking_for_roommate`, `roommate_preferences`, `updated_at`, `admin_notes`, `owner_notes`) VALUES
(8, 7, 15, 'Single', NULL, '2025-08-31', '2025-12-01', 3, 450.00, 'Cash', '2025-08-07 16:31:00', 'unaccepted', 0, 0, 'esraa omar', 'esraa123@gmail.com', '120212231108', 'Female', '077989', 'full_room', 0, NULL, '2025-12-12 19:44:29', NULL, NULL),
(9, 21, 17, 'Double', NULL, '2025-08-31', '2025-12-01', 3, 600.00, 'Cash', '2025-08-08 20:09:41', 'accepted', 0, 0, 'omar omar', 'omar123@gmail.com', '12345678', 'Male', '0799999', 'full_room', 0, NULL, '2025-12-12 19:44:29', NULL, NULL),
(11, 5, 15, 'Double', NULL, '2025-10-17', '2026-01-17', 3, 900.00, 'Cash', '2025-10-17 18:31:03', 'Pending', 0, 0, 'omar omar', 'esraa123@gmail.com', '120212231108', 'Female', '0798546382', 'full_room', 0, NULL, '2025-12-12 19:44:29', NULL, NULL),
(12, 13, 15, 'Single', NULL, '2025-10-19', '2026-01-19', 3, 1500.00, 'Cash', '2025-10-19 06:05:27', 'Confirmed', 0, 0, 'omar omar', 'esraa123@gmail.com', '120212231108', 'Female', '0785156617', 'full_room', 0, NULL, '2025-12-12 21:51:30', '', NULL),
(14, 26, 15, 'Double', NULL, '2025-11-04', '2026-02-04', 3, 675.00, 'Cash', '2025-11-04 18:50:07', 'Cancelled', 0, 0, 'islam alqisi', 'esraa123@gmail.com', '120212231108', 'Male', '0798546382', 'full_room', 0, NULL, '2025-12-12 21:52:18', '', NULL),
(16, 17, 15, 'Double', NULL, '2025-11-08', '2026-02-08', 3, 825.00, 'Cash', '2025-11-08 18:30:18', 'Cancelled', 0, 0, 'esraa omar', 'esraa123@gmail.com', '1234567890', 'Female', '0785156617', 'full_room', 0, NULL, '2025-12-12 21:52:03', '', NULL),
(17, 11, 17, 'Double', 395, '2025-12-12', '2026-03-12', 3, 810.00, 'Cash', '2025-12-12 19:13:28', 'Pending', 0, 0, 'omar omar', 'omar123@gmail.com', '120212231108', 'Female', '0797773789', 'shared_spot', 1, NULL, '2025-12-12 19:44:29', NULL, NULL),
(19, 2, 15, 'Single', 45, '2025-12-13', '2026-03-13', 3, 1440.00, 'Cash', '2025-12-12 22:01:04', 'Confirmed', 0, 0, 'esraa omar', 'esraa123@gmail.com', '120212231108', 'Female', '0785156617', 'full_room', 0, NULL, '2025-12-12 22:02:47', NULL, ''),
(20, 7, 15, 'Double', 254, '2025-12-13', '2026-02-13', 2, 320.00, 'Cash', '2025-12-12 22:44:08', 'Confirmed', 0, 0, 'esraa omar', 'esraa123@gmail.com', '120212231108', 'Female', '0785156617', 'shared_spot', 1, NULL, '2025-12-12 22:48:32', NULL, ''),
(21, 11, 17, 'Double', 407, '2025-12-14', '2026-03-14', 3, 810.00, 'Cash', '2025-12-14 03:51:31', 'Pending', 0, 0, 'omar omar', 'omar123@gmail.com', '120212231108', 'Female', '0785156617', 'shared_spot', 1, NULL, '2025-12-14 03:51:31', NULL, NULL),
(22, 2, 15, 'Triple', 53, '2025-12-20', '2026-02-20', 2, 820.00, 'Cash', '2025-12-20 17:06:49', 'Confirmed', 0, 0, 'MOHAMMAD ISMAIL', 'esraa123@gmail.com', '120212231108', 'Female', '0785156617', 'shared_spot', 1, NULL, '2025-12-20 17:07:21', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `dorm_id` int(11) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `number_of_stars` int(1) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL COMMENT 'معرف المستخدم',
  `status` enum('approved','pending','rejected') DEFAULT 'approved' COMMENT 'حالة التعليق'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `dorm_id`, `name`, `number_of_stars`, `comment`, `created_at`, `user_id`, `status`) VALUES
(1, 1, 'Ahmed Tarawneh', 5, 'Al-Redwan Dorm exceeded my expectations! The facilities are top-notch, and the staff is incredibly helpful. I felt safe and comfortable throughout my stay.', '2025-11-04 14:04:12', NULL, 'approved'),
(2, 1, 'Leen Alkhatteb', 4, 'Al-Manzel Dorm is a great place to live if you\'re looking for a quiet and focused environment. The rooms are well-maintained, and the study areas are perfect for getting work done.', '2025-11-04 14:04:12', NULL, 'approved'),
(3, 1, 'Mohammed Alqisi', 5, 'Al-yousef Dorm offers a vibrant community atmosphere. I made so many friends here, and the social events were always a blast. Highly recommend!', '2025-11-04 14:04:12', NULL, 'approved'),
(37, 1, 'Khaled Ahmad', 5, 'Excellent dorm with great facilities and friendly staff!', '2025-11-04 14:04:14', NULL, 'approved'),
(38, 1, 'Sara Mohammed', 4, 'Very clean and comfortable. The location is perfect for students.', '2025-11-04 14:04:14', NULL, 'approved'),
(39, 1, 'Ali Hassan', 5, 'Best dorm experience ever! Highly recommended.', '2025-11-04 14:04:14', NULL, 'approved'),
(40, 1, 'Noor Khalil', 4, 'Good value for money. The rooms are spacious.', '2025-11-04 14:04:14', NULL, 'approved'),
(41, 1, 'Omar Yousef', 5, 'Amazing place! The Wi-Fi is super fast.', '2025-11-04 14:04:14', NULL, 'approved'),
(42, 1, 'Lina Tariq', 5, 'Love the community here. Made so many friends!', '2025-11-04 14:04:14', NULL, 'approved'),
(43, 1, 'Fadi Nasser', 4, 'Great study environment. Very quiet at night.', '2025-11-04 14:04:14', NULL, 'approved'),
(44, 2, 'Reem Abdullah', 5, 'Perfect for serious students. Great study rooms!', '2025-11-04 14:04:14', NULL, 'approved'),
(45, 2, 'Tariq Salem', 4, 'Clean and well-maintained. Staff is very helpful.', '2025-11-04 14:04:14', NULL, 'approved'),
(46, 2, 'Huda Fares', 5, 'Excellent security and safety measures.', '2025-11-04 14:04:14', NULL, 'approved'),
(47, 2, 'Youssef Amin', 4, 'Good location near the university.', '2025-11-04 14:04:14', NULL, 'approved'),
(48, 2, 'Layla Majed', 5, 'The kitchen facilities are top-notch!', '2025-11-04 14:04:14', NULL, 'approved'),
(49, 2, 'Sami Rashed', 4, 'Comfortable rooms with good ventilation.', '2025-11-04 14:04:14', NULL, 'approved'),
(50, 3, 'Dina Waleed', 5, 'Vibrant community! Always something fun happening.', '2025-11-04 14:04:14', NULL, 'approved'),
(51, 3, 'Majed Karim', 4, 'Great social atmosphere. Made lifelong friends here.', '2025-11-04 14:04:14', NULL, 'approved'),
(52, 3, 'Hala Ziad', 5, 'The common areas are beautifully designed.', '2025-11-04 14:04:14', NULL, 'approved'),
(53, 3, 'Rami Fouad', 5, 'Best dorm for extroverted students!', '2025-11-04 14:04:14', NULL, 'approved'),
(54, 3, 'Nada Samer', 4, 'Love the events they organize every week.', '2025-11-04 14:04:14', NULL, 'approved'),
(55, 3, 'Bashar Tamer', 5, 'Excellent facilities and friendly residents.', '2025-11-04 14:04:14', NULL, 'approved'),
(56, 4, 'Aya Nabil', 5, 'Peaceful and quiet. Perfect for studying.', '2025-11-04 14:04:14', NULL, 'approved'),
(57, 4, 'Zaid Munir', 4, 'Very affordable with good amenities.', '2025-11-04 14:04:14', NULL, 'approved'),
(58, 4, 'Maha Adel', 5, 'Clean rooms and helpful management.', '2025-11-04 14:04:14', NULL, 'approved'),
(59, 4, 'Hamza Jalal', 4, 'Good security and safe environment.', '2025-11-04 14:04:14', NULL, 'approved'),
(60, 4, 'Rana Samir', 5, 'Love the location! Close to everything.', '2025-11-04 14:04:14', NULL, 'approved'),
(61, 5, 'Kareem Hani', 5, 'Modern facilities and great maintenance.', '2025-11-04 14:04:14', NULL, 'approved'),
(62, 5, 'Salma Wael', 4, 'Comfortable and clean. Good value.', '2025-11-04 14:04:14', NULL, 'approved'),
(63, 5, 'Firas Emad', 5, 'Excellent Wi-Fi and study spaces.', '2025-11-04 14:04:14', NULL, 'approved'),
(64, 5, 'Dalia Rami', 4, 'Friendly staff and nice community.', '2025-11-04 14:04:14', NULL, 'approved'),
(65, 5, 'Ammar Bilal', 5, 'Best dorm in the area!', '2025-11-04 14:04:14', NULL, 'approved'),
(66, 5, 'Lara Fadi', 4, 'Great place to live and study.', '2025-11-04 14:04:14', NULL, 'approved'),
(67, 11, 'esraa omar', 3, 'اوصي بالتجربة', '2025-11-04 14:05:21', NULL, 'approved'),
(68, 11, 'omar', 5, 'جميل جدا جدا', '2025-11-04 14:06:41', NULL, 'approved'),
(70, NULL, 'اسراء عمر', 5, 'سهل الاستخدام و يلبي اغلب المتطلبات', '2025-11-04 16:33:06', NULL, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `dorms`
--

CREATE TABLE `dorms` (
  `dorm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_range` varchar(50) DEFAULT NULL,
  `proximity_to_university` varchar(50) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `number_of_rooms` int(11) DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'female',
  `owner_id` int(11) DEFAULT NULL COMMENT 'معرف صاحب السكن',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive','pending') DEFAULT 'active' COMMENT 'حالة السكن'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dorms`
--

INSERT INTO `dorms` (`dorm_id`, `name`, `name_ar`, `description`, `description_ar`, `price_range`, `proximity_to_university`, `room_type`, `availability`, `image_url`, `number_of_rooms`, `location`, `owner_name`, `contact_email`, `contact_phone`, `gender`, `owner_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Naela Dorm', 'سكن نائلة', 'Cozy and affordable living with a friendly atmosphere. Ideal for students seeking a quiet study environment.', 'سكن مريح وبأسعار معقولة في جو ودود. مثالي للطلاب الذين يبحثون عن بيئة دراسية هادئة.', '200_250', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQjLUTJD33UJpS_kIjQR4UtqP3U6jEk0aot-FfGGqauDW1k27Wn3zy5zzoh04p7AFNwzxQ&usqp=CAU', 325, 'Mirwid', 'Ahmed Al-Ali', 'Naela Dorm@example.com', '+962 77 111 1111', 'female', 22, '2025-12-12 19:44:28', '2025-12-17 19:19:21', 'active'),
(2, 'Bella Residence', 'بيلا', 'Modern and vibrant living spaces with premium amenities. Perfect for students who enjoy an active social life.', 'مساحات معيشة عصرية ونابضة بالحياة مع مرافق فاخرة. مثالية للطلاب الذين يستمتعون بحياة اجتماعية نشطة.', '400_500', '2 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVFj0_39sqHfhpaD1dMXA_LoFMVBPgbJmCdim0bbRc5Xmlb7G89uM7olXowG9lvNLSvps&usqp=CAU', 280, 'Aleadnania', 'Fatima Hassan', 'dorm2@example.com', '+962 78 222 2222', 'female', 23, '2025-12-12 19:44:28', '2025-12-18 13:53:27', 'active'),
(3, 'Malk Dorm', 'ملك', 'A tranquil and focused environment, great for serious students. Offers comfortable rooms and a peaceful ambiance.', 'بيئة هادئة ومُركزة، مثالية للطلاب الجادين. توفر غرفًا مريحة وأجواءً هادئة.', '250_300', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvpQoBsr6e09t_2N2oIVp4jqboOHC1TN5Qjr4twujfHrnNpbk6bFDNRJLTQum_Yk8QFYQ&usqp=CAU', 390, 'Mutah', 'Mohammed Saleh', 'dorm3@example.com', '+962 79 333 3333', 'female', 24, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(4, 'Merwed Suites', 'مرود', 'Spacious suites with private baths, offering a luxurious student living experience. Designed for comfort and privacy.', 'بيت أشعة الشمس - سكن مشمس ومريح مع أجواء عائلية دافئة. غرف مضيئة وواسعة مع مساحات مشتركة مريحة.بيئة هادئة ومُركزة، مثالية للطلاب الجادين. توفر غرفًا مريحة وأجواءً هادئة.', '500_600', '2 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1eNNNxoKbyIlAZjZYReMonzldym2rAJLtHklMNZqzdgAs5T9W6zDgIiQOQ37MyXNxFto&usqp=CAU', 215, 'Zahoum', 'Laila Hussein', 'dorm4@example.com', '+962 77 444 4444', 'female', 25, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(5, 'Maya Dorm', 'مايا', 'Community-focused living with shared spaces that encourage interaction and collaboration. A lively and engaging environment.', 'سكن مجتمعي متكامل، بمساحات مشتركة تشجع على التفاعل والتعاون. بيئة حيوية وتفاعلية.', '300_400', '5 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTE1h1t4ade8Bf2Qz4UtrT_89aCaHE5LFb-lDtHYo6lUkSnBpVmcioF86gX1MPWzpHJQJs&usqp=CAU', 350, 'Mirwid', 'Omar Khalid', 'dorm5@example.com', '+962 78 555 5555', 'female', 26, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(6, 'Albaladeia Apartments', 'البلدية', 'Apartment-style units offering independence and all necessary facilities for a comfortable stay. Ideal for longer terms.', 'الوادي الأخضر - سكن محاط بالطبيعة الخضراء مع بيئة هادئة ومنعشة. مثالي لمحبي الطبيعة والهدوءوحدات سكنية على طراز الشقق، توفر الاستقلالية وجميع المرافق اللازمة لإقامة مريحة. مثالية لفترات الإقامة الطويلة.', '250_300', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzXWWoIxlgiI2oTtdwfba7lUZg583-TNingA&s', 230, 'Aleadnania', 'Sara Abdulaziz', 'dorm6@example.com', '+962 79 666 6666', 'female', 27, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(7, 'Alesraa Dorm', 'الاسراء', 'Traditional and welcoming environment, offering a sense of community and support.', 'بيئة تقليدية وترحيبية، توفر شعوراً بالمجتمع والدعم.', '150_200', '2 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEuJWX1sqALp74HPs0WyXExQYTm2PBbfRTOw&s', 370, 'Mutah', 'Ali Zaki', 'dorm7@example.com', '+962 77 777 7777', 'female', 28, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(8, 'Alnajah Residences', 'النجاح', 'Luxurious and private residence with top-tier amenities, ensuring a comfortable and exclusive experience.', 'مسكن فاخر وخاص مع وسائل راحة من الدرجة الأولى، يضمن تجربة مريحة وحصرية.', 'greater_than_600', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSKd_jZ1QrIKrGYdqYlniPJQTcF9YZpdfXDFg&s', 295, 'Zahoum', 'Nour Abdullah', 'dorm8@example.com', '+962 78 888 8888', 'female', 29, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(9, 'Crown Dorm', 'كراون', 'Panoramic views and supreme comfort define these suites, perfect for students seeking premium living.', 'تتميز هذه الأجنحة بإطلالات بانورامية وراحة فائقة، وهي مثالية للطلاب الذين يبحثون عن معيشة متميزة.', '500_600', '2 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTu35U-HAqikmbABzR6O1MELuFaG6Vw_0NykQ&s', 205, 'Mirwid', 'Khalid Mahmoud', 'dorm9@example.com', '+962 79 999 9999', 'female', 30, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(10, 'Jasmine Dorm', 'الياسمين', 'Eco-friendly and serene living, surrounded by greenery for a peaceful and healthy student life.', 'معيشة صديقة للبيئة وهادئة، محاطة بالخضرة لحياة طلابية هادئة وصحية.', '300_400', '5 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5nyBDWAkdPRVUYKL0cBp7t-H-EAl6Z8mlLQ&s', 310, 'Aleadnania', 'Reem Ahmed', 'dorm10@example.com', '+962 77 000 0000', 'female', 31, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(11, 'Alaman Dorm', 'الامان', 'Vibrant and social hub designed for collaboration and interaction, located in the heart of student life.', 'مركز نابض بالحياة واجتماعي مصمم للتعاون والتفاعل، ويقع في قلب الحياة الطلابية.', '250_300', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThC2Xh2L4nSccqmlW6QW1vQmBmfYo8WjXKJA&s', 260, 'Mutah', 'Yousef Ibrahim', 'dorm11@example.com', '+962 78 123 4567', 'female', 32, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(12, 'Mutah Apartments', 'مؤتة', 'Urban living with unparalleled accessibility to city amenities and public transport.', 'حياة حضرية مع إمكانية الوصول لا مثيل لها إلى وسائل الراحة في المدينة ووسائل النقل العام.', '400_500', '1 mile', 'Single,Double,Triple', 'Next Month', 'https://static.vecteezy.com/system/resources/previews/032/720/359/non_2x/simple-and-bright-room-for-a-student-in-a-student-dormitory-ai-generative-photo.jpg', 385, 'Zahoum', 'Hana Omar', 'dorm12@example.com', '+962 79 234 5678', 'female', 33, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(13, 'Dina Dorms', 'دينا', 'Prime location close to campus with stunning views, offering convenience and a great living atmosphere.', 'موقع مميز بالقرب من الحرم الجامعي مع مناظر خلابة، ويوفر الراحة وأجواء معيشة رائعة.', '500_600', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnjjY9__m0NUwYAWgyRWEXN2KXe3tvqwISGQ&s', 245, 'Mirwid', 'Majed Nasser', 'dorm13@example.com', '+962 77 345 6789', 'female', 34, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(14, 'Alfirdaws Dorm', 'الفردوس', 'A calm and quiet environment, ideal for students who prioritize peace and focus in their academic journey.', 'بيئة هادئة وساكنة، مثالية للطلاب الذين يعطون الأولوية للسلام والتركيز في رحلتهم الأكاديمية.', '150_200', '5 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROpryBa8cAWDcZMGmzqIyclQGYWobgihQWrw&s', 330, 'Aleadnania', 'Dina Adel', 'dorm14@example.com', '+962 78 456 7890', 'female', 35, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(15, 'Elite Dorm', 'ايليت', 'Contemporary design and state-of-the-art facilities for a dynamic and comfortable student experience.', 'تصميم معاصر ومرافق حديثة لتوفير تجربة طلابية ديناميكية ومريحة.', 'greater_than_600', '2 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOBlf74BYRphRtslHBtWEFAbk658jo0jKM1-khGfLh-JVSTgjCmn2b4Xea-ywKBbNbVUY&usqp=CAU', 270, 'Mutah', 'Fahad Jamal', 'dorm15@example.com', '+962 79 567 8901', 'female', 36, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(16, 'Prince Residences', 'الامير', 'An exclusive retreat designed for focused study and academic excellence, with all necessary comforts.', 'ملاذ حصري مصمم للدراسة المركزة والتميز الأكاديمي، مع جميع وسائل الراحة الضرورية.', '300_400', '1 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlYMkNqd27gTBRMZrznB4NiUKtV_O6eZDMfA&s', 365, 'Zahoum', 'Aisha Raed', 'dorm16@example.com', '+962 77 678 9012', 'female', 37, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(17, 'Rama Apartments', 'راما', 'Easy access to transportation and key city areas, making daily commutes simple and convenient.', 'سهولة الوصول إلى وسائل النقل والمناطق الرئيسية في المدينة، مما يجعل التنقل اليومي بسيطًا ومريحًا.', '250_300', '2 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSfSQi49t48bsTQcwiA3_ma0djBtpKlA-qSgQ&s', 220, 'Mirwid', 'Sami Jawad', 'dorm17@example.com', '+962 78 789 0123', 'female', 38, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(18, 'Gardenia Dorm', 'غاردينيا', 'Spacious rooms and breathtaking cityscapes, offering a luxurious and expansive living experience.', 'غرف واسعة ومناظر طبيعية خلابة للمدينة، توفر تجربة معيشة فاخرة وواسعة.', '500_600', '2 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTRGisnhYpHLdVGliHR7mLoTjqK13vDzDwvVQ&s', 340, 'Aleadnania', 'Mona Tareq', 'dorm18@example.com', '+962 79 890 1234', 'female', 39, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(19, 'Almanzel Dorm', 'المنزل', 'Charming and intimate living space, providing a cozy and personal environment for students.', 'مساحة معيشة ساحرة وحميمة توفر بيئة مريحة وشخصية للطلاب.', '150_200', '1 mile', 'Single,Double,Triple', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTgft_svSAsWA8fZ_EV2_FrTv2xLCp_8ut0w&s', 290, 'Mutah', 'Basel Nabil', 'dorm19@example.com', '+962 77 901 2345', 'female', 40, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(20, 'Arnon Dorm', 'ارنون', 'Balanced living and community spirit, fostering a supportive and harmonious environment for all residents.', 'العيش المتوازن وروح المجتمع، وتعزيز بيئة داعمة ومتناغمة لجميع السكان.', '400_500', '5 mile', 'Single,Double,Triple', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSn-xzkrc5yeTmi-g5HADcm0lBr5Aso1P2yX7lAM0BayPNY3vEMHB27N3JBRYChy1sWevA&usqp=CAU', 300, 'Zahoum', 'Hadeel Salem', 'dorm20@example.com', '+962 78 012 3456', 'female', 41, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(21, 'Alamir Dorm', 'الامير', 'Comfortable and spacious accommodation for male students, located close to the university and providing a quiet study environment.', 'مقر الملوك - سكن فاخر للطلاب مع مرافق حديثة وموقع ممتاز قرب جامعة مؤتة. يوفر بيئة ملكية للطلاب المتميزين.سكن مريح وواسع للطلاب الذكور، يقع بالقرب من الجامعة ويوفر بيئة دراسية هادئة.', '150_200', '1 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1eNNNxoKbyIlAZjZYReMonzldym2rAJLtHklMNZqzdgAs5T9W6zDgIiQOQ37MyXNxFto&usqp=CAU', 150, 'Mutah', 'Khaled Ali', 'alamir@example.com', '+962 77 123 4567', 'male', 42, '2025-12-12 19:44:28', '2025-12-12 21:07:23', 'active'),
(22, 'Alqima Dorm', 'القمة', 'Modern accommodation with sports facilities and full services, ideal for active students.', 'سكن حديث مع مرافق رياضية وخدمات كاملة، مثالي للطلاب النشطين.', '200_250', '1 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTsZPDgqyr7CJjf1DWkk794gDkzLC7bQpgcg&s', 120, 'Mutah', 'Ahmed Sami', 'alqima@example.com', '+962 78 987 6543', 'male', 43, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(23, 'Alyarmuk Dorm', 'اليرموك', 'Budget accommodation offering shared and single rooms, with a great community atmosphere.', 'إقامة اقتصادية توفر غرفًا مشتركة وفردية، مع أجواء مجتمعية رائعة.', '250_300', '2 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSc-H8fjNWiLyuoFCzi1Al1wTUlURAjf5DUuA&s', 200, 'Mutah', 'Omar Fawzi', 'bader@example.com', '+962 79 111 2222', 'male', 44, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(24, 'Maka Dorm', 'مكة', 'Luxury suites with private bathrooms and hotel services, for students seeking comfort and privacy.', 'أجنحة فاخرة مع حمامات خاصة وخدمات فندقية، للطلاب الذين يبحثون عن الراحة والخصوصية.', '300_400', '5', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQsFOYdwXN6XRbmLe0svgJrTOdyOTQ8_WT8i4K7gOaYEq3b7u6kxiFHRvUe8nAYXqxqiTk&usqp=CAU', 90, 'Mutah', 'Hassan Zaki', 'safwa@example.com', '+962 77 333 4444', 'male', 45, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(25, 'Bayt Almaqdis', 'بيت المقدس', 'Quiet and study-friendly accommodation, close to libraries and academic facilities.', 'مكان إقامة هادئ ومناسب للدراسة، بالقرب من المكتبات والمرافق الأكاديمية.', '400_500', '3', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSKd_jZ1QrIKrGYdqYlniPJQTcF9YZpdfXDFg&s\" alt=\"Al-Najah Residences', 180, 'Mutah', 'Yousef Naji', 'alquds@example.com', '+962 79 555 6666', 'male', 46, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(26, 'Albatra Dorm', 'البتراء', 'A student house that focuses on a balanced and calm environment, ideal for academic concentration.', 'بيت طلابي يركز على بيئة متوازنة وهادئة، مثالية للتركيز الأكاديمي.', '150_200', '1.5 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDRnPUKLLKXHHCtElSWKdHtmvA37ce045yvXdis14VRj3njFSLYSKRCd5dVB8VrKIBT6Y&usqp=CAU', 100, 'Mirwid', 'Sami Adnan', 'harmony@example.com', '+962 78 777 8888', 'male', 47, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(27, 'Alrawashida', 'الرواشدة', 'Luxury accommodation with modern design and advanced services, offering a high-end accommodation experience.', 'إقامة فاخرة بتصميم عصري وخدمات متطورة توفر تجربة إقامة راقية.', '200_250', '5 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ_hrE5S3nlVReieGJwO3EsSD6d1R-nUA8Xwg&s', 80, 'Mirwid', 'Fahad Rashid', 'elite@example.com', '+962 77 999 0000', 'male', 48, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(28, 'Rayaan Alkhalij', 'ريان الخليج', 'A social and study center that provides opportunities for interaction and collaboration among students.', 'مركز اجتماعي ودراسي يوفر فرص التفاعل والتعاون بين الطلاب.', '250_300', '3 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1xPn9meEOmJ8gtvh6zbvNyf9k1kYB4rbQWA&s', 220, 'Mirwid', 'Maher Kamal', 'hub@example.com', '+962 79 123 9876', 'male', 49, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(29, 'Arnon Dorm', 'ارنون', 'Spacious, fully furnished rooms with panoramic city views.', 'غرف واسعة ومفروشة بالكامل مع إطلالات بانورامية على المدينة.', '300_400', '1 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS45GICDg2daDfsdFp630QfcOHku27A3vchQg&s', 70, 'Mirwid', 'Nader Faisal', 'royal@example.com', '+962 78 456 7890', 'male', 50, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(30, 'Albara\'a Dorm', 'البراء', 'A quiet and stimulating haven for serious study, with dedicated areas for reading and research.', 'ملاذ هادئ ومثير للدراسة الجادة، مع مناطق مخصصة للقراءة والبحث.', '400_500', '5 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT82jvgmNdrOqSvdroM5_-_08ZJug1O69eGCQ&s', 160, 'Mirwid', 'Tareq Adel', 'scholar@example.com', '+962 77 000 1111', 'male', 51, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(31, 'Alqatawneh Dorm', 'القطاونة', 'Strategic location close to transportation and essential facilities.', 'موقع استراتيجي بالقرب من وسائل النقل والمرافق الأساسية.', '200_250', '3 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRI1_NKwNo1OTjFysxqGnXBanE8A1NxASc-A&s', 190, 'Aleadnania', 'Ziad Mustafa', 'gateway@example.com', '+962 79 222 3333', 'male', 52, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(32, 'Karak Rabua', 'ربوة الكرك', 'Modernly designed accommodation that provides maximum comfort and technology for students.', 'سكن مصمم بشكل عصري يوفر أقصى قدر من الراحة والتكنولوجيا للطلاب.', '250_300', '1 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmwiA7xc1uDAVR0gHqMkfTtyWJ4XSikU8POQ&s', 110, 'Aleadnania', 'Waleed Samer', 'zenith@example.com', '+962 78 444 5555', 'male', 53, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(33, 'Almanara', 'المنارة', 'A historic building renovated to offer modern accommodation with an authentic character.', 'مبنى تاريخي تم تجديده ليقدم إقامة حديثة ذات طابع أصيل.', '300_400', '1 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfqTJXf9LBaRC5XiHyML3_tjuoE2yx4ofagg&s\" alt=\"Prince Residences', 130, 'Aleadnania', 'Ramzi Ghazi', 'heritage@example.com', '+962 77 666 7777', 'male', 54, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(34, 'Unity Dorm', 'سكن الوحدة', 'A supportive environment that encourages friendships and collaboration among students from different backgrounds.', 'بيئة داعمة تشجع الصداقات والتعاون بين الطلاب من خلفيات مختلفة.', '400_500', '1 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTsZPDgqyr7CJjf1DWkk794gDkzLC7bQpgcg&s', 170, 'Aleadnania', 'Jamal Haider', 'unity@example.com', '+962 79 888 9999', 'male', 55, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(35, 'Ruoteen Suites', 'روتين', 'Fully furnished studio apartments provide independence and privacy for students.', 'توفر شقق الاستوديو المفروشة بالكامل الاستقلال والخصوصية للطلاب.', '500_600', '1 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDHKwzfRaYJuUQxQA_ihHnkUi2zrEkA8y-xBuZL7WEm6r9VTEUF_ihkeo8HUKR1QvtY5E&usqp=CAU', 60, 'Aleadnania', 'Basel Amjad', 'phoenix@example.com', '+962 78 012 3456', 'male', 56, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(36, 'Place', 'بالاس', 'Stunning city views with luxurious amenities and elegant interior design.', 'إطلالات مذهلة على المدينة مع وسائل راحة فاخرة وتصميم داخلي أنيق.', '250_300', '3 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgaY7b21D2D0cpirfk2nmlORUfmu3tsD07QQ&s', 50, 'Zahoum', 'Rakan Salim', 'summit@example.com', '+962 77 234 5678', 'male', 57, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(37, 'The Citadel Dorm', 'القلعة', 'Centrally located accommodation with easy access to all the city\'s vital areas.', 'مكان إقامة في موقع مركزي مع سهولة الوصول إلى جميع المناطق الحيوية في المدينة.', '300_400', '5 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTModg0ye_EdfYaY_nEOnEjrkRKdJIfJXIsk1HGkdtgvQ5Fx7hDNKTW4vBSkmA8mOyik7Y&usqp=CAU', 140, 'Zahoum', 'Mazen Khalil', 'citadel@example.com', '+962 79 456 7890', 'male', 58, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(38, 'Almalek', 'المالك', 'A quiet and comfortable environment, away from the hustle and bustle of the city, ideal for relaxation.', 'بيئة هادئة ومريحة، بعيدًا عن صخب المدينة وضجيجها، مثالية للاسترخاء.', '400_500', '3 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3yU12zXwpVNn3mxx7mtcK1fSUd7Xyhn8Ikw&s', 210, 'Zahoum', 'Adnan Hussein', 'oasis@example.com', '+962 78 678 9012', 'male', 59, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(39, 'Apex', 'ابيكس', 'Modern accommodation providing all the amenities for modern student living.', 'سكن حديث يوفر كافة وسائل الراحة اللازمة للحياة الطلابية الحديثة.', '500_600', '1 mile', '', 'Available Now', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRrl7cq8atOdRzBRcUTSo4sg4iQ5HV6iW0z4Q&s', 95, 'Zahoum', 'Jawad Fouad', 'apex@example.com', '+962 77 890 1234', 'male', 60, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active'),
(40, 'The book', 'الكتاب', 'Eco-friendly housing surrounded by green spaces, providing a healthy and peaceful atmosphere.', 'سكن صديق للبيئة محاط بالمساحات الخضراء، يوفر أجواء صحية وهادئة.', 'greater_than_600', '3 mile', '', 'Next Month', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1exyqtmK0XNawOxqxaYQj6qusgOcuchb6Yw&s', 105, 'Zahoum', 'Hadi Nasser', 'greenwood@example.com', '+962 79 000 0000', 'male', 61, '2025-12-12 19:44:28', '2025-12-12 21:07:24', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `dorm_amenities`
--

CREATE TABLE `dorm_amenities` (
  `dorm_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dorm_amenities`
--

INSERT INTO `dorm_amenities` (`dorm_id`, `amenity_id`) VALUES
(1, 1),
(1, 2),
(1, 5),
(1, 6),
(1, 8),
(1, 9),
(2, 1),
(2, 2),
(2, 3),
(2, 5),
(2, 6),
(2, 9),
(2, 10),
(3, 2),
(3, 5),
(3, 6),
(3, 8),
(3, 9),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(4, 6),
(4, 9),
(4, 10),
(5, 2),
(5, 5),
(5, 6),
(5, 7),
(5, 9),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 8),
(6, 9),
(6, 10),
(7, 2),
(7, 5),
(7, 6),
(7, 8),
(7, 9),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(9, 1),
(9, 2),
(9, 5),
(9, 6),
(9, 9),
(9, 10),
(10, 2),
(10, 5),
(10, 6),
(10, 7),
(10, 9),
(11, 1),
(11, 2),
(11, 3),
(11, 5),
(11, 6),
(11, 9),
(12, 2),
(12, 5),
(12, 6),
(12, 9),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(13, 6),
(13, 7),
(13, 8),
(13, 9),
(13, 10),
(14, 2),
(14, 5),
(14, 6),
(14, 9),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(15, 5),
(15, 6),
(15, 7),
(15, 8),
(15, 9),
(15, 10),
(16, 2),
(16, 5),
(16, 6),
(16, 8),
(16, 9),
(17, 1),
(17, 2),
(17, 5),
(17, 6),
(17, 9),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(18, 7),
(18, 8),
(18, 9),
(18, 10),
(19, 2),
(19, 5),
(19, 6),
(19, 9),
(20, 1),
(20, 2),
(20, 3),
(20, 5),
(20, 6),
(20, 8),
(20, 9),
(21, 1),
(21, 2),
(21, 6),
(21, 8),
(22, 1),
(22, 2),
(22, 3),
(22, 6),
(22, 10),
(23, 2),
(23, 6),
(23, 8),
(23, 9),
(24, 1),
(24, 2),
(24, 6),
(24, 10),
(25, 2),
(25, 6),
(25, 8),
(26, 1),
(26, 2),
(26, 7),
(26, 9),
(27, 1),
(27, 2),
(27, 3),
(27, 4),
(27, 5),
(27, 6),
(27, 7),
(27, 8),
(27, 9),
(27, 10),
(28, 2),
(28, 6),
(28, 8),
(28, 9),
(29, 1),
(29, 2),
(29, 6),
(29, 10),
(30, 2),
(30, 6),
(30, 8),
(31, 2),
(31, 5),
(31, 6),
(32, 1),
(32, 2),
(32, 6),
(32, 10),
(33, 2),
(33, 6),
(33, 8),
(33, 9),
(34, 2),
(34, 6),
(34, 7),
(35, 1),
(35, 2),
(35, 6),
(35, 10),
(36, 1),
(36, 2),
(36, 3),
(36, 4),
(36, 5),
(36, 6),
(36, 7),
(36, 8),
(36, 9),
(36, 10),
(37, 2),
(37, 6),
(37, 8),
(37, 9),
(38, 2),
(38, 6),
(38, 8),
(39, 1),
(39, 2),
(39, 3),
(39, 6),
(39, 10),
(40, 2),
(40, 6),
(40, 7);

-- --------------------------------------------------------

--
-- Table structure for table `dorm_floors`
--

CREATE TABLE `dorm_floors` (
  `floor_id` int(11) NOT NULL,
  `dorm_id` int(11) NOT NULL,
  `floor_number` int(11) NOT NULL COMMENT 'رقم الطابق (0 = أرضي، 1 = أول، إلخ)',
  `floor_name` varchar(50) DEFAULT NULL COMMENT 'اسم الطابق (اختياري)',
  `total_rooms` int(11) DEFAULT 0 COMMENT 'عدد الغرف في الطابق'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dorm_floors`
--

INSERT INTO `dorm_floors` (`floor_id`, `dorm_id`, `floor_number`, `floor_name`, `total_rooms`) VALUES
(1, 1, 0, 'Ground Floor', 9),
(2, 1, 1, 'First Floor', 9),
(3, 1, 2, 'Second Floor', 12),
(4, 1, 3, 'Third Floor', 11),
(5, 2, 0, 'Ground Floor', 8),
(6, 2, 1, 'First Floor', 10),
(7, 2, 2, 'Second Floor', 9),
(8, 2, 3, 'Third Floor', 11),
(9, 3, 0, 'Ground Floor', 10),
(10, 3, 1, 'First Floor', 10),
(11, 3, 2, 'Second Floor', 12),
(12, 4, 0, 'Ground Floor', 11),
(13, 4, 1, 'First Floor', 8),
(14, 4, 2, 'Second Floor', 11),
(15, 4, 3, 'Third Floor', 8),
(16, 4, 4, 'Fourth Floor', 12),
(17, 5, 0, 'Ground Floor', 11),
(18, 5, 1, 'First Floor', 8),
(19, 5, 2, 'Second Floor', 8),
(20, 5, 3, 'Third Floor', 8),
(21, 5, 4, 'Fourth Floor', 10),
(22, 6, 0, 'Ground Floor', 9),
(23, 6, 1, 'First Floor', 11),
(24, 6, 2, 'Second Floor', 12),
(25, 6, 3, 'Third Floor', 8),
(26, 7, 0, 'Ground Floor', 8),
(27, 7, 1, 'First Floor', 10),
(28, 7, 2, 'Second Floor', 11),
(29, 7, 3, 'Third Floor', 8),
(30, 8, 0, 'Ground Floor', 12),
(31, 8, 1, 'First Floor', 8),
(32, 8, 2, 'Second Floor', 11),
(33, 8, 3, 'Third Floor', 11),
(34, 9, 0, 'Ground Floor', 11),
(35, 9, 1, 'First Floor', 10),
(36, 9, 2, 'Second Floor', 9),
(37, 10, 0, 'Ground Floor', 11),
(38, 10, 1, 'First Floor', 9),
(39, 10, 2, 'Second Floor', 10),
(40, 11, 0, 'Ground Floor', 12),
(41, 11, 1, 'First Floor', 8),
(42, 11, 2, 'Second Floor', 9),
(43, 12, 0, 'Ground Floor', 9),
(44, 12, 1, 'First Floor', 8),
(45, 12, 2, 'Second Floor', 12),
(46, 12, 3, 'Third Floor', 11),
(47, 13, 0, 'Ground Floor', 11),
(48, 13, 1, 'First Floor', 12),
(49, 13, 2, 'Second Floor', 11),
(50, 13, 3, 'Third Floor', 11),
(51, 14, 0, 'Ground Floor', 10),
(52, 14, 1, 'First Floor', 12),
(53, 14, 2, 'Second Floor', 8),
(54, 14, 3, 'Third Floor', 11),
(55, 14, 4, 'Fourth Floor', 9),
(56, 15, 0, 'Ground Floor', 10),
(57, 15, 1, 'First Floor', 10),
(58, 15, 2, 'Second Floor', 10),
(59, 15, 3, 'Third Floor', 8),
(60, 15, 4, 'Fourth Floor', 12),
(61, 16, 0, 'Ground Floor', 8),
(62, 16, 1, 'First Floor', 11),
(63, 16, 2, 'Second Floor', 12),
(64, 16, 3, 'Third Floor', 12),
(65, 16, 4, 'Fourth Floor', 12),
(66, 17, 0, 'Ground Floor', 12),
(67, 17, 1, 'First Floor', 10),
(68, 17, 2, 'Second Floor', 12),
(69, 18, 0, 'Ground Floor', 10),
(70, 18, 1, 'First Floor', 11),
(71, 18, 2, 'Second Floor', 11),
(72, 19, 0, 'Ground Floor', 11),
(73, 19, 1, 'First Floor', 9),
(74, 19, 2, 'Second Floor', 12),
(75, 19, 3, 'Third Floor', 8),
(76, 19, 4, 'Fourth Floor', 9),
(77, 20, 0, 'Ground Floor', 11),
(78, 20, 1, 'First Floor', 11),
(79, 20, 2, 'Second Floor', 8),
(80, 21, 0, 'Ground Floor', 10),
(81, 21, 1, 'First Floor', 11),
(82, 21, 2, 'Second Floor', 9),
(83, 21, 3, 'Third Floor', 9),
(84, 21, 4, 'Fourth Floor', 11),
(85, 22, 0, 'Ground Floor', 10),
(86, 22, 1, 'First Floor', 9),
(87, 22, 2, 'Second Floor', 11),
(88, 22, 3, 'Third Floor', 12),
(89, 23, 0, 'Ground Floor', 9),
(90, 23, 1, 'First Floor', 12),
(91, 23, 2, 'Second Floor', 8),
(92, 23, 3, 'Third Floor', 10),
(93, 24, 0, 'Ground Floor', 12),
(94, 24, 1, 'First Floor', 10),
(95, 24, 2, 'Second Floor', 11),
(96, 24, 3, 'Third Floor', 8),
(97, 24, 4, 'Fourth Floor', 8),
(98, 25, 0, 'Ground Floor', 9),
(99, 25, 1, 'First Floor', 12),
(100, 25, 2, 'Second Floor', 10),
(101, 25, 3, 'Third Floor', 9),
(102, 26, 0, 'Ground Floor', 11),
(103, 26, 1, 'First Floor', 12),
(104, 26, 2, 'Second Floor', 9),
(105, 26, 3, 'Third Floor', 9),
(106, 26, 4, 'Fourth Floor', 10),
(107, 27, 0, 'Ground Floor', 11),
(108, 27, 1, 'First Floor', 12),
(109, 27, 2, 'Second Floor', 12),
(110, 28, 0, 'Ground Floor', 8),
(111, 28, 1, 'First Floor', 11),
(112, 28, 2, 'Second Floor', 12),
(113, 28, 3, 'Third Floor', 8),
(114, 28, 4, 'Fourth Floor', 8),
(115, 29, 0, 'Ground Floor', 10),
(116, 29, 1, 'First Floor', 8),
(117, 29, 2, 'Second Floor', 8),
(118, 30, 0, 'Ground Floor', 11),
(119, 30, 1, 'First Floor', 9),
(120, 30, 2, 'Second Floor', 12),
(121, 30, 3, 'Third Floor', 12),
(122, 31, 0, 'Ground Floor', 8),
(123, 31, 1, 'First Floor', 9),
(124, 31, 2, 'Second Floor', 12),
(125, 32, 0, 'Ground Floor', 9),
(126, 32, 1, 'First Floor', 12),
(127, 32, 2, 'Second Floor', 9),
(128, 32, 3, 'Third Floor', 8),
(129, 32, 4, 'Fourth Floor', 8),
(130, 33, 0, 'Ground Floor', 10),
(131, 33, 1, 'First Floor', 12),
(132, 33, 2, 'Second Floor', 10),
(133, 34, 0, 'Ground Floor', 8),
(134, 34, 1, 'First Floor', 10),
(135, 34, 2, 'Second Floor', 12),
(136, 35, 0, 'Ground Floor', 12),
(137, 35, 1, 'First Floor', 12),
(138, 35, 2, 'Second Floor', 12),
(139, 35, 3, 'Third Floor', 11),
(140, 35, 4, 'Fourth Floor', 10),
(141, 36, 0, 'Ground Floor', 9),
(142, 36, 1, 'First Floor', 8),
(143, 36, 2, 'Second Floor', 12),
(144, 36, 3, 'Third Floor', 9),
(145, 37, 0, 'Ground Floor', 11),
(146, 37, 1, 'First Floor', 8),
(147, 37, 2, 'Second Floor', 10),
(148, 37, 3, 'Third Floor', 12),
(149, 38, 0, 'Ground Floor', 8),
(150, 38, 1, 'First Floor', 8),
(151, 38, 2, 'Second Floor', 12),
(152, 39, 0, 'Ground Floor', 10),
(153, 39, 1, 'First Floor', 9),
(154, 39, 2, 'Second Floor', 9),
(155, 39, 3, 'Third Floor', 9),
(156, 40, 0, 'Ground Floor', 11),
(157, 40, 1, 'First Floor', 9),
(158, 40, 2, 'Second Floor', 9),
(159, 40, 3, 'Third Floor', 8),
(160, 40, 4, 'Fourth Floor', 9);

-- --------------------------------------------------------

--
-- Table structure for table `dorm_rooms`
--

CREATE TABLE `dorm_rooms` (
  `room_id` int(11) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `dorm_id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL COMMENT 'رقم الغرفة',
  `room_type` varchar(50) NOT NULL COMMENT 'نوع الغرفة (Single, Double, Triple, Suite)',
  `position` enum('front','back','side','corner') DEFAULT 'front' COMMENT 'موقع الغرفة في المبنى',
  `view_type` varchar(50) DEFAULT NULL COMMENT 'نوع الإطلالة (Street, Garden, Mountain, etc.)',
  `is_available` tinyint(1) DEFAULT 1 COMMENT '1 = متاحة، 0 = محجوزة',
  `capacity` int(11) DEFAULT 1 COMMENT 'سعة الغرفة (عدد الأشخاص)',
  `available_spots` int(11) DEFAULT 0,
  `current_occupancy` int(11) DEFAULT 0 COMMENT 'عدد الأشخاص الحاليين',
  `price_per_month` decimal(10,2) DEFAULT NULL COMMENT 'السعر الشهري',
  `features` text DEFAULT NULL COMMENT 'مميزات إضافية (JSON)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dorm_rooms`
--

INSERT INTO `dorm_rooms` (`room_id`, `floor_id`, `dorm_id`, `room_number`, `room_type`, `position`, `view_type`, `is_available`, `capacity`, `available_spots`, `current_occupancy`, `price_per_month`, `features`) VALUES
(1, 1, 1, '001', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 200.00, NULL),
(2, 1, 1, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(3, 1, 1, '003', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(4, 1, 1, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(5, 1, 1, '005', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(6, 1, 1, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(7, 1, 1, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(8, 1, 1, '008', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(9, 1, 1, '009', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(10, 1, 1, '010', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(11, 1, 1, '011', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(12, 1, 1, '012', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(13, 2, 1, '101', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(14, 2, 1, '102', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(15, 2, 1, '103', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(16, 2, 1, '104', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(17, 2, 1, '105', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(18, 2, 1, '106', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(19, 2, 1, '107', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(20, 2, 1, '108', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(21, 2, 1, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(22, 3, 1, '201', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(23, 3, 1, '202', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(24, 3, 1, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(25, 3, 1, '204', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(26, 3, 1, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(27, 3, 1, '206', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(28, 3, 1, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(29, 3, 1, '208', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(30, 3, 1, '209', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(31, 3, 1, '210', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 200.00, NULL),
(32, 3, 1, '211', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(33, 3, 1, '212', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(34, 4, 1, '301', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(35, 4, 1, '302', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(36, 4, 1, '303', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(37, 4, 1, '304', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(38, 4, 1, '305', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(39, 4, 1, '306', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(40, 4, 1, '307', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 200.00, NULL),
(41, 4, 1, '308', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(42, 4, 1, '309', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(43, 5, 2, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(44, 5, 2, '002', 'Single', 'front', 'Street View', 1, 1, 1, 0, 480.00, NULL),
(45, 5, 2, '003', 'Single', 'side', 'Garden View', 0, 1, 0, 1, 480.00, NULL),
(46, 5, 2, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(47, 5, 2, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(48, 5, 2, '006', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(49, 5, 2, '007', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(50, 5, 2, '008', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(51, 6, 2, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 440.00, NULL),
(52, 6, 2, '102', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(53, 6, 2, '103', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(54, 6, 2, '104', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(55, 6, 2, '105', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(56, 6, 2, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 480.00, NULL),
(57, 6, 2, '107', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(58, 6, 2, '108', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(59, 6, 2, '109', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(60, 6, 2, '110', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(61, 7, 2, '201', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(62, 7, 2, '202', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 410.00, NULL),
(63, 7, 2, '203', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(64, 7, 2, '204', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(65, 7, 2, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(66, 7, 2, '206', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(67, 7, 2, '207', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(68, 7, 2, '208', 'Double', 'front', 'Street View', 1, 2, 2, 0, 440.00, NULL),
(69, 8, 2, '301', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(70, 8, 2, '302', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(71, 8, 2, '303', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(72, 8, 2, '304', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(73, 8, 2, '305', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(74, 8, 2, '306', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(75, 8, 2, '307', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(76, 8, 2, '308', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(77, 8, 2, '309', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(78, 9, 3, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 260.00, NULL),
(79, 9, 3, '002', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(80, 9, 3, '003', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 280.00, NULL),
(81, 9, 3, '004', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 260.00, NULL),
(82, 9, 3, '005', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 260.00, NULL),
(83, 9, 3, '006', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(84, 9, 3, '007', 'Single', 'front', 'Street View', 1, 1, 1, 0, 280.00, NULL),
(85, 9, 3, '008', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 280.00, NULL),
(86, 10, 3, '101', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 260.00, NULL),
(87, 10, 3, '102', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 260.00, NULL),
(88, 10, 3, '103', 'Double', 'front', 'Street View', 1, 2, 2, 0, 260.00, NULL),
(89, 10, 3, '104', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 280.00, NULL),
(90, 10, 3, '105', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(91, 10, 3, '106', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 260.00, NULL),
(92, 10, 3, '107', 'Single', 'front', 'Street View', 1, 1, 1, 0, 280.00, NULL),
(93, 10, 3, '108', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 260.00, NULL),
(94, 10, 3, '109', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(95, 10, 3, '110', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 280.00, NULL),
(96, 10, 3, '111', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 260.00, NULL),
(97, 10, 3, '112', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 280.00, NULL),
(98, 11, 3, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 260.00, NULL),
(99, 11, 3, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 260.00, NULL),
(100, 11, 3, '203', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 250.00, NULL),
(101, 11, 3, '204', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 280.00, NULL),
(102, 11, 3, '205', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 280.00, NULL),
(103, 11, 3, '206', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 280.00, NULL),
(104, 11, 3, '207', 'Single', 'front', 'Street View', 1, 1, 1, 0, 280.00, NULL),
(105, 11, 3, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(106, 11, 3, '209', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(107, 12, 4, '001', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 500.00, NULL),
(108, 12, 4, '002', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(109, 12, 4, '003', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(110, 12, 4, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 530.00, NULL),
(111, 12, 4, '005', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(112, 12, 4, '006', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 530.00, NULL),
(113, 12, 4, '007', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(114, 12, 4, '008', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(115, 13, 4, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(116, 13, 4, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(117, 13, 4, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(118, 13, 4, '104', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(119, 13, 4, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 500.00, NULL),
(120, 13, 4, '106', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 530.00, NULL),
(121, 13, 4, '107', 'Single', 'front', 'Street View', 1, 1, 1, 0, 570.00, NULL),
(122, 13, 4, '108', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(123, 14, 4, '201', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 500.00, NULL),
(124, 14, 4, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 530.00, NULL),
(125, 14, 4, '203', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 500.00, NULL),
(126, 14, 4, '204', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 530.00, NULL),
(127, 14, 4, '205', 'Single', 'front', 'Street View', 1, 1, 1, 0, 570.00, NULL),
(128, 14, 4, '206', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 500.00, NULL),
(129, 14, 4, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 570.00, NULL),
(130, 14, 4, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 500.00, NULL),
(131, 14, 4, '209', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(132, 14, 4, '210', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(133, 14, 4, '211', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 530.00, NULL),
(134, 14, 4, '212', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 570.00, NULL),
(135, 15, 4, '301', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 500.00, NULL),
(136, 15, 4, '302', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 530.00, NULL),
(137, 15, 4, '303', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(138, 15, 4, '304', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(139, 15, 4, '305', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(140, 15, 4, '306', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 500.00, NULL),
(141, 15, 4, '307', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 500.00, NULL),
(142, 15, 4, '308', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(143, 15, 4, '309', 'Single', 'front', 'Street View', 1, 1, 1, 0, 570.00, NULL),
(144, 15, 4, '310', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 530.00, NULL),
(145, 15, 4, '311', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 530.00, NULL),
(146, 15, 4, '312', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 500.00, NULL),
(147, 16, 4, '401', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 530.00, NULL),
(148, 16, 4, '402', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 570.00, NULL),
(149, 16, 4, '403', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 500.00, NULL),
(150, 16, 4, '404', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 500.00, NULL),
(151, 16, 4, '405', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(152, 16, 4, '406', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 570.00, NULL),
(153, 16, 4, '407', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 570.00, NULL),
(154, 16, 4, '408', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 500.00, NULL),
(155, 16, 4, '409', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 530.00, NULL),
(156, 16, 4, '410', 'Double', 'front', 'Street View', 1, 2, 2, 0, 530.00, NULL),
(157, 17, 5, '001', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(158, 17, 5, '002', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(159, 17, 5, '003', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(160, 17, 5, '004', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(161, 17, 5, '005', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(162, 17, 5, '006', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(163, 17, 5, '007', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 390.00, NULL),
(164, 17, 5, '008', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 350.00, NULL),
(165, 17, 5, '009', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(166, 17, 5, '010', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(167, 17, 5, '011', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(168, 18, 5, '101', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(169, 18, 5, '102', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(170, 18, 5, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 390.00, NULL),
(171, 18, 5, '104', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(172, 18, 5, '105', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 390.00, NULL),
(173, 18, 5, '106', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(174, 18, 5, '107', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 350.00, NULL),
(175, 18, 5, '108', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(176, 18, 5, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(177, 18, 5, '110', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(178, 18, 5, '111', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(179, 18, 5, '112', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(180, 19, 5, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(181, 19, 5, '202', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(182, 19, 5, '203', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(183, 19, 5, '204', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 390.00, NULL),
(184, 19, 5, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(185, 19, 5, '206', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 390.00, NULL),
(186, 19, 5, '207', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(187, 19, 5, '208', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(188, 20, 5, '301', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(189, 20, 5, '302', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(190, 20, 5, '303', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 390.00, NULL),
(191, 20, 5, '304', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(192, 20, 5, '305', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(193, 20, 5, '306', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(194, 20, 5, '307', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 390.00, NULL),
(195, 20, 5, '308', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 350.00, NULL),
(196, 21, 5, '401', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(197, 21, 5, '402', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 390.00, NULL),
(198, 21, 5, '403', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(199, 21, 5, '404', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(200, 21, 5, '405', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(201, 21, 5, '406', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(202, 21, 5, '407', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(203, 21, 5, '408', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(204, 21, 5, '409', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(205, 21, 5, '410', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(206, 21, 5, '411', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(207, 21, 5, '412', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(208, 22, 6, '001', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(209, 22, 6, '002', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(210, 22, 6, '003', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(211, 22, 6, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(212, 22, 6, '005', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(213, 22, 6, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(214, 22, 6, '007', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(215, 22, 6, '008', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(216, 22, 6, '009', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(217, 22, 6, '010', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(218, 23, 6, '101', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(219, 23, 6, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(220, 23, 6, '103', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(221, 23, 6, '104', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(222, 23, 6, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(223, 23, 6, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(224, 23, 6, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(225, 23, 6, '108', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(226, 23, 6, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(227, 23, 6, '110', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(228, 24, 6, '201', 'Double', 'front', 'Street View', 1, 2, 2, 0, 265.00, NULL),
(229, 24, 6, '202', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(230, 24, 6, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(231, 24, 6, '204', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(232, 24, 6, '205', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(233, 24, 6, '206', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(234, 24, 6, '207', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(235, 24, 6, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(236, 25, 6, '301', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(237, 25, 6, '302', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(238, 25, 6, '303', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(239, 25, 6, '304', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(240, 25, 6, '305', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(241, 25, 6, '306', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(242, 25, 6, '307', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(243, 25, 6, '308', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(244, 25, 6, '309', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(245, 25, 6, '310', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(246, 26, 7, '001', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(247, 26, 7, '002', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(248, 26, 7, '003', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(249, 26, 7, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(250, 26, 7, '005', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(251, 26, 7, '006', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 150.00, NULL),
(252, 26, 7, '007', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(253, 26, 7, '008', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(254, 26, 7, '009', 'Double', 'back', 'Quiet Area', 1, 2, 1, 1, 160.00, NULL),
(255, 26, 7, '010', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(256, 26, 7, '011', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(257, 26, 7, '012', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(258, 27, 7, '101', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(259, 27, 7, '102', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(260, 27, 7, '103', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(261, 27, 7, '104', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(262, 27, 7, '105', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(263, 27, 7, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(264, 27, 7, '107', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(265, 27, 7, '108', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(266, 27, 7, '109', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(267, 27, 7, '110', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(268, 27, 7, '111', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(269, 27, 7, '112', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(270, 28, 7, '201', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(271, 28, 7, '202', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(272, 28, 7, '203', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(273, 28, 7, '204', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(274, 28, 7, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(275, 28, 7, '206', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(276, 28, 7, '207', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(277, 28, 7, '208', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(278, 28, 7, '209', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(279, 28, 7, '210', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(280, 29, 7, '301', 'Single', 'front', 'Street View', 1, 1, 1, 0, 180.00, NULL),
(281, 29, 7, '302', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(282, 29, 7, '303', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(283, 29, 7, '304', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(284, 29, 7, '305', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(285, 29, 7, '306', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(286, 29, 7, '307', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(287, 29, 7, '308', 'Single', 'front', 'Street View', 1, 1, 1, 0, 180.00, NULL),
(288, 29, 7, '309', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(289, 29, 7, '310', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(290, 29, 7, '311', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(291, 30, 8, '001', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(292, 30, 8, '002', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 750.00, NULL),
(293, 30, 8, '003', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 680.00, NULL),
(294, 30, 8, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 750.00, NULL),
(295, 30, 8, '005', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 750.00, NULL),
(296, 30, 8, '006', 'Single', 'front', 'Street View', 1, 1, 1, 0, 750.00, NULL),
(297, 30, 8, '007', 'Double', 'front', 'Street View', 1, 2, 2, 0, 680.00, NULL),
(298, 30, 8, '008', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 750.00, NULL),
(299, 30, 8, '009', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(300, 30, 8, '010', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(301, 30, 8, '011', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(302, 30, 8, '012', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 750.00, NULL),
(303, 31, 8, '101', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 750.00, NULL),
(304, 31, 8, '102', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(305, 31, 8, '103', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(306, 31, 8, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(307, 31, 8, '105', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(308, 31, 8, '106', 'Single', 'front', 'Street View', 1, 1, 1, 0, 750.00, NULL),
(309, 31, 8, '107', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(310, 31, 8, '108', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(311, 32, 8, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(312, 32, 8, '202', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(313, 32, 8, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 680.00, NULL),
(314, 32, 8, '204', 'Single', 'front', 'Street View', 1, 1, 1, 0, 750.00, NULL),
(315, 32, 8, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 680.00, NULL),
(316, 32, 8, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 750.00, NULL),
(317, 32, 8, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 750.00, NULL),
(318, 32, 8, '208', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 750.00, NULL),
(319, 32, 8, '209', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 750.00, NULL),
(320, 32, 8, '210', 'Double', 'front', 'Street View', 1, 2, 2, 0, 680.00, NULL),
(321, 33, 8, '301', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 620.00, NULL),
(322, 33, 8, '302', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(323, 33, 8, '303', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 620.00, NULL),
(324, 33, 8, '304', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(325, 33, 8, '305', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 680.00, NULL),
(326, 33, 8, '306', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 620.00, NULL),
(327, 33, 8, '307', 'Single', 'front', 'Street View', 1, 1, 1, 0, 750.00, NULL),
(328, 33, 8, '308', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 680.00, NULL),
(329, 33, 8, '309', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 750.00, NULL),
(330, 34, 9, '001', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 540.00, NULL),
(331, 34, 9, '002', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 510.00, NULL),
(332, 34, 9, '003', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 510.00, NULL),
(333, 34, 9, '004', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 580.00, NULL),
(334, 34, 9, '005', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 540.00, NULL),
(335, 34, 9, '006', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(336, 34, 9, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 540.00, NULL),
(337, 34, 9, '008', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 510.00, NULL),
(338, 34, 9, '009', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 540.00, NULL),
(339, 34, 9, '010', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 540.00, NULL),
(340, 34, 9, '011', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(341, 34, 9, '012', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(342, 35, 9, '101', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 540.00, NULL),
(343, 35, 9, '102', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(344, 35, 9, '103', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(345, 35, 9, '104', 'Double', 'front', 'Street View', 1, 2, 2, 0, 540.00, NULL),
(346, 35, 9, '105', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 540.00, NULL),
(347, 35, 9, '106', 'Double', 'front', 'Street View', 1, 2, 2, 0, 540.00, NULL),
(348, 35, 9, '107', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 510.00, NULL),
(349, 35, 9, '108', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 510.00, NULL),
(350, 35, 9, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 510.00, NULL),
(351, 35, 9, '110', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(352, 35, 9, '111', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 540.00, NULL),
(353, 36, 9, '201', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 510.00, NULL),
(354, 36, 9, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 540.00, NULL),
(355, 36, 9, '203', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(356, 36, 9, '204', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 540.00, NULL),
(357, 36, 9, '205', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 510.00, NULL),
(358, 36, 9, '206', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 580.00, NULL),
(359, 36, 9, '207', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 510.00, NULL),
(360, 36, 9, '208', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 510.00, NULL),
(361, 37, 10, '001', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(362, 37, 10, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(363, 37, 10, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(364, 37, 10, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(365, 37, 10, '005', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 380.00, NULL),
(366, 37, 10, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 380.00, NULL),
(367, 37, 10, '007', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 310.00, NULL),
(368, 37, 10, '008', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 340.00, NULL),
(369, 37, 10, '009', 'Double', 'front', 'Street View', 1, 2, 2, 0, 340.00, NULL),
(370, 37, 10, '010', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(371, 37, 10, '011', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 380.00, NULL),
(372, 37, 10, '012', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(373, 38, 10, '101', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 380.00, NULL),
(374, 38, 10, '102', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(375, 38, 10, '103', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(376, 38, 10, '104', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 340.00, NULL),
(377, 38, 10, '105', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 380.00, NULL),
(378, 38, 10, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(379, 38, 10, '107', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(380, 38, 10, '108', 'Single', 'front', 'Street View', 1, 1, 1, 0, 380.00, NULL),
(381, 38, 10, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(382, 38, 10, '110', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(383, 38, 10, '111', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(384, 39, 10, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 340.00, NULL),
(385, 39, 10, '202', 'Single', 'front', 'Street View', 1, 1, 1, 0, 380.00, NULL),
(386, 39, 10, '203', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(387, 39, 10, '204', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(388, 39, 10, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(389, 39, 10, '206', 'Double', 'front', 'Street View', 1, 2, 2, 0, 340.00, NULL),
(390, 39, 10, '207', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(391, 39, 10, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(392, 40, 11, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 270.00, NULL),
(393, 40, 11, '002', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(394, 40, 11, '003', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(395, 40, 11, '004', 'Double', 'back', 'Quiet Area', 1, 2, 1, 1, 270.00, NULL),
(396, 40, 11, '005', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 250.00, NULL),
(397, 40, 11, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 290.00, NULL),
(398, 40, 11, '007', 'Single', 'front', 'Street View', 1, 1, 1, 0, 290.00, NULL),
(399, 40, 11, '008', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 290.00, NULL),
(400, 40, 11, '009', 'Double', 'front', 'Street View', 1, 2, 2, 0, 270.00, NULL),
(401, 41, 11, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 270.00, NULL),
(402, 41, 11, '102', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(403, 41, 11, '103', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(404, 41, 11, '104', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 270.00, NULL),
(405, 41, 11, '105', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(406, 41, 11, '106', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 290.00, NULL),
(407, 41, 11, '107', 'Double', 'back', 'Quiet Area', 1, 2, 1, 1, 270.00, NULL),
(408, 41, 11, '108', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(409, 41, 11, '109', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 270.00, NULL),
(410, 41, 11, '110', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(411, 41, 11, '111', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 290.00, NULL),
(412, 41, 11, '112', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(413, 42, 11, '201', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(414, 42, 11, '202', 'Double', 'front', 'Street View', 1, 2, 2, 0, 270.00, NULL),
(415, 42, 11, '203', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 270.00, NULL),
(416, 42, 11, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 250.00, NULL),
(417, 42, 11, '205', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 250.00, NULL),
(418, 42, 11, '206', 'Single', 'front', 'Street View', 1, 1, 1, 0, 290.00, NULL),
(419, 42, 11, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(420, 42, 11, '208', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 270.00, NULL),
(421, 42, 11, '209', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(422, 43, 12, '001', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 400.00, NULL),
(423, 43, 12, '002', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(424, 43, 12, '003', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 430.00, NULL),
(425, 43, 12, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(426, 43, 12, '005', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 430.00, NULL),
(427, 43, 12, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 400.00, NULL),
(428, 43, 12, '007', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 400.00, NULL),
(429, 43, 12, '008', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 400.00, NULL),
(430, 43, 12, '009', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 430.00, NULL),
(431, 43, 12, '010', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 400.00, NULL),
(432, 43, 12, '011', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 430.00, NULL),
(433, 43, 12, '012', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(434, 44, 12, '101', 'Single', 'front', 'Street View', 1, 1, 1, 0, 470.00, NULL),
(435, 44, 12, '102', 'Double', 'front', 'Street View', 1, 2, 2, 0, 430.00, NULL),
(436, 44, 12, '103', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 400.00, NULL),
(437, 44, 12, '104', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 470.00, NULL),
(438, 44, 12, '105', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 400.00, NULL),
(439, 44, 12, '106', 'Double', 'front', 'Street View', 1, 2, 2, 0, 430.00, NULL),
(440, 44, 12, '107', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 400.00, NULL),
(441, 44, 12, '108', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 430.00, NULL),
(442, 44, 12, '109', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 400.00, NULL),
(443, 44, 12, '110', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 430.00, NULL),
(444, 44, 12, '111', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(445, 44, 12, '112', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 470.00, NULL),
(446, 45, 12, '201', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(447, 45, 12, '202', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 400.00, NULL),
(448, 45, 12, '203', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 400.00, NULL),
(449, 45, 12, '204', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 470.00, NULL),
(450, 45, 12, '205', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 400.00, NULL),
(451, 45, 12, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 470.00, NULL),
(452, 45, 12, '207', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 400.00, NULL),
(453, 45, 12, '208', 'Single', 'front', 'Street View', 1, 1, 1, 0, 470.00, NULL),
(454, 45, 12, '209', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 470.00, NULL),
(455, 45, 12, '210', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(456, 45, 12, '211', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(457, 46, 12, '301', 'Single', 'front', 'Street View', 1, 1, 1, 0, 470.00, NULL),
(458, 46, 12, '302', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 400.00, NULL),
(459, 46, 12, '303', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 430.00, NULL),
(460, 46, 12, '304', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(461, 46, 12, '305', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 430.00, NULL),
(462, 46, 12, '306', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 430.00, NULL),
(463, 46, 12, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 430.00, NULL),
(464, 46, 12, '308', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 400.00, NULL),
(465, 47, 13, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(466, 47, 13, '002', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(467, 47, 13, '003', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(468, 47, 13, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(469, 47, 13, '005', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(470, 47, 13, '006', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(471, 47, 13, '007', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(472, 47, 13, '008', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 520.00, NULL),
(473, 47, 13, '009', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(474, 47, 13, '010', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(475, 48, 13, '101', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(476, 48, 13, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(477, 48, 13, '103', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(478, 48, 13, '104', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(479, 48, 13, '105', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 590.00, NULL),
(480, 48, 13, '106', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(481, 48, 13, '107', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(482, 48, 13, '108', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(483, 48, 13, '109', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(484, 48, 13, '110', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(485, 48, 13, '111', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(486, 49, 13, '201', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(487, 49, 13, '202', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 550.00, NULL),
(488, 49, 13, '203', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(489, 49, 13, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 520.00, NULL),
(490, 49, 13, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(491, 49, 13, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(492, 49, 13, '207', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(493, 49, 13, '208', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(494, 49, 13, '209', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 550.00, NULL),
(495, 49, 13, '210', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(496, 49, 13, '211', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(497, 50, 13, '301', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(498, 50, 13, '302', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 590.00, NULL),
(499, 50, 13, '303', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 550.00, NULL),
(500, 50, 13, '304', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 550.00, NULL),
(501, 50, 13, '305', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(502, 50, 13, '306', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 590.00, NULL),
(503, 50, 13, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 550.00, NULL),
(504, 50, 13, '308', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(505, 50, 13, '309', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(506, 51, 14, '001', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(507, 51, 14, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(508, 51, 14, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(509, 51, 14, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(510, 51, 14, '005', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(511, 51, 14, '006', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(512, 51, 14, '007', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(513, 51, 14, '008', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(514, 51, 14, '009', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(515, 51, 14, '010', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(516, 51, 14, '011', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(517, 52, 14, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(518, 52, 14, '102', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(519, 52, 14, '103', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(520, 52, 14, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(521, 52, 14, '105', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(522, 52, 14, '106', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(523, 52, 14, '107', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 170.00, NULL),
(524, 52, 14, '108', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(525, 52, 14, '109', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(526, 52, 14, '110', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(527, 52, 14, '111', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(528, 53, 14, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(529, 53, 14, '202', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(530, 53, 14, '203', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(531, 53, 14, '204', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(532, 53, 14, '205', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(533, 53, 14, '206', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(534, 53, 14, '207', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(535, 53, 14, '208', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(536, 54, 14, '301', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(537, 54, 14, '302', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(538, 54, 14, '303', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(539, 54, 14, '304', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(540, 54, 14, '305', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(541, 54, 14, '306', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 170.00, NULL),
(542, 54, 14, '307', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(543, 54, 14, '308', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(544, 54, 14, '309', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(545, 54, 14, '310', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(546, 54, 14, '311', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(547, 55, 14, '401', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(548, 55, 14, '402', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(549, 55, 14, '403', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(550, 55, 14, '404', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(551, 55, 14, '405', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(552, 55, 14, '406', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(553, 55, 14, '407', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(554, 55, 14, '408', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(555, 55, 14, '409', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(556, 55, 14, '410', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(557, 55, 14, '411', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(558, 55, 14, '412', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(559, 56, 15, '001', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 650.00, NULL),
(560, 56, 15, '002', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 720.00, NULL),
(561, 56, 15, '003', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(562, 56, 15, '004', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 650.00, NULL),
(563, 56, 15, '005', 'Single', 'front', 'Street View', 1, 1, 1, 0, 800.00, NULL),
(564, 56, 15, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 720.00, NULL),
(565, 56, 15, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 720.00, NULL),
(566, 56, 15, '008', 'Double', 'front', 'Street View', 1, 2, 2, 0, 720.00, NULL),
(567, 57, 15, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(568, 57, 15, '102', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 720.00, NULL),
(569, 57, 15, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 800.00, NULL),
(570, 57, 15, '104', 'Single', 'front', 'Street View', 1, 1, 1, 0, 800.00, NULL),
(571, 57, 15, '105', 'Single', 'front', 'Street View', 1, 1, 1, 0, 800.00, NULL),
(572, 57, 15, '106', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 800.00, NULL),
(573, 57, 15, '107', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 650.00, NULL),
(574, 57, 15, '108', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 720.00, NULL),
(575, 57, 15, '109', 'Double', 'front', 'Street View', 1, 2, 2, 0, 720.00, NULL),
(576, 57, 15, '110', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 650.00, NULL),
(577, 58, 15, '201', 'Double', 'front', 'Street View', 1, 2, 2, 0, 720.00, NULL),
(578, 58, 15, '202', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 800.00, NULL),
(579, 58, 15, '203', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 650.00, NULL),
(580, 58, 15, '204', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 800.00, NULL),
(581, 58, 15, '205', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 800.00, NULL),
(582, 58, 15, '206', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 720.00, NULL),
(583, 58, 15, '207', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 720.00, NULL),
(584, 58, 15, '208', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 800.00, NULL),
(585, 58, 15, '209', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(586, 58, 15, '210', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(587, 58, 15, '211', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 650.00, NULL),
(588, 58, 15, '212', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 650.00, NULL),
(589, 59, 15, '301', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 650.00, NULL),
(590, 59, 15, '302', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 650.00, NULL),
(591, 59, 15, '303', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 720.00, NULL),
(592, 59, 15, '304', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(593, 59, 15, '305', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(594, 59, 15, '306', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 650.00, NULL),
(595, 59, 15, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 720.00, NULL),
(596, 59, 15, '308', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(597, 60, 15, '401', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 650.00, NULL),
(598, 60, 15, '402', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 800.00, NULL),
(599, 60, 15, '403', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 800.00, NULL),
(600, 60, 15, '404', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 650.00, NULL),
(601, 60, 15, '405', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 650.00, NULL),
(602, 60, 15, '406', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 650.00, NULL),
(603, 60, 15, '407', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 800.00, NULL),
(604, 60, 15, '408', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 650.00, NULL),
(605, 60, 15, '409', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 800.00, NULL),
(606, 60, 15, '410', 'Single', 'front', 'Street View', 1, 1, 1, 0, 800.00, NULL),
(607, 60, 15, '411', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 720.00, NULL),
(608, 60, 15, '412', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 720.00, NULL),
(609, 61, 16, '001', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(610, 61, 16, '002', 'Double', 'front', 'Street View', 1, 2, 2, 0, 330.00, NULL),
(611, 61, 16, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 300.00, NULL),
(612, 61, 16, '004', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(613, 61, 16, '005', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 330.00, NULL),
(614, 61, 16, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(615, 61, 16, '007', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(616, 61, 16, '008', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 370.00, NULL),
(617, 61, 16, '009', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(618, 61, 16, '010', 'Double', 'front', 'Street View', 1, 2, 2, 0, 330.00, NULL),
(619, 61, 16, '011', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(620, 61, 16, '012', 'Double', 'front', 'Street View', 1, 2, 2, 0, 330.00, NULL),
(621, 62, 16, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 330.00, NULL),
(622, 62, 16, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(623, 62, 16, '103', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(624, 62, 16, '104', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(625, 62, 16, '105', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(626, 62, 16, '106', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 300.00, NULL),
(627, 62, 16, '107', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 330.00, NULL);
INSERT INTO `dorm_rooms` (`room_id`, `floor_id`, `dorm_id`, `room_number`, `room_type`, `position`, `view_type`, `is_available`, `capacity`, `available_spots`, `current_occupancy`, `price_per_month`, `features`) VALUES
(628, 62, 16, '108', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 330.00, NULL),
(629, 62, 16, '109', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 330.00, NULL),
(630, 62, 16, '110', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 370.00, NULL),
(631, 63, 16, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(632, 63, 16, '202', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(633, 63, 16, '203', 'Double', 'front', 'Street View', 1, 2, 2, 0, 330.00, NULL),
(634, 63, 16, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(635, 63, 16, '205', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(636, 63, 16, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 370.00, NULL),
(637, 63, 16, '207', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 300.00, NULL),
(638, 63, 16, '208', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(639, 63, 16, '209', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 300.00, NULL),
(640, 63, 16, '210', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(641, 63, 16, '211', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(642, 64, 16, '301', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(643, 64, 16, '302', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(644, 64, 16, '303', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(645, 64, 16, '304', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(646, 64, 16, '305', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(647, 64, 16, '306', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 370.00, NULL),
(648, 64, 16, '307', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 330.00, NULL),
(649, 64, 16, '308', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 330.00, NULL),
(650, 64, 16, '309', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(651, 65, 16, '401', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(652, 65, 16, '402', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 300.00, NULL),
(653, 65, 16, '403', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(654, 65, 16, '404', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 330.00, NULL),
(655, 65, 16, '405', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(656, 65, 16, '406', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 330.00, NULL),
(657, 65, 16, '407', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(658, 65, 16, '408', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 300.00, NULL),
(659, 65, 16, '409', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 370.00, NULL),
(660, 65, 16, '410', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 300.00, NULL),
(661, 65, 16, '411', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(662, 65, 16, '412', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 330.00, NULL),
(663, 66, 17, '001', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 260.00, NULL),
(664, 66, 17, '002', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 260.00, NULL),
(665, 66, 17, '003', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 295.00, NULL),
(666, 66, 17, '004', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 275.00, NULL),
(667, 66, 17, '005', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(668, 66, 17, '006', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(669, 66, 17, '007', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 260.00, NULL),
(670, 66, 17, '008', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 275.00, NULL),
(671, 67, 17, '101', 'Single', 'front', 'Street View', 1, 1, 1, 0, 295.00, NULL),
(672, 67, 17, '102', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(673, 67, 17, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 295.00, NULL),
(674, 67, 17, '104', 'Single', 'front', 'Street View', 1, 1, 1, 0, 295.00, NULL),
(675, 67, 17, '105', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 275.00, NULL),
(676, 67, 17, '106', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(677, 67, 17, '107', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(678, 67, 17, '108', 'Double', 'front', 'Street View', 1, 2, 2, 0, 275.00, NULL),
(679, 67, 17, '109', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 295.00, NULL),
(680, 68, 17, '201', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(681, 68, 17, '202', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 295.00, NULL),
(682, 68, 17, '203', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 260.00, NULL),
(683, 68, 17, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 260.00, NULL),
(684, 68, 17, '205', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 295.00, NULL),
(685, 68, 17, '206', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 260.00, NULL),
(686, 68, 17, '207', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 295.00, NULL),
(687, 68, 17, '208', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 295.00, NULL),
(688, 68, 17, '209', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 275.00, NULL),
(689, 68, 17, '210', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 260.00, NULL),
(690, 68, 17, '211', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 275.00, NULL),
(691, 69, 18, '001', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 515.00, NULL),
(692, 69, 18, '002', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 585.00, NULL),
(693, 69, 18, '003', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 515.00, NULL),
(694, 69, 18, '004', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 545.00, NULL),
(695, 69, 18, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 545.00, NULL),
(696, 69, 18, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 585.00, NULL),
(697, 69, 18, '007', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 585.00, NULL),
(698, 69, 18, '008', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 515.00, NULL),
(699, 69, 18, '009', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 545.00, NULL),
(700, 69, 18, '010', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 515.00, NULL),
(701, 69, 18, '011', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 585.00, NULL),
(702, 69, 18, '012', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 585.00, NULL),
(703, 70, 18, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 545.00, NULL),
(704, 70, 18, '102', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 545.00, NULL),
(705, 70, 18, '103', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 515.00, NULL),
(706, 70, 18, '104', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 515.00, NULL),
(707, 70, 18, '105', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 585.00, NULL),
(708, 70, 18, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 585.00, NULL),
(709, 70, 18, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 515.00, NULL),
(710, 70, 18, '108', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 515.00, NULL),
(711, 70, 18, '109', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 515.00, NULL),
(712, 70, 18, '110', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 585.00, NULL),
(713, 70, 18, '111', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 545.00, NULL),
(714, 70, 18, '112', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 515.00, NULL),
(715, 71, 18, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 545.00, NULL),
(716, 71, 18, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 545.00, NULL),
(717, 71, 18, '203', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 515.00, NULL),
(718, 71, 18, '204', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 545.00, NULL),
(719, 71, 18, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 545.00, NULL),
(720, 71, 18, '206', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 545.00, NULL),
(721, 71, 18, '207', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 515.00, NULL),
(722, 71, 18, '208', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 545.00, NULL),
(723, 71, 18, '209', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 515.00, NULL),
(724, 72, 19, '001', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(725, 72, 19, '002', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(726, 72, 19, '003', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 165.00, NULL),
(727, 72, 19, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(728, 72, 19, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(729, 72, 19, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 165.00, NULL),
(730, 72, 19, '007', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(731, 72, 19, '008', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(732, 73, 19, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(733, 73, 19, '102', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(734, 73, 19, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 185.00, NULL),
(735, 73, 19, '104', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(736, 73, 19, '105', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(737, 73, 19, '106', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(738, 73, 19, '107', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(739, 73, 19, '108', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 185.00, NULL),
(740, 73, 19, '109', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(741, 73, 19, '110', 'Single', 'front', 'Street View', 1, 1, 1, 0, 185.00, NULL),
(742, 73, 19, '111', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(743, 74, 19, '201', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(744, 74, 19, '202', 'Single', 'front', 'Street View', 1, 1, 1, 0, 185.00, NULL),
(745, 74, 19, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 165.00, NULL),
(746, 74, 19, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(747, 74, 19, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(748, 74, 19, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(749, 74, 19, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(750, 74, 19, '208', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(751, 74, 19, '209', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(752, 74, 19, '210', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 185.00, NULL),
(753, 74, 19, '211', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(754, 74, 19, '212', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(755, 75, 19, '301', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(756, 75, 19, '302', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(757, 75, 19, '303', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(758, 75, 19, '304', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(759, 75, 19, '305', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(760, 75, 19, '306', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(761, 75, 19, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 165.00, NULL),
(762, 75, 19, '308', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(763, 75, 19, '309', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(764, 76, 19, '401', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(765, 76, 19, '402', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 185.00, NULL),
(766, 76, 19, '403', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(767, 76, 19, '404', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(768, 76, 19, '405', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(769, 76, 19, '406', 'Double', 'front', 'Street View', 1, 2, 2, 0, 165.00, NULL),
(770, 76, 19, '407', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(771, 76, 19, '408', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(772, 76, 19, '409', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(773, 77, 20, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(774, 77, 20, '002', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 490.00, NULL),
(775, 77, 20, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(776, 77, 20, '004', 'Double', 'front', 'Street View', 1, 2, 2, 0, 450.00, NULL),
(777, 77, 20, '005', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 420.00, NULL),
(778, 77, 20, '006', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 490.00, NULL),
(779, 77, 20, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(780, 77, 20, '008', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 450.00, NULL),
(781, 77, 20, '009', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 450.00, NULL),
(782, 77, 20, '010', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(783, 77, 20, '011', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(784, 77, 20, '012', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 420.00, NULL),
(785, 78, 20, '101', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 420.00, NULL),
(786, 78, 20, '102', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(787, 78, 20, '103', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(788, 78, 20, '104', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(789, 78, 20, '105', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(790, 78, 20, '106', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(791, 78, 20, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(792, 78, 20, '108', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 490.00, NULL),
(793, 78, 20, '109', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(794, 78, 20, '110', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(795, 78, 20, '111', 'Double', 'front', 'Street View', 1, 2, 2, 0, 450.00, NULL),
(796, 79, 20, '201', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 450.00, NULL),
(797, 79, 20, '202', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(798, 79, 20, '203', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(799, 79, 20, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 420.00, NULL),
(800, 79, 20, '205', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(801, 79, 20, '206', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(802, 79, 20, '207', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 450.00, NULL),
(803, 79, 20, '208', 'Double', 'front', 'Street View', 1, 2, 2, 0, 450.00, NULL),
(804, 79, 20, '209', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(805, 79, 20, '210', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(806, 80, 21, '001', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(807, 80, 21, '002', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(808, 80, 21, '003', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(809, 80, 21, '004', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(810, 80, 21, '005', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(811, 80, 21, '006', 'Single', 'front', 'Street View', 1, 1, 1, 0, 180.00, NULL),
(812, 80, 21, '007', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(813, 80, 21, '008', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(814, 80, 21, '009', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(815, 80, 21, '010', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 150.00, NULL),
(816, 80, 21, '011', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(817, 81, 21, '101', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(818, 81, 21, '102', 'Single', 'front', 'Street View', 1, 1, 1, 0, 180.00, NULL),
(819, 81, 21, '103', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(820, 81, 21, '104', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(821, 81, 21, '105', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(822, 81, 21, '106', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(823, 81, 21, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 150.00, NULL),
(824, 81, 21, '108', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(825, 81, 21, '109', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(826, 81, 21, '110', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(827, 82, 21, '201', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(828, 82, 21, '202', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(829, 82, 21, '203', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 150.00, NULL),
(830, 82, 21, '204', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(831, 82, 21, '205', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(832, 82, 21, '206', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 150.00, NULL),
(833, 82, 21, '207', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(834, 82, 21, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(835, 82, 21, '209', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 160.00, NULL),
(836, 82, 21, '210', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(837, 82, 21, '211', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(838, 82, 21, '212', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 180.00, NULL),
(839, 83, 21, '301', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 150.00, NULL),
(840, 83, 21, '302', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(841, 83, 21, '303', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(842, 83, 21, '304', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(843, 83, 21, '305', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(844, 83, 21, '306', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(845, 83, 21, '307', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(846, 83, 21, '308', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(847, 83, 21, '309', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(848, 84, 21, '401', 'Double', 'front', 'Street View', 1, 2, 2, 0, 160.00, NULL),
(849, 84, 21, '402', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(850, 84, 21, '403', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(851, 84, 21, '404', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 180.00, NULL),
(852, 84, 21, '405', 'Single', 'front', 'Street View', 1, 1, 1, 0, 180.00, NULL),
(853, 84, 21, '406', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 150.00, NULL),
(854, 84, 21, '407', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 160.00, NULL),
(855, 84, 21, '408', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 150.00, NULL),
(856, 84, 21, '409', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 180.00, NULL),
(857, 84, 21, '410', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 160.00, NULL),
(858, 85, 22, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(859, 85, 22, '002', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(860, 85, 22, '003', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(861, 85, 22, '004', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(862, 85, 22, '005', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(863, 85, 22, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 170.00, NULL),
(864, 85, 22, '007', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(865, 85, 22, '008', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(866, 86, 22, '101', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(867, 86, 22, '102', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(868, 86, 22, '103', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(869, 86, 22, '104', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(870, 86, 22, '105', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 160.00, NULL),
(871, 86, 22, '106', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(872, 86, 22, '107', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 170.00, NULL),
(873, 86, 22, '108', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(874, 87, 22, '201', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 160.00, NULL),
(875, 87, 22, '202', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(876, 87, 22, '203', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(877, 87, 22, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(878, 87, 22, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(879, 87, 22, '206', 'Single', 'front', 'Street View', 1, 1, 1, 0, 190.00, NULL),
(880, 87, 22, '207', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(881, 87, 22, '208', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(882, 87, 22, '209', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 170.00, NULL),
(883, 87, 22, '210', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 190.00, NULL),
(884, 87, 22, '211', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 160.00, NULL),
(885, 88, 22, '301', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(886, 88, 22, '302', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(887, 88, 22, '303', 'Double', 'front', 'Street View', 1, 2, 2, 0, 170.00, NULL),
(888, 88, 22, '304', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 190.00, NULL),
(889, 88, 22, '305', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 170.00, NULL),
(890, 88, 22, '306', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 170.00, NULL),
(891, 88, 22, '307', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(892, 88, 22, '308', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 160.00, NULL),
(893, 88, 22, '309', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 190.00, NULL),
(894, 89, 23, '001', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 155.00, NULL),
(895, 89, 23, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(896, 89, 23, '003', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(897, 89, 23, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(898, 89, 23, '005', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(899, 89, 23, '006', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(900, 89, 23, '007', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(901, 89, 23, '008', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(902, 90, 23, '101', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(903, 90, 23, '102', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(904, 90, 23, '103', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(905, 90, 23, '104', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(906, 90, 23, '105', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(907, 90, 23, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(908, 90, 23, '107', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(909, 90, 23, '108', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(910, 90, 23, '109', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 165.00, NULL),
(911, 91, 23, '201', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(912, 91, 23, '202', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(913, 91, 23, '203', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 155.00, NULL),
(914, 91, 23, '204', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(915, 91, 23, '205', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(916, 91, 23, '206', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(917, 91, 23, '207', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 155.00, NULL),
(918, 91, 23, '208', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(919, 92, 23, '301', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(920, 92, 23, '302', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(921, 92, 23, '303', 'Single', 'front', 'Street View', 1, 1, 1, 0, 185.00, NULL),
(922, 92, 23, '304', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(923, 92, 23, '305', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 165.00, NULL),
(924, 92, 23, '306', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 155.00, NULL),
(925, 92, 23, '307', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(926, 92, 23, '308', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 165.00, NULL),
(927, 92, 23, '309', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 185.00, NULL),
(928, 92, 23, '310', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 185.00, NULL),
(929, 92, 23, '311', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 185.00, NULL),
(930, 93, 24, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(931, 93, 24, '002', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(932, 93, 24, '003', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(933, 93, 24, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(934, 93, 24, '005', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(935, 93, 24, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(936, 93, 24, '007', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(937, 93, 24, '008', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(938, 93, 24, '009', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(939, 93, 24, '010', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(940, 94, 24, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(941, 94, 24, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(942, 94, 24, '103', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(943, 94, 24, '104', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(944, 94, 24, '105', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(945, 94, 24, '106', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(946, 94, 24, '107', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(947, 94, 24, '108', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 200.00, NULL),
(948, 94, 24, '109', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 200.00, NULL),
(949, 94, 24, '110', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 240.00, NULL),
(950, 94, 24, '111', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(951, 95, 24, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(952, 95, 24, '202', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(953, 95, 24, '203', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(954, 95, 24, '204', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(955, 95, 24, '205', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(956, 95, 24, '206', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 240.00, NULL),
(957, 95, 24, '207', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(958, 95, 24, '208', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(959, 95, 24, '209', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(960, 95, 24, '210', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 200.00, NULL),
(961, 95, 24, '211', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(962, 95, 24, '212', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(963, 96, 24, '301', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 200.00, NULL),
(964, 96, 24, '302', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(965, 96, 24, '303', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(966, 96, 24, '304', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(967, 96, 24, '305', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(968, 96, 24, '306', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(969, 96, 24, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(970, 96, 24, '308', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(971, 97, 24, '401', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(972, 97, 24, '402', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 220.00, NULL),
(973, 97, 24, '403', 'Single', 'front', 'Street View', 1, 1, 1, 0, 240.00, NULL),
(974, 97, 24, '404', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 200.00, NULL),
(975, 97, 24, '405', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(976, 97, 24, '406', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 240.00, NULL),
(977, 97, 24, '407', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 220.00, NULL),
(978, 97, 24, '408', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 220.00, NULL),
(979, 97, 24, '409', 'Double', 'front', 'Street View', 1, 2, 2, 0, 220.00, NULL),
(980, 98, 25, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 235.00, NULL),
(981, 98, 25, '002', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 235.00, NULL),
(982, 98, 25, '003', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(983, 98, 25, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 205.00, NULL),
(984, 98, 25, '005', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 205.00, NULL),
(985, 98, 25, '006', 'Double', 'front', 'Street View', 1, 2, 2, 0, 215.00, NULL),
(986, 98, 25, '007', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 235.00, NULL),
(987, 98, 25, '008', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 235.00, NULL),
(988, 98, 25, '009', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(989, 98, 25, '010', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 205.00, NULL),
(990, 98, 25, '011', 'Double', 'front', 'Street View', 1, 2, 2, 0, 215.00, NULL),
(991, 98, 25, '012', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(992, 99, 25, '101', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 215.00, NULL),
(993, 99, 25, '102', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(994, 99, 25, '103', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 215.00, NULL),
(995, 99, 25, '104', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 205.00, NULL),
(996, 99, 25, '105', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(997, 99, 25, '106', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(998, 99, 25, '107', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(999, 99, 25, '108', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(1000, 100, 25, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(1001, 100, 25, '202', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 205.00, NULL),
(1002, 100, 25, '203', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(1003, 100, 25, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 215.00, NULL),
(1004, 100, 25, '205', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 205.00, NULL),
(1005, 100, 25, '206', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 205.00, NULL),
(1006, 100, 25, '207', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 215.00, NULL),
(1007, 100, 25, '208', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 205.00, NULL),
(1008, 100, 25, '209', 'Double', 'front', 'Street View', 1, 2, 2, 0, 215.00, NULL),
(1009, 100, 25, '210', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 215.00, NULL),
(1010, 100, 25, '211', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(1011, 100, 25, '212', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 215.00, NULL),
(1012, 101, 25, '301', 'Single', 'front', 'Street View', 1, 1, 1, 0, 235.00, NULL),
(1013, 101, 25, '302', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 205.00, NULL),
(1014, 101, 25, '303', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 215.00, NULL),
(1015, 101, 25, '304', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 205.00, NULL),
(1016, 101, 25, '305', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 205.00, NULL),
(1017, 101, 25, '306', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 205.00, NULL),
(1018, 101, 25, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 215.00, NULL),
(1019, 101, 25, '308', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 205.00, NULL),
(1020, 102, 26, '001', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1021, 102, 26, '002', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1022, 102, 26, '003', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1023, 102, 26, '004', 'Single', 'front', 'Street View', 1, 1, 1, 0, 245.00, NULL),
(1024, 102, 26, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 225.00, NULL),
(1025, 102, 26, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 210.00, NULL),
(1026, 102, 26, '007', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 210.00, NULL),
(1027, 102, 26, '008', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1028, 103, 26, '101', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1029, 103, 26, '102', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 210.00, NULL),
(1030, 103, 26, '103', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1031, 103, 26, '104', 'Single', 'front', 'Street View', 1, 1, 1, 0, 245.00, NULL),
(1032, 103, 26, '105', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1033, 103, 26, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 210.00, NULL),
(1034, 103, 26, '107', 'Double', 'front', 'Street View', 1, 2, 2, 0, 225.00, NULL),
(1035, 103, 26, '108', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1036, 103, 26, '109', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1037, 103, 26, '110', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1038, 103, 26, '111', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1039, 104, 26, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1040, 104, 26, '202', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 225.00, NULL),
(1041, 104, 26, '203', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 245.00, NULL),
(1042, 104, 26, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 225.00, NULL),
(1043, 104, 26, '205', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 210.00, NULL),
(1044, 104, 26, '206', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1045, 104, 26, '207', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 245.00, NULL),
(1046, 104, 26, '208', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1047, 104, 26, '209', 'Single', 'front', 'Street View', 1, 1, 1, 0, 245.00, NULL),
(1048, 104, 26, '210', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 210.00, NULL),
(1049, 104, 26, '211', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 225.00, NULL),
(1050, 104, 26, '212', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1051, 105, 26, '301', 'Single', 'front', 'Street View', 1, 1, 1, 0, 245.00, NULL),
(1052, 105, 26, '302', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 245.00, NULL),
(1053, 105, 26, '303', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 245.00, NULL),
(1054, 105, 26, '304', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1055, 105, 26, '305', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 245.00, NULL),
(1056, 105, 26, '306', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 245.00, NULL),
(1057, 105, 26, '307', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1058, 105, 26, '308', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 245.00, NULL),
(1059, 105, 26, '309', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 245.00, NULL),
(1060, 105, 26, '310', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1061, 105, 26, '311', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 225.00, NULL),
(1062, 106, 26, '401', 'Double', 'front', 'Street View', 1, 2, 2, 0, 225.00, NULL),
(1063, 106, 26, '402', 'Double', 'front', 'Street View', 1, 2, 2, 0, 225.00, NULL),
(1064, 106, 26, '403', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 225.00, NULL),
(1065, 106, 26, '404', 'Double', 'front', 'Street View', 1, 2, 2, 0, 225.00, NULL),
(1066, 106, 26, '405', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 245.00, NULL),
(1067, 106, 26, '406', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 245.00, NULL),
(1068, 106, 26, '407', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 210.00, NULL),
(1069, 106, 26, '408', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 225.00, NULL),
(1070, 106, 26, '409', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 245.00, NULL),
(1071, 106, 26, '410', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 210.00, NULL),
(1072, 106, 26, '411', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 245.00, NULL),
(1073, 106, 26, '412', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 210.00, NULL),
(1074, 107, 27, '001', 'Double', 'front', 'Street View', 1, 2, 2, 0, 270.00, NULL),
(1075, 107, 27, '002', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(1076, 107, 27, '003', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 270.00, NULL),
(1077, 107, 27, '004', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 270.00, NULL),
(1078, 107, 27, '005', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(1079, 107, 27, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 250.00, NULL),
(1080, 107, 27, '007', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(1081, 107, 27, '008', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 270.00, NULL),
(1082, 108, 27, '101', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 290.00, NULL),
(1083, 108, 27, '102', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(1084, 108, 27, '103', 'Double', 'front', 'Street View', 1, 2, 2, 0, 270.00, NULL),
(1085, 108, 27, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(1086, 108, 27, '105', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 250.00, NULL),
(1087, 108, 27, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 250.00, NULL),
(1088, 108, 27, '107', 'Single', 'front', 'Street View', 1, 1, 1, 0, 290.00, NULL),
(1089, 108, 27, '108', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 290.00, NULL),
(1090, 109, 27, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 290.00, NULL),
(1091, 109, 27, '202', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 250.00, NULL),
(1092, 109, 27, '203', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 290.00, NULL),
(1093, 109, 27, '204', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 250.00, NULL),
(1094, 109, 27, '205', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 290.00, NULL),
(1095, 109, 27, '206', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 250.00, NULL),
(1096, 109, 27, '207', 'Single', 'front', 'Street View', 1, 1, 1, 0, 290.00, NULL),
(1097, 109, 27, '208', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 290.00, NULL),
(1098, 109, 27, '209', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 290.00, NULL),
(1099, 109, 27, '210', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 270.00, NULL),
(1100, 110, 28, '001', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1101, 110, 28, '002', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(1102, 110, 28, '003', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1103, 110, 28, '004', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(1104, 110, 28, '005', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(1105, 110, 28, '006', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(1106, 110, 28, '007', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(1107, 110, 28, '008', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1108, 110, 28, '009', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(1109, 110, 28, '010', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1110, 111, 28, '101', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(1111, 111, 28, '102', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(1112, 111, 28, '103', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(1113, 111, 28, '104', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(1114, 111, 28, '105', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(1115, 111, 28, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(1116, 111, 28, '107', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(1117, 111, 28, '108', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1118, 111, 28, '109', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(1119, 111, 28, '110', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1120, 111, 28, '111', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(1121, 112, 28, '201', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1122, 112, 28, '202', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1123, 112, 28, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 265.00, NULL),
(1124, 112, 28, '204', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(1125, 112, 28, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1126, 112, 28, '206', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(1127, 112, 28, '207', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(1128, 112, 28, '208', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(1129, 112, 28, '209', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(1130, 112, 28, '210', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(1131, 112, 28, '211', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(1132, 113, 28, '301', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 255.00, NULL),
(1133, 113, 28, '302', 'Double', 'front', 'Street View', 1, 2, 2, 0, 265.00, NULL),
(1134, 113, 28, '303', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 265.00, NULL),
(1135, 113, 28, '304', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(1136, 113, 28, '305', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(1137, 113, 28, '306', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 255.00, NULL),
(1138, 113, 28, '307', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 285.00, NULL),
(1139, 113, 28, '308', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(1140, 113, 28, '309', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(1141, 113, 28, '310', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 285.00, NULL),
(1142, 114, 28, '401', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 265.00, NULL),
(1143, 114, 28, '402', 'Single', 'front', 'Street View', 1, 1, 1, 0, 285.00, NULL),
(1144, 114, 28, '403', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(1145, 114, 28, '404', 'Double', 'front', 'Street View', 1, 2, 2, 0, 265.00, NULL),
(1146, 114, 28, '405', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1147, 114, 28, '406', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 255.00, NULL),
(1148, 114, 28, '407', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1149, 114, 28, '408', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 255.00, NULL),
(1150, 114, 28, '409', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 285.00, NULL),
(1151, 115, 29, '001', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 260.00, NULL),
(1152, 115, 29, '002', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(1153, 115, 29, '003', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 275.00, NULL),
(1154, 115, 29, '004', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 295.00, NULL),
(1155, 115, 29, '005', 'Single', 'front', 'Street View', 1, 1, 1, 0, 295.00, NULL),
(1156, 115, 29, '006', 'Double', 'front', 'Street View', 1, 2, 2, 0, 275.00, NULL),
(1157, 115, 29, '007', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 260.00, NULL),
(1158, 115, 29, '008', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 260.00, NULL),
(1159, 115, 29, '009', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 260.00, NULL),
(1160, 116, 29, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(1161, 116, 29, '102', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 260.00, NULL),
(1162, 116, 29, '103', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 275.00, NULL),
(1163, 116, 29, '104', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 260.00, NULL),
(1164, 116, 29, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 260.00, NULL),
(1165, 116, 29, '106', 'Single', 'front', 'Street View', 1, 1, 1, 0, 295.00, NULL),
(1166, 116, 29, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 260.00, NULL),
(1167, 116, 29, '108', 'Single', 'front', 'Street View', 1, 1, 1, 0, 295.00, NULL),
(1168, 116, 29, '109', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(1169, 116, 29, '110', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(1170, 117, 29, '201', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 260.00, NULL),
(1171, 117, 29, '202', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 260.00, NULL),
(1172, 117, 29, '203', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(1173, 117, 29, '204', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 295.00, NULL),
(1174, 117, 29, '205', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 275.00, NULL),
(1175, 117, 29, '206', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 275.00, NULL),
(1176, 117, 29, '207', 'Double', 'front', 'Street View', 1, 2, 2, 0, 275.00, NULL),
(1177, 117, 29, '208', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 295.00, NULL),
(1178, 117, 29, '209', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 275.00, NULL),
(1179, 117, 29, '210', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 295.00, NULL),
(1180, 118, 30, '001', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 350.00, NULL),
(1181, 118, 30, '002', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1182, 118, 30, '003', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 380.00, NULL),
(1183, 118, 30, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 380.00, NULL),
(1184, 118, 30, '005', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 320.00, NULL),
(1185, 118, 30, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1186, 118, 30, '007', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(1187, 118, 30, '008', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(1188, 118, 30, '009', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(1189, 118, 30, '010', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(1190, 118, 30, '011', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1191, 118, 30, '012', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(1192, 119, 30, '101', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(1193, 119, 30, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 380.00, NULL),
(1194, 119, 30, '103', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 380.00, NULL),
(1195, 119, 30, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1196, 119, 30, '105', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(1197, 119, 30, '106', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1198, 119, 30, '107', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(1199, 119, 30, '108', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1200, 119, 30, '109', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(1201, 119, 30, '110', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1202, 119, 30, '111', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(1203, 119, 30, '112', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1204, 120, 30, '201', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(1205, 120, 30, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(1206, 120, 30, '203', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(1207, 120, 30, '204', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 380.00, NULL),
(1208, 120, 30, '205', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1209, 120, 30, '206', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 350.00, NULL),
(1210, 120, 30, '207', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(1211, 120, 30, '208', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1212, 121, 30, '301', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(1213, 121, 30, '302', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 320.00, NULL),
(1214, 121, 30, '303', 'Single', 'front', 'Street View', 1, 1, 1, 0, 380.00, NULL),
(1215, 121, 30, '304', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 350.00, NULL),
(1216, 121, 30, '305', 'Double', 'front', 'Street View', 1, 2, 2, 0, 350.00, NULL),
(1217, 121, 30, '306', 'Single', 'front', 'Street View', 1, 1, 1, 0, 380.00, NULL),
(1218, 121, 30, '307', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 380.00, NULL),
(1219, 121, 30, '308', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 350.00, NULL),
(1220, 121, 30, '309', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(1221, 121, 30, '310', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 320.00, NULL),
(1222, 121, 30, '311', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 320.00, NULL),
(1223, 122, 31, '001', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 360.00, NULL),
(1224, 122, 31, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 330.00, NULL),
(1225, 122, 31, '003', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(1226, 122, 31, '004', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 360.00, NULL),
(1227, 122, 31, '005', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 330.00, NULL),
(1228, 122, 31, '006', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 360.00, NULL),
(1229, 122, 31, '007', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1230, 122, 31, '008', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1231, 122, 31, '009', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(1232, 122, 31, '010', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 390.00, NULL),
(1233, 122, 31, '011', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 360.00, NULL),
(1234, 123, 31, '101', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 330.00, NULL),
(1235, 123, 31, '102', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 330.00, NULL),
(1236, 123, 31, '103', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 360.00, NULL),
(1237, 123, 31, '104', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 360.00, NULL),
(1238, 123, 31, '105', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 330.00, NULL),
(1239, 123, 31, '106', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1240, 123, 31, '107', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 330.00, NULL),
(1241, 123, 31, '108', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 330.00, NULL);
INSERT INTO `dorm_rooms` (`room_id`, `floor_id`, `dorm_id`, `room_number`, `room_type`, `position`, `view_type`, `is_available`, `capacity`, `available_spots`, `current_occupancy`, `price_per_month`, `features`) VALUES
(1242, 123, 31, '109', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(1243, 123, 31, '110', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 330.00, NULL),
(1244, 123, 31, '111', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(1245, 123, 31, '112', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 360.00, NULL),
(1246, 124, 31, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 390.00, NULL),
(1247, 124, 31, '202', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1248, 124, 31, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 360.00, NULL),
(1249, 124, 31, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1250, 124, 31, '205', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 330.00, NULL),
(1251, 124, 31, '206', 'Double', 'front', 'Street View', 1, 2, 2, 0, 360.00, NULL),
(1252, 124, 31, '207', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 330.00, NULL),
(1253, 124, 31, '208', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 330.00, NULL),
(1254, 124, 31, '209', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 390.00, NULL),
(1255, 125, 32, '001', 'Double', 'front', 'Street View', 1, 2, 2, 0, 340.00, NULL),
(1256, 125, 32, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1257, 125, 32, '003', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(1258, 125, 32, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(1259, 125, 32, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1260, 125, 32, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 370.00, NULL),
(1261, 125, 32, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1262, 125, 32, '008', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1263, 126, 32, '101', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1264, 126, 32, '102', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1265, 126, 32, '103', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(1266, 126, 32, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(1267, 126, 32, '105', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 370.00, NULL),
(1268, 126, 32, '106', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1269, 126, 32, '107', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1270, 126, 32, '108', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(1271, 127, 32, '201', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1272, 127, 32, '202', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 340.00, NULL),
(1273, 127, 32, '203', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 310.00, NULL),
(1274, 127, 32, '204', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1275, 127, 32, '205', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(1276, 127, 32, '206', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 370.00, NULL),
(1277, 127, 32, '207', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(1278, 127, 32, '208', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1279, 127, 32, '209', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 340.00, NULL),
(1280, 127, 32, '210', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1281, 128, 32, '301', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(1282, 128, 32, '302', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(1283, 128, 32, '303', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1284, 128, 32, '304', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1285, 128, 32, '305', 'Double', 'front', 'Street View', 1, 2, 2, 0, 340.00, NULL),
(1286, 128, 32, '306', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(1287, 128, 32, '307', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1288, 128, 32, '308', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1289, 128, 32, '309', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(1290, 128, 32, '310', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(1291, 128, 32, '311', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(1292, 128, 32, '312', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1293, 129, 32, '401', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 310.00, NULL),
(1294, 129, 32, '402', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(1295, 129, 32, '403', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1296, 129, 32, '404', 'Single', 'front', 'Street View', 1, 1, 1, 0, 370.00, NULL),
(1297, 129, 32, '405', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1298, 129, 32, '406', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1299, 129, 32, '407', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 370.00, NULL),
(1300, 129, 32, '408', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 310.00, NULL),
(1301, 129, 32, '409', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 310.00, NULL),
(1302, 129, 32, '410', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 310.00, NULL),
(1303, 129, 32, '411', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 340.00, NULL),
(1304, 129, 32, '412', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 340.00, NULL),
(1305, 130, 33, '001', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 335.00, NULL),
(1306, 130, 33, '002', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1307, 130, 33, '003', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 395.00, NULL),
(1308, 130, 33, '004', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1309, 130, 33, '005', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 395.00, NULL),
(1310, 130, 33, '006', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 365.00, NULL),
(1311, 130, 33, '007', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 335.00, NULL),
(1312, 130, 33, '008', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1313, 130, 33, '009', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 335.00, NULL),
(1314, 130, 33, '010', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 365.00, NULL),
(1315, 130, 33, '011', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 335.00, NULL),
(1316, 130, 33, '012', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 395.00, NULL),
(1317, 131, 33, '101', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 335.00, NULL),
(1318, 131, 33, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 395.00, NULL),
(1319, 131, 33, '103', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1320, 131, 33, '104', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 365.00, NULL),
(1321, 131, 33, '105', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1322, 131, 33, '106', 'Single', 'front', 'Street View', 1, 1, 1, 0, 395.00, NULL),
(1323, 131, 33, '107', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 395.00, NULL),
(1324, 131, 33, '108', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 365.00, NULL),
(1325, 131, 33, '109', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 335.00, NULL),
(1326, 132, 33, '201', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 395.00, NULL),
(1327, 132, 33, '202', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 395.00, NULL),
(1328, 132, 33, '203', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1329, 132, 33, '204', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 365.00, NULL),
(1330, 132, 33, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 365.00, NULL),
(1331, 132, 33, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 395.00, NULL),
(1332, 132, 33, '207', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 365.00, NULL),
(1333, 132, 33, '208', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 365.00, NULL),
(1334, 132, 33, '209', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 365.00, NULL),
(1335, 133, 34, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(1336, 133, 34, '002', 'Single', 'front', 'Street View', 1, 1, 1, 0, 480.00, NULL),
(1337, 133, 34, '003', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(1338, 133, 34, '004', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(1339, 133, 34, '005', 'Single', 'front', 'Street View', 1, 1, 1, 0, 480.00, NULL),
(1340, 133, 34, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(1341, 133, 34, '007', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(1342, 133, 34, '008', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 480.00, NULL),
(1343, 134, 34, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(1344, 134, 34, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 480.00, NULL),
(1345, 134, 34, '103', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(1346, 134, 34, '104', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 450.00, NULL),
(1347, 134, 34, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(1348, 134, 34, '106', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 450.00, NULL),
(1349, 134, 34, '107', 'Double', 'front', 'Street View', 1, 2, 2, 0, 450.00, NULL),
(1350, 134, 34, '108', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(1351, 134, 34, '109', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(1352, 134, 34, '110', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(1353, 134, 34, '111', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 480.00, NULL),
(1354, 134, 34, '112', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(1355, 135, 34, '201', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 480.00, NULL),
(1356, 135, 34, '202', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 450.00, NULL),
(1357, 135, 34, '203', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(1358, 135, 34, '204', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(1359, 135, 34, '205', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 420.00, NULL),
(1360, 135, 34, '206', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 480.00, NULL),
(1361, 135, 34, '207', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 480.00, NULL),
(1362, 135, 34, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 420.00, NULL),
(1363, 135, 34, '209', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 480.00, NULL),
(1364, 135, 34, '210', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 450.00, NULL),
(1365, 135, 34, '211', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 420.00, NULL),
(1366, 136, 35, '001', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1367, 136, 35, '002', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(1368, 136, 35, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1369, 136, 35, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 430.00, NULL),
(1370, 136, 35, '005', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 430.00, NULL),
(1371, 136, 35, '006', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(1372, 136, 35, '007', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 430.00, NULL),
(1373, 136, 35, '008', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1374, 136, 35, '009', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1375, 136, 35, '010', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 460.00, NULL),
(1376, 136, 35, '011', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 460.00, NULL),
(1377, 136, 35, '012', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1378, 137, 35, '101', 'Single', 'front', 'Street View', 1, 1, 1, 0, 490.00, NULL),
(1379, 137, 35, '102', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1380, 137, 35, '103', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1381, 137, 35, '104', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(1382, 137, 35, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1383, 137, 35, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1384, 137, 35, '107', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 460.00, NULL),
(1385, 137, 35, '108', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1386, 137, 35, '109', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1387, 137, 35, '110', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 460.00, NULL),
(1388, 138, 35, '201', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 460.00, NULL),
(1389, 138, 35, '202', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1390, 138, 35, '203', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1391, 138, 35, '204', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 430.00, NULL),
(1392, 138, 35, '205', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 460.00, NULL),
(1393, 138, 35, '206', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 430.00, NULL),
(1394, 138, 35, '207', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 490.00, NULL),
(1395, 138, 35, '208', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1396, 138, 35, '209', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1397, 138, 35, '210', 'Double', 'front', 'Street View', 1, 2, 2, 0, 460.00, NULL),
(1398, 138, 35, '211', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1399, 139, 35, '301', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 460.00, NULL),
(1400, 139, 35, '302', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 430.00, NULL),
(1401, 139, 35, '303', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1402, 139, 35, '304', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1403, 139, 35, '305', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1404, 139, 35, '306', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(1405, 139, 35, '307', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1406, 139, 35, '308', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1407, 139, 35, '309', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 490.00, NULL),
(1408, 139, 35, '310', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 490.00, NULL),
(1409, 140, 35, '401', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1410, 140, 35, '402', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1411, 140, 35, '403', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 430.00, NULL),
(1412, 140, 35, '404', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 430.00, NULL),
(1413, 140, 35, '405', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 460.00, NULL),
(1414, 140, 35, '406', 'Double', 'front', 'Street View', 1, 2, 2, 0, 460.00, NULL),
(1415, 140, 35, '407', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 430.00, NULL),
(1416, 140, 35, '408', 'Double', 'front', 'Street View', 1, 2, 2, 0, 460.00, NULL),
(1417, 141, 36, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1418, 141, 36, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 410.00, NULL),
(1419, 141, 36, '003', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1420, 141, 36, '004', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(1421, 141, 36, '005', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(1422, 141, 36, '006', 'Single', 'front', 'Street View', 1, 1, 1, 0, 470.00, NULL),
(1423, 141, 36, '007', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 410.00, NULL),
(1424, 141, 36, '008', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 410.00, NULL),
(1425, 141, 36, '009', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1426, 141, 36, '010', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1427, 142, 36, '101', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 410.00, NULL),
(1428, 142, 36, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 470.00, NULL),
(1429, 142, 36, '103', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 410.00, NULL),
(1430, 142, 36, '104', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(1431, 142, 36, '105', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(1432, 142, 36, '106', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(1433, 142, 36, '107', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 410.00, NULL),
(1434, 142, 36, '108', 'Double', 'front', 'Street View', 1, 2, 2, 0, 440.00, NULL),
(1435, 143, 36, '201', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 410.00, NULL),
(1436, 143, 36, '202', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(1437, 143, 36, '203', 'Single', 'front', 'Street View', 1, 1, 1, 0, 470.00, NULL),
(1438, 143, 36, '204', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 410.00, NULL),
(1439, 143, 36, '205', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1440, 143, 36, '206', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 470.00, NULL),
(1441, 143, 36, '207', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1442, 143, 36, '208', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(1443, 143, 36, '209', 'Double', 'front', 'Street View', 1, 2, 2, 0, 440.00, NULL),
(1444, 143, 36, '210', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(1445, 144, 36, '301', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 410.00, NULL),
(1446, 144, 36, '302', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 440.00, NULL),
(1447, 144, 36, '303', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 470.00, NULL),
(1448, 144, 36, '304', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 410.00, NULL),
(1449, 144, 36, '305', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1450, 144, 36, '306', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(1451, 144, 36, '307', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 470.00, NULL),
(1452, 144, 36, '308', 'Double', 'front', 'Street View', 1, 2, 2, 0, 440.00, NULL),
(1453, 144, 36, '309', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 410.00, NULL),
(1454, 144, 36, '310', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 440.00, NULL),
(1455, 144, 36, '311', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 440.00, NULL),
(1456, 145, 37, '001', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(1457, 145, 37, '002', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 580.00, NULL),
(1458, 145, 37, '003', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(1459, 145, 37, '004', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(1460, 145, 37, '005', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 550.00, NULL),
(1461, 145, 37, '006', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(1462, 145, 37, '007', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(1463, 145, 37, '008', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1464, 145, 37, '009', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(1465, 145, 37, '010', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 520.00, NULL),
(1466, 146, 37, '101', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 580.00, NULL),
(1467, 146, 37, '102', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1468, 146, 37, '103', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(1469, 146, 37, '104', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1470, 146, 37, '105', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 520.00, NULL),
(1471, 146, 37, '106', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(1472, 146, 37, '107', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(1473, 146, 37, '108', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 550.00, NULL),
(1474, 146, 37, '109', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1475, 146, 37, '110', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1476, 146, 37, '111', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1477, 146, 37, '112', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(1478, 147, 37, '201', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1479, 147, 37, '202', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1480, 147, 37, '203', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(1481, 147, 37, '204', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1482, 147, 37, '205', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1483, 147, 37, '206', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 550.00, NULL),
(1484, 147, 37, '207', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(1485, 147, 37, '208', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1486, 147, 37, '209', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 580.00, NULL),
(1487, 148, 37, '301', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 520.00, NULL),
(1488, 148, 37, '302', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 550.00, NULL),
(1489, 148, 37, '303', 'Single', 'front', 'Street View', 1, 1, 1, 0, 580.00, NULL),
(1490, 148, 37, '304', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 520.00, NULL),
(1491, 148, 37, '305', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 550.00, NULL),
(1492, 148, 37, '306', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 520.00, NULL),
(1493, 148, 37, '307', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 550.00, NULL),
(1494, 148, 37, '308', 'Double', 'front', 'Street View', 1, 2, 2, 0, 550.00, NULL),
(1495, 148, 37, '309', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 580.00, NULL),
(1496, 148, 37, '310', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 580.00, NULL),
(1497, 149, 38, '001', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 530.00, NULL),
(1498, 149, 38, '002', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(1499, 149, 38, '003', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 560.00, NULL),
(1500, 149, 38, '004', 'Double', 'front', 'Street View', 1, 2, 2, 0, 560.00, NULL),
(1501, 149, 38, '005', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 590.00, NULL),
(1502, 149, 38, '006', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(1503, 149, 38, '007', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 560.00, NULL),
(1504, 149, 38, '008', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(1505, 150, 38, '101', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 530.00, NULL),
(1506, 150, 38, '102', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 530.00, NULL),
(1507, 150, 38, '103', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 530.00, NULL),
(1508, 150, 38, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 530.00, NULL),
(1509, 150, 38, '105', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 530.00, NULL),
(1510, 150, 38, '106', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(1511, 150, 38, '107', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(1512, 150, 38, '108', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 590.00, NULL),
(1513, 150, 38, '109', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 530.00, NULL),
(1514, 150, 38, '110', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 560.00, NULL),
(1515, 150, 38, '111', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 560.00, NULL),
(1516, 151, 38, '201', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(1517, 151, 38, '202', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 530.00, NULL),
(1518, 151, 38, '203', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 590.00, NULL),
(1519, 151, 38, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 560.00, NULL),
(1520, 151, 38, '205', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 560.00, NULL),
(1521, 151, 38, '206', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 530.00, NULL),
(1522, 151, 38, '207', 'Single', 'front', 'Street View', 1, 1, 1, 0, 590.00, NULL),
(1523, 151, 38, '208', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 530.00, NULL),
(1524, 151, 38, '209', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 590.00, NULL),
(1525, 151, 38, '210', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 530.00, NULL),
(1526, 151, 38, '211', 'Double', 'front', 'Street View', 1, 2, 2, 0, 560.00, NULL),
(1527, 151, 38, '212', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 530.00, NULL),
(1528, 152, 39, '001', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 650.00, NULL),
(1529, 152, 39, '002', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(1530, 152, 39, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(1531, 152, 39, '004', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 650.00, NULL),
(1532, 152, 39, '005', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(1533, 152, 39, '006', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 680.00, NULL),
(1534, 152, 39, '007', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 650.00, NULL),
(1535, 152, 39, '008', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1536, 152, 39, '009', 'Single', 'front', 'Street View', 1, 1, 1, 0, 680.00, NULL),
(1537, 152, 39, '010', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 650.00, NULL),
(1538, 153, 39, '101', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(1539, 153, 39, '102', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1540, 153, 39, '103', 'Single', 'front', 'Street View', 1, 1, 1, 0, 680.00, NULL),
(1541, 153, 39, '104', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 620.00, NULL),
(1542, 153, 39, '105', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 650.00, NULL),
(1543, 153, 39, '106', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 650.00, NULL),
(1544, 153, 39, '107', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(1545, 153, 39, '108', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(1546, 154, 39, '201', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 620.00, NULL),
(1547, 154, 39, '202', 'Single', 'front', 'Street View', 1, 1, 1, 0, 680.00, NULL),
(1548, 154, 39, '203', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 680.00, NULL),
(1549, 154, 39, '204', 'Double', 'front', 'Street View', 1, 2, 2, 0, 650.00, NULL),
(1550, 154, 39, '205', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1551, 154, 39, '206', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 680.00, NULL),
(1552, 154, 39, '207', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 680.00, NULL),
(1553, 154, 39, '208', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1554, 154, 39, '209', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 680.00, NULL),
(1555, 154, 39, '210', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 680.00, NULL),
(1556, 154, 39, '211', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 680.00, NULL),
(1557, 154, 39, '212', 'Double', 'front', 'Street View', 1, 2, 2, 0, 650.00, NULL),
(1558, 155, 39, '301', 'Double', 'corner', 'Panoramic View', 1, 2, 2, 0, 650.00, NULL),
(1559, 155, 39, '302', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1560, 155, 39, '303', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(1561, 155, 39, '304', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 620.00, NULL),
(1562, 155, 39, '305', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 620.00, NULL),
(1563, 155, 39, '306', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 650.00, NULL),
(1564, 155, 39, '307', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 650.00, NULL),
(1565, 155, 39, '308', 'Single', 'front', 'Street View', 1, 1, 1, 0, 680.00, NULL),
(1566, 156, 40, '001', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1567, 156, 40, '002', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1568, 156, 40, '003', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1569, 156, 40, '004', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1570, 156, 40, '005', 'Single', 'front', 'Street View', 1, 1, 1, 0, 720.00, NULL),
(1571, 156, 40, '006', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 660.00, NULL),
(1572, 156, 40, '007', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1573, 156, 40, '008', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 660.00, NULL),
(1574, 156, 40, '009', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 660.00, NULL),
(1575, 156, 40, '010', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1576, 157, 40, '101', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 660.00, NULL),
(1577, 157, 40, '102', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 720.00, NULL),
(1578, 157, 40, '103', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 720.00, NULL),
(1579, 157, 40, '104', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1580, 157, 40, '105', 'Double', 'front', 'Street View', 1, 2, 2, 0, 690.00, NULL),
(1581, 157, 40, '106', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 660.00, NULL),
(1582, 157, 40, '107', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 690.00, NULL),
(1583, 157, 40, '108', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 660.00, NULL),
(1584, 157, 40, '109', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1585, 157, 40, '110', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1586, 157, 40, '111', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1587, 157, 40, '112', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1588, 158, 40, '201', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1589, 158, 40, '202', 'Single', 'side', 'Garden View', 1, 1, 1, 0, 720.00, NULL),
(1590, 158, 40, '203', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1591, 158, 40, '204', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 690.00, NULL),
(1592, 158, 40, '205', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1593, 158, 40, '206', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 660.00, NULL),
(1594, 158, 40, '207', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 690.00, NULL),
(1595, 158, 40, '208', 'Double', 'front', 'Street View', 1, 2, 2, 0, 690.00, NULL),
(1596, 158, 40, '209', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 690.00, NULL),
(1597, 158, 40, '210', 'Triple', 'front', 'Street View', 1, 3, 3, 0, 660.00, NULL),
(1598, 158, 40, '211', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 690.00, NULL),
(1599, 158, 40, '212', 'Triple', 'side', 'Garden View', 1, 3, 3, 0, 660.00, NULL),
(1600, 159, 40, '301', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 660.00, NULL),
(1601, 159, 40, '302', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1602, 159, 40, '303', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1603, 159, 40, '304', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 660.00, NULL),
(1604, 159, 40, '305', 'Triple', 'back', 'Quiet Area', 1, 3, 3, 0, 660.00, NULL),
(1605, 159, 40, '306', 'Double', 'side', 'Garden View', 1, 2, 2, 0, 690.00, NULL),
(1606, 159, 40, '307', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1607, 159, 40, '308', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 690.00, NULL),
(1608, 160, 40, '401', 'Double', 'front', 'Street View', 1, 2, 2, 0, 690.00, NULL),
(1609, 160, 40, '402', 'Double', 'back', 'Quiet Area', 1, 2, 2, 0, 690.00, NULL),
(1610, 160, 40, '403', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL),
(1611, 160, 40, '404', 'Single', 'front', 'Street View', 1, 1, 1, 0, 720.00, NULL),
(1612, 160, 40, '405', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1613, 160, 40, '406', 'Triple', 'corner', 'Panoramic View', 1, 3, 3, 0, 660.00, NULL),
(1614, 160, 40, '407', 'Single', 'corner', 'Panoramic View', 1, 1, 1, 0, 720.00, NULL),
(1615, 160, 40, '408', 'Single', 'back', 'Quiet Area', 1, 1, 1, 0, 720.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dorm_room_prices`
--

CREATE TABLE `dorm_room_prices` (
  `id` int(11) NOT NULL,
  `dorm_id` int(11) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `price` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dorm_room_prices`
--

INSERT INTO `dorm_room_prices` (`id`, `dorm_id`, `room_type`, `price`) VALUES
(1, 7, 'Single', '180'),
(2, 7, 'Double', '160'),
(3, 7, 'Triple', '150'),
(4, 14, 'Single', '190'),
(5, 14, 'Double', '170'),
(6, 14, 'Triple', '160'),
(7, 19, 'Single', '185'),
(8, 19, 'Double', '165'),
(9, 19, 'Triple', '155'),
(10, 1, 'Single', '240'),
(11, 1, 'Double', '220'),
(12, 1, 'Triple', '200'),
(13, 11, 'Single', '290'),
(14, 11, 'Double', '270'),
(15, 11, 'Triple', '250'),
(16, 6, 'Single', '285'),
(17, 6, 'Double', '265'),
(18, 6, 'Triple', '255'),
(19, 3, 'Single', '280'),
(20, 3, 'Double', '260'),
(21, 3, 'Triple', '250'),
(22, 17, 'Single', '295'),
(23, 17, 'Double', '275'),
(24, 17, 'Triple', '260'),
(25, 10, 'Single', '380'),
(26, 10, 'Double', '340'),
(27, 10, 'Triple', '310'),
(28, 5, 'Single', '390'),
(29, 5, 'Double', '350'),
(30, 5, 'Triple', '320'),
(31, 16, 'Single', '370'),
(32, 16, 'Double', '330'),
(33, 16, 'Triple', '300'),
(34, 2, 'Single', '480'),
(35, 2, 'Double', '100'),
(36, 2, 'Triple', '410'),
(37, 20, 'Single', '490'),
(38, 20, 'Double', '450'),
(39, 20, 'Triple', '420'),
(40, 12, 'Single', '470'),
(41, 12, 'Double', '430'),
(42, 12, 'Triple', '400'),
(43, 9, 'Single', '580'),
(44, 9, 'Double', '540'),
(45, 9, 'Triple', '510'),
(46, 13, 'Single', '590'),
(47, 13, 'Double', '550'),
(48, 13, 'Triple', '520'),
(49, 4, 'Single', '570'),
(50, 4, 'Double', '530'),
(51, 4, 'Triple', '500'),
(52, 18, 'Single', '585'),
(53, 18, 'Double', '545'),
(54, 18, 'Triple', '515'),
(55, 8, 'Single', '750'),
(56, 8, 'Double', '680'),
(57, 8, 'Triple', '620'),
(58, 15, 'Single', '800'),
(59, 15, 'Double', '720'),
(60, 15, 'Triple', '650'),
(61, 21, 'Single', '180'),
(62, 21, 'Double', '160'),
(63, 21, 'Triple', '150'),
(64, 22, 'Single', '190'),
(65, 22, 'Double', '170'),
(66, 22, 'Triple', '160'),
(67, 23, 'Single', '185'),
(68, 23, 'Double', '165'),
(69, 23, 'Triple', '155'),
(70, 24, 'Single', '240'),
(71, 24, 'Double', '220'),
(72, 24, 'Triple', '200'),
(73, 25, 'Single', '235'),
(74, 25, 'Double', '215'),
(75, 25, 'Triple', '205'),
(76, 26, 'Single', '245'),
(77, 26, 'Double', '225'),
(78, 26, 'Triple', '210'),
(79, 27, 'Single', '290'),
(80, 27, 'Double', '270'),
(81, 27, 'Triple', '250'),
(82, 28, 'Single', '285'),
(83, 28, 'Double', '265'),
(84, 28, 'Triple', '255'),
(85, 29, 'Single', '295'),
(86, 29, 'Double', '275'),
(87, 29, 'Triple', '260'),
(88, 30, 'Single', '380'),
(89, 30, 'Double', '350'),
(90, 30, 'Triple', '320'),
(91, 31, 'Single', '390'),
(92, 31, 'Double', '360'),
(93, 31, 'Triple', '330'),
(94, 32, 'Single', '370'),
(95, 32, 'Double', '340'),
(96, 32, 'Triple', '310'),
(97, 33, 'Single', '395'),
(98, 33, 'Double', '365'),
(99, 33, 'Triple', '335'),
(100, 34, 'Single', '480'),
(101, 34, 'Double', '450'),
(102, 34, 'Triple', '420'),
(103, 35, 'Single', '490'),
(104, 35, 'Double', '460'),
(105, 35, 'Triple', '430'),
(106, 36, 'Single', '470'),
(107, 36, 'Double', '440'),
(108, 36, 'Triple', '410'),
(109, 37, 'Single', '580'),
(110, 37, 'Double', '550'),
(111, 37, 'Triple', '520'),
(112, 38, 'Single', '590'),
(113, 38, 'Double', '560'),
(114, 38, 'Triple', '530'),
(115, 39, 'Single', '680'),
(116, 39, 'Double', '650'),
(117, 39, 'Triple', '620'),
(118, 40, 'Single', '720'),
(119, 40, 'Double', '690'),
(120, 40, 'Triple', '660');

-- --------------------------------------------------------

--
-- Stand-in structure for view `dorm_room_statistics`
-- (See below for the actual view)
--
CREATE TABLE `dorm_room_statistics` (
`dorm_id` int(11)
,`dorm_name` varchar(255)
,`total_rooms` bigint(21)
,`available_rooms` decimal(22,0)
,`occupied_rooms` decimal(22,0)
,`total_capacity` decimal(32,0)
,`current_occupancy` decimal(32,0)
,`available_spots` decimal(33,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `dorm_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `student_id`, `dorm_id`, `added_at`) VALUES
(63, 17, 7, '2025-08-08 20:13:13'),
(65, 15, 21, '2025-10-06 18:14:11'),
(66, 15, 40, '2025-10-06 18:19:54'),
(71, 15, 6, '2025-12-20 15:49:28'),
(72, 15, 7, '2025-12-20 15:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `feedback` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `users_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `national_id` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `level` int(1) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL COMMENT 'العنوان',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1 = نشط, 0 = معطل'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`users_id`, `name`, `email`, `national_id`, `gender`, `level`, `password`, `phone`, `specialty`, `address`, `created_at`, `last_login`, `is_active`) VALUES
(1, 'Ahmed Al-Ali', 'NaelaDorm@example.com', '200087432', 'male', 3, '$2y$10$4MBtxT3qqPOU28VQ2m1uheqsVT54WHkmL6EdWjqdNTHsItD72Aj/6', '0798854563', 'owner', NULL, '2025-12-12 20:09:59', NULL, 1),
(15, 'esraa omar', 'esraa123@gmail.com', '120212231108', 'Female', 2, '$2y$10$8VnZugVTr7/cEq4WIN.qe.XWFH1WlDdM1Lz77T9xwD6s3Ob9CcfBG', '0785156617', 'SE', NULL, '2025-12-12 19:44:29', NULL, 1),
(17, 'omar', 'omar123@gmail.com', NULL, NULL, 1, '$2y$10$p6thL1wk7Mtgj05POZNvk./bkPt4wagdZc5HGmppcMHSL7s8wevZ6', NULL, 'SE', NULL, '2025-12-12 19:44:29', NULL, 1),
(18, 'randa ', 'randa123@gmail.com', NULL, NULL, 2, '$2y$10$/s.fRYb7gWKIuc9SxEMe.Ofny/hmAJ/eiLmmZHGbanYSabrBHZTM.', NULL, NULL, NULL, '2025-12-12 19:44:29', NULL, 1),
(20, 'Admin', 'admin@dormsbooking.com', NULL, 'male', 1, '$2y$10$IBqCicfO1AudGkz3pcWR7OCO.hAokqt7kx9qeiCUb4zCv63Nw2nOe', NULL, NULL, NULL, '2025-12-12 19:51:12', NULL, 1),
(21, 'Owner Test', 'owner@test.com', NULL, NULL, 3, '$2y$10$01j6ZyKXAeVMz3FYb/U4u.S7mORzeOgKtMrY.Ze2gWUrtKfKSC75a', '0599123456', NULL, NULL, '2025-12-12 20:41:17', NULL, 1),
(22, 'Ahmed Al-Ali', 'Naela Dorm@example.com', NULL, NULL, 3, 'owner123', '+962 77 111 1111', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(23, 'Fatima Hassan', 'dorm2@example.com', NULL, NULL, 3, '$2y$10$rfwvOg5XbCp7W54Seikl1uzBjNQ122YrkGMkBbKihMypSRhuQtOTm', '+962 78 222 2222', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(24, 'Mohammed Saleh', 'dorm3@example.com', NULL, NULL, 3, 'owner123', '+962 79 333 3333', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(25, 'Laila Hussein', 'dorm4@example.com', NULL, NULL, 3, 'owner123', '+962 77 444 4444', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(26, 'Omar Khalid', 'dorm5@example.com', NULL, NULL, 3, 'owner123', '+962 78 555 5555', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(27, 'Sara Abdulaziz', 'dorm6@example.com', NULL, NULL, 3, 'owner123', '+962 79 666 6666', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(28, 'Ali Zaki', 'dorm7@example.com', NULL, NULL, 3, '$2y$10$Kxbs7jwM6If0iAiALN9ry.TI7pul8ykGbSxiaH15aoUIqFrBluD6y', '+962 77 777 7777', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(29, 'Nour Abdullah', 'dorm8@example.com', NULL, NULL, 3, 'owner123', '+962 78 888 8888', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(30, 'Khalid Mahmoud', 'dorm9@example.com', NULL, NULL, 3, 'owner123', '+962 79 999 9999', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(31, 'Reem Ahmed', 'dorm10@example.com', NULL, NULL, 3, 'owner123', '+962 77 000 0000', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(32, 'Yousef Ibrahim', 'dorm11@example.com', NULL, NULL, 3, 'owner123', '+962 78 123 4567', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(33, 'Hana Omar', 'dorm12@example.com', NULL, NULL, 3, 'owner123', '+962 79 234 5678', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(34, 'Majed Nasser', 'dorm13@example.com', NULL, NULL, 3, 'owner123', '+962 77 345 6789', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(35, 'Dina Adel', 'dorm14@example.com', NULL, NULL, 3, 'owner123', '+962 78 456 7890', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(36, 'Fahad Jamal', 'dorm15@example.com', NULL, NULL, 3, 'owner123', '+962 79 567 8901', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(37, 'Aisha Raed', 'dorm16@example.com', NULL, NULL, 3, 'owner123', '+962 77 678 9012', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(38, 'Sami Jawad', 'dorm17@example.com', NULL, NULL, 3, 'owner123', '+962 78 789 0123', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(39, 'Mona Tareq', 'dorm18@example.com', NULL, NULL, 3, 'owner123', '+962 79 890 1234', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(40, 'Basel Nabil', 'dorm19@example.com', NULL, NULL, 3, 'owner123', '+962 77 901 2345', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(41, 'Hadeel Salem', 'dorm20@example.com', NULL, NULL, 3, 'owner123', '+962 78 012 3456', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(42, 'Khaled Ali', 'alamir@example.com', NULL, NULL, 3, 'owner123', '+962 77 123 4567', NULL, NULL, '2025-12-12 21:07:23', NULL, 1),
(43, 'Ahmed Sami', 'alqima@example.com', NULL, NULL, 3, 'owner123', '+962 78 987 6543', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(44, 'Omar Fawzi', 'bader@example.com', NULL, NULL, 3, 'owner123', '+962 79 111 2222', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(45, 'Hassan Zaki', 'safwa@example.com', NULL, NULL, 3, 'owner123', '+962 77 333 4444', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(46, 'Yousef Naji', 'alquds@example.com', NULL, NULL, 3, 'owner123', '+962 79 555 6666', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(47, 'Sami Adnan', 'harmony@example.com', NULL, NULL, 3, 'owner123', '+962 78 777 8888', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(48, 'Fahad Rashid', 'elite@example.com', NULL, NULL, 3, 'owner123', '+962 77 999 0000', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(49, 'Maher Kamal', 'hub@example.com', NULL, NULL, 3, 'owner123', '+962 79 123 9876', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(50, 'Nader Faisal', 'royal@example.com', NULL, NULL, 3, 'owner123', '+962 78 456 7890', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(51, 'Tareq Adel', 'scholar@example.com', NULL, NULL, 3, 'owner123', '+962 77 000 1111', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(52, 'Ziad Mustafa', 'gateway@example.com', NULL, NULL, 3, 'owner123', '+962 79 222 3333', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(53, 'Waleed Samer', 'zenith@example.com', NULL, NULL, 3, 'owner123', '+962 78 444 5555', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(54, 'Ramzi Ghazi', 'heritage@example.com', NULL, NULL, 3, 'owner123', '+962 77 666 7777', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(55, 'Jamal Haider', 'unity@example.com', NULL, NULL, 3, 'owner123', '+962 79 888 9999', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(56, 'Basel Amjad', 'phoenix@example.com', NULL, NULL, 3, 'owner123', '+962 78 012 3456', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(57, 'Rakan Salim', 'summit@example.com', NULL, NULL, 3, 'owner123', '+962 77 234 5678', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(58, 'Mazen Khalil', 'citadel@example.com', NULL, NULL, 3, 'owner123', '+962 79 456 7890', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(59, 'Adnan Hussein', 'oasis@example.com', NULL, NULL, 3, 'owner123', '+962 78 678 9012', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(60, 'Jawad Fouad', 'apex@example.com', NULL, NULL, 3, 'owner123', '+962 77 890 1234', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(61, 'Hadi Nasser', 'greenwood@example.com', NULL, NULL, 3, 'owner123', '+962 79 000 0000', NULL, NULL, '2025-12-12 21:07:24', NULL, 1),
(63, 'omar', 'omar12@gmail.com', NULL, NULL, 2, '$2y$10$wmqQLTt9tP/UoEAZCAH79OjXfy2byG82dOVMpTa96be.q6sdI/T3q', NULL, NULL, NULL, '2025-12-13 17:23:48', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message_body` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_email`, `recipient_email`, `recipient_id`, `subject`, `message_body`, `sent_at`, `is_read`) VALUES
(1, 'dorm2@example.com', 'esraa123@gmail.com', 15, 'اهلا وسهلا', '....', '2025-12-13 01:42:25', 0),
(2, 'admin@dormsbooking.com', 'esraa123@gmail.com', 15, 'اهلا وسهلا', 'هههههه', '2025-12-13 19:53:22', 0),
(3, 'admin@dormsbooking.com', 'esraa123@gmail.com', 15, 'للكل', '.....', '2025-12-13 19:56:12', 0),
(4, 'admin@dormsbooking.com', 'randa123@gmail.com', 18, 'للكل', '.....', '2025-12-13 19:56:14', 0),
(5, 'dorm2@example.com', 'esraa123@gmail.com', 15, 'rahaf', '......', '2025-12-14 06:55:40', 0),
(6, 'dorm2@example.com', '', NULL, 'بخصوص حجزك', '', '2025-12-20 19:22:37', 0),
(7, 'dorm2@example.com', '', NULL, 'بخصوص حجزك', '', '2025-12-20 19:27:09', 0),
(8, 'dorm2@example.com', 'esraa123@gmail.com', NULL, 'بخصوص حجزك', ';;;;', '2025-12-20 19:52:23', 0),
(9, 'esraa123@gmail.com', 'dorm2@example.com', NULL, 'Re: بخصوص حجزك', 'تمام ولا يهمك', '2025-12-20 21:55:23', 0),
(10, 'esraa123@gmail.com', 'admin@mu-dorms.com', NULL, 'dd', 'من: MOHAMMAD ISMAIL\nالبريد: esraa123@gmail.com\n\nsssss', '2025-12-20 22:39:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'معرف المستخدم',
  `title` varchar(255) NOT NULL COMMENT 'عنوان الإشعار',
  `message` text NOT NULL COMMENT 'نص الإشعار',
  `type` enum('booking','message','comment','system') DEFAULT 'system',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`, `read_at`) VALUES
(1, 15, 'تحديث حالة الحجز', 'تم تأكيد حجزك في Dina Dorms', 'booking', 0, '2025-12-12 21:51:30', NULL),
(2, 15, 'تحديث حالة الحجز', 'تم إلغاء حجزك في Rama Apartments', 'booking', 0, '2025-12-12 21:52:03', NULL),
(3, 15, 'تحديث حالة الحجز', 'تم إلغاء حجزك في Albatra Dorm', 'booking', 0, '2025-12-12 21:52:18', NULL),
(4, 15, 'تحديث حالة الحجز', 'تم تأكيد حجزك من قبل المالك', 'booking', 0, '2025-12-12 22:02:47', NULL),
(5, 15, 'رسالة جديدة', 'لديك رسالة جديدة: اهلا وسهلا', 'message', 0, '2025-12-12 22:31:10', NULL),
(6, 15, 'رسالة جديدة', 'لديك رسالة جديدة: اهلا وسهلا', 'message', 0, '2025-12-12 22:42:25', NULL),
(7, 15, 'تحديث حالة الحجز', 'تم تأكيد حجزك في Alesraa Dorm من قبل المالك', 'booking', 0, '2025-12-12 22:48:32', NULL),
(8, 15, 'رسالة جديدة', 'لديك رسالة جديدة: rahaf', 'message', 0, '2025-12-14 03:55:40', NULL),
(9, 15, '', 'بخصوص حجزك: kk', 'system', 0, '2025-12-20 16:22:37', NULL),
(10, 15, '', 'بخصوص حجزك: كيفك', 'system', 0, '2025-12-20 16:27:09', NULL),
(11, 15, '', 'بخصوص حجزك: ;;;;', 'system', 0, '2025-12-20 16:52:23', NULL),
(12, 15, 'تحديث حالة الحجز', 'تم تأكيد حجزك في Bella Residence من قبل المالك', 'booking', 0, '2025-12-20 17:07:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `used`, `created_at`) VALUES
(26, 'esraa123@gmail.com', '8f28240ca46e6e7446ae7580dad666d29a5ccb26f06c47f376ce2356636272a2', '2025-09-10 23:36:48', 1, '2025-09-10 20:06:48'),
(27, 'esraa123@gmail.com', '40006200863be08f7c03cfd767050317461ddcebfc302af3b2b3d7825de4d8f3', '2025-09-11 17:10:56', 1, '2025-09-11 13:40:56'),
(32, 'oalhajjaj05@gmail.com', 'ffc64d4ba51e582b1fb5f72597e674a92ca33268c90f0ae131c75e246ed65faf', '2025-10-19 13:57:20', 0, '2025-10-19 10:27:20'),
(34, 'esraa123@gmail.com', 'b23b77e9ec46025fae297cbbf51f0580bf2cb61dcd6ab0d558813fa12fbeb304', '2025-10-29 22:06:01', 1, '2025-10-29 18:36:01'),
(35, 'esraa123@gmail.com', '83880ce8616d65f3c87968e2ecd9897dcdc1e7c302ed2a898ac3a15ed4ecb292', '2025-11-03 07:31:08', 1, '2025-11-03 04:01:08'),
(36, 'admin@dormsbooking.com', '1d0b1e1f9f907336bfe0c6da2236d178426d4be41556db9d726333d234bafc93', '2025-12-12 23:24:05', 1, '2025-12-12 19:54:05'),
(38, 'NaelaDorm@example.com', '0a2b60b9701eb87c6b783fb97823924f2b1f16c6a9089c280855ec10e2f49f80', '2025-12-12 23:41:19', 1, '2025-12-12 20:11:19'),
(39, 'owner@test.com', '1c7b52bf52a30ac6b8bd85b339aca3ba1b57e470c2674982e121da2c1db73160', '2025-12-13 00:16:36', 1, '2025-12-12 20:46:36'),
(40, 'dorm2@example.com', '655f95facb218de2955ac0b2ed273f1a7a3e9b7328d3516288807222bed99b6e', '2025-12-13 00:44:47', 1, '2025-12-12 21:14:47'),
(41, 'NaelaDorm@example.com', '10fbf5fc2006f188ade32a0784e72930a62a76158b61c2490b9035ea7100745e', '2025-12-13 01:27:10', 1, '2025-12-12 21:57:10'),
(42, 'dorm7@example.com', '387375796fbdbab95d25baa5bfadf86638b9fdcda086dbe690fd4c5865146a3b', '2025-12-13 02:17:36', 1, '2025-12-12 22:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `roommate_matches`
--

CREATE TABLE `roommate_matches` (
  `match_id` int(11) NOT NULL,
  `request_id_1` int(11) NOT NULL COMMENT 'طلب الطالب الأول',
  `request_id_2` int(11) NOT NULL COMMENT 'طلب الطالب الثاني',
  `student_id_1` int(11) NOT NULL,
  `student_id_2` int(11) NOT NULL,
  `room_id` int(11) NOT NULL COMMENT 'الغرفة المشتركة',
  `booking_id_1` int(11) DEFAULT NULL COMMENT 'حجز الطالب الأول',
  `booking_id_2` int(11) DEFAULT NULL COMMENT 'حجز الطالب الثاني',
  `match_status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roommate_requests`
--

CREATE TABLE `roommate_requests` (
  `request_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL COMMENT 'معرف الطالب من جدول login',
  `dorm_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL COMMENT 'الغرفة المحددة (اختياري)',
  `room_type` varchar(50) NOT NULL COMMENT 'نوع الغرفة المطلوبة',
  `specialty` varchar(100) DEFAULT NULL COMMENT 'التخصص',
  `gender` enum('Male','Female') NOT NULL,
  `preferences` text DEFAULT NULL COMMENT 'تفضيلات إضافية (JSON)',
  `status` enum('active','matched','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roommate_requests`
--

INSERT INTO `roommate_requests` (`request_id`, `student_id`, `dorm_id`, `room_id`, `room_type`, `specialty`, `gender`, `preferences`, `status`, `created_at`, `updated_at`) VALUES
(1, 17, 11, 395, 'Double', 'SE', 'Female', NULL, 'matched', '2025-12-12 19:13:28', '2025-12-12 19:16:51'),
(3, 15, 7, 254, 'Double', 'SE', 'Female', NULL, 'active', '2025-12-12 22:44:08', '2025-12-12 22:44:08'),
(4, 17, 11, 407, 'Double', 'SE', 'Female', NULL, 'active', '2025-12-14 03:51:31', '2025-12-14 03:51:31'),
(5, 15, 2, 53, 'Triple', 'SE', 'Female', NULL, 'active', '2025-12-20 17:06:49', '2025-12-20 17:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL COMMENT 'مفتاح الإعداد',
  `setting_value` text DEFAULT NULL COMMENT 'قيمة الإعداد',
  `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL COMMENT 'وصف الإعداد',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'site_name', 'نظام حجز السكنات الجامعية', 'text', 'اسم الموقع', '2025-12-12 19:44:30'),
(2, 'admin_email', 'admin@dormsbooking.com', 'text', 'بريد المدير', '2025-12-12 19:44:30'),
(3, 'booking_approval_required', '1', 'boolean', 'هل يتطلب الحجز موافقة', '2025-12-12 19:44:30'),
(4, 'max_booking_duration', '12', 'number', 'أقصى مدة حجز بالأشهر', '2025-12-12 19:44:30');

-- --------------------------------------------------------

--
-- Structure for view `available_rooms_view`
--
DROP TABLE IF EXISTS `available_rooms_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `available_rooms_view`  AS SELECT `r`.`room_id` AS `room_id`, `r`.`dorm_id` AS `dorm_id`, `d`.`name` AS `dorm_name`, `f`.`floor_number` AS `floor_number`, `f`.`floor_name` AS `floor_name`, `r`.`room_number` AS `room_number`, `r`.`room_type` AS `room_type`, `r`.`position` AS `position`, `r`.`view_type` AS `view_type`, `r`.`capacity` AS `capacity`, `r`.`current_occupancy` AS `current_occupancy`, `r`.`price_per_month` AS `price_per_month`, `r`.`capacity`- `r`.`current_occupancy` AS `available_spots` FROM ((`dorm_rooms` `r` join `dorm_floors` `f` on(`r`.`floor_id` = `f`.`floor_id`)) join `dorms` `d` on(`r`.`dorm_id` = `d`.`dorm_id`)) WHERE `r`.`is_available` = 1 AND `r`.`current_occupancy` < `r`.`capacity` ;

-- --------------------------------------------------------

--
-- Structure for view `dorm_room_statistics`
--
DROP TABLE IF EXISTS `dorm_room_statistics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dorm_room_statistics`  AS SELECT `d`.`dorm_id` AS `dorm_id`, `d`.`name` AS `dorm_name`, count(`r`.`room_id`) AS `total_rooms`, sum(case when `r`.`is_available` = 1 then 1 else 0 end) AS `available_rooms`, sum(case when `r`.`is_available` = 0 then 1 else 0 end) AS `occupied_rooms`, sum(`r`.`capacity`) AS `total_capacity`, sum(`r`.`current_occupancy`) AS `current_occupancy`, sum(`r`.`capacity`) - sum(`r`.`current_occupancy`) AS `available_spots` FROM (`dorms` `d` left join `dorm_rooms` `r` on(`d`.`dorm_id` = `r`.`dorm_id`)) GROUP BY `d`.`dorm_id`, `d`.`name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`amenity_id`),
  ADD UNIQUE KEY `amenity_name` (`amenity_name`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `dorm_id` (`dorm_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_room` (`room_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dorm_id` (`dorm_id`),
  ADD KEY `fk_comment_user` (`user_id`);

--
-- Indexes for table `dorms`
--
ALTER TABLE `dorms`
  ADD PRIMARY KEY (`dorm_id`),
  ADD KEY `fk_dorm_owner` (`owner_id`);

--
-- Indexes for table `dorm_amenities`
--
ALTER TABLE `dorm_amenities`
  ADD PRIMARY KEY (`dorm_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `dorm_floors`
--
ALTER TABLE `dorm_floors`
  ADD PRIMARY KEY (`floor_id`),
  ADD KEY `idx_dorm_floor` (`dorm_id`,`floor_number`);

--
-- Indexes for table `dorm_rooms`
--
ALTER TABLE `dorm_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `unique_room` (`dorm_id`,`room_number`),
  ADD KEY `idx_floor` (`floor_id`),
  ADD KEY `idx_availability` (`is_available`),
  ADD KEY `idx_room_type` (`room_type`);

--
-- Indexes for table `dorm_room_prices`
--
ALTER TABLE `dorm_room_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dorm_id` (`dorm_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `uq_student_dorm` (`student_id`,`dorm_id`),
  ADD KEY `dorm_id` (`dorm_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_recipient` (`recipient_email`),
  ADD KEY `idx_sender` (`sender_email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `idx_requests` (`request_id_1`,`request_id_2`),
  ADD KEY `idx_students` (`student_id_1`,`student_id_2`),
  ADD KEY `idx_room` (`room_id`),
  ADD KEY `fk_match_request2` (`request_id_2`),
  ADD KEY `fk_match_student2` (`student_id_2`),
  ADD KEY `fk_match_booking1` (`booking_id_1`),
  ADD KEY `fk_match_booking2` (`booking_id_2`);

--
-- Indexes for table `roommate_requests`
--
ALTER TABLE `roommate_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_dorm_room_type` (`dorm_id`,`room_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_roommate_room` (`room_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `amenity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `dorms`
--
ALTER TABLE `dorms`
  MODIFY `dorm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `dorm_floors`
--
ALTER TABLE `dorm_floors`
  MODIFY `floor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `dorm_rooms`
--
ALTER TABLE `dorm_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1616;

--
-- AUTO_INCREMENT for table `dorm_room_prices`
--
ALTER TABLE `dorm_room_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roommate_requests`
--
ALTER TABLE `roommate_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `login` (`users_id`) ON DELETE SET NULL;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `login` (`users_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `dorm_rooms` (`room_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_dorm` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `login` (`users_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_comments_dorm` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dorms`
--
ALTER TABLE `dorms`
  ADD CONSTRAINT `fk_dorm_owner` FOREIGN KEY (`owner_id`) REFERENCES `login` (`users_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `dorm_amenities`
--
ALTER TABLE `dorm_amenities`
  ADD CONSTRAINT `dorm_amenities_ibfk_1` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dorm_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE CASCADE;

--
-- Constraints for table `dorm_floors`
--
ALTER TABLE `dorm_floors`
  ADD CONSTRAINT `fk_floor_dorm` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dorm_rooms`
--
ALTER TABLE `dorm_rooms`
  ADD CONSTRAINT `fk_room_dorm` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_room_floor` FOREIGN KEY (`floor_id`) REFERENCES `dorm_floors` (`floor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dorm_room_prices`
--
ALTER TABLE `dorm_room_prices`
  ADD CONSTRAINT `dorm_room_prices_ibfk_1` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `login` (`users_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `login` (`users_id`) ON DELETE CASCADE;

--
-- Constraints for table `roommate_matches`
--
ALTER TABLE `roommate_matches`
  ADD CONSTRAINT `fk_match_booking1` FOREIGN KEY (`booking_id_1`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_match_booking2` FOREIGN KEY (`booking_id_2`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_match_request1` FOREIGN KEY (`request_id_1`) REFERENCES `roommate_requests` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_match_request2` FOREIGN KEY (`request_id_2`) REFERENCES `roommate_requests` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_match_room` FOREIGN KEY (`room_id`) REFERENCES `dorm_rooms` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_match_student1` FOREIGN KEY (`student_id_1`) REFERENCES `login` (`users_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_match_student2` FOREIGN KEY (`student_id_2`) REFERENCES `login` (`users_id`) ON DELETE CASCADE;

--
-- Constraints for table `roommate_requests`
--
ALTER TABLE `roommate_requests`
  ADD CONSTRAINT `fk_roommate_dorm` FOREIGN KEY (`dorm_id`) REFERENCES `dorms` (`dorm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_roommate_room` FOREIGN KEY (`room_id`) REFERENCES `dorm_rooms` (`room_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_roommate_student` FOREIGN KEY (`student_id`) REFERENCES `login` (`users_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
