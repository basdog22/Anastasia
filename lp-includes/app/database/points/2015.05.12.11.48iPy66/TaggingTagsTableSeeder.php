<?php

class TaggingTagsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tagging_tags')->delete();
        
		\DB::table('tagging_tags')->insert(array (
			0 => 
			array (
				'id' => '1',
				'slug' => 'hello',
				'name' => 'Hello',
				'suggest' => '0',
				'count' => '1',
			),
		));
	}

}
