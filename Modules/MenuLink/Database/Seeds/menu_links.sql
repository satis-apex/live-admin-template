INSERT INTO `menu_links` (`id`, `name`,`movable`, `link`,`permission_key`, `icon`, `type`, `parent_id`, `access`, `created_at`, `updated_at`) VALUES
	(1, 'Home',0, 'dashboard','Dashboard', 'trash', 'parent-single', NULL, 'Su-Admin,Admin', NULL, NULL),
	(2, 'Manage Menu', 0,'menulink.index','MenuLink', 'database', 'child', 3, 'Su-Admin,Admin', '2022-10-12 02:43:42', '2022-10-12 02:43:42'),
	(3, 'App Settings',0, '#','#', 'gear', 'parent', NULL, 'Su-Admin,Admin', '2022-10-12 02:43:42', '2022-10-12 02:43:42');