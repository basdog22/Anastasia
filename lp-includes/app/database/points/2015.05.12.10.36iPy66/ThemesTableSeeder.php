<?php

class ThemesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('themes')->delete();
        
		\DB::table('themes')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'anastasia',
				'title' => 'Anastasia Default Theme',
				'image' => 'http://laravel.com/assets/img/laravel-logo.png',
				'version' => '1.0.0',
				'author' => 'basdog22',
				'url' => 'http://www.bonweb.gr',
				'installed' => '1',
				'active' => '1',
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 08:36:38',
			),
		));
	}

}
