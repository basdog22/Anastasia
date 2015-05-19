<?php

class PagesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('pages')->delete();
        
		\DB::table('pages')->insert(array (
			0 => 
			array (
				'id' => '1',
				'slug' => '404-page',
				'layout' => NULL,
				'title' => '404 Error',
				'content' => '<p>The content you are looking for is not here anymore.</p>',
				'status' => '1',
				'created_at' => '2015-04-20 09:00:15',
				'updated_at' => '2015-04-27 12:38:17',
			),
			1 => 
			array (
				'id' => '2',
				'slug' => 'about',
				'layout' => NULL,
				'title' => 'About Anastasia',
			'content' => '<p>Anastasia Publishing Platform (aka APP) is a publishing platform and cms built with Laravel.</p>
<p>This is a sample page. You can edit it and put your own content here or delete it</p>',
				'status' => '1',
				'created_at' => '2015-04-27 12:59:42',
				'updated_at' => '2015-05-12 10:31:50',
			),
		));
	}

}
