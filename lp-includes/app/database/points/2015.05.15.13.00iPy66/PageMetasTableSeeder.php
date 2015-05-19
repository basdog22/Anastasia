<?php

class PageMetasTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('page_metas')->delete();
        
	}

}
