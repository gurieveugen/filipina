$(document).ready(function(){
	setInterval(function(){

		realoadFeaturedLeftRight();
	}, 6000 * 600);	//6 minutes update

$(".widget #editprofile_click").click(function(){
	$(this).attr("class", "current");
	//alert(window.location.queryKey['editprofile']);
	//alert(window.location.search.slice(1));
	
});
$(".drop_bomb").click(function(){

	$("#drop_bomb_window").css({'display':'block','position':'absolute', 'z-index':'999999999999', 'opacity':'1', 'top':'100px'});	
});

$("#close_bomb").click(function(){

	$("#drop_bomb_window").css({'display':'none'});	
});

$('#drop_bomb_window #country').change(function(){
		getPeopleBombMessage(0, 0);
        
});

$('#drop_bomb_window #city').change(function(){
		getPeopleBombMessage(0, 0);
        
});

$('#drop_bomb_window #reach').change(function(){
		getPeopleBombMessage(0, 0);
		
        
});

$(".feature_profile").click(function(){

	$("#feature_profile_window").css({'display':'block','position':'absolute', 'z-index':'999999999999', 'opacity':'1', 'top':'100px'});
	//var leftValues = 0;
	$('.nstSlider').nstSlider({
	
    "left_grip_selector": ".leftGrip",
    "value_changed_callback": function(cause, leftValue, rightValue) {
		var d = $("#feature_profile_window #reach").val();
		var price = 0;
		var time = "";
		var s = ""; 
		switch(d){
			case '1':
				price = 10;
				time =" Day";
			break;
			case '2':
				price = 200;
				time =" Month";
			break;	
			case '3':
				price = 1000;
				time =" Year";
			break;	
		}
		
		if(leftValue > 1){
			if(time != "") s = "s";
			time = time + s;
		}
		$('.after_leftLabel_word').remove();
        $(this).parent().find('.leftLabel').text(leftValue);
		$(this).parent().find('.leftLabel').after("<div class='after_leftLabel_word'>" + time + "</div>")
		var credit = price * leftValue;
		$('.credit_boost_visibility').text(credit);
    }
});

});

$("#close_profile").click(function(){

	$("#feature_profile_window").css({'display':'none'});	
});

$('#feature_profile_window #country').change(function(){
		getPeopleBombMessage(2, 0);
        
});

$('#feature_profile_window #city').change(function(){
		getPeopleBombMessage(2, 0);
        
});

$('#feature_profile_window #reach').change(function(){
		getPeopleBombMessage(2, 0);
		
		var d = $("#feature_profile_window #reach").val();
		price = 0;
		var time = "";
		switch(d){
			case '1':
				price = 10;
				time =" Day";
			break;
			case '2':
				price = 200;
				time =" Month";
			break;	
			case '3':
				price = 1000;
				time =" Year";
			break;	
		}
		
		if(parseInt($('.leftLabel').text()) > 1){
			time = time + "s";
		}
		$('.after_leftLabel_word').empty().text(time);
		
		var credit = price * parseInt($('.leftLabel').text());
		$('.credit_boost_visibility').text(credit);
        
});




checkedSelect($('.message_avatar input:visible'), false); 

$('#inbox_click').css({'color':'#666'});
$('#sent').css({'display':'none'});
$('#unread').css({'display':'none'});
$('#trash').css({'display':'none'});
$('#draft').css({'display':'none'});
	$('#inbox_click').click(function(){
		location.hash = 'reloaded=inbox';
		window.location.reload();
	});
	
	$('#sent_click').click(function(){
		location.hash = 'reloaded=sent';
		window.location.reload();
	});
	
	$('#trash_click').click(function(){
		location.hash = 'reloaded=trash';
		window.location.reload();
	});
	
	$('#draft_click').click(function(){
		location.hash = 'reloaded=draft';
		window.location.reload();
	});
	
	
	$('#unread_click').click(function(){
		
		location.hash = 'reloaded=unread';
		window.location.reload();
	});
	
	
	$('#select_all').click(function(){
		checkedSelect($('.message_avatar input:visible '), true);
	});
	
	$('#select_none').click(function(){
		checkedSelect($('.message_avatar input:visible '), false);
	});
	
	
	$('#select_delete').click(function(){
		resAjax("comment_id_delete_messages");
	});
	
	
	$('#select_read').click(function(){
		resAjax("comment_id_read_messages");
	});
	
	
	$('#select_unread').click(function(){
		resAjax("comment_id_unread_messages");
		
	});
	getUrl();
	

	var url = window.location.pathname.split( '/' );
	
	
	if(url[1] != "messages"){
	
  
	}
		//alert(url[1]);
	
	if(url[1] == "profile"){
		$("#formatted-form_left").css({'display':'block'});
		$(".profile_right").css({'display':'block'});
		$(".fourcol").css({'display':'none'});
		
	}else{
		$("#formatted-form_left").css({'display':'none'});
		$(".profile_right").css({'display':'none'});
		$(".fourcol").css({'display':'block'});
	}
	
	//position alert messages unread
	/*if(url[1] == "messages"){
			setTimeout(function(){
			methodToFixLayout(85);
		
		}, 2000);
		
	}else{
	}
	*/
	// setTimeout(function(){
	// 		methodToFixLayout(85);
		
	// 	}, 700);
	
	$(window).resize(function(){
		  methodToFixLayout(85);
	});

});

function getQueryParam(param) {
    var result =  window.location.search.match(
        new RegExp("(\\?|&)" + param + "(\\[\\])\\([^&]*)")
    );

    return result ? result : false;
}


function capitalise(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

function getPeopleBombMessage(res, send){
	var gender = "";
	
	var city = $("#city").val();
	var country = $("#country").val();
	var message = $("#message").val();
	
	
	switch(send){
		case 1:
			send = "message_mass_send=send_on&";
		break;
		
		case 2:
			send = "boost_visibility_send=send_on&";
		break;
	}
	
	switch($("#reach").val()){
		case '1':
			gender = "man";
		break;
		
		case '2':
			gender = "woman";
		break;
	}
	if((city != '0' || city != 'null') && (country != '0' || country != 'null')) {
		if($("#reach").val() == '1' || $("#reach").val() == '2'){
			//message = "You are sending a message to all the " + capitalise(gender) + " living in " + capitalise(city) + ", " + capitalise(country);
			$("#send_message_bomb").empty().append("You are sending a message to all the " + capitalise(gender) + " living in " + capitalise(city) + ", " + capitalise(country));
		}
	
	}
	var data_post = "";
	
	if(res == 1){
		data_post = send + "bombMessage=on&message_text=" + message + "&gender=" + gender + "&city=" + city + "&country=" + country; 
	}else if(res == 0){
		data_post = send + "bombMessage=on&" + "gender=" + gender + "&city=" + city + "&country=" + country;
	}else{
		var city = $("#feature_profile_window #city").val();
		var country = $("#feature_profile_window #country").val();
		var credit = $(".credit_boost_visibility").text();
		var time = $(".leftLabel").text();
		var type = $("#feature_profile_window #reach").val();
		var gender ="";
		
		data_post = send + "bombMessage=on&credit=" + credit + "&time=" + time + "&type=" + type + "&city=" + city + "&country=" + country; 
	}
	
	$.ajax({
			 url: "?seeking=" + gender + "&country=" + country + "&city=" + city + "&s=",
			 type: "POST",
			 data: data_post,
			 beforeSend: function() {
				$("#credit_td").empty().append("<div id='load_css'></div>");
				},
				success: function(data) {
					var last_id_insert = "";
					last_id_insert = $('#last_id_insert',data).text();
					
					switch(last_id_insert){
						case "success":
							$("#feature_profile_window #continue_bomb_message").after("<div id='message_after_continue' style='color:#54AFD3'>Activated!</div>");
							$("#feature_profile_window #continue_bomb_message").remove();
							realoadFeaturedLeftRight();
						break;
						
						case "faild":
							$("#feature_profile_window #continue_bomb_message").after("<div id='message_after_continue' style='color:#F17A97'>Failed, try again!</div>");
							$("#feature_profile_window #continue_bomb_message").remove();
						break;
					}
					
					var message = $('#bool_message',data).text();
					
					if(message == "1"){
						//alert('yes');
						$("#credit_td").empty().append('<div id="credit_bomb_message" style="width:300px;"><div>Your message was sent successfully!</div></div>');
					}else if(message == "0"){
						$("#credit_td").empty().append('<div id="credit_bomb_message" style="width:300px;"><div>Messages failing to send, try again!</div></div>');
					}else{						
						var count = $('#count_users_search',data).text();
						$("#credit_td").empty().append('<div id="credit_bomb_message"><div>Credits: </div><div id="credits_bomb_message">' + count + '</div></div>');
					}
					
				},
			});
	
			
			
}

function continue_bomb_message(){
	getPeopleBombMessage(1, 1);
	
}

function continue_boost_visibility(){
	getPeopleBombMessage(2, 2);
	
}		 

function methodToFixLayout($add) {
	
}

function resAjax(key){
		var $show = visibleFilter();
		var i = 0;
		$('.message_avatar input:visible ').each(
			function(index){  
				var input = $(this);
				var user_id = $(this).parent().attr('id');
				//{key : $(this).val(),'user_id' : user_id};
				var owner =0;
				if(key == "comment_id_delete_messages"){
					owner = 1;
				}
				if($(this).prop('checked') == true){
					$.ajax({
							 url: window.location,
							 type: "post",
							data: key + "=" + $(this).val() + "&user_id="+user_id, 
							}).success(function(res) {
									  
								i++;
								if($('.message_avatar input ').filter(':visible').filter(':checked').length == i){
									location.hash = "reloaded=" + $show ;
									window.location.reload();
								}
							});
				}
			}
		);
	}

function checkedSelect(item, type){
		item.each(
		function(index){  
			var input = $(this);
			$(this).prop('checked',type);
		}
		);
	}
	

function reloadInboxNumber(){
		
	}	

function getUrl(){
	var hashstring = window.location.hash.substring(1);
	
	// Do a hash exist?
	if (hashstring.length > 0) 
	{
		// Split the hash by '&'-sign (in case you have more variables in the hash, as I have)
		var a = hashstring.split("&");

		// Loop through the values
		for (i = 0; i < a.length; i++)
		{
			// Split the string by '=' (key=value format)
			var b = a[i].split("=");

			// If the key is 'reloaded' (which tells us if the page is reloaded)
			if(b[0] == 'reloaded')
			{
				
				$('#inbox').css({'display':'none'});
				$('#sent').css({'display':'none'});
				$('#unread').css({'display':'none'});
				$('#trash').css({'display':'none'});
				$('#draft').css({'display':'none'});
				
				$('#inbox_click').css({'color':'#54AFD3'});
				$('#sent_click').css({'color':'#54AFD3'});
				$('#unread_click').css({'color':'#54AFD3'});
				$('#trash_click').css({'color':'#54AFD3'});
				$('#draft_click').css({'color':'#54AFD3'});
				
				$('#' + b[1]).css({'display':'block'});
				$('#' + b[1] + '_click').css({'color':'#666'});
				
				checkedSelect($('.message_avatar input:visible'), false); 	
			}
		}
	}
}	


function CheckNotification(profile_url){
		
		// notification(profile_url);
		
		// setInterval(function(){
			
		// 	notification(profile_url);
		
		// }, 10000);
		
	
}

function notification(profile_url){
	console.log(profile_url);
	$.ajax({
			 url: profile_url,
			}).success(function(data) {
				  $('#notification').remove();
				  $('body').append("<div id='notification'></div>");
				  var $bubble = $('#notification',data).html();
				  $('#notification').append($bubble);
				  methodToFixLayout(85);

				  	if(typeof(hide_notify) == 'undefined')
				  	{
				  		$('#notification a').each(function(){
				  			jQuery(this).css({"position":"fixed","display":"block", 'bottom': '0px', 'margin-left':'40px', 'z-index':'999999999'});						
				  		});

				  		setTimeout(function(){
				  			$('#notification a').each(function(){
				  				jQuery(this).fadeOut();
				  			});
				  		}, 4000);	
				  	}
				  
				 //  var index = 0;
					// var $tabs = $('#notification a');
					
					// setInterval(function(){
					// 	//tabs('select', tabs[index].panel('options').title);
					// 	$tabs.css("display","none");

					// 	$tabs.eq( index ).css({"position":"fixed","display":"block", 'bottom': '0px', 'margin-left':'40px', 'z-index':'999999999'});
					// 	index++;
					// 	if (index >= $tabs.length){
					// 		index = 0;
					// 	}
					// }, 0);
		});

			

}


function visibleFilter(){
	
    if ($('#inbox').is(':visible')) {
        return 'inbox';
    }else if ($('#sent').is(':visible')) {
        return 'sent';
    }else if ($('#unread').is(':visible')) {
        return 'unread';
    }else{
		return 'inbox';
	}

 
}

function clickUrl(url){
	window.location.href = url;
}


function update(slider,val) {
 
 var $duration = slider == 2?val:$("#duration").val();
 $( "#duration" ).val($duration);
if($duration == 0) $duration = 1;
 $('#slider2 a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$duration+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
}
	  
function addFeaturedAdmin(user_id){
	$.ajax({
		 url: window.location,
		 type: "POST",
		 data: "user_id=" + user_id + "&admin=add_on" 
		}).success(function(data) {
			  $('#profileAdmin').empty();
			  var $bubble = $('#profileAdmin',data).html();
			  $('#profileAdmin').append($bubble);
		});
		
}

function removeFeaturedAdmin(user_id){
	$.ajax({
		 url: window.location,
		 type: "POST",
		 data: "user_id=" + user_id + "&admin=remove_on" 
		}).success(function(data) {
			  $('#profileAdmin').empty();
			  var $bubble = $('#profileAdmin',data).html();
			  $('#profileAdmin').append($bubble);
		});
}  

function realoadFeaturedLeftRight(){
	
	
					$.ajax({
		 url: window.location,
		}).success(function(data) {
			  $('#featured-left').empty();
			  var $bubble = $('#featured-left',data).html();
			  $('#featured-left').append($bubble);
			  
			  $('#featured-right').empty();
			  var $bubble = $('#featured-right',data).html();
			  $('#featured-right').append($bubble);
		});	
} 

(function($){ 
	$(document).ready( function(){
		$('select[name="city"]').children('option').eq(0).attr('selected','true').text('City');
		$('select[name="seeking"]').children('option').eq(0).attr('selected','true').text('I am seeking a');
		$("#sel-country-group").text("Country");
		
		$("#sel-age-group,#sel-country-group").click( function(e){
			$(".hidden-f-n-click").hide();
			$(this).parents(".mw").children(".hidden-f-n-click").toggle();
		});

		
		var top_of_ctr = new Array();
		$("#country").children('option').each( function(){
			if( $(this).text() == 'United States' ){ $(this).remove(); top_of_ctr[0] = "<option value='US'>United States</option>";}
			if( $(this).text() == 'Philippines' ){  $(this).remove(); top_of_ctr[1] = "<option value='PH'>Philippines</option>";}
			if( $(this).text() == 'Australia' ){  $(this).remove(); top_of_ctr[2] = "<option value='AU'>Australia</option>";}
		});
		
		$("#country").prepend('<optgroup label="_________"><optgroup>');
		for(var i=2; i>=0; i--){
			$("#country").prepend( top_of_ctr[ i ] );
		}
		$("#country").parent().children('span').text('US');
		$("#country").children('option').eq(0).attr('selected',true);
		check_country();
             $("#country").change(function(){
                      
                           check_country();
                     
               });
		
		$("#ok-sel-country").click(function(e){
			e.preventDefault();
			$(this).parents('.mw').children(".hidden-f-n-click").hide();       
			$("#sel-country-group").text( $("#country").parent().children('span').text() );		
			
		});
		
		$("#ok-sel-age").click(function(e){
			e.preventDefault();
			$(this).parents('.mw').children(".hidden-f-n-click").hide();  
			$("#sel-age-group").text('Age ' + $("#age_from").val() + ' to ' + $("#age_to").val() );
		});
	});

function check_country(){
 setTimeout( function(){
	if( $('select[name="city"]').children('option').length > 0 )     $('select[name="city"]').parent('.select-field').eq(0).show();
	else $('select[name="city"]').parent('.select-field').eq(0).hide();
 },1000);
}  
})(jQuery); 