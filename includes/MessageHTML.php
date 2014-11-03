<?php

class MessageHTML{
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $message;
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($message)
	{
		$this->message = $message;
	}

	public function getHTML()
	{
		$to = get_user_by( 'id', $this->message->comment_parent );
		$img = get_avatar($this->message->user_id, 60);
		ob_start();
		?>
		<li>
			<div class="avatar">
				<?php echo $this->wrapA( $img ); ?>
			</div>
			<div class="content">
				<div class="authors">
					<span><b>From:</b> <?php echo $this->message->comment_author; ?></span>
					<span><b>To:</b>  <?php echo $to->data->user_login; ?> </span>
				</div>
				<div class="time">
					<?php echo $this->wrapA( date("F j, Y, g:i a", strtotime($this->message->comment_date) ) ); ?>
				</div>
				<div class="txt">
					<?php echo $this->wrapA( $this->message->comment_content ); ?>
				</div>
			</div>
		</li>
		<?php
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	private function wrapA($txt)
	{
		$url = sprintf(
			'%s/msg?owner=%d&to=%d&msg=%d',
			get_bloginfo( 'url' ),
			$this->message->user_id,
			$this->message->comment_parent,
			$this->message->comment_ID
		);
		return sprintf(
			'<a href="%s">%s</a>',
			$url, $txt
		);
	}
}