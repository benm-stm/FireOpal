--
-- table `testcase_result` structure
--

DROP TABLE IF EXISTS `result`;

CREATE TABLE IF NOT EXISTS `testcase_result` (
  `testcase_id` BINARY(20) NOT NULL,
  `testsuite_id` BINARY(20) NOT NULL,
  `status` varchar(100) NOT NULL,
  `output` BLOB,
  `date` int(11) NOT NULL,
  KEY (`testcase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;