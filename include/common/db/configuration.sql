﻿CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(128),
  `value` varchar(256),
  `Description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;