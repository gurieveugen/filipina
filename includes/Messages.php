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

	public function getMessages()
	{
		$result = array();

		if(!isset($this->response['user_ids'])) return $result;

		$query = sprintf('
			SELECT * FROM %1$s as c
			WHERE c.comment_parent IN(%2$s)
			AND c.comment_type = \'message\'
			AND c.trash=0
			AND c.draft=0
			ORDER BY c.comment_date DESC',
			$this->db->comments,
			mysql_real_escape_string($this->response['user_ids'])
		);
		$this->last_query = $query;
		return $this->db->get_results($query);
	}    

	public function getLastQuery()
	{
		return $this->last_query;
	} 

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