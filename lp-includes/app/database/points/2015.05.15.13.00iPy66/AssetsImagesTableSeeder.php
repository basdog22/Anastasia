<?php

class AssetsImagesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('assets_images')->delete();
        
		\DB::table('assets_images')->insert(array (
			0 => 
			array (
				'id' => '1',
				'title' => 'mobile-web-design-techniques.png',
				'description' => 'mobile-web-design-techniques.png',
				'filename' => 'mobile-web-design-techniques.png',
				'path' => 'images/mobile-web-design-techniques.png',
				'original_filename' => 'mobile-web-design-techniques.png',
				'type' => 'image/png',
				'filesize' => '128304',
				'width' => '1024',
				'height' => '450',
				'ratio' => '1.00',
				'sizes' => '{"original":"http:\\/\\/anastasia-app.com\\/lp-content\\/files\\/images\\/mobile-web-design-techniques.png","300":"http:\\/\\/anastasia-app.com\\/lp-content\\/files\\/images\\/2015\\/04\\/29\\/300\\/mobile-web-design-techniques.png","200":"http:\\/\\/anastasia-app.com\\/lp-content\\/files\\/images\\/2015\\/04\\/29\\/200\\/mobile-web-design-techniques.png","120":"http:\\/\\/devhost.dev\\/LaraPress\\/en\\/lp-content\\/files\\/images\\/2015\\/05\\/15\\/120\\/mobile-web-design-techniques.png"}',
				'full_data' => 'YToxMjp7czoxNDoiR0VUSUQzX1ZFUlNJT04iO3M6MTQ6IjEuOS45LTIwMTQxMTIxIjtzOjg6ImZpbGVzaXplIjtpOjEyODMwNDtzOjg6ImZpbGVwYXRoIjtzOjQ0OiIvaG9tZS9xb29sL0xhcmFQcmVzcy9scC1jb250ZW50L2ZpbGVzL2ltYWdlcyI7czo4OiJmaWxlbmFtZSI7czozMjoibW9iaWxlLXdlYi1kZXNpZ24tdGVjaG5pcXVlcy5wbmciO3M6MTI6ImZpbGVuYW1lcGF0aCI7czo3NzoiL2hvbWUvcW9vbC9MYXJhUHJlc3MvbHAtY29udGVudC9maWxlcy9pbWFnZXMvbW9iaWxlLXdlYi1kZXNpZ24tdGVjaG5pcXVlcy5wbmciO3M6MTI6ImF2ZGF0YW9mZnNldCI7aTowO3M6OToiYXZkYXRhZW5kIjtpOjEyODMwNDtzOjEwOiJmaWxlZm9ybWF0IjtzOjM6InBuZyI7czo1OiJ2aWRlbyI7YTo2OntzOjEwOiJkYXRhZm9ybWF0IjtzOjM6InBuZyI7czo4OiJsb3NzbGVzcyI7YjowO3M6MTI6InJlc29sdXRpb25feCI7aToxMDI0O3M6MTI6InJlc29sdXRpb25feSI7aTo0NTA7czoxNToiYml0c19wZXJfc2FtcGxlIjtpOjg7czoxNzoiY29tcHJlc3Npb25fcmF0aW8iO2Q6MC4yNzg0Mzc1O31zOjg6ImVuY29kaW5nIjtzOjU6IlVURi04IjtzOjk6Im1pbWVfdHlwZSI7czo5OiJpbWFnZS9wbmciO3M6MzoicG5nIjthOjU6e3M6NDoiSUhEUiI7YTo2OntzOjY6ImhlYWRlciI7YTo2OntzOjExOiJkYXRhX2xlbmd0aCI7aToxMztzOjk6InR5cGVfdGV4dCI7czo0OiJJSERSIjtzOjg6InR5cGVfcmF3IjtpOjEyMjk0NzI4NTA7czo0OiJkYXRhIjtzOjEzOiIAAAQAAAABwggDAAAAIjtzOjM6ImNyYyI7aToxMTg4Nzc2ODczO3M6NToiZmxhZ3MiO2E6NDp7czoxMDoiYW5jaWxsaWFyeSI7YjowO3M6NzoicHJpdmF0ZSI7YjowO3M6ODoicmVzZXJ2ZWQiO2I6MDtzOjEyOiJzYWZlX3RvX2NvcHkiO2I6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OjMwO2k6NDIzNjkwNjtpOjMxO2k6MjA1OTE0OTtpOjMyO2k6ODg5Njg3NTtpOjMzO2k6Njk4MjMwNDtpOjM0O2k6MTU3MTU1MTU7aTozNTtpOjIwNjA2OTY7aTozNjtpOjg2OTc3MDY7aTozNztpOjEyODM1NTA5O2k6Mzg7aToxMjYzNzQxNztpOjM5O2k6Mzg0MDkwMztpOjQwO2k6MjEyNjUwNTtpOjQxO2k6NjkwOTU1MztpOjQyO2k6ODQzOTQ5ODtpOjQzO2k6MTA5MjM0NDE7aTo0NDtpOjM2MzcxNjA7aTo0NTtpOjEzMDE3OTIxO2k6NDY7aTo1NjgxNTA4O2k6NDc7aToxNTQzNzE2NztpOjQ4O2k6MTc5NTcwNDtpOjQ5O2k6NDk2MDA5MjtpOjUwO2k6ODI0MjAyODtpOjUxO2k6MTI0MjI0NTc7aTo1MjtpOjE1MzU4NTI1O2k6NTM7aToxMDIwNTg5OTtpOjU0O2k6NTQ2Mjg4MDtpOjU1O2k6ODQzNzkzMDtpOjU2O2k6NjMzNzM5MDtpOjU3O2k6MTQ5MzY4MTc7aTo1ODtpOjYzNDE1NzY7aTo1OTtpOjI5MTM5NjM7aTo2MDtpOjE0MDcwMDkzO2k6NjE7aToxNDAyNzI3O2k6NjI7aToxNTc3NzE4OTtpOjYzO2k6MTk3ODE2MztpOjY0O2k6MTk5OTAwODtpOjY1O2k6MjMwMTcyODtpOjY2O2k6MTA2NTQwNTQ7aTo2NztpOjIwNjA5NjU7aTo2ODtpOjE0NzI2NzIwO2k6Njk7aToxMzIxNjg1ODtpOjcwO2k6NjE4NjA4OTtpOjcxO2k6MTE3MTI0NDE7aTo3MjtpOjY4NTg5NDM7aTo3MztpOjI1NzAwNDQ7aTo3NDtpOjMzNzI0NTQ7aTo3NTtpOjY4NjIyMTU7aTo3NjtpOjE1MzE0NDc2O2k6Nzc7aTo1ODY5MjQ0O2k6Nzg7aTo1MDMwNTk5O2k6Nzk7aToxMDQ3MjkxMztpOjgwO2k6MTA3OTgzMDE7aTo4MTtpOjg0MzgzODc7aTo4MjtpOjQ2ODUyMjc7aTo4MztpOjExNzY4OTE0O2k6ODQ7aTo5MDg5MjI1O2k6ODU7aTo2MjcxMzgzO2k6ODY7aToxMzQ3MjgxMTtpOjg3O2k6MTIxMTQ4Nzg7aTo4ODtpOjE2NzY0MTk7aTo4OTtpOjE1NjI2ODMzO2k6OTA7aTo1NzQzMjkxO2k6OTE7aToxMjMwMzgwNztpOjkyO2k6MTM3NTM4MzM7aTo5MztpOjY3ODI1OTc7aTo5NDtpOjEwODc2MjQ7aTo5NTtpOjExODUxNjk2O2k6OTY7aTo0ODgzNjM4O2k6OTc7aToxMjMxMjAxMztpOjk4O2k6OTIxMjA0NTtpOjk5O2k6MTQxMzIyNjk7aToxMDA7aToxMTY1Mzg0ODtpOjEwMTtpOjcxODIyNzM7aToxMDI7aToxMTU4OTI4MjtpOjEwMztpOjM5NTIyMTI7aToxMDQ7aTozNzY1NjY5O2k6MTA1O2k6ODAyMzQyODtpOjEwNjtpOjE1NTE0Njc0O2k6MTA3O2k6MTMyMjY5Njc7aToxMDg7aToxMjM2MzExMTtpOjEwOTtpOjEwMDA4NTMxO2k6MTEwO2k6MTA1MzQzNTk7aToxMTE7aTo2Mzg3MDkzO2k6MTEyO2k6MTUwNTQxMDk7aToxMTM7aToxMjIxMDUwNjtpOjExNDtpOjMxNzE3MjM7aToxMTU7aTo0MzQ4MjY1O2k6MTE2O2k6MjA2NTI3MjtpOjExNztpOjEwMjAwMjMzO2k6MTE4O2k6MjEyNjc2MjtpOjExOTtpOjE1NDM4MjY7aToxMjA7aToxMTY0OTk5OTtpOjEyMTtpOjEzMDkyNTQ5O2k6MTIyO2k6Mzk2MzgyMTtpOjEyMztpOjg4NzQ2MTg7aToxMjQ7aToxNTkwODYzNztpOjEyNTtpOjcxMTEwNDk7aToxMjY7aTo0MTUzMjA3O2k6MTI3O2k6ODg4NjQyODtpOjEyODtpOjI0NTM5MjU7aToxMjk7aToxMDcyNzI4O2k6MTMwO2k6MTIzNzM3MjM7aToxMzE7aTo3MTA2NzAxO2k6MTMyO2k6NTk5MjgyNDtpOjEzMztpOjEyNjAyNjkyO2k6MTM0O2k6OTc5MDgyOTtpOjEzNTtpOjMzMDA0NzM7aToxMzY7aTo1OTk2OTU4O2k6MTM3O2k6OTg2ODE1NDtpOjEzODtpOjExMTE2MTQ4O2k6MTM5O2k6MTMwOTg5NzM7aToxNDA7aToyOTEzMTkwO2k6MTQxO2k6NTUzMjI3MjtpOjE0MjtpOjMyOTgxNTI7aToxNDM7aTozMjMwMjk3O2k6MTQ0O2k6NzI0MzE1MztpOjE0NTtpOjU2MDA0MTA7aToxNDY7aToyNzE1Mjk1O2k6MTQ3O2k6MTYzNjg2NTg7aToxNDg7aToxMjUwOTY2MTtpOjE0OTtpOjcxOTI1MDg7aToxNTA7aTozNjk2MDA2O2k6MTUxO2k6NDY3NTY4MDtpOjE1MjtpOjUwNzEyMDg7aToxNTM7aTo4MTY5NjY5O2k6MTU0O2k6MTAyNzYyMzg7aToxNTU7aToxODYyNTYwO2k6MTU2O2k6NjMyMjMwMjtpOjE1NztpOjgwMzExMjE7aToxNTg7aToxMDgzNzU5OTtpOjE1OTtpOjk0ODgyNTM7aToxNjA7aToxMTYyMjIyNztpOjE2MTtpOjQzNTU3NDY7aToxNjI7aTo4NjIwNDIwO2k6MTYzO2k6MzEwNzk5MTtpOjE2NDtpOjIyNTc4MzI7aToxNjU7aTo0NDEyNTA3O2k6MTY2O2k6MTQxNDcwMzY7aToxNjc7aTo1MDc3NDA5O2k6MTY4O2k6MTM2ODc3NzI7aToxNjk7aToxMzE1ODM0MztpOjE3MDtpOjQxNjEyMDQ7aToxNzE7aToxMzM1NTcyMjtpOjE3MjtpOjM3MDE5Mzg7aToxNzM7aTo0Mjc5NjM0O2k6MTc0O2k6MTQxNDUyMzg7aToxNzU7aTo0MDE2NzE5O2k6MTc2O2k6NTU1MjU2MztpOjE3NztpOjEzOTQ3NjAyO2k6MTc4O2k6MzY4ODAxMjtpOjE3OTtpOjE1NjU4NDc3O2k6MTgwO2k6NTQ4NjI1NDtpOjE4MTtpOjE0NDA4NDA5O2k6MTgyO2k6NTQ4NjUxMjtpOjE4MztpOjUzNTM2MzE7aToxODQ7aToxMzYxOTE1MDtpOjE4NTtpOjE1NTI2ODkxO2k6MTg2O2k6MTI5NjA5NjQ7aToxODc7aTozMzU5NTYyO2k6MTg4O2k6MTU3OTAwNjQ7aToxODk7aToxMDAwMDc5MztpOjE5MDtpOjE0NjA1Nzg4O2k6MTkxO2k6MTUzOTUwNDg7aToxOTI7aToxNjM4MjQ1NztpOjE5MztpOjMxNjIxODI7aToxOTQ7aToxNjY0NTg4NjtpOjE5NTtpOjUzNTM4OTA7aToxOTY7aToxNjI1MDYxNDtpOjE5NztpOjE0NjA2MzA0O2k6MTk4O2k6MTUwMDAyOTA7aToxOTk7aToxNjUxNDA0MztpOjIwMDtpOjMzNjkwNTY7aToyMDE7aTo1NDIwNDYwO2k6MjAyO2k6MTQ4MDMxNjg7aToyMDM7aTo5MjI2NjA2O2k6MjA0O2k6MTY3MDc0MTg7aToyMDU7aTo1NDIwMjAxO2k6MjA2O2k6NTQxOTY4NTtpOjIwNztpOjE1OTIxOTA2O2k6MjA4O2k6MTQyNjUxMjM7aToyMDk7aToxMjM1NDYwNDtpOjIxMDtpOjEwNTM3MTk1O2k6MjExO2k6MTUxMzIxMzM7aToyMTI7aToxNTgzNjYxO2k6MjEzO2k6OTY4MDMzNTtpOjIxNDtpOjQwOTU2Njc7aToyMTU7aTo4NDM0NzkxO2k6MjE2O2k6MTkyODM1NDtpOjIxNztpOjE5Mjg2MTE7aToyMTg7aTo0MzA5NDQ3O2k6MjE5O2k6MTExMzAyNjY7aToyMjA7aToxMjgyOTM3ODtpOjIyMTtpOjE0NDc0MjAzO2k6MjIyO2k6MTYxNjk1MTc7aToyMjM7aTo4MjQyMjgwO2k6MjI0O2k6MTAyMjg1MztpOjIyNTtpOjE0Mjc3MDgwO2k6MjI2O2k6Mzk2NDMzOTtpOjIyNztpOjE5Mjg4Njk7aToyMjg7aToxNTU1MTI4MTtpOjIyOTtpOjQyMzg2ODA7aToyMzA7aTo0ODI0NzYxO2k6MjMxO2k6ODc0ODMwO2k6MjMyO2k6MTY3NjI4ODg7aToyMzM7aToxMjc5ODc4NTtpOjIzNDtpOjE5OTQ2NjE7aToyMzU7aTo2NTE5OTM5O2k6MjM2O2k6NDU0MjU0OTtpOjIzNztpOjE5OTUxNzY7aToyMzg7aTozNDM5MjgxO2k6MjM5O2k6MjA2MTQ4NTtpOjI0MDtpOjI0NTQ5NTc7aToyNDE7aToxOTMwNDEyO2k6MjQyO2k6MTk5NDkxOTtpOjI0MztpOjE0NjcxNTgxO2k6MjQ0O2k6MTg2NDEwNTtpOjI0NTtpOjM2MzYxNDU7aToyNDY7aTozMDQ1NTUxO2k6MjQ3O2k6MTY3NzcyMTU7aToyNDg7aToyNjUxODIyO2k6MjQ5O2k6MTIyNDc3NzU7aToyNTA7aTozMzA3OTUyO2k6MjUxO2k6MjcxNzYxNTtpOjI1MjtpOjMwMzA1OTY7aToyNTM7aToxOTk0OTE4O2k6MjU0O2k6MjEyNjUwNjtpOjI1NTtpOjIwNjEyMjc7fXM6NDoiSURBVCI7YToxOntpOjA7YToxOntzOjY6ImhlYWRlciI7YTo1OntzOjExOiJkYXRhX2xlbmd0aCI7aToxMjc0Njc7czo5OiJ0eXBlX3RleHQiO3M6NDoiSURBVCI7czo4OiJ0eXBlX3JhdyI7aToxMjI5MjA5OTQwO3M6MzoiY3JjIjtkOjQxNTc3MDQ2MzE7czo1OiJmbGFncyI7YTo0OntzOjEwOiJhbmNpbGxpYXJ5IjtiOjA7czo3OiJwcml2YXRlIjtiOjA7czo4OiJyZXNlcnZlZCI7YjowO3M6MTI6InNhZmVfdG9fY29weSI7YjowO319fX1zOjQ6IklFTkQiO2E6MTp7czo2OiJoZWFkZXIiO2E6Njp7czoxMToiZGF0YV9sZW5ndGgiO2k6MDtzOjk6InR5cGVfdGV4dCI7czo0OiJJRU5EIjtzOjg6InR5cGVfcmF3IjtpOjEyMjkyNzg3ODg7czozOiJjcmMiO2Q6MjkyMzU4NTY2NjtzOjU6ImZsYWdzIjthOjQ6e3M6MTA6ImFuY2lsbGlhcnkiO2I6MDtzOjc6InByaXZhdGUiO2I6MDtzOjg6InJlc2VydmVkIjtiOjA7czoxMjoic2FmZV90b19jb3B5IjtiOjA7fXM6NDoiZGF0YSI7czowOiIiO319czo4OiJlbmNvZGluZyI7czo1OiJVVEYtOCI7fX0=',
				'created_at' => '2015-04-29 04:21:27',
				'updated_at' => '2015-05-15 12:14:51',
			),
			1 => 
			array (
				'id' => '17',
				'title' => 'mov_bbb.mp4.frame.2.jpg',
				'description' => 'mov_bbb.mp4.frame.2.jpg',
				'filename' => 'mov_bbb.mp4.frame.2.jpg',
				'path' => 'images/mov_bbb.mp4.frame.2.jpg',
				'original_filename' => 'mov_bbb.mp4.frame.2.jpg',
				'type' => 'image/jpeg',
				'filesize' => '13607',
				'width' => '320',
				'height' => '176',
				'ratio' => '1.00',
				'sizes' => '{"original":"http:\\/\\/devhost.dev\\/LaraPress\\/lp-content\\/files\\/images\\/mov_bbb.mp4.frame.2.jpg","120":"http:\\/\\/devhost.dev\\/LaraPress\\/en\\/lp-content\\/files\\/images\\/2015\\/05\\/15\\/120\\/mov_bbb.mp4.frame.2.jpg"}',
				'full_data' => 'YToxMTp7czoxNDoiR0VUSUQzX1ZFUlNJT04iO3M6MTQ6IjEuOS45LTIwMTQxMTIxIjtzOjg6ImZpbGVzaXplIjtpOjEzNjA3O3M6ODoiZmlsZXBhdGgiO3M6NjU6Ii9ob21lL2Jhc2RvZzIyL1BocHN0b3JtUHJvamVjdHMvTGFyYVByZXNzL2xwLWNvbnRlbnQvZmlsZXMvaW1hZ2VzIjtzOjg6ImZpbGVuYW1lIjtzOjIzOiJtb3ZfYmJiLm1wNC5mcmFtZS4yLmpwZyI7czoxMjoiZmlsZW5hbWVwYXRoIjtzOjg5OiIvaG9tZS9iYXNkb2cyMi9QaHBzdG9ybVByb2plY3RzL0xhcmFQcmVzcy9scC1jb250ZW50L2ZpbGVzL2ltYWdlcy9tb3ZfYmJiLm1wNC5mcmFtZS4yLmpwZyI7czoxMjoiYXZkYXRhb2Zmc2V0IjtpOjA7czo5OiJhdmRhdGFlbmQiO2k6MTM2MDc7czoxMDoiZmlsZWZvcm1hdCI7czozOiJqcGciO3M6NToidmlkZW8iO2E6Nzp7czoxMDoiZGF0YWZvcm1hdCI7czozOiJqcGciO3M6ODoibG9zc2xlc3MiO2I6MDtzOjE1OiJiaXRzX3Blcl9zYW1wbGUiO2k6MjQ7czoxODoicGl4ZWxfYXNwZWN0X3JhdGlvIjtkOjE7czoxMjoicmVzb2x1dGlvbl94IjtpOjMyMDtzOjEyOiJyZXNvbHV0aW9uX3kiO2k6MTc2O3M6MTc6ImNvbXByZXNzaW9uX3JhdGlvIjtkOjAuMDgwNTMzODU0MTY2NjY2NjY5O31zOjg6ImVuY29kaW5nIjtzOjU6IlVURi04IjtzOjk6Im1pbWVfdHlwZSI7czoxMDoiaW1hZ2UvanBlZyI7fQ==',
				'created_at' => '2015-05-13 13:09:38',
				'updated_at' => '2015-05-15 12:14:51',
			),
			2 => 
			array (
				'id' => '18',
				'title' => 'mov_bbb.mp4.frame.5.jpg',
				'description' => 'mov_bbb.mp4.frame.5.jpg',
				'filename' => 'mov_bbb.mp4.frame.5.jpg',
				'path' => 'images/mov_bbb.mp4.frame.5.jpg',
				'original_filename' => 'mov_bbb.mp4.frame.5.jpg',
				'type' => 'image/jpeg',
				'filesize' => '9162',
				'width' => '320',
				'height' => '176',
				'ratio' => '1.00',
				'sizes' => '{"original":"http:\\/\\/devhost.dev\\/LaraPress\\/lp-content\\/files\\/images\\/mov_bbb.mp4.frame.5.jpg","120":"http:\\/\\/devhost.dev\\/LaraPress\\/en\\/lp-content\\/files\\/images\\/2015\\/05\\/15\\/120\\/mov_bbb.mp4.frame.5.jpg"}',
				'full_data' => 'YToxMTp7czoxNDoiR0VUSUQzX1ZFUlNJT04iO3M6MTQ6IjEuOS45LTIwMTQxMTIxIjtzOjg6ImZpbGVzaXplIjtpOjkxNjI7czo4OiJmaWxlcGF0aCI7czo2NToiL2hvbWUvYmFzZG9nMjIvUGhwc3Rvcm1Qcm9qZWN0cy9MYXJhUHJlc3MvbHAtY29udGVudC9maWxlcy9pbWFnZXMiO3M6ODoiZmlsZW5hbWUiO3M6MjM6Im1vdl9iYmIubXA0LmZyYW1lLjUuanBnIjtzOjEyOiJmaWxlbmFtZXBhdGgiO3M6ODk6Ii9ob21lL2Jhc2RvZzIyL1BocHN0b3JtUHJvamVjdHMvTGFyYVByZXNzL2xwLWNvbnRlbnQvZmlsZXMvaW1hZ2VzL21vdl9iYmIubXA0LmZyYW1lLjUuanBnIjtzOjEyOiJhdmRhdGFvZmZzZXQiO2k6MDtzOjk6ImF2ZGF0YWVuZCI7aTo5MTYyO3M6MTA6ImZpbGVmb3JtYXQiO3M6MzoianBnIjtzOjU6InZpZGVvIjthOjc6e3M6MTA6ImRhdGFmb3JtYXQiO3M6MzoianBnIjtzOjg6Imxvc3NsZXNzIjtiOjA7czoxNToiYml0c19wZXJfc2FtcGxlIjtpOjI0O3M6MTg6InBpeGVsX2FzcGVjdF9yYXRpbyI7ZDoxO3M6MTI6InJlc29sdXRpb25feCI7aTozMjA7czoxMjoicmVzb2x1dGlvbl95IjtpOjE3NjtzOjE3OiJjb21wcmVzc2lvbl9yYXRpbyI7ZDowLjA1NDIyNTg1MjI3MjcyNzI2OTt9czo4OiJlbmNvZGluZyI7czo1OiJVVEYtOCI7czo5OiJtaW1lX3R5cGUiO3M6MTA6ImltYWdlL2pwZWciO30=',
				'created_at' => '2015-05-13 13:09:38',
				'updated_at' => '2015-05-15 12:14:51',
			),
			3 => 
			array (
				'id' => '19',
				'title' => 'mov_bbb.mp4.frame.8.jpg',
				'description' => 'mov_bbb.mp4.frame.8.jpg',
				'filename' => 'mov_bbb.mp4.frame.8.jpg',
				'path' => 'images/mov_bbb.mp4.frame.8.jpg',
				'original_filename' => 'mov_bbb.mp4.frame.8.jpg',
				'type' => 'image/jpeg',
				'filesize' => '10326',
				'width' => '320',
				'height' => '176',
				'ratio' => '1.00',
				'sizes' => '{"original":"http:\\/\\/devhost.dev\\/LaraPress\\/lp-content\\/files\\/images\\/mov_bbb.mp4.frame.8.jpg","120":"http:\\/\\/devhost.dev\\/LaraPress\\/en\\/lp-content\\/files\\/images\\/2015\\/05\\/15\\/120\\/mov_bbb.mp4.frame.8.jpg"}',
				'full_data' => 'YToxMTp7czoxNDoiR0VUSUQzX1ZFUlNJT04iO3M6MTQ6IjEuOS45LTIwMTQxMTIxIjtzOjg6ImZpbGVzaXplIjtpOjEwMzI2O3M6ODoiZmlsZXBhdGgiO3M6NjU6Ii9ob21lL2Jhc2RvZzIyL1BocHN0b3JtUHJvamVjdHMvTGFyYVByZXNzL2xwLWNvbnRlbnQvZmlsZXMvaW1hZ2VzIjtzOjg6ImZpbGVuYW1lIjtzOjIzOiJtb3ZfYmJiLm1wNC5mcmFtZS44LmpwZyI7czoxMjoiZmlsZW5hbWVwYXRoIjtzOjg5OiIvaG9tZS9iYXNkb2cyMi9QaHBzdG9ybVByb2plY3RzL0xhcmFQcmVzcy9scC1jb250ZW50L2ZpbGVzL2ltYWdlcy9tb3ZfYmJiLm1wNC5mcmFtZS44LmpwZyI7czoxMjoiYXZkYXRhb2Zmc2V0IjtpOjA7czo5OiJhdmRhdGFlbmQiO2k6MTAzMjY7czoxMDoiZmlsZWZvcm1hdCI7czozOiJqcGciO3M6NToidmlkZW8iO2E6Nzp7czoxMDoiZGF0YWZvcm1hdCI7czozOiJqcGciO3M6ODoibG9zc2xlc3MiO2I6MDtzOjE1OiJiaXRzX3Blcl9zYW1wbGUiO2k6MjQ7czoxODoicGl4ZWxfYXNwZWN0X3JhdGlvIjtkOjE7czoxMjoicmVzb2x1dGlvbl94IjtpOjMyMDtzOjEyOiJyZXNvbHV0aW9uX3kiO2k6MTc2O3M6MTc6ImNvbXByZXNzaW9uX3JhdGlvIjtkOjAuMDYxMTE1MDU2ODE4MTgxODE1O31zOjg6ImVuY29kaW5nIjtzOjU6IlVURi04IjtzOjk6Im1pbWVfdHlwZSI7czoxMDoiaW1hZ2UvanBlZyI7fQ==',
				'created_at' => '2015-05-13 13:09:38',
				'updated_at' => '2015-05-15 12:14:51',
			),
		));
	}

}
