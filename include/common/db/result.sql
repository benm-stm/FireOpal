--
-- table `result` structure
--

DROP TABLE IF EXISTS `result`;

CREATE TABLE IF NOT EXISTS `result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `output` BLOB,
  `testsuite` BLOB,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;