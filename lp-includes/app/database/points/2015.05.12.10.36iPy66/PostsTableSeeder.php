<?php

class PostsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('posts')->delete();
        
		\DB::table('posts')->insert(array (
			0 => 
			array (
				'id' => '1',
				'category_id' => '1',
				'slug' => 'hello-world',
				'title' => 'Hello World',
				'content' => '<p>Hello and welcome to Anastasia!</p>
<p>This is your first post and you can manage it from within the administration panel.</p>',
				'image_id' => '1',
				'user_id' => '1',
				'status' => '1',
				'created_at' => '2015-04-22 12:21:37',
				'updated_at' => '2015-04-29 04:45:56',
			),
		));
	}

}
