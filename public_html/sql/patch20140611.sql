ALTER TABLE `user` ADD `facebook_id` VARCHAR(255) NOT NULL AFTER `type_user`, ADD `twitter_id` VARCHAR(255) NOT NULL AFTER `facebook_id`, ADD `google_id` VARCHAR(255) NOT NULL AFTER `twitter_id`;
