create table `messages` (
	`id` int unsigned not null auto_increment primary key, 

	`origin_id` int unsigned not null, 
	`target_id` int unsigned not null, 
	`message` longtext not null, 

	`created_at` timestamp null, 
	`updated_at` timestamp null,

	CONSTRAINT fk_origin_id 
	foreign key(`origin_id`) references `users`(`id`) on delete cascade,
	CONSTRAINT fk_target_id 
	foreign key(`target_id`) references `users`(`id`) on delete cascade
);