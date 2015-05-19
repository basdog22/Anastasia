<?php
$sub = substr(\Config::get('app.key'),0,5);
return array(

	'path' => '/database/points/'.date("Y.m.d.H.i",time()).$sub,
    'chunk_size' => 500 // Maximum number of rows per insert statement

);
