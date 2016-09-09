$(document).ready(function() {
	var MIN_LENGTH = 2;
	
	//Datepicker on customer_callin
	$("#eventdatepicker").datepicker({ dateFormat: "mm-dd-yy", minDate: 0 });	//datepicker on customer_callin.php
	
	//Timepicker on customer_callin
	$('#event_timepicker').timepicker();
	
	/*
	//AUTO COMPLETE FOR DRIVER ID - customer_callin.php -- KEYUP Event
	$("#drivercode").keyup(function() {
		var keyword = $("#drivercode").val();
		if (keyword.length >= MIN_LENGTH) {

			$.get( "auto-complete.php", { drivercode: keyword } )
			.done(function( data ) {
				$('#results').html('');
				var results = jQuery.parseJSON(data);
				$.each(results, function(idx, obj) {
					$('#results').append('<div class="item">' + obj.Name+'-'+obj.DriverId + '</div>');
				});
				
				/*$.each(results, function (key, data) {
					//console.log(key)
					$.each(data, function (index, data) {
						console.log('index', data)
					})
				})
				
				
			    $('.item').click(function() {
			    	var text = $(this).html();
					//split the string to get name and driver id
					var splitVal = text.split("-");
			    	$('#drivername').val(splitVal[0]);
					$('#drivercode').val(splitVal[1]);
			    })

			});	//end .done
			
		} else {
			$('#results').html('');
		}
	});
	*/
	/*
    $("#drivercode").blur(function(){
		$("#results").fadeOut(500);
	})
	.focus(function() {		
		$("#results").show();
		$("#drivername").val('');
	});
	*/
	
	//*****************************************************
	// LOOKUP DRIVER NAME ON BLUR OF DRIVERCODE FIELD
	//*****************************************************
	$("#drivercode").blur(function(){
		var keyword = $("#drivercode").val();
		$.get( "lookup_driver.php", { drivercode: keyword } )
			.done(function( data ) {
				//$('#results').html('');
				var results = jQuery.parseJSON(data);
				$.each(results, function(idx, obj) {
					//$('#results').append('<div class="item">' + obj.Name+'-'+obj.DriverId + '</div>');
					$("#drivername").val(obj.Name);
				});
		});
	})
	
});		//END DOCUMENT READY
//*******************************************************************************************