-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ext_tbl_ahistorizer_histories`
--

CREATE TABLE IF NOT EXISTS `ext_tbl_ahistorizer_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_class` varchar(256) NOT NULL,
  `model_id` int(11) NOT NULL,
  `model_attributes` mediumtext NOT NULL,
  `date_create` datetime NOT NULL,
  `user_create` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

