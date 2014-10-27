<?php error_reporting( E_ALL ); ?>
<?php 
require_once('../../../../wp-load.php' );
if ( ! empty($_POST['data']) ) {
		$data = wp_unslash( (array) $_POST['data'] );
		$data_string=str_replace('&','","',$data[0]);
		$data_string=str_replace('=','":"',$data_string);
		$data_string='{"'.$data_string.'"}';		
		$user_data=(json_decode($data_string,true));
		insert_wp_user($user_data);
		//echo $user_data['user_login'];
}
function insert_wp_user($arr_data)
{
	global $wpdb;
	$reg_errors='';
	$insert_wp_user=wp_create_user($arr_data['user_login'], $arr_data['user_password'],urldecode($arr_data['user_email']));	
	if($insert_wp_user==NULL):
		echo 'Error in saving profile';
	elseif(is_object($insert_wp_user)):
		foreach($insert_wp_user as $error):
			//echo print_r($error);
			if($error['empty_user_login'][0]!=''):				
				$reg_errors.='<li>'.$error['empty_user_login'][0].'</li>';
			elseif($error['existing_user_login'][0]):
				$reg_errors.='<li>'.$error['existing_user_login'][0].'</li>';				
			elseif($error['existing_user_email'][0]):
				$reg_errors.='<li>'.$error['existing_user_email'][0].'</li>';
			endif;
		endforeach;
	elseif($arr_data['user_email']==''):
		$reg_errors.='<li>You missed your email address!</li>';
	elseif($arr_data['user_password']==''):
		$reg_errors.='<li>Password is emtpy</li>';
	elseif($arr_data['user_password']!=$arr_data['user_password_repeat']):
		$reg_errors.='<li>Password not matched</li>';
	else:		
		insert_wp_user_meta($arr_data, $insert_wp_user);		
	endif;	
	if($reg_errors!=''):
		$reg_errors='<ul class="error">'.$reg_errors.'</ul>';
		echo $reg_errors;
	endif;
}
function insert_wp_user_meta($arr_data,$wp_user_id)
{
	global $wpdb;
	$themx_ctr=0;
	$wp_meta_ctr=0;
	$usermeta_format=array('%d','%s','%s');
	$themex_meta_values=array($arr_data['age'],$arr_data['seeking'],$arr_data['gender'],
							  $arr_data['country'],$arr_data['city'],'',
							  '',date('Y-m-d'));
	$themex_meta_keys=array('_themex_age','_themex_seeking','_themex_gender',
							'_themex_country','_themex_city','_themex_phone-number',
							'_themex_load-promo-code','_themex_updated');	
	while($themx_ctr<count($themex_meta_keys)):
		$insert_meta=array('user_id'=>$wp_user_id,'meta_key'=>$themex_meta_keys[$themx_ctr],'meta_value'=>$themex_meta_values[$themx_ctr]);
		$wpdb->insert('wp_usermeta',$insert_meta,$usermeta_format);
		$themx_ctr++;
	endwhile;
	$update_first_name=array('meta_value'=>$arr_data['first_name']);
	$wpdb->update('wp_usermeta', $update_first_name, array('user_id'=>$wp_user_id,'meta_key'=>'first_name'),'%s',array('%d','%s'));
	
	$update_last_name=array('meta_value'=>$arr_data['last_name']);
	$wpdb->update('wp_usermeta', $update_last_name, array('user_id'=>$wp_user_id,'meta_key'=>'last_name'));
	
	$secure_cookie = is_ssl() ? true : false;
	wp_set_auth_cookie($wp_user_id, true, $secure_cookie );
	_execute_popupupload_form(SITE_URL.'/profile/'.$arr_data['user_login']);
	//echo '<ul class="success"><li><a href="'.SITE_URL.'/profile/'.$arr_data['user_login'].'/" class="redirect"></a></li></ul>';
}
function hash_password($raw_pass)
{
	return wp_hash_password($raw_pass);
}
function send_new_registered_mail($registered_email=NULL)
{
	if($registered_email!=NULL):
		wp_mail( $to, $subject, $message, $headers, $attachments );
	else:
		continue;
	endif;
}
die();
?>
<?php function _execute_popupupload_form($upload_redirect){?>
<script type="text/javascript">
	$=jQuery;
	$(document).ready(function(){   
	   $('#upload-image-form .upload-form').attr('action','<?php echo $upload_redirect;?>')   
	   $.colorbox({html:$('#upload-image-form').html()});
    });
</script>
<?php }?>