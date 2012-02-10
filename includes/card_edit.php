<script src="assets/js/jquery.jeditable.mini.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/js/jquery.jeditable.ajaxupload.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/js/jquery.ajaxfileupload.js" type="text/javascript"></script>

<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
    $('a.clue').aToolTip();
    function togglebuttons(){
        if(($('#name').html()!='Click here to add your card\'s issue (1)')&&($('#category_id').html()!='<span class="editable_select" title="Click to edit...">select category (2)</span>')){
               $('.buttons-disab').hide();
               $('.buttons-enab').show();
        } else{
               $('.buttons-disab').show();
               $('.buttons-enab').hide();
        }
    }  
    togglebuttons();
    $('.editable').editable('<?php echo(BASE_URL) ?>includes/load_jeditable.php',{
        indicator : '<img src="assets/images/indicator.gif" alt="" />',
        cancel    : 'Cancel',
        submit    : 'OK',
        tooltip   : 'Click to edit...',
        submitdata : function() {
                return {controller:'card', card_id : <?php echo $card_id ?> };
        },
        data: function(value) {
         switch (value){
            case 'Click here to add your card\'s issue (1)':
            return ('');
            break;
            case 'Add Question (4)':
            return ('');
            break;
            default:
            return (value);
        }     
        },
        callback: function(value, settings) { 
           togglebuttons();
        }
    });
    $('.edit_area').editable('<?php echo(BASE_URL) ?>includes/load_jeditable.php',{
        type: 'textarea',
        indicator : '<img src="assets/images/indicator.gif" alt="" />', 
        cancel    : 'Cancel',
        submit    : 'OK',
        rows:'4',
        submitdata : function() {
                return {controller:'card', card_id : <?php echo $card_id ?> };
        },
        data: function(value) {
          return (value == 'Add Factoid (5)') ? '' : value;
        }
    }); 
    var data = {'1':'social','2':'technological','3':'economic','4':'environmental','5':'political' };
    $('.editable_select').editable("<?php echo(BASE_URL) ?>includes/load_jeditable.php", {
        type: 'select',
        data: data,
        indicator : '<img src="assets/images/indicator.gif" alt="" />',
        tooltip   : 'Click to edit...',
        cancel    : 'Cancel',
        submit : "OK",
        style  : "inherit",
        
        submitdata : function() {
            return {controller:'card', id:"category_id",card_id : <?php echo $card_id ?>};
        },
        callback: function(value, settings) {
            var newcat = data[value].toLowerCase();
            $(this).html(newcat);
            $(this).parent().removeClass().addClass('category '+newcat);
            $(this).val(value);
            togglebuttons();
                
        }
    });

     $(".ajaxupload").editable("<?php echo(BASE_URL) ?>includes/upload.php", { 
             indicator : '<img src="<?php echo(BASE_URL) ?>assets/images/indicator.gif" alt="" />',
             type      : 'ajaxupload',
             submit    : 'Upload',
             cancel    : 'Cancel',
             tooltip   : "Click to upload...",
             submitdata : function() {
                 return {card_id : <?php echo $card_id ?>};
             }
    });
});
/* ]]> */
 </script>
<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
	<div id="page-heading" class="clearfix">
		<div class="grid-wrap title-event">
		<div class="grid_2 title-crumbs">
		    <h2 id="name" class="editable"><?php if (isset($card->name)){ echo $card->name; } else{ echo'Click here to add your card\'s issue (1)';} ?></h1>
			<h2 id="category_id" class="category <?php if (isset($card)){ echo $steep[$card->category_id]; } else{ echo'grey';} ?>"><span class="editable_select"><?php if (isset($card)){ echo $steep[$card->category_id]; } else{ echo'select category (2)';} ?></span></h2>
		</div>
		<div class="grid_2 align_right pad-h1  chi">
		    <span class="buttons-enab">
			<a href="#" class="button blue small">Save card</a> <a href="#" class="button red small">Move to trash</a></span>
			
			<span class="buttons-disab">
			<p href="" class="button disabled small">Save card</p> <?php if(isset($card_id)){ ?><p href="" class="button disabled small">Delete card</p><?php } ?>
			</span>
		</div>
	</div>
	
	</div>
</div>
<!-- END PAGE BREADCRUMBS/TITLE -->

<div class="container_4">
    <div class="grid-wrap">
	<!-- BEGIN FORM STYLING -->
	<div id="add-card" class="grid_3b">
		<div class="panel">
		    <div id="img" class="ajaxupload" <?php if (isset($card->image)){ echo ('style="background:#ebebeb url('.UPLOADS_URL.$card->image.'_b.jpg) no-repeat center center"><span>Wrong image? Click here to change it.</span>'); } else{echo('>Add Image (3)'); }?></div>
		    <div id="factoid" class="edit_area"><?php if (isset($card)){ echo $card->factoid; } else{ echo'Add Factoid (5)';} ?></div>
		    
		    <!-- <div id="steep"><div class="editable_select"><p>STEEP (2)</p></div></div> -->
		    <div id="question" class="editable"><?php if (isset($card)){ echo $card->question; } else{ echo'Add Question (4)';} ?></div>
		    <!-- <div id="issue" class="editable">Add Issue</div> -->
			<img class="bkg" src="assets/images/card-bkg.gif" alt="card background" />
		</div>
		<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
		<p>Maecenas faucibus mollis interdum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean lacinia bibendum nulla sed consectetur. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
	</div>
	<!-- END FORM STYLING -->
	<!-- BEGIN PLAIN TEXT EXAMPLE
	 	 The simplest one of all, just some regular ol' text in a panel. -->
	<div class="grid_1b">
		<div class="panel">
				<h4>1. The issue</h4>
                <p>an issue that is 'driving change.</p>

                <h4>2. Select category</h4>
                <p>a category in the STEEP framework (Social, Technological, Economic, Environmental or Political). </p>

                <h4>3. Question</h4>
                <p>to further articulate the issue, a thought provoking question. </p>
                <h4>4. Image</h4>
                <p>Upload an image which is a visual representation of the issue or its potential consequence. Images are limited to 1MB in file size. Images will be cropped and placed inside the grey area overlaid by text as shown.</p>

                <h4>5. Factoid</h4>
                <p>a factoid which expands on the consequences of each issue.</p>
			</div>
		</div>
	</div>
	</div>
	
</div>