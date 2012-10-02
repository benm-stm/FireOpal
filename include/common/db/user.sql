--
-- table `user` structure
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `familyName` varchar(100) DEFAULT NULL,
  `organisation` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `typeOrganisation` int(11) DEFAULT NULL,
  `activityFocus` varchar(100) DEFAULT NULL,
  `titleQualification` int(11) DEFAULT NULL,
  `englishLanguage` varchar(10) DEFAULT NULL,
  `sex` varchar(100) DEFAULT NULL,
  `born` varchar(100) DEFAULT NULL,
  `check1` int(11) DEFAULT '0',
  `check2` int(11) DEFAULT '0',
  `check3` int(11) DEFAULT '0',
  `check4` int(11) DEFAULT '0',
  `completeRecording` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Table `user` content
--

INSERT INTO `user` (`id`, `email`, `password`, `surname`, `familyName`, `organisation`, `login`, `country`, `city`, `photo`, `typeOrganisation`, `activityFocus`, `titleQualification`, `englishLanguage`, `sex`, `born`, `check1`, `check2`, `check3`, `check4`, `completeRecording`) VALUES
(1, 'toto@laposte.net', 'aaa', 'xxxx', 'xxxxx', 'xx', 'xxxx', 74, '1', '52830.jpg', 1, '1', 1, 'no', 'female', '1974', 1, 1, 1, 1, 1);

-- --------------------------------------------------------