<?php

class PostMetasTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('post_metas')->delete();
        
	}

}
