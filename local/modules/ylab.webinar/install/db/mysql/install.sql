CREATE TABLE `b_ylab_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `NAME` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL,
  `BIRTHDAY` date NOT NULL,
  `PHONE` varchar(12) COLLATE 'utf8_unicode_ci' NOT NULL,
  `CITY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `b_ylab_users`
  ADD KEY `CITY` (`CITY`);

CREATE TABLE `b_ylab_city` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `CITY` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `b_ylab_users`
  ADD FOREIGN KEY (`CITY`)
  REFERENCES `b_ylab_city`(`ID`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

INSERT INTO `b_ylab_city` (`ID`, `CITY`) VALUES
(1, 'Москва'),
(2, 'Санкт-Петербург'),
(3, 'Казань');

INSERT INTO `b_ylab_users` (`ID`, `NAME`, `BIRTHDAY`, `PHONE`, `CITY`) VALUES
(1, 'Иван', '1980-10-10', '+78001234567', 1),
(2, 'Петр', '1981-11-11', '+78009999999', 2),
(3, 'Евгений', '1982-12-12', '+79990001000', 3);
