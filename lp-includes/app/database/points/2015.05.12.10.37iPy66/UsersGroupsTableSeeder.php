<?php

class UsersGroupsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users_groups')->delete();
        
		\DB::table('users_groups')->insert(array (
			0 => 
			array (
				'user_id' => '1',
				'group_id' => '1',
			),
		));
	}

}
