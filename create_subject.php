<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
	$errors=array();
	//Form Validation
	$required_values=array("menu_name","position","visible");
	foreach($required_values as $input){
		validate($input);
	}
	if(!empty($errors)){
		redirect("new_subject.php");
	}
?>

<?php
	if(empty($errors)){
		$menu_name=mysqli_real_escape_string($connection,$_POST['menu_name']);
		$position=mysqli_real_escape_string($connection,$_POST['position']);
		$visible=mysqli_real_escape_string($connection,$_POST['visible']);
	}
?>

<?php
	$query="INSERT INTO subjects (menu_name,position,visible)
			VALUES ('{$menu_name}', {$position}, {$visible})";
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