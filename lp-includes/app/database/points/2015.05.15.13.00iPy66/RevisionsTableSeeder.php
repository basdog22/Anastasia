<?php

class RevisionsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('revisions')->delete();
        
		\DB::table('revisions')->insert(array (
			0 => 
			array (
				'id' => '1',
				'revisionable_type' => 'Page',
				'revisionable_id' => '2',
				'user_id' => '1',
				'key' => 'content',
			'old_value' => '<p>Anastasia Publishing Platform (aka APP) is a publishing platform and cms built with Laravel.</p>
<p>This is a sample page. You can edit it and put your own content here or delete it</p>',
			'new_value' => '<p>Anastasia Publishing Platform (aka APP) is a publishing platform and cms built with Laravel.</p>
<p>This is a sample page. You can edit it and put your own content here or delete it</p>',
				'created_at' => '2015-05-12 10:31:50',
				'updated_at' => '2015-05-12 10:31:50',
			),
			1 => 
			array (
				'id' => '2',
				'revisionable_type' => 'Page',
				'revisionable_id' => '2',
				'user_id' => '1',
				'key' => 'content',
			'old_value' => '<p>Anastasia Publishing Platform (aka APP) is a publishing platform and cms built with Laravel.</p>
<p>This is a sample page. You can edit it and put your own content here or delete it</p>',
				'new_value' => 'This is a sample page. You can edit it and put your own content here or delete it



[gallery dir="" width="120"]',
				'created_at' => '2015-05-15 12:09:59',
				'updated_at' => '2015-05-15 12:09:59',
			),
		));
	}

}
