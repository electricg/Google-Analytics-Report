/*
MySQL - 5.1.55-community : Database - response_google_analytics
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`response_google_analytics` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `response_google_analytics`;

/*Table structure for table `accesstimes` */

DROP TABLE IF EXISTS `accesstimes`;

CREATE TABLE `accesstimes` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `dayOfWeek` int(1) unsigned NOT NULL DEFAULT '0',
  `hour` int(2) unsigned NOT NULL DEFAULT '0',
  `visitors` int(11) unsigned NOT NULL DEFAULT '0',
  `visits` int(11) unsigned NOT NULL DEFAULT '0',
  `avgTimeOnSite` int(11) unsigned NOT NULL DEFAULT '0',
  `noext_visitors` int(11) unsigned NOT NULL DEFAULT '0',
  `noext_visits` int(11) unsigned NOT NULL DEFAULT '0',
  `noext_avgTimeOnSite` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`,`hour`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `browsers` */

DROP TABLE IF EXISTS `browsers`;

CREATE TABLE `browsers` (
  `year` int(4) unsigned NOT NULL DEFAULT '0',
  `month` int(2) unsigned NOT NULL DEFAULT '0',
  `browser` varchar(255) CHARACTER SET latin1 NOT NULL,
  `browserVersion` varchar(255) CHARACTER SET latin1 NOT NULL,
  `operatingSystem` varchar(255) CHARACTER SET latin1 NOT NULL,
  `operatingSystemVersion` varchar(255) CHARACTER SET latin1 NOT NULL,
  `isMobile` tinyint(1) NOT NULL DEFAULT '0',
  `visitors` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`year`,`month`,`browser`,`browserVersion`,`operatingSystem`,`operatingSystemVersion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `year` int(4) unsigned NOT NULL DEFAULT '0',
  `month` int(2) unsigned NOT NULL DEFAULT '0',
  `country` varchar(255) CHARACTER SET latin1 NOT NULL,
  `visitors` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`year`,`month`,`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `helppages` */

DROP TABLE IF EXISTS `helppages`;

CREATE TABLE `helppages` (
  `year` int(4) unsigned NOT NULL DEFAULT '0',
  `month` int(2) unsigned NOT NULL DEFAULT '0',
  `pagePath` varchar(255) CHARACTER SET latin1 NOT NULL,
  `visitors` int(11) unsigned NOT NULL DEFAULT '0',
  `pageviews` int(11) unsigned NOT NULL DEFAULT '0',
  `uniquePageviews` int(11) unsigned NOT NULL DEFAULT '0',
  `avgTimeOnPage` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`year`,`month`,`pagePath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `screens` */

DROP TABLE IF EXISTS `screens`;

CREATE TABLE `screens` (
  `year` int(4) unsigned NOT NULL DEFAULT '0',
  `month` int(2) unsigned NOT NULL DEFAULT '0',
  `width` int(5) unsigned NOT NULL DEFAULT '0',
  `height` int(5) unsigned NOT NULL DEFAULT '0',
  `visitors` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`year`,`month`,`width`,`height`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
