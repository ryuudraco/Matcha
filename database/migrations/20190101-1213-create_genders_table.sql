create table `genders` (
	`id` int unsigned not null auto_increment primary key, 

	`name` varchar(255) not null, 
	
	`created_at` timestamp null, 
	`updated_at` timestamp null
);