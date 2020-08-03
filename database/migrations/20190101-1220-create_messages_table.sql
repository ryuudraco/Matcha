create table `messages` (
	`id` int unsigned not null auto_increment primary key, 

	`origin_id` int unsigned not null, 
	`target_id` int unsigned not null, 
	`message` longtext not null, 
	`thread_id` int unsigned not null,
	`new` boolean not null default 1,

	`created_at` timestamp null, 
	`updated_at` timestamp null
);