<link rel="stylesheet" type="text/css" href="assets/css/jquery.taghandler.css">
<?php 
//comments all
if (isset($card)){
$comments_json = callAPI('cardcomments?card_id='.$card->id."&include_owner=1");
    $comments = json_decode($comments_json);
    //var_dump($card);
    $date = date('j F, Y \a\t g:i a',$card->ctime);
    if ($card->owner==1){
        $card_front = ARUP_CARDS_URL.$card->card_front;
        $card_back = ARUP_CARDS_URL.$card->card_back;
    } else{
        $card_front = UPLOADS_URL.'fronts/'.$card->card_front.'.jpg';
        $card_headers = @get_headers($card_front);
        if($card_headers[0] == 'HTTP/1.1 404 Not Found') {
            $card_front="false";
        }
    }
} else{
    //@todo -- throw error
}
?>
<?php if (!isset($card)){ ?>
    <div class="container_4">
    	<div id="page-heading" class="clearfix">
    		<div class="grid-wrap title-event">
    		<div class="grid_2 title-crumbs">
    		    <h2 id="name" class="">Card not found</h2>
    		</div>
    	</div>
    </div>
<?php }else{ ?>
<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
	<div id="page-heading" class="clearfix">
		<div class="grid-wrap title-event">
		<div class="grid_2 title-crumbs">
		    <h2 id="name" class=""><?php if (isset($card->name)){ echo $card->name;} ?></h1>
			<?php if (isset($card->category_id)){?><h2 id="category_id" class="category <?php echo $steep[$card->category_id]; ?>"><?php echo $steep[$card->category_id]; ?></h2><?php }?>
		</div>
		<div class="grid_2 align_right pad-h1  chi">
			<?php if ((isset($_SESSION['user']->id) && $card->owner==$_SESSION['user']->id) || (isset($_SESSION['user']->id) && $_SESSION['user']->id==$_SESSION['event_owner']) ){?><a href="index.php?do=create&card_id=<?php echo $card->id ?>" class="button blue small">Edit card</a><?php }?>
		</div>
	</div>
	
	</div>
</div>
<!-- END PAGE BREADCRUMBS/TITLE -->

<div class="container_4">
    <div class="grid-wrap">
	<!-- BEGIN FORM STYLING -->
	<div class="grid_3b">
		<div class="panel">
		    <div class="card">
		   <img id="card-front" src='<?php echo $card_front; ?>' alt="<?php echo $card->name; ?> Front" />
		   	</div>
		</div>
		<?php if (isset($card_back)){ ?>
		<div class="panel">
	   	<div class="card">
	   <img id="card-back" src='<?php echo $card_back; ?>' alt="<?php echo $card->name; ?> Back" />
	   	</div>
	   	</div>
	   	<?php } ?>
	</div>
	<!-- END FORM STYLING -->
    	<div class="grid_1b">
    		<div class="panel">
    			<div class="content no-cap">
    			    <p>Made by <span class="author"><?php echo $owner_name ?></span><br />
    			    on <?php echo $date ?>.</p>
    			    <div class="card-options">
    			        <!-- <div class="favorite-bar"><span class="progress-icon">34</span><div class="progress-bar"><div class="bar white" style="width:30%">30 Percent</div></div></div> -->
    			        <?php if($_SESSION['user']->id){?><a class="icon-button icon-favorite" href="" title="Add to favorites">Add to Favorites</a><span class="counter" id="star-counter">&hellip;</span> <a class="icon-button icon-comment" href="#">Add Comment</a><span class="counter" id="comment-counter"><?php echo count($comments);?></span> <a class="icon-button icon-flag" href="#" title="Report as inappropriate">Report</a><span class="counter" id="flag-counter">&hellip;</span><?php }else{?><p class="icon-button icon-favorite" href="" title="Add to favorites">Favorites</p><span class="counter" id="star-counter">&hellip;</span> <p class="icon-button icon-comment" href="#"> Comments</p><span class="counter" id="comment-counter"><?php echo count($comments);?></span> <p class="icon-button icon-flag" href="#" title="Report">Report</p><span class="counter" id="flag-counter">&hellip;</span><?php }?>&nbsp;&nbsp;<a class="icon-button icon-send" href="mailto:?subject=<?php echo $card->question; ?>&amp;body=Drivers of Change: <?php echo $card->name; ?>">Share</a>
    			    </div>
    			</div>
    		</div>
    		<?php if (isset($_SESSION['c_tags'])&&count($_SESSION['c_tags'])!=0){?>
    		<div class="panel card_info">
        		    <h2 class="cap">Tags</h2>
        		    <div class="content">
        		        <ul id="tag-list">
        		          <?php foreach($_SESSION['c_tags'] as $tag){
        		              echo("<li>".$tag."</li>");
        		          } ?>
        		        </ul>
        		    </div>
        	</div>
        	<?php } ?>
    			<div class="panel">
    			    <h2 class="cap"><?php if (isset($comments)){ echo('Recent Comments'); }else{echo('Comments');}?></h2>
        			<div id="comments" class="content">
        			<?php if (isset($comments)){ 
        			    foreach ((array_slice($comments, 0, 5)) as $comment) { 
        			        $com_owner = $comment->owner_user;
        			        $com_owner_name = $com_owner->first_name.' '.$com_owner->last_name;?>
        				<div class="comment"><p><?php echo $comment->message ?><p class="date-added"><span class="author"><?php echo ($com_owner_name); ?></span> on <?php echo date('j F, Y \a\t g:i A', $comment->ctime); ?></p></div>
                       <?php unset($comment);unset($com_owner_name); } }?>
        			</div>
        			<div class="content no-cap">
        			    <?php if(!empty($_SESSION['user'])){?>
        			    <p id="message" class="edit_area"><span>Add a comment...</span></p>
                        <?php if (isset($comments)){?><!-- <a class="all-comments">View all comments</a> --><?php }
                        }else{?>
                            <p>You must <a href="index.php?do=login">login</a> or <a href="index.php?do=register">register</a> to comment.</p>
                        <?php }?>
        			</div>
        		</div>
    	</div>
    	</div>
    	</div>
<?php }?>
    </div>
    <script src="assets/js/jquery.jeditable.mini.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
    /* <![CDATA[ */
    var base_url = "<?php echo BASE_URL;?>";
    var uploads_url = "<?php echo UPLOADS_URL;?>";
    var card_front = "<?php echo $card_front;?>";
    var card_id = "<?php echo $card_id; ?>";
    var curr_user_id = "<?php if(isset($_SESSION['user']->id)){ echo $_SESSION['user']->id; }else{ echo (''); }?>";
    var curr_user = "<?php if(isset($_SESSION['user']->id)){ echo $_SESSION['user_name'];} else{ echo(''); }?>";
    var c_counter = parseInt("<?php echo count($comments);?>");
    var s_counter = 0;
    var f_counter = 0;
    var is_fav = false;
    var is_flagged = false;
    function countComments(){
        if (c_counter>0){
            $(".icon-comment").addClass("full");
        }
    }
    function countStars(){
         $.post('includes/load.php', 'controller=cardtags&action=get&card_id='+card_id+'&tag_id=7', function(data) {
                   var stars = jQuery.parseJSON(data);
                   if (stars.length>0){
                       s_counter = stars.length;
                       $("#star-counter").html(s_counter);
                       jQuery.each(stars, function() {
                         if(this.owner==curr_user_id){
                             $(".icon-favorite").addClass("full");
                             is_fav = true;
                             return false;
                         }
                       });
                   } else{$("#star-counter").html(s_counter);}
          })
    }
    function countFlags(){
         $.post('includes/load.php', 'controller=cardtags&action=get&card_id='+card_id+'&tag_id=8', function(data) {
                   var flags = jQuery.parseJSON(data);
                   if (flags.length>0){
                       f_counter = flags.length;
                       $("#flag-counter").html(f_counter);
                       jQuery.each(flags, function() {
                         if((this.owner == curr_user_id) && this.tag_id==8){
                             $(".icon-flag").addClass("full");
                             is_flagged = true;
                             return false;
                         }
                       });
                   } else{$("#flag-counter").html(s_counter);}
          })
    }
    function toggle_fav(){
        function toggle_display(bol){
            is_fav = bol;
            (bol) ? s_counter ++ : s_counter --;
            $("#star-counter").html(s_counter);
            $(".icon-favorite").toggleClass("full");
        }
        var action = (is_fav) ? "delete" : "post";
        $("#star-counter").html("&hellip;");
        $.post('includes/load.php', 'controller=cardtags&action='+action+'&card_id='+card_id+'&tag_id=7&owner='+curr_user_id, function(data) {
                  if (action=='delete' && data=='false'){
                        toggle_display(false);
                  } else if (action=='post' && data){
                      var star = eval(jQuery.parseJSON(data));
                      if (star.tag_id==7){
                           toggle_display(true);
                     }
                  } else{ alert("Error");}
                }).error(function() { alert("Error"); })
    }
    function toggle_flag(){
        function toggle_display(bol){
            is_flagged = bol;
            (bol) ? f_counter ++ : f_counter --;
            $("#flag-counter").html(f_counter);
            $(".icon-flag").toggleClass("full");
        }
        var action = (is_flagged) ? "delete" : "post";
        $("#flag-counter").html("&hellip;");
        $.post('includes/load.php', 'controller=cardtags&action='+action+'&card_id='+card_id+'&tag_id=8&owner='+curr_user_id, function(data) {
                  if (action=='delete' && data=='false'){
                        toggle_display(false);
                  } else if (action=='post' && data){
                      var flag = eval(jQuery.parseJSON(data));
                      if (flag.tag_id==8){
                           toggle_display(true);
                     }
                  } else{ alert("Error");}
                }).error(function() { alert("Error"); })
    }
    function timeConverter(UNIX_timestamp){
     var a = new Date(UNIX_timestamp*1000);
         var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
         var year = a.getFullYear();
         var month = months[a.getMonth()];
         var date = a.getDate();
         var hour = a.getHours();
         var min = a.getMinutes();
         var ap = "AM";
         if (hour   > 11) { ap = "PM";        }
         if (hour   > 12) { hour = hour - 12; }
         if (hour   == 0) { hour = 12;        }
         if (min < 10) { min = "0" + min; }
         var time = date+' '+month+', '+year+' at '+hour+':'+min+' '+ap;
         return time;
     }
     function create_card_front(){
          //alert(base_url+'includes/create_card_front.php?'+'card_id='+card_id);
           var action = 'card_id='+card_id;
           $.post("includes/create_card_front.php", { card_id: card_id },
              function(data) {
                if(data){
                    $('#card-front').attr("src", uploads_url+'fronts/'+data+'.jpg');
                    $('#card-front').fadeIn('slow');
                    $('#indicator').remove();
                }
              });
       }
    $(document).ready(function() {
        
    	//$('a.clue').aToolTip();
    	//
        if (card_front=="false"){
            $("#card-front").after('<p id="indicator"><img src="assets/images/indicator.gif" alt="" /> Generating image...</p>');
            $('#card-front').hide();
            create_card_front();
        }
    	$('#add-card').hide();
    	$('#add-card').fadeIn('slow');
    	countStars();
    	countComments();
    	countFlags();
        $('.edit_area').editable(base_url+'includes/load_jeditable.php',{
            type: 'textarea',
            indicator : '<img src="assets/images/indicator.gif" alt="" />', 
            cancel    : 'Cancel',
            submit    : 'OK',
            rows:'4',
            submitdata : function() {
                    return {controller:'comment', action:'post', card_id : '<?php echo $card->id ?>' , owner:'1'};
            },
            data: function(value) {
              return (value == '<span>Add a comment...</span>') ? '' : value;
            },
            callback: function(value, settings) {
                 var comment = eval(jQuery.parseJSON(value));
                 if(comment.id){
                 //create a container for the new comment
                 var div = $("<div>").addClass("comment").appendTo("#comments");
                 div.hide();
                 //add author name and comment to container
            	 $("<p>").html(comment.message).appendTo(div);
            	 var cDate= timeConverter(comment.ctime);
            	 $("<p>").addClass("date-added").html("<span class=\"author\">"+curr_user+"</span> - "+cDate).appendTo(div);
            	 div.fadeIn("slow");
                 $(this).html("<span>Add a comment...</span>");
                 c_counter++;
                  $('#comment-counter').text(c_counter);
                 //if($("#comments").height>300){alert("300");}
                 if($('#comments .comment').size()>5){
                     $('#comments').find('.comment:lt(1)').remove();
                 };
                }
            }
        });
        
        if(curr_user_id!=''){
            $(".icon-favorite").click(function() {
                 toggle_fav();
                 return false;
               });
            $(".icon-flag").click(function() {
                toggle_flag();
                return false;
              });
        }
        
    });
    /* ]]> */
     </script>