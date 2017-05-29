
function showSignUpForm()
{
	$('.create').on('click', function(){
		$('.form-signup').slideDown(function(){
			$('.control-container').slideUp();
		});
	});
}

function signUpNewMember()
{

	$('.signup').on('click', function(e){
		e.preventDefault();

		var first = $('#inputFname').val();
		var last = $('#inputLname').val();
		var email = $('#inputEmail2').val();
		var payload = [{"first":first,"last":last,"reservations":[], "email":email}];

		$.ajax('http://localhost/json-nator/api.php/user',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				$('.info-notification').html('<h2>' + 'Your account has been registered' + '</h2>');
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not register your account' + '</h2>');
			}
		});

	});

}
function signIn()
{
		$('.signin').on('click', function(e){
		e.preventDefault();

		var email = $('#inputEmail').val();
		var payload = [{"email":email}];

		$.ajax('http://localhost/json-nator/api.php/login',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				localStorage.setItem('userInfo', JSON.stringify(data));
				displayUserControlsOnLogin(data);
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	});
}

function displayUserControlsOnLogin(data)
{
	var userInfo = localStorage.getItem('userInfo', JSON.stringify(data));
	var userInfo = JSON.parse(userInfo);
	$('.userInfoArea h2').html('<h2>Welcome ' + userInfo['first'] + ' ' + userInfo['last'] + '</h2>');
	$('.control-container').slideUp(function(){
		$('body .userInfoArea').slideDown();
	});
}

function showReservationForm()
{
	$('#create').on('click', function(){
		$.when($('.open').slideUp()).done(function(){
			$('.create-reservation').addClass('open').slideDown();
		})
		
	});
}
function createReservation()
{
		$('#submitRes').on('click', function(e){
		e.preventDefault();

		var email = $('#inputEmail').val();
		var payload = [{"email":email}];

		$.ajax('http://localhost/json-nator/api.php/login',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				localStorage.setItem('userInfo', JSON.stringify(data));
				displayUserControlsOnLogin(data);
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	});
}

function showUpdateReservationForm()
{
	$('#update').on('click', function(){
		$.when($('.open').slideUp()).done(function(){
			$('.update-reservation').addClass('open').slideDown();
		})
		
	});
}
function updateReservation()
{
		$('#submitRes').on('click', function(e){
		e.preventDefault();

		var email = $('#inputEmail').val();
		var payload = [{"email":email}];

		$.ajax('http://localhost/json-nator/api.php/login',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				localStorage.setItem('userInfo', JSON.stringify(data));
				displayUserControlsOnLogin(data);
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	});
}

function showDeleteReservationForm()
{
	$('#delete').on('click', function(){
		$.when($('.open').slideUp()).done(function(){
			$('.delete-reservation').addClass('open').slideDown();
		})
		
	});
}
function deleteReservation()
{
		$('#submitRes').on('click', function(e){
		e.preventDefault();

		var email = $('#inputEmail').val();
		var payload = [{"email":email}];

		$.ajax('http://localhost/json-nator/api.php/login',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				localStorage.setItem('userInfo', JSON.stringify(data));
				displayUserControlsOnLogin(data);
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	});
}
function showReservations()
{

}



function init()
{
	showSignUpForm();
	signUpNewMember();
	signIn();
	showReservationForm();
	showUpdateReservationForm();
	showDeleteReservationForm();
}

$(document).ready(init);