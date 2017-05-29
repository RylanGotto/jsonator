function initDateTimePickers()
{
	        $('#datetimepicker6').datetimepicker();
            $('#datetimepicker7').datetimepicker({
                useCurrent: false //Important! See issue #1075
            });
            $("#datetimepicker6").on("dp.change", function (e) {
                $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            });
            $("#datetimepicker7").on("dp.change", function (e) {
                $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
            });


            $('#datetimepicker8').datetimepicker();
            $('#datetimepicker9').datetimepicker({
                useCurrent: false //Important! See issue #1075
            });
            $("#datetimepicker8").on("dp.change", function (e) {
                $('#datetimepicker9').data("DateTimePicker").minDate(e.date);
            });
            $("#datetimepicker9").on("dp.change", function (e) {
                $('#datetimepicker8').data("DateTimePicker").maxDate(e.date);
            });
}
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
				$('.info-notification').html('<h2>' + 'Your account has been registered.' + '</h2>');
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not register your account.' + '</h2>');
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
				showUserControlsOnLogin(data);

			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	});
}

function showUserControlsOnLogin(data)
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
		$('#createReservation').on('click', function(e){
			e.preventDefault();
			var depart = $('#datetimepicker6 input').val();
			var returnDate = $('#datetimepicker7 input').val();

			var payload = [{"depart":depart, "return":returnDate}];

		$.ajax('http://localhost/json-nator/api.php/reservation',{
		    'data': JSON.stringify(payload) ,
		    'type': 'POST',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				id = data['id'];
				var userInfo = localStorage.getItem('userInfo');
				userInfo = JSON.parse(userInfo);
				userID = userInfo['id'];
				userInfo['reservations'].push(id);
				localStorage.setItem('userInfo', JSON.stringify(userInfo));

				var payload2 = [{"first":userInfo['first'], "last":userInfo['last'], "reservations":userInfo['reservations'], "email":userInfo['email']}];

					$.ajax('http://localhost/json-nator/api.php/user/' + userID,{
					    'data':  JSON.stringify(payload2),
					    'type': 'PUT',
					    'processData': false,
					    'contentType': 'application/json',
						'success':function(data){
							$('.info-notification').html('<h2>' + 'Your Reservation has been made.' + '</h2>');

						},
						'error':function(){
							$('.info-notification').html('<h2>' + 'Something went wrong, we could not save your reservation.' + '</h2>');
						}
					});


			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'Something went wrong, we could not save your reservation' + '</h2>');
			}
		});

	});
}

function showUpdateReservationForm()
{
	$('#update').on('click', function(){
		getReservations();
		$.when($('.open').slideUp()).done(function(){
			$('.update-reservation').addClass('open').slideDown(function(){
				$('.reservations').addClass('open').slideDown();
			});
			
		})
		
	});
}
function updateReservation()
{
		$('#updateReservation').on('click', function(e){
			e.preventDefault();
			

			var id = $('#idToUpdate').val();

			var userInfo = localStorage.getItem('userInfo');
			userInfo = JSON.parse(userInfo);

			var index = userInfo['reservations'].indexOf(parseInt(id, 10));

			if(index > -1){
				var depart = $('#datetimepicker8 input').val();
				var returnDate = $('#datetimepicker9 input').val();
				var payload = [{"depart":depart, "return":returnDate}];

				$.ajax('http://localhost/json-nator/api.php/reservation/' + id,{
				    'data': JSON.stringify(payload) ,
				    'type': 'PUT',
				    'processData': false,
				    'contentType': 'application/json',
					'success':function(data){
						$('.info-notification').html('<h2>' + 'Your reservation has been updated.' + '</h2>');
						getReservations();
					},
					'error':function(){
						$('.info-notification').html('<h2>' + 'We could not update that reservation.' + '</h2>');
					}
				});
			}else{
				$('.info-notification').html('<h2>' + 'We could not update that reservation.' + '</h2>');
			}



	});
}

function showDeleteReservationForm()
{
	$('#delete').on('click', function(){
		getReservations();
		$.when($('.open').slideUp()).done(function(){
			$('.delete-reservation').addClass('open').slideDown(function(){
				$('.reservations').addClass('open').slideDown();
			});

		})
		
	});
}
function deleteReservation()
{
		$('#deleteReservation').on('click', function(e){
		e.preventDefault();

		var reservationID = $('#idToDelete').val();

		$.ajax('http://localhost/json-nator/api.php/reservation/' + reservationID,{
		    'type': 'DELETE',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				
				$('.info-notification').html('<h2>' + 'Record has been deleted.' + '</h2>');

				var userInfo = localStorage.getItem('userInfo');
				userInfo = JSON.parse(userInfo);

				var index = userInfo['reservations'].indexOf(parseInt(reservationID,10));

				if (index > -1) {
    				userInfo['reservations'].splice(index, 1);
    				localStorage.setItem('userInfo', JSON.stringify(userInfo));

    				var payload2 = [{"first":userInfo['first'], "last":userInfo['last'], "reservations":userInfo['reservations'], "email":userInfo['email']}]

    				$.ajax('http://localhost/json-nator/api.php/user/' + userInfo['id'],{
					    'data': JSON.stringify(payload2) ,
					    'type': 'PUT',
					    'processData': false,
					    'contentType': 'application/json',
						'success':function(data){
							getReservations();
						},
						'error':function(){
							$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
						}
				});

				}
			},
			'error':function(){
				$('.info-notification').html('<h2>' + 'Something went wrong, we could not delete that record.' + '</h2>');
			}
		});

	});
}
function showReservations()
{
	$('#view').on('click', function(){
		getReservations();
		$.when($('.open').slideUp()).done(function(){
			$('.reservations').addClass('open').slideDown();
		})
		
	});
}
function getReservations()
{
		$.ajaxSetup({ async: false });
		var userID = JSON.parse(localStorage.getItem('userInfo'))['id'];
		$('tbody').empty();

		$.ajax('http://localhost/json-nator/api.php/user/' + userID,{
		    'type': 'GET',
		    'processData': false,
		    'contentType': 'application/json',
			'success':function(data){
				localStorage.setItem('userInfo', JSON.stringify(data));
				var indexes = data['reservations'].length;
				for(var i = 0; i < indexes; i++){
					reservationID = data['reservations'][i];

					$.ajax('http://localhost/json-nator/api.php/reservation/' + reservationID,{
					    'type': 'GET',
					    'processData': false,
					    'contentType': 'application/json',
						'success':function(data){
							$('tbody').append('<tr><td>' + data['id'] + '</td> <td>' + data['depart'] + '</td> <td>' + data['return'] + '</td> </tr>');
						},
						'error':function(){
							$('.info-notification').html('<h2>' + 'We could not find any reservations.' + '</h2>');
						}
					});


				}
			},
			'error':function(){
				//$('.info-notification').html('<h2>' + 'We could not sign you in.' + '</h2>');
			}
		});

	
}



function init()
{
	initDateTimePickers();
	signUpNewMember();
	signIn();
	showSignUpForm();
	showReservationForm();
	showUpdateReservationForm();
	showDeleteReservationForm();
	showReservations();
	createReservation();
	deleteReservation();
	updateReservation();

}

$(document).ready(init);