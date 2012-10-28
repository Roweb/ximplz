<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ximplz - Dropbox music player</title>		
		<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,400italic' rel='stylesheet' type='text/css'>
		
		<?php echo css('jplayer.blue.monday.css') ?>
		<?php echo css('style.css') ?>
		
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
		
		<?php echo js('jquery.jplayer.js'); ?>
		<?php echo js('jplayer.playlist.js'); ?>
		<?php echo js('player.js'); ?>		
    </head>
    <body>
		<div id="header">
			<img src="/assets/img/logo.png" alt="Logo" id="logo"/>
			<div id="player-wrapper">
				<?php $this->template->load_view('player_js'); ?>
			</div>
			
			<ol id="recycle" class="droptrue "></ol>
			
			<ul id="menu">
				<li><a href="https://www.dropbox.com/home/Public/ximplz" target="_blank"><img src="/assets/img/dropbx.png"/> &nbsp; Manage music</a></li>
				<li><a href="/login/logout"><img src="/assets/img/logout.png" /> &nbsp; Logout</a></li>
			</ul>
			
			<div class="clear"></div>
		</div>
		<div id="content">
			<?php echo $template['body']; ?>
		</div>
    </body>
</html>