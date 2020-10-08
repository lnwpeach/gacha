CREATE TABLE `code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(16) NOT NULL,
  `value` int(5) unsigned NOT NULL,
  `expire_dt` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = wait / 1 = used',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;