<?php 

if (isset($_POST)){

	$check = new DB_check;
	if ($check->ifbanned()){
	}else{
		
				if(trim($_POST['commentname']) === '')  {
		$_SESSION['errors']['commentname'] = "You must enter a name.";
		$hasError = true;	
	} else {
		$commentname = $_POST['commentname'];
	}
	
		if(empty($_POST['commentemail']))  {	
		$_SESSION['errors']['commentemail'] = "You must enter an email.";
		$hasError = true;	
	} else {
		$commentemail = $_POST['commentemail'];
	}
	
		if(empty($_POST['commentcontent']))  {	
		$_SESSION['errors']['commentcontent'] = "You must enter content.";
		$hasError = true;	
	} else {
		$commentcontent = $_POST['commentcontent'];
	}

		if(isset($hasError)){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode($_SESSION['errors']);
                exit;
	}} else {
	
	$global = new DB_global;
	$parsed_link = parse_url ($_SERVER['HTTP_REFERER']);
	$link = str_replace("/", "", $parsed_link['path']);
	$linkinit = $global->sqlquery("SELECT content_id FROM dd_content WHERE content_permalink = '".$link."';");
	$post_id = $linkinit->fetch_assoc();
	$comment_post_id = $post_id['content_id'];
	
	
	$userstatus = $global->sqlquery("SELECT * FROM dd_users WHERE user_id = '".$_COOKIE['userID']."';");
	$userstatus2 = $userstatus->fetch_assoc();
	
	if ($userstatus2['user_isadmin'] = '1'){
	$commentstatus = "'1', '0',";
	} else if ($userstatus2['user_isadmin'] = '1'){
	$commentstatus = "'0', '1',";
	}
	
	if ($check->isLoggedIn()){
		$userid = $_COOKIE['userID'];
	}
	
	$comment_id = $global->sqllastid("INSERT INTO `dd_comments` (`comment_id`, `comment_postid`, `comment_username`, `comment_email`, `comment_date`, `comment_content`, `comment_ip`, `comment_reported`, `comment_isfromadmin`, `comment_isfromcontributor`, `comment_userid`) VALUES (NULL, '".$comment_post_id."', '".$commentname."', '".$commentemail."', CURRENT_TIMESTAMP, '".$commentcontent."', '".$_POST[commentip]."', '', ".$commentstatus." '".$userid."')");
	
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	   $_SESSION['resp']['formrefresh'] = true;
       echo json_encode($_SESSION['resp']);
       exit;
	}
	}}
}