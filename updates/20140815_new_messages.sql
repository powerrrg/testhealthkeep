DROP TABLE `new_message`;
CREATE TABLE `new_message` (
  `message_id`		int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id`	int(11) NOT NULL,
  `from_user_id`	int(11) NOT NULL,
  `to_user_id`		int(11) NOT NULL,
  `message`			text,
  `is_read`			tinyint(1) NOT NULL,
  `is_deleted`		tinyint(1) NOT NULL,
  `timestamp`		timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `image`			varchar(128),
  PRIMARY KEY (`message_id`)
);
