<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ximplz - Login to the music player</title>
       	<?php echo css('login.css') ?>
    </head>
    <body>
    <div id="page">	
        <div id="header">
        	<?php $this->load->view('partials/header'); ?>
        </div>
		<div id="content">
			<?php echo $template['body']; ?>
		</div>
        <div id="footer">
        	<?php $this->load->view('partials/footer'); ?>
        </div>
    </div>         
        
        <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    </body>
</html>