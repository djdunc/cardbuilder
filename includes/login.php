<?php 
$ref_page = $_SESSION['ref_page'];
 if (isset($_SESSION['from_reg'])){
     $from_reg = $_SESSION['from_reg'];
     unset($_SESSION['from_reg']);
 } else{ $from_reg="false";}
 if (isset($_SESSION['card_id'])){
     $ref_page = $ref_page.'&card_id='.$_SESSION['card_id'];
 }
?>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
var formChanged = false;
var baseurl = "<?php echo BASE_URL; ?>";
var ref = "<?php echo $ref_page; ?>";
var from_reg = "<?php echo $from_reg; ?>"
var action = 'controller=user&action=get&';
$(document).ready(function() {
    if (from_reg=="true"){
        displayAlertMessage('Your details have been saved, please login below.');
        from_reg="";
    }
    var validator = $("#loginform").validate({ 
         rules: { 
            username: "required", 
            password: "required", 
    },
    errorElement: "span",
     messages: {
     	 username: "Username required", 
         password: "Password required",	
    },
    
       debug:true
    });
    
   // if something is edited, show save button, and display alert on page leave
   $('#loginform .editable').bind('change paste', function() {
           handleFormChanged();
      });
    
    //bind save button
    $("#login").click(function() {
      if($("#loginform").valid()){
          $('#login').hide();
          $(".buttons").append("<div id=\"indicator\"><img src=\"assets/images/indicator.gif\" /></div>")
          $.post('includes/load_login.php', action+$("#loginform").serialize(), function(data) {
                try {
                      var user = jQuery.parseJSON(data);
                      window.location.href = baseurl+"index.php?"+ref;
                  } catch (err) {
                	  displayAlertMessage(data);//todo display real json error
                      $('#login').show();
                      $('#indicator').remove();
                  }
            }).error(function() { displayAlertMessage("Bad username/password combination"); $('#indicator').remove(); }, "json")
      }
      return false;
    });
    
   });

   function handleFormChanged() {
        $('#login').show();
        $('#fakelogin').hide();
        formChanged = true;
   }

    /* ]]> */
</script>
<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
	<div id="page-heading" class="clearfix">
	        <div class="grid-wrap">
    		<div class="grid_2 title-crumbs">
    		       <h2><?php if (isset($title)){echo $title;}else{echo 'Login';} ?></h2> 
    		</div>
    		<div class="grid_2 align_right">	
    		</div>
	</div>
</div>
</div>
<div class="container_4">
<div class="grid-wrap">
	<!-- BEGIN FORM STYLING -->
	<div class="grid_2">
		<div class="panel form">
		    <span class="message"></span>
			<div class="content no-cap">
			    <form method="post" action="index.php?do=login&ref_page=<?php echo $ref_page; ?>" name="loginform" id="loginform" class="styled login">   
			    <fieldset>
    			 <!-- Text Field -->
                    <!-- Text Field -->
					<label class="align-left" for="username">
						<span>Username<strong class="red">*</strong></span>
						<input class="textbox required editable" name="username" id="username" type="text" value="" />
					</label>
					<!-- Text Field -->
					<label class="align-left" for="password">
						<span>Password<strong class="red">*</strong></span>
						<input class="textbox required editable" name="password" id="password" type="password" value="" />
					</label>
						<!-- Buttons -->
						<div class="non-label-section">
						    <div class="buttons">
						    <p class="button medium disabled" id="fakelogin">Login</p>
						    <input type="submit" id="login" class="button medium blue" value="Login" style="display:none" />
						    </div>
						    <br /><br />
						    <p id="account_set">Don't have an account yet?, <a href="index.php?do=register">click here to register</a>.</p> 
						</div>
                    <!-- <input type="submit" name="login" id="login" value="Login" class="button blue" /> --> 
                </form>
                </div>
</div>
</div>
</div>
</div>