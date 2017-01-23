<?php
/**
 * This file is used to markup the meta box aspects of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/admin/partials
 */
?>

<script type="text/javascript">
	var _iPanoramaAppData = window.iPanoramaAppData || {};
	if(_iPanoramaAppData) {
		_iPanoramaAppData.path = '<?php echo plugin_dir_url( __DIR__ ) ?>';
		_iPanoramaAppData.ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
		_iPanoramaAppData.uploadUrl = '<?php $upload_dir = wp_upload_dir(); echo $upload_dir["baseurl"]; ?>';
	}
</script>

<!-- ipnrm-ui-app -->
<div id="ipnrm-ui-app" x-ng-controller="ngiPanoramaAppController">
	<input type="hidden" id="ipnrm-ui-meta-ui-cfg" name="ipnrm-meta-ui-cfg" value="<?php echo get_post_meta( get_the_ID(), 'ipnrm-meta-ui-cfg', true ); ?>">
	<input type="hidden" id="ipnrm-ui-meta-panorama-cfg" name="ipnrm-meta-panorama-cfg" value="">
	<input type="hidden" id="ipnrm-ui-meta-panorama-theme" name="ipnrm-meta-panorama-theme" value="">
	<input type="hidden" id="ipnrm-ui-meta-total-scenes" name="ipnrm-meta-total-scenes" value="">
	
	<div id="ipnrm-ui-loading">
		<div class="ipnrm-ui-loading-progress"></div>
	</div>
	<div id="ipnrm-ui-workspace" class="ipnrm-ui-clearfix" x-workspace x-init="appData.fn.workspace.init">
		<div id="ipnrm-ui-screen">
			<div id="ipnrm-ui-canvas" x-ng-class="{'ipnrm-ui-target-tool': appData.targetTool}">
			</div>
		</div>
		<div id="ipnrm-ui-tabs">
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.general.isActive}" x-tab-panel-item x-id="general" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-cog"></i>General</div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.scenes.isActive}" x-tab-panel-item x-id="scenes" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-picture-o"></i>Scenes<div class="ipnrm-ui-label">{{appData.config.scenes.length}}</div></div>
			<div class="ipnrm-ui-tab" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.hotspots.isActive, 'ipnrm-ui-hide': !appData.scene.selected}" x-tab-panel-item x-id="hotspots" x-init="appData.fn.tabPanelItemInit"><i class="fa fa-fw fa-dot-circle-o"></i>Hotspots<div class="ipnrm-ui-label">{{appData.scene.selected.config.hotspots.length}}</div></div>
			<div class="ipnrm-ui-cmd-preview" x-ng-click="appData.fn.preview(appData);">Preview</div>
			<div class="ipnrm-ui-cmd-load" x-ng-click="appData.fn.storage.loadFromFile(appData);">Load From File</div>
			<div class="ipnrm-ui-cmd-save" x-ng-click="appData.fn.storage.saveToFile(appData);">Save To File</div>
			<input id="ipnrm-ui-load-from-file" type="file" style="display:none;" />
		</div>
		<div id="ipnrm-ui-tab-data">
			<!-- general section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.general.isActive}">
				<div class="ipnrm-ui-config">
					<table class="ipnrm-ui-form-table">
						<tbody>
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Panorama Size</div>
									<div class="ipnrm-ui-info">Use this option to set panorama custom width and height.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-label">Type</div>
										<select x-ng-model="appData.config.panoramaSize">
											<option value="none">Default</option>
											<option value="fixed">Fixed Size</option>
										</select>
									</div>
									
									<div class="ipnrm-ui-inline-group" x-ng-class="{'ipnrm-ui-hide': appData.config.panoramaSize=='none'}"> 
										<div class="ipnrm-ui-inline-group">
											<div class="ipnrm-ui-label">Width (px)</div>
											<div class="ipnrm-ui-control">
												<input type="number" min="0" x-ng-model="appData.config.panoramaWidth" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
											</div>
										</div>
										<div class="ipnrm-ui-inline-group">
											<div class="ipnrm-ui-label">Height (px)</div>
											<div class="ipnrm-ui-control">
												<input type="number" min="0" x-ng-model="appData.config.panoramaHeight" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
											</div>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Theme</div>
									<div class="ipnrm-ui-info">Use this option to set the theme for the panorama.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<select x-ng-model="appData.config.theme">
											<option value="ipnrm-theme-default">default</option>
											<option value="ipnrm-theme-modern">modern</option>
											<option value="ipnrm-theme-dark">dark</option>
										</select>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Image Preview</div>
									<div class="ipnrm-ui-info">Use this option to set a preview image to display before the panorama is loaded.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-image-preview" type="checkbox" x-ng-model="appData.config.imagePreview">
											<label for="ipnrm-cfg-image-preview">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control" >
										<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.config.imagePreviewUrl) + ')'}" x-select-image x-id="preview" x-init="appData.fn.selectImageInit">
											<div class="ipnrm-ui-img-clear" x-ng-click="appData.config.imagePreviewUrl='';$event.stopPropagation();"></div>
											<div class="ipnrm-ui-img-label">image</div>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Auto Load</div>
									<div class="ipnrm-ui-info">If ON the panorama will automatically load.<br>Default : OFF</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-auto-load" type="checkbox" x-ng-model="appData.config.autoLoad">
											<label for="ipnrm-cfg-auto-load">&nbsp;</label>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Auto Rotate</div>
									<div class="ipnrm-ui-info">If ON the panorama will automatically rotate when loaded.<br>Default : 3000 ms</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-auto-rotate" type="checkbox" x-ng-model="appData.config.autoRotate">
											<label for="ipnrm-cfg-auto-rotate">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Inactivity Delay</div>
									<div class="ipnrm-ui-control">
										<input type="number" min="0" x-ng-model="appData.config.autoRotateInactivityDelay"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Mouse Wheel Rotate</div>
									<div class="ipnrm-ui-info">Enable or disable the panorama rotate using the mouse scroll wheel.<br>Default : 0.2</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-mouse-wheel-rotate" type="checkbox" x-ng-model="appData.config.mouseWheelRotate">
											<label for="ipnrm-cfg-mouse-wheel-rotate">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Rotate Coefficient</div>
									<div class="ipnrm-ui-control">
										<input type="number" step="any" x-ng-model="appData.config.mouseWheelRotateCoef">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Mouse Wheel Zoom</div>
									<div class="ipnrm-ui-info">Enable or disable the panorama zoom using the mouse scroll wheel.<br>Default : 0.05</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-mouse-wheel-zoom" type="checkbox" x-ng-model="appData.config.mouseWheelZoom">
											<label for="ipnrm-cfg-mouse-wheel-zoom">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Zoom Coefficient</div>
									<div class="ipnrm-ui-control">
										<input type="number" step="any" x-ng-model="appData.config.mouseWheelZoomCoef">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Hover Grab</div>
									<div class="ipnrm-ui-info">Enable or disable the "grab/move" mode on the mouse hover event.<br>Default (yaw and pitch coef) : 20</div>
								</td>
								<td class="ipnrm-ui-col-data">
								<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hover-grab" type="checkbox" x-ng-model="appData.config.hoverGrab">
											<label for="ipnrm-cfg-hover-grab">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Yaw Coefficient</div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.config.hoverGrabYawCoef">
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Pitch Coefficient</div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.config.hoverGrabPitchCoef">
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Grab</div>
									<div class="ipnrm-ui-info">Enable or disable the "grab/move" mode on the mouse click and move events.<br>Default : 0.1</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-grab" type="checkbox" x-ng-model="appData.config.grab">
											<label for="ipnrm-cfg-grab">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Grab Coefficient</div>
									<div class="ipnrm-ui-control">
										<input type="number" step="any" x-ng-model="appData.config.grabCoef">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Controls</div>
									<div class="ipnrm-ui-info">Use this options to show or hide controls.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-show-on-hover" type="checkbox" x-ng-model="appData.config.showControlsOnHover">
											<label for="ipnrm-cfg-show-on-hover">Show Controls On Hover</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-thumbs-control" type="checkbox" x-ng-model="appData.config.showSceneThumbsCtrl">
											<label for="ipnrm-cfg-scene-thumbs-control">Scene Thumbs Control</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-radio">
											<input id="ipnrm-cfg-scene-thumbs-vertical-on" type="radio" name="ipnrm-meta-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="true">
											<label for="ipnrm-cfg-scene-thumbs-vertical-on">Scene Thumbs Vertical</label>
											<input id="ipnrm-cfg-scene-thumbs-vertical-off" type="radio" name="ipnrm-meta-scene-thumbs-vertical" x-ng-model="appData.config.sceneThumbsVertical" x-ng-value="false">
											<label for="ipnrm-cfg-scene-thumbs-vertical-off">Scene Thumbs Horizontal</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-menu-control" type="checkbox" x-ng-model="appData.config.showSceneMenuCtrl">
											<label for="ipnrm-cfg-scene-menu-control">Scene Menu Control (toggle thumbnails)</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-next-prev-control" type="checkbox" x-ng-model="appData.config.showSceneNextPrevCtrl">
											<label for="ipnrm-cfg-scene-next-prev-control">Scene Next Prev Control</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-next-prev-loop" type="checkbox" x-ng-model="appData.config.sceneNextPrevLoop">
											<label for="ipnrm-cfg-scene-next-prev-loop">Scene Next Prev Loop</label>
										</div>
									</div>
									
									<!--
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-share-control" type="checkbox" x-ng-model="appData.config.showShareCtrl">
											<label for="ipnrm-cfg-share-control">Share Control</label>
										</div>
									</div>
									-->
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-zoom-control" type="checkbox" x-ng-model="appData.config.showZoomCtrl">
											<label for="ipnrm-cfg-zoom-control">Zoom Control</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-fullscreen-control" type="checkbox" x-ng-model="appData.config.showFullscreenCtrl">
											<label for="ipnrm-cfg-fullscreen-control">Fullscreen Control</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-compass-control" type="checkbox" x-ng-model="appData.config.compass">
											<label for="ipnrm-cfg-compass-control">Compass</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-title-control" type="checkbox" x-ng-model="appData.config.title">
											<label for="ipnrm-cfg-title-control">Title</label>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Keyboard</div>
									<div class="ipnrm-ui-info">Enable or disable keyboard actions (navigation and zooming).</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-keyboard-nav" type="checkbox" x-ng-model="appData.config.keyboardNav">
											<label for="ipnrm-cfg-keyboard-nav">Keyboard Navigation</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-keyboard-zoom" type="checkbox" x-ng-model="appData.config.keyboardZoom">
											<label for="ipnrm-cfg-keyboard-zoom">Keyboard Zoom</label>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Popover</div>
									<div class="ipnrm-ui-info">Use this options to set popover settings.<br>We recommend do not change the popover template without having some knowledge.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-popover" type="checkbox" x-ng-model="appData.config.popover">
											<label for="ipnrm-cfg-popover">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Popover HTML Template</div>
									<div class="ipnrm-ui-control">
										<textarea cols="40" rows="5" x-ng-model="appData.config.popoverTemplate"></textarea>
									</div>
									
									<div class="ipnrm-ui-label">Popover Placement</div>
									<div class="ipnrm-ui-control">
										<select x-ng-model="appData.config.popoverPlacement">
											<option value="top">top</option>
											<option value="bottom">bottom</option>
											<option value="left">left</option>
											<option value="right">right</option>
											<option value="top-left">top-left</option>
											<option value="top-right">top-right</option>
											<option value="bottom-left">bottom-left</option>
											<option value="bottom-right">bottom-right</option>
										</select>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-popover-top" type="checkbox" x-ng-model="appData.config.hotSpotBelowPopover">
											<label for="ipnrm-cfg-popover-top">Popover is Under the Hotspot</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Popover Show Trigger</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-show-trigger-hover" type="checkbox"  x-ng-model="appData.config.popoverShowTriggerHover">
												<label for="ipnrm-cfg-popover-show-trigger-hover">Hover</label>
											</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-show-trigger-click" type="checkbox" x-ng-model="appData.config.popoverShowTriggerClick">
												<label for="ipnrm-cfg-popover-show-trigger-click">Click</label>
											</div>
										</div>
									</div>
									<br>
									
									<div class="ipnrm-ui-label">Popover Hide Trigger</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-hide-trigger-leave" type="checkbox" x-ng-model="appData.config.popoverHideTriggerLeave">
												<label for="ipnrm-cfg-popover-hide-trigger-leave">Leave</label>
											</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-hide-trigger-click" type="checkbox" x-ng-model="appData.config.popoverHideTriggerClick">
												<label for="ipnrm-cfg-popover-hide-trigger-click">Click</label>
											</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-hide-trigger-grab" type="checkbox" x-ng-model="appData.config.popoverHideTriggerGrab">
												<label for="ipnrm-cfg-popover-hide-trigger-grab">Grab</label>
											</div>
										</div>
									</div>
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-control">
											<div class="ipnrm-ui-checkbox">
												<input id="ipnrm-cfg-popover-hide-trigger-manual" type="checkbox" x-ng-model="appData.config.popoverHideTriggerManual">
												<label for="ipnrm-cfg-popover-hide-trigger-manual">Manual</label>
											</div>
										</div>
									</div>
									<br>
									
									<div class="ipnrm-ui-label">Popover Show CSS3 Class</div>
									<div class="ipnrm-ui-control">
										<button type="button" x-select-class x-init="appData.fn.selectPopoverShowClass">GET</button>
										<input type="text" x-ng-model="appData.config.popoverShowClass" x-ng-model-options="{updateOn: 'change blur'}">
									</div>
									
									<div class="ipnrm-ui-label">Popover Hide CSS3 Class</div>
									<div class="ipnrm-ui-control">
										<button type="button" x-select-class x-init="appData.fn.selectPopoverHideClass">GET</button>
										<input type="text" x-ng-model="appData.config.popoverHideClass" x-ng-model-options="{updateOn: 'change blur'}">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Scene Fade Duration</div>
									<div class="ipnrm-ui-info">Specifies the fade duration in milliseconds, when transitioning between scenes.<br>Default : 3000</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<input type="number" min="0" x-ng-model="appData.config.sceneFadeDuration"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Pitch Limits</div>
									<div class="ipnrm-ui-info">Enable or disable limits for the pitch parameter.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-pitch-limits" type="checkbox" x-ng-model="appData.config.pitchLimits">
											<label for="ipnrm-cfg-pitch-limits">&nbsp;</label>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Mobile</div>
									<div class="ipnrm-ui-info">Enable or disable the animation in the mobile browsers.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-mobile" type="checkbox" x-ng-model="appData.config.mobile">
											<label for="ipnrm-cfg-mobile">&nbsp;</label>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Custom CSS</div>
									<div class="ipnrm-ui-info">Enter any custom css you want to apply on this panorama.<br>Note:<br>Please do not use <b>&lt;style&gt;...&lt;/style&gt;</b> tag with Custom CSS.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-customCSS" type="checkbox" x-ng-model="appData.config.customCSS">
											<label for="ipnrm-cfg-customCSS">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<textarea cols="40" rows="10" x-ng-model="appData.config.customCSSContent"></textarea>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /end general section -->
			
			<!-- scene section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.scenes.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.scenes.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="scene in appData.config.scenes track by scene.id" x-ng-class="{'ipnrm-ui-active': scene.isSelected}" x-ng-click="appData.fn.scenes.select(appData, scene)">
							<span class="ipnrm-ui-drop-zone" x-drag-drop="appData.fn.scenes.dropScene" x-drag-over="appData.fn.scenes.dragOver" x-drag-leave="appData.fn.scenes.dragLeave" x-drag-over-element="scene"></span>
							<span class="ipnrm-ui-dragger" draggable="true" x-drag-start="appData.fn.scenes.dragStart" x-drag-element="scene"></span>
							<span class="ipnrm-ui-name">{{scene.id}}</span>
							<span class="ipnrm-ui-visible" x-ng-click="scene.isVisible=!scene.isVisible" x-ng-class="{'ipnrm-ui-off': !scene.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<table class="ipnrm-ui-form-table" x-ng-class="{'ipnrm-ui-hide': !appData.scene.selected}">
						<tbody>
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Scene Title</div>
									<div class="ipnrm-ui-info">Use this option to set the title of the scene.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-label">Title</div>
									<div class="ipnrm-ui-control">
										<input type="text" class="ipnrm-ui-long" x-ng-model="appData.scene.selected.config.title">
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-title-html" type="checkbox" x-ng-model="appData.scene.selected.config.titleHtml">
											<label for="ipnrm-cfg-scene-title-html">HTML</label>
										</div>
									</div>
									
									<!--
									<div class="ipnrm-ui-label">Selector</div>
									<div class="ipnrm-ui-control">
										<input type="text" class="ipnrm-ui-long" x-ng-model="appData.scene.selected.config.titleSelector" placeholder="it allows you to set an element's HTML content for the title">
									</div>
									-->
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Scene Type</div>
									<div class="ipnrm-ui-info">Use this option to set the type of the scene.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<select x-ng-model="appData.scene.selected.config.type">
											<option value="sphere">sphere</option>
											<option value="cylinder">cylinder</option>
											<option value="cube">cube</option>
										</select>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Image</div>
									<div class="ipnrm-ui-info">Use this option to set images for the scene.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageFront) + ')'}" x-select-image x-id="front" x-init="appData.fn.selectImageInit">
											<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageFront='';$event.stopPropagation();"></div>
											<div class="ipnrm-ui-img-label">front</div>
										</div>
										<span x-ng-class="{'ipnrm-ui-image-hide': appData.scene.selected.config.type != 'cube'}">
											<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageBack) + ')'}" x-select-image x-id="back" x-init="appData.fn.selectImageInit">
												<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageBack='';$event.stopPropagation();"></div>
												<div class="ipnrm-ui-img-label">back</div>
											</div>
											<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageLeft) + ')'}" x-select-image x-id="left" x-init="appData.fn.selectImageInit">
												<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageLeft='';$event.stopPropagation();"></div>
												<div class="ipnrm-ui-img-label">left</div>
											</div>
											<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageRight) + ')'}" x-select-image x-id="right" x-init="appData.fn.selectImageInit">
												<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageRight='';$event.stopPropagation();"></div>
												<div class="ipnrm-ui-img-label">right</div>
											</div>
											<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageTop) + ')'}" x-select-image x-id="top" x-init="appData.fn.selectImageInit">
												<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageTop='';$event.stopPropagation();"></div>
												<div class="ipnrm-ui-img-label">top</div>
											</div>
											<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.imageBottom) + ')'}" x-select-image x-id="bottom" x-init="appData.fn.selectImageInit">
												<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.imageBottom='';$event.stopPropagation();"></div>
												<div class="ipnrm-ui-img-label">bottom</div>
											</div>
										</span>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Thumb</div>
									<div class="ipnrm-ui-info">Use this option to set a thumbnail for the scene.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-scene-thumb" type="checkbox" x-ng-model="appData.scene.selected.config.thumb">
											<label for="ipnrm-cfg-scene-thumb">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-image" x-ng-style="{'background-image': 'url(' + appData.fn.getImageFullPath(appData, appData.scene.selected.config.thumbImage) + ')'}" x-select-image x-id="thumb" x-init="appData.fn.selectImageInit">
											<div class="ipnrm-ui-img-clear" x-ng-click="appData.scene.selected.config.thumbImage='';$event.stopPropagation();"></div>
											<div class="ipnrm-ui-img-label">thumb</div>
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Yaw, Pitch and Zoom</div>
									<div class="ipnrm-ui-info">Use this options to set the panorama's starting yaw and pitch position in degrees, and the zoom parameter.<br>Default (yaw and pitch) : 0<br>Default (zoom) : 75</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Yaw <div class="ipnrm-ui-label-info"><i class="ipnrm-ui-label-btn fa fa-fw fa-arrow-circle-down" x-ng-click="appData.scene.selected.config.yaw=appData.scene.selected.yaw"></i>{{appData.scene.selected.yaw}}</div></div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.scene.selected.config.yaw">
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Pitch <div class="ipnrm-ui-label-info"><i class="ipnrm-ui-label-btn fa fa-fw fa-arrow-circle-down" x-ng-click="appData.scene.selected.config.pitch=appData.scene.selected.pitch"></i>{{appData.scene.selected.pitch}}</div></div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.scene.selected.config.pitch">
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Zoom <div class="ipnrm-ui-label-info"><i class="ipnrm-ui-label-btn fa fa-fw fa-arrow-circle-down" x-ng-click="appData.scene.selected.config.zoom=appData.scene.selected.zoom"></i>{{appData.scene.selected.zoom}}</div></div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.scene.selected.config.zoom">
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Compass North Offset</div>
									<div class="ipnrm-ui-info">Use this option to set the offset, in degrees, of the center of the panorama from North. As this affects the compass, it only has an effect if compass is set to ON.<br>Default : 0</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label"><div class="ipnrm-ui-label-info"><i class="ipnrm-ui-label-btn fa fa-fw fa-arrow-circle-down" x-ng-click="appData.scene.selected.config.compassNorthOffset=appData.scene.selected.yaw"></i>{{appData.scene.selected.yaw}}</div></div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.scene.selected.config.compassNorthOffset">
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /end scene section -->
			
			<!-- hotspots section -->
			<div class="ipnrm-ui-section" x-ng-class="{'ipnrm-ui-active': appData.tabPanel.hotspots.isActive}">
				<div class="ipnrm-ui-item-list-wrap">
					<div class="ipnrm-ui-item-commands">
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.add(appData)"><i class="fa fa-fw fa-plus-square"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.copySelected(appData)"><i class="fa fa-fw fa-clone"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.upSelected(appData)"><i class="fa fa-fw fa-arrow-up"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.downSelected(appData)"><i class="fa fa-fw fa-arrow-down"></i></div>
						<div class="ipnrm-ui-item-command" x-ng-click="appData.fn.hotspots.removeSelected(appData)"><i class="fa fa-fw fa-trash"></i></div>
					</div>
					<ul class="ipnrm-ui-item-list">
						<li class="ipnrm-ui-item" x-ng-repeat="hotspot in appData.scene.selected.config.hotspots track by hotspot.id" x-ng-class="{'ipnrm-ui-active': hotspot.isSelected}" x-ng-click="appData.fn.hotspots.select(appData, hotspot)">
							<span class="ipnrm-ui-drop-zone" x-drag-drop="appData.fn.hotspots.dropScene" x-drag-over="appData.fn.hotspots.dragOver" x-drag-leave="appData.fn.hotspots.dragLeave" x-drag-over-element="hotspot"></span>
							<span class="ipnrm-ui-dragger" draggable="true" x-drag-start="appData.fn.hotspots.dragStart" x-drag-element="hotspot"></span>
							<span class="ipnrm-ui-name">{{hotspot.id}}</span>
							<span class="ipnrm-ui-visible" x-ng-click="hotspot.isVisible=!hotspot.isVisible;appData.hotspot.dirty=true" x-ng-class="{'ipnrm-ui-off': !hotspot.isVisible}"></span>
						</li>
					</ul>
				</div>
				<div class="ipnrm-ui-config">
					<table class="ipnrm-ui-form-table">
						<tbody>
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Target Tool</div>
									<div class="ipnrm-ui-info">Use the target tool to quick create a hotspot and it's location on the panorama.<br>When the target tool is ON click on the panorama together with Ctrl Key.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-target-tool" type="checkbox" x-ng-model="appData.targetTool">
											<label for="ipnrm-cfg-target-tool">&nbsp;</label>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="ipnrm-ui-form-table" x-ng-class="{'ipnrm-ui-hide': !appData.hotspot.selected}">
						<tbody>
							<tr class="ipnrm-ui-row ipnrm-ui-row-top-border">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Yaw and Pitch</div>
									<div class="ipnrm-ui-info">Use this options to set the hotspot's starting yaw and pitch position in degrees.<br>If you want to change the location of the selected hotspot, just click on the panorama together with Ctrl Key.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Yaw</div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.hotspot.selected.config.yaw" x-ng-model-options="{updateOn: 'change blur'}" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
									
									<div class="ipnrm-ui-inline-group">
										<div class="ipnrm-ui-label">Pitch</div>
										<div class="ipnrm-ui-control">
											<input type="number" step="any" x-ng-model="appData.hotspot.selected.config.pitch" x-ng-model-options="{updateOn: 'change blur'}" x-ng-change="appData.hotspot.dirty=true">
										</div>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Go to the Scene</div>
									<div class="ipnrm-ui-info">Use this option to specify the ID of the scene to link to.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<select x-ng-model="appData.hotspot.selected.config.sceneId">
											<option value="none">none</option>
											<option x-ng-repeat="scene in appData.config.scenes track by scene.id" value="{{appData.fn.getSceneKeyById(scene.id)}}">{{scene.id}} {{appData.fn.trunc(scene.config.title, 25)}}</option>
										</select>
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Popover</div>
									<div class="ipnrm-ui-info">Use this options to set popover settings.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hotspot-popover" type="checkbox" x-ng-model="appData.hotspot.selected.config.popover">
											<label for="ipnrm-cfg-hotspot-popover">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Popover Content</div>
									<div class="ipnrm-ui-control">
										<textarea cols="40" rows="5" x-ng-model="appData.hotspot.selected.config.popoverContent"></textarea>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hotspot-popover-html" type="checkbox" x-ng-model="appData.hotspot.selected.config.popoverHtml">
											<label for="ipnrm-cfg-hotspot-popover-html">HTML</label>
										</div>
									</div>
									
									<!--
									<div class="ipnrm-ui-label">Selector</div>
									<div class="ipnrm-ui-control">
										<input type="text" class="ipnrm-ui-long" x-ng-model="appData.hotspot.selected.config.popoverSelector" placeholder="it allows you to set an element's HTML content for the popover">
									</div>
									-->
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hotspot-popover-lazyload" type="checkbox" x-ng-model="appData.hotspot.selected.config.popoverLazyload">
											<label for="ipnrm-cfg-hotspot-popover-lazyload">Lazyload</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hotspot-popover-show" type="checkbox" x-ng-model="appData.hotspot.selected.config.popoverShow">
											<label for="ipnrm-cfg-hotspot-popover-show">Show on Load</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Popover Placement</div>
									<div class="ipnrm-ui-control">
										<select x-ng-model="appData.hotspot.selected.config.popoverPlacement">
											<option value="default">default</option>
											<option value="top">top</option>
											<option value="bottom">bottom</option>
											<option value="left">left</option>
											<option value="right">right</option>
											<option value="top-left">top-left</option>
											<option value="top-right">top-right</option>
											<option value="bottom-left">bottom-left</option>
											<option value="bottom-right">bottom-right</option>
										</select>
									</div>
									<br>
									
									<div class="ipnrm-ui-label">Popover Custom Width (px)</div>
									<div class="ipnrm-ui-control">
										<input type="number" min="0" x-ng-model="appData.hotspot.selected.config.popoverWidth" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
								</td>
							</tr>
							
							<tr class="ipnrm-ui-row">
								<td class="ipnrm-ui-col-label">
									<div class="ipnrm-ui-label">Hotspot Custom Style</div>
									<div class="ipnrm-ui-info">Use this option to set hotspot custom style. You can define your own style for hotspot with images, icons, text and etc.</div>
								</td>
								<td class="ipnrm-ui-col-data">
									<div class="ipnrm-ui-control">
										<div class="ipnrm-ui-checkbox">
											<input id="ipnrm-cfg-hotspot-content" type="checkbox" x-ng-model="appData.hotspot.selected.config.custom">
											<label for="ipnrm-cfg-hotspot-content">&nbsp;</label>
										</div>
									</div>
									
									<div class="ipnrm-ui-label">Hotspot Class Name</div>
									<div class="ipnrm-ui-control">
										<input type="text" x-ng-model="appData.hotspot.selected.config.customClassName">
									</div>
									
									<div class="ipnrm-ui-label">Hotspot HTML Content</div>
									<div class="ipnrm-ui-control">
										<textarea cols="40" rows="5" x-ng-model="appData.hotspot.selected.config.customContent"></textarea>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /end hotspots section -->
		</div>
	</div>
	<div id="ipnrm-ui-modal" class="ipnrm-ui-modal" x-ng-class="{'ipnrm-ui-active': appData.modal}" tabindex="-1" role="dialog">
		<div class="ipnrm-ui-modal-dialog">
			<div class="ipnrm-ui-modal-content">
				<div id="ipnrm-ui-modal-data">
				</div>
			</div>
		</div>
	</div>
	<div id="ipnrm-ui-preview-wrap" x-ng-class="{'ipnrm-ui-active': appData.preview}">
		<button type="button" id="ipnrm-ui-preview-close" aria-label="Close" x-ng-click="appData.fn.previewClose(appData);"><span aria-hidden="true">&times;</span></button>
		<div id="ipnrm-ui-preview-inner">
			<style x-ng-if="appData.config.customCSS">
				{{appData.config.customCSSContent}}
			</style>
			<div id="ipnrm-ui-preview-canvas">
			</div>
		</div>
	</div>
</div>
<!-- /end ipnrm-ui-app -->