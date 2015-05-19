<?php

class CommentsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('comments')->delete();
        
		\DB::table('comments')->insert(array (
			0 => 
			array (
				'id' => '5',
				'created_at' => '2015-05-12 11:23:38',
				'updated_at' => '2015-05-12 11:23:38',
				'status' => '0',
				'title' => 'fddffdfd',
				'body' => 'fdfdfddfdfd',
				'parent_id' => NULL,
				'lft' => '1',
				'rgt' => '2',
				'depth' => '0',
				'commentable_id' => '1',
				'commentable_type' => 'Plugins\\posts\\models\\Post',
				'user_id' => '1',
			),
			1 => 
			array (
				'id' => '6',
				'created_at' => '2015-05-12 11:26:37',
				'updated_at' => '2015-05-12 11:26:37',
				'status' => '0',
				'title' => 'dffdfd',
				'body' => 'fddfdffdfd',
				'parent_id' => NULL,
				'lft' => '3',
				'rgt' => '4',
				'depth' => '0',
				'commentable_id' => '1',
				'commentable_type' => 'Plugins\\posts\\models\\Post',
				'user_id' => '1',
			),
			2 => 
			array (
				'id' => '7',
				'created_at' => '2015-05-12 11:29:30',
				'updated_at' => '2015-05-12 11:29:30',
				'status' => '0',
				'title' => 'dfdffdfd',
				'body' => 'fdfdfddf',
				'parent_id' => NULL,
				'lft' => '5',
				'rgt' => '6',
				'depth' => '0',
				'commentable_id' => '1',
				'commentable_type' => 'Plugins\\posts\\models\\Post',
				'user_id' => '1',
			),
		));
	}

}
