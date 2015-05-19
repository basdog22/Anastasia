<?php

class GroupsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('groups')->delete();
        
		\DB::table('groups')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'Root',
				'permissions' => '{"root":1,"admin":1,"editor":1,"author":1,"member":1}',
				'created_at' => '2015-05-12 08:36:43',
				'updated_at' => '2015-05-12 08:36:43',
			),
			1 => 
			array (
				'id' => '2',
				'name' => 'Administrator',
				'permissions' => '{"admin":1,"editor":1,"author":1,"member":1}',
				'created_at' => '2015-05-12 08:36:43',
				'updated_at' => '2015-05-12 08:36:43',
			),
			2 => 
			array (
				'id' => '3',
				'name' => 'Editor',
				'permissions' => '{"editor":1,"author":1,"member":1}',
				'created_at' => '2015-05-12 08:36:43',
				'updated_at' => '2015-05-12 08:36:43',
			),
			3 => 
			array (
				'id' => '4',
				'name' => 'Author',
				'permissions' => '{"author":1,"member":1}',
				'created_at' => '2015-05-12 08:36:43',
				'updated_at' => '2015-05-12 08:36:43',
			),
			4 => 
			array (
				'id' => '5',
				'name' => 'Member',
				'permissions' => '{"member":1}',
				'created_at' => '2015-05-12 08:36:43',
				'updated_at' => '2015-05-12 08:36:43',
			),
		));
	}

}
