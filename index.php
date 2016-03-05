<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Garage Door Opener">
     
    <link rel="icon" href="/favicon.ico"> 
    <title>Garage Door Opener</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
</head>    

<body class="header-fixed">

<div class="wrapper">

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
				<h2>Garage Door Opener</h2>
				<p>Version 1.1</p>
				<p><br><br></p>

				<div id="message" class="alert alert-dismissable alert-danger">
					<!--button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button-->
					<h4>
						<strong>Door is in progress please wait!</strong>
					</h4>
				</div>
				<hr>
			<div id="controls">
				<div class="row">
					<div class="col-xs-6">

						<button id="close-button" type="button" class="btn btn-danger">Close Door</button>
						 
					</div>
					<div class="col-xs-6">

						<button id="open-button" type="button" class="btn btn-primary">Open Door</button>
						 
					</div>
				</div>
				<hr>
				<div class="row">	
					<div class="col-md-12">	
						<h4>
							Door Status:
						</h4>
						<p>
							<span id="door-status">checking...
								<div class="progress">
								  <div class="progress-bar progress-bar-striped active" role="progressbar"
								  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
								  </div>
								</div>
							</span> 
							<img src='closed.png' width='200' height='100' id="img-closed">
							<img src='open.png'  width='200' height='100'  id="img-open">
						</p>
						 
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>

</div>

<style>
.progress.active .progress-bar {
    -webkit-transition: none !important;
    transition: none !important;
}
</style>

<script>
$(document).ready(function(){
	
	//hide elements
	$("#message").hide();
	$("#img-closed").hide();
	$("#img-open").hide();

	$(".progress-bar").animate({
	    width: "100%"
	}, 1000);

	setInterval(function(){ GetDoorStatus(); }, 1500);

    $("#close-button").click(function(){
        $("#message").show();
       	ToggleDoor(12000);
        
    });
    $("#open-button").click(function(){
        $("#message").show();
        ToggleDoor(4000);

    });

    function ToggleDoor(delay){
    	 
    	$("#controls").hide();

    	$.ajax({
                url: '/doorcontrols.php?trigger=true',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                	console.log(data);
                	if(data == "triggering"){
                		
                	}
                	
                    
                }
            });
    	
    	$("#message").delay(delay).slideUp(200, function() {
    					$("#controls").show();
					    $(this).hide();
					    
					});

    }

    function GetDoorStatus(){
    	
    	$.ajax({
                url: '/doorcontrols.php?status=true',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                	console.log(data);
                	$(".progress").hide();
                	$("#door-status").html(' ');
                	if(data == "closed"){
                		 $("#img-closed").show();
                		 $("#img-open").hide();
                	}else{
                		$("#img-closed").hide();
                		$("#img-open").show();
                	}
                	
                    
                }
            });

    }

});
	
</script>


</body>
</html>