$(function() {			
	$("#sortable1").sortable({
		connectWith: ".connectedSortable",
		forcePlaceholderSize: true,
		remove: function(event, ui) {
			$(ui.item).clone().appendTo(event.target);
		}
	}).disableSelection();
				
	$("#sortable2").sortable({
		forcePlaceholderSize: true,
		connectWith: "#recycle",
		
		receive: function() {

			myPlaylist.remove();
			
			var playlist = [];
			$('#sortable2 li').each(function(index, value){
				song = {title: $(this).text(), mp3: $(this).children('a').attr('href')};
				playlist[ parseInt(index + 1) ] = song;
			});

			myPlaylist.setPlaylist(playlist);
         },

		update: function() {
				myPlaylist.remove();
				
				var playlist = [];
				$('#sortable2 li').each(function(index, value){
					song = {title: $(this).text(), mp3: $(this).children('a').attr('href')};
					playlist[ index ] = song;
				});
				myPlaylist.setPlaylist(playlist);
	         }
	}).disableSelection();
	
	$("#recycle").sortable({
        update: function(event, ui)
        {
        	$(ui.item).css('display', 'none');
    	}
    }).disableSelection();
	
	// new
	$(document).delegate('.save', 'click', function(e) {
		var songs = [];
		$("#sortable2 li").each(function(){
	        var current = {path: $(this).children('a').attr('href'), name: $(this).children('a').html()};
	        songs.push(current);
	    });

		if(songs)
		{
			$.post('/player/ajax_save', {data: songs}, function(data) {
				alert('Successfully saved!');
			});
		}
	    
		return false;
	});
	
	$(document).delegate(".folderslist a","click",function(e) {
		$('#sortable1').children().remove();
		$('#loader').show();
		
		$.post('/player/ajax_get', {path: $(this).attr('href').substr(1)}, function(data) {
			obj = JSON.parse(data);
			
			$('#sortable1').children().remove();
			if(obj.mp3s.length > 0)
			{
				$.each(obj.mp3s, function(key, val) {
					$('#sortable1').append('<li><a href="' + val.path + '">' + val.name + '<span>4:03</span></a></li>');
				});
			}

			$('.folderslist').children().remove();
			$('#loader').hide();
			//loader
			if(obj.parent)
			{
				$('.folderslist').append('<li><a href="#' + obj.parent.path + '">' + obj.parent.name + '</a></li>');
			}
			if(obj.dirs.length > 0)
			{
				$.each(obj.dirs, function(key, val) {
					$('.folderslist').append('<li><a href="#' + val.path + '">' + val.name + '</a></li>');
				});
			}
		});

		return false;
	});

	$('#sortable2 a').live({
		click: function() {
			var id = ($('#sortable2 li').index($(this).parent()));
			myPlaylist.select(id);
			myPlaylist.play();
			$('#sortable2 li').removeClass('jp-playlist-current');

			$(this).parent().addClass('jp-playlist-current');
			
			return false;
		  }
	});	
}); 