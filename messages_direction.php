<?php 

if(isset($_POST['comment_id_delete_messages']) && $_POST['comment_id_delete_messages'] != ''){
	$comment_id = (int)trim($_POST['comment_id_delete_messages']);
	$user_id = (int)trim($_POST['user_id']);
	$owner = (int)trim($_POST['owner']);
	if (is_numeric($comment_id)) {
		$messages = ThemexUser::messagesDelete($comment_id, $user_id, $owner);
	}
}


if(isset($_POST['comment_id_read_messages']) && $_POST['comment_id_read_messages'] != ''){
	$comment_id = (int)trim($_POST['comment_id_read_messages']);
	$user_id = (int)trim($_POST['user_id']);
	if (is_numeric($comment_id)) {
		$messages = ThemexUser::messagesRead($comment_id, $user_id);
	}
}

if(isset($_POST['comment_id_unread_messages']) && $_POST['comment_id_unread_messages'] != ''){
	$comment_id = (int)trim($_POST['comment_id_unread_messages']);
	$user_id = (int)trim($_POST['user_id']);
	if (is_numeric($comment_id)) {
		$messages = ThemexUser::messagesUnread($comment_id, $user_id);
	}
}




?>