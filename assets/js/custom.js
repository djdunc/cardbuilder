// Wait for the page to load...

$(document).ready(function() {
	/* OPEN & CLOSE PANELS													*/
	/* -------------------------------------------------------------------- */
	
	var screenSize = $('body').width();
	
	$('.panel .cap').click(function(){
		var content = $(this).parent().find('.content');
		var tabs = $(this).parent().find('.tabs');
		var accordionWrapper = $(this).parent().find('.accordion-wrapper');
		var isOpen = content.is(":visible");
		if (!isOpen) { var isOpen = tabs.is(":visible"); }
		if (!isOpen) { var isOpen = accordionWrapper.is(":visible"); }
		if (isOpen){
			if (screenSize <= 1024){
				content.hide();
				tabs.hide();
				accordionWrapper.hide();
			} else {
				content.slideUp();
				tabs.slideUp();
				accordionWrapper.slideUp();
			}
		} else {
			if (screenSize <= 1024){
				content.show();
				tabs.show();
				accordionWrapper.show();
			} else {
				content.slideDown();
				tabs.slideDown();
				accordionWrapper.slideDown();
			}
			
			// If this is the gallery tab, resize the galleries for mobile browsers.
			if (content.hasClass('gallery')) { resizeGalleries();}
		}
	});
	
	$('.alert-text .close').click(function(){
		$(this).parents('.alert-wrapper').slideUp('normal');
		return false;
	});
	
	/* /// END - OPEN & CLOSE PANELS /// */
	
	
	
	/* SORTABLE TABLES														*/
	/* -------------------------------------------------------------------- */
	
	try {
		$('.tablesorter')
		.tablesorter({headers: {0:{sorter: false},3:{sorter: false}}, widthFixed: true, widgets: ['zebra']}) 
	    .tablesorterPager({container: $("#table-pager-1")});
		$('.tablesorter2')
		.tablesorter({headers: {0:{sorter: false},3:{sorter: false}}, widthFixed: true, widgets: ['zebra']}) 
	    .tablesorterPager({container: $("#table-pager-2")});
	    
	    //$(".pagesize").chosen();
	     $(".pagesize").chosen({ disable_search_threshold : 10 });
	    
	    $('.checkall').click(function(){
	    	$(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
	    });
	}
	catch(err){
		// Error stuff here
	}
    
    /* /// END - SORTABLE TABLES /// */
    


	/* TABS																	*/
	/* -------------------------------------------------------------------- */
	
    //When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li a").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).parent().addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
		
	});
	
	/* /// END - TABS /// */
	
	
	
	/* ACCORDIONS															*/
	/* -------------------------------------------------------------------- */
	
	// On load, show the first panel in each accordion.
	$('.accordion').each(function(){
		$(this).find('.accordion-block').eq(0).addClass('open');
		$(this).find('.accordion-block').eq(0).find('.accordion-content').show();
	});
	
	$('.accordion-block h3').click(function(){
		if($(this).parent().hasClass('open')){
			$('.accordion-block .accordion-content').slideUp('fast',function(){
				$('.accordion-block').removeClass('open');
			});
		} else {
			$('.accordion-block .accordion-content').slideUp('fast');
			$(this).parent().find('.accordion-content').slideDown('fast');
			$('.accordion-block').removeClass('open');
			$(this).parent().addClass('open');
		}
	});
	
	/* /// END - ACCORDIONS /// */
	
	
	
	/* GALLERIES															*/
	/* -------------------------------------------------------------------- */
	
	// Add Fancybox lightboxing to each of the images.
	//try { $('.fancybox').fancybox(); } catch(err) { /* Error Stuff */ }

	// On window resize, adjust the sizing.
	$(window).resize(function(){
  		setTimeout("resizeGalleries()",50);
		setTimeout("resizeChosenWidths()",50);
		setTimeout("generateGraphs()", 50);
  	});
  	
  	// When you check a checkbox, add some styling to the image block
  	$('.gallery-item .checkbox-block input').click(function(){
  	
  		var checkedLayer = $(this).parent().parent().find('.checked-layer');
  	
  		if ($(this).attr('checked')){
  			checkedLayer.show();
  		} else {
  			checkedLayer.hide();
  		}
  		
   	});
   	
   	$('.gallery').find('.next').click(function(){
   		var thisGallery = $(this).parent().parent().parent().find('.gallery-wrap');
   		
   		// Get page information
   		var pageDisplayContent = $(this).parent().find('.pagedisplay').val();
   		pageDisplayContent = pageDisplayContent.split('/');
   		currentPage = pageDisplayContent[0];
   		totalPages = pageDisplayContent[1];
   		var nextPage = parseInt(currentPage) + 1;
   		
   		// Get this galleries height
   		var galleryHeight = $(this).parent().parent().parent().find('.gallery-wrap').height();
   		var galleryHeight = galleryHeight + 10;
   		
   		// Slide the gallery to the next page
   		if (nextPage <= totalPages){
   			galleryPaginate(thisGallery,nextPage,galleryHeight,totalPages);
   		}
   	});
   	
   	$('.gallery').find('.prev').click(function(){
   		var thisGallery = $(this).parent().parent().parent().find('.gallery-wrap');
   		
   		// Get page information
   		var pageDisplayContent = $(this).parent().find('.pagedisplay').val();
   		pageDisplayContent = pageDisplayContent.split('/');
   		currentPage = pageDisplayContent[0];
   		totalPages = pageDisplayContent[1];
   		var prevPage = parseInt(currentPage) - 1;
   		
   		// Get this galleries height
   		var galleryHeight = $(this).parent().parent().parent().find('.gallery-wrap').height();
   		var galleryHeight = galleryHeight + 10;
   		
   		// Slide the gallery to the previous page
   		if (prevPage > 0){
   			galleryPaginate(thisGallery,prevPage,galleryHeight,totalPages);
   		}
   	});
   	
   	$('.gallery').find('.last').click(function(){
   		var thisGallery = $(this).parent().parent().parent().find('.gallery-wrap');
   		
   		// Get page information
   		var pageDisplayContent = $(this).parent().find('.pagedisplay').val();
   		pageDisplayContent = pageDisplayContent.split('/');
   		currentPage = pageDisplayContent[0];
   		totalPages = pageDisplayContent[1];
   		
   		// Get this galleries height
   		var galleryHeight = $(this).parent().parent().parent().find('.gallery-wrap').height();
   		var galleryHeight = galleryHeight + 10;
   		
   		// Slide the gallery to the last page
   		galleryPaginate(thisGallery,totalPages,galleryHeight,totalPages);
   	});
   	
   	$('.gallery').find('.first').click(function(){
   		var thisGallery = $(this).parent().parent().parent().find('.gallery-wrap');
   		
   		// Get page information
   		var pageDisplayContent = $(this).parent().find('.pagedisplay').val();
   		pageDisplayContent = pageDisplayContent.split('/');
   		currentPage = pageDisplayContent[0];
   		totalPages = pageDisplayContent[1];
   		
   		// Get this galleries height
   		var galleryHeight = $(this).parent().parent().parent().find('.gallery-wrap').height();
   		var galleryHeight = galleryHeight + 10;
   		
   		// Slide the gallery to the first page
   		galleryPaginate(thisGallery,1,galleryHeight,totalPages);
   	});
   	
   	/* /// END - GALLERIES /// */
   	
   	
   	
   	/* FORMS																*/
	/* -------------------------------------------------------------------- */
   	
   	
   	// Custom file field
   	$('form.styled input[type=file]').each(function(){
   		$(this).before('<input class="textbox file-field" name="uploadField" type="text" value="" /><span class="browse button medium grey">Browse...</span>');
   	});
   	
   	$('form.styled input[type=file]').animate({'opacity':0},0);
   	
   	$('form.styled input[type=file]').hover(function(){
   		$(this).parent().find('.browse').addClass('hover');
   	},function(){
   		$(this).parent().find('.browse').removeClass('hover');
   	});
   	
   	$('form.styled input[type=file]').change(function(){
   		$(this).parent().find('.file-field').val($(this).val().replace('C:\\fakepath\\',''));
   	});
   	
   	// "Chosen" field
   	// http://harvesthq.github.com/chosen/

	try {
   		$('form.styled').find('.chosen').chosen();
   	}
   	catch(err){
   		// Error stuff here
   	}
   	   	
   	/* /// END - FORMS /// */
   	
   	
   	
   	/* MOBILE DROPDOWN NAVIGATION											*/
	/* -------------------------------------------------------------------- */
   	
   	$('.mobile-navigation').change(function(){
   		var url = $(this).val();
   		location.href = url;
	  	return false;
   	});
   	
   	/* /// END - MOBILE DROPDOWN NAVIGATION /// */
   	
   	
   	
   	/* 	FOR MOBILE WEB APPS
   		This allows the app to stay contained instead of launching Safari
   		when clicking on links.												*/
	/* -------------------------------------------------------------------- */
	
	$('a').click(function(){
		if ( !$(this).hasClass('fancybox') ){
	  		var href = $(this).attr('href');
	  		if (href) { var firstChar = href.substring(0,1); }
	  		if (href && href != '#' && firstChar != '#') {
	  			location.href = href;
	  			return false;
	  		} else {
	  			return false;
	  		}
	  	}
  	});
  	
  	/* /// END - FOR MOBILE WEB APPS /// */

	setTimeout("resizeGalleries()",50);
	setTimeout("resizeChosenWidths()",50);
	setTimeout("generateGraphs()", 50);

});



/* CHARTS																*/
/* -------------------------------------------------------------------- */
     
function generateGraphs(){
    $("table.statics").each(function() {
    	var widthOfParent = $(this).parent().width();
    	$(this).hide();
    	$(this).parent().find('.flot-graph').remove();
        var colors = [];
        $(this).find("thead th:not(:first)").each(function() {
            colors.push($(this).css("color"));
        });
        $(this).graphTable({
            series: 'columns',
            position: 'before',
            width: widthOfParent,
            height: '200px',
            colors: colors
        }, {
            xaxis: {
                tickSize: 1
            }
        });
    });

    $('.flot-graph').before('<div class="space"></div>');

    function showTooltip(x, y, contents) {
        $('<div id="tooltip" style="color:#fff; padding:4px 8px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; line-height:11px; font-size:11px; background:#333;">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn("fast");
    }

    var previousPoint = null;
    $(".flot-graph").bind("plothover", function(event, pos, item) {
        $("#x").text(pos.x);
        $("#y").text(pos.y);

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];

                showTooltip(item.pageX, item.pageY, "<b>" + item.series.label + "</b>: " + y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
}

/* /// END - CHARTS /// */



/* FUNCTIONS - GALLERY													*/
/* -------------------------------------------------------------------- */

function resetGalleryPager(thisGallery,thumbsToFit,newWrapHeight){
	var totalThumbs = thisGallery.find('.gallery-item').size();
	var totalVisibleThumbs = thumbsToFit * 3;
	var totalPages = Math.ceil(totalThumbs / totalVisibleThumbs);
	window.adminpro_totalPages = totalPages;
	thisGallery.parent().find('.pagedisplay').val('1/'+totalPages);
	galleryPaginate(thisGallery,1,newWrapHeight,totalPages);
	window.adminpro_newWrapHeight = newWrapHeight;
	//
	//instrWrap = $('.demo-card');
	//instrWrap.height(newWrapHeight+68);
}

function galleryPaginate(thisGallery,currentPage,galleryHeight,totalPages){
	var pageLocation = -1 * ((currentPage * galleryHeight) - galleryHeight);
	thisGallery.find('.gallery-pager').css('top',pageLocation);
	thisGallery.parent().find('.pagedisplay').val(currentPage+'/'+totalPages);
	window.currentPage = currentPage;
}

function resizeGalleries(i,width){
	galleryWrap = $('.gallery-wrap');
	galleryWrap.each(function(){
		var thisGallery = $(this);
		var thumbSize = 150;
		var galleryWrapWidth = thisGallery.width();
		var thumbsToFit = Math.floor(galleryWrapWidth / thumbSize);
		var totalThumbWidth = thumbsToFit * thumbSize;
		var leftOverWidth = galleryWrapWidth - totalThumbWidth;
		var addToThumbWidth = Math.floor(leftOverWidth / thumbsToFit);
		var totalThumbSize = addToThumbWidth + thumbSize;
		var newWrapHeight = (((totalThumbSize*0.7)) * 3) + 20;
		thisGallery.find('.gallery-item').width((totalThumbSize)-15).height((totalThumbSize*0.7));
		thisGallery.height(newWrapHeight);
		resetGalleryPager(thisGallery,thumbsToFit,newWrapHeight);
	
	});	
}
/* /// END - FUNCTIONS - GALLERY /// */



/* FUNCTIONS - RESIZE THE "CHOSEN" STYLE DROPDOWNS						*/
/* -------------------------------------------------------------------- */
function resizeChosenWidths(){
	$('form.styled').each(function(){
		$(this).find('.chzn-container').width('100%');
		var containerWidth = $(this).find('.chzn-container').width();
		$(this).find('.chzn-drop').width(containerWidth - 2);
		var searchWidth = $(this).find('.chzn-search').width();
		$(this).find('.chzn-search input').width(searchWidth - 26);
		
	});
}

/* /// END - FUNCTIONS - RESIZE THE "CHOSEN" STYLE DROPDOWNS /// */



//open and close messages
    function displayAlertMessage(message) {
    var timeOut = 5
    jQuery('.message').text(message).fadeIn();
    jQuery('.message').css("display", "block");
    setTimeout(function() {
        jQuery('.message').fadeOut('slow')
    }, timeOut * 1000);
    }