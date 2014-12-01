<?php
$query=new WP_Query(array(
	'post_type' =>'slide',
	'showposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));

if($query->have_posts()) {
?>
<div class="header-slider themex-slider" style="background-color:white; height:480px;">
	            <div class="slider-container" >
			<div class="container" >
             &nbsp;
                <form action="<?php echo 'http://filipina.net/search-profiles' ?>" method="GET" >
              <div class="membership-box"> 
               <div style=" border-bottom:#CCC solid 1px; text-align:center; height:50px;font-size:16px; color:#999; padding-bottom:3px;">
               <p style="font-family:arial; font-size:22px; color:#FFF; ">100% Free Site</p><br /><p style=" font-family:arial; font-size:20px; color:#FFF; ">No Paid Membership</p></div>
             <div style="margin-top:10px;padding-left:20px">
              <span style="padding-left:50px; color:#FFF; height:25px; font-size:15px;"> I Am</span> <select style="
height: 22px;
font-size: 13px;
padding: 0px; width:172px;" name=""> 
              <option> man</option>
              <option> woman</option>
              <option>ladyboy </option>
              </select><!--<input type="text" name="" value="" style="height:4px; width:120px;" />-->
              
          
               <span style="color:#FFF; font-size:15px;">Looking For </span><select style="height: 22px;
font-size: 13px;
padding: 0px; width:172px;" name="seeking"> 
              <option > </option>
              <option selected="selected"> man </option>
              <option> woman</option>
              <option>ladyboy </option>
              </select> <!--<input type="text" name="" value="" style="height:4px; width:120px;" />-->
               
               <span style="padding-left:60px; color:#FFF; font-size:15px;"> In &nbsp;</span>
               <div class="mw" style="">
				<div class="select-fields" style="width: 170px;background-color: white; font-size:13px; padding:0px;
margin-left: 86px;
margin-top: -20px;
height: 22px">
					<span id="sel-country-group" style="padding: 2px;
font-size: 12px">Country</span>
				</div>
				<div class="hidden-f-n-click dropdown-country">
					<!--<h5><?php _e('Country', 'lovestory'); ?></h5>--></td>
					<div class="select-field">
						<span></span>
						<?php 
						echo ThemexInterface::renderOption(array(
							'id' => 'country',
							'type' => 'select',
							'options' =>  ThemexCore::$components['countries'],
							'value' => isset($_GET['country'])?$_GET['country']:null,
							'attributes' => array('class' => 'countries-list'),
							'wrap' => false,
						));
						?>
					</div>
					<!--<h5><?php _e('City', 'lovestory'); ?></h5>--></td>
					<div class="select-field">
						<span></span>
						<?php 
						echo ThemexInterface::renderOption(array(
							'id' => 'city',
							'type' => 'select_city',
							'value' => isset($_GET['city'])?$_GET['city']:'City',
							'attributes' => array(
								'class' => 'filterable-list',
								'data-filter' => 'countries-list',
							),
							'wrap' => false,
						));
						?>
					</div>
					<div class="btn-wrapper" style="text-align: center;">
						<a href="javascript:void(0);" id="ok-sel-country" class="btn btn-success">OK</a>
					</div>
				</div>
			</div>
               <a href="#" class="home-search-button"><?php _e('Search', 'lovestory'); ?></a>
                <!--<input type="text" name="" value="" style="height:4px; width:120px;" />-->
              
                             <!-- <input type="submit" name="search" value="Search" style="margin-left:50px; font-family:Arial; font-size:25px; width:180px; background-color:#FF8000; border-radius:10px;height: 40px;
padding-top: 0px;
margin-top: 5px;" />-->
</div>
               
               <img src="wp-content/themes/lovestory/images/or2.png" />
               <?php if(ThemexFacebook::isActive()) { ?>
		<a href="<?php echo home_url('?facebook_login=1'); ?>" 
        	class="button1 facebook-login-button form-button facebook-btn"
            title="<?php _e('Sign in with Facebook', 'lovestory'); ?>" style="font-size: 9px;
    height: 10px;
    line-height: 9px;
    margin-bottom: 0;
    text-align: center;
    vertical-align:top;">
			<!--<span class="button-icon icon-facebook nomargin"></span>-->
            <img src="wp-content/themes/lovestory/images/facebook-icon.png" />
		</a>
		<?php } ?>
              <!--<a href=""><img src="wp-content/themes/lovestory/images/fb.jpg" style="margin-left:15px;" /></a>-->
              <span style=" color: #999;
    display: inline-table;
    font-size: 10px;
    line-height: 7px;
    text-align: center;
    width: 100%;"> We will never publish anything on your timeline </span>
               
               </div>
               <input type="hidden" name="s" value="" />
               </form>
             
                 </div>
			</div>
		
	<input type="hidden" class="slider-pause" value="<?php echo ThemexCore::getOption('slider_pause', '0'); ?>" />
	<input type="hidden" class="slider-speed" value="<?php echo ThemexCore::getOption('slider_speed', '1000'); ?>" />	
</div>
<?php } else { ?>
<div class="header-slider"></div>
<?php } ?>