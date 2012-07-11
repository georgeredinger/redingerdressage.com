<?php

$plugin_version = $_GET['plugin_version'];
$includes_url = $_GET['includes_url'];
$plugins_url = $_GET['plugins_url'];
$charset = $_GET['charset'];

if (!headers_sent()) {
    header('Content-Type: text/html; charset='.$charset);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
	<title>Youtube player configuration</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $plugins_url.'/youtube-shortcode/bootstrap-1.4.0.min.css?v='.$plugin_version; ?>"/>
	<script type="text/javascript" src="<?php echo $includes_url.'js/tinymce/tiny_mce_popup.js?v='.$plugin_version; ?>"></script>
	<style type="text/css">
		html, body{background-color:#fff !important;font-size:13px !important;}
		.form-stacked{padding-left:0;}
		.form-stacked fieldset {border: none;font-size: 13px;}
		.help-inline, .help-block {font-size: 10px;}
    	.form-stacked fieldset legend{color:#2B6FB6;font-size:13px;font-weight:bold;}
    	.form-stacked span[class*="span"] {display:inline-block}
    	.form-stacked .actions {margin-left:0}
    	.form-stacked div.label{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-weight: normal;line-height: 20px;text-align: left;width: auto;color: #404040;font-size: 13px;white-space: normal;}
    	.form-stacked .inputs-list:first-child {padding-top:0;}
	</style>
</head>
<body>

	<form class="form-stacked" action="#">

		<fieldset>
			<legend>Videos</legend>
			<div class="clearfix">
				<ul class="inputs-list">
					<li>
						<label>
							<span>Video <span style="color:red">*</span></span>
							<input type="text" name="url" class="span5"/>
							<span class="help-inline">Video URL or ID</span>
						</label>
					</li>
					<li>
						<label>
							<span>Playlist</span>
							<input type="text" name="playlist" class="span5"/>
							<span class="help-inline">A comma-separated list of video IDs to play.</span>
						</label>
					</li>
				</ul>
			</div>
		</fieldset>
        <fieldset>
			<legend>SEO & Accessibility</legend>
			<div class="clearfix">
				<ul class="inputs-list">
					<li>
                        <label>
							<span><em>title</em> attribute</span>
							<input type="text" name="title" class="span7" placeholder="YouTube video player"/>
							<span class="help-block" style="margin-left:80px">Description of the video's content (HTML attribute for iframe & object elements)</span>
						</label>
					</li>
                </ul>
             </div>
        </fieldset>
		<fieldset>
			<legend>Player appearance</legend>
			<div class="clearfix">
				<ul class="inputs-list">
					<li>
						<div class="label" style="margin-left:20px">
							<span>Width</span>
							<input type="text" name="width" class="span1" placeholder="560"/>
							<span>Height</span>
							<input type="text" name="height" class="span1" placeholder="340"/>
							<span class="help-block">Omit both if you want the video to be fluid (recommended for fluid, elastic and mobile friendly designs). If width is set and height is omitted, height is calculated automatically (static or fixed-width designs).</span>
						</div>
					</li>
                    <li>
						<label>
							<input type="checkbox" name="ratio" value="4:3" />
							<span>4:3 aspect ratio</span>
							<span class="help-inline">Default is 16:9 (also known as widescreen)</span>
						</label>
					</li>
                    <li>
						<label>
							<span>CSS class(es)</span>
							<input type="text" name="class" class="span4" placeholder="CSS class name(s)"/>
                            <span class="help-inline">Useful for positioning.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="border" value="1" />
							<span>Border</span>
							<span class="help-inline">Enables a border around the entire video player.</span>
						</label>
					</li>
					<li>
						<label>
							<span>Border primary color #</span>
							<input type="text" name="color1" class="span2" placeholder="b1b1b1"/>
						</label>
					</li>
					<li>
						<label>
							<span>Border secondary color / Video control bar background color #</span>
							<input type="text" name="color2" class="span2" placeholder="cfcfcf"/>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="color" value="white" />
							<span>White progress bar</span>
							<span class="help-inline">Color that will be used in the player's video progress bar.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="theme" value="light" />
							<span>Light theme</span>
							<span class="help-inline">Display player controls within a dark (default) or light control bar.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="modestbranding" value="1" />
							<span>Prevent the YouTube logo from displaying in the control bar.</span>
						</label>
					</li>

            	</ul>
			</div>
		</fieldset>
		<fieldset>
			<legend>Player behavior</legend>
			<div class="clearfix" style="margin-bottom:0;">
				<ul class="inputs-list">
					<li>
						<label>
							<input type="radio" name="autohide" value="0" />
							<span>Progress bar and player controls are visible throughout the video.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="autohide" value="1" />
							<span>Progress bar and player controls slide out of view just after the video starts playing. They will only reappear if the user moves her mouse over the video player or presses a key on her keyboard.</span>
						</label>
					</li>
					<li>
						<span class="help-block" style="margin-left:20px">The default behavior is for the video progress bar to fade out while the player controls remain visible.</span>
					</li>	
				</ul>
			</div>

			<div class="clearfix">
				<ul class="inputs-list">
					<li>
						<label>
							<input type="checkbox" name="controls" value="0" />
							<span>Hide player controls</span>
							<span class="help-inline">Hides the video player controls (chromeless player).</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="autoplay" value="1" />
							<span>Autoplay video</span>
							<span class="help-inline">Sets whether or not the initial video will autoplay when the player loads.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="fs" value="1" />
							<span>Fullscreen button</span>
							<span class="help-inline">Enables the fullscreen button in the embedded player.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="hd" value="1" />
							<span>HD playback</span>
							<span class="help-inline">Enables HD playback by default.</span>
						</label>
					</li>
                    <li>
						<label>
							<input type="checkbox" name="embedcode" value="old" />
							<span>Use old embed code (object)</span>
						</label>
					</li>
                    <li>
						<label>
							<input type="checkbox" name="version" value="2" />
							<span>Use AS2 player (deprecated)</span>
							<span class="help-inline">Enable it if you need to force HD playback.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="loop" value="1" />
							<span>Loop playback</span>
							<span class="help-inline">In the case of a single video player, the player will play the initial video again and again. In the case of a playlist player (or custom player), the player will play the entire playlist and then start again at the first video.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="egm" value="1" />
							<span>Enhanced Genie Menu</span>
							<span class="help-inline">Causes the genie menu (if present) to appear when the user's mouse enters the video display area, as opposed to only appearing when the menu button is pressed.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="cc_load_policy" value="1" />
							<span>Show closed captions by default</span>
							<span class="help-inline">Show closed captions by default, even if the user has turned captions off.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="iv_load_policy" value="3" />
							<span>Hide video annotations by default</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="disablekb" value="1" />
							<span>Disable Keyboard</span>
							<span class="help-inline">Enabling this setting will disable the player keyboard controls.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="rel" value="0" />
							<span>Disable the load of related videos</span>
							<span class="help-inline">The player search functionality will be disabled too.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="showinfo" value="0" />
							<span>Disable the display of the video title & uploader before the video starts playing.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="showsearch" value="0" />
							<span>Disable the search box from displaying when the video is minimized.</span>
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="nocookie" value="1" />
							<span>Privacy mode</span>
							<span class="help-inline">Does not store a cookie on user's browser.</span>
						</label>
					</li>
					<li>
						<label>
							<span>Video playback start</span>
							<input type="text" name="start" class="span2" />
							<span class="help-inline">A positive integer (seconds from the start of the video).</span>
						</label>
					</li>

            	</ul>
			</div>
		</fieldset>

		<fieldset>
			<legend>Javascript API</legend>
			<div class="clearfix">
				<ul class="inputs-list">
					<li>
						<label>
							<input type="checkbox" name="enablejsapi" value="1" />
							<span>Enable Javascript API</span>
						</label>
					</li>
					<li>
						<label>
							<span class="span2">Player API Id</span>
							<input type="text" name="playerapiid" />
							<span class="help-inline">Any alphanumeric string.</span>
						</label>
					</li>
					<li>
						<label>
							<span class="span2">Origin</span>
							<input type="text" name="origin" />
							<span class="help-inline">Specify your domain.</span>
						</label>
					</li>
            	</ul>
			</div>
		</fieldset>
		<div class="actions">
            <input type="submit" value="Insert shortcode" class="btn primary"/>
            or
            <input class="btn" type="reset" value="Reset settings"/>
        </div>
	</form>


	<script type="text/javascript">
		tinyMCEPopup.onInit.add(function(ed) {

			var form = document.forms[0],

			isEmpty = function(value) {
				return (/^\s*$/.test(value));
			},

			encodeStr = function(value) {
				return value.replace(/\s/g, "%20")
                            .replace(/"/g, "%22")
                            .replace(/'/g, "%27")
                            .replace(/=/g, "%3D")
                            .replace(/\[/g, "%5B")
                            .replace(/\]/g, "%5D")
                            .replace(/\//g, "%2F");
			},

			insertShortcode = function(e){
				var sc = "[youtube_sc ",
					inputs = form.elements, input, inputName, inputValue,
					l = inputs.length, i = 0;

				for ( ; i < l; i++) {
					input = inputs[i];
					inputName = input.name;
					inputValue = input.value;
					// Video URL or ID validation
					if (inputName == "url" && isEmpty(inputValue)){
						alert("You need to introduce at least a Video URL or ID.");
						return false;
					}
					// inputs of type "checkbox", "radio" and "text"
					if (input.checked || (input.type == "text" && !isEmpty(inputValue) && inputValue != input.defaultValue)) {
						if (inputName == "title") {
							inputValue = encodeStr(inputValue);
						}
						sc += ' ' + inputName + '="' + inputValue + '"';
					}
				}

				sc += "]";

				ed.execCommand('mceInsertContent', 0, sc);
				tinyMCEPopup.close();

				return false;
			};

			form.onsubmit = insertShortcode;

			tinyMCEPopup.resizeToInnerSize();
		});
	</script>
</body>
</html>    