/* global webaliveScreenReaderText */
(function( $ ) {

	// Variables and DOM Caching.
	var $body = $( 'body' ),
		$customHeader = $body.find( '.custom-header' ),
		$branding = $customHeader.find( '.site-branding' ),
		$navigation = $body.find( '.navigation-top' ),
		$navWrap = $navigation.find( '.wrap' ),
		$navMenuItem = $navigation.find( '.menu-item' ),
		$menuToggle = $navigation.find( '.menu-toggle' ),
		$menuScrollDown = $body.find( '.menu-scroll-down' ),
		$sidebar = $body.find( '#secondary' ),
		$entryContent = $body.find( '.entry-content' ),
		$formatQuote = $body.find( '.format-quote blockquote' ),
		isFrontPage = $body.hasClass( 'twentyseventeen-front-page' ) || $body.hasClass( 'home blog' ),
		navigationFixedClass = 'site-navigation-fixed',
		navigationHeight,
		navigationOuterHeight,
		navPadding,
		navMenuItemHeight,
		idealNavHeight,
		navIsNotTooTall,
		headerOffset,
		menuTop = 0,
		resizeTimer;

	// Ensure the sticky navigation doesn't cover current focused links.
	$( 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex], [contenteditable]', '.site-content-contain' ).filter( ':visible' ).focus( function() {
		if ( $navigation.hasClass( 'site-navigation-fixed' ) ) {
			var windowScrollTop = $( window ).scrollTop(),
				fixedNavHeight = $navigation.height(),
				itemScrollTop = $( this ).offset().top,
				offsetDiff = itemScrollTop - windowScrollTop;

			// Account for Admin bar.
			if ( $( '#wpadminbar' ).length ) {
				offsetDiff -= $( '#wpadminbar' ).height();
			}

			if ( offsetDiff < fixedNavHeight ) {
				$( window ).scrollTo( itemScrollTop - ( fixedNavHeight + 50 ), 0 );
			}
		}
	});

	// Set properties of navigation.
	function setNavProps() {
		navigationHeight      = $navigation.height();
		navigationOuterHeight = $navigation.outerHeight();
		navPadding            = parseFloat( $navWrap.css( 'padding-top' ) ) * 2;
		navMenuItemHeight     = $navMenuItem.outerHeight() * 2;
		idealNavHeight        = navPadding + navMenuItemHeight;
		navIsNotTooTall       = navigationHeight <= idealNavHeight;
	}

	// Make navigation 'stick'.
	function adjustScrollClass() {

		// Make sure we're not on a mobile screen.
		if ( 'none' === $menuToggle.css( 'display' ) ) {

			// Make sure the nav isn't taller than two rows.
			if ( navIsNotTooTall ) {

				// When there's a custom header image or video, the header offset includes the height of the navigation.
				if ( isFrontPage && ( $body.hasClass( 'has-header-image' ) || $body.hasClass( 'has-header-video' ) ) ) {
					headerOffset = $customHeader.innerHeight() - navigationOuterHeight;
				} else {
					headerOffset = $customHeader.innerHeight();
				}

				// If the scroll is more than the custom header, set the fixed class.
				if ( $( window ).scrollTop() >= headerOffset ) {
					$navigation.addClass( navigationFixedClass );
				} else {
					$navigation.removeClass( navigationFixedClass );
				}

			} else {

				// Remove 'fixed' class if nav is taller than two rows.
				$navigation.removeClass( navigationFixedClass );
			}
		}
	}

	// Set margins of branding in header.
	function adjustHeaderHeight() {
		if ( 'none' === $menuToggle.css( 'display' ) ) {

			// The margin should be applied to different elements on front-page or home vs interior pages.
			if ( isFrontPage ) {
				$branding.css( 'margin-bottom', navigationOuterHeight );
			} else {
				$customHeader.css( 'margin-bottom', navigationOuterHeight );
			}

		} else {
			$customHeader.css( 'margin-bottom', '0' );
			$branding.css( 'margin-bottom', '0' );
		}
	}

	// Set icon for quotes.
	function setQuotesIcon() {
		$( webaliveScreenReaderText.quote ).prependTo( $formatQuote );
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass( param ) {
		var sidebarPos, sidebarPosBottom;

		if ( ! $body.hasClass( 'has-sidebar' ) || (
			$body.hasClass( 'search' ) ||
			$body.hasClass( 'single-attachment' ) ||
			$body.hasClass( 'error404' ) ||
			$body.hasClass( 'twentyseventeen-front-page' )
		) ) {
			return;
		}

		sidebarPos       = $sidebar.offset();
		sidebarPosBottom = sidebarPos.top + ( $sidebar.height() + 28 );

		$entryContent.find( param ).each( function() {
			var $element = $( this ),
				elementPos = $element.offset(),
				elementPosTop = elementPos.top;

			// Add 'below-entry-meta' to elements below the entry meta.
			if ( elementPosTop > sidebarPosBottom ) {
				$element.addClass( 'below-entry-meta' );
			} else {
				$element.removeClass( 'below-entry-meta' );
			}
		});
	}

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	function supportsInlineSVG() {
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( 'undefined' !== typeof SVGRect && div.firstChild && div.firstChild.namespaceURI );
	}

	/**
	 * Test if an iOS device.
	*/
	function checkiOS() {
		return /iPad|iPhone|iPod/.test(navigator.userAgent) && ! window.MSStream;
	}

	/*
	 * Test if background-attachment: fixed is supported.
	 * @link http://stackoverflow.com/questions/14115080/detect-support-for-background-attachment-fixed
	 */
	function supportsFixedBackground() {
		var el = document.createElement('div'),
			isSupported;

		try {
			if ( ! ( 'backgroundAttachment' in el.style ) || checkiOS() ) {
				return false;
			}
			el.style.backgroundAttachment = 'fixed';
			isSupported = ( 'fixed' === el.style.backgroundAttachment );
			return isSupported;
		}
		catch (e) {
			return false;
		}
	}

	// Fire on document ready.
	$( document ).ready( function() {

		// If navigation menu is present on page, setNavProps and adjustScrollClass.
		if ( $navigation.length ) {
			setNavProps();
			adjustScrollClass();
		}

		// If 'Scroll Down' arrow in present on page, calculate scroll offset and bind an event handler to the click event.
		if ( $menuScrollDown.length ) {

			if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
				menuTop -= 32;
			}
			if ( $( 'body' ).hasClass( 'blog' ) ) {
				menuTop -= 30; // The div for latest posts has no space above content, add some to account for this.
			}
			if ( ! $navigation.length ) {
				navigationOuterHeight = 0;
			}

			$menuScrollDown.click( function( e ) {
				e.preventDefault();
				$( window ).scrollTo( '#primary', {
					duration: 600,
					offset: { top: menuTop - navigationOuterHeight }
				});
			});
		} 

		adjustHeaderHeight();
		setQuotesIcon();
		if ( true === supportsInlineSVG() ) {
			document.documentElement.className = document.documentElement.className.replace( /(\s*)no-svg(\s*)/, '$1svg$2' );
		}

		if ( true === supportsFixedBackground() ) {
			document.documentElement.className += ' background-fixed';
		}
	});

	// If navigation menu is present on page, adjust it on scroll and screen resize.
	if ( $navigation.length ) {

		// On scroll, we want to stick/unstick the navigation.
		$( window ).on( 'scroll', function() {
			adjustScrollClass();
			adjustHeaderHeight();
		});

		// Also want to make sure the navigation is where it should be on resize.
		$( window ).resize( function() {
			setNavProps();
			setTimeout( adjustScrollClass, 500 );
		});
	}

	$( window ).resize( function() {
		clearTimeout( resizeTimer );
		resizeTimer = setTimeout( function() {
			belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
		}, 300 );
		setTimeout( adjustHeaderHeight, 1000 );
	});

	// Add header video class after the video is loaded.
	$( document ).on( 'wp-custom-header-video-loaded', function() {
		$body.addClass( 'has-header-video' );
	});



// // Home Form by Ajax
	$(document.body).on('click', '#js-submit', function(e) {
		e.preventDefault();
		$.ajax({
            type: 'POST',
            url: public_localizer.ajax_url,
            data: {
				action: 'my_user_ajax',
                fields: $('form#custom-form').serialize()
			},
			success: function(data){                 
				}
		})      
	})



//email and Social account validation Ajax
$(document.body).on('click', '#js-submit', function(e) {
	 //e.preventDefault();
	var lastname = $("#lastname").val();
	var firstname = $("#firstname").val();
	// console.log(lastname);
	$.ajax({
		type: 'POST',
		url: public_localizer.ajax_url,
		data: {
			action: 'email_validation',
			fields: $('form#custom-form').serialize(),
		},
		success: function(res){
			// console.log(res);
			var data = JSON.parse(res);
			if( data.status == 'success' && $("#lastname").val() != ""  && $("#firstname").val() != "" && $("#email").val() != "") {
				window.location.replace(public_localizer.home_url + "second-signup-page/");	 
			}
			if( $("#lastname").val() == "") {
				$('#result').html("Please enter your last Name."); 
			}
			if( $("#firstname").val() == "") {
				$('#result').html("Please enter your first Name."); 
			}

			if( $("#email").val() == "") {
				$('#result').html("Please enter a valid email."); 
			}

			if( data.status == 'error' ) {
				// erro message
				$('#result').html(data.msg);			
			}				
        }		
	})      
})


// 2nd form validation
	$(document.body).on('click', '#js-second-submit', function(e) {
		var $this = $(this);	
	    e.preventDefault();

	    var identifier = $("#identifier").val();
	    var provider = $("#provider").val();
		var first_name = $("#first_name").val();
		var last_name = $("#last_name").val();
		var email = $("#email").val();
		var name = $("#name").val();
		var password = $("#password").val();
		var password_again = $("#password_again").val();
		var address1 = $("#address1").val();
		var address2 = $("#address2").val();
		var city = $("#city").val();
		var postcode = $("#postcode").val();
		var states = $("#states").val();
		var country = $("#country").val();
		var mobile = $("#mobile").val();
		var phone = $("#phone").val();
		var inputTimezone = $("#inputTimezone").val();
		var passwordSize= password.length;

	   $.ajax({
		   type: 'POST',
		   url: public_localizer.ajax_url,
		   data: {
			   action: 'my_second_user_ajax',
			   fields: $('form#custom-form-two').serialize()
		   },
		   success: function(res) {

            if( $("#identifier").val()== "" ){
			if(passwordSize >= 8 && ( $("#password").val() == $("#password_again").val()) && $("#email").val()!= "" && $("#first_name").val()!= "" && $("#last_name").val()!= "" && $("#name").val() != "" && $("#url_input").val() != ""  && $("#password").val() != "" && $("#password_again").val() != ""  && $("#address1").val() != ""&& $("#address2").val() != ""  && $("#city").val() != ""&& $("#postcode").val() != ""  && $("#states").val() != ""&& $("#country").val() != ""  && $("#mobile").val() != "" && $("#phone").val() != ""  && $("#inputTimezone").val() != "" ) {
				window.location.replace(public_localizer.home_url + "end-signup");	
				$this.button('loading');
				setTimeout(function() {
					$this.button('reset');
				}, 15000);
			}
			if( $("#inputTimezone").val() == "") {
				$('#result2').html("Please Enter Your Timezone."); 
			}
			if( $("#mobile").val() == "") {
				$('#result2').html("Please Enter Your Mobile Number."); 
			}
			if( $("#phone").val() == "") {
				$('#result2').html("Please Enter Your Phone Number."); 
			}
			if( $("#states").val() == "") {
				$('#result2').html("Please Enter Your State."); 
			}
			if( $("#country").val() == "") {
				$('#result2').html("Please Enter Your Country."); 
			}
			if( $("#postcode").val() == "") {
				$('#result2').html("Please Enter Your Postcode."); 
			}
			if( $("#city").val() == "") {
				$('#result2').html("Please Enter Your City."); 
			}
			if( $("#address2").val() == "") {
				$('#result2').html("Please Enter Your Address-2."); 
			}
			if( $("#address1").val() == "") {
				$('#result2').html("Please Enter Your Address-1."); 
			}
			if( $("#password").val() != $("#password_again").val()) {
				$('#result2').html("Your password and confirmation password do not match."); 
			}
			if( $("#password_again").val() == "") {
				$('#result2').html("Please Confirm Your Password."); 
			}

			// if(!/\d/.test($("#password").val())){
			// 	$('#result2').html("AZaz09-1"); 
			// }
          
			if(!/[a-z]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}
           
			if(!/[A-Z]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}

			if(!/[0-9]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}
			
			// if(/[^0-9a-zA-Z]/.test($("#password").val())){
			// 	$('#result2').html("AZaz09-3"); 
			// }
            


			if ( $("#password").val().length <8) {
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
				// $('#password').after('<span class="error">Password must be at least 8 characters long</span>');
			  }
			if( $("#password").val() == "") {
				$('#result2').html("Please Enter Your Password."); 
			}
			if( $("#url_input").val() == "") {
				$('#result2').html("Please Enter Your Organisation URL."); 
			}
			if( $("#name").val() == "") {
				$('#result2').html("Please Enter Your Organisation Name."); 
			}
			if( $("#first_name").val() == "") {
				$('#result2').html("Error - Your first name is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
			if( $("#last_name").val() == "") {
				$('#result2').html("Error - Your last name is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
			if( $("#email").val() == "") {
				$('#result2').html("Error - Your email is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
		}


		if( $("#identifier").val() != "" ){
			var data = JSON.parse(res);
			if(passwordSize >= 8 && ( $("#password").val() == $("#password_again").val()) && $("#email").val()!= "" && $("#first_name").val()!= "" && $("#last_name").val()!= "" && $("#name").val() != "" && $("#url_input").val() != ""  && $("#password").val() != "" && $("#password_again").val() != ""  && $("#address1").val() != ""&& $("#address2").val() != ""  && $("#city").val() != ""&& $("#postcode").val() != ""  && $("#states").val() != ""&& $("#country").val() != ""  && $("#mobile").val() != "" && $("#phone").val() != ""  && $("#inputTimezone").val() != "" ) {
				window.location.replace(public_localizer.home_url + "end-signup");	
				$this.button('loading');
				setTimeout(function() {
					$this.button('reset');
				}, 15000);
			}

			if( $("#inputTimezone").val() == "") {
				$('#result2').html("Please Enter Your Timezone"); 
			}
			if( $("#mobile").val() == "") {
				$('#result2').html("Please Enter Your Mobile Number"); 
			}
			if( $("#phone").val() == "") {
				$('#result2').html("Please Enter Your Phone Number"); 
			}
			if( $("#country").val() == "") {
				$('#result2').html("Please Enter Your Country"); 
			}
			if( $("#states").val() == "") {
				$('#result2').html("Please Enter Your State"); 
			}
			if( $("#postcode").val() == "") {
				$('#result2').html("Please Enter Your Postcode"); 
			}
			if( $("#city").val() == "") {
				$('#result2').html("Please Enter Your City"); 
			}
			if( $("#address2").val() == "") {
				$('#result2').html("Please Enter Your Address-2"); 
			}
			if( $("#address1").val() == "") {
				$('#result2').html("Please Enter Your Address-1"); 
			}
			if( $("#password").val() != $("#password_again").val()) {
				$('#result2').html("Your password and confirmation password do not match."); 
			}
			if( $("#password_again").val() == "") {
				$('#result2').html("Please Confirm Your Password."); 
			}

			// if(!/\d/.test($("#password").val())){
			// 	$('#result2').html("AZaz09-1"); 
			// }
          
			if(!/[a-z]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}
           
			if(!/[A-Z]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}

			if(!/[0-9]/.test($("#password").val())){
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
			}
			
			// if(/[^0-9a-zA-Z]/.test($("#password").val())){
			// 	$('#result2').html("AZaz09-3"); 
			// }
            


			if ( $("#password").val().length <8) {
				$('#result2').html("Please enter upper case letter [A-Z] , lower case letter [a-z] and number [0-9]. Ex: AZaz09AZaz09 Note: Password must be at least 8 characters long."); 
				// $('#password').after('<span class="error">Password must be at least 8 characters long</span>');
			  }
			if( $("#password").val() == "") {
				$('#result2').html("Please Enter Your Password."); 
			}
			if( $("#url_input").val() == "") {
				$('#result2').html("Please Enter Your Organisation URL"); 
			}
			if( $("#name").val() == "") {
				$('#result2').html("Please Enter Your Organisation Name"); 
			}
			if( $("#first_name").val() == "") {
				$('#result2').html("Error - Your first name is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
			if( $("#last_name").val() == "") {
				$('#result2').html("Error - Your last name is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
			if( $("#email").val() == "") {
				$('#result2').html("Error - Your email is empty!! Please go back and re-enter your first name, last name, and email."); 
			}
			if( data.status == 'error' ) {
				// erro message
				$('#result2').html(data.msg);
				$('#result3').html("Error!! Please go back and re-enter your information.");		
			}
		}
		   }           
	   });
   })



// // Url Validation
	$(document.body).on('keyup', '#url_input', function(e) {
	e.preventDefault();
	   $.ajax({
			type: 'POST',
			url: public_localizer.ajax_url,
			data: {
				action: 'url_validation',
				fields: $('form#custom-form-two').serialize()
			},
			success: function(res) {
				var data = JSON.parse(res);
				console.log(data);
				if(data.status == "error") {
					$('#messageurl2').html( data.msg);
					$('#js-second-submit').hide();
				}
                if(data.status == "success") {
					$('#messageurl2').html( "");
					$('#js-second-submit').show();
				}   		 
			}           
	   });
   })



//help pop up
		$(function() {
			var moveLeft = -200;
			var moveDown = -280;
			$('a.trigger').hover(function(e) {
			$('div#pop-up').show();
			//.css('top', e.pageY + moveDown)
			//.css('left', e.pageX + moveLeft)
			//.appendTo('body');
			}, function() {
			$('div#pop-up').hide();
			});
			$('a.trigger').mousemove(function(e) {
			$("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
			});
		});

  var base_url = public_localizer.home_url;
  console.log(base_url);



// // country-States api
	$(document.body).on('change', '#country', function(e) {
		e.preventDefault();
		var html;
		$.ajax({
            type: 'POST',
            url: public_localizer.ajax_url,
            data: {
				action: 'country_states',
                fields: $('form#custom-form-two').serialize()
			},
			success: function(data){ 
				var states_try = JSON.parse(data);
				console.log(states_try.status);
				if(states_try.status == "error"){
					html += `
					<option value="`+"no-state"+`">`+"no-state"+`</option>
					`;
				   // console.log(html);
				   $('.form-group select#states').html(html);
				}

				else{
									// console.log(data);
				// var states_try = JSON.parse(data);
				//console.log(states_try.states);
				Object.keys(states_try.states).forEach(function (key){
					//console.log(states_try.states[key]);
								html += `
				 <option value="`+states_try.states[key]+`">`+states_try.states[key]+`</option>
				 `;
				// console.log(html);
				$('.form-group select#states').html(html);
				});
				}
			}
		})      
	})

})( jQuery );

