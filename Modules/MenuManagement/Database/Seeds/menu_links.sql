INSERT INTO `menu_links` (`id`, `name`,`default_menu`, `link`,`permission_key`,`module`, `icon`, `type`, `parent_id`, `access`, `created_at`, `updated_at`) VALUES
	(1, 'Home',1, 'dashboard','Dashboard',NULL, 'trash', 'parent-single', NULL, 'Su-Admin,Admin', NULL, NULL),
	(2, 'Manage Menu', 1,'menuLink.index','MenuLink',"MenuManagement" ,'database', 'child', 3, 'Su-Admin,Admin', '2022-10-12 02:43:42', '2022-10-12 02:43:42'),
	(3, 'App Settings',1, '#','#',NULL,'gear', 'parent', NULL, 'Su-Admin,Admin', '2022-10-12 02:43:42', '2022-10-12 02:43:42'),
	(4, 'App Information',1, 'appSetting.index','AppSetting', 'AppManagement',NULL, 'child', 3, 'Su-Admin,Admin', '2022-10-12 02:43:42', '2022-10-12 02:43:42'),
	(5, 'Manage Role', 0, 'userRole.index', 'UserRole','UserManagement', NULL, 'child', '3', 'Su-Admin,Admin', '2022-11-28 06:48:02', '2022-11-28 06:48:02'),
	(6, 'Server Side Table', 0, 'dataTable.index', 'DataTable','DataTable', 'table', 'parent-single', NULL, 'Su-Admin', '2022-11-30 08:59:18', '2022-11-30 08:59:18'),
	(7, 'Manage Employee', 0, 'employee.index', 'Employee','EmployeeManagement', 'table', 'parent-single', NULL, 'Su-Admin', '2022-11-30 08:59:18', '2022-11-30 08:59:18'),
	(8, 'Manage Event', 0, '#', '#', 'EventManagement', 'calendar-days', 'parent', NULL, 'Su-Admin,Admin', '2023-01-10 06:05:51', '2023-01-10 06:05:51'),
	(9, 'Announcement', 0, 'announcement.index', 'Announcement', 'EventManagement', 'calendar-days', 'child', '8', 'Su-Admin,Admin', '2023-01-10 05:45:29', '2023-01-10 06:05:30');

