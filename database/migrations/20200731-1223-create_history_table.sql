create table `history` (
	`id` int unsigned not null auto_increment primary key, 

	`origin_id` int unsigned not null, 
	`target_id` int unsigned not null, 
	`action` varchar(10) not null,
	`status` boolean not null default 0,

	`created_at` timestamp default current_timestamp, 
	`updated_at` timestamp default current_timestamp
)