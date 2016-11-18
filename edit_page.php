<?php require_once('includes/session.php'); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php confirm_logged_in(); ?>
<?php 
	if(intval($_GET['page'])==0){
		redirect("content.php");
	}

	if(isset($_POST['delete'])){
		redirect("delete_page.php?page=".urlencode($_GET['page']));
	}

	if(isset($_POST['submit'])){
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
		if(empty($errors)){
			//Perform Update
			$id=mysqli_real_escape_string($connection,$_GET['page']);
			$menu_name=mysqli_real_escape_string($connection,$_POST['menu_name']);
			$parent_subject=mysqli_real_escape_string($connection,$_POST['subject']);
			$content=mysqli_real_escape_string($connection,$_POST['content']);
			$position=mysqli_real_escape_string($connection,$_POST['position']);
			$visible=mysqli_real_escape_string($connection,$_POST['visible']);

			$query="UPDATE pages SET 
					menu_name=\"{$menu_name}\",
					subject_id={$parent_subject},
					content=\"{$content}\",
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
		echo "swal(\"Edited\", \"The subject was sucessfully edited\", \"success\");";
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
			<br>
			<a href="staff.php">Return to Menu</a>
		</td>
		<td id="page">
		<h2>Edit Page: <?php echo $selected_page['menu_name']; ?></h2>
			<form action="edit_page.php?page=<?php echo urlencode($selected_page['id']);?>" method="POST">
				<p>
					Page Name:
					<input type="text" name="menu_name" value="<?php echo $selected_page['menu_name']; ?>" id="menu_name"/>
				</p>
				<p>
					Parent Subject:
					<select name="subject">
					<?php
					$subject_set=get_all_subjects();
					while($subject=mysqli_fetch_array($subject_set)){
						echo "<option value=\"{$subject['id']}\"";
						if($subject['id']==$selected_page['subject_id']){ echo "selected";}
						echo ">{$subject['menu_name']}</option>";
					}
					?>
					</select>
				</p>
				<p>
					Content:
					<br>
					<textarea rows="20" cols=100 name="content"  id="content"><?php echo $selected_page['content']; ?></textarea>
				</p>
				<p>
					Position:
					<select name="position">
						<?php
							$id_subject=$selected_page['subject_id'];
							$page_set=get_pages_for_subject($id_subject);
							$page_count=mysqli_num_rows($page_set);
							for($count=1;$count<=$page_count+1;$count++){
								echo "<option value=\"{$count}\"";
								if($count==$selected_page['position']){ echo "selected";}
								echo ">{$count}</option>";
							}
						?>
					</select>
				</p>
				<p>
					Visible:
					<input type="radio" name="visible" value="0" <?php if($selected_page['visible']==0){echo "checked";} ?>/>No
					&nbsp;
					<input type="radio" name="visible" value="1" <?php if($selected_page['visible']==1){echo "checked";} ?>/>Yes
				</p>
				<input type="submit" name="submit" value="Edit Page"/>
				<input type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete Page"/>
			</form>
		<br>
		<a href="content.php">Cancel</a>
		</td>
	</tr>
</table> 
<?php require_once('includes/footer.php'); ?>
