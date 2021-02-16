
CREATE TABLE `market_data`(
  id_value varchar(20) NOT NULL,
  price varchar(10) NOT NULL,
  is_noon  ENUM("N", "S", "O", "Y") DEFAULT "N",
  update_date DATETIME
);
ALTER TABLE `market_data` CHANGE `update_date` `update_date` DATE NULL DEFAULT NULL;
