<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/public
 */
class iPanorama_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $plugin_name;
	
	
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $version;

	
	/**
	 * The post type of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $post_type;
	
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $post_type ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->post_type = $post_type;
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		global $post, $wpdb;
		$shortcode_found = false;
		$post_content = '';
		
		if(!isset($post)) {
			return;
		}
		
		if ( has_shortcode($post->post_content, 'ipanorama') ) {
			$shortcode_found = true;
			$post_content = $post->post_content;
		} else if ( isset($post->ID) ) {
			$results = $wpdb->get_results( $wpdb->prepare(
				"SELECT meta_value FROM $wpdb->postmeta " .
				"WHERE post_id = %d and meta_value LIKE '%%ipanorama%%'", $post->ID ) );
			
			foreach($results as $rec) {
				$post_content = $post_content . $rec->meta_value;
			}
			$shortcode_found = !empty( $post_content );
		}
		
		if($shortcode_found) {
			$theme_found = false;
			$regex_pattern = get_shortcode_regex();
			preg_match_all('/'.$regex_pattern.'/s', $post_content, $matches, PREG_PATTERN_ORDER);
			
			// include common css styles
			wp_enqueue_style( $this->plugin_name . '_effectless', plugin_dir_url( __DIR__ ) . 'lib/effect.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_ipanorama', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.css', array(), $this->version, 'all' );
			
			if(is_array($matches) && isset($matches[2]) && in_array("ipanorama",$matches[2]) ) {
				foreach ($matches[3] as $key => $value) {
					$attribures_str = str_replace (" ", "&", trim($value));
					$attribures_str = str_replace ('"', '', $attribures_str);
				
					$defaults = array (
						'id' => null,
						'slug' => null
					);
					$attributes = wp_parse_args ($attribures_str, $defaults);
					
					$post_id = null;
					if ( isset($attributes["id"]) ) {
						$post_id = $attributes["id"];
					} if (isset ($attributes["slug"])) { 
						$post_slug = $attributes["slug"];
						$result = $wpdb->get_var( $wpdb->prepare( 
							"SELECT ID FROM $wpdb->posts " .
							"WHERE post_name = %s AND post_type = %s AND post_status = 'publish'", 
							$post_slug, "ipnrm_item" ) );
							
						if ( $result ) {
							$post_id = $result;
						}
					}
					
					// add theme css
					if ( isset($post_id) ) {
						$result = $wpdb->get_var( $wpdb->prepare(
							"SELECT meta_value FROM $wpdb->postmeta " .
							"WHERE post_id = %d AND meta_key = %s",
							$post_id, "ipnrm-meta-panorama-theme" ) );
						
						if (strpos($result, 'default') !== false) {
							wp_enqueue_style( $this->plugin_name . '_ipanorama_default', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.default.css', array(), $this->version, 'all' );
							$theme_found = true;
						} else if (strpos($result, 'modern') !== false) {
							wp_enqueue_style( $this->plugin_name . '_ipanorama_modern', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.modern.css', array(), $this->version, 'all' );
							$theme_found = true;
						} else if (strpos($result, 'dark') !== false) {
							wp_enqueue_style( $this->plugin_name . '_ipanorama_dark', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.dark.css', array(), $this->version, 'all' );
							$theme_found = true;
						}
					}
				}
			}
			
			if( !$theme_found ) {
				wp_enqueue_style( $this->plugin_name . '_ipanorama_default', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.default.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '_ipanorama_modern', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.modern.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '_ipanorama_dark', plugin_dir_url( __DIR__ ) . 'lib/ipanorama.theme.dark.css', array(), $this->version, 'all' );
			}
			
			wp_enqueue_script( $this->plugin_name . '-three',     plugin_dir_url( __DIR__ ) . 'lib/three.min.js', array(), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-ipanorama', plugin_dir_url( __DIR__ ) . 'lib/jquery.ipanorama.min.js', array( 'jquery' ), $this->version, false );
		}
	}
	
	/**
	 * Init the edit screen of the plugin post type item
	 *
	 * @since 1.0.0
	 */
	public function public_init() {
		add_shortcode( $this->plugin_name, array( $this , 'shortcode') );
	}
	
	/**
	 * Shortcode output for the plugin
	 *
	 * @since 1.0.0
	 */
	public function shortcode( $atts ) {
		extract(
			shortcode_atts(
				array(
					'id' => 0,
					'slug' => '',
					'width' => NULL,
					'height' => NULL,
					'class' => NULL
				), $atts
			)
		);
		
		if ( !$id && !$slug ) {
			return __('Invalid iPanorama shortcode attributes', $this->plugin_name);
		}
		
		if ( !$id ) {
			$obj = get_page_by_path( $slug, OBJECT, $this->post_type );
			if ( $obj ) {
				$id = $obj->ID;
			} else {
				return __('Invalid iPanorama slug attribute', $this->plugin_name);
			}
		}
		
		$cfg = html_entity_decode(get_post_meta( $id, 'ipnrm-meta-panorama-cfg', true ), ENT_QUOTES);
		
		// make parameters
		$json = json_decode($cfg);
		$panoramaSize   = $json->panoramaSize;
		$panoramaWidth  = $json->panoramaWidth;
		$panoramaHeight = $json->panoramaHeight;

		$panoramaWidthStyle  = ($width  != NULL ? 'width:'  . $width  . ';' : (($panoramaSize == 'fixed' && $panoramaWidth  > 0) ? 'width:'  . $panoramaWidth  . 'px;' : ($class ? NULL : 'width:100%;')));
		$panoramaHeightStyle = ($height != NULL ? 'height:' . $height . ';' : (($panoramaSize == 'fixed' && $panoramaHeight > 0) ? 'height:' . $panoramaHeight . 'px;' : ($class ? NULL : 'height:250px;')));

		// generate unique prefix for $id to avoid clashes with multiple same shortcode use
		$prefix  = strtolower(wp_generate_password( 5, false ));
		$id_data = 'ipnrm-data-' . $prefix . '-' . $id;
		$id      = 'ipnrm-' . $prefix . '-' . $id;
		
		$upload_dir = wp_upload_dir();
		$baseurl = $upload_dir["baseurl"];
		
		// turn on buffering 
		ob_start();
		?>
<?php
	if($json->customCSS) { 
		echo '<style>' . PHP_EOL;
		echo $json->customCSSContent . PHP_EOL;
		echo '</style>' . PHP_EOL;
	}
?>
<div id='<?php echo $id_data; ?>' style='display:none;'>
<?php foreach ($json->scenes as $key => $scene) { ?>
<?php if( property_exists($scene, 'title') ) { ?>
<div id='<?php echo 'ipnrm-data-scene-' . $prefix . '-' . $key; ?>'>
<?php echo $scene->title; ?>
</div>
<?php } ?>
<?php if( property_exists($scene, 'hotSpots') ) { ?>
<?php $index = 0; ?>
<?php foreach($scene->hotSpots as $hotspot) { ?>
<?php if( property_exists($hotspot, 'popoverContent') ) { ?>
<div id='<?php echo 'ipnrm-data-scene-' . $prefix . '-' . $key . '-popover-' . $index; ?>'>
<?php echo do_shortcode($hotspot->popoverContent); ?>
</div>
<?php } ?>
<?php $index = $index + 1; ?>
<?php } // hotspots ?>
<?php } ?>
<?php } // scenes ?>
</div>

<?php if( $panoramaWidthStyle || $panoramaHeightStyle ) { ?>
	<div id='<?php echo $id; ?>' <?php echo ($class != NULL ? 'class=\'' . $class . '\'' : ''); ?> style='<?php echo $panoramaWidthStyle . $panoramaHeightStyle; ?>'></div>
<?php } else { ?>
	<div id='<?php echo $id; ?>' <?php echo ($class != NULL ? 'class=\'' . $class . '\'' : ''); ?>></div>
<?php } ?>

<script type="text/javascript">
jQuery( document ).ready(function( jQuery ) {
	jQuery( '#<?php echo $id; ?>' ).ipanorama({
		theme: '<?php echo $json->theme; ?>',
<?php if( property_exists($json, 'imagePreview') ) { ?>
		imagePreview: '<?php echo $baseurl . $json->imagePreview; ?>',
<?php } ?>
<?php if( property_exists($json, 'autoLoad') ) { ?>
		autoLoad: <?php echo ($json->autoLoad ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'autoRotate') ) { ?>
		autoRotate: <?php echo ($json->autoRotate ? 'true' : 'false'); ?>,
		autoRotateInactivityDelay: <?php echo $json->autoRotateInactivityDelay; ?>,
<?php } ?>
<?php if( property_exists($json, 'mouseWheelRotate') ) { ?>
		mouseWheelRotate: <?php echo ($json->mouseWheelRotate ? 'true' : 'false'); ?>,
		mouseWheelRotateCoef: <?php echo $json->mouseWheelRotateCoef; ?>,
<?php } ?>
<?php if( property_exists($json, 'mouseWheelZoom') ) { ?>
		mouseWheelZoom: <?php echo ($json->mouseWheelZoom ? 'true' : 'false'); ?>,
		mouseWheelZoomCoef: <?php echo $json->mouseWheelZoomCoef; ?>,
<?php } ?>
<?php if( property_exists($json, 'hoverGrab') ) { ?>
		hoverGrab: <?php echo ($json->hoverGrab ? 'true' : 'false'); ?>,
		hoverGrabYawCoef: <?php echo $json->hoverGrabYawCoef; ?>,
		hoverGrabPitchCoef: <?php echo $json->hoverGrabPitchCoef; ?>,
<?php } ?>
<?php if( property_exists($json, 'grab') ) { ?>
		grab: <?php echo ($json->grab ? 'true' : 'false'); ?>,
		grabCoef: <?php echo $json->grabCoef; ?>,
<?php } ?>
<?php if( property_exists($json, 'showControlsOnHover') ) { ?>
		showControlsOnHover: <?php echo ($json->showControlsOnHover ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showSceneThumbsCtrl') ) { ?>
		showSceneThumbsCtrl: <?php echo ($json->showSceneThumbsCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showSceneMenuCtrl') ) { ?>
		showSceneMenuCtrl: <?php echo ($json->showSceneMenuCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showSceneNextPrevCtrl') ) { ?>
		showSceneNextPrevCtrl: <?php echo ($json->showSceneNextPrevCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showShareCtrl') ) { ?>
		showShareCtrl: <?php echo ($json->showShareCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showZoomCtrl') ) { ?>
		showZoomCtrl: <?php echo ($json->showZoomCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'showFullscreenCtrl') ) { ?>
		showFullscreenCtrl: <?php echo ($json->showFullscreenCtrl ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'sceneThumbsVertical') ) { ?>
		sceneThumbsVertical: <?php echo ($json->sceneThumbsVertical ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'title') ) { ?>
		title: <?php echo ($json->title ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'compass') ) { ?>
		compass: <?php echo ($json->compass ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'keyboardNav') ) { ?>
		keyboardNav: <?php echo ($json->keyboardNav ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'keyboardZoom') ) { ?>
		keyboardZoom: <?php echo ($json->keyboardZoom ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'sceneNextPrevLoop') ) { ?>
		sceneNextPrevLoop: <?php echo ($json->sceneNextPrevLoop ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'popover') ) { ?>
		popover: <?php echo ($json->popover ? 'true' : 'false'); ?>,
<?php if( $json->popover ) { ?>
<?php if( property_exists($json, 'popoverTemplate') ) { ?>
		popoverTemplate: '<?php echo $json->popoverTemplate; ?>',
<?php } ?>
		popoverPlacement: '<?php echo $json->popoverPlacement; ?>',
		popoverShowTrigger: '<?php echo $json->popoverShowTrigger; ?>',
		popoverHideTrigger: '<?php echo $json->popoverHideTrigger; ?>',
<?php if( property_exists($json, 'popoverShowClass') ) { ?>
		popoverShowClass: '<?php echo $json->popoverShowClass; ?>',
<?php } ?>
<?php if( property_exists($json, 'popoverHideClass') ) { ?>
		popoverHideClass: '<?php echo $json->popoverHideClass; ?>',
<?php } ?>
<?php if(property_exists($json, 'hotSpotBelowPopover')) { ?>
		hotSpotBelowPopover: <?php echo ($json->hotSpotBelowPopover ? 'true' : 'false'); ?>,
<?php } ?>
<?php } ?>
<?php } ?>
<?php if( property_exists($json, 'pitchLimits') ) { ?>
		pitchLimits: <?php echo ($json->pitchLimits ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'mobile') ) { ?>
		mobile: <?php echo ($json->mobile ? 'true' : 'false'); ?>,
<?php } ?>
<?php if( property_exists($json, 'sceneId') ) { ?>
		sceneId: '<?php echo $json->sceneId; ?>',
		sceneFadeDuration: <?php echo $json->sceneFadeDuration; ?>,
<?php } ?>
		scenes: {
<?php foreach ($json->scenes as $key => $scene) { ?>
			<?php echo $key; ?> : {
				type: '<?php echo $scene->type; ?>',
<?php if( $scene->type == 'cube' ) { ?>
				image: {
					front:  '<?php echo $baseurl . $scene->image->front; ?>',
					back:   '<?php echo $baseurl . $scene->image->back; ?>',
					left:   '<?php echo $baseurl . $scene->image->left; ?>',
					right:  '<?php echo $baseurl . $scene->image->right; ?>',
					top:    '<?php echo $baseurl . $scene->image->top; ?>',
					bottom: '<?php echo $baseurl . $scene->image->bottom; ?>',
				},
<?php } else { ?>
				image: '<?php echo $baseurl . $scene->image; ?>',
<?php } ?>
<?php if( property_exists($scene, 'thumb') ) { ?>
<?php if( $scene->thumb ) { ?>
	thumb: 'true',
	thumbImage:  '<?php echo $baseurl . $scene->thumbImage; ?>',
<?php } ?>
<?php } ?>
<?php if( property_exists($scene, 'yaw') ) { ?>
				yaw: <?php echo $scene->yaw; ?>,
<?php } ?>
<?php if( property_exists($scene, 'pitch') ) { ?>
				pitch: <?php echo $scene->pitch; ?>,
<?php } ?>
<?php if( property_exists($scene, 'zoom') ) { ?>
				zoom: <?php echo $scene->zoom; ?>,
<?php } ?>
<?php if( property_exists($scene, 'compassNorthOffset') ) { ?>
				compassNorthOffset: <?php echo $scene->compassNorthOffset; ?>,
<?php } ?>
<?php if( property_exists($scene, 'title') ) { ?>
				titleHtml: <?php echo ($scene->titleHtml ? 'true' : 'false'); ?>,
				titleSelector: '<?php echo '#ipnrm-data-scene-' . $prefix . '-' . $key; ?>',
<?php } ?>
<?php if( property_exists($scene, 'hotSpots') ) { ?>
				hotSpots: [
<?php $index = 0; ?>
<?php foreach($scene->hotSpots as $hotspot) { ?>
					{
						yaw: <?php echo $hotspot->yaw; ?>,
						pitch: <?php echo $hotspot->pitch; ?>,
<?php if( property_exists($hotspot, 'sceneId') ) { ?>
						sceneId: '<?php echo $hotspot->sceneId; ?>',
<?php } ?>
<?php if( property_exists($hotspot, 'className') ) { ?>
						className: '<?php echo $hotspot->className; ?>',
<?php } ?>
<?php if( property_exists($hotspot, 'content') ) { ?>
<?php $content = $hotspot->content; ?>
<?php $content = addcslashes($content, "\'"); ?>
<?php $content = str_replace(array("\n", "\t", "\r"),'', $content); ?>
						content: '<?php echo $content; ?>',
<?php } ?>
<?php if( property_exists($hotspot, 'popoverContent') ) { ?>
						popoverHtml: <?php echo ($hotspot->popoverHtml ? 'true' : 'false'); ?>,
						popoverSelector: '<?php echo '#ipnrm-data-scene-' . $prefix . '-' . $key . '-popover-' . $index; ?>',
<?php if( property_exists($hotspot, 'popoverPlacement') ) { ?>
						popoverPlacement: '<?php echo $hotspot->popoverPlacement; ?>',
<?php } ?>
<?php if( property_exists($hotspot, 'popoverWidth') ) { ?>
						popoverWidth: <?php echo $hotspot->popoverWidth; ?>,
<?php } ?>
<?php } ?>
					},
<?php $index = $index + 1; ?>
<?php } ?>
				]
<?php } ?>
			},
<?php } ?>
		}
	});
});
</script>
		<?php 
		// get the buffered content into a var
		$output = ob_get_contents();
		
		// clean buffer
		ob_end_clean();
		
		return $output;
	}
}