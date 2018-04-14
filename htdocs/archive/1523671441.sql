-- MySQL dump 10.13  Distrib 5.6.39, for linux-glibc2.12 (x86_64)
--
-- Host: localhost    Database: nursing
-- ------------------------------------------------------
-- Server version	5.6.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES UTF8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `sub_of` int(6) unsigned NOT NULL DEFAULT '1',
  `sequence` int(2) unsigned NOT NULL DEFAULT '1',
  `restricted` int(1) unsigned NOT NULL DEFAULT '0',
  `description` text,
  `color` varchar(30) DEFAULT NULL,
  `background` varchar(255) DEFAULT '',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Nursing Building',0,1,0,'Top Level Category',NULL,'');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `course_number` smallint(4) NOT NULL,
  `prefix` varchar(4) NOT NULL DEFAULT 'NURS',
  `title` varchar(50) NOT NULL,
  `Lead_Instructor` int(8) DEFAULT NULL,
  `semester` enum('1','2','3','4','5') NOT NULL,
  PRIMARY KEY (`course_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (1001,'NURS','(TestData) SQL Insertion in Seniors ',0,'1'),(2000,'NURS','NURSING CONCEPTS',51111111,'2'),(2002,'NURS','TRANSITIONS IN NURSING',51111111,'2'),(2004,'NURS','HEALTH ASSESSMENT',51111112,'1'),(2009,'NURS','FUND PROF NURSING PRACTICE',NULL,'1'),(2011,'NURS','INTRO GERONTOLOGICAL NURSING',NULL,'1'),(2013,'NURS','COMPUTING FOR NURSES',0,'1'),(2020,'NURS','PROFESSIONAL NURSING CONCEPTS',0,'2'),(2080,'NURS','BASIC PRIN OF PHARMACOLOGY',0,'2'),(3009,'NURS','ADULT HEALTH NURSING 1',0,'2'),(3010,'NURS','MENTAL HEALTH NURSING',0,'2'),(3011,'NURS','NURSING SYNTHESIS 1',0,'2'),(3012,'NURS','ADULT HEALTH 1 THEORY',51111111,'3'),(3013,'NURS','ADULT HEALTH 1 PRACTICUM A',0,'3'),(3014,'NURS','ADULT HEALTH 1 PRACTICUM B',0,'3'),(3028,'NURS','ADULT HEALTH NURSING 2',0,'3'),(3029,'NURS','MATERNAL CHILD HEALTH NURSING',0,'3'),(3030,'NURS','NURSING SYNTHESIS 2',0,'3'),(4000,'NURS','ADULT HEALTH NURSING 3',0,'4'),(4001,'NURS','NSG RESEARCH EBP',0,'4'),(4026,'NURS','RESEARCH',0,'4'),(4066,'NURS','NURSING MANAGEMENT',0,'5'),(4067,'NURS','PUBLIC HEALTH NURSING',0,'5'),(4076,'NURS','NURSING MANAGEMENT RN',0,'4'),(4077,'NURS','NURSING MANAGEMENT RN PRACTICUM',0,'4'),(4078,'NURS','PUBLIC HEALTH NURSING RN',0,'4'),(4079,'NURS','PUBLIC HEALTH NURSING RN PRAC',0,'4'),(5002,'NURS','RESEARCH EBP',0,'5'),(5004,'NURS','PERSONNEL & ORG MGMT',0,'5'),(5007,'NURS','ADV PHYSICAL ASSESSMENT',0,'5'),(5008,'NURS','ADVANCED PHARMACOLOGY',0,'5'),(5009,'NURS','HC ECONOMIC & FINANCE',0,'5'),(5202,'NURS','AGNP I',0,'5');
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dates`
--

DROP TABLE IF EXISTS `dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dates` (
  `event_id` int(8) unsigned DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dates`
--

LOCK TABLES `dates` WRITE;
/*!40000 ALTER TABLE `dates` DISABLE KEYS */;
/*!40000 ALTER TABLE `dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deadline`
--

DROP TABLE IF EXISTS `deadline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deadline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open` date NOT NULL,
  `close` date NOT NULL,
  `type` enum('schedule','semester') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deadline`
--

LOCK TABLES `deadline` WRITE;
/*!40000 ALTER TABLE `deadline` DISABLE KEYS */;
INSERT INTO `deadline` VALUES (1,'2017-12-18','2018-01-21','schedule'),(13,'2018-01-22','2018-05-18','semester');
/*!40000 ALTER TABLE `deadline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `attendees` int(11) NOT NULL DEFAULT '1',
  `type` enum('class','clinical','exam','event') NOT NULL DEFAULT 'class',
  `crn` int(6) NOT NULL,
  `CWID` int(8) NOT NULL,
  `room_number` varchar(32) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `series` smallint(6) NOT NULL,
  `dateStart` datetime NOT NULL,
  `dateEnd` datetime NOT NULL,
  `timeCreated` datetime NOT NULL,
  `status` enum('approved','pending','rejected','changed','resubmit') NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `crn` (`crn`),
  KEY `room_number` (`room_number`),
  CONSTRAINT `event_ibfk_1` FOREIGN KEY (`crn`) REFERENCES `section` (`crn`),
  CONSTRAINT `event_ibfk_2` FOREIGN KEY (`room_number`) REFERENCES `room` (`room_number`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (20,'Sample Event 1',1,'exam',43672,222222222,'2','This one\'s a doozy',1,'2018-03-15 13:00:00','2018-03-15 13:00:00','2018-03-03 12:02:28','approved'),(21,'test',1,'exam',43672,222222222,'2','I just want this to work so I can go to sleep',1,'2018-03-19 12:00:00','2018-03-19 14:00:00','2018-03-03 12:42:37','approved'),(22,'test',1,'event',43672,222222222,'2','None to be stated',2,'2018-03-09 12:39:00','2018-03-09 17:30:00','2018-03-03 14:41:23','approved'),(23,'LRC',1,'class',43672,222222222,'2','None to be stated',3,'2018-03-06 12:30:00','2018-03-06 16:15:00','2018-03-03 15:05:34','approved'),(24,'Test Event',45,'',43672,2,'2','None',4,'2018-03-14 13:33:00','2018-03-14 16:14:00','2018-03-06 16:22:41','approved'),(25,'test',33,'',43672,1111,'159','None',5,'2018-03-13 13:30:00','2018-03-13 14:20:00','2018-03-06 16:25:06','approved'),(26,'TBart1',12,'class',43935,1111,'101','test1',6,'2018-03-06 15:15:00','2018-03-06 16:50:00','2018-03-06 20:36:44','approved'),(27,'TBart2',50,'',43935,1111,'42','sample2',7,'2018-03-08 13:00:00','2018-03-08 14:30:00','2018-03-06 20:38:09','approved'),(28,'TBart3',50,'',43935,1111,'159','',8,'2018-03-20 13:00:00','2018-03-20 13:50:00','2018-03-06 20:44:42','approved'),(29,'TBart4',1,'class',43935,1111,'101','',9,'2018-03-21 08:00:00','2018-03-21 15:00:00','2018-03-06 20:45:30','approved'),(30,'TBart5',100,'clinical',43935,1111,'101','examTest',10,'2018-03-22 14:00:00','2018-03-22 16:00:00','2018-03-06 20:46:28','approved'),(31,'TBart7',10,'',43935,1111,'101','',11,'2018-03-23 13:00:00','2018-03-23 16:00:00','2018-03-06 20:48:30','approved'),(32,'Database Test',1,'',43672,111111111,'101','1',12,'2018-03-10 12:30:00','2018-03-10 13:30:00','2018-03-09 12:53:52','approved'),(175,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-01 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(176,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-02 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(177,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-09 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(178,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-16 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(179,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-23 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(181,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-11 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(182,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-18 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(183,'Final Repetition Test',22,'class',63665,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-25 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(184,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-01 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(185,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-02 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(186,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-09 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(187,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-16 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(188,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-23 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(189,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-04 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(190,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-11 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(191,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-18 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(192,'Final Repetition Test',22,'class',63667,911111111,'Offsite','This will hopefully be my last test, and I can go to bed happy. Will use 2 CRNs and repeat on 2 days!',24,'2018-04-25 11:11:00','2018-04-18 12:22:00','2018-03-10 20:15:38','approved'),(193,'EXA',32,'exam',43922,911111111,'ICU-243','32',25,'2018-03-20 14:22:00','2018-03-20 15:33:00','2018-03-19 16:03:19','approved'),(194,'mockSched',12,'class',60977,55555551,'218','',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','approved'),(200,'Test For Pending',55,'',63659,911111111,'218','This should work',26,'2018-04-12 15:33:00','2018-04-12 16:44:00','0000-00-00 00:00:00','approved'),(202,'Course A [1] Example',13,'class',63548,86753090,'338','This is a pre-created event to demonstrate for Gamma Demo.',28,'2018-05-01 13:00:00','2018-05-01 17:00:00','2018-04-09 20:04:24','approved'),(203,'GONNA MAKE A CONFLICT',56,'class',43922,51111111,'338','I HATE THIS! I WANT TO GO TO SLEEP. WHY? WHY!?!',29,'2018-05-01 14:00:00','2018-05-01 15:00:00','2018-04-09 21:25:48','approved'),(204,'More Confclits',2,'class',43935,51111111,'338','1221ffaaa',30,'2018-05-01 14:12:00','2018-05-01 14:23:00','2018-04-09 21:31:23','pending'),(205,'CONFLITTT',1,'exam',63481,51111111,'338','sdgaasdfa',31,'2018-05-01 14:22:00','2018-05-01 14:33:00','2018-04-09 21:32:41','pending'),(206,'CONFLITTT',1,'exam',63481,51111111,'30','1',32,'2018-05-01 14:22:00','2018-05-01 14:33:00','2018-04-09 21:33:30','pending'),(208,'NO',233,'',43672,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(209,'NO',233,'',43673,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(210,'NO',233,'',43675,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(211,'NO',233,'',43677,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(212,'NO',233,'',43933,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(213,'NO',233,'',43934,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(214,'NO',233,'',63458,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(215,'NO',233,'',63459,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(216,'NO',233,'',63460,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(217,'NO',233,'',63461,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(218,'NO',233,'',63462,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(219,'NO',233,'',63548,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(220,'NO',233,'',63668,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(221,'NO',233,'',63669,51111114,'101','Nonoe',33,'2018-04-18 15:00:00','2018-04-18 16:00:00','2018-04-10 09:40:02',''),(222,'test',324,'class',43672,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(223,'test',324,'class',43673,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(225,'test',324,'class',43677,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(226,'test',324,'class',43933,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(227,'test',324,'class',43934,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(228,'test',324,'class',63458,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(229,'test',324,'class',63459,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(230,'test',324,'class',63460,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(231,'test',324,'class',63461,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(232,'test',324,'class',63462,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(233,'test',324,'class',63548,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(234,'test',324,'class',63668,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved'),(235,'test',324,'class',63669,911111111,'101','noi',34,'2018-04-13 15:33:00','2018-04-13 16:44:00','2018-04-10 09:45:22','approved');
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `group_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `sub_of` int(6) unsigned NOT NULL DEFAULT '1',
  `sequence` int(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Nursing Building',0,1);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `link_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(50) DEFAULT NULL,
  `address1` varchar(40) DEFAULT NULL,
  `address2` varchar(40) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CWID` int(8) NOT NULL,
  `message` varchar(40) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `referred_page` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (3,30030212,'Did nothing','2018-04-13 18:23:22','no.php'),(4,30030213,'also did nothing','2018-04-13 18:23:22','/helpme.php'),(5,86753090,'Event approved: 224','2018-04-13 20:08:37','/editevent.php'),(6,86753090,'Event approved: 224','2018-04-13 20:08:54','/editevent.php'),(7,86753090,'Event approved: 224','2018-04-13 20:09:07','/editevent.php'),(8,86753090,'Event approved: 224','2018-04-13 20:09:16','/editevent.php'),(9,86753090,'Event approved: 224','2018-04-13 20:09:18','/editevent.php'),(10,86753090,'Event approved: 224','2018-04-13 20:09:18','/editevent.php'),(11,86753090,'Event approved: 224','2018-04-13 20:09:19','/editevent.php'),(12,86753090,'Event approved: 224','2018-04-13 20:09:30','/editevent.php'),(13,86753090,'Event approved: 224','2018-04-13 20:09:45','/editevent.php'),(14,86753090,'Event approved: 224','2018-04-13 20:09:47','/editevent.php'),(15,86753090,'Event approved: 224','2018-04-13 20:10:02','/editevent.php'),(16,86753090,'Event approved: 224','2018-04-13 20:10:47','/editevent.php'),(17,86753090,'Event deleted: 224','2018-04-13 20:17:00','/editevent.php'),(18,911111111,'Event series approved: ','2018-04-13 22:14:13','/showevent.php'),(19,86753090,'Event series approved: ','2018-04-14 00:23:40','/showevent.php'),(20,86753090,'Event series approved: 34','2018-04-14 00:26:07','/showevent.php'),(21,86753090,'Backup Created','2018-04-14 01:58:11','/admin/admin.php'),(22,86753090,'Backup Created','2018-04-14 02:02:36','/admin/admin.php');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UserTo` tinytext NOT NULL,
  `UserFrom` tinytext NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `status` text NOT NULL,
  `SentDate` text NOT NULL,
  `mail_id` int(80) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail`
--

LOCK TABLES `mail` WRITE;
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
INSERT INTO `mail` VALUES (0,'alyssa','admin','links','<a href=\"/index.php\">This is a link</a> and a test to see if links work in this damn thing.','unread','tomorrow',22),(0,'alyssa','admin','noHTML','this is just a simple test to see if not html works','read','long ago',20),(0,'alyssa','admin','no html','no html is included with this message!','unread','today',21);
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `module_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `active` int(1) unsigned NOT NULL DEFAULT '0',
  `sequence` int(2) unsigned NOT NULL DEFAULT '1',
  `script` varchar(60) DEFAULT NULL,
  `year` int(2) unsigned DEFAULT NULL,
  `month` int(2) unsigned DEFAULT NULL,
  `week` int(2) unsigned DEFAULT NULL,
  `day` int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'Semester','Semester',1,1,'semester.php',0,2,3,4),(2,'Month','Month',1,2,'grid.php',0,2,3,4),(3,'Week','Week',1,3,'week.php',0,2,3,4),(4,'Day','Day',1,4,'day.php',0,2,3,4);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_schedule`
--

DROP TABLE IF EXISTS `personal_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_schedule` (
  `CWID` int(8) NOT NULL,
  `crn` int(6) NOT NULL,
  PRIMARY KEY (`CWID`,`crn`),
  KEY `personal_schedule_ibfk_3` (`crn`),
  CONSTRAINT `personal_schedule_ibfk_3` FOREIGN KEY (`crn`) REFERENCES `section` (`crn`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_schedule`
--

LOCK TABLES `personal_schedule` WRITE;
/*!40000 ALTER TABLE `personal_schedule` DISABLE KEYS */;
INSERT INTO `personal_schedule` VALUES (30051263,63461),(30030212,63480),(30051263,63480),(30054130,63480),(17679601,63481),(30034221,63481),(30051263,63483),(17679601,63548),(30030212,63548),(30034221,63669),(30054130,63669);
/*!40000 ALTER TABLE `personal_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `capacity` smallint(6) DEFAULT NULL,
  `room_number` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (400,'101','Highest Room, Tallest Tower'),(314,'159','Pi'),(0,'2',' '),(0,'218','10 dead mannequin beds'),(0,'221','10 dead mannequin beds'),(NULL,'242','Smart Classroom'),(30,'30','Non-Descript'),(NULL,'320','Has 6 hospital beds.'),(NULL,'325','Has 10 hospital beds.'),(NULL,'327','Practice assessments for graduate students'),(77,'338','Smart Class Room'),(50,'340','Smart Classroom.'),(1,'42','BabyPopOutStation'),(300,'Auditorium-107A','Smart Class Room'),(NULL,'ICU-243','Five beds with live mannequin'),(74,'LRC-236','Exam Lab'),(NULL,'Nowell-215','Live birth simulator mannequin '),(32767,'Offsite','Any offsite location. Please include details in notes.');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `crn` int(6) NOT NULL,
  `course_number` smallint(4) NOT NULL,
  `Instructor` int(8) DEFAULT NULL,
  PRIMARY KEY (`crn`),
  KEY `section_ibfk_2` (`course_number`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course_number`) REFERENCES `course` (`course_number`) ON DELETE CASCADE,
  CONSTRAINT `section_ibfk_2` FOREIGN KEY (`course_number`) REFERENCES `course` (`course_number`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (43672,2009,NULL),(43673,2009,NULL),(43675,2009,NULL),(43677,2009,NULL),(43678,2011,NULL),(43679,2011,NULL),(43922,2002,NULL),(43923,2011,NULL),(43928,2004,51111114),(43929,2004,NULL),(43930,2004,NULL),(43931,2004,51111113),(43932,2004,51111111),(43933,2009,NULL),(43934,2009,NULL),(43935,1001,NULL),(44187,2004,51111112),(44195,4000,NULL),(44196,4000,NULL),(44201,4001,NULL),(60977,2000,0),(60978,2004,NULL),(60988,2080,NULL),(61752,2002,NULL),(61755,2080,NULL),(63457,2004,NULL),(63458,2009,NULL),(63459,2009,NULL),(63460,2009,NULL),(63461,2009,NULL),(63462,2009,NULL),(63464,3009,NULL),(63465,3009,NULL),(63466,3009,NULL),(63470,3010,NULL),(63473,3010,NULL),(63475,3011,NULL),(63479,2011,NULL),(63480,2011,51111117),(63481,2011,51111115),(63482,2013,NULL),(63483,2013,NULL),(63484,2013,NULL),(63485,2013,NULL),(63486,2020,NULL),(63548,2009,51111114),(63659,2004,NULL),(63660,2004,NULL),(63662,2004,NULL),(63663,2004,NULL),(63665,2004,NULL),(63667,2004,51111113),(63668,2009,NULL),(63669,2009,51111117),(63673,2011,NULL),(63676,3009,NULL),(63692,4000,NULL),(63694,4001,NULL),(64066,3010,NULL),(64067,5002,NULL),(64069,5008,NULL),(64197,3010,NULL),(64243,5202,NULL);
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `username` varchar(30) CHARACTER SET latin2 NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `hash_generated` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CWID` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `CWID` (`CWID`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','5f4dcc3b5aa765d61d8327deb882cf99','2eabb806070f3d663ec806be06149032',9,'admin1@admin.com',1519605786,0,'admin','b52a2d3d168312df857fda82dbeb632a',1523420302,1,911111111),('teacher1','5f4dcc3b5aa765d61d8327deb882cf99','d4f4a97480b03bb41dd846fc77a0971f',5,'teacher1@teacher.com',1519693251,0,'tea cher','df0ec850275c4c2ac00c7f35be354bf0',1523496050,2,51111111),('teacher2','5f4dcc3b5aa765d61d8327deb882cf99','2c7fc09dea2febd1c00a0826e6ccd7c9',5,'teacher2@teacher.com',1520654856,0,'teacher2','615ee366974312f7356abef093337e7a',1523222168,6,51111112),('sched','5f4dcc3b5aa765d61d8327deb882cf99','df04bf5b61151fbbc0a5443fb33f12da',1,'sched@sched.com',1520379929,0,'Schedule Notifications','0',0,4,1111),('teacher3','5f4dcc3b5aa765d61d8327deb882cf99','db68ea074af52b2108f0a81c56f430ea',5,'teacher3@teacher.com',1520654890,0,'teacher3','c197a438f53f268942b5671ed629e769',1523203607,7,51111113),('student1','5f4dcc3b5aa765d61d8327deb882cf99','14a00f390bafccb0e7b0a45094656956',1,'student1@student.com',1520654962,0,'student1','c9577370c3cd1ffefa7dd0d71fcd77f1',1523131925,9,11111111),('jonesms','5f4dcc3b5aa765d61d8327deb882cf99','d63977edb8e9142d7000e4ef57b0ca1a',5,'jonesms@nursing.com',1523247037,0,'Mark S. Jones','f55107d9fbf41ae5fa5936e4cff88631',1523378061,20,51111115),('pilotkp','5f4dcc3b5aa765d61d8327deb882cf99','1176dad7d78b4a31a5bb374edd464a64',5,'pilotkp@nursing.com',1523247065,0,'Keyton K. Pilot','27b226341cebaa253002fc1c65f7b426',1523378582,21,51111116),('admin2','5f4dcc3b5aa765d61d8327deb882cf99','863ae74da56e15a9e4a3000025645037',9,'admin2@admin.com',1520655232,0,'admin2','0',0,13,91111112),('doejm','5f4dcc3b5aa765d61d8327deb882cf99','e248b7a6ccbf4d513890eef17a0d356d',5,'doejm@nursing.com',1523246998,0,'Jane M. Doe','5ce6b120ad3a388000138f15d15bae79',1523329504,19,51111114),('mcduffan','5f4dcc3b5aa765d61d8327deb882cf99','9a9bb6b56dcf51c0e2fa8ae1d5dbfef0',1,'mcduffan@student.com',1521568560,0,'Alyssa McDuffan','0',0,14,30030212),('barneta','5f4dcc3b5aa765d61d8327deb882cf99','b3068373a57ea43397c341d31e664839',1,'barneta@student.com',1521568622,0,'Taylor Barneta','812de34f744086d651a0fc6eed17ee96',1523378554,15,17679601),('abierajj','5f4dcc3b5aa765d61d8327deb882cf99','6a5a83fb3ee29d59a7068d1dbe81cdda',1,'baierajj@student.com',1521568843,0,'Rajat Abierajj','0',0,16,30051263),('chapmam','5f4dcc3b5aa765d61d8327deb882cf99','ce5197146cdb128a2d6f99d9dfa9a9f7',1,'chapman@student.com',1521569028,0,'Chad Chapman','0',0,17,30034221),('cheekas','e32ccf920755b4b58782b325144db60c','6be66973170c5a6d52d5292a6da0e8ae',1,'cheekas@student.com',1521569155,0,'Aakash Cheekas','0',0,18,30054130),('smithjr','5f4dcc3b5aa765d61d8327deb882cf99','e0c90bd959fcac19cdf97e5c24d34b0f',2,'smithjr@nursing.com',1523247105,0,'Jill R. Smith','0',0,22,51111117),('alyssa','5f4dcc3b5aa765d61d8327deb882cf99','f0a84abd4f540d480ef74ebc4145e7bd',9,'nottoday@no.com',1523285982,0,'Alyssa McMurray','2031a86289484e13808b931b2e1eea5b',1523639891,23,86753090);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_to_categories`
--

DROP TABLE IF EXISTS `users_to_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_to_categories` (
  `user_id` int(6) unsigned NOT NULL DEFAULT '0',
  `category_id` int(6) unsigned NOT NULL DEFAULT '0',
  `moderate` int(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_to_categories`
--

LOCK TABLES `users_to_categories` WRITE;
/*!40000 ALTER TABLE `users_to_categories` DISABLE KEYS */;
INSERT INTO `users_to_categories` VALUES (1,1,1,1);
/*!40000 ALTER TABLE `users_to_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_to_groups`
--

DROP TABLE IF EXISTS `users_to_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_to_groups` (
  `user_id` int(6) unsigned NOT NULL DEFAULT '0',
  `group_id` int(6) unsigned NOT NULL DEFAULT '0',
  `moderate` int(1) NOT NULL DEFAULT '0',
  `subscribe` int(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_to_groups`
--

LOCK TABLES `users_to_groups` WRITE;
/*!40000 ALTER TABLE `users_to_groups` DISABLE KEYS */;
INSERT INTO `users_to_groups` VALUES (1,1,1,0,1);
/*!40000 ALTER TABLE `users_to_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-14  2:04:01
