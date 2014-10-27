//Elements
var themeElements = {
	headerMenu: '.header-menu',
	slider: '.themex-slider',
	headerSlider: '.header-slider',
	carouselSlider: '.carousel-slider',
	selectField: '.select-field',
	selectMenu: '.mobile-menu',
	headerForm: '.header-form',	
	headerLoginButton: '.header-login-button',		
	headerLoginForm: '.header-login-form',
	headerPasswordButton: '.header-password-button',
	headerPasswordForm: '.header-password-form',
	facebookButton: '.facebook-login-button',
	colorboxLink: '.colorbox',
	colorboxValue: '.colorbox-value',
	placeholderFields: '.header-form input',
	tabsContainer: '.tabs-container',
	tabsTitles: '.tabs',
	tabsPane: '.pane',
	toggleTitle: '.toggle-title',
	toggleContent: '.toggle-content',
	toggleContainer: '.toggle-container',
	accordionContainer: '.accordion-container',
	uploadForm: '.upload-form',
	submitButton: '.submit-button',
	ajaxForm: '.ajax-form',
	elementSelect: '.element-select',
	elementOption: '.element-option',
	elementValue: '.element-value',		
	popupContainer: '.popup-container',
	statusForm: '.status-form',
	chatContainer: '.chat-container',
	chatFormSend: '.chat-form-send',
	chatFormUpdate: '.chat-form-update',
	filterableList: '.filterable-list',
	optionsTable: '.profile-options'
}
	
//DOM Loaded
jQuery(document).ready(function($) {

	//Dropdown Menu
	$(themeElements.headerMenu).find('li').hoverIntent(
		function() {
			var menuItem=$(this);
			menuItem.parent('ul').css('overflow','visible');			
			menuItem.children('ul').slideToggle(200, function() {
				menuItem.addClass('hover');
			});
		},
		function() {
			var menuItem=$(this);
			menuItem.children('ul').slideToggle(200, function() {
				menuItem.removeClass('hover');
			});
		}
	);

	//Select Menu
	$(themeElements.selectMenu).find('select').fadeTo(0, 0);
	$(themeElements.selectMenu).find('span').text($(themeElements.selectMenu).find('option:first').text());
	$(themeElements.selectMenu).find('option').each(function() {
		if(window.location.href==$(this).val()) {
			$(themeElements.selectMenu).find('span').text($(this).text());
			$(this).attr('selected','selected');
		}
	});
	
	$(themeElements.selectMenu).find('select').change(function() {
		window.location.href=$(this).find('option:selected').val();
		$(themeElements.selectMenu).find('span').text($(this).find('option:selected').text());
	});
	
	$(themeElements.selectField).each(function() {
		$(this).find('select:first').fadeTo(0, 0);
		$(this).find('span').text($(this).find('option:first').text());
		if($(this).find('option:selected').length) {
			$(this).find('span').text($(this).find('option:selected').text());
		}
		
		$(this).change(function() {
			$(this).find('span').text($(this).find('option:selected').text());
		});
	});
	
	//Select Filter
	$(themeElements.filterableList).each(function() {
		var filter=$('.'+$(this).data('filter')),
			label=$(this).parent().find('span'),
			select=$(this),
			clone=select.clone(false).insertAfter(select.parent());
			
		clone.removeAttr('name class data-filter').hide();		
		label.text(clone.find('option:first').text());
		select.prop('disabled', true);
		select.html('');
		
		filter.change(function() {
			var options=clone.find('option:first').add(clone.find('option.'+filter.val()));
			
			select.html('');
			label.text(clone.find('option:first').text());
			
			if(options.length > 1) {
				select.prop('disabled', false);				
				options.clone().appendTo(select);
			} else {
				select.prop('disabled', true);
			}
		});
		
		if(filter.val()) {
			var options=clone.find('option:first').add(clone.find('option.'+filter.val()));
			
			if(options.length > 1) {
				select.prop('disabled', false);
				options.clone().appendTo(select);
				
				if(options.filter(':selected').length) {
					label.text(options.filter(':selected').text());
				}
			} else {
				select.prop('disabled', true);
			}
		}
	});
	
	//Sliders
	$(themeElements.slider).each(function() {
		var sliderOptions= {};
		
		if($(this).is(themeElements.headerSlider)) {
			sliderOptions.effect='slide';	
			sliderOptions.speed=parseInt($(this).find('.slider-speed').val());
			if($(this).find('.slider-pause').val()) {
				sliderOptions.pause=parseInt($(this).find('.slider-pause').val());
			}
		} else if($(this).is(themeElements.carouselSlider)) {
			sliderOptions.speed=0;
		}
		
		$(this).themexSlider(sliderOptions);
	});
	
	//Colorbox
	$(themeElements.colorboxLink).each(function() {
		var inline=false;
		if($(this).hasClass('inline')) {
			inline=true;			
		}
	
		$(this).colorbox({
			rel: $(this).data('group'),
			inline: inline,
			current: '',
			maxWidth: '100%',
		});
		
		$(this).click(function() {			
			if(inline) {
				$($(this).attr('href')).find('.message').html('');
				$($(this).attr('href')).find(themeElements.colorboxValue).val($(this).data('value'));
			}
		});
	});
	
	//Popup Form
	$(themeElements.headerLoginButton).add(themeElements.headerForm).click(function(e) {
		if(!$(this).hasClass('active')) {
			$(themeElements.headerLoginButton).add(themeElements.headerLoginForm).addClass('active');
		}
		
		return false;
	});
	
	$(themeElements.headerPasswordButton).click(function() {
		$(themeElements.headerLoginForm).addClass('switched');
		setTimeout(function() {
			$(themeElements.headerPasswordForm).addClass('active');
		}, 400);
	});
	
	$('body').click(function() {
		$(themeElements.headerLoginButton).add(themeElements.headerForm).removeClass('switched active');
	});
	
	//Popup Container
	$(themeElements.popupContainer).each(function() {
		var popup=$(this).find('.popup');	

		if(popup.length) {
			$(this).find('a').each(function() {
				if(!popup.data('id') || $(this).data('popup')==popup.data('id')) {
					$(this).addClass('disabled').click(function() {
						$.colorbox({html:'<div class="popup">'+popup.html()+'</div>'});
						return false;
					});
				}
			});
		}
	});
	
	//Upload Form
	$(themeElements.uploadForm).find('input[type="file"]').change(function() {
		var form=$(this).parent();
		
		while(!form.is('form')) {
			form=form.parent();
		}
		
		form.submit();
	});
	
	//AJAX Form
	$(themeElements.ajaxForm).each(function() {
		var form=$(this);
		
		form.submit(function() {
			var message=form.find('.message'),
				loader=form.find('.loader'),
				toggle=form.find('.toggle'),
				button=form.find(themeElements.submitButton),
				title=form.find(themeElements.submitButton).data('title');
				
			var data={
					action: form.find('.action').val(),
					nonce: form.find('.nonce').val(),
					data: form.serialize()
				}
			
			loader.show();
			button.addClass('disabled').toggleClass('current');
			
			if(!message.hasClass('static')) {
				message.slideUp(300, function() {
					$(themeElements.colorboxLink).colorbox.resize();
				});
			}			
			
			jQuery.post(form.attr('action'), data, function(response) {
				if(jQuery('.redirect', response).length) {
					if(jQuery('.redirect', response).attr('href')) {
						window.location.href=jQuery('.redirect',response).attr('href');
					} else {
						window.location.reload();
					}
					
					message.remove();
				}
				
				if(title) {
					button.data('title', button.attr('title'));
					button.attr('title', title);
				}
				
				toggle.each(function() {
					var value=toggle.val();
					toggle.val(toggle.data('value'));
					toggle.data('value', value);
				});
				
				loader.hide();
				button.removeClass('disabled');
				
				if(response!='' &&  response!='0' && response!='-1') {
					if(message.hasClass('popup')) {
						$.colorbox({html:'<div class="popup">'+response+'</div>'});
					} else if(message.hasClass('static')) {
						message.append(response);
					} else {
						message.html(response).slideDown(300, function() {
							$(themeElements.colorboxLink).colorbox.resize();
						});
					}
				}
				
				form.find('.temporary').val('');				
				form.find('.scroll').each(function() {
					$(this).scrollTop($(this)[0].scrollHeight);
				});
			});
			
			return false;
		});
	});
	
	//Submit Button
	$(themeElements.submitButton).not('.disabled').click(function() {
		var form=$($(this).attr('href'));
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();		
		return false;
	});
		
	//Facebook Button
	$(themeElements.facebookButton).click(function() {
		var redirect=$(this).attr('href');
		
		if(typeof(FB)!='undefined') {
			FB.login(function(response) {
				if (response.authResponse) {
					window.location.href=redirect;
				}
			}, {
				scope: 'email',
			});
		}
		
		return false;
	});
	
	//Placeholders
	$('input, textarea').each(function(){
		if($(this).attr('placeholder')) {
			$(this).placeholder();
		}		
	});
	
	$(themeElements.placeholderFields).each(function(){
		var placeholder=$(this).val();
	
		$(this).focus(function () {
			if($(this).val()==placeholder){
				$(this).val('');
			}
		});
		$(this).blur(function () {	
			if(!$(this).val()){
				$(this).val(placeholder);
			}
		});
	});
	
	//Tabs
	$(themeElements.tabsContainer).each(function() {	
		var tabs=$(this);
		
		//show current pane
		if(window.location.hash && $(window.location.hash+'-tab').length) {
			var currentPane=$(window.location.hash+'-tab');
			currentPane.show();
			$(window).scrollTop(tabs.offset().top);
			tabs.find(themeElements.tabsTitles).find('li').eq(currentPane.index()).addClass('current');
		} else {
			tabs.find(themeElements.tabsPane).eq(0).show().addClass('current');
			tabs.find(themeElements.tabsTitles).find('li').eq(0).addClass('current');
		}
		
		tabs.find('.tabs li').click(function() {
			//set tab link
			window.location.href=$(this).find('a').attr('href');
			
			//set active state to tab
			tabs.find('.tabs li').removeClass('current');
			$(this).addClass('current');
			
			//show current tab
			tabs.find('.pane').hide();
			tabs.find('.pane:eq('+$(this).index()+')').show();

			return false;
		});
	});	
	
	//Toggles
	$(themeElements.accordionContainer).each(function() {
		if(!$(this).find(themeElements.toggleContainer+'.expanded').length) {
			$(this).find(themeElements.toggleContainer).eq(0).addClass('expanded').find(themeElements.toggleContent).show();
		}
	});
	
	$(themeElements.toggleTitle).live('click', function() {
		if($(this).parent().parent().is(themeElements.accordionContainer) && $(this).parent().parent().find('.expanded').length) {
			if($(this).parent().hasClass('expanded')) {
				return false;
			}
			$(this).parent().parent().find('.expanded').find(themeElements.toggleContent).slideUp(200, function() {
				$(this).parent().removeClass('expanded');		
			});
		}
		
		$(this).parent().find(themeElements.toggleContent).slideToggle(200, function(){
			$(this).parent().toggleClass('expanded');		
		});
		
		$(this).find('input').prop('checked', true);
	});

	//Element Select
	$(themeElements.elementSelect).each(function() {
		var options=$(this).find(themeElements.elementOption),
			value=$(this).find(themeElements.elementValue);
			
		if(options.length) {
			options.eq(0).addClass('current');
			value.val(options.eq(0).data('value'));
		}
		
		options.click(function() {
			value.val($(this).data('value'));		
			options.removeClass('current');
			$(this).addClass('current');
		});
	});
	
	//Options Table
	$(themeElements.optionsTable).each(function() {
		$(this).children('div').css('width', 100/$(this).children('div:visible').length+'%');
	});
	
	//Update Status
	if($(themeElements.statusForm).length) {
		$(themeElements.statusForm).submit();
		setInterval(function() {
			$(themeElements.statusForm).submit();
		}, 120000);
	}
	
	//Update Chat
	$(themeElements.chatFormSend).each(function() {
		var form=$(this);
		var temporary=form.find('.temporary');
		
		if(!form.hasClass('disabled')) {	
			var refresh=setInterval(function() {
				form.submit();
			}, 10000);
		
			form.submit();
			$(themeElements.chatFormUpdate).submit(function() {
				var message=$(this).find('.message');
				temporary.val(message.val());
				message.val('');
				form.submit();
				clearInterval(refresh);
				refresh=setInterval(function() {
					form.submit();
				}, 10000);
				
				return false;
			});
		} else {
			$(themeElements.chatFormUpdate).submit(function() {
				return false;
			});
		}
	});
	
	//DOM Elements
	$('p:empty').remove();
	$('h1,h2,h3,h4,h5,h6,blockquote').prev('br').remove();
});