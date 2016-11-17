 <?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php find_selected_page() ?>
<?php include('includes/header.php'); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<?php echo navigation($selected_subject,$selected_page); ?>
			<br>
			<a href="new_subject.php">+Add a New Subject</a>
		</td>
		<td id="page">
		<h2>Add Page </h2>
			<form action="create_page.php" method="POST">
				<p>
					Page Name:
					<input type="text" name="menu_name" value="" id="menu_name"/>
				</p>
				<p>
					Parent Subject:
					<select name="subject">
					<?php
					$subject_set=get_all_subjects();
					while($subject=mysqli_fetch_array($subject_set)){
						echo "<option value=\"{$subject['id']}\"";
						if($_GET['subj']==$subject['id']){ echo "selected";}
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
							$page_set=get_all_pages($id_subject);
							$page_count=mysqli_num_rows($page_set);
							for($count=1;$count<=$page_count+1;$count++){
								echo "<option value=\"{$count}\">{$count}</option>";
							}
						?>
					</select>
				</p>
				<p>
					Visible:
					<input type="radio" name="visible" value="0"/>No
					&nbsp;
					<input type="radio" name="visible" value="1"/>Yes
				</p>
				<input type="submit" name="submit" value="Add Page"/>
			</form>
		<br>
		<a href="content.php">Cancel</a>
		</td>
	</tr>
</table> 
<?php require_once('includes/footer.php'); ?>
