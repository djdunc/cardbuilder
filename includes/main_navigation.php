<!-- MAIN NAVIGATION WITH ICON CLASSES -->
<div id="main-navigation">
	<div class="nav-wrap"> 
			<!-- The class "hide-on-mobile" will hide this navigation on a small mobile device. -->
			<ul class="hide-on-mobile">
				<li><a href="index.php"<?php if($page == 'home'){ echo(" class=\"active\"");} ?>>Home</a></li>
				<li><a href="index.php?do=create"<?php if($page == 'create'){ echo(" class=\"active\"");} ?>>Create</a></li>
				<li><a href="index.php?do=explore"<?php if($page == 'view'||$page == 'explore'){ echo(" class=\"active\"");} ?>>Explore</a></li>
				<li><a href="index.php?do=admin" class="settings">My events</a></li>
				<li><a href="">All events</a>
			</ul>
			<!-- The class "show-on-mobile" will show only this navigation on a small mobile device. It's a dropdown select box that loads the page upon select. Dependant on JS within "custom.js" -->
			<div class="show-on-mobile">
					<select name="navigation" class="mobile-navigation">
						<option value="index.php">Home</option>
						<option value="grid.php">Create</option>
						<option value="page.php">Explore</option>
						<option value="stats.php">Admin</option>
					</select>
			</div>
	</div>
		<!-- END NAV WRAP -->
	</div>
	<!-- END MAIN NAVIGATION -->