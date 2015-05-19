<?php

class AssetsArchiveTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('assets_archive')->delete();
        
	}

}
