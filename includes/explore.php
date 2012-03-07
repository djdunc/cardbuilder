<!-- BEGIN SUBNAVIGATION -->
<!-- <div class="container_4 no-space">
    <div id="subpages" class="clearfix">
        <div class="grid_4">
            <div class="subpage-wrap">
                <ul>
                    <li><a href="#">Subpage One</a></li>
                    <li><a href="#">Subpage Two</a></li>
                    <li><a href="#">Subpage Three</a></li>
                    <li><a href="#">Subpage Four</a></li>
                </ul>
            </div>
        </div>
    </div>
</div> -->
<!-- END SUBNAVIGATION -->

<!-- BEGIN PAGE BREADCRUMBS/TITLE -->
<!-- <div class="container_4">
    <div id="page-heading" class="clearfix">
        <div class="title-crumbs">
            <div class="page-wrap title-event">
                <h1><?php echo $_SESSION['event_name']; ?> cards</h1>
            </div>
        </div>
    </div>
</div> -->
<!-- END PAGE BREADCRUMBS/TITLE -->
<br />

<div class="container_4">
    <script type="text/javascript">
    /* <![CDATA[ */
    $(document).ready(function() {
        $('a.clue').aToolTip();
    });
    /* ]]> */
    </script>
	<!-- BEGIN GALLERY -->
		<div class="grid-wrap">
	<div class="grid_4">
		<div class="panel">	
			<!-- gallery -->
			<div class="content no-cap gallery">
				<div class="gallery-wrap">
					<div class="gallery-pager">
						<?php foreach (array_reverse($event_cards) as $card) { 
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
							<a class="clue" title="<?php echo $card->name; ?>" href="index.php?do=view&card_id=<?php echo $card->id ?>"><img src="<?php if (isset($card_front)){ echo ($card_front);}?>" alt="" /></a>
						</div>
						<!-- END GALLERY ITEM -->
						<?php unset($card); } ?>
					</div>
				</div>
			
				<!-- The gallery pagination/options area. -->
				<div class="pager">
				
					<!-- Gallery options - these should probably become active once you've checked an image or more. -->
					<!-- <div class="gallery-options">
					                       <a class="button red small" href="#">Delete</a>
					                       <a class="button blue small" href="#">Edit</a>
					                   </div> -->
					
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
			<!-- END CONTENT -->
			
		</div>
		<!-- END PANEL -->
		
	</div>
	<!-- END GRID_4/GALLERY -->
	</div>
</div>