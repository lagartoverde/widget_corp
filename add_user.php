<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php
	if(isset($_POST['submit'])){
		$errors=array();
		//Form Validation
		$required_values=array("username","password");
		foreach($required_values as $input){
			validate($input);
		}

		$field_with_lengths= array('username'=>30,'password'=>30);
		foreach($field_with_lengths as $field_name=>$max_length){
			check_length($field_name,$max_length);
		}
		if(empty($errors)){
			//Perform Update
			$username=mysqli_real_escape_string($connection,$_POST['username']);
			$password=mysqli_real_escape_string($connection,$_POST['password']);

			$hash_password=sha1($password);

			$query="INSERT INTO `users` (`id`, `username`, `hash_password`) VALUES (NULL, '{$username}', '{$hash_password}')";

			$result=mysqli_query($connection,$query);
			if(mysqli_affected_rows($connection)==1){
				//Success
				$success=true;
				redirect_to("staff.php");
				
			}else{
				//Fail
				$success=false;
				$fail=1;
			}

		}else{
			//Errors occurred
			$success=false;
			$fail=2;

		}
	}else{
		$username="";
		$password="";
	}

?>
<?php include('includes/header.php'); ?>
<?php

if(isset($success)){
	if($success){
		echo "<script>";
		echo "swal(\"Added\", \"The user was sucessfully created!\", \"success\");";
		echo "</script>";
	}else if($fail==1){
		echo "<script>";
		echo "swal(\"Error\", \"The database connection failed\", \"error\");";
		echo "</script>";
	}else if($fail=2){
		echo "<script>";
		echo "swal(\"Error\", \"The following values are not valid: ";
		foreach($errors as $error){
			echo $error." ";
		}
		echo "\", \"error\");";
		echo "</script>";
	}
}
?>
<table id="structure">
	<tr>
		<td id="navigation">
			<a href="content.php">Return to Menu</a>
		</td>
		<td id="page">
			<h2>Create New User: </h2>
			<form action="add_user.php" method="POST">
				Username:<input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username);?>"/><br>
				Password: <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password);?>"/><br>
				<input type="submit" name="submit" value="Create User">
			</form>
			<div class="page-content">
				<?php
					if(isset($selected_page)&&$selected_page!=NULL){
					 	echo $selected_page['content'];
					 }
				?>
			</div>
		</td>
	</tr>
</table> 
<?php require_once('includes/footer.php'); ?>