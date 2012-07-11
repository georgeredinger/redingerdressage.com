<?php
/*
Plugin Name: Youtube shortcode
Plugin URI: http://www.margenn.com/tubal/youtube_shortcode/
Description: Embed Youtube videos using shortcodes
Version: 1.8.3
Author: Túbal Martín
Author URI: http://www.margenn.com
License: GPL2

Copyright 2011-2012  Túbal Martín  (email : tubalmartin@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if (!class_exists('Youtube_shortcode')):

class Youtube_shortcode {

	var $plugin_version = '183'; // version 1.8.3
    var $hl; // Host language code
    var $is_feed;

	// Constructor / initialize plugin
	function Youtube_shortcode()
	{
		// Load our shortcode only on the frontend
		if ( !is_admin() )
		{
			// Is it a mobile phone? What browser?
            $this->is_mobile();
			// Guess once host language (used for Youtube's old embed code = Flash only)
            $this->get_hl();
            // do_shortcode() is registered as a default filter on 'the_content' with a priority of 11.
            add_shortcode('youtube_sc', array($this, 'output_html'));
            add_action('wp_head', array($this, 'output_css'));
		}
		// Allow the button to appear on any rich text editor (i.e. text editor in a widget)
		else
		{
			add_action('admin_init', array($this, 'setup_tinymce_button'));
		}
	}


    static function encode_str($str)
    {
        return str_replace(array(' ', '"', "'", '=', '[', ']', '/'), array('%20', '%22', '%27', '%3D', '%5B', '%5D', '%2F'), (string) $str);
    }


    function decode_str($str)
    {
        return str_replace(array('%20', '%22', '%27', '%3D', '%5B', '%5D', '%2F'), array(' ', '&quot;', "'", '=', '[', ']', '/'), (string) $str);
    }


    function output_css()
    {
        echo '<style type="text/css">'
           . '.youtube_sc{background-color:#000;color:#fff;font-size:12px}.youtube_sc a{color:blue;text-decoration:underline}.youtube_sc,.youtube_sc img,.youtube_sc iframe,.youtube_sc object,.youtube_sc embed{max-width:100%;_width:100%}.youtube_sc.fluid{position:relative;height:0;padding-top:25px;padding-left:0;padding-right:0}.youtube_sc.fluid .inner.block{display:block}.youtube_sc.fluid .inner,.youtube_sc.fluid iframe.yp,.youtube_sc.fluid object.yp,.youtube_sc.fluid embed.yp{position:absolute;width:100%;height:100%;left:0;top:0}.youtube_sc.fluid.widescreen{padding-bottom:56.25%}.youtube_sc.fluid.fourthree{padding-bottom:75%}'
		   . '</style>';
    }


	function output_html( $atts, $content = null )
	{
		if ( !isset($this->is_feed) )
        {
            $this->is_feed = is_feed();
        }

        $default_width  = '560';
		$default_height = '340';
        $default_ratio  = '16:9';
        $default_title  = 'YouTube video player';

		extract( shortcode_atts( array(
			// custom parameters
			'url' => '',
            'v' => '', // Alias of url
            'video' => '', // Alias of url
            'title' => $default_title, // SEO & WCAG 1.0+
			'width' => $default_width, // minimum: 200
			'height' => $default_height,
            'w' => $default_width, // Alias of width
			'h' => $default_height, // Alias of height
            'ratio' => $default_ratio, // or 4:3
            'class' => '', // Additional CSS class name(s)
            'embedcode' => 'new', // new = iframe. old = object.
			'version' => '3',
			'nocookie' => '0', // privacy mode
			// Youtube's official player parameters & default values
			// Reference: https://developers.google.com/youtube/player_parameters
			'autohide' => '2',
			'controls' => '1',
			'modestbranding' => '0',
			'origin' => '',
			'playlist' => '',
			'rel' => '1',
			'autoplay' => '0',
			'loop' => '0',
			'enablejsapi' => '0',
			'playerapiid' => '',
			'disablekb' => '0',
			'egm' => '0',
			'border' => '0',
			'color' => 'red',
			'color1' => 'b1b1b1',
			'color2' => 'cfcfcf',
			'start' => '',
			'theme' => 'dark',
			'fs' => '0',
			'hd' => '0',
			'showsearch' => '1',
			'showinfo' => '1',
			'iv_load_policy' => '1', // or 3
			'cc_load_policy' => '' // user's account setting. To force subtitles set to 1
		), $atts ) );

        // Handle alternative parameter names (aliases)
        $width  = $w != $default_width ? $w : $width;
        $height = $h != $default_height ? $h : $height;
        $url    = trim((empty($url) ? (empty($v) ? $video : $v) : $url));

        $width  = $this->is_mobile ? '100%' : ((int) $width < 200 ? '200' : ($this->is_feed && (int) $width > 650 ? '650' : $width));
        $height = $this->is_mobile ? '100%' : ((($width != $default_width && $height == $default_height) || ($this->is_feed && $width == '650')) ? (string) (floor((int) $width / ($ratio == $default_ratio ? 1.78 : 1.34)) + 25) : $height);
        $static = $this->is_mobile ? false : (($width != $default_width || $height != $default_height) ? true : false);
        $title  = $title != $default_title ? $this->decode_str((string) $title) : $default_title;
        $ratio  = $ratio == $default_ratio ? 'widescreen' : 'fourthree';
        $wtag   = in_the_loop() ? 'p' : 'div'; // Add support for Youtube shortcode in posts
        $itag   = $wtag == 'div' ? 'div' : 'span';

        $video_id  = $this->get_video_id($url);
        $video_url = 'http://'.$this->get_server_host($url).'youtube' . ((bool) $nocookie ? '-nocookie' : '') . '.com/%s/'.$video_id.'?version='.$version .
            ($autohide !== '2' ? '&amp;autohide=' . $autohide : '') .
            (!(bool) $controls ? '&amp;controls=0' : '') .
            ((bool) $modestbranding ? '&amp;modestbranding=1' : '') .
            (!empty($origin) ? '&amp;origin=' . $origin : '') .
            (!empty($playlist) ? '&amp;playlist=' . $playlist : '') .
            (!(bool) $rel ? '&amp;rel=0' : '') .
            ((bool) $autoplay ? '&amp;autoplay=1' : '') .
            ((bool) $loop ? '&amp;loop=1' : '') .
            ((bool) $enablejsapi ? '&amp;enablejsapi=1' : '') .
            (!empty($playerapiid) ? '&amp;playerapiid=' . $playerapiid : '') .
            ((bool) $disablekb ? '&amp;disablekb=1' : '') .
            ((bool) $egm ? '&amp;egm=1' : '') .
            ((bool) $border ? '&amp;border=1' : '') .
            ($color != 'red' ? '&amp;color=' . $color : '') .
            ($color1 != 'b1b1b1' ? '&amp;color1=0x' . $color1 : '') .
            ($color2 != 'cfcfcf' ? '&amp;color2=0x' . $color2 : '') .
            (!empty($start) ? '&amp;start=' . $start : '') .
            ($theme != 'dark' ? '&amp;theme=' . $theme : '') .
            ((bool) $fs ? '&amp;fs=1' : '') .
            ((bool) $hd ? '&amp;hd=1' : '') .
            (!(bool) $showsearch ? '&amp;showsearch=0' : '') .
            (!(bool) $showinfo ? '&amp;showinfo=0' : '') .
            ($iv_load_policy === '3' ? '&amp;iv_load_policy=' . $iv_load_policy : '') .
            (!empty($cc_load_policy) ? '&amp;cc_load_policy=' . $cc_load_policy : '');

        $flash_only_url     = sprintf($video_url, 'v') . '&amp;hl='.$this->hl;
        $flash_or_html5_url = sprintf($video_url, 'embed') . '&amp;wmode=transparent';
        
        $watch_on_youtube = '<a href="http://www.youtube.com/watch?v='.$video_id.'" target="_blank" title="Watch on YouTube">Watch this video on YouTube</a>.';
        $class_or_style   = $this->is_feed ? 'style="background-color:#000;display:block;margin-bottom:0;max-width:100%;"' : 'class="yp"';
        $fs               = (bool) $fs;

        $old_embed_code = '<object width="'.$width.'" height="'.$height.'" title="'.$title.'" '.$class_or_style.'>' .
                                '<param name="movie" value="'.$flash_only_url.'"></param>' .
                                ($fs ? '<param name="allowfullscreen" value="true"></param>' : '') .
                                '<param name="allowscriptaccess" value="always"></param>' .
                                '<param name="wmode" value="transparent"></param>' .
                                '<embed class="yp" src="'.$flash_only_url.'" width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" ' . ($fs ? 'allowfullscreen="true"' : '') . '></embed>' .
                                ($this->is_feed ? '' : 
                                '<span style="display:block;margin-top:15px;">The Adobe Flash Player is required for video playback.<br><a href="http://get.adobe.com/flashplayer/" title="Install from Adobe">Get the latest Flash Player</a> or '.$watch_on_youtube.'</span>') .
                          '</object>';
        $iframe_code    = '<iframe src="'.$flash_or_html5_url.'" width="'.$width.'" height="'.$height.'" title="'.$title.'" '.$class_or_style.' frameborder="0" allowfullscreen></iframe>';

        // AS2 or Old embed code ?
        $OEC = (int) $version == 2 || $embedcode == 'old' ? true : false;

        // HTML OUTPUT
        $output = '';

        if ($this->is_feed) {
            // Feeds
            $output .= ($OEC ? $old_embed_code : $iframe_code).'<p style="font-size:11px;margin-top:0;">'.$watch_on_youtube.'</p>';
        } else {
            // Browsers
            $output .= '<'.$wtag.' class="youtube_sc '.($this->is_mobile ? '' : $class).($static ? '' : ' fluid '.$ratio).'"'.($static ? ' style="width:'.$width.'px;height:'.$height.'px;"' : '').'>';
            $output .= !$static ? '<'.$itag.' class="inner block">' : '';

            // Fix for Opera mobile & Mini
            if ($this->opera_mobile) {
                $output .= '<a href="vnd.youtube:'.$video_id.'" title="'.$title.'"><img src="http://i.ytimg.com/vi/'.$video_id.'/hqdefault.jpg" width="100%" height="100%"/></a>';
            } else {
                $output .= $OEC ? $old_embed_code :
                    '<noscript>'
                        .'<style type="text/css">.youtube_sc iframe.yp{display:none;}</style>'
                        .$old_embed_code
                    .'</noscript>'
                    .$iframe_code;
            }

            $output .= !$static ? '</'.$itag.'>' : '';
            $output .= '</'.$wtag.'>';
        }

        return $output;
	}


	function get_video_id($url)
	{
		if (preg_match('#^https?\://(?:(?:[a-z0-9-_\.]+\.|)youtube\.com/(?:watch\?v=|v/|embed/)|youtu\.be/)([a-z0-9-_]+)|^([a-z0-9-_]+)$#i', $url, $matches) > 0) {
			return $matches[empty($matches[1]) ? 2 : 1];
		}
		// default video ID on error
		return 'QRS8MkLhQmM';
	}


	function get_server_host($url)
	{
		if (preg_match('/^http\:\/\/([a-zA-Z0-9\-\_\.]+\.)(?:youtube\.com|youtu\.be)/i', $url, $matches)) {
			return $matches[1];
		}

		return 'www.';
	}


    function get_hl()
	{
		$this->hl = 'en_US';

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && ! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$hl_codes = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

			if (count($hl_codes) > 0) {
				$hl_codes = explode(';', $hl_codes[0]);
				$hl_code = strtolower($hl_codes[0]);

				$hl_codes = array(
					'cs_CZ' => array('cs'), // CZECH
					'de_DE' => array('de','de-de','de-at','de-lu','de-li','de-ch'), // GERMAN
					'nl_NL' => array('nl','nl-be'), // DUTCH
					'en_GB' => array('en-gb'), // ENGLISH UK
					'es_ES' => array('es','es-es','es-ar'), // SPANISH (SPAIN & ARGENTINA)
					'es_MX' => array('es-mx','es-bo','es-cl','es-co','es-cr','es-do','es-ec','es-sv','es-gt','es-hn'), // SPANISH (MEXICO aka latin)
					'fr_FR' => array('fr','fr-fr','fr-ca','fr-be','fr-lu','fr-mc','fr-ch'), // FRENCH
					'it_IT' => array('it','it-ch'), // ITALIAN
					'ja_JP' => array('ja'), // JAPANESE
					'ko_KR' => array('ko','ko-kp','ko-kr'), // KOREAN
					'pl_PL' => array('pl'), // POLISH
					'pt_PT' => array('pt'), // PORTUGUESE
					'pt_BR' => array('pt-br'), // PORTUGUESE (BRAZIL)
					'ru_RU' => array('ru','ru-mo'), // RUSSIAN
					'sv_SE' => array('sv','sv-fi','sv-sv'), // SWEDISH
					'zh_TW' => array('zh','zh-tw','zh-hk','zh-cn','zh-sg') // CHINESE
				);

				foreach ($hl_codes as $k => $v) {
					if (in_array($hl_code, $v)) {
						$this->hl = $k;
						return;
					}
				}
			}
		}
	}


    // Detects most mobilephones/tablets.
    function is_mobile()
    {
        $mobiles = array(
            'opera_mobile' => 'opera (mobi|mini)', // Opera Mobile or Mini
            'webkit_mobile' => '(android|nokia|webos|hpwos|blackberry).*?webkit|webkit.*?(mobile|kindle|bolt|skyfire|dolfin|iris)', // Webkit mobile
            'firefox_mobile' => 'fennec|maemo', // Firefox mobile
            'ie_mobile' => 'iemobile|windows ce', // IE mobile
            'netfront' => 'netfront|kindle|psp|blazer|jasmine', // Netfront
            'uc_browser' => 'ucweb' // UC browser
        );

        // Init properties
        $this->is_mobile = false;

        foreach ($mobiles as $name => $regex) {
            $this->$name = false;
        }

        // Check user agent string
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        if (!empty($agent)) {
            foreach ($mobiles as $name => $regex) {
                if (preg_match('/'.$regex.'/i', $agent)) {
                    $this->is_mobile = $this->$name = true;
                    return;
                }
            }
        }

        // Fallbacks
        $regex = 'ip(hone|ad|od)|kindle|android|windows (phone|ce)|symb(ian|os)|(web|hpw)os|blackberry|palm|bada|nokia|htc|motorola|ericsson|lge?(-|;|\/|\s)|samsung|asus|mobile|phone|tablet|pocket|wap|wireless|up\.browser|up\.link|j2me|midp|cldc|kddi|mmp|obigo|novarra|teleca|openwave|uzardweb|pre\/|hiptop|avantgo|plucker|xiino|elaine|vodafone|sprint|o2';
        $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

        // is the device showing signs of support for text/vnd.wap.wml or application/vnd.wap.xhtml+xml
        // or is giving us a HTTP_X_WAP_PROFILE or HTTP_PROFILE header - only mobile devices would do this
        if (false !== strpos($accept,'text/vnd.wap.wml')
            || false !== strpos($accept,'application/vnd.wap.xhtml+xml')
            || isset($_SERVER['HTTP_X_WAP_PROFILE'])
            || isset($_SERVER['HTTP_PROFILE'])
            || preg_match('/'.$regex.'/i', $agent)
        ) {
            $this->is_mobile = true;
        }
    }

    
	// TinyMCE Button

	// Set up our TinyMCE button
	function setup_tinymce_button()
	{
		if (get_user_option('rich_editing') == 'true' && current_user_can('edit_posts')) {
			add_filter('mce_external_plugins', array($this, 'add_tinymce_button_script'));
			add_filter('mce_buttons', array($this, 'register_tinymce_button'));
		}
	}


	// Register our TinyMCE button
	function register_tinymce_button($buttons) {
		array_push($buttons, '|', 'YoutubeShortcodeButtonMargenn');
		return $buttons;
	}


	// Register our TinyMCE Script
	function add_tinymce_button_script($plugin_array) {
		$plugin_array['YoutubeShortcodeMargenn'] = plugins_url('tinymcebutton.js.php?params='.$this->get_pop_up_params(), __FILE__);
		return $plugin_array;
	}


	function get_pop_up_params()
	{
		return urlencode(base64_encode(
			'plugin_version='.$this->plugin_version.'&'.
			'includes_url='.urlencode(includes_url()).'&'.
			'plugins_url='.urlencode(plugins_url()).'&'.
			'charset='.urlencode(get_option('blog_charset'))
		));
	}

}

// Create just one instance per request
new Youtube_shortcode();

endif;