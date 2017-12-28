CREATE TABLE IF NOT EXISTS `#__adalogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ADA_AUTH_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_USER_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_TOKEN_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `appkey` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `secret` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `joomla_psw` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COLLATE=utf8_hungarian_ci;

INSERT IGNORE INTO #__adalogin 
	(`id`, 
	`ADA_AUTH_URI`, 
	`ADA_USER_URI`, 
	`ADA_TOKEN_URI`, 
	`appkey`, 
	`secret`, 
	`joomla_psw`
	)
	VALUES
	(1, 
	'https://adatom.hu/ada/v1/oauth2/auth', 
	'https://adatom.hu/ada/v1/users/me', 
	'https://adatom.hu/ada/v1/oauth2/token', 
	'appkey', 
	'secret', 
	ROUND(10000000 * RAND()) + 10000000
	);

