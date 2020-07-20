create table `interests` (
	`id` int unsigned not null auto_increment primary key, 
	`name` varchar(20) not null,
	`user_id` int unsigned not null, 
	`created_at` timestamp null, 
	`updated_at` timestamp null,

	CONSTRAINT fk_user_id 
	foreign key(`user_id`) references `users`(`id`) on delete cascade
);