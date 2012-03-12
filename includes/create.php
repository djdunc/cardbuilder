 <link rel="stylesheet" href="assets/css/jquery-ui/jquery-ui-1.8.16.custom.css" type="text/css" media="all" />
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
 <?php if ($card_id==0){  ?>
 <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
 <script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/branches/dev/ui/jquery.ui.button.js"></script>
 <?php } ?>
 <script src="assets/js/jquery.jeditable.mini.js" type="text/javascript" charset="utf-8"></script>
 <script src="assets/js/jquery.jeditable.ajaxupload.js" type="text/javascript" charset="utf-8"></script>
 <script src="assets/js/jquery.ajaxfileupload.js" type="text/javascript"></script>
 <link rel="stylesheet" type="text/css" href="assets/css/jquery.taghandler.css">
 <!-- Tagg-handler -->
 <script src="assets/js/jquery.taghandler.js" type="text/javascript" charset="utf-8"></script>
 
 <script type="text/javascript">
      /* <![CDATA[ */
      var base_url = "<?php echo BASE_URL;?>";
      var owner_id = "<?php echo $_SESSION['user']->id;?>";
      var steep = ["social","technological","economic","environmental","political"];
      var event_id = "<?php echo $_SESSION['event_id']; ?>";
      var card_id = "<?php echo $card_id; ?>";
      var card_owner = '<?php if (isset($card->owner)){ echo ($card->owner); } else{ echo(''); } ?>';
      function create_card_front(){
           if (card_owner!='1'){
            var action = 'card_id='+card_id;
            $.post("includes/create_card_front.php", { card_id: card_id },
               function(data) {
                 if(data!="false"){
                     togglebuttons("Card saved.");
                 } else{
                     togglebuttons("Problem saving card.");
                 }
               });
            }
        }
        function togglebuttons(saving){
              $("#saving_message").html(saving);
              if(saving == "Card saved."){
                     $('.buttons-disab').hide();
                     $('.buttons-enab').show();
              } else{
                     $('.buttons-disab').show();
                     $('.buttons-enab').hide();
              }
          }
        function viewcard() {
             window.location.href = 'index.php?do=view&card_id='+card_id;
             return false;
        }
      $(document).ready(function() { 
          if (card_id!="0"){
                  togglebuttons("Card saved.");
              }     
              $('#question').editable(base_url+'includes/load_jeditable.php',{
                  indicator : '<img src="assets/images/indicator.gif" alt="" />',
                  cancel    : 'Cancel',
                  submit    : 'OK',
                  tooltip   : 'Click to edit...',
                  placeholder : "Add Question (3)",
                  submitdata : function() {
                          togglebuttons("Saving...");
                          return {controller:'card', card_id : card_id };
                  },
                  data: function(value) {
                      return (value);
                  },
                  callback: function(value, settings) { 
                     create_card_front();
                     var completed = (value != '') ? $("#question_info").addClass("completed") : $("#question_info").removeClass("completed");
                  }
              });
              $('#name').editable(base_url+'includes/load_jeditable.php',{
                   indicator : '<img src="assets/images/indicator.gif" alt="" />',
                   cancel    : 'Cancel',
                   submit    : 'OK',
                   tooltip   : 'Click to edit...',
                   submitdata : function() {
                           togglebuttons("Saving...");
                           return {controller:'card', card_id : card_id };
                   },
                   data: function(value) {
                       return (value);
                   },
                   callback: function(value, settings) { 
                      create_card_front();
                   }
               });
              $('#factoid').editable(base_url+'includes/load_jeditable.php',{
                  type: 'textarea',
                  indicator : '<img src="assets/images/indicator.gif" alt="" />', 
                  cancel    : 'Cancel',
                  submit    : 'OK',
                  rows:'4',
                  placeholder : "Add Factoid (5)",
                  submitdata : function() {
                          togglebuttons("Saving...");
                          return {controller:'card', card_id : card_id };
                  },
                  data: function(value) {
                    return (value == 'Add Factoid (5)') ? '' : value;
                  },
                  callback: function(value, settings) { 
                      var completed = (value != '') ? $("#fact_info").addClass("completed") : $("#fact_info").removeClass("completed");
                      create_card_front();
                   }
              }); 
              var data = {'1':'social','2':'technological','3':'economic','4':'environmental','5':'political' };
              $('.editable_select').editable(base_url+"includes/load_jeditable.php", {
                  type: 'select',
                  data: data,
                  indicator : '<img src="assets/images/indicator.gif" alt="" />',
                  tooltip   : 'Click to edit...',
                  cancel    : 'Cancel',
                  submit : "OK",
                  style  : "inherit",

                  submitdata : function() {
                      togglebuttons("Saving...");
                      return {controller:'card', id:"category_id",card_id : card_id};
                  },
                  callback: function(value, settings) {
                      var newcat = data[value].toLowerCase();
                      $(this).html(newcat);
                      $(this).parent().removeClass().addClass('category '+newcat);
                      $(this).val(value);
                      create_card_front();
                  }
              });

               $(".ajaxupload").editable(base_url+"includes/upload.php", { 
                       indicator : '<img src="<?php echo(BASE_URL) ?>assets/images/indicator.gif" alt="" />',
                       type      : 'ajaxupload',
                       submit    : 'Upload',
                       cancel    : 'Cancel',
                       tooltip   : "Click to upload...",
                       submitdata : function() {
                           togglebuttons("Saving...");
                           return {card_id : card_id};
                       },
                       callback: function(value) {
                           if (value=='true'){
                               create_card_front();
                               $("#img_info").addClass("completed");
                           } else {
                               togglebuttons("Card saved.")
                           }
                       }
              });
      });
      /* ]]> */
 </script>
 
 <script>
	    $(function(){
	    var sampleTags = new Array("<?php echo implode('","',$_SESSION['tags']); ?>");
	    var cardTags = new Array(<?php if (count($_SESSION['c_tags'])>0){echo "\"".implode('","',$_SESSION['c_tags'])."\"";} ?>);
	    //var tags = $('#tags');
         $('#tags').tagHandler({
             assignedTags:cardTags,
             availableTags: sampleTags,
             autocomplete: true,
             minChars: 2,
             updateData: { card_id: "<?php echo $card_id?>" },
             updateURL: 'includes/load_tags.php',
             autoUpdate: true,
             delimiter: " ",
             sortTags: false,
             //initLoad: false,
            //onAdd: function(tag) { alert('Added tag: ' + tag); },
            //onDelete: function(tag) { alert('Deleted tag: ' + tag);}
         });
         
	    });
	</script>
  <?php if ($card_id == 0){  ?>
      <script type="text/javascript">
      /* <![CDATA[ */
      $(document).ready(function() {
      $( "#dialog" ).dialog({
                     modal: true,
                     width:530,
                     closeOnEscape: false,
                     open: function(event, ui) { 
                         $(this).parent().find('.ui-dialog-buttonpane button:first-child').next().addClass('ui-priority-secondary');
                         $(".ui-dialog-titlebar-close").hide();
                     },
                     resizable: false,
                     title: 'Your card',
                     buttons: {
                         "create": function(){ 
                             if($("#newcard").valid()){
                                 var action = 'controller=card&action=post&'+$("#newcard").serialize()+"&type=Card&topic_id=1&owner="+owner_id;
                                 $(".ui-dialog-buttonset").html("<img src=\"assets/images/indicator.gif\" alt=\"\" /> Saving card...");
                                 $.post('includes/load.php', action, function(data) {
                                              var card = eval(jQuery.parseJSON(data));
                                              if(card.id) {
                                                   card_id = card.id;
                                                    action = "controller=eventcards&action=post&event_id="+event_id+"&card_id="+card_id;
                                                   $.post('includes/load.php', action, function(data) {
                                                          var eventcard = eval(jQuery.parseJSON(data));
                                                          if(eventcard.id) {
                                                          $("#dialog").dialog("close");
                                                          $("#dialog").remove();
                                                          $('#name').html(card.name);
                                                          $('#category_id .editable_select').html(steep[card.category_id-1]);
                                                          $('#category_id').removeClass().addClass('category '+steep[card.category_id-1]);
                                                          create_card_front();
                                                          $("#issue_info").addClass("completed");
                                                          $("#steep_info").addClass("completed");
                                                      }
                                                   }).error(function() { alert("error"); })  
                                              }
                                      }).error(function() { alert("error"); }) 
                             }
                          },
                          "cancel": function(){ window.location.href=base_url; return false;}
                     }
       });
      $( "#radio" ).buttonset();
      $("#newcard").validate({
          rules: { name: "required", category_id:"required"},
          messages: { name: "Please enter your card's issue", category_id: "Please choose a STEEP category"},
          //errorElement: "span",

      });
 });
      /* ]]> */
</script>
 <?php } ?>

 <?php if ($card_id == 0){  ?>
 <div id="dialog" title="Basic dialog" style="display:none">
 	<form id="newcard" class="dialog">
 	    <h3>1. Issue:</h3>
 	    <p>What is your card's issue? What is the force driving the change? (This should be stated simply in one or two words, you can expand on it in the next step.)</p>
 	   <p><input class="textbox l required editable" name="name" type="text" value="<?php if(isset($edit_event->name)){echo($edit_event->name);}?>" /></p>
 	   <h3>2. Category:</h3>
 	   <p>Into which STEEP category does your issue best fit? It might fit into more than one but please choose the most relevant.</p>
 	   <p id="radio">
 	   <?php foreach($steep as $key => $value){
 	       echo("<input type=\"radio\" name=\"category_id\" id=\"$value\" value=\"$key\" /><span id=\"$value\"><label for=\"$value\">$value</label></span>");
 	   } ?>
 	   <label for="category_id" class="error" generated="true"></label>
 	  </p>
 	</form>
 </div>
  <?php }?>
<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
	<div id="page-heading" class="clearfix">
		<div class="grid-wrap title-event">
		<div class="grid_2 title-crumbs">
		    <h2 id="name" class="editable"><?php if (isset($card->name)){ echo $card->name; } else{ echo'Untitled card (1)';} ?></h1>
			<h2 id="category_id" class="category <?php if (isset($card)){ echo $steep[$card->category_id]; } else{ echo'grey';} ?>"><span class="editable_select"><?php if (isset($card)){ echo $steep[$card->category_id]; } else{ echo'category (2)';} ?></span></h2>
		</div>
		<div class="grid_2 align_right pad-h1  chi">
		    <span id="saving_message">Saving card...</span>&nbsp;&nbsp;
		    <span class="buttons-enab" style="display:none">
			<a id="viewcard" href="#" onClick="viewcard()" class="button blue small">View card</a> <a href="#" onClick="trashcard()" class="button red small">Move to trash</a>
			</span>
			<span class="buttons-disab">
			<p href="#" class="button disabled small">View card</p> <?php if(isset($card_id)){ ?><p href="" class="button disabled small">Move to trash</p><?php } ?>
			</span>
		</div>
	</div>
	</div>
</div>
<!-- END PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
    <div class="grid-wrap">
	<!-- BEGIN FORM STYLING -->
	<div class="grid_3b">
		<div id="add-card" class="panel">
		    <?php if( isset($card) && $card->owner!=1 ){?>
		    <div id="img" class="ajaxupload" <?php if (isset($card->image)){ echo ('style="background:#ebebeb url('.UPLOADS_URL.$card->image.'_b.jpg) no-repeat center center"><span>Wrong image? Click here to change it.</span>'); } else{ echo('><span>Add Image (4)</span>'); }?></div>
		    <?php } else{ echo('<div id="img">&nbsp;</div>'); } ?>
		    <div id="factoid"><?php if (isset($card->factoid)){ echo $card->factoid; } else{ echo'Add Factoid (5)';} ?></div>
		    <div id="question" class="editable"><?php if (isset($card)){ echo $card->question; } else{ echo'Add Question (3)';} ?></div>
			<img class="bkg" src="assets/images/card-bkg.gif" alt="card background" />
		</div>
		<div class="panel white">
     
    	    
        </div>
	</div>
	<!-- END FORM STYLING -->
	<!-- BEGIN PLAIN TEXT EXAMPLE
	 	 The simplest one of all, just some regular ol' text in a panel. -->
	<div class="grid_1b">
		<div class="panel card_info">
    		    <h2 class="cap">Card components</h2>
    		   <div class="content" >
    		    <div id="issue_info"<?php if (isset($card->name)&&$card->name!=""){ echo " class=\"completed\""; } ?>>
				<h4>1. The issue</h4>
                <p>What is the force driving the change?</p>
                </div>
                <div id="steep_info"<?php if (isset($card->category_id)&&$card->category_id!=""){ echo "class=\"completed\""; } ?>>
                <h4>2. Category</h4>
                <p>Into which STEEP category does your issue best fit? (Social, Technological, Economic, Environmental or Political). </p>
                </div>
                <div id="question_info"<?php if (isset($card->question)&&$card->question!=""){ echo "class=\"completed\""; } ?>>
                <h4>3. Question</h4>
                <p>What question illustrates the sheer impact of your issue?</p>
                </div>
                <?php if( isset($card) && $card->owner!=1 ){?>
                <div id="img_info"<?php if (isset($card->image)&&$card->image!=""){ echo "class=\"completed\""; } ?>>
                <h4>4. Image</h4>
                <p>Upload an image, sketch or graphic to illustrate your issue or its potential consequence. (Up to 200Mb in file size. It will be cropped to fit background).</p>
                </div>
                <?php } else{?>
                 <div id="img_info" class="completed">
                <h4>4. Image</h4>
                <p>Arup cards have pre-created images, please edit file on cards folder.</p> 
                <?php } ?>
                <div id="fact_info"<?php if (isset($card->factoid)&&$card->factoid!=""){ echo "class=\"completed\""; } ?>>
                <h4>5. Factoid</h4>
                <p>Can you expand on the potential consequences of the issue? (in about 40 words or less)</p>
                <!-- <form id="tag-it" action="javascript:void(0);">
                                    <h4>Tags</h4>
                                    <p>Additionally, you can add some <b>tags</b> or keywords below to describe your card's topic:</p>
                                    
                                </form> -->
                </div>
			</div>
		</div>
		<div class="panel tag_holder">
    		    <h2 class="cap">Tags</h2>
    		        <ul id="tags"> </ul>
    		        <p>Separate tags with commas, spaces or return.</p>
    	</div>
	</div>
	</div>	
</div>