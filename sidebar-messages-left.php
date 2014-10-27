<?php $messages=ThemexUser::getMessagesInbox(ThemexUser::$data['user']['ID'], get_query_var('message'), $page); ?>
<aside class="column threecol">
	<div id="header_title_message">
		<h2>MESSAGES</h2>
	</div>
	<div class="widget profile-menu">
		<div id="menu_messages">
			<div id="inbox_click">Inbox(<?php echo count($messages); ?>)</div>
			<!--<div>Filtered Mail</div>
			<div id="draft_click">Drafts</div>-->
			<div id="sent_click">Sent</div>
			<div id="trash_click">Trash</div>
		</div>
	</div>

</aside>
