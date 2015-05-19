<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->delete();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => '1',
				'username' => 'admin',
				'firstname' => '',
				'lastname' => '',
				'email' => 'basdog22@gmail.com',
				'password' => '$2y$10$6tW2DTzs1t8Zd9CFRwfGBewsB7SFKFaKktVgsq0sTP2pm457qCsZi',
				'permissions' => NULL,
				'activated' => '0',
				'activation_code' => NULL,
				'activated_at' => NULL,
				'last_login' => '2015-05-12 09:27:37',
				'persist_code' => NULL,
				'remember_token' => 'Il51oQQw0buTNV3Fm9jgsgaoJGVW6PNkw0W6jGxdfuPCRQscVsMeyTJO8RBp',
				'reset_password_code' => NULL,
				'first_name' => NULL,
				'last_name' => NULL,
				'created_at' => '2015-05-12 08:36:38',
				'updated_at' => '2015-05-12 09:27:37',
			),
		));
	}

}
