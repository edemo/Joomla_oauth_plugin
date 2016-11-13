INSERT INTO j_extensions
(`extension_id`,`name`,`type`,`element`,`folder`,`client_id`,`enabled`,`access`,
 `protected`,`manifest_cache`,`params`,`custom_data`,`system_data`,`checked_out`,
 `checked_out_time`,`ordering`,`state`) 
VALUES 
(0,"Adalogin","component","com_adalogin","",1,1,0,0,
"{\"name\":\"Adalogin\",\"type\":\"component\",\"creationDate\":\"2016-11-08\",\"author\":\"Fogler Tibor\",\"copyright\":\"Copyright (C) 2016 Fogler Tibor Open Source Matters. All rights reserved.\",\"authorEmail\":\"tibor.fogler@gmail.com\",\"authorUrl\":\"http:\\/\\/adatmagus.hu\",\"version\":\"4.00\",\"description\":\"login into joomla use adataom.hu user authorization service.\",\"group\":\"\",\"filename\":\"com_adalogin\"}",
"{}","","",0,"0000-00-00 00:00:00",0,0);

CREATE TABLE IF NOT EXISTS j_adalogin (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ADA_AUTH_URI` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_USER_URI` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_TOKEN_URI` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  `appkey` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  `secret` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  `joomla_psw` VARCHAR(128) COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT IGNORE INTO j_adalogin 
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
	"https://adatom.hu/ada/v1/oauth2/auth", 
	"https://adatom.hu/ada/v1/users/me", 
	"https://adatom.hu/ada/v1/oauth2/token", 
	"appkey", 
	"secret", 
	ROUND(10000000 * RAND()) + 10000000
	); 
');
