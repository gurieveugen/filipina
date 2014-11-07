<?php
/*
Template Name: Profiles
*/

$users=ThemexUser::getUsers(array(
	'number' => ThemexCore::getOption('user_per_page', 9),
	'offset' => ThemexCore::getOption('user_per_page', 9)*(themex_paged()-1),
));
?>
<?php get_header(); ?>
<div class="column eightcol">	
	<?php if(!empty($users)):
		$ctr=1;
		?><!-- CHECK IF THERE ARE USERS EXISTS -->
	<div class="profile-listing">	
        <?php foreach($users as $user):?>
        <?php ThemexUser::$data['active_user']=ThemexUser::getUser($user->ID);?>
            <div class="column fourcol <?php echo ($ctr%3)==0 ? 'last' : ''?>">
                <?php get_template_part('content', 'profile-grid'); ?>
            </div>
            <?php if(($ctr%3)==0):?>
            	<div class="clear"></div>
            <?php endif;?>
        <?php $ctr++; endforeach;?>
    </div>
    <?php 
	//echo phpinfo();	
		//ThemexInterface::renderPagination(themex_paged(), themex_pages(ThemexUser::getUsers(), ThemexCore::getOption('user_per_page', 9))); 		
		//echo 'paged '.themex_paged().' users: '.ThemexUser::getUsers();
		//echo count(ThemexUser::getUsers()).' '.ThemexCore::getOption('user_per_page', 9);
		ThemexInterface::renderPagination(themex_paged(), 50); // 300 FOR THE MEANTIME TO AVOID EXHAUSTION OF SERVER
		?>     
	<?php else:?>
		<h3><?php _e('No profiles found. Try a different search?','lovestory'); ?></h3>
		<p><?php _e('Sorry, no profiles matched your search. Try again with different parameters.','lovestory'); ?></p>	
    <?php endif;?><!-- END CHECK IF THERE ARE USERS EXISTS -->  
</div>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php get_footer(); ?>