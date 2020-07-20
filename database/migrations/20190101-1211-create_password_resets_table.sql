create table `password_resets` (
	`email` varchar(255) not null, 
	`token` varchar(255) not null, 
	`created_at` timestamp null
);

alter table `password_resets` add index `password_resets_email_index`(`email`);