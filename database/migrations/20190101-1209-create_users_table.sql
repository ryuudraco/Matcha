 create table `users` (
 	`id` int unsigned not null auto_increment primary key, 

 	`username` varchar(255) not null, 
 	`email` varchar(255) not null, 
 	`password` varchar(255) not null, 

 	`verify_token` varchar(32) null, 
 	`verify_at` datetime null,

 	`first_name` varchar(255) not null, 
 	`last_name` varchar(255) not null, 
 	`interests` varchar(255) null, 
 	`biography` longtext null, 
 	`preference` varchar(255) null, 
 	`age` int unsigned null, 

 	`ip_address` varchar(255) not null, 
 	`city` varchar(255) null, 
 	`province` varchar(255) null, 
 	`country` varchar(255) null, 
 	`postal_code` varchar(255) null,

	`fame_rating` int unsigned not null default 1,

 	`avatar_image` varchar(255) null, 
 	`gender_id` int unsigned null, 

 	`created_at` timestamp null, 
 	`updated_at` timestamp null
);

alter table `users` add unique `users_email_unique`(`email`);
alter table `users` add unique `users_username_unique`(`username`);