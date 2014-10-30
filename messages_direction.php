<?php
if(isset($_GET['user_id']) && $_GET['user_id'] != ''){
echo $_GET['user_id'];
$messages=ThemexUser::getMessagesInbox(ThemexUser::$data['user']['ID'], get_query_var('message'), themex_paged());
	var_dump($messages);
}

?>