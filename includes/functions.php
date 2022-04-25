<?php
/*
	Implements db_connect
	Provides the connection if already exists otherwise creates a new connection and returns it

	@return connection or an error
*/
function db_connect()
{
	static $connection;

	if(!isset($connection)){
		$config = parse_ini_file("config.ini");
		$connection = mysqli_connect($config['host'],$config['username'],$config['password'],$config['dbname']);
	}

	if($connection === false){
		return msqli_connect_error();
	}

	return $connection;
}

function db_query($query){
	//connect to database
	$connection = db_connect();

	$result = mysqli_query($connection,$query);

	return $result;
}

function db_select($query){
	$result = db_query($query);
	if($result === false){
		return false;
	}
	$rows = array();
	while($row = mysqli_fetch_assoc($result)){
		$rows[] = $row;
	}
	return $rows;
}

function db_error(){
	$connection = db_connect();
	return mysqli_error($connection);
}

function sanitize_input($value){
	$connection = db_connect();
	return mysqli_real_escape_string($connection,$value);
}
function dd($var){
	die(var_dump($var));
}