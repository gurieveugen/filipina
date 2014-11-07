<?php

class Messages{
	//                                       __  _          
	//     ____  _________  ____  ___  _____/ /_(_)__  _____
	//    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
	//   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
	//  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
	// /_/              /_/                                 
	private $response;
	private $db;
	private $last_query;
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct($response)
	{
		global $wpdb;
		$this->response = $response;
		$this->db = $wpdb;
	}	 

	/**
	 * Get all messsages count
	 * @return integer
	 */
	public function getDBCount()
	{
		if(!isset($this->response['user_ids']) OR !strlen($this->response['user_ids']))
		{
			$this->response['user_ids'] = (string) get_option( 'gs_fake_users' );
		}

		$query = sprintf('
			SELECT COUNT(*) FROM %1$s as c
			WHERE c.comment_parent IN(%2$s)
			AND c.comment_type = \'message\'
			AND c.trash=0
			AND c.draft=0
			ORDER BY c.comment_date DESC',
			$this->db->comments,
			mysql_real_escape_string($this->response['user_ids'])
		);
		return $this->db->get_var($query);
	}       

	/**
	 * Get total pages
	 * @return integer --- total pages
	 */
	public function getTotal()
	{
		return ceil($this->getDBCount()/$this->getCount());
	}  

	/**
	 * Get messages limit count
	 * @return integer --- count
	 */
	public function getCount()
	{
		return (int) get_option( 'gs_messages_per_page' );
	}        

	/**
	 * Get meesages limit offset
	 * @return integer --- offset
	 */
	public function getOffset()
	{
		return ($this->getCurrentPage() - 1)*$this->getCount();
	}

	/**
	 * Get current page
	 * @return integer --- current page
	 */
	public function getCurrentPage()
	{
		return max(1, get_query_var('paged'));
	}

	/**
	 * Get pagination
	 * @return string --- HTML code
	 */
	public function getPagination()
	{
		$args = array(
			'base' => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
			'total' => $this->getTotal(),
			'current' => $this->getCurrentPage(),
			'mid_size' => 5,
			'end_size' => 1,
			'prev_text' => '',
			'next_text' => ''
		);
		
		$out = paginate_links($args);
		if($out!='') 
		{
			$out='<nav class="pagination">'.$out.'</nav>';
		}
		return $out;
	}

	/**
	 * Get messages list
	 * @return array --- messages list
	 */
	public function getMessages()
	{
		$result = array();
		if(!isset($this->response['user_ids']) OR !strlen($this->response['user_ids']))
		{
			$this->response['user_ids'] = (string) get_option( 'gs_fake_users' );
		}

		$query = sprintf('
			SELECT * FROM %1$s as c
			WHERE c.comment_parent IN(%2$s)
			AND c.comment_type = \'message\'
			AND c.trash=0
			AND c.draft=0
			ORDER BY c.comment_date DESC
			LIMIT %3$d,%4$d',
			$this->db->comments,
			mysql_real_escape_string($this->response['user_ids']),
			$this->getOffset(),
			$this->getCount()
		);
		$this->last_query = $query;
		return $this->db->get_results($query);
	}    

	/**
	 * Get last query
	 * @return string --- last sql query
	 */
	public function getLastQuery()
	{
		return $this->last_query;
	} 

	/**
	 * Add comment
	 * @param integet $owner --- owner id
	 * @param integer $target --- target id
	 * @param string $msg --- message
	 */
	public static function add($owner, $target, $msg)
	{
		$user = get_user_by( 'id', $owner );
		if( !strlen( $msg ) ) return false;
		return wp_insert_comment(
			array(
				'comment_post_ID' => 0,
				'comment_karma' => 0,
				'comment_type' => 'message',
				'comment_parent' => $target,
				'user_id' => $owner,
				'comment_owner' => (int)$target,
				'comment_author' => $user->data->user_login,
				'comment_author_email' => $user->data->user_email,
				'comment_content' => wp_kses(
					$msg, 
					array(
						'strong' => array(),
						'em' => array(),
						'a' => array(
							'href' => array(),
							'title' => array(),
							'target' => array(),
						),
					'p' => array(),
					'br' => array(),
					)
				),
			)
		);
	}                      
}