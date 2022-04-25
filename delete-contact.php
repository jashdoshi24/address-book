<?php
require_once("includes/functions.php");
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$row = db_select("SELECT * FROM contacts WHERE id = {$id}");
	
	if($row == false){
		$error = "error while deleting";
		header("Location: index.php?q=error&op=del");
		// dd($error);
	}
	$image_name = $row[0]['image_name'];
	console.log($image_name);
	unlink("images/users/{$image_name}");

	$result = db_query("DELETE FROM contacts WHERE id={$id}");
	if(!$result)
	{
		header("Location: index.php?q=error&op=del");
	}else{
		header("Location: index.php?q=success&op=del");
	}
	
}