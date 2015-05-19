<?php

class TaggingTaggedTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tagging_tagged')->delete();
        
		\DB::table('tagging_tagged')->insert(array (
			0 => 
			array (
				'id' => '1',
				'taggable_id' => '1',
				'taggable_type' => 'Plugins\\posts\\models\\Post',
				'tag_name' => 'Hello',
				'tag_slug' => 'hello',
			),
		));
	}

}
