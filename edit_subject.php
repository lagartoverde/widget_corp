 <?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php 
	if(intval($_GET['subj'])==0){
		redirect("content.php");
	}

	if(isset($_POST['delete'])){
		redirect("delete_subject.php?subj=".urlencode($_GET['subj']));
	}

	if(isset($_POST['submit'])){
		$errors=array();
		//Form Validation
		$required_values=array("menu_name","position");
		foreach($required_values as $input){
			validate($input);
		}

		$field_with_lengths= array('menu_name'=>30);
		foreach($field_with_lengths as $field_name=>$max_length){
			check_length($field_name,$max_length);
		}
		if(empty($errors)){
			//Perform Update
			$id=mysqli_real_escape_string($connection,$_GET['subj']);
			$menu_name=mysqli_real_escape_string($connection,$_POST['menu_name']);
			$position=mysqli_real_escape_string($connection,$_POST['position']);
			$visible=mysqli_real_escape_string($connection,$_POST['visible']);

			$query="UPDATE subjects SET 
					menu_name=\"{$menu_name}\",
					position={$position},
					visible={$visible}
					WHERE id={$id};";

			$result=mysqli_query($connection,$query);
			if(mysqli_affected_rows($connection)==1){
				//Success
				$success=true;
				
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

		
	}
	if(isset($_POST['delete'])){
		$delete=true;
	}

?>
<?php find_selected_page() ?>
<?php include('includes/header.php'); ?>
<?php
if(isset($success)){
	if($success){
		echo "<script>";
		echo "swal(\"Edited\", \"The subject was sucessfully edited!\", \"success\");";
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
			<?php echo navigation($selected_subject,$selected_page); ?>
			<br>
			<a href="new_subject.php">+Add a New Subject</a>
		</td>
		<td id="page">
		<h2>Edit Subject: <?php echo $selected_subject['menu_name']; ?></h2>
			<form action="edit_subject.php?subj=<?php echo urlencode($selected_subject['id']);?>" method="POST">
				<p>
					Subject Name:
					<input type="text" name="menu_name" value="<?php echo $selected_subject['menu_name']; ?>" id="menu_name"/>
				</p>
				<p>
					Position:
					<select name="position">
						<?php
							$subject_set=get_all_subjects();
							$subject_count=mysqli_num_rows($subject_set);
							for($count=1;$count<=$subject_count+1;$count++){
								echo "<option value=\"{$count}\"";
								if($count==$selected_subject['position']){ echo "selected";}
								echo ">{$count}</option>";
							}
						?>
					</select>
				</p>
				<p>
					Visible:
					<input type="radio" name="visible" value="0" <?php if($selected_subject['visible']==0){echo "checked";} ?>/>No
					&nbsp;
					<input type="radio" name="visible" value="1" <?php if($selected_subject['visible']==1){echo "checked";} ?>/>Yes
				</p>
				<input type="submit" name="submit" value="Edit Subject"/>
				<input type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete Subject"/>
			</form>
		<br>
		<a href="content.php">Cancel</a>
		<br>
		<h3>List of Pages:</h3>
		<table>
			<ul>
				<?php
					$id=mysqli_real_escape_string($connection,$_GET['subj']);
					$page_set=get_pages_for_subject($id);
					while($page=mysqli_fetch_array($page_set)){
						echo "<li><a href=\"edit_page.php?page={$page['id']}\">{$page['menu_name']}</a></li>";
					}
				?>
			</ul>
			<br>
				<a href="new_page.php?subj=<?php echo $id; ?>">+Add a New Page</a>
		</table>
		</td>
	</tr>
</table> 
<?php require_once('includes/footer.php'); ?>
