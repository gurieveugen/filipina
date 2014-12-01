<?php 
	$recipients=ThemexUser::getRecipients(ThemexUser::$data['user']['ID']); 
	if(!empty($recipients)) {
		$container_ctr=1;
		$container_suffix=0;
		$end_limit=5;
        $done_paginate_this=true;
        $page_button='';
	?>    
    <div class="pagination top-pagination clearfix">
        <nav class="pagination" id="messages-container">
        </nav>
    </div>
    <?php foreach($recipients as $recipient):?>
    <!-- PAGINATION -->
    <!-- END PAGINATION -->
    <?php if($container_ctr==1 || $container_suffix==count($recipients) ):
        $page_button_number=$container_suffix+1;
        $page_button.='<span class="page-numbers" data-target="inb_cont_'.$container_suffix.'">'.$page_button_number.'</span>';
    ?>
    <?php endif;?>
    <?php if($container_ctr==1):?>
        <div id="inb_cont_<?php echo $container_suffix;?>" class="messages-container-new">
        <ul class="bordered-list">
    <?php endif;?>
        <?php ThemexUser::$data['active_user']=ThemexUser::getUser($recipient['ID']);?>
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
                            <?php //echo print_r(ThemexUser::$data['active_user']);?>
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
                            //echo print_r($arr);
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
        <?php if($container_ctr==$end_limit || $container_suffix==count($recipients)):?>
        </ul>
        </div>
		<?php 
        $container_ctr=0;
        $container_suffix++;
        endif; ?>
	<?php 	
    $container_ctr++;
    endforeach;
    ThemexUser::refresh();
    ?>
	<?php } else { ?>
	<p class="secondary"><?php _e('No messages received yet.', 'lovestory'); ?></p>
	<?php } ?>
<script type="text/javascript">
    $(document).ready(function() {       
        $('#messages-container').html('<?php echo $page_button;?>');
			$('#inb_cont_0').css('display','block');
		$('#messages-container span').click(function(e) {
			$('.messages-container-new').css('display','none');
            $('#'+$(this).attr('data-target')).css('display','block');
        });
    });
</script>