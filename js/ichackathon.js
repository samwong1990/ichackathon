var id = 2;
function addMemberHTML(){
	$newMember = $(
		'			<fieldset>' +
		// '				<legend>Member ' + id + '</legend>' +
		'				<div class="clearfix">' +
		'					<label for="member' + id + '">Member ' + id + ': Name</label>' +
		'					<div class="input">' +
		'						<input class="xlarge" id="member' + id + '" name="member' + id + '" size="30" type="text" placeholder="John Doe">' +
		'					</div>' +
		'				</div><!-- /clearfix -->' +
		'				<div class="clearfix">' +
		'					<label for="member' + id + 'email">Member ' + id + ': Email</label>' +
		'					<div class="input input-append">' +
		'						<input class="xlarge placeholderRight" id="member' + id + 'email" name="member' + id + 'email" size="20" type="text" placeholder="john.doe97" style="width: 167px;"><span class="add-on emaildomain">@imperial.ac.uk</span>' +
		'					</div>' +
		'				</div><!-- /clearfix -->' +
		'			</fieldset>');
		id++;
		return $newMember;
	}

	$(document).ready(function(){
		
		/* Form validation */
		$('#formElt').submit(function(){
			console.log("validating");
			var validity = true;
			$('input').each(
				function(){
					if($(this).val() == ""){
						$(this).parents('.clearfix').removeClass('error success warning').addClass('error');
						validity = false;
						console.log("found problem");
					}else{
						$(this).parents('.clearfix').removeClass('error success warning').addClass('success');
						console.log("no problem");
					}
					console.log(validity);
				}
			);
			if(validity){
				$('#formElt input[type="submit"]')
				.addClass('success')
				.removeClass('primary error');
			}else{
				$('#formElt input[type="submit"]')
				.addClass('error')
				.removeClass('primary success');
			} 
			return validity;
		});
		/* End Form validation */
		
		$("#addmember").click(function() {
			if(id < 6){
				addMemberHTML().insertBefore("#formElt > fieldset:last");
				$("#removemember").text("Member--");
				if(id == 6) $("#addmember").text("Maxed");
			}
		});
		$("#removemember").click(function(){
			if(id > 2){
				id--;
				$("#formElt > fieldset:last").prev().remove();
				$("#addmember").text("Member++");
				if(id == 2) $("#removemember").text("Min");
			}
		});
		
		$(".remove").click(function() {
			$(this).parent().remove();
		});
		$('#signup_form_html').hide();
		
		$('#tldrbtn').click(
			function(){
				if($('#tldr_signup_form #signup_form_html').length > 0){
					$('#signup_form_html').slideToggle();
				}else{
					$('#signup_form_html').slideUp('slow', function(){
						$('#tldr_signup_form').append($('#signup_form_html'));
						$('#signup_form_html').slideDown();
					});
				}	
			}
		);
		$('#main_signup').click(
			function(){
				if($('#main_signup_form #signup_form_html').length > 0){
					$('#signup_form_html').slideToggle();
				}else{
					$('#signup_form_html').slideUp('slow', function(){
						$('#main_signup_form').append($('#signup_form_html'));
						$('#signup_form_html').slideDown();
					});
				}
			}
		);
		$('.show_signup_form').click(function(){
			$('#tldr_signup_form').append($('#signup_form_html'));
			$('#signup_form_html').slideDown();
			setTimeout(hotfix_disappearing_topbar,0);
		});
	});

	function hotfix_disappearing_topbar(){
		window.scrollBy(0,1);window.scrollBy(0,-1);
	}
