INSERT INTO `menus` (`id`, `menu_list`, `created_at`, `updated_at`) VALUES
	(1, '[{"access":"Su-Admin,Admin","link":"dashboard","icon":"house","name":"Home","id":1,"type":"parent-single","default_menu":true},{"access":"Su-Admin,Admin","link":"#","icon":"gear","name":"App Settings","id":3,"type":"parent","default_menu":true,"children":[{"parent_id":3,"access":"Su-Admin,Admin","link":"menuLink.index","icon":"gear","name":"Manage Menu","id":2,"type":"child","default_menu":true},{"id":4,"link":"appSetting.index","name":"App Information","icon":null,"type":"child","access":"Su-Admin,Admin","parent_id":3,"default_menu":true},{"id":5,"link":"userRole.index","name":"Manage Role","icon":null,"type":"child","access":"Su-Admin,Admin","parent_id":3,"default_menu":false}]}]', NULL, '2022-10-12 02:46:00');
