<!-- MAIN NAVIGATION -->
			<!-- The class "hide-on-mobile" will hide this navigation on a small mobile device. -->
			<ul id="main-navigation">
				<li><a href="index.php"<?php if($page == 'home'){ echo(" class=\"active\"");} ?>>about</a></li><li><a href="index.php?do=create"<?php if($page == 'create'){ echo(" class=\"active\"");} ?>>create</a></li><li class="last"><a href="index.php?do=explore"<?php if($page == 'view'||$page == 'explore'){ echo(" class=\"active\"");} ?>>explore</a></li></ul>
			<!-- The class "show-on-mobile" will show only this navigation on a small mobile device. It's a dropdown select box that loads the page upon select. Dependant on JS within "custom.js" -->
			<!-- <div class="show-on-mobile">
			                 <select name="navigation" class="mobile-navigation">
			                     <option value="index.php">about</option>
			                     <option value="index.php?do=create">create</option>
			                     <option value="index.php?do=explore">explore</option>
			                 </select>
			         </div> -->
	<!-- END MAIN NAVIGATION -->