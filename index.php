<?php require_once('includes/connection.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php find_selected_page(); ?>
<?php include('includes/header.php'); ?>
<table id="structure">
	<tr>
		<td id="navigation">
			<?php echo navigation_public($selected_subject,$selected_page); ?>
		</td>
		<td id="page">
			<h2>
			<?php
				if(isset($selected_page)&&$selected_page!=NULL){
			 			echo htmlentities($selected_page['menu_name']);	
			 	}else{
			 		echo "Welcome To Widget Corp";
			 	}
			?>
			</h2>
			<div class="page-content">
				<?php
					if(isset($selected_page)&&$selected_page!=NULL){
					 	echo strip_tags(nl2br($selected_page['content']),$allowed_tags);
					 }
				?>
			</div>
		</td>
	</tr>
</table> 
<?php require_once('includes/footer.php'); ?>