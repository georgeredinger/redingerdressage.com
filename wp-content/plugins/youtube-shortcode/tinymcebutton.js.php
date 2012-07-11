<?php
	if (!headers_sent()) {
	    header("Content-Type: application/x-javascript; charset=UTF-8");
	}
?>
(function() {
    tinymce.create('tinymce.plugins.YoutubeShortcodeMargenn', {
        
        init : function(ed, url) {
        
        	var popUpURL = url + '/youtube-shortcode-tinymce.php?' + '<?php echo base64_decode(urldecode($_GET['params'])); ?>';
        
			ed.addCommand('YoutubeShortcodePopupMargenn', function() {
				ed.windowManager.open({
					url : popUpURL,
					width : 600,
					height : 500, 
					inline : 1
				});
			});

			ed.addButton('YoutubeShortcodeButtonMargenn', {
				title : 'YouTube Shortcode',
				image : url + '/youtube-shortcode-button.png',
				cmd : 'YoutubeShortcodePopupMargenn'
			});
		},
		
		createControl : function() {
            return null;
        },

		getInfo : function() {
            return {};
        }
    });
    tinymce.PluginManager.add('YoutubeShortcodeMargenn', tinymce.plugins.YoutubeShortcodeMargenn);
}());