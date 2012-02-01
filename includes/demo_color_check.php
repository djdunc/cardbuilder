<?php

// Color set in the URL?
if (isset($_GET['color'])){
	$color = $_GET['color'];
	$_SESSION['custom_color'] = $color;
} else if (isset($_SESSION['custom_color'])){
	$color = $_SESSION['custom_color'];
}

?>