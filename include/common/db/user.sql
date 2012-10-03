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
  `completeRecording` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Table `user` content
--

INSERT INTO `user` (`id`, `email`, `password`, `surname`, `familyName`, `organisation`, `login`, `completeRecording`) VALUES
(1, 'toto@laposte.net', 'aaa', 'xxxx', 'xxxxx', 'xx', 'xxxx', 1);

-------------------------------------------------------------------