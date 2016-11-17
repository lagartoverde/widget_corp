<?php
	//This file is the place to store all basic functions
	
	function confirm_query($result_set){
		if(!$result_set){
			die("Database query failed");
		}
	}
	function get_all_subjects($public){
		global $connection;
		$query="SELECT *
				FROM subjects ";
		if($public){ $query.="WHERE visible=1 ";}
		$query.="ORDER BY position ASC";
		$subject_set=mysqli_query($connection, $query);
		confirm_query($subject_set);
		return $subject_set;
	}

	function get_all_pages(){
		global $connection;
		$query="SELECT *
				FROM pages 
				ORDER BY position ASC";
		$page_set=mysqli_query($connection, $query);
		confirm_query($page_set);
		return $page_set;
	}

	function get_pages_for_subject($subject_id,$public=false){
		global $connection;
		$query="SELECT * 
				FROM pages 
				WHERE subject_id={$subject_id } ";
		if($public){ $query.="AND visible=1 "; } 
		$query.="ORDER BY position ASC";
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
		$page_id=mysqli_real_escape_string($connection,$page_id);
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

	function get_default_page($subject_id){
		$page_set=get_pages_for_subject($subject_id);
		if($default=mysqli_fetch_array($page_set)){
			return $default;
		}else{
			return NULL;
		}
	}

	function find_selected_page(){
		global $selected_subject;
		global $selected_page;
		global $connection;
		if(isset($_GET['subj'])){ 
			$subj_id=mysqli_real_escape_string($connection,$_GET['subj']);
			$selected_subject=get_subject_by_id($subj_id);
			$selected_page=get_default_page($subj_id);
		} elseif(isset($_GET['page'])){
			$page_id=mysqli_real_escape_string($connection,$_GET['page']);
			$selected_page=get_page_by_id($page_id);
		}
	}

	function navigation($selected_subject,$selected_page){
		$public=false;
		$output= "<ul class=\"subjects\">";
		//3.Perform Database Query
		$subject_set=get_all_subjects($public);
		//4.Use Returned Data
		while($subject=mysqli_fetch_array($subject_set)){
			if(isset($selected_subject)&&$selected_subject['id']==$subject['id']){
				$class="class=\"selected\"";
			}else{
				$class="";
			}
			$output.= "<li {$class}><a href=\"edit_subject.php?subj=".urlencode($subject['id'])."\">{$subject["menu_name"]}</a></li>";
			//3.Perform Database Query
			$page_set=get_pages_for_subject($subject['id'],$public);
			$output.= "<ul class=\"pages\">";
			//4.Use Returned Data
			while($page=mysqli_fetch_array($page_set)){
				if(isset($selected_page)&&$selected_page['id']==$page['id']){
					$class="class=\"selected\"";
				}else{
					$class="";
				}
				$output.= "<li {$class}><a href=\"edit_page.php?page=".urlencode($page["id"])."\">{$page["menu_name"]}</a></li>";
			}
			$output.= "</ul>";
		}
		$output.= "</ul>";
		return $output;
	}

	function navigation_public($selected_subject,$selected_page){
		$public =true; 
		$output= "<ul class=\"subjects\">";
		//3.Perform Database Query
		$subject_set=get_all_subjects($public);
		//4.Use Returned Data
		while($subject=mysqli_fetch_array($subject_set)){
			if(isset($selected_subject)&&$selected_subject['id']==$subject['id']){
				$class="class=\"selected\"";
			}else{
				$class="";
			}
			$output.= "<li {$class}><a href=\"index.php?subj=".urlencode($subject['id'])."\">{$subject["menu_name"]}</a></li>";
			//3.Perform Database Query
			$page_set=get_pages_for_subject($subject['id'],$public);
			$output.= "<ul class=\"pages\">";
			if((isset($selected_subject)&&$selected_subject['id']==$subject['id'])||(isset($selected_page)&&$selected_page['subject_id']==$subject['id'])){
			//4.Use Returned Data
				while($page=mysqli_fetch_array($page_set)){
					if(isset($selected_page)&&$selected_page['id']==$page['id']){
						$class="class=\"selected\"";
					}else{
						$class="";
					}
					$output.= "<li {$class}><a href=\"index.php?page=".urlencode($page["id"])."\">{$page["menu_name"]}</a></li>";
				}
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

	function validate($field_name){
		global $errors;
		if(!isset($_POST[$field_name])||(empty ($_POST[$field_name])&&$field_name!="visible")){
			$errors[]=$field_name;
		}
	}

	function check_length($field_name,$max_length){
		global $errors;
		if(strlen(trim($_POST[$field_name]))>$max_length){
			$errors[]=$field_name;
		}
	}

?>