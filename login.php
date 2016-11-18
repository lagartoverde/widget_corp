<?php require_once('includes/session.php'); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php if(logged_in()){ redirect('staff.php');} ?>
<?php
	//Start Form Processing
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

			$query="SELECT id,username FROM users WHERE username='{$username}' AND hash_password='{$hash_password}' LIMIT 1";

			$result_set=mysqli_query($connection,$query);
			confirm_query($result_set);
			if(mysqli_num_rows($result_set)){
				//Success
				$success=true;
				$found_user=mysqli_fetch_array($result_set);
				$_SESSION['user_id']=$found_user['id'];
				$_SESSION['username']=$found_user['username'];
				redirect("staff.php");
				
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
		echo "swal(\"Correct\", \"The login was sucessfull\", \"success\");";
		echo "</script>";
	}else if($fail==1){
		echo "<script>";
		echo "swal(\"Error\", \"Username or password incorrect\", \"error\");";
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
if(isset($_GET['logout'])&&$_GET['logout']=1){
	echo "<script>";
	echo "swal(\"Logged Out\", \"You are now logged out\", \"success\");";
	echo "</script>";
}
?>
<table id="structure">
	<tr>
		<td id="navigation">
			<a href="index.php">Return to public site</a>
		</td>
		<td id="page">
			<h2>Staff Login: </h2>
			<form action="login.php" method="POST">
				Username: <input type="text" name="username" value=""/><br>
				Password: <input type="password" name="password" value=""/><br>
				<input type="submit" name="submit" value="Login">
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

