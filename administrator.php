<?php
/*
Template Name: administrator
*/
?>

<?php
@session_start();
$messages = "";
//$_SESSION['administrator']['login'] = "a"; 
if(isset($_POST['admUsername']) && isset($_POST['admPassword'])): 
			$messages = ThemexUser::loginAdmin(trim($_POST['admUsername']), trim($_POST['admPassword']));
endif;

	if($_SESSION['administrator']['login'] !== "credentials"){
		
			
?>
	<style type="text/css">
		#form_log{
			width:20%;
			margin-right:auto;
			margin-left: auto;
			margin-top: 150px;
		}

		#form_log tr:first-child{
			text-align: center;
			font-family: Arial;
			font-size: 16px;
		}

		#form_log tr:last-child{
			text-align: center;
		}
	</style>
	<form id="form_log" method="POST" action="">
    <table> 
        <tr>
            <td colspan="2"><?php echo $messages; ?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>            
            <td>
                Username
            </td>
            <td>
                <input type="text" name="admUsername"/> 
            </td>
        </tr>
        <tr>            
            <td>
                Password 
            </td>
            <td>
                <input type="password" name="admPassword" />
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>     
            <td colspan="2">
                <input type="submit" value="Login" />
            </td>
        </tr>
    </table>
</form>

<?php	
		die();
	}
?>
<?php
	if(isset($_POST['user_id']) && isset($_POST['admin']) && $_POST['admin'] == "add_on"){
		ThemexUser::boostVisibilityAdmin(trim($_POST['user_id']));
	
	}else if(isset($_POST['user_id']) && isset($_POST['admin']) && $_POST['admin'] == "remove_on"){
		ThemexUser::boostVisibilityAdminR(trim($_POST['user_id']));
	}
?>
<?php get_header(); ?>

<?php
$users=ThemexUser::getUsers(array(
	'number' => ThemexCore::getOption('user_per_page', 9),
	'offset' => ThemexCore::getOption('user_per_page', 9)*(themex_paged()-1),
));
?>

 <div id="profileAdmin" class="column eightcol" style="width:100%;">
 <div class="profile-search-username-form">
	<form action="<?php echo "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" method="GET" >
		<table style="border:0px;">
			<tr>
				<td style="border:0px; width:200px; vertical-align:middle;"><b style="position:relative; top:-5px; margin-top:-10px; font-family:Arial; font-size:18px;">Search by Username</b></td>
				<td style="border:0px; width:200px;"><input type="text" name="username" /></td><td style="border:0px;">
					<a href="#" class="button medium submit-button" style="background-color:#48A9D0; top:-3px;"><span class="button-icon icon-search"></span><?php _e('Go', 'lovestory'); ?></a>
				<input type="hidden" name="s" value="" /></td>
				
			</tr>
		</table>
	</form>
</div>

 	<div class="profiles-listing clearfix" style="width:100%;">
		<?php
		$counter=0;
		
		foreach($users as $user) {		
		//ThemexUser::$data['active_user']=ThemexUser::getUser($user->ID);
		
		$counter++;
		$color ="#F0708F";
		if(ThemexUser::boostVisibilityAdminShow($user->ID))
		$color = "#4DABD1";
		?>
			<div class="featured-user-avatar" style="width:210px; float:left; margin-left:10%; margin-bottom:4%; border:4px solid <?php echo $color; ?>">
				<?php ThemexUser::$data['active_user']=ThemexUser::getUser($user->ID); ?>
				<article class="featured-profile" style="width:200px;">
					<div class="profile-wrap">
				<div class="profile-preview" style="word-wrap: break-word; width:100%;">
					<div class="profile-image" style="width:200px;">
						<a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>"><?php echo get_avatar(ThemexUser::$data['active_user']['ID'], 250); ?></a>
					</div>
					<div class="profile-text" style="width:200px;">
						<h5 style="word-wrap: break-word; width:180px;"><?php //get_template_part('module', 'status'); ?><a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>" style="word-wrap: break-word; width:200px;"><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></a></h5>
						<p><?php echo ThemexUser::getExcerpt(ThemexUser::$data['active_user']['profile']); ?></p>
					</div>
				</div>
					</div>
				</article>
			
			<div id="button_admin_featured_add" onclick="addFeaturedAdmin(<?php echo $user->ID; ?>);">Add Featured</div>
		<div id="button_admin_featured_remove" onclick="removeFeaturedAdmin(<?php echo $user->ID; ?>);">Remove Featured</div>
			</div>
			<?php		
			if($counter==3) {
			$counter=0;
			?>
			<div class="clear"></div>
			<?php } ?>
		<?php } ?>
		
	</div>
	
	<!-- /profiles -->
	<?php ThemexInterface::renderPagination(themex_paged(), themex_pages(ThemexUser::getUsers(), ThemexCore::getOption('user_per_page', 9))); ?>

</div>