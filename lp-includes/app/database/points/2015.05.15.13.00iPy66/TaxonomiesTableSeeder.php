<?php

class TaxonomiesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('taxonomies')->delete();
        
		\DB::table('taxonomies')->insert(array (
			0 => 
			array (
				'id' => '1',
				'_lft' => '1',
				'_rgt' => '2',
				'parent_id' => NULL,
				'sort' => '0',
				'name' => 'general',
				'title' => 'General',
				'active' => '0',
				'created_at' => '2015-04-27 12:34:28',
				'updated_at' => '2015-04-27 12:34:28',
			),
		));
	}

}
