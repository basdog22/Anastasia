<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {

		$this->call('AssetsArchiveTableSeeder');
		$this->call('AssetsAudioTableSeeder');
		$this->call('AssetsImagesTableSeeder');
		$this->call('AssetsMiscfilesTableSeeder');
		$this->call('AssetsVideosTableSeeder');
		$this->call('BlocksTableSeeder');
		$this->call('CacheTableSeeder');
		$this->call('CommentsTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('MenusTableSeeder');
		$this->call('MetasTableSeeder');
		$this->call('PagesTableSeeder');
		$this->call('PageMetasTableSeeder');
		$this->call('PluginsTableSeeder');
		$this->call('PostsTableSeeder');
		$this->call('PostMetasTableSeeder');
		$this->call('RevisionsTableSeeder');
		$this->call('SessionsTableSeeder');
		$this->call('SettingsTableSeeder');
		$this->call('TaggingTaggedTableSeeder');
		$this->call('TaggingTagsTableSeeder');
		$this->call('TasksTableSeeder');
		$this->call('TaxonomiesTableSeeder');
		$this->call('ThemesTableSeeder');
		$this->call('ThrottleTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('UsersGroupsTableSeeder');
		$this->call('UserMetasTableSeeder');
	}

}



