CREATE TABLE `post_complaint` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `post_user_UNIQUE` (`post_id` ASC, `user_id` ASC));

CREATE TABLE `post_comment_complaint` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `comment_user_UNIQUE` (`comment_id` ASC, `user_id` ASC));