<?php

class MenusTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('menus')->delete();
        
		\DB::table('menus')->insert(array (
			0 => 
			array (
				'id' => '9',
				'_lft' => '1',
				'_rgt' => '10',
				'parent_id' => NULL,
				'position' => 'main_menu',
				'name' => 'main',
				'title' => 'Main frontend menu',
				'model' => '',
				'sort' => '0',
				'url' => '',
				'link_text' => '',
				'link_target' => '_self',
				'link_attr' => '',
				'link_css' => '',
				'link_class' => '',
				'created_at' => '2015-04-19 07:07:39',
				'updated_at' => '2015-04-21 12:02:33',
			),
			1 => 
			array (
				'id' => '17',
				'_lft' => '2',
				'_rgt' => '3',
				'parent_id' => '9',
				'position' => '',
				'name' => 'home',
				'title' => 'Home',
				'model' => '',
				'sort' => '0',
				'url' => 'home_url',
				'link_text' => 'Home',
				'link_target' => '_self',
				'link_attr' => '',
				'link_css' => '',
				'link_class' => '',
				'created_at' => '2015-04-27 12:43:12',
				'updated_at' => '2015-04-27 12:43:12',
			),
			2 => 
			array (
				'id' => '18',
				'_lft' => '4',
				'_rgt' => '5',
				'parent_id' => '9',
				'position' => '',
				'name' => 'about',
				'title' => 'About',
				'model' => '',
				'sort' => '0',
				'url' => '/about',
				'link_text' => 'About Anastasia',
				'link_target' => '_self',
				'link_attr' => '',
				'link_css' => '',
				'link_class' => '',
				'created_at' => '2015-04-27 12:43:50',
				'updated_at' => '2015-04-27 12:43:50',
			),
			3 => 
			array (
				'id' => '19',
				'_lft' => '6',
				'_rgt' => '7',
				'parent_id' => '9',
				'position' => '',
				'name' => 'blog',
				'title' => 'Blog',
				'model' => '\\Plugins\\posts\\models\\Post',
				'sort' => '0',
				'url' => '/blog',
				'link_text' => 'Blog',
				'link_target' => '_self',
				'link_attr' => '',
				'link_css' => '',
				'link_class' => '',
				'created_at' => '2015-04-27 12:44:24',
				'updated_at' => '2015-04-27 12:44:24',
			),
			4 => 
			array (
				'id' => '20',
				'_lft' => '8',
				'_rgt' => '9',
				'parent_id' => '9',
				'position' => '',
				'name' => 'anastasia',
				'title' => 'Anastasia Link',
				'model' => '',
				'sort' => '0',
				'url' => 'http://www.anastasia-app.com',
				'link_text' => 'Anastasia APP',
				'link_target' => '_blank',
				'link_attr' => '',
				'link_css' => '',
				'link_class' => '',
				'created_at' => '2015-04-27 12:45:52',
				'updated_at' => '2015-04-27 12:46:11',
			),
		));
	}

}
