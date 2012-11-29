--
-- table `testcase` structure
--

DROP TABLE IF EXISTS `testcase`;

CREATE TABLE IF NOT EXISTS `testcase` (
  `id` BINARY(20) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `testsuite_id` BINARY(20) NOT NULL,
  KEY (`id`, `filename`, `testsuite_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- table `testcase_result` structure
--

DROP TABLE IF EXISTS `testcase_result`;

CREATE TABLE IF NOT EXISTS `testcase_result` (
  `testcase_id` BINARY(20) NOT NULL,
  `testsuite_id` BINARY(20) NOT NULL,
  `status` varchar(100) NOT NULL,
  `output` BLOB,
  `date` int(11) NOT NULL,
  KEY (`testcase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
