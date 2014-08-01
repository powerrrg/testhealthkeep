CREATE TABLE IF NOT EXISTS `inoppriate` (
  `id_inop` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_inop` int(11) NOT NULL,
  `post_id_inop` int(11) NOT NULL,
  `comment_id_inop` int(11) NOT NULL,
  PRIMARY KEY (`id_inop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

