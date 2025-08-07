-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 07, 2025 at 12:45 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u878574291_ccs`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `year_level` varchar(20) DEFAULT NULL,
  `program` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_contact_number` varchar(20) DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `has_voted` int(10) DEFAULT NULL,
  `qr_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `last_name`, `first_name`, `middle_initial`, `suffix`, `full_name`, `gender`, `year_level`, `program`, `contact_number`, `birth_date`, `email_address`, `profile_picture`, `status`, `guardian_name`, `guardian_contact_number`, `home_address`, `present_address`, `has_voted`, `qr_path`) VALUES
(208097, 'SORIA', 'ADRIAN TODZ', 'L', '', 'ADRIAN TODZ L. SORIA', 'MALE', '2ND YEAR', 'IT', NULL, NULL, NULL, 'uploads/ccs3.png', NULL, 'NANCY SORIA', '09075430346', 'JUMPERS DRIVE JOHN BOSCO DISTRICT MANGAGOY BISLIG CITY', 'JUMPERS DRIVE JOHN BOSCO DISTRICT MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/208097.png'),
(1003751, 'PERNITES', 'KENJI LENNOX', 'S', '', 'KENJI LENNOX S. PERNITES', 'MALE', '1ST YEAR', 'IT', '9753073286', '0000-00-00', 'Lennoxpernites@gmail.com', '', '', 'Richel S. Pernites', '9753073286', '', '', 0, 'uploads/qrcodes/1003751.png'),
(1410671, 'BIAÑO', 'VICTOR', 'Q', 'II', 'VICTOR Q. BIANO, II', 'MALE', '1ST YEAR', 'IT', '09455487905', '2003-11-30', 'victorbiano3@gmail.com', '', '', 'ELLEN Q. MONTENEGRO', '09455487905', 'San Roque, Gate 1, Forest Drive Village, Bislig City', 'San Roque, Gate 1, Forest Drive Village, Bislig City', 0, 'uploads/qrcodes/1410671.png'),
(1810075, 'GODIS', 'LEE CHRISTOPHER', 'P', '', 'LEE CHRISTOPHER P. GODIS', 'MALE', '1ST YEAR', 'IT', '09551141279', '2005-04-16', 'leechristiphergodis6@gmail.com', '', '', 'ANA P. PLETE', '09758458546', 'FILLINGS VILLAGE, MANGAGOY, BISLIG CITY', 'FILLINGS VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/1810075.png'),
(2220004, 'FAELNAR', 'MISSY', 'A', '', 'MISSY A. FAELNAR', 'FEMALE', '3RD YEAR', 'IT', '9124879213', '0000-00-00', 'faelnarmissy16@gmail.com', '', '', 'MENCHU F. PONTILLO', '0951 652 12', 'PUROK 6, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 6, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2220004.png'),
(2220005, 'CORTEZ', 'ZETHRO', 'J', '', 'ZETHRO J. CORTEZ', 'MALE', '3RD YEAR', 'IT', '9464766675', '0000-00-00', 'Zethrocortez0@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220005.png'),
(2220060, 'BACARON', 'MESSIAH JOHN', 'S', '', 'MESSIAH JOHN S. BACARON', 'MALE', '3RD YEAR', 'IT', '9471703605', '0000-00-00', 'matthewjohnbacaron31@gmail.com', '', '', 'BURT O. BACARON', '9204286468', '', '', 0, 'uploads/qrcodes/2220060.png'),
(2220061, 'BACARON', 'MATTHEW JOHN', 'S', '', 'MATTHEW JOHN S. BACARON', 'MALE', '3RD YEAR', 'IT', '9206330534', '0000-00-00', 'messiahjohnbacaron03@gmail.com', '', '', 'BURT O. BACARON', '9204286468', '', '', 0, 'uploads/qrcodes/2220061.png'),
(2220065, 'CASTRO', 'CHRISTIAN', 'L', '', 'CHIRSTIAN L. CASTRO', 'MALE', '3RD YEAR', 'IT', '9638316144', '0000-00-00', 'chirstianIcastro18@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220065.png'),
(2220066, 'GARAY', 'ROSENDO JAIME', 'L', '', 'ROSENDO JAIME L. GARAY', 'MALE', '3RD YEAR', 'IT', 'NONE', '0000-00-00', 'garayjaime@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220066.png'),
(2220070, 'MORENO', 'VINCENT', 'T', '', 'VINCENT T. MORENO', 'MALE', '2ND YEAR', 'IT', '9076365345', '2004-07-25', 'binsentmoreno@gmail.com', 'uploads/1000126313.jpg', '', 'JONING TUGAWIN', '9073635345', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2220070.png'),
(2220077, 'AMO', 'JEFRY', 'L', '', 'JEFRY L. AMO', 'MALE', '3RD YEAR', 'IT', '9388987533', '0000-00-00', 'jefrygroovie@gmail.com', '', '', 'FELIZARDO AMO', 'Not Availab', '', '', 0, 'uploads/qrcodes/2220077.png'),
(2220078, 'FEROLINO', 'JHUVIE', 'E', '', 'JHUVIE E. FEROLINO', 'MALE', '3RD YEAR', 'IT', '9260423096', '0000-00-00', 'jhuvieferolino@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220078.png'),
(2220079, 'LEONGAS', 'JUDE', 'G', '', 'JUDE G. LEONGAS', 'MALE', '3RD YEAR', 'IT', '9563113439', '0000-00-00', 'judeleongas@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220079.png'),
(2220080, 'MARTINEZ', 'KENT ANDRIAN', 'M', '', 'KENT ANDRIAN M. MARTINEZ', 'MALE', '3RD YEAR', 'IT', '9060310915', '0000-00-00', 'kentdrianmartinez@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220080.png'),
(2220082, 'VILLAESTER', 'CARL DAVE', 'C', '', 'CARL DAVE C. VILLAESTER', 'MALE', '3RD YEAR', 'IT', '9662986556', '2003-08-17', 'villaestercarl1713@gmail.com', '', '', 'RONALD CAGAMPANG', '9266689655', '', '', 0, 'uploads/qrcodes/2220082.png'),
(2220083, 'PAULE', 'JUDE', 'R', '', 'JUDE R. PAULE', 'MALE', '3RD YEAR', 'IT', '9700312165', '0000-00-00', 'gilbertchupa@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220083.png'),
(2220084, 'BALONG', 'RENNIEL', 'Y', '', 'RENNIEL Y. BALONG', 'MALE', '3RD YEAR', 'IT', '9565683790', '0000-00-00', 'rennielyntig@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220084.png'),
(2220085, 'CLAVERIA', 'JOHN LLOYD', 'C', '', 'JOHN LLOYD C. CLAVERIA', 'MALE', '3RD YEAR', 'IT', '9078701781', '0000-00-00', 'johnlloyd@gmail.com', '', '', 'CONRADO CLAVERIA', '0907 870 47', '', '', 0, 'uploads/qrcodes/2220085.png'),
(2220086, 'DATWIN', 'IRONE', 'E', '', 'IRONE E. DATWIN', 'MALE', '3RD YEAR', 'IT', '9945896729', '0000-00-00', 'ironedatwin@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220086.png'),
(2220089, 'DELGADO', 'KENNETH', 'A', '', 'KENNETH A. DELGADO', 'MALE', '3RD YEAR', 'IT', '9626393668', '0000-00-00', 'kennethdelgado81@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220089.png'),
(2220090, 'BASAÑEZ', 'STEPHEN ANGELO', 'A', '', 'STEPHEN ANGELO A. BASANES', 'MALE', '3RD YEAR', 'IT', '9267390627', '0000-00-00', 'stephenangelo200@gmail.com', '', '', 'MARILOU B. BATAUSA', '9267390627', '', '', 0, 'uploads/qrcodes/2220090.png'),
(2220092, 'TOLENTINO', 'ALDRIN', 'N', '', 'ALDRIN N. TOLENTINO', 'MALE', '3RD YEAR', 'IT', '9952676896', '2003-11-30', 'aldrintolentino412@gmail.com', '', '', 'RHEA C. NAMANG', '9952676896', '', '', 0, 'uploads/qrcodes/2220092.png'),
(2220113, 'BASUT', 'LOUIE JOHN', 'M', '', 'LOUIE JOHN M. BASUT', 'MALE', '3RD YEAR', 'IT', '9562114748', '0000-00-00', 'louiebasut@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220113.png'),
(2220127, 'INTING', 'ANJELEXES', 'B', '', 'ANJELEXES B. INTING', 'FEMALE', '3RD YEAR', 'IT', '9662145545', '0000-00-00', 'anjelexes@gmail.com', '', '', 'HERNAN DARYL S. INTING', '9456787535', 'UNION SITE DISTRICT, MANGAGOY, BISLIG CITY', 'UNION SITE DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2220127.png'),
(2220128, 'MONTA', 'KYLE DAVEN CHIT', 'R', '', 'KYLE DAVEN CHIT R. MONTA', 'MALE', '3RD YEAR', 'IT', 'NONE', '0000-00-00', 'kylengs18@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220128.png'),
(2220129, 'PASTRANO', 'JON MERVIN', 'D', '', 'JON MERVIN D. PASTRANO', 'MALE', '3RD YEAR', 'IT', 'NONE', '0000-00-00', 'mervinpastrano67@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220129.png'),
(2220141, 'GARCIA', 'EARL RYAN', 'F', '', 'EARL RYAN F. GARCIA', 'MALE', '3RD YEAR', 'IT', 'NONE', '0000-00-00', 'NONE', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2220141.png'),
(2220148, 'BATAUSA', 'JENNY', 'C', '', 'JENNY L. BATAUSA', 'FEMALE', '3RD YEAR', 'IT', '9466070441', '0000-00-00', 'jenbatz768@gmail.com', '', '', 'DINA L. BATAUSA', '9466070441', '', '', 0, 'uploads/qrcodes/2220148.png'),
(2220151, 'CAGOL', 'SHAIRINE ASHLEY', 'M', '', 'SHAIRINE ASHLEY R. CAGOL', 'FEMALE', '3RD YEAR', 'IT', '9560556902', '0000-00-00', 'cagolashley@gmail.com', '', '', 'MECY LLAMAS', '9560884968', '', '', 0, 'uploads/qrcodes/2220151.png'),
(2220152, 'MANANGAN', 'MARIELLA', 'R', '', 'MARIELLA MANANGAN', 'FEMALE', '3RD YEAR', 'IT', '9207414846', '0000-00-00', 'mananganmariella5@gmail.com', '', '', 'MARIBEL D. MANANGAN', '9207414846', '', '', 0, 'uploads/qrcodes/2220152.png'),
(2220159, 'SORIA', 'ALYANAH CASEY', 'G', '', 'ALYANAH CASEY G. SORIA', 'FEMALE', '3RD YEAR', 'IT', '9668226645', '2004-10-27', 'alyanahcaseysoria@gmail.com', '', '', 'NANCY SORIA', '9075430346', '', '', 0, 'uploads/qrcodes/2220159.png'),
(2220166, 'OCAMPO', 'KHIMWILLAM', 'B', '', 'KHIMWILLAM B. OCAMPO', 'MALE', '2ND YEAR', 'IT', '9303680490', '0000-00-00', 'ocampokhimwillam@gmail.com', '', '', 'ROMELITO OCAMPO', '9303680490', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2220166.png'),
(2220167, 'ALALAN', 'KEENAN PAUL', 'B', '', 'KEENAN PAUL B. ALALAN', 'MALE', '2ND YEAR', 'IT', '9662659022', '2004-09-27', 'ichijour559@gmail.com', 'uploads/ccs3.png', NULL, 'ROSALYN BENORE', '9665377717', 'VILLA JOSEFA, POBLACION, BISLIG CITY', 'VILLA JOSEFA, POBLACION, BISLIG CITY', 0, 'uploads/qrcodes/2220167.png'),
(2340003, 'ROMANO', 'CHIAH GRACE', 'O', '', 'CHIAH GRACE O. ROMANO', 'FEMALE', '2ND YEAR', 'IT', '9387138039', '2004-07-27', 'chiaromano@gmail.com', '', '', 'FERMINA ROMANO', '', 'PUROK 5, CARAMCAM, MANGAGOY, BISLIG CITY', 'PUROK 5, CARAMCAM, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340003.png'),
(2340008, 'CALUSAYAN', 'AJ', 'C', '', 'AJ C. CALUSAYAN', 'MALE', '2ND YEAR', 'IT', '9941025772', '2005-08-25', 'ajcalusayan8@gmail.com', '', '', 'ARMANDO P. CALUSAYAN', '9708722592', 'PUROK 2, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 2, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340008.png'),
(2340012, 'ALMERIA', 'MARIANNE GEM', 'D', '', 'MARIANNE GEM D. ALMERA', 'FEMALE', '2ND YEAR', 'CS', '9304467521', '0000-00-00', 'mariannealmeria918@gmail.com', '', '', 'GEMMA D. ALMERIA', '9300429039', '', '', 0, 'uploads/qrcodes/2340012.png'),
(2340013, 'SONGODANAN', 'KURT ANDRIE', 'T', '', 'KURT ANDRIE T. SONGODANAN', 'MALE', '2ND YEAR', 'IT', '9516327597', '2005-05-07', 'ksongodanan@gmail.com', NULL, '', NULL, NULL, 'PUROK 3, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 3, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340013.png'),
(2340014, 'DELA SALDE', 'ABEL ANGELO', 'P', '', 'ABEL ANGELO P. DE LA SALDE', 'MALE', '2ND YEAR', 'IT', '9777493451', '0000-00-00', 'abelangelo143@gmail.com', 'uploads/1000042737.jpg', '', 'VICENTA ORILLO', '9777493451', 'LINGIG, SURIGAO DEL SUR', 'LINGIG, SURIGAO DEL SUR', 0, 'uploads/qrcodes/2340014.png'),
(2340016, 'LIBRE', 'FRANCIS', 'B', '', 'FRANCIS B. LIBRE', 'MALE', '2ND YEAR', 'IT', '9664251959', '2006-04-14', 'francisfree15@gmail.com', '', '', 'FLORAMAY B. LIBRE', '9664251999', 'CACAYAN, MANGAGOY, BISLIG CITY', 'CACAYAN, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340016.png'),
(2340017, 'AVILA', 'PRINCESS KHEA', 'R', '', 'PRINCESS KHEA R. AVILA', 'FEMALE', '2ND YEAR', 'IT', '9682052356', '2005-06-05', 'avilaprincesskhea@gmail.com', 'uploads/Screenshot 2024-11-25 074851.png', '', 'ROLLY PAGENTE', '9568116330', 'PUROK 3, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 3, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340017.png'),
(2340019, 'ABRAO', 'JERICHO', 'L', 'JR.', 'JERICHO L. ABRAO, JR.', 'MALE', '2ND YEAR', 'IT', '12345678910', '2025-05-18', 'jbrao@gmail.com', 'uploads/ccs3.png', NULL, 'JERICHO L. ABRAO, I.', '12345678910', 'P2 BOGAC DIST. MANGAGOY BISLIG CITY', 'P2 BOGAC DIST. MANGAGOY BISLIG CITY', 0, ''),
(2340020, 'ZASPA', 'HANNIE MARIE', 'A', '', 'HANNIE MARIE A. ZASPA', 'FEMALE', '2ND YEAR', 'CS', '9947990336', '0000-00-00', '', '', '', 'ROLANDA A. ZASPA', '9387671321', '', '', 0, 'uploads/qrcodes/2340020.png'),
(2340021, 'DORMENDO', 'JENNY', 'R', '', 'JENNY R DORMENDO', 'FEMALE', '2ND YEAR', 'IT', '9859623817', '0000-00-00', 'dormendojeny@gmail.com', '', '', 'ROSITA DORMENDO', '9380744077', 'PUROK 3, SAN ISIDRO, BISLIG CITY', 'PUROK 3, SAN ISIDRO, BISLIG CITY', 0, 'uploads/qrcodes/2340021.png'),
(2340023, 'EGYPTO', 'MARGIE CLAIRE', 'T', '', 'MARIGIE T. EGYPTO', 'FEMALE', '2ND YEAR', 'IT', '9506608886', '0000-00-00', 'egyptoclaire@gmail.com', '', '', 'MAGDALENA P. EGYPTO', '9506608886', 'PUROK 2, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 2, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340023.png'),
(2340024, 'CANTILLAS', 'LEI VINCENT', 'L', '', 'LEI VINCENT L. CANTILLAS', 'MALE', '2ND YEAR', 'IT', '9700722994', '0000-00-00', 'Cantillaslei51@gmail.com', '', '', NULL, NULL, 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340024.png'),
(2340031, 'BUBAN', 'ACEPHER', 'P', '', 'ACEPHER P. BUBAN', 'MALE', '2ND YEAR', 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'uploads/qrcodes/2340031.png'),
(2340032, 'CONDE', 'RAYMART', 'P', '', 'RAYMART P. CONDE', 'MALE', '2ND YEAR', 'IT', '9638192382', '0000-00-00', 'ednocemart335@gmail.com', '', '', 'ROSITA CONDE', '9638192382', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340032.png'),
(2340035, 'FAJARDO', 'KLENT JAY', 'S', '', 'KLENT JAY S. FAJARDO', 'MALE', '2ND YEAR', 'IT', '9261245844', '0000-00-00', 'Kjfardz11@gmail.com', '', '', NULL, NULL, 'MARKETSITE DISTRICT, MANGAGOY BISLIG CITY', 'MARKETSITE DISTRICT, MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/2340035.png'),
(2340036, 'ALVAR', 'FREIDERHOD', 'L', 'JR.', 'FREIDERHOD L. ALVAR, JR.', 'MALE', '2ND YEAR', 'IT', '9692274385', '0000-00-00', 'jralvar13@gmail.com', '', '', 'ANALOU L. ALVAR', '9692274385', 'PUROK 5, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 5, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340036.png'),
(2340038, 'MONTAJES', 'AL FRANCIS', 'N', '', 'AL FRANCIS N. MONTAJES', 'MALE', '2ND YEAR', 'IT', '9619346211', '2005-04-24', 'almontajes@gmail.com', '', '', 'ALBERTO A. MONTAJES', '9666087870', 'PUROK 3, MANGGAWONG DISTRICT, MANGAGOY BISLIG CITY', 'PUROK 3, MANGGAWONG DISTRICT, MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/2340038.png'),
(2340040, 'MOZO', 'IAN CEDRIC', 'L', '', 'IAN CEDRIC L. MOZO', 'MALE', '2ND YEAR', 'IT', '9153564451', '2005-10-20', 'mozogeo77@gmail.com', '', '', 'HAZIEL MOZO', '9153564451', 'JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 'JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340040.png'),
(2340048, 'DELFIN', 'RENJIE', 'U', '', 'RENJIE U. DELFIN', 'MALE', '2ND YEAR', 'CS', '9303989943', '0000-00-00', 'delfinrenjie1129@gmail.com', '', '', 'LERMA U. DELFIN', '9518095100', '', '', 0, 'uploads/qrcodes/2340048.png'),
(2340049, 'BALBARES', 'EDWARD', 'A', '', 'EDWARD A. BALBARES', 'MALE', '2ND YEAR', 'IT', '9123478971', '0000-00-00', 'Balbarese@gmail.com', 'uploads/1000035302.jpg', '', 'MR. BALBARES', '9709228976', 'CORE 1, BISLIG CITY', 'CORE 1, BISLIG CITY', 0, 'uploads/qrcodes/2340049.png'),
(2340051, 'ORILLANEDA', 'CAPIE JEAN', 'S', '', 'CAPIE JEAN S. ORILLANEDA', 'FEMALE', '2ND YEAR', 'IT', '9928338571', '1999-08-29', 'orillaneda999@gmail.com', '', '', 'RICARDO ORILLANEDA', '9094784844', 'PUROK 8 ROAD 1, MAHARLIKA, BISLIG CITY', 'PUROK 8 ROAD 1, MAHARLIKA, BISLIG CITY', 0, 'uploads/qrcodes/2340051.png'),
(2340054, 'ALBERIO', 'JOHN LLOYD', 'C', '', 'JOHN LLOYD C. ALBERIO', 'MALE', '2ND YEAR', 'CS', '9274868151', '0000-00-00', 'johnlloydalberio04@gmail.com', '', '', 'ELIZA C. ALBERIO', '9274486851', '', '', 0, 'uploads/qrcodes/2340054.png'),
(2340058, 'PABELONIA', 'ZYKIE', 'A', '', 'ZYKIE A. PABELONIA', 'FEMALE', '2ND YEAR', 'CS', '9919296365', '0000-00-00', '', '', '', 'BERLY C. ARANAS', '95611259', '', '', 0, 'uploads/qrcodes/2340058.png'),
(2340061, 'NAHIAL', 'ISRAEL', 'L', '', 'ISRAEL L. NAHIAL', 'MALE', '2ND YEAR', 'IT', '9317521012', '2004-03-20', 'ellavandero2005@gmail.com', '', '', 'AIDA L. NAHIAL', '9317521012', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340061.png'),
(2340069, 'LARA', 'DEVAN DAVE', 'N', '', 'DEVAN DAVE N. LARA', 'MALE', '2ND YEAR', 'IT', '9317521000', '0000-00-00', 'devandavelara88@gmail.com', 'uploads/1000051588.jpg', '', 'JUDITH LARA', '9317521007', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340069.png'),
(2340070, 'NAHIAL', 'GODPRAY', 'L', '', 'GODPRAY L. NAHIAL', 'MALE', '2ND YEAR', 'IT', '09317521012', '2004-03-20', 'ngodpray@gmail.com', 'uploads/1000027801.jpg', '', 'AIDA NAHIAL', '09514519549', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340070.png'),
(2340076, 'MACALOS', 'MARJORIE', 'A', '', 'MARJORIE A. MACALOS', 'FEMALE', '2ND YEAR', 'IT', '9272860744', '0000-00-00', 'mrjrmcls@gmail.com', 'uploads/profile_2340076_1740469866.png', '', 'MARISSA VOCAL', '9662345265', 'PUROK 2A, POBLACION, LINGIG, SURIGAO DEL SUR', 'PUROK 2A, POBLACION, LINGIG, SURIGAO DEL SUR', 0, 'uploads/qrcodes/2340076.png'),
(2340080, 'MORALES', 'JOHN LEMUEL', 'L', '', 'JOHN LEMUEL L. MORALES', 'MALE', '2ND YEAR', 'IT', '9382063450', '2004-11-19', 'lemuelj818@gmail.com', '', '', 'ELIVIRA L MORALES', '9941059530', 'PUROK 1, CARAMCAM, DISTRICT, MANGAGOY BISLIG CITY', 'PUROK 1, CARAMCAM, DISTRICT, MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/2340080.png'),
(2340081, 'LAURENTE', 'LORRAINE ANNE', 'G', '', 'LORRAINE ANNE G. LAURENTE', 'FEMALE', '2ND YEAR', 'IT', '9317873872', '0000-00-00', 'lorrainelaurente18@gmail.com', 'uploads/1000126303.jpg', '', 'JESSIVEL G. LAURENTE', '9101704710', 'PUROK 1, MAHARLIKA, BISLIG CITY', 'PUROK 1, MAHARLIKA, BISLIG CITY', 0, 'uploads/qrcodes/2340081.png'),
(2340082, 'CUANAN', 'JONAH', 'G', '', 'JONAH G. CUANAN', 'FEMALE', '2ND YEAR', 'IT', '9940048921', '0000-00-00', 'jonahcuanan15@gmail.com', '', '', 'JONAS ROJAS CUANAN', '9482593670', 'BAYBAY 1, POBLACION, BISLIG CITY', 'BAYBAY 1, POBLACION, BISLIG CITY', 0, 'uploads/qrcodes/2340082.png'),
(2340084, 'TORREON', 'JONAVIE', 'A', '', 'JONAVIE A. TORREON', 'FEMALE', '2ND YEAR', 'CS', '9532536508', '2004-12-14', 'ascarez.jonavie@gmail.com', '', '', 'EUGENE VI A. TORREON', '9071498019', '', '', 0, 'uploads/qrcodes/2340084.png'),
(2340085, 'GOMERA', 'ANDRES', 'C', '', 'ANDRES C. GOMERA', 'MALE', '2ND YEAR', 'CS', '9702724765', '0000-00-00', 'andrygomera54@gmail.com', '', '', 'INES C. GOMERA', '9050851220', '', '', 0, 'uploads/qrcodes/2340085.png'),
(2340086, 'CATAYOC', 'MICHEALLE MAE', 'R', '', 'MICHEALLE MAE R. CATAYOC', 'FEMALE', '2ND YEAR', 'IT', '09350262110', '2025-05-21', 'Micheallecatayoc@gmail.com', '', '', 'ERNIE R. CATAYOC', '09453447087', 'PUROK TALISAY 2, SAN ANTONIO, DAVAO ORIENTAL', 'PUROK 8, SANTA MARIA, TABON, BISLIG CITY', 0, 'uploads/qrcodes/2340086.png'),
(2340087, 'SALVA', 'JULIUS VINCENT', 'G', '', 'JULIUS VINCEN T G. SALVA', 'MALE', '2ND YEAR', 'CS', 'NONE', '0000-00-00', 'NONE', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2340087.png'),
(2340092, 'SUMIGUIN', 'ANDRIAN', 'D', '', 'ANDRIAN D. SUMIGUIN', 'MALE', '2ND YEAR', 'IT', '9167736853', '2004-10-31', 'andriansumiguin31@gmail.com', 'uploads/profile_2340092_1741065934.jpg', '', 'GLENDRE SUMIGUIN', '9167736853', 'PUROK 7, POST 12, TABON, BISLIG CITY', 'PUROK 7, POST 12, TABON, BISLIG CITY', 0, 'uploads/qrcodes/2340092.png'),
(2340095, 'PAGAYANAN', 'KURT RUSSEL', 'C', '', 'KURT RUSSEL C. PAGAYANAN', 'MALE', '2ND YEAR', 'IT', '9949567100', '2005-07-30', 'pagayananrussel5@gmail.com', '', '', 'EMILIA PAGAYANAN', '', 'PUROK 1, GAMAON DISTRICT, MANGAGOY BISLIG CITY', 'PUROK 1, GAMAON DISTRICT, MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/2340095.png'),
(2340098, 'GUIAS', 'JUSTIN MARTIN', 'F', '', 'KING JAMES F. GUIAS', 'MALE', '2ND YEAR', 'CS', 'NONE', '0000-00-00', 'NONE', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2340098.png'),
(2340099, 'LACHICA', 'MARIO EMMANUEL', 'C', '', 'MARIO EMMANUEL C. LACHICA', 'MALE', '2ND YEAR', 'CS', '9476267361', '0000-00-00', '', '', '', 'ESPERANZA C. LACHICA', '9774803941', '', '', 0, 'uploads/qrcodes/2340099.png'),
(2340101, 'REIN', 'VINCE PAUL', 'A', '', 'VINCE PAUL A. REIN', 'MALE', '2ND YEAR', 'CS', '9946956815', '0000-00-00', 'NONE', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2340101.png'),
(2340105, 'OTUGAY', 'KRISTYL IVY', 'T', '', 'KRISTYL IVY T. UTOGAY', 'FEMALE', '2ND YEAR', 'IT', '9944618531', '2004-11-21', 'otugaykristyl311@gmail.com', '', '', 'CHERLIE T. OTUGAY', '9815228990', 'PUROK 3, PAMAYPAYAN, BISLIG CITY', 'PUROK 10A, SUG-UBON, TABON, BISLIG CITY', 0, 'uploads/qrcodes/2340105.png'),
(2340108, 'BAYLOSIS', 'FRANZ EZRA', 'C', '', 'FRANZ EZRA C. BAYLOSIS', 'MALE', '2ND YEAR', 'CS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ''),
(2340124, 'LIM', 'SIMON PETER', 'C', '', 'SIMON PETER C. LIM', 'MALE', '2ND YEAR', 'IT', '09754144792', '1992-03-19', 'simonlimboy031992@gmail.com', 'uploads/a02c4f2f-8a53-4c42-a34d-5a7081d4754e-1_all_118.jpg', '', 'SUSAN C. LIM', '09945158690', 'P3 GAMAON DIST.', 'P3 GAMAON DIST.', 0, 'uploads/qrcodes/2340124.png'),
(2340126, 'TROCIO', 'LYKA MAE', 'B', '', 'LYKA MAE B. TROCIO', 'FEMALE', '2ND YEAR', 'IT', '9560849255', '2005-06-25', 'lykameatrocio@gmail.com', '', '', 'DAISYLYN TROCIO', '945430721', 'PUROK 3, MANGGAWONG DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 3, MANGGAWONG DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340126.png'),
(2340127, 'CUIMAN', 'DANIELA MARIEL', 'C', '', 'DANIELA MARIEL C. CUIMAN', 'FEMALE', '2ND YEAR', 'CS', '9560967990', '0000-00-00', 'ego.sensei20@gmail.com', '', '', 'JOSEPHINE CABUSAS', '9560967990', '', '', 0, 'uploads/qrcodes/2340127.png'),
(2340128, 'DELOS SANTOS', 'FRANCIS DAVE', 'T', '', 'FRANCIS DAVE T. DELOS SANTOS', 'MALE', '2ND YEAR', 'IT', '9060714498', '0000-00-00', 'franzdavedelossantos@gmail.com', '', '', 'MRS. DELOS SANTOS', '9060714498', 'PUROK 8, PIAPI STREET, BISLIG CITY', 'PUROK 8, PIAPI STREET, BISLIG CITY', 0, 'uploads/qrcodes/2340128.png'),
(2340137, 'TERIOTE', 'KYLA MARIE', 'G', '', 'KYLA MARIE G. TERIOTE', 'FEMALE', '2ND YEAR', 'IT', '9454367217', '0000-00-00', 'teriotekylamarie@gmail.com', '', '', 'MARLON M. TERIOTE', '9560849255', 'PUROK 7, GORDONAS, JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 7, GORDONAS, JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340137.png'),
(2340144, 'GEREDA', 'LARRA MAE', 'C', '', 'LARRA MAE C. GEREDA', 'FEMALE', '2ND YEAR', 'CS', '9276026650', '0000-00-00', 'larramaegereda@gmail.com', '', '', 'GENALYN RAVELO', '9563605221', '', '', 0, 'uploads/qrcodes/2340144.png'),
(2340154, 'SACAL', 'DAVE', 'Y', '', 'DAVE Y. SACAL', 'MALE', '2ND YEAR', 'IT', '9497811854', '2000-02-20', 'davesacal78@gmail.com', '', '', 'RIO GERBESE', '9949503496', '', '', 0, 'uploads/qrcodes/2340154.png'),
(2340164, 'PLAZA', 'RAMELYN', 'E', '', 'RAMELYN E. PLAZA', 'FEMALE', '2ND YEAR', 'IT', '9705950310', '2003-12-01', 'ramplaza29@gmail.com', '', '', 'SHUETLEE GARNET T. MAYNAGCOT', '9949520717', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 4, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340164.png'),
(2340195, 'BILLONES', 'WELSON JAMES', 'M', '', 'WELSON JAMES M. BILLIONES', 'MALE', '2ND YEAR', 'IT', '9482348668', '0000-00-00', 'welsonkyut@gmail.com', '', '', 'GERALDINE M. BILLIONES', '9084842611', '', '', 0, 'uploads/qrcodes/2340195.png'),
(2340204, 'PADILLA', 'ADRIAN', 'P', '', 'ADRIAN P. PADILLA', 'MALE', '2ND YEAR', 'CS', '9276026123', '0000-00-00', 'andrianpia2003@gmail.com', '', '', 'ANNIE MAE E. PIA', '9264984018', '', '', 0, 'uploads/qrcodes/2340204.png'),
(2340213, 'JUMAWAN', 'JAMES IVAN', 'M', '', 'JAMES IVAN M. JUMAWAN', NULL, '2ND YEAR', 'IT', '9159032353', '0000-00-00', 'jamesivanmjumawan@gmail.com', '', '', 'ANTONIO M. JUMAWAN', '9183577742', 'PUROK 1, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340213.png'),
(2340222, 'COMANDA', 'JULIAN ROB', 'L', '', 'JULIAN ROB L. COMANDA', 'MALE', '2ND YEAR', 'CS', '9560138151', '0000-00-00', '', '', '', 'JOLLY L. COMANDA', '9560138151', '', '', 0, 'uploads/qrcodes/2340222.png'),
(2340226, 'MONTENEGRO', 'MATTHEW ANDRIEL', 'M', '', 'MATTHEW ANDRIEL M. MONTENEGRO', 'MALE', '2ND YEAR', 'IT', '9942270462', '2006-11-17', 'matthewandrielmontenegro@gmail.com', '', '', 'ANALIZA M. MONTENEGRO', '9942270462', 'PUROK 10, SUG-UBON, TABON, BISLIG CITY', 'PUROK 10, SUG-UBON, TABON, BISLIG CITY', 0, 'uploads/qrcodes/2340226.png'),
(2340229, 'CASTRO', 'JAVIER', 'M', 'JR.', 'JAVIER M. CASTRO, JR.', 'MALE', '2ND YEAR', 'IT', '9948016017', '0000-00-00', 'javiercastrojr@gmail.com', 'uploads/1000026239.jpg', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2340229.png'),
(2340232, 'SUYOT', 'JAMES RYAN', 'A', '', 'JAMES RYAN A. SUYOT', 'MALE', '1ST YEAR', 'IT', '09274059601', '2005-11-10', 'jamezsuuyyy@gmail.com', '', '', 'SHEILA A. SUYOT', '09554559835', 'p1 jumpers drive john bosco dist. mangagoy bislig city', 'p1 jumpers drive john bosco dist. mangagoy bislig city', 0, 'uploads/qrcodes/2340232.png'),
(2340236, 'TOSING', 'MARILOU', 'A', '', 'MARILOU A. TOSING', 'FEMALE', '2ND YEAR', 'IT', '9630537473', '2000-07-08', 'mariloutosing4@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2340236.png'),
(2340248, 'DEGUIÑO', 'AL', 'N', '', 'AL N. DEGUIÑO', 'MALE', '1ST YEAR', 'IT', 'NONE', '2003-03-09', 'NONE', '', '', NULL, NULL, 'P3- GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 'P3- GAMAON DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2340248.png'),
(2340249, 'MUNDAS', 'JAN CELESTINE', 'A', '', 'JAN CELESTINE A. MUNDAS', 'MALE', '2ND YEAR', 'IT', '9560840879', '2005-01-21', 'mundasjc123@Gmail.com', '', '', 'VIRGINIA A. MUNDAS', '9053197908', '', '', 0, 'uploads/qrcodes/2340249.png'),
(2340252, 'VISCARA', 'JOHN CARL', 'C', '', 'JOHN CARL C. VISCARA', 'MALE', '1ST YEAR', 'IT', '9560849215', '0000-00-00', '', '', '', 'EVANGELINE VISCARA', '', 'ERICSON, BISLIG CITY', 'ERICSON, BISLIG CITY', 0, 'uploads/qrcodes/2340252.png'),
(2340253, 'BACUD', 'NIKKO RENZ', 'R', '', 'NIKKO RENZ R. BACUD', 'MALE', '2ND YEAR', 'IT', '09158581242', '2002-11-25', 'bacudnikkorenzr@gmail.com', '', '', 'NESA R. BACUD', '09158582425', 'Ericson Subdivision, Poblacion, Bislig CIty', 'Ericson Subdivision, Poblacion, Bislig CIty', 0, 'uploads/qrcodes/2340253.png'),
(2440001, 'ZULIETA', 'JERICK JEHU', 'G', '', 'JERICK JEHU G. ZULIETA', 'MALE', '1ST YEAR', 'IT', '', '0000-00-00', '', '', '', 'JULIET A. GARAY', '9197114713', '', '', 0, 'uploads/qrcodes/2440001.png'),
(2440026, 'POLINA', 'KAYE MARIE', 'L', '', 'KAYE MARIE L. POLINA', 'FEMALE', '1ST YEAR', 'IT', '9275893024', '0000-00-00', 'kayemariepolina@gmail.com', '', '', 'MARVIN B. POLINA', '9279783756', '', '', 0, 'uploads/qrcodes/2440026.png'),
(2440029, 'EYAC', 'ASHLY', 'J', '', 'ASHLY J. EYAC', 'FEMALE', '1ST YEAR', 'IT', '09464768584', '2004-03-04', 'ashlyeyaceyac@gmail.com', '', '', 'LONNIIE G. SINGOL', '09511789917', 'P-12 SANRAfAEL, MAHARLIKA, BISLIG CITY', 'P-12 SANRAfAEL, MAHARLIKA, BISLIG CITY', 0, 'uploads/qrcodes/2440029.png'),
(2440032, 'TEJERO', 'GILBERT', 'G', 'III', 'GILBERT G. TEJERO, III', 'MALE', '1ST YEAR', 'IT', '9943227615', '2006-07-16', 'tejerogilbert89@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2440032.png'),
(2440034, 'DE LOS SANTOS', 'YESHA', 'C', '', 'YESHA C. DE LOS SANTOS', 'FEMALE', '1ST YEAR', 'IT', '09774060975', '2004-07-03', 'yeshadelossantos9@gmail.com', '', '', 'RAQUEL C. DE LOS SANTOS', '09541008044', 'P2A- POBLACION, LINGIG', 'P2A- POBLACION, LINGIG', 0, 'uploads/qrcodes/2440034.png'),
(2440035, 'TANDUGON', 'DENMARK', 'B', '', 'DENMARK B.TANDUGON', 'MALE', '1ST YEAR', 'IT', '9271883807', '2006-06-03', 'denmarktandugon@gmail.com', '', '', 'ISABEL C. TANDUGON', '9667527960', '', '', 0, 'uploads/qrcodes/2440035.png'),
(2440036, 'ECO', 'LANCE LOWELL', 'L', '', 'LANCE LOWELL L. ECO', 'MALE', '1ST YEAR', 'IT', '09685361255', '2006-05-18', 'lanceeco63@gmail.com', '', '', 'IRIS L. ECO', '09614479853', 'P-4 CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'P-4 CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440036.png'),
(2440037, 'FERNANDEZ', 'KENJI', 'R', '', 'KENJI R. FERNANDEZ', 'MALE', '1ST YEAR', 'IT', '09559636712', '2005-10-21', 'kenjifernandez4@gmail.com', '', '', 'MARISA R. FERNANEZ', '09559636712', 'P-9 CALUBIAN TABON, BISLIG CITY', 'P-9 CALUBIAN TABON, BISLIG CITY', 0, 'uploads/qrcodes/2440037.png'),
(2440038, 'ANIANA', 'AYISHA KATE', 'S', '', 'AYISHA GRACE S. ANIANA', 'FEMALE', '1ST YEAR', 'IT', '09551559803', '2006-08-05', '', '', '', 'Wilfredo D. Aniana', '09670127340', 'P-1 JUMPERS DRIVE, JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 'P-1 JUMPERS DRIVE, JOHN BOSCO DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440038.png'),
(2440042, 'MONZON', 'HYRIE JHON', 'M', '', 'HYRIE JHON M. MONZON', 'MALE', '1ST YEAR', 'IT', '9102273674', '0000-00-00', 'monzonhyrie@gmail.com', '', '', 'HASEL M. MONZON', '9197110617', 'P1 CAATIHAN BOSTON, DAVAO ORRIENTAL', 'P1 JUMPERS DRIVE, JOHN BOSCO DISTRIC MANGAGOY BISLIG CITY', 0, 'uploads/qrcodes/2440042.png'),
(2440043, 'OLVIDA', 'JANILLE', 'M', '', 'JANILLE M. OLVIDA', NULL, '1ST YEAR', 'IT', 'NONE', '0000-00-00', 'NONE', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2440043.png'),
(2440044, 'DAIGDIGAN', 'JESREEL', 'B', '', 'JESREEL B. DAIGDIGAN', NULL, '1ST YEAR', 'IT', '09262943002', '0000-00-00', '1116jorel@gmail.com', '', '', 'ARMANDO P. DAIGDIGAN', '09262943002', 'P1- CUMAWAS, BISLIG CITY', 'P1- CUMAWAS, BISLIG CITY', 0, 'uploads/qrcodes/2440044.png'),
(2440045, 'ATOK', 'YESHA FAITH DOMINIC', 'D', '', 'YESHA FAITH DOMINIC D.  ATOK', NULL, '1ST YEAR', 'IT', '09515377121', '2006-09-06', 'yeshafaithdominicatok07@gmail.com', '', '', 'RUBY D. CABALUNA', '09955116505', 'P-2 CARAMCAM, MANGAGOY, BISLIG CITY', 'P-2 CARAMCAM, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440045.png'),
(2440046, 'MAGNO', 'SHYMAE', 'G', '', 'SHYMAE G. MAGNO', NULL, '1ST YEAR', 'IT', '9514683928', '0000-00-00', 'shymaemagno@gmail.com', '', '', 'ANGELICA G. MAGNO', '9514683928', '', '', 0, 'uploads/qrcodes/2440046.png'),
(2440047, 'VISCARA', 'VIN AXEL', 'S', '', 'VIN AXEL S. VISCARA', NULL, '1ST YEAR', 'IT', '9560849215', '0000-00-00', '', '', '', 'ANGELITA VISCARA', '9560849217', 'TRAMO, BRGY 156, ZONE 16 PASAY CITY', 'P-14 ERICSON SUBDIVISON, BISLIG CITY', 0, 'uploads/qrcodes/2440047.png'),
(2440048, 'ADLAWAN', 'IANSEL', 'S', '', 'IANSEL S. ADLAWAN', 'MALE', '1ST YEAR', 'IT', NULL, NULL, NULL, 'uploads/1741701171203.jpg', NULL, NULL, NULL, NULL, NULL, 0, ''),
(2440051, 'GUIWAN', 'MARK LOUIE', 'L', '', 'MARK LOUIE L. GUIWAN', 'MALE', '1ST YEAR', 'IT', '09943432112', '2005-10-17', 'louiemark839@gmail.com', '', '', 'Maryjean L. Guiwan', '0975413664', 'FOREST DRIVE VILLAGE, SAN ROQUE, BISLIG CITY ', 'FOREST DRIVE VILLAGE, SAN ROQUE, BISLIG CITY ', 0, 'uploads/qrcodes/2440051.png'),
(2440052, 'ORTIZ', 'HYACINTH GLEECE', 'T', '', 'HYACITH GLEECE T. ORTIZ', 'FEMALE', '1ST YEAR', 'IT', '', '0000-00-00', '', '', '', 'DECY T. ORTIZ', '9077971652', '', '', 0, 'uploads/qrcodes/2440052.png'),
(2440053, 'PALER', 'CIDNICOLE', 'C', '', 'CIDNICOLE C. PALER', NULL, '1ST YEAR', 'IT', '9774070186', '0000-00-00', 'cidnicolepaler@gmail.com', '', '', 'REGIELIN PALER', '9774070186', '', '', 0, 'uploads/qrcodes/2440053.png'),
(2440054, 'DULOHAN', 'CHARLJAY', 'M', '', 'CHARLJAY M. DULOHAN', NULL, '1ST YEAR', 'IT', '09934873315', '2006-06-09', 'Jaydul1744@gmail.com', '', '', 'Jomiluz M. Dulohan', '09153577671', 'P-3A CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'P-3A CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440054.png'),
(2440055, 'BALONG', 'RYAN RALPH', 'B', '', 'RYAN RALPH B. BALONG', 'MALE', '1ST YEAR', 'IT', '09105439754', '2006-05-12', '', '', '', 'DIVINA B. BALONG', '09511424191', 'P-3 MANGGAWONG, MANGAGOY, BISLIG CITY', 'P-3 MANGGAWONG, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440055.png'),
(2440056, 'LOOD', 'JOLA MARY', 'P', '', 'JOLA MARY P. LOOD', NULL, '1ST YEAR', 'IT', '9947738956', '0000-00-00', 'loodlamary@gmail.com', '', '', 'LAWRENCE S. LOOD', '9566100124', '', '', 0, 'uploads/qrcodes/2440056.png'),
(2440057, 'ALMUCERA', 'ALEXAN JANE', 'L', '', 'ALEXAN JANE L. ALMUCERA', 'FEMALE', '1ST YEAR', 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ''),
(2440058, 'ALANO', 'ELJHON', 'D', '', 'ELJHON D. ALANO', 'MALE', '1ST YEAR', 'IT', '09774065481', '2006-07-04', 'alijhonalano8@gmail.com', '', '', 'HELEN ALANO', '09452454369', 'P-5 Poblacion Lingig', 'P-5 Poblacion Lingig', 0, 'uploads/qrcodes/2440058.png'),
(2440059, 'ORILLO', 'ALDREN', 'Q', '', 'ALDREN Q. ORILLO', NULL, '1ST YEAR', 'IT', '9973200104', '0000-00-00', 'Aldrenorillo1219@gmail.com', '', '', 'Delma Q. Orillo', '9519128010', '', '', 0, 'uploads/qrcodes/2440059.png'),
(2440060, 'SILVOSA', 'ROGIL', 'A', '', 'ROGIL  A. SILVOSA', NULL, '1ST YEAR', 'IT', '09195180767', '2004-07-19', 'rogil.silvosa@gmail.com', '', '', 'APRIL GRACE S. SILVOSA', '09568547928', 'LINGIG surigao del sur', 'la salle drive john bosco district', 0, 'uploads/qrcodes/2440060.png'),
(2440061, 'MAGNO', 'CRESTIAN KEM', 'G', '', 'CRESTIAN KEM G. MAGNO', NULL, '1ST YEAR', 'IT', '9504656073', '0000-00-00', 'Crestiankemm@gmail.com', '', '', 'Delia A. Magno', '9543054201', '', '', 0, 'uploads/qrcodes/2440061.png'),
(2440062, 'TUBO', 'GUEN', 'B', '', 'GUEN B. TUBO', NULL, '1ST YEAR', 'IT', '9351191272', '2006-04-21', 'guentubo69@gmail.com', '', '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2440062.png'),
(2440063, 'DUENAS', 'MARC JHUN ADRIAN', 'V', '', 'MARC JHUN ANDRIAN V. DUENAS', NULL, '1ST YEAR', 'IT', '09952650600', '2006-03-16', 'marcjhun15@icloud.com', '', '', 'HANNAH V. DUEÑAS', '09952650600', 'P-1 POBLACION, LINGIG', 'P-1 POBLACION, LINGIG', 0, 'uploads/qrcodes/2440063.png'),
(2440064, 'OJANO', 'XANDER FEBB', 'S', '', 'XANDER FEBB S. OJANO', NULL, '1ST YEAR', 'IT', '9942270436', '0000-00-00', 'ojanoxander@gmail.com', '', '', 'Ojano, Gennyrose S.', '9942270436', '', '', 0, 'uploads/qrcodes/2440064.png'),
(2440163, 'PEJERNIN', 'JOELLA', 'G', '', 'JOELLA G. PEJERNIN', NULL, '1ST YEAR', 'IT', '9163976712', '0000-00-00', 'lala.pejernin@gmail.com', '', '', 'ELYN C. PEJERNIN', '9270028784', 'P2B POBLACION LINGIG SURIGAO DEL SUR', 'P2B POBLACION LINGIG SURIGAO DEL SUR', 0, 'uploads/qrcodes/2440163.png'),
(2440165, 'ANGCOY', 'PAUL XYRIL', 'V', '', 'PAUL XYRIL V. ANGCOY', 'MALE', '1ST YEAR', 'IT', '09102405766', '2006-01-09', 'paulxyril09@gmail.com', '', '', 'TERESITA ANGCOY', '09102405766', 'P-3 Maharlika Mangagoy Bislig City', 'P-3 Maharlika Mangagoy Bislig City', 0, 'uploads/qrcodes/2440165.png'),
(2440168, 'CARLON', 'QUEENIELYN JOY', 'M', '', 'QUEENIELYN JOY M. CARLON', NULL, '1ST YEAR', 'IT', '09854694280', '2006-04-20', 'carlonqueenielynjoy@gmail.com', '', '', 'BERLIN M. CARLON', '09100126689', 'CORE SHELTER 2 POBLACION, BISLIG CITY', 'CORE SHELTER 2 POBLACION, BISLIG CITY', 0, 'uploads/qrcodes/2440168.png'),
(2440169, 'BARTOLAZO', 'IRISH CLAIRE', 'F', '', 'IRISH CLAIRE F. BARTOLAZO', NULL, '1ST YEAR', 'IT', '09203545500', '2006-06-29', '', '', '', 'IRENE F. BARTOLAZO', '09505862804', 'P-1 MONE, BISLIG CITY', 'P-1 MONE, BISLIG CITY', 0, 'uploads/qrcodes/2440169.png'),
(2440185, 'TAJALE', 'JERECO', 'C', '', 'JERECO C. TAJALE', 'MALE', '1ST YEAR', 'IT', '9673678381', '2005-11-06', 'jerecotajale@gmail.com', '', '', 'JUDY S. TAJALE', '9078252182', 'P-4 UnionSite District Mangagoy bislig City', 'P-4 UnionSite District Mangagoy bislig City', 0, 'uploads/qrcodes/2440185.png'),
(2440192, 'JIMENEZ', 'ALTHEA', 'S', '', 'ALTHEA S. JIMENEZ', 'FEMALE', '1ST YEAR', 'IT', '092799784725', '2004-09-18', 'altheajimenez360@gmail.com', '', '', 'EMMA J. JALA', '09227016454', 'GATE 2 CAMANCHILE STREET P-3 FOREST DRIVE VILLAGE, SAN ROQUE, BISLIG CITY', 'GATE 2 CAMANCHILE STREET P-3 FOREST DRIVE VILLAGE, SAN ROQUE, BISLIG CITY', 0, 'uploads/qrcodes/2440192.png'),
(2440194, 'JALE', 'JASPER HARVEY', 'C', '', 'JASPER HARVEY C. JALE', 'MALE', '1ST YEAR', 'IT', '09666935028', '2006-02-13', 'jasperharveyjale@gmail.com', '', '', 'JONATHAN S. JALE', '09536834653', 'P-9 CALUBIAN TABON, BISLIG CITY', 'P-9 CALUBIAN TABON, BISLIG CITY', 0, 'uploads/qrcodes/2440194.png'),
(2440198, 'GALANO', 'ZYANN', 'M', '', 'ZYANN M. GALANO', 'MALE', '1ST YEAR', 'IT', 'NONE', '2004-05-30', 'Zyanngalano0903@gmail.com', '', '', NULL, NULL, 'SAN VICENTE 1 MANGAGOY, BISLIG CITY', 'SAN VICENTE 1 MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440198.png'),
(2440200, 'ANGELES', 'YZAH GRACE', 'A', '', 'YZZAH GRACE A. ANGELES', 'FEMALE', '1ST YEAR', 'IT', '09994691638', '2005-11-28', 'alexanjanealmucera@gmail.com', '', '', 'HEDELIZA P. APOLO', '09665760466', 'P-1 Cacayan John Bosco District Mangagoy Bislig City', 'P-1 Cacayan John Bosco District Mangagoy Bislig City', 0, 'uploads/qrcodes/2440200.png'),
(2440209, 'PICASALES', 'RENIROSE', 'A', '', 'RENIROSE A. PICASALES', 'FEMALE', '1ST YEAR', 'IT', '9660266444', '0000-00-00', 'renzpcs@gmail.com', '', '', 'VIVIAN PICASALES', '9276716601', 'PUROK 1, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440209.png'),
(2440215, 'SUPREMO', 'BETHANIE', 'C', '', 'BETHANIE C. SUPREMO', 'FEMALE', '1ST YEAR', 'IT', '9560142020', '2004-12-02', 'Bethaniecasillanosupremo17@gmail.com', '', '', 'CHERRY C. SUPREMO', '9560184164', '', '', 0, 'uploads/qrcodes/2440215.png'),
(2440217, 'MAGALLANES', 'RUTHER', 'G', '', 'RUTHER G. MAGALLANES', 'MALE', '1ST YEAR', 'IT', '9639599956', '0000-00-00', 'magallanesruther8@gmail.com', '', '', 'ROSALINDA G. MAGALLANES', '9354822374', 'PUROK 6, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 'PUROK 6, CASTILLO VILLAGE, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440217.png'),
(2440229, 'MAGLINTE', 'CHRISTIAN JADE', 'D', '', 'CHRISTIAN JADE D. MAGLINTE', 'MALE', '1ST YEAR', 'IT', '9954757480', '0000-00-00', 'chirstianjade311@gmail.com', '', '', 'Christy Maglinte', '9278547315', 'P9 CALUBIAN TABON, BISLIG CITY', 'P9 CALUBIAN TABON, BISLIG CITY', 0, 'uploads/qrcodes/2440229.png'),
(2440231, 'JACINTO', 'GREGORY', 'M', '', 'GREGORY M. JACINTO', 'MALE', '1ST YEAR', 'IT', '09855868669', '2000-12-23', 'geraldjacinto03@gmail.com', '', '', 'JUDITH J. ALCARAZ', '09885868669', 'PUROK 1, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 'PUROK 1, CARAMCAM DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440231.png'),
(2440247, 'GUANGCO', 'ANNA', 'T', '', 'ANNA T. GUANGCO', 'FEMALE', '1ST YEAR', 'IT', '9939791220', '0000-00-00', 'guangcoanna6@gmail.com', NULL, '', NULL, NULL, '', '', 0, 'uploads/qrcodes/2440247.png'),
(2440249, 'AVILA', 'LESLIE', 'R', '', 'LESLIE R. AVILA', 'FEMALE', '1ST YEAR', 'IT', '09912517812', '0000-00-00', 'avilaprincesskhea@gmail.com', '', '', 'FELIPE J. AVILA', '09466048752', 'P-3 RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 'P-3 RIVERSIDE DISTRICT, MANGAGOY, BISLIG CITY', 0, 'uploads/qrcodes/2440249.png'),
(2440283, 'CABATING', 'KHAITE PAULA', 'C', '', 'KHAITE PAULA C. CABATING', NULL, '1ST YEAR', 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ''),
(2440285, 'ANGUSAN', 'APRIL ROSE', 'P', '', 'APRIL ROSE P.    ANGUSAN', NULL, '1ST YEAR', 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ''),
(2440287, 'SALDIVAR', 'CHRISTIAN BILL', 'S', '', 'CHRISTIAN BILL S. SALDIVAR', NULL, '1ST YEAR', 'IT', '09516551918', '2005-12-05', 'chansnchz@gmail.com', 'uploads/ccs3.png', NULL, 'maribel s. saldivar', '09469797879', 'p2 mahayay a, osmena, tagbina surigao del sur', 'p1 jumpers drive, mangagoy bislig city', 0, 'uploads/qrcodes/2440287.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
