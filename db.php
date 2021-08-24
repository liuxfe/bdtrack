<?php

define("__DEBUG__", "1");

class Db extends Sqlite3
{
	function __construct($db_file){
		parent::__construct($db_file);
	}

	function __destruct(){
		parent::close();
	}
}

$db = new Db(__DIR__."/bdtrackid.db");