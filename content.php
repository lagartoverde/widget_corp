<?php require_once('includes/session.php'); ?>
<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php include('includes/header.php'); ?>
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
			<h2>
			<?php
				if(isset($selected_subject)&&$selected_subject!=NULL){
			 		echo $selected_subject['menu_name'];
			 	}elseif(isset($selected_page)&&$selected_page!=NULL){
			 			echo $selected_page['menu_name'];	
			 	}else{
			 		echo "Select a Subject or Page";
			 	}
			?>
			</h2>
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
