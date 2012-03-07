<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
    $('a.clue').aToolTip();
});
/* ]]> */
</script>
<!-- BEGIN HOMEPAGE -->
<div class="container_4">
    <div id="page-heading" class="clearfix">
    	<div class="page-wrap">
		<?php echo nl2br($event->description) ?>
        <p>Login to <a href="create.php">create a card</a> or <a href="explore.php">explore the cards</a> other groups have created in our galleries.</p>
    </div>
</div>
</div>
<div class="container_4">
    <div class="grid-wrap">
	<div class="grid_3b">
		<div class="panel">
			<h2 class="cap-static"><?php echo $event->name ?> Cards</h2>
			<!-- Be sure you're keeping to this exact structure! -->
			<div class="content gallery">
				<div class="gallery-wrap">
					<div class="gallery-pager">
					    <?php if (isset($event_cards)&&count($event_cards)>0){?>
						<?php $last_event_cards = array_reverse($event_cards); 
						foreach (array_slice($last_event_cards, 0, 24) as $card) { 
						    if (isset($card->card_front)){
						        if ($card->owner==1){
                                    $tmp_front = substr($card->card_front, 0, -4);
                                    $tmp_thumb = $tmp_front."_t.jpg";
                                    $tmp_headers = @get_headers(UPLOADS_URL.'fronts/'.$tmp_thumb);
                                    //if no thumb on folder, create one
                                    if($tmp_headers[0] == 'HTTP/1.1 404 Not Found') {
                                        $new_thumb = CroppedThumbnail(ABSPATH.'assets/cards/'.$card->card_front,200,142);
                                        imagejpeg($new_thumb, UPLOADS_DIR.'fronts/'.$tmp_thumb);
                                    }
                                    $card_front = UPLOADS_URL.'fronts/'.$tmp_thumb;
                                } else{
                                    $card_front = UPLOADS_URL.'fronts/'.$card->card_front.'_t.jpg';
                                }
                                $card_headers = @get_headers($card_front);
                                if($card_headers[0] == 'HTTP/1.1 404 Not Found') {
                                   // $card_front="false";
                                }
                            }
						?>
						<!-- GALLERY ITEM -->
						<div class="gallery-item">
							<a class="clue" title="<?php echo $card->name; ?>" href="index.php?do=view&card_id=<?php echo $card->id ?>"><img src="<?php echo $card_front; ?>" alt="" /></a>
						</div>
						<!-- END GALLERY ITEM -->
						<?php unset($card); } ?>
						<?php } else {echo("No cards added yet."); }?>
					</div>
				</div>
				<!-- The gallery pagination/options area. -->
				<div class="pager">
					<div class="gallery-options">
						<a class="button blue small" href="index.php?do=explore">View all cards</a>
					</div>
					
					<!-- Gallery pagination -->
					<form action="">
						<a class="button small first"><img src="assets/images/table_pager_first.png" alt="First" /></a>
						<a class="button small prev"><img src="assets/images/table_pager_previous.png" alt="Previous" /></a>
						<input type="text" class="pagedisplay" disabled="disabled" />
						<a class="button small next"><img src="assets/images/table_pager_next.png" alt="Next" /></a>
						<a class="button small last"><img src="assets/images/table_pager_last.png" alt="Last" /></a>
					</form>
				</div>
			</div>
			</div>
			<!-- END CONTENT -->
			
		</div>
		<!-- END PANEL -->	
	<!-- END GRID_3/GALLERY -->
	<div class="grid_1b">
		<div class="panel">
			<h2 class="cap-static">Arup Drivers of Change Cards</h2>
			<div class="content demo-card" >
			    <img class="arup-card" src="assets/images/demo-card.jpg" /><br />
			    <h3>Card title</h3>
			    <p>An Arup card or several here, perhaps have a rotating selected one and links to more to make it different? or one here and losts below.</p>
			    <a title='ftygigbyu'>aaaa</a>
			    <p>With links to other things</p>
			</div>
		</div>
	</div>
	</div>
</div>
<!-- END CONTAINER -->