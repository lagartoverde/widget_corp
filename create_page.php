<?php require_once('includes/session.php'); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$errors=array();
	//Form Validation
	$required_values=array("menu_name","content","subject","position");
	foreach($required_values as $input){
		validate($input);
	}

	$field_with_lengths= array('menu_name'=>30);
	foreach($field_with_lengths as $field_name=>$max_length){
		check_length($field_name,$max_length);
	}
	if(!empty($errors)){
		redirect("new_subject.php");
	}
?>

<?php
		$menu_name=mysqli_real_escape_string($connection,$_POST['menu_name']);
		$parent_subject=mysqli_real_escape_string($connection,$_POST['subject']);
		$content=mysqli_real_escape_string($connection,$_POST['content']);
		$position=mysqli_real_escape_string($connection,$_POST['position']);
		$visible=mysqli_real_escape_string($connection,$_POST['visible']);
?>

<?php
	$query="INSERT INTO pages (menu_name,subject_id,content,position,visible)
			VALUES ('{$menu_name}',{$parent_subject}, '{$content}', {$position}, {$visible})";
	if(mysqli_query($connection,$query)){
		//Success
		redirect("content.php");
	}else{
		//Display Error Message
		echo "<p>Subject Creation Failed</p>";
		echo "<a href=\"new_subject.php\">Volver</a>";
	}
?>

<?php
	mysqli_close($connection);
?>