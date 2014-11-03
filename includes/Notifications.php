<?php

class Notifications{
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $db;
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		// ==============================================================
		// Actions & Filters
		// ==============================================================
		add_action( 'wp_enqueue_scripts', array( &$this, 'scriptsAndStyles' ) );
		add_action( 'wp_ajax_getCounters', array( &$this, 'getCountersAJAX' ) );
		add_action( 'wp_ajax_nopriv_getCounters', array( &$this, 'getCountersAJAX' ) );
		add_action( 'wp_ajax_setReadedStatus', array( &$this, 'setReadedStatusAJAX' ) );
		add_action( 'wp_ajax_nopriv_setReadedStatus', array( &$this, 'setReadedStatusAJAX' ) );
	}

	/**
	 * Add some scripts and styles
	 */
	public function scriptsAndStyles()
	{
		// ==============================================================
		// Scripts
		// ==============================================================
		wp_enqueue_script( 'string-format', THEME_URI.'js/string.format.js', array('jquery') );
		wp_enqueue_script( 'notifications', THEME_URI.'js/notifications.js', array('jquery') );
		wp_localize_script( 
			'notifications', 
			'defaults',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'theme_uri' => get_template_directory_uri(),
			)
		);
	}

	/**
	 * Get counter AJAX query
	 */
	public function getCountersAJAX()
	{
		$id  = get_current_user_id();
		$ids = array();
		$query = sprintf("
				SELECT * FROM %s as c
				WHERE c.comment_parent = %d 
				AND c.comment_karma = '0'
				AND c.trash=0
				AND c.draft=0
				AND c.comment_type = 'message' 
				ORDER BY c.comment_date DESC", 
				$this->db->comments, $id, $id
		);
		$messages = $this->db->get_results($query);

		if(count($messages))
		{
			foreach ($messages as &$message) 
			{
				$message->avatar  = get_avatar($message->user_id, 60);
				$message->age     = '';
				$message->country = '';
				$message->city    = '';
				$message->url     = sprintf(
					'%s/message/%d',
					get_bloginfo('url'),
					$message->user_id
				);
				array_push( $ids, intval( $message->comment_ID ) );
			}
		}

		$json = array(
			'query' => $query,
			'count' => count($messages),
			'id'    => $id,
			'ids'   => $ids,
			'messages' => $messages
		);

		echo json_encode($json);
		die();
	}

	/**
	 * Set readed status to unread messages
	 */
	public function setReadedStatusAJAX()
	{
		$ids = (array) $_POST['ids'];
		if(!count($ids)) die();
		$query = sprintf(
			'UPDATE %s c SET c.comment_karma = \'1\' WHERE c.comment_ID in(%s)',
			$this->db->comments,
			implode(',', $ids)
		);
		$json = array(
			'query' => $query,
			$this->db->query($query)
		);
		echo json_encode($json);
		die();
	}
}
// ==============================================================
// Launch
// ==============================================================
$notifications = new Notifications();