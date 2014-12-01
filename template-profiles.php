
<?php
/*
Template Name: Profiles
*/

$users=ThemexUser::getUsers(array(
	'number' => ThemexCore::getOption('user_per_page', 9),
	'offset' => ThemexCore::getOption('user_per_page', 9)*(themex_paged()-1),
));

?> 
<?php get_header(); 
if(isset($_POST['message_mass_send']) && $_POST['message_mass_send'] == "send_on"){
	echo ThemexUser::sendMassMessage($users, ThemexUser::$data['user'], trim($_POST['message_text']));	
}
if(isset($_POST['boost_visibility_send']) && $_POST['boost_visibility_send'] == "send_on"){
	$location = array('country' => $_POST['country'], 'city' => $_POST['city'], 'credit' => $_POST['credit'], 'time' => $_POST['time'], 'type' => $_POST['type'], 'gender' => $_POST['gender']);
	echo ThemexUser::boostVisibility($location, ThemexUser::$data['user']);
}

?>
<div class="filtered-search">
<?php //dynamic_sidebar('filter-search'); 
	get_template_part('topsearch-form');
?>
</div>
<!--<div class="feature_profile">
	Feature your profile!
</div>

<div class="drop_bomb">
	Drop a Bomb Message
</div>-->

<div id="drop_bomb_window" style="display:none;">
	<div id="count_users_search" style="display:none;"><?php echo count($users); ?></div>
	<div class="header_bomb_message">
		Be seen by millions!
	<div id="close_bomb">
	</div>
	</div>	
	<table>
		<?php if(!ThemexCore::checkOption('user_location')) { ?>
				<tr>
					<td><h5><?php _e('Select a country', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'country',
								'type' => 'select',
								'options' => array_merge(array('0' => '&ndash;'), ThemexCore::$components['countries']),
								'value' => isset($_GET['country'])?$_GET['country']:null,
								'attributes' => array('class' => 'countries-list-bomb'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td><h5><?php _e('Select a city', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'city',
								'type' => 'select_city',
								'value' => isset($_GET['city'])?$_GET['city']:'',
								'attributes' => array(
									'class' => 'filterable-list',
									'id' => 'city',
									'data-filter' => 'countries-list-bomb',
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td><h5><?php _e('Who do you want to reach?', 'lovestory'); ?></h5></td>
				<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'reach',
								'type' => 'select',
								'options' => array('0' =>'-', '1'=>'man', '2'=>'woman'),
								'value' => array('0' =>'-', '1'=>'man', '2'=>'woman'),
								'attributes' => array(
									'class' => 'reach-list',
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
			</tr>
			
			<tr>
				<td colspan="2"><h5><?php _e('Write your message', 'lovestory'); ?></h5></td>
			</tr>
			<tr>
				<td colspan="2">
						<div class="textarea-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'message',
								'type' => 'textarea',
								'namne' => 'message',
								'wrap' => true,
							));
							?>
						</div>
					</td>
			</tr>
			
			<tr>
				<td colspan="2" ><div id="send_message_bomb"></div> </td>
			</tr>
			<tr>
				<td colspan="2" id="credit_td"><div id="credit_bomb_message"><div>Credits: </div><div id="credits_bomb_message">0</div></div></td>
			</tr>
			<tr>
				<td colspan="2"><div id="continue_bomb_message" onclick="continue_bomb_message();">Continue</div></td>
			</tr>
	</table>
</div>


<!-- feature_profile -->


<div id="feature_profile_window" style="display:none;">

	<div class="header_bomb_message">
		Be seen by millions!
			<div id="close_profile">
	</div>
	</div>

	
	<table>
		<?php if(!ThemexCore::checkOption('user_location')) { ?>
				<tr>
					<td><h5><?php _e('Select a country', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'country',
								'type' => 'select',
								'options' => array_merge(array('0' => '&ndash;'), ThemexCore::$components['countries']),
								'value' => isset($_GET['country'])?$_GET['country']:null,
								'attributes' => array('class' => 'countries-list-feature'),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td><h5><?php _e('Select a city', 'lovestory'); ?></h5></td>
					<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'city',
								'type' => 'select_city',
								'value' => isset($_GET['city'])?$_GET['city']:'',
								'attributes' => array(
									'class' => 'filterable-list',
									'id' => 'city',
									'data-filter' => 'countries-list-feature',
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td><h5><?php _e('Profile Featured Duration', 'lovestory'); ?></h5></td>
				<td>
						<div class="select-field">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'reach',
								'type' => 'select',
								'options' => array('0' =>'-', '1'=>'Day', '2'=>'Month', '3' => 'Year'),
								'value' => array('0' =>'-', '1'=>'Day', '2'=>'Month', '3' => 'Year'),
								'attributes' => array(
									'class' => 'reach-list',
								),
								'wrap' => false,
							));
							?>
						</div>
					</td>
			</tr>
			<tr>
				<td colspan="2">
				 <div class="nstSlider" data-range_min="0" data-range_max="10" 
                       data-cur_min="0">
					<div class="leftGrip"></div>
				</div>
				<div class="leftLabel" />
			
				</td>
			</tr>	
			
			<tr>
				<td colspan="2" id="credit_td"><div id="credit_bomb_message"><div>Credits: </div><div id="credits_bomb_message" class="credit_boost_visibility">0</div></div></td>
			</tr>
			<tr>
				<td colspan="2"><div id="continue_bomb_message" onclick="continue_boost_visibility();">Continue</div></td>
			</tr>
	</table>
</div>
<!-- end feature_profile -->
<div class="profile-lists">
	<?php if(!empty($users)) { ?>
 	<div class="profiles-listing clearfix">
		<?php
		$counter=0;
		
		foreach($users as $user) {		
		ThemexUser::$data['active_user']=ThemexUser::getUser($user->ID);$counter++;
		?>
        	<?php if($counter==1):?>
            <section>
			<?php endif;?>
                <div class="column twocol prof-box">
                <?php get_template_part('content', 'profile-grid'); ?>
                </div>
                <?php		
                if($counter==7) {
                $counter=0;
                ?>
			</section>
			<?php } ?>
		<?php } ?>
	</div>
	<!-- /profiles -->
	<?php ThemexInterface::renderPagination(themex_paged(), themex_pages(ThemexUser::getUsers(), ThemexCore::getOption('user_per_page', 9))); ?>
	<?php } else { ?>
	<h3><?php _e('No profiles found. Try a different search?','lovestory'); ?></h3>
	<p><?php _e('Sorry, no profiles matched your search. Try again with different parameters.','lovestory'); ?></p>
	<?php } ?>
</div>
<script src="<?php echo THEME_URI; ?>slideBoost/rangeslider.min.js"></script>
<?php get_footer(); ?>
