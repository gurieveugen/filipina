<?php
/*
* Template Name: topsearch-form
*/
?>
<!--form search age, city, country-->
<div class="tabular" id="select-filter">
	<ul>
		<li class="active"><a href="#basic" title="search basic" data-target="#search-basic">Basic</a></li>
		<li><a href="" title="search user" data-target="#search-user">User</a></li>
		<li><a href="" title="search advanced" data-target="#search-advanced">Advanced</a></li>
	</ul>
</div>
<div class="profile-search-form top-search">
<section id="search-basic" style="display:block;">
	<form action="<?php echo SITE_URL.'/'.basename(get_permalink()).'/'; ?>" method="GET">
    	<div class="column fourcol">
			<div class="mw">
				<div class="select-fields">
					<span id="sel-country-group">Country</span>
				</div>
				<div class="hidden-f-n-click">
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
                    <script type="text/javascript">
						$(document).ready(function() {
							$('#country').prepend('<option value="" selected="selected"> Select Country </option>');
                            
                        });
					</script>
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
					<div class="btn-wrapper">
						<a href="javascript:void(0);" id="ok-sel-country" class="btn btn-success">OK</a>
					</div>
				</div>
			</div>
			<div class="mw">
				<div class="select-fields">
					<span id="sel-age-group">Age</span>
				</div>
				<div class="hidden-f-n-click" style="width:235px">
					<div class="select-field w50">
							<span style="font-size:11px; width:55px;"></span>
							<select id="age_from" name="age_from" style="opacity: 0;">
                            	<option value="1">Age From</option>
								<?php for($i=18;$i<=99;$i++){ echo '<option value="'. $i . '">' . $i . '</option>'; } ?>
							</select>	
					</div>	
					&nbsp;&nbsp;to&nbsp;&nbsp;
					<div class="select-field w50">
							<span style="font-size:11px; width:55px;">Age To</span>	
							<select id="age_to" name="age_to" style="opacity: 0;">
                            	<option value="100">Age To</option>
								<?php //for($i=18;$i<=99;$i++){ echo '<option value="'. $i . '"'.($i==31? 'selected':'').'>' . $i . '</option>'; } ?>
                                <?php for($i=18;$i<=99;$i++){ echo '<option value="'. $i . '">' . $i . '</option>'; } ?>
							</select>	
					</div>
					<div class="btn-wrapper">
						<a href="javascript:void(0);" id="ok-sel-age" class="btn btn-success">OK</a>
					</div>
				</div>
			</div>
        </div>
        <div class="column fourcol last">
			<div class="mw">
            <!--<h5><?php _e('I\m Seeking a: ', 'lovestory'); ?></h5>--></td>
            <div class="select-field">
                <span></span>
                <?php 
                echo ThemexInterface::renderOption(array(
                    'id' => 'seeking',
                    'type' => 'select',
                    'value' => isset($_GET['seeking'])?$_GET['seeking']:'woman',
                    'wrap' => false,
                    'options' => array_merge(array('0' => 'I am Seeking a'), ThemexCore::$components['genders'] ),						
                ))
                ?>
            </div>
			</div>
			<div class="mw">
			<div class="select-field">
                <span></span>
                <select id="themex_user_order" name="themex_user_order">
					<option value="">Order By </option>
					<option value="date">Date</option>
					<option value="name">Name</option>
					<option value="status">Status</option>
				</select>
            </div>
			</div>
        </div>
		<div class="column eightcol last button-container">
            <a href="#" class="button medium submit-button">
                <span class="button-icon icon-search"></span><?php _e('Find My Matches', 'lovestory'); ?>
            </a>
        </div>
		<input type="hidden" name="s" value="" />
	</form>
</section>
<section id="search-user">
    <form action="<?php echo SITE_URL.'/'.basename(get_permalink()).'/'; ?>" method="GET" >
    	 <div class="column fourcol input-fields">
        	<div class="field-wrap">
            	<input type="text" placeholder="First Name" id="firstname" name="firstname">
            </div>
            <div class="field-wrap">
            	<input type="text" placeholder="Last Name" id="lastname" name="lastname">
            </div>
        	
        </div>
        <div class="column fourcol input-fields last">
        	<div class="field-wrap">
            	<input type="text" id="username" name="username" value="" placeholder="Username">
            </div>                      
        </div>
        <div class="clear"></div>
        <div class="column eightcol last button-container">
        	<a href="#" class="button medium submit-button">
            	<span class="button-icon icon-search"></span><?php _e('Find Profile', 'lovestory'); ?>
            </a>
        </div>
        <input type="hidden" name="s" value="" />
    </form>
</section>
<section id="search-advanced">
    <form action="<?php echo SITE_URL.'/'.basename(get_permalink()).'/'; ?>" method="GET" >
        <div class="column fourcol input-fields">
        	<div class="field-wrap">
            	<input type="text" placeholder="Height" id="height-in-cm" name="height-in-cm">
            </div>
            <div class="field-wrap">
            	<input type="text" placeholder="Weight" id="weight-in-kilos" name="weight-in-kilos">
            </div>
        	
        </div>
        <div class="column fourcol input-fields last">
        	<div class="field-wrap">
            	<input type="text" id="wants-to-meet" name="wants-to-meet" value="" placeholder="Wants to meet">
            </div>
            <div class="field-wrap">
            	<input type="text" id="description" name="description" value="" placeholder="Description">
            </div>            
        </div>
        <div class="clear"></div>
        <div class="button-container">
        	<a href="#" class="button medium submit-button">
            	<span class="button-icon icon-search"></span><?php _e('Find My Matches', 'lovestory'); ?>
            </a>
        </div>
        <input type="hidden" name="s" value="" />
    </form>
</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
        $('#select-filter ul li a').click(function() {
			$('.top-search section').css('display','none');
			$($(this).attr('data-target')).css('display','block');
			$('#select-filter ul li').removeClass('active');
			$(this).parent('li').addClass('active');
			return false;            
        });
    });
</script>