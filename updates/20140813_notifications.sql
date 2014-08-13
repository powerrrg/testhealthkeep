TRUNCATE `notifications`;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'likepost', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'likecomment', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'follower', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'newsletter', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'newuser', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'newpost', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'comcom', id_profile FROM profile;

INSERT INTO notifications 
	(type_not, id_profile_not) 
SELECT 'post4user', id_profile FROM profile;
