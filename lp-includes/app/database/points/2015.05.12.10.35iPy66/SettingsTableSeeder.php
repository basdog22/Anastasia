<?php

class SettingsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('settings')->delete();
        
		\DB::table('settings')->insert(array (
			0 => 
			array (
				'id' => '1',
				'namespace' => 'cms',
				'setting_name' => 'title',
				'setting_value' => 'Anastasia',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			1 => 
			array (
				'id' => '2',
				'namespace' => 'cms',
				'setting_name' => 'brand',
				'setting_value' => 'Anastasia',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			2 => 
			array (
				'id' => '3',
				'namespace' => 'cms',
				'setting_name' => 'screensaver_password',
				'setting_value' => '1234',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			3 => 
			array (
				'id' => '4',
				'namespace' => 'cms',
				'setting_name' => 'screensaver_timeout',
				'setting_value' => '1800',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			4 => 
			array (
				'id' => '5',
				'namespace' => 'cms',
				'setting_name' => 'paging',
				'setting_value' => '20',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			5 => 
			array (
				'id' => '6',
				'namespace' => 'cms',
				'setting_name' => 'main_controller',
				'setting_value' => 'FrontController',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			6 => 
			array (
				'id' => '7',
				'namespace' => 'cms',
				'setting_name' => 'main_controller_function',
				'setting_value' => 'anastasia',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			7 => 
			array (
				'id' => '8',
				'namespace' => 'cms',
				'setting_name' => 'tinymce_plugins',
				'setting_value' => 'advlist anchor autolink autosave charmap code',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
			8 => 
			array (
				'id' => '9',
				'namespace' => 'cms',
				'setting_name' => 'search_assets',
				'setting_value' => '1',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			9 => 
			array (
				'id' => '10',
				'namespace' => 'cms',
				'setting_name' => 'search_assets_metadata',
				'setting_value' => '0',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			10 => 
			array (
				'id' => '11',
				'namespace' => 'cms',
				'setting_name' => 'tagbase',
				'setting_value' => 'tag',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			11 => 
			array (
				'id' => '12',
				'namespace' => 'cms',
				'setting_name' => 'feed_items_num',
				'setting_value' => '10',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			12 => 
			array (
				'id' => '13',
				'namespace' => 'cms',
				'setting_name' => 'default_locale',
				'setting_value' => 'en',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			13 => 
			array (
				'id' => '14',
				'namespace' => 'cms',
				'setting_name' => 'allow_comments',
				'setting_value' => '1',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			14 => 
			array (
				'id' => '15',
				'namespace' => 'cms',
				'setting_name' => 'auto_approve_comments',
				'setting_value' => '0',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			15 => 
			array (
				'id' => '16',
				'namespace' => 'cms',
				'setting_name' => 'page_cache_time',
				'setting_value' => '60',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			16 => 
			array (
				'id' => '17',
				'namespace' => 'cms',
				'setting_name' => 'max_upload_file_size',
				'setting_value' => '2',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
			17 => 
			array (
				'id' => '18',
				'namespace' => 'cms',
				'setting_name' => 'admin_email',
				'setting_value' => 'admin@example.org',
				'autoload' => '1',
				'created_at' => '2015-05-12 08:36:39',
				'updated_at' => '2015-05-12 08:36:39',
			),
		));
	}

}
