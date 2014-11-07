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

	this.getViews = function(){
		jQuery.ajax({
			url: defaults.ajax_url + '?action=getViews',
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.length > 0)
				{
					$this.setViewNotification(response);
					$this.clearView();
				}
			}
		});
	};

	this.clearView = function(){
		jQuery.ajax({ url: defaults.ajax_url + '?action=clearViews' });
	};

	this.clearFavorites = function(){
		jQuery.ajax({ url: defaults.ajax_url + '?action=clearFavorites' });
	};

	this.getFavorites = function(){
		jQuery.ajax({
			url: defaults.ajax_url + '?action=getFavorites',
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.length)
				{
					$this.setFavoriteNotification(response);
					$this.clearFavorites();
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
		var posi = jQuery("#menu-final-main-menu li:nth-child(3)").position().left;
		var sum  = parseInt(jQuery("#bubble").text());

		sum = isNaN(sum) ? val : sum + val;
		
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
			jQuery('#notification').css({ display: 'block' });
		}
	};

	/**
	 * Set and show norification message
	 * @param object response --- ajax response
	 */
	this.setViewNotification = function(response){
		this.createNotificationBlock();
		var message = '';
		for(var i = 0; i < response.length; i++)
		{	
			message = jQuery( this.wrapProfileView( response[i] ) ).css(
				{
					position:      "fixed",
					display:       "block", 
					bottom:        '0px', 
					"margin-left": '40px', 
					"z-index":       '999999999'
				}
			);
			jQuery('#notification').append( message );
			jQuery('#notification').css({ display: 'block' });
		}
	};

	/**
	 * Set and show norification message
	 * @param object response --- ajax response
	 */
	this.setFavoriteNotification = function(response){
		this.createNotificationBlock();
		var message = '';
		for(var i = 0; i < response.length; i++)
		{	
			message = jQuery( this.wrapFavorites( response[i] ) ).css(
				{
					position:      "fixed",
					display:       "block", 
					bottom:        '0px', 
					"margin-left": '40px', 
					"z-index":       '999999999'
				}
			);
			jQuery('#notification').append( message );
			jQuery('#notification').css({ display: 'block' });
		}
	};

	/**
	 * Create bubble if his not created
	 */
	this.createBubble = function(){
		if(!jQuery('#bubble').length)
		{
			jQuery("#menu-final-main-menu li:nth-child(3)").append('<div id="bubble"></div>');
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
				'<div>You\'ve got mail</div>',
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

	this.wrapProfileView = function(view){
		var msg = [];
		msg.push('<a class="new_notification" style="display:none;">');
		msg.push(
			String.Format(
				'<div>{0}</div>',
				view.avatar
			)
		);
		msg.push('<div>');
		msg.push(
			String.Format(
				'<div>Your profile has been viewed</div>',
				defaults.theme_uri
			)
		);
		msg.push(
			String.Format(
				'<div> {0} has just viewed your profile.</div>',
				view.name
			)
		);
		msg.push(
			String.Format(
				'<div onclick=\'clickUrl("{0}");\'>{1} view</div>',
				view.url,
				view.name
			)
		);
		msg.push('</div>');
		msg.push('</a>');

		return msg.join('');
	};

	this.wrapFavorites = function(favorite){
		var msg = [];
		msg.push('<a class="new_notification" style="display:none;">');
		msg.push(
			String.Format(
				'<div>{0}</div>',
				favorite.avatar
			)
		);
		msg.push('<div>');
		msg.push(
			String.Format(
				'<div>Your profile has added to favorites</div>',
				defaults.theme_uri
			)
		);
		msg.push(
			String.Format(
				'<div> {0} has just added to favorites.</div>',
				favorite.name
			)
		);
		msg.push(
			String.Format(
				'<div onclick=\'clickUrl("{0}");\'>{1} view</div>',
				favorite.url,
				favorite.name
			)
		);
		msg.push('</div>');
		msg.push('</a>');

		return msg.join('');
	};
}

function clickUrl(url)
{
	window.location.href = url;
}

// ==============================================================
// Launch
// ==============================================================
jQuery(document).ready(function(){
	var notifications = new Notifications();
	notifications.getCounters();
	notifications.getViews();
	notifications.getFavorites();
	setInterval(function(){ notifications.getCounters(); }, 10000);
	setInterval(function(){ notifications.getViews(); }, 15000);
	setInterval(function(){ notifications.getFavorites(); }, 16000);
	setInterval(function(){ jQuery('#notification').fadeOut() }, 30000);
});
