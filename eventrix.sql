-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 11, 2025 at 08:35 AM
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
-- Database: `eventrix`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

DROP TABLE IF EXISTS `accommodations`;
CREATE TABLE `accommodations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accommodations`
--

INSERT INTO `accommodations` (`id`, `name`, `description`, `address`, `email`, `phone`, `website`, `photo`) VALUES
(1, 'North Block Hotel', 'North Block Hotel Yountville, California, United States ratings, photos, prices, expert advice, traveler reviews and tips, and more information from Condé Nast Traveler.', '6757 Washington St., Yountville, California 94599, United States', 'aarifhsn@gmail.com', '+88 (226) 753-6996', 'https://northblock.hotel/', 'photo_6808fad6cc4863.29849699.jpg'),
(3, 'Clarke Willis', 'At fugiat quia enim omnis corporis', 'Fuga Laborum Quos nulla aut et eius quasi labore eos voluptate non', 'mhasanb006@gmail.com', '+88 (778) 206-1225', 'https://www.vuxezucu.ws', 'photo_6808fd22b93766.84162303.jpg'),
(4, 'Jason Moss', 'Possimus corrupti soluta ut explicabo Maiores vero', 'Similique sed neque ea perferendis voluptatem Qui sed praesentium fuga Maiores autem nostrud', 'aarifhsn@gmail.com', '+88 (777) 281-8924', 'https://www.zojefo.tv', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

DROP TABLE IF EXISTS `counters`;
CREATE TABLE `counters` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `number` int(11) NOT NULL DEFAULT 0,
  `label` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `title`, `details`) VALUES
(1, 'Voluptas officia duis non eius obcaecati debitis ratione eu tenetur', 'Et officia fuga Tenetur illo ea et dignissimos quia labore velit dolore eligendi consequatur rem enim est expedita molestiae'),
(2, 'Non sit qui quibusdam dolor cillum laborum Sint odio cupidatat ullamco vitae corrupti soluta molestiae qui in', 'Temporibus non labore esse quis sint modi lorem blanditiis aut consectetur ex'),
(5, 'Perspiciatis porro ut nostrud dolore eiusmod veniam sed asperiores enim voluptate optio accusantium', 'Eiusmod ab nobis est commodi dolorum dolor repudiandae labore est officia elit corrupti facere id natus voluptate'),
(6, 'Eum molestiae fuga Facilis quia iusto do inventore fugiat consectetur fugiat', 'Quia sit non et ullamco qui reprehenderit sed in cumque ex velit ducimus');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `feature_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `name`, `feature_order`) VALUES
(2, 'Unlimited Drinkgs & Coffee', 2),
(3, 'Lunch Facility', 3),
(4, 'Meet with Speakers', 5);

-- --------------------------------------------------------

--
-- Table structure for table `feature_package`
--

DROP TABLE IF EXISTS `feature_package`;
CREATE TABLE `feature_package` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feature_package`
--

INSERT INTO `feature_package` (`id`, `package_id`, `feature_id`) VALUES
(10, 2, 2),
(11, 2, 3),
(12, 3, 2),
(13, 3, 3),
(14, 3, 4),
(15, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `homepage_sections`
--

DROP TABLE IF EXISTS `homepage_sections`;
CREATE TABLE `homepage_sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_sections`
--

INSERT INTO `homepage_sections` (`id`, `section_name`, `title`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'pricing', 'Pricing', 'You will find below the different pricing options for our event. Choose the one that suits you best and register now! You will have access to all sessions, unlimited coffee and food, and the opportunity to meet with your favorite speakers', 1, '2025-09-08 06:41:54', '2025-09-08 07:10:55'),
(29, 'blog', 'Latest News', 'All the latest news and updates about our event and conference are available here. Stay informed and don\'t miss any important information!', 1, '2025-09-08 07:27:05', '2025-09-08 07:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `home_abouts`
--

DROP TABLE IF EXISTS `home_abouts`;
CREATE TABLE `home_abouts` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `button_text` tinytext DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_abouts`
--

INSERT INTO `home_abouts` (`id`, `photo`, `heading`, `description`, `button_text`, `button_url`, `status`) VALUES
(1, 'photo_68be6c1b17e349.07006568.png', 'Hic voluptatem non voluptatem id ea autem voluptatibus ut', 'Autem velit qui est rerum a saepe consectetur quidem excepturi quas nulla', 'Et neque', 'http://arif.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `home_banners`
--

DROP TABLE IF EXISTS `home_banners`;
CREATE TABLE `home_banners` (
  `id` int(11) NOT NULL,
  `heading` text NOT NULL,
  `subheading` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `background` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_banners`
--

INSERT INTO `home_banners` (`id`, `heading`, `subheading`, `description`, `background`, `event_date`) VALUES
(2, 'Consectetur ex sed', '', '', 'background_6803e93cc39735.56653821.jpg', '2025-09-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `admin_id`, `message`, `date_time`) VALUES
(1, 15, 0, 'hello there', '2025-09-11 07:07:55'),
(2, 15, 0, 'hello there', '2025-09-11 07:08:38'),
(3, 15, 1, 'who are you', '2025-09-11 10:18:01'),
(4, 15, 1, 'who are you', '2025-09-11 10:18:28'),
(5, 16, 0, 'I want to purchage a project. Can you consider', '2025-09-11 10:22:09'),
(6, 16, 1, 'Sure. lets discuss', '2025-09-11 10:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `organizers`
--

DROP TABLE IF EXISTS `organizers`;
CREATE TABLE `organizers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `facebook` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `linkedin` varchar(50) DEFAULT NULL,
  `instagram` varchar(50) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizers`
--

INSERT INTO `organizers` (`id`, `name`, `designation`, `bio`, `address`, `email`, `phone`, `website`, `facebook`, `twitter`, `linkedin`, `instagram`, `photo`) VALUES
(6, 'Marfi Ahmed', 'Web DEveloper', 'Brahmabarian', 'Brahmanbaria, Bangladesh', 'aarifhasan@gmail.com', '1750128167', 'https://www.jum.co', 'www.facebook.com/aarifhasan', 'www.twitter.com/aarifhsn', 'www.linkedin.com/aarifhsn', 'www.instragram.com/aarifhsn', 'photo_68085475e92ed3.25145820.jpg'),
(10, 'Riyad Hassan', 'Web DEveloper', 'Brahmabarian', 'Brahmanbaria, Bangladesh', 'aarifhasan@gmail.com', '1750128167', 'https://www.jum.co', 'www.facebook.com/aarifhasan', 'www.twitter.com/aarifhsn', 'www.linkedin.com/aarifhsn', '', 'photo_6805d372caef79.60891468.jpg'),
(15, 'ARIF', 'Web', '', 'Brahmanbaria, Bangladesh', 'aarifhsn@gmail.com', '01750128167', 'https://www.jum.co', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'photo_6808fd7ff2c1c2.15534085.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `max_price` int(11) DEFAULT NULL,
  `item_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `price`, `max_price`, `item_order`) VALUES
(1, 'Standard', 9, 19, 1),
(2, 'Business', 29, 39, 2),
(3, 'Premium', 99, 109, 3);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `show_on_homepage` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `photo`, `title`, `slug`, `content`, `date`, `show_on_homepage`) VALUES
(1, 'photo_680a5e19b74854.53989968.jpg', 'Magnam officiis consequatur dolores distinctio Atque voluptates in dolore itaque culpa obcaecati', 'odit-et-quam-a-quae-aliquip-odit-est-nisi-1-1', 'Dolor cupiditate ducimus quisquam non consequatur quaerat', '2025-04-26 00:00:00', 1),
(2, 'photo_67fc8e0c8e9246.49052277.jpg', 'Do velit qui libero facere qui numquam praesentium numquam rerum sit aut dolorum praesentium laboris error laborum reprehenderit quas quia', 'Laudantium voluptate quia qui architecto', 'Fuga Ab voluptates consectetur molestias amet adipisicing', '1971-07-25 00:00:00', 1),
(3, 'photo_680a5d6bab56a9.71437298.jpg', 'Praesentium quas et at perferendis rem excepteur cillum suscipit vero qui est laudantium eu numquam', 'odit-et-quam-a-quae-aliquip-odit-est-nisi-1', 'Repellendus In quis sed suscipit quisquam nulla id quis aut magnam sunt laborum Occaecat', '2025-04-26 00:00:00', 1),
(4, 'photo_67fc8e0c8e9246.49052277.jpg', 'Odit et quam a quae aliquip odit est nisi', 'odit-et-quam-a-quae-aliquip-odit-est-nisi', 'Et duis alias ipsa minus nostrum dolorem rem omnis dolores ex aspernatur voluptas', '2025-04-26 00:00:00', 1),
(5, '', 'Expedita aliquid non voluptatem in id incididunt sed dolor maxime velit et labore et quo minus non consequatur', 'odit-et-quam-a-quae-aliquip-odit-est-nisi-2', 'Et est officia repellendus Necessitatibus dolores odio aut tempor voluptas libero possimus sunt ut deserunt quia', '2025-04-26 00:00:00', 1),
(6, 'photo_680a554fbd90d5.19641552.jpg', 'Velit quam qui dolor ipsam amet aut nemo ut unde anim', 'iure-quae-ut-sint-tenetur-et-quaerat-magnam-quas-sunt-officia-quod-nisi-odio', 'Qui dolore eaque est laboriosam facere laborum sequi rem', '2025-04-18 00:00:00', 1),
(7, NULL, 'Ipsa quidem consequatur minim aut doloribus ipsum eaque reiciendis dolorem voluptate ut', 'id-blanditiis-sed-laborum-sint-voluptatem-autem-irure-maxime-cupidatat-lorem-voluptatem', 'Ut a ut in qui aute recusandae Aut consectetur rem odit consectetur fugit et id labore voluptas soluta', '1975-03-31 00:00:00', 1),
(8, NULL, 'Arif\'s blog', 'arif-s-blog', 'arif', '2025-04-24 20:17:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `schedule_day_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `time` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `item_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `schedule_day_id`, `name`, `title`, `description`, `location`, `time`, `photo`, `item_order`) VALUES
(21, 14, 'Session 1', 'The Evolution of Web Development: From HTML to Headless CMS', 'A journey through the history, current trends, and the future of web technologies.', 'Dhaka, Bangladesh', '10:00 AM - 10:45 AM', 'photo_6805e300aaee13.31211567.webp', 1),
(22, 14, 'Session 1', 'Modern Frontend Frameworks: React vs Vue vs Svelte', 'Compare strengths, real-world use cases, and when to choose what.', 'Dhaka, Bangladesh', '11:00 AM - 11:45 AM', 'photo_6805e4409b01b5.38892991.jpg', 2),
(23, 14, 'Session 2', 'Mastering the Backend: Laravel, Node.js, and Beyond', 'Discuss powerful backend tools, architecture tips, and clean API design.', 'Dhaka, Bangladesh', '12:00 AM - 12:45 AM', 'photo_6805e476987cd7.76769760.jpg', 3),
(24, 14, 'Session 2', 'The Power of RESTful & GraphQL APIs', 'How to design, implement, and consume APIs effectively in modern apps.', 'Dhaka, Bangladesh', '13:00 AM - 13:45 AM', 'photo_6805e4d0cc6930.19954114.jpg', 4),
(25, 15, 'Session 1', 'Full Stack Development in 2025: Tools, Trends, and Career Paths', 'What it means to be full stack today, and the skills developers really need.', 'Dhaka, Bangladesh', '10:00 AM - 10:45 AM', 'photo_6805e508935773.65511636.jpg', 5),
(26, 15, 'Session 2', 'UI/UX in Web Development: Creating Interfaces Users Love', 'Talk about design systems, accessibility, and best practices for user experience.', 'Dhaka, Bangladesh', '12:00 AM - 12:45 AM', 'photo_6805e5376e3316.13294668.jpg', 6),
(27, 16, 'Session 1', 'DevOps for Web Developers: CI/CD, Docker, and Deployment Pipelines', 'A practical guide to automating deployments and improving code reliability.', 'Dhaka, Bangladesh', '10:00 AM - 10:45 AM', 'photo_6805e57fa64876.60748799.jpg', 7),
(28, 16, 'Session 1', 'Performance Optimization: Making Websites Blazing Fast', 'From lazy loading to code splitting, practical tips for speed and SEO.', 'Dhaka, Bangladesh', '11:00 AM - 11:45 AM', 'photo_6805e6b4551441.13686729.jpg', 8),
(29, 16, 'Session 2', 'Security Best Practices Every Web Developer Should Know', 'Cover common vulnerabilities (like XSS, CSRF) and how to prevent them.', 'Dhaka, Bangladesh', '12:00 AM - 12:45 AM', 'photo_6805e6e9485f03.81816176.jpg', 9),
(30, 16, 'Session 2', 'AI & Web Development: Building Smarter Websites', 'Explore how AI tools like ChatGPT, Copilot, and ML APIs are changing the dev game.', 'Dhaka, Bangladesh', '12:00 AM - 12:45 AM', 'photo_6805e711922054.88792573.jpg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_days`
--

DROP TABLE IF EXISTS `schedule_days`;
CREATE TABLE `schedule_days` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `date` datetime DEFAULT NULL,
  `order_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_days`
--

INSERT INTO `schedule_days` (`id`, `title`, `date`, `order_number`) VALUES
(14, 'Day 1', '2025-04-25 00:00:00', 1),
(15, 'Day 2', '2025-04-26 00:00:00', 2),
(16, 'Day 3', '2025-04-27 00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `speakers`
--

DROP TABLE IF EXISTS `speakers`;
CREATE TABLE `speakers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `facebook` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `linkedin` varchar(50) DEFAULT NULL,
  `instagram` varchar(50) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speakers`
--

INSERT INTO `speakers` (`id`, `name`, `designation`, `bio`, `address`, `email`, `phone`, `website`, `facebook`, `twitter`, `linkedin`, `instagram`, `photo`) VALUES
(6, 'Marfi Ahmed', 'Web DEveloper', 'Brahmabarian', 'Brahmanbaria, Bangladesh', 'aarifhasan@gmail.com', '1750128167', 'https://www.jum.co', 'www.facebook.com/aarifhasan', 'www.twitter.com/aarifhsn', 'www.linkedin.com/aarifhsn', 'www.instragram.com/aarifhsn', 'photo_6805d3616ae247.98007810.webp'),
(10, 'Riyad Hassan', 'Web DEveloper', 'Brahmabarian', 'Brahmanbaria, Bangladesh', 'aarifhasan@gmail.com', '1750128167', 'https://www.jum.co', 'www.facebook.com/aarifhasan', 'www.twitter.com/aarifhsn', 'www.linkedin.com/aarifhsn', '', 'photo_6805d372caef79.60891468.jpg'),
(14, 'MD ARIF HASSAN', 'Software Engineer', '', 'Brahmanbaria, Bangladesh', 'aarifhsn@gmail.com', '1750128167', 'https://www.vevodani.net', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'photo_6805d37c842492.69152277.png'),
(15, 'ARIF', 'Web', '', 'Brahmanbaria, Bangladesh', 'aarifhsn@gmail.com', '01750128167', 'https://www.jum.co', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'http://arif.com', 'photo_6805d3939a5a41.35062536.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `speaker_schedule`
--

DROP TABLE IF EXISTS `speaker_schedule`;
CREATE TABLE `speaker_schedule` (
  `id` int(11) NOT NULL,
  `speaker_id` int(11) NOT NULL,
  `schedule_day_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speaker_schedule`
--

INSERT INTO `speaker_schedule` (`id`, `speaker_id`, `schedule_day_id`, `schedule_id`) VALUES
(2, 6, 14, 21),
(3, 10, 14, 22),
(4, 14, 14, 23),
(6, 6, 15, 25),
(7, 14, 15, 26),
(8, 15, 16, 27),
(9, 10, 16, 28),
(10, 14, 16, 29),
(11, 15, 16, 30),
(12, 15, 14, 24),
(13, 10, 14, 21),
(15, 10, 15, 25);

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
CREATE TABLE `sponsors` (
  `id` int(11) NOT NULL,
  `sponsor_category_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `website` text DEFAULT NULL,
  `facebook` text DEFAULT NULL,
  `twitter` text DEFAULT NULL,
  `linkedin` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `map` text DEFAULT NULL,
  `logo` text NOT NULL,
  `featured_photo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsors`
--

INSERT INTO `sponsors` (`id`, `sponsor_category_id`, `name`, `title`, `description`, `address`, `email`, `phone`, `website`, `facebook`, `twitter`, `linkedin`, `instagram`, `map`, `logo`, `featured_photo`) VALUES
(1, 1, 'Denton Cantu', 'Velit sed ullamco eum magnam unde ea similique quasi', 'Voluptatibus accusamus officia dolore occaecat voluptates amet ullam rem recusandae Consequatur', 'Sit saepe hic in ipsa debitis laboriosam dolore nostrum eius possimus eiusmod', NULL, '+88 (305) 579-1309', 'https://www.cydorokyroros.cc', 'Maxime assumenda sunt esse elit fugiat reprehenderit voluptatem ea cupidatat ex eligendi eos sit qui est sed a', 'Et Nam rem laboriosam odit neque in modi veniam cillum non', 'Sit ad ut enim adipisci qui inventore', 'Unde repudiandae culpa quisquam in tempore id facilis omnis sed possimus qui sunt rerum ipsum nulla accusantium', 'Est ipsum consequatur Amet reprehenderit in ex esse minima nisi nisi est natus maxime', 'logo_68079b10666676.24354018.jpg', NULL),
(3, 3, 'Aurelia Cabrera', 'Tempor ipsum iusto minima commodo dicta consequuntur eum aliquam quia nisi autem animi aut libero excepteur esse', 'Rerum non exercitationem magnam enim facilis praesentium libero quis', 'Neque proident quibusdam aliquid non atque ut aliquam dolores porro aut incidunt pariatur Mollit laborum suscipit', NULL, '+88 (401) 727-9586', 'https://www.sijokuxisut.com.au', 'Dolore sit sequi velit laborum autem ullam sunt facere labore dolor sit aute quisquam doloremque eu', 'Quam veniam cupiditate ratione explicabo Deleniti aute at libero beatae temporibus consectetur illum nulla dolore sunt laborum', 'Ullamco ipsam qui exercitation molestiae nihil consequatur sed incididunt consequatur Deserunt', 'Explicabo Dolor dolorum ipsa soluta molestias aut esse sequi cum suscipit aut fuga Voluptatem eu consectetur maxime iste nulla molestias', 'Est veniam elit necessitatibus et blanditiis doloremque id dolor atque omnis modi', 'logo_6807e57edb20c6.22057731.jpg', 'photo_6806976c01eab5.16840236.jpg'),
(4, 4, 'Pixel Perfect', 'A USA-Based Software Development Company', 'Pixel Perfect is a leading software development company specializing in creating innovative and high-quality solutions for businesses of all sizes. With a team of dedicated experts, they offer a wide range of services, including custom software development, mobile app creation, and web design. Their commitment to excellence and customer satisfaction has earned them a stellar reputation in the industry. We are thrilled to have Pixel Perfect as a sponsor, supporting our mission to bring exceptional events and conferences to our community.', 'Brahmanbaria, Bangladesh', NULL, '01750128167', '', '', '', '', '', '', 'logo_680727d4439b40.32680149.jpg', 'featured_photo_680727d4436f68.82343325.jpg'),
(5, 4, 'Damon Henson', 'Excepteur aut voluptas reprehenderit quidem inventore consectetur fugiat voluptatum minus nihil ratione rerum commodi autem duis commodi nisi optio', 'Eiusmod dolores expedita est veritatis fugit minus quia voluptatum ipsam delectus qui ea commodi culpa deserunt minim veniam', 'Velit tempora dicta unde nihil iusto nostrum sapiente do duis culpa amet alias expedita laboriosam in', NULL, '+88 (673) 335-1357', 'https://www.ruqipewawywene.com', 'Culpa sunt iure quia ratione in mollitia eveniet', 'Qui voluptates Nam magni ut dolore excepturi nostrud nostrum ut quaerat voluptatum voluptatem qui quam impedit soluta autem ipsa nihil', 'Vel adipisicing dolorem consequatur Unde molestias ex', 'Laborum Harum aliquam praesentium quos sit ut', 'Rerum aut enim assumenda voluptatum et voluptatem id aut asperiores consequatur quas sed quaerat magnam officiis cillum', 'logo_6807712a847770.07091172.jpg', 'featured_photo_6807712a846120.10529984.jpg'),
(6, 1, 'MD ARIF HASSAN', '', '', 'Brahmanbaria, Bangladesh', 'aarifhsn@gmail.com', '01750128167', '', '', '', '', '', '', 'logo_6807e5b68183e9.61922396.jpg', 'featured_photo_6807e5b6816481.38178526.jpg'),
(7, 3, 'MD ARIF HASSAN', '', '', 'Brahmanbaria, Bangladesh', NULL, '01750128167', '', '', '', '', '', '', 'logo_6807e5dde5b6f6.92434550.jpg', 'featured_photo_6808514e885a40.18879093.jpg'),
(8, 1, 'MD ARIF HASSAN', '', '', 'Brahmanbaria, Bangladesh', 'aarifhsn@gmail.com', '01750128167', '', '', '', '', '', '', 'logo_6807e5ddedc592.73020670.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sponsor_categories`
--

DROP TABLE IF EXISTS `sponsor_categories`;
CREATE TABLE `sponsor_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor_categories`
--

INSERT INTO `sponsor_categories` (`id`, `title`, `description`) VALUES
(1, 'Gold Sponsor2', 'All the gold sponsors of the event are listed here. If you are interested in becoming a gold sponsor.'),
(3, 'Platinum Sponsor', 'All the platinum sponsors of the event are listed here. If you are interested in becoming a platinum sponsor, please contact us.'),
(4, 'Special Sponsor', 'Their commitment to excellence and customer satisfaction has earned them a stellar reputation in the industry. We are thrilled to have Pixel Perfect as a sponsor, supporting our mission to bring exceptional events and conferences to our community.');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `comment`, `photo`) VALUES
(1, 'Gloria Leblanc', 'Web DEveloper', 'Enim rem voluptas commodo molestiae veniam laborum Exercitationem voluptates irure est sed inventore cumque', 'photo_6809b60be2ca43.12166996.png'),
(2, 'Ramona Cash', 'Software Engineer', 'Beatae aut adipisci nisi voluptates aute excepturi aut quos fugiat voluptate enim laudantium', 'photo_6809b6002be537.74332254.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `package_name` text NOT NULL,
  `billing_name` text NOT NULL,
  `billing_email` text NOT NULL,
  `billing_phone` text NOT NULL,
  `billing_address` text NOT NULL,
  `billing_country` text NOT NULL,
  `billing_state` text NOT NULL,
  `billing_city` text NOT NULL,
  `billing_zip` text NOT NULL,
  `billing_note` text NOT NULL,
  `payment_method` text NOT NULL,
  `payment_currency` text NOT NULL,
  `payment_status` text NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `bank_transaction_info` text NOT NULL,
  `per_ticket_price` int(11) NOT NULL,
  `total_tickets` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `purchase_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `package_id`, `payment_id`, `package_name`, `billing_name`, `billing_email`, `billing_phone`, `billing_address`, `billing_country`, `billing_state`, `billing_city`, `billing_zip`, `billing_note`, `payment_method`, `payment_currency`, `payment_status`, `transaction_id`, `bank_transaction_info`, `per_ticket_price`, `total_tickets`, `total_price`, `purchase_date_time`) VALUES
(1, 15, 2, 'PAYID-NDASDTI7KK903485V720332C', 'Business', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'PayPal', 'USD', 'Completed', 'PAYID-NDASDTI7KK903485V720332C', '', 29, 1, 29, '2025-09-10 13:00:36'),
(2, 15, 1, 'PAYID-NDASJRI7TJ269530K829425Y', 'Standard', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'PayPal', 'USD', 'Completed', 'PAYID-NDASJRI7TJ269530K829425Y', '', 9, 1, 9, '2025-09-10 13:12:14'),
(3, 15, 1, 'pi_3S5mrzKHBQQiGrkq1V6XRsjX', 'Standard', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'Stripe', 'USD', 'Completed', 'pi_3S5mrzKHBQQiGrkq1V6XRsjX', '', 9, 1, 9, '2025-09-10 18:11:31'),
(4, 15, 2, 'pi_3S5ngWKHBQQiGrkq1pl8f8Cj', 'Business', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'Stripe', 'USD', 'Completed', 'pi_3S5ngWKHBQQiGrkq1pl8f8Cj', '', 29, 1, 29, '2025-09-10 19:03:44'),
(5, 15, 3, 'pi_3S5nqDKHBQQiGrkq0B159Gcb', 'Premium', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', 'pay first', 'Stripe', 'USD', 'Completed', 'pi_3S5nqDKHBQQiGrkq0B159Gcb', '', 99, 1, 99, '2025-09-10 19:13:44'),
(6, 15, 2, '17575114396245', 'Business', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'Bank', 'USD', 'Completed', '', 'sampe', 29, 1, 29, '2025-09-10 19:37:19'),
(7, 15, 2, '17575115684017', 'Business', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'Bank', 'USD', 'Completed', '', 'sampe', 29, 1, 29, '2025-09-10 19:39:28'),
(8, 15, 2, '17575122713824', 'Business', 'Arif Hassan', 'arif@gmail.com', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '', 'Bank', 'USD', 'Pending', '', 'sampe', 29, 1, 29, '2025-09-10 19:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `role` varchar(15) NOT NULL,
  `status` varchar(10) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `token`, `role`, `status`, `photo`, `phone`, `address`, `country`, `state`, `city`, `zip_code`, `created_at`) VALUES
(1, 'Arif Hassan', 'aarif@gmail.com', '$2y$10$GfX0weqLZy2WBY2oXbH.Bevvi1N1n7TjhmSZ.mMorB3iduW5uAMPK', '', 'admin', '1', 'photo_67fc8e0c8e9246.49052277.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00'),
(15, 'Arif Hassan', 'arif@gmail.com', '$2y$10$ihsamlKmgmLsU2ZJzU0g0u12q5Tg1h79yDlko0wFfQEEEnQcOcN8e', '', 'user', '1', 'photo_68c246bd647473.16765242.png', '01750128167', 'Brahmanbaria, Bangladesh', 'Bangladesh', 'Monsef Para', 'Brahmanbaria', '3400', '2025-05-10'),
(16, 'Riyad Hossain', 'riyad@gmail.com', '$2y$10$W4fzoHUNz5MDJft191W1WOmO8kEg5MxL816OvxOTQb.zX3yXpsgKm', '', 'user', '1', '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_package`
--
ALTER TABLE `feature_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feature_package_ibfk_1` (`package_id`),
  ADD KEY `feature_package_ibfk_2` (`feature_id`);

--
-- Indexes for table `homepage_sections`
--
ALTER TABLE `homepage_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_name` (`section_name`);

--
-- Indexes for table `home_abouts`
--
ALTER TABLE `home_abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_banners`
--
ALTER TABLE `home_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizers`
--
ALTER TABLE `organizers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_days`
--
ALTER TABLE `schedule_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speakers`
--
ALTER TABLE `speakers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speaker_schedule`
--
ALTER TABLE `speaker_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_speaker_schedule_day` (`schedule_day_id`),
  ADD KEY `fk_speaker_schedule_item` (`schedule_id`),
  ADD KEY `fk_speaker_schedule_speaker` (`speaker_id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sponsor_category_id` (`sponsor_category_id`);

--
-- Indexes for table `sponsor_categories`
--
ALTER TABLE `sponsor_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feature_package`
--
ALTER TABLE `feature_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `homepage_sections`
--
ALTER TABLE `homepage_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `home_abouts`
--
ALTER TABLE `home_abouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_banners`
--
ALTER TABLE `home_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `organizers`
--
ALTER TABLE `organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `schedule_days`
--
ALTER TABLE `schedule_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `speakers`
--
ALTER TABLE `speakers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `speaker_schedule`
--
ALTER TABLE `speaker_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sponsor_categories`
--
ALTER TABLE `sponsor_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feature_package`
--
ALTER TABLE `feature_package`
  ADD CONSTRAINT `feature_package_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feature_package_ibfk_2` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `speaker_schedule`
--
ALTER TABLE `speaker_schedule`
  ADD CONSTRAINT `fk_speaker_schedule_day` FOREIGN KEY (`schedule_day_id`) REFERENCES `schedule_days` (`id`),
  ADD CONSTRAINT `fk_speaker_schedule_item` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`),
  ADD CONSTRAINT `fk_speaker_schedule_speaker` FOREIGN KEY (`speaker_id`) REFERENCES `speakers` (`id`);

--
-- Constraints for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD CONSTRAINT `sponsors_ibfk_1` FOREIGN KEY (`sponsor_category_id`) REFERENCES `sponsor_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
