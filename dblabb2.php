<?php
/* extend PDO */
class DB extends PDO
{
	public function __construct($dbname = "dblabb")
	{
		try {
			parent::__construct("mysql:host=localhost;dbname=$dbname;charset=utf8", "root", "mysql");
		} catch (Exception $e) {
			echo "<pre>" . print_r($e, 1) . "</pre>";
		}
	}
}