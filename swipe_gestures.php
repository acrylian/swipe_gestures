<?php
/**
 * A simple plugin that enabled touch screen left and right swiping for various previous and next pages.
 * Based on the jQuery plugin touchSwipe http://labs.rampinteractive.co.uk/touchSwipe
 *
 * @author Malte Müller (acrylian) <info@maltem.de>
 * @copyright 2015 Malte Müller
 * @license GPL v3 or later
 * @package plugins
 * @subpackage misc
 */

$plugin_is_filter = 9|THEME_PLUGIN;
$plugin_description = gettext('A simple plugin that enabled touch screen left and right swiping on the single image page. Based on the jQuery plugin touchSwipe.');
$plugin_author = 'Malte Müller (acrylian)';
$plugin_version = '1.0.4';
$option_interface = 'swipeGestures';
zp_register_filter('theme_head','swipeGestures::swipejs');

/**
 * Plugin option handling class
 *
 */
class swipeGestures {

	function swipeGesturesOptions() {
	
	}

	function getOptionsSupported() {
		$options = array(
			gettext('Single image page') => array(
				'key' => 'swipe_gestures_image', 
				'type' => OPTION_TYPE_CHECKBOX,
				'desc' => gettext('Enables left/right swipe gestures for the previous/next image navigation.')),
			gettext('Album pages') => array(
				'key' => 'swipe_gestures_album', 
				'type' => OPTION_TYPE_CHECKBOX,
				'desc' => gettext('Enables left/right swipe gestures for the previous/next album/search page navigation.')),
			gettext('News pages') => array(
				'key' => 'swipe_gestures_news', 
				'type' => OPTION_TYPE_CHECKBOX,
				'desc' => gettext('Enables left/right swipe gestures for the previous/next news loop page navigation (<em>news on index</em> option not supported).'))
		);
		return $options;
	}

	static function swipejs() {
		global $_zp_gallery, $_zp_current_image, $_zp_gallery_page;
		$prevurl = '';
		$nexturl = '';
		switch($_zp_gallery_page) {
			case 'index.php':
			case 'gallery.php':
			case 'album.php':
			case 'search.php':
				if(getOption('swipe_gestures_album')) {
					if(hasPrevPage()) {
						$prevurl = getPrevPageURL();
					}
					if(hasNextPage()) {
						$nexturl = getNextPageURL();
					}
				}
				break;
			case 'image.php':
				if(getOption('swipe_gestures_image')) {
					if(hasPrevImage()) {
						$prevurl = getPrevImageURL(); 
					}
					if(hasNextImage()) {
						$nexturl = getNextImageURL(); 
					}
				}
				break;
			case 'news.php':
				if(getOption('swipe_gestures_news') && getOption('zp_plugin_zenpage')) {
					if(is_NewsArticle()) {
						if(getPrevNewsURL()) {
							$prevurl = getPrevNewsURL();
							$prevurl = $prevurl['link'];
						}
						if(getNextNewsURL()) {
							$nexturl = getNextNewsURL();
							$nexturl = $nexturl['link'];
						}
					} else {
						if(getPrevNewsPageURL()) {
							$prevurl = getPrevNewsPageURL(); 
						}
						if(getNextNewsPageURL()) {
							$nexturl = getNextNewsPageURL(); 
						}
					}
				}
				break;
		}
		if(!empty($prevurl) || !empty($nexturl)) {
			?>
			<script type="text/javascript" src="<?php echo FULLWEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/swipe_gestures/jquery.touchSwipe.min.js"></script>
			<script type="text/javascript">
				$('html').swipe({
					//Generic swipe handler for all directions
						<?php if(!empty($prevurl)) { ?>
							swipeRight:function(event, direction, distance, duration, fingerCount) {
								this.fadeOut();
								document.location.href = '<?php echo $prevurl; ?>';
							},
						<?php } ?>
						<?php if(!empty($nexturl)) { ?>
							swipeLeft:function(event, direction, distance, duration, fingerCount) {
								this.fadeOut();
								document.location.href = '<?php echo $nexturl; ?>';
							},
						<?php } ?>
						//Default is 75px, set to 0 for demo so any distance triggers swipe
						threshold: 100
				});
			</script>
		<?php
		}
	}
}
?>
