<?php
/**
 * Template name: all messagesssss
 */

if(get_current_user_id() != 411251 AND !is_admin()) wp_redirect( get_bloginfo( 'url' ) );
?>
<?php get_header(); ?>
<form action="" method="POST">
	<input type="text" name="user_ids">
	<input type="submit" value="sumbit">
	<label for="user_ids">You need type user ids separated by commas. Like this: 1,2,3,4</label>
</form>
<div id="paginate"></div>
<div class="inbox_p">
<?php
$messages = new Messages($_POST);
$response = $messages->getMessages();
$messages = '';

if( count( $response ) )
{
	foreach ($response as $message) 
	{
		$msg = new MessageHTML($message);
		$messages .= $msg->getHTML();
	}
	printf('<ul class="messages">%s</ul>', $messages);
}
else
{
	echo '<p>No messages received yet.</p>';
}
?>
</div>
<?php get_footer(); ?>
