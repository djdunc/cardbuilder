<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
var formChanged = false;
var baseurl = "<?php echo BASE_URL; ?>";
var action = 'controller=user&action=post&';
$(document).ready(function() {
    
    var validator = $("#register").validate({ 
         rules: { 
            firstname: "required", 
            lastname: "required", 
            username: { 
                required: true, 
                 remote: {
                     url:baseurl+'includes/username_unique.php',
                     type: 'post',
                 }
            },
            password: { 
                required: true, 
                minlength: 5 
            }, 
            password_confirm: { 
                required: true, 
                minlength: 5, 
                equalTo: "#password" 
            },
            email:{
                email:true
            }
    },
     messages: {
     			password_confirm: {
     				required: " ",
     				equalTo: "Please enter the same password as above"	
     			},
     			username: {
     				required: "Username is required",
     				remote: jQuery.validator.format("{0} is already taken, please enter a different username.")	
     			}
    },
           // debug:true
     
     
    });
    
   // if something is edited, show save button, and display alert on page leave
   $('#register .editable').bind('change paste', function() {
           handleFormChanged();
      });
    
    //bind save button
    $("#save").click(function() {
      if($("#register").valid()){
          $(window).unbind("beforeunload");
          $("#fakesave").html("Sending...").show();
          $("#save").hide();
          if($("#email").val()!=''){
              var fields = $($("#register")[0].elements).not("#password_confirm").serialize();
          }else{
              var fields = $($("#register")[0].elements).not("#email").serialize();
          }  
           $.post('includes/load.php', action+fields, function(data) {
               displayAlertMessage(data);
            var user = eval(jQuery.parseJSON(data));
               if(user.username){
                   window.location.href = baseurl+"index.php?do=login";
              } else{
                    displayAlertMessage(data);
            }
           }).error(function() { alert("error"); }, "json")
      }
      return false;
    });
    
   });

   function handleFormChanged() {
        $(window).bind('beforeunload', confirmNavigation);
     $('#save').show();
        $('#fakesave').hide();
        formChanged = true;
   }

   function confirmNavigation() {
        if (formChanged) {
             return ('One or more forms on this page have changed. Your changes will be lost!');
        } else {
             return true;
        }
   }
    /* ]]> */
</script>
<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
	<div id="page-heading" class="clearfix">
	        <div class="grid-wrap">
    		<div class="grid_2 title-crumbs">
    		       <h2 id="title">Create an account</h2>
    		</div>
    		<div class="grid_2 align_right">
    				
    		</div>
	</div>
</div>
</div>
<!-- END PAGE BREADCRUMBS/TITLE -->

<!-- BEGIN FULL WIDTH ERROR BLOCK -->
<!-- <div class="container_4 push-down">
    <div class="alert-wrapper warning clearfix">
        <div class="alert-text">
            This is a warning! You can warn users of important things with this alert style.
            <a href="#" class="close">Close</a>
        </div>
    </div>
</div> -->
<!-- END FULL WIDTH ERROR BLOCK -->
<div class="container_4">
<div class="grid-wrap">
	<!-- BEGIN FORM STYLING -->
	<div class="grid_3">
		<div class="panel form">
		    <span class="message"></span>
			<div class="content no-cap">
				<!-- Any form you want to use this custom styling with must have the class "styled" -->
				<form id="register" class="styled">
					<fieldset>
					    <!-- Text Field -->
						<label class="align-left" for="name">
							<span>First name</span>
							<input class="textbox m editable" name="first_name" id="first_name" type="text" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="name">
							<span>Last name</span>
							<input class="textbox m editable" name="last_name" id="last_name" type="text" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="username">
							<span>Choose a username<strong class="red">*</strong></span>
							<input class="textbox required editable" name="username" id="username" type="text" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="password">
							<span>Password<strong class="red">*</strong></span>
							<input class="textbox required editable" name="password" id="password" type="password" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="name">
							<span>Confirm password<strong class="red">*</strong></span>
							<input class="textbox required editable" name="password_confirm" id="password_confirm" type="password" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="email">
							<span>Email address</span>
							<input class="textbox m editable" name="email" id="email" type="text" value="" />
						</label>
						<!-- Text Field -->
						<label class="align-left" for="name">
							<span>Organisation</span>
							<input class="textbox m editable" name="organisation" id="organisation" type="text" value="" />
						</label>
						<input type="hidden" name="group_id" value="1" />
							<!-- Tick box -->
    					<div class="non-label-section">
    						    <p class="check-pair">
                                <label>
                                    <input type="hidden" name="admin_request" value="false" />
                                    <input type="checkbox" name="admin_request" value="true"> I'd like to apply to set up my own events</label></p>
                        </div>
						<!-- Buttons -->
						<div class="non-label-section" id="save_btns">
						    <p class="button medium disabled" id="fakesave">Register</p>
						    <input type="submit" id="save" class="button medium blue" value="Register" style="display:none" />
						    <a href="index.php" class="button medium">Cancel</a>
						</div>
					
					</fieldset>
				</form>
				<div id="login"></div>
			</div>
		</div>
	</div>
	
	<!-- END FORM STYLING -->
	<div class="grid_1">
		<div class="panel">
		    <h2 class="cap">Lorem Ipsum</h2>
		    	<div class="content">
		    	    <p><strong class="red">*</strong> Indicates required fields</p>
    				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    			</div>
		</div>
	</div>
</div>
</div>