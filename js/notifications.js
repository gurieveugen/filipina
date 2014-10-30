function Notifications()
{
	var $this = this;
	this.getCounters = function(){
		jQuery.ajax({
			url: defaults.ajax_url + '?action=getCounters',
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.count)
				{
					$this.setBubble(response.count);
					$this.setNotification(response);
					$this.setReadedStatus(response.ids);
				}
			}
		});
	};

	/**
	 * Set read status to ureaded messages
	 * @param Array ids --- unread message ids
	 */
	this.setReadedStatus = function(ids){
		jQuery.ajax({
			url: defaults.ajax_url + '?action=setReadedStatus',
			type: 'POST',
			dataType: 'JSON',
			data: { ids: ids }
		});
	};

	/**
	 * Set bubble counter
	 */
	this.setBubble = function(val){
		this.createBubble();
		var posi = jQuery("#menu-main-menu li:nth-child(2)").position().left;
		var sum  = parseInt(jQuery("#bubble").text());

		sum = isNaN(sum) ? val : sum + val;

		console.log(sum);
		
		jQuery("#bubble").css({ display:'block' });
		jQuery("#bubble").text(sum);
	};

	/**
	 * Set and show norification message
	 * @param object response --- ajax response
	 */
	this.setNotification = function(response){
		this.createNotificationBlock();
		var message = '';
		for(var i = 0; i < response.messages.length; i++)
		{	
			message = jQuery( this.wrapMessage( response.messages[i] ) ).css(
				{
					position:      "fixed",
					display:       "block", 
					bottom:        '0px', 
					"margin-left": '40px', 
					"z-index":       '999999999'
				}
			);
			jQuery('#notification').append( message );
		}

		setTimeout(
			function(){ jQuery('#notification a').each(function(){ jQuery(this).fadeOut() }); }, 
			5000
		);
	};

	/**
	 * Create bubble if his not created
	 */
	this.createBubble = function(){
		if(!jQuery('#bubble').length)
		{
			jQuery("#menu-main-menu li:nth-child(2)").append('<div id="bubble"></div>');
		}
	};

	/**
	 * Create notification block if his not created
	 */
	this.createNotificationBlock = function(){
		if(!jQuery('notification').length)
		{
			jQuery('body').append('<div id="notification"></div>');
		}
	};

	/**
	 * Wrap one message to HTML code
	 * @param  obj message --- message object
	 * @return string --- HTML code
	 */
	this.wrapMessage = function(message){
		var msg = [];

		msg.push('<a class="new_notification" style="display:none;">');
		msg.push(
			String.Format(
				'<div>{0}</div>',
				message.avatar
			)
		);
		msg.push('<div>');
		msg.push(
			String.Format(
				'<div>You\'ve got mail <img src="{0}/images/icons/Email.png" width="30"/></div>',
				defaults.theme_uri
			)
		);
		msg.push(
			String.Format(
				'<div> {0} {1} from {2} {3} has just sent you a message.</div>',
				message.comment_author,
				message.age,
				message.country,
				message.city
			)
		);
		msg.push(
			String.Format(
				'<div onclick=\'clickUrl("{0}");\'>Read {1} message</div>',
				message.url,
				message.comment_author
			)
		);
		msg.push('</div>');
		msg.push('</a>');

		return msg.join('');
	};
}

// ==============================================================
// Launch
// ==============================================================
jQuery(document).ready(function(){
	var notifications = new Notifications();
	notifications.getCounters();
	setInterval(function(){ notifications.getCounters(); }, 10000);
});
