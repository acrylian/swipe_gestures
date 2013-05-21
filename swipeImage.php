<?php
/**
 * A simple plugin that enables touch screen left and right swiping on the single image page.
 * Based on the jQuery plugin touchSwipe http://labs.rampinteractive.co.uk/touchSwipe
 *
 * @author Malte Müller (acrylian)
 *
 * @package plugins
 */

$plugin_is_filter = 9|THEME_PLUGIN;
$plugin_description = gettext('A simple plugin that enabled touch screen left and right swiping on the single image page. Based on the jQuery plugin touchSwipe.');
$plugin_author = 'Malte Müller (acrylian)';

zp_register_filter('theme_head','swipejs');

function swipejs() {
	global $_zp_gallery, $_zp_current_image, $_zp_gallery_page;
	if($_zp_gallery_page == 'image.php' && (hasPrevImage() || hasNextImage())) {
		?>
		<script type="text/javascript" src="<?php echo FULLWEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/swipeImage/jquery.touchSwipe.min.js"></script>
		<script type="text/javascript">
		 	$('html').swipe({
				//Generic swipe handler for all directions
				  <?php if(hasPrevImage()) { ?>
						swipeRight:function(event, direction, distance, duration, fingerCount) {
							//alert("You swiped " + direction );
							this.fadeOut();
							document.location.href = '<?php echo getPrevImageURL(); ?>';
						},
					<?php } ?>
					<?php if(hasNextImage()) { ?>
						swipeLeft:function(event, direction, distance, duration, fingerCount) {
							//alert("You swiped " + direction );	
							this.fadeOut();
							document.location.href = '<?php echo getNextImageURL(); ?>';
						},
					<?php } ?>
					//Default is 75px, set to 0 for demo so any distance triggers swipe
					threshold:0
			});
		</script>
	<?php
	}
}

?>