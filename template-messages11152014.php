<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="full-profile fivecol column">
	<div class="section-title">
		<h2><?php _e('My Messages', 'lovestory'); ?></h2>
	</div>
	<?php 
	$recipients=ThemexUser::getRecipients(ThemexUser::$data['user']['ID']); 
	if(!empty($recipients)) {
	?>
	<ul class="bordered-list">
		<?php 
		foreach($recipients as $recipient) {
		ThemexUser::$data['active_user']=ThemexUser::getUser($recipient['ID']);
		?>
		<li class="clearfix">
			<div class="static-column twelvecol message-mini-content">
				<h4>                	
                    <article>
					<?php get_template_part('module', 'status'); ?>
                    	<a href="<?php echo ThemexUser::$data['active_user']['profile_url']; ?>">
                        <!-- for image--> 
                        <?php echo get_avatar(ThemexUser::$data['active_user']['ID'], 50); ?>
                        <!-- end for image-->
						<aside><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></aside>
                        </a>
                    </article>
                    <article>
                        <!-- for date-->
                        <span> <?php 
						//echo 'first name '.print_r(ThemexUser::$data['active_user']['profile']);
						//echo 'ID '.ThemexUser::$data['active_user']['ID'];
                        $abc= ThemexUser::$data['active_user']['profile']['first_name'];
                        $data=mysql_query("select * from wp_users where display_name='$abc' ");
                        $array=mysql_fetch_array($data);
						
                        $u_id=$array['ID'];         
						$message_status='';               
                        //$ans=mysql_query("select * from wp_comments where user_id='$u_id' Order by comment_ID DESC ");
						$ans=mysql_query("select * from wp_comments where (comment_parent=".ThemexUser::$data['user']['ID'].
										" AND user_id=".ThemexUser::$data['active_user']['ID'].")".
										"OR (comment_parent=".ThemexUser::$data['active_user']['ID'].
											" AND user_id=".ThemexUser::$data['user']['ID'].") AND comment_type='message' Order by comment_ID DESC ");						
                        $arr=mysql_fetch_array($ans);						
                        $timestamp= $arr['comment_date'];
                        $date = date('Y-m-d',strtotime($timestamp));
                        echo $date;
                        ?>
                        </span> 
                        <!--end for date-->
                        
                    </article>
                </h4>
                <section>
					<?php $res2= $arr['comment_content'];
                          $res3=strlen($res2);
						  $res_number='';
                        if($res3<100){$res_number=strip_tags($res2);}else{$res5=substr("$res2",0,100);$res_number=strip_tags($res5);}                        
                    ?>
                	<?php 
					if($recipient['unread']>0):?>
                    <a href="<?php echo ThemexUser::$data['active_user']['message_url']; ?>">
                    <b>
                    	<?php
						if($arr['comment_karma']==0):							
							echo '<span class="icon-upload"></span> Sent...';
						else:
							echo $res_number.'...';
						endif;
						?>						
                    </b>
                    </a>
                    <?php else: ?>
                    <span>
                    <a href="<?php echo ThemexUser::$data['active_user']['message_url']; ?>">
                    	<?php
						if($arr['comment_karma']==0):							
							echo '<span class="icon-upload"></span> Sent...';
						else:
							echo $res_number.'...';
						endif;
						?>
                    </a>
                    </span>
                    <?php endif;					
					?>                   
                </section>
			</div>
			<?php if($recipient['unread']>0) { ?>
			<div class="static-column twocol last profile-value"><?php echo $recipient['unread']; ?></div>
			<?php } ?>
		</li>
		<?php 
		}
		ThemexUser::refresh();
		?>
	</ul>
	<?php } else { ?>
	<p class="secondary"><?php _e('No messages received yet.', 'lovestory'); ?></p>
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>