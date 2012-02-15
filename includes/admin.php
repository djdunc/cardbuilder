 <!--	Load the Tablesorter script. -->
 <script src="assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>
 <script src="assets/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
 <!--	Load the "Chosen" stylesheet. -->
 <link rel="stylesheet" href="assets/js/chosen/chosen.css" type="text/css" media="screen" />
 <!--	Load the Chosen script.-->
 <script src="assets/js/chosen/chosen.jquery.js" type="text/javascript"></script>
 <!-- BEGIN PAGE BREADCRUMBS/TITLE -->
 <div class="container_4">
 	<div id="page-heading" class="clearfix">
 	    <div class="grid-wrap">
     		<h2 class="grid_2">
     		       My events
     		</h2>
     		<div class="grid_2 align_right">
     				<a href="index.php?do=form" class="button medium">Add new event</a>
     		</div>
 	    </div>
     </div>
 </div>
 <!-- END PAGE BREADCRUMBS/TITLE -->
<div class="container_4">
    <div class="grid-wrap">	
    <!-- BEGIN TABLESORTER
		 Tablesorter documentation can be found at their website:
	 
	 		http://tablesorter.com/docs/ -->
	<div class="grid_4">
		<div class="panel">
			<h2 class="cap">Events</h2>
			<div class="content">			
				<table id="tablesorter-sample" class="tablesorter styled"> 
					<thead> 
						<tr> 
							<th class="checkbox-row"><input type="checkbox" class="checkall" /></th> 
							<th>Event Name</th> 
							<th>Cards</th>
							<th>Start date</th>
							<th>End date</th> 
							<th class="options-row">Options</th> 
						</tr> 
					</thead> 
					<tbody> 
						<?php foreach ($events as $event){?>
						<?php $event_cards_json = callAPI("eventcards?event_id=".$event->id); 
						if (isset($event_cards_json)) {$event_cards = json_decode($event_cards_json);}?>
						<tr> 
							<td><input type="checkbox" name="checkbox" /></td> 
							<td><a href="index.php?do=form&edit_event=<?php echo $event->id ?>"><?php echo $event->name ?></a></td> 
							<td class="center"><?php if(isset($event_cards)){echo(count($event_cards));}?></td> 
							<td class="center"><?php echo(date( "d/m/y", $event->start)); ?></td>
							<td class="center"><?php if($event->end!=0){echo(date( "d/m/y", $event->end));}else{echo("–");} ?></td> 
							<td class="center options-row"><a class="icon-button edit" title="edit event" href="index.php?do=form&edit_event=<?php echo $event->id ?>">Edit</a><a class="icon-button send" title="send details" href="mailto:?subject=<?php echo $event->name; ?> Drivers of Change&amp;body=Link: <?php echo urlencode(BASE_URL.'index.php?event='.$event->id); ?> <?php if($event->password!=''){ echo("Secret Code:".$event->password);} ?>">Send details</a><a class="icon-button link" title="view event" href="<?php echo(BASE_URL.'index.php?event='.$event->id);?>">View event</a></td> 
						</tr>
						<?php unset($event); } ?>
					</tbody> 
					
				</table>
				
				<div id="table-pager-1" class="pager">
					<div class="table-options">
						<a class="button red small" href="#">Delete</a>
					</div>
					<form action="">
						<select class="pagesize">
							<option selected="selected" value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
						</select>
						<a class="button small first"><img src="assets/images/table_pager_first.png" alt="First" /></a>
						<a class="button small prev"><img src="assets/images/table_pager_previous.png" alt="Previous" /></a>
						<input type="text" class="pagedisplay" disabled="disabled" />
						<a class="button small next"><img src="assets/images/table_pager_next.png" alt="Next" /></a>
						<a class="button small last"><img src="assets/images/table_pager_last.png" alt="Last" /></a>
					</form>
				</div>
				
			</div>
		</div>
	</div>
	
	<!-- END TABLESORTER -->
    
	</div>
</div>