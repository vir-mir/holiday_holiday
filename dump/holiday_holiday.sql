
SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `holiday_holiday`;

CREATE DATABASE `holiday_holiday`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `holiday_holiday`;

#
# Structure for the `congratulation` table : 
#

DROP TABLE IF EXISTS `congratulation`;

CREATE TABLE `congratulation` (
  `id_user` int(11) NOT NULL,
  `id_holiday` int(11) NOT NULL,
  `dd` date NOT NULL,
  PRIMARY KEY (`id_user`,`id_holiday`,`dd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `holiday` table : 
#

DROP TABLE IF EXISTS `holiday`;

CREATE TABLE `holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `sex` enum('all','m','f') NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Structure for the `user` table : 
#

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adress` text,
  `sex` enum('m','f') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sex` (`sex`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Data for the `congratulation` table  (LIMIT 0,500)
#

INSERT INTO `congratulation` (`id_user`, `id_holiday`, `dd`) VALUES 
  (1,2,'2013-01-01'),
  (2,1,'2013-03-08'),
  (2,2,'2013-01-01');
COMMIT;

#
# Data for the `holiday` table  (LIMIT 0,500)
#

INSERT INTO `holiday` (`id`, `name`, `day`, `month`, `sex`) VALUES 
  (1,'8 марта',8,3,'f'),
  (2,'Новы год',1,1,'all'),
  (3,'Рождество',7,1,'all');
COMMIT;

#
# Data for the `user` table  (LIMIT 0,500)
#

INSERT INTO `user` (`id`, `fio`, `email`, `adress`, `sex`) VALUES 
  (1,'Елена','inf@inf.ru','лялялял','f'),
  (2,'Анна','anna@ana.ru','asdasdasd','f'),
  (3,'Дмитрий','dd@mail.ru','asdasdasdasd','m'),
  (4,'Саша','asdas@mail.ru','asdasdasdasdasdasdas','f'),
  (5,'Алексей','info@vir-mir.ru','ыва\r\nsd\r\nfsd\r\nа\r\nsdf','m'),
  (6,'Алексей','info@vir-mir1.ru','asdasdasda','m'),
  (7,'Алексей','info@vir-mir2.ru','asdasdasda','m'),
  (8,'Алексей ру','info@vir-mir3.ru','вапва\r\nп\r\nва\r\nп\r\nвап','m'),
  (9,'Алексей','ya.my-alex@yandex.ru','ывафыааыва\r\nыва\r\nsd\r\nfsd','m');
COMMIT;


