			<div class="folders">
				<ul class="switch">
					<li class="active dropbox"><a href="#">Dropbox</a></li>
					<li class="skydrive"><a href="#">SkyDrive</a></li>
				</ul>
				<ul class="folderslist">
					<?php if($parent): ?>
					<li><a href="<?php echo $parent['path']; ?>"><?php echo $parent['name']; ?></a></li>	
					<?php endif; ?>
				<?php if(!empty($dirs)): ?>
					<?php foreach($dirs as $key => $value): ?>
					<li><a href="#<?php echo $value['path']; ?>"><?php echo $value['name']; ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
				<div class="clear"></div>
			</div>
			<div class="files">
				<div id="loader" class="hidden"><img src="/assets/img/ajax-loader.gif" /></div>
				<ol id="sortable1" class="songs">
				<?php if(isset($mp3s)): ?>
				<?php foreach($mp3s as $key => $value): ?>
				<li><a href="<?php echo $value['path']; ?>"><?php echo $value['name']; ?><span>4:03</span></a></li>
				<?php endforeach; ?>
				<?php endif; ?>
				</ol>
			</div>
			<div class="playlists">
				<ul class="switch">
					<li class="active"><a href="#">Playlist</a></li>
					<li><a href="#">Playlist 2</a></li>
				</ul>
				<div class="clear"></div>
							
				<ol id="sortable2" class="songs connectedSortable">
					<?php foreach($play as $song): ?>
					<li><a href="<?php echo $song['path'] ?>"><?php echo $song['name'] ?><span>4:03</span></a></li>
					<?php endforeach; ?>
				</ol>
				
				<div class="clear"></div>
				<div class="buttons">
					<a href="#save" class="save">Save</a>
				</div>
			</div>
