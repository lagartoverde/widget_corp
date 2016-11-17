<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
	if(intval($_GET['subj'])==0){
		redirect("content.php");
	}
	$id=mysqli_real_escape_string($connection,$_GET['subj']);

	if($subject=get_subject_by_id($id)){

		$query="DELETE FROM subjects WHERE id={$id} LIMIT 1";

		$result=mysqli_query($connection,$query);

		if(mysqli_affected_rows($connection)==1){
			redirect("content.php");
		}else{
			//Deletion Failed
			echo "Subject Deletion Fail";
			echo "<a href=\"content.php\">Return to mainpage</a>";
		}
	}else{
		redirect("content.php");
	}
?>

<?php
	mysqli_close($connection);
?>