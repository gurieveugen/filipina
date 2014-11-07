<?php

class Notifications{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const FIELD_VIEWS = 'views';	
	const FIELD_FAVORITES = 'favorites';                                                 
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
		add_action( 'wp_ajax_getViews', array( &$this, 'getViewsAJAX' ) );
		add_action( 'wp_ajax_clearViews', array( &$this, 'clearViewsAJAX' ) );
		add_action( 'wp_ajax_getFavorites', array( &$this, 'getFavoritesAJAX' ) );
		add_action( 'wp_ajax_clearFavorites', array( &$this, 'clearFavoritesAJAX' ) );
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
	 * Get profile views [AJAX]
	 */
	public function getViewsAJAX()
	{
		$json  = array();
		$views = self::getViews();
		if(count($views))
		{
			foreach ($views as $viewer) 
			{
				$user = get_user_by( 'id', $viewer );
				if($user !== false)
				{
					$json[] = array(
						'avatar' => get_avatar($user->data->ID, 60),
						'age'    => '',
						'name'   => $user->data->display_name,
						'url'    => sprintf(
							'%s/message/%d',
							get_bloginfo('url'),
							$user->data->ID
						)
					);	
				}
			}
		}
		echo json_encode($json);
		die();
	}

	/**
	 * Clear views [AJAX]
	 */
	public function clearViewsAJAX()
	{
		self::clearViews(get_current_user_id());
	}

	/**
	 * Clear Favorites [AJAX]
	 */
	public function clearFavoritesAJAX()
	{
		self::clearFavorites(get_current_user_id());
	}

	/**
	 * Get favorites [AJAX]
	 */
	public function getFavoritesAJAX()
	{
		$json  = array();
		$favorites = self::getFavorites();
		if(count($favorites))
		{
			foreach ($favorites as $favorite) 
			{
				$user = get_user_by( 'id', $favorite );
				if($user !== false)
				{
					$json[] = array(
						'avatar' => get_avatar($user->data->ID, 60),
						'age'    => '',
						'name'   => $user->data->display_name,
						'url'    => sprintf(
							'%s/message/%d',
							get_bloginfo('url'),
							$user->data->ID
						)
					);	
				}
			}
		}
		echo json_encode($json);
		die();
	}

	/**
	 * Get profile views
	 * @return array --- profile views id's
	 */
	public static function getViews()
	{
		$views = (array) get_user_meta( get_current_user_id(), self::FIELD_VIEWS, true );
		return $views;
	}

	/**
	 * Get recently added to favorites list
	 * @return array --- recently favorites list
	 */
	public static function getFavorites()
	{
		$favorites = (array) get_user_meta( get_current_user_id(), self::FIELD_FAVORITES, true );
		return $favorites;
	}

	/**
	 * Add favorites to list
	 * @param integer $id --- owner id
	 */
	public static function addFavorites($id)
	{
		$favorites = (array) get_user_meta( $id, self::FIELD_FAVORITES, true );
		$favorites = self::deleteEmpty( $favorites );
		if( !in_array(get_current_user_id(), $favorites))
		{
			array_push( $favorites, get_current_user_id() );	
		}
		update_user_meta( $id, self::FIELD_FAVORITES, $favorites );
	}

	/**
	 * View profile
	 * @param  integer $owner --- owner id
	 */
	public static function viewProfile($owner)
	{
		$views = (array) get_user_meta( $owner, self::FIELD_VIEWS, true );
		$views = self::deleteEmpty( $views );
		if( !in_array(get_current_user_id(), $views))
		{
			array_push( $views, get_current_user_id() );	
		}
		update_user_meta( $owner, self::FIELD_VIEWS, $views );
	}

	/**
	 * Delete empty elements from array
	 * @param  array $arr --- haystack
	 * @return array --- cleaned array
	 */
	public static function deleteEmpty($arr)
	{      
	   $empty_elements = array_keys($arr, '');      
	   foreach ($empty_elements as &$e) unset($arr[$e]);

	   return $arr;
	}

	/**
	 * Clear all views in user profile
	 * @param  integer $owner --- owner id
	 */
	public static function clearViews($owner)
	{
		update_user_meta( $owner, self::FIELD_VIEWS, array() );
	}

	/**
	 * Clear all favorites in user profile
	 * @param  integer $owner --- owner id
	 */
	public static function clearFavorites($owner)
	{
		update_user_meta( $owner, self::FIELD_FAVORITES, array() );
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