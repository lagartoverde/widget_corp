<?php
	//This file is the place to store all basic functions
	
	function confirm_query($result_set){
		if(!$result_set){
			die("Database query failed");
		}
	}
	function get_all_subjects(){
		global $connection;
		$query="SELECT *
				FROM subjects 
				ORDER BY position ASC";
		$subject_set=mysqli_query($connection, $query);
		confirm_query($subject_set);
		return $subject_set;
	}

	function get_pages_for_subject($subject_id){
		global $connection;
		$query="SELECT * 
				FROM pages 
				WHERE subject_id={$subject_id } 
				ORDER BY position ASC";
		$page_set=mysqli_query($connection, $query);
		confirm_query($page_set);
		return $page_set;
	}

	function get_subject_by_id($subject_id){
		global $connection;
		$query="SELECT * ";
		$query.="FROM subjects ";
		$query.="WHERE id={$subject_id} ";
		$query.="LIMIT 1";
		$result_set=mysqli_query($connection,$query);
		confirm_query($result_set);
		//REMEMBER:
		//if not rows are return fetch array returns false
		if($subject = mysqli_fetch_array($result_set)){
			return $subject;
		}else{
			return NULL;
		}
	}

	function get_page_by_id($page_id){
		global $connection;
		$query="SELECT * ";
		$query.="FROM pages ";
		$query.="WHERE id={$page_id} ";
		$query.="LIMIT 1";
		$result_set=mysqli_query($connection,$query);
		confirm_query($result_set);
		//REMEMBER:
		//if not rows are return fetch array returns false
		if($page = mysqli_fetch_array($result_set)){
			return $page;
		}else{
			return NULL;
		}
	}

	function find_selected_page(){
		global $selected_subject;
		global $selected_page;
		if(isset($_GET['subj'])){ 
			$selected_subject=get_subject_by_id($_GET['subj']);
		} elseif(isset($_GET['page'])){
			$selected_page=get_page_by_id($_GET['page']);
		}
	}

	function navigation($selected_subject,$selected_page){
		$output= "<ul class=\"subjects\">";
		//3.Perform Database Query
		$subject_set=get_all_subjects();
		//4.Use Returned Data
		while($subject=mysqli_fetch_array($subject_set)){
			if(isset($selected_subject)&&$selected_subject['id']==$subject['id']){
				$class="class=\"selected\"";
			}else{
				$class="";
			}
			$output.= "<li {$class}><a href=\"content.php?subj=".urlencode($subject['id'])."\">{$subject["menu_name"]}</a></li>";
			//3.Perform Database Query
			$page_set=get_pages_for_subject($subject['id']);
			$output.= "<ul class=\"pages\">";
			//4.Use Returned Data
			while($page=mysqli_fetch_array($page_set)){
				if(isset($selected_page)&&$selected_page['id']==$page['id']){
					$class="class=\"selected\"";
				}else{
					$class="";
				}
				$output.= "<li {$class}><a href=\"content.php?page=".urlencode($page["id"])."\">{$page["menu_name"]}</a></li>";
			}
			$output.= "</ul>";
		}
		$output.= "</ul>";
		return $output;
	}

	function redirect($location=NULL){
		if($location!=NULL){
			header("Location: {$location}");
			exit;
		}
	}

	function validate($input){
		global $errors;
		if(!isset($_POST[$input])||empty($_POST[$input])){
		$errors[]=$input;
		}
	}
?>