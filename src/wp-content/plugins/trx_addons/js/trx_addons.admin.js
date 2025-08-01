/**
 * Admin utilities (for internal use only!)
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

/* global jQuery, TRX_ADDONS_STORAGE */

(function() {

	"use strict";

	if ( typeof TRX_ADDONS_STORAGE == 'undefined' ) window.TRX_ADDONS_STORAGE = {};

	var $document = jQuery( document ),
		$body = jQuery( 'body' );
	
	$document.ready( function() {
	
		// Trigger 'admin_action.init_hidden_elements' on meta box hide and show
		$document.on('postbox-toggled', function() {
			$document.trigger('admin_action.init_hidden_elements');
		});
		// Trigger 'admin_action.init_hidden_elements' on sorting meta boxes
		jQuery('.meta-box-sortables').on( 'sortstop', function() {
			$document.trigger('admin_action.init_hidden_elements');
		});

		// Scroll to the theme panel after page reloaded
		trx_addons_document_animate_to( jQuery( '.trx_addons_scroll_to' ).eq(0) );


		// Hide admin notice
		jQuery( '.trx_addons_admin_notice .notice-dismiss' )
			.on( 'click', function(e) {
				jQuery.post(
					TRX_ADDONS_STORAGE['ajax_url'], {
						'action': 'trx_addons_hide_' + jQuery( this ).parent().data( 'notice' ) + '_notice',
						'nonce': TRX_ADDONS_STORAGE['ajax_nonce'],
						is_admin_request: 1
					},
					function(response){}
				);
				e.preventDefault();
				return false;
			}
		);
		
		
		// Check fields dependencies in widgets
		//-----------------------------------------------------------------------------------

		var in_widgets = false;

		// Check dependencies in widgets in admin menu
		jQuery( '.widget-liquid-right,.widgets-holder-wrap.inactive-sidebar,.edit-widgets-block-editor' )
			.on( 'change', '[data-param-name]', function() {
				trx_addons_admin_check_dependencies(jQuery(this).parents('form'));
			} )
			.each( function () {
				var $area = jQuery( this );
				in_widgets = true;
				if ( $area.hasClass( 'edit-widgets-block-editor' ) ) {
					trx_addons_admin_check_dependencies( $area );
				} else {
					$area.find( 'form' ).each( function() {
						trx_addons_admin_check_dependencies(jQuery(this));
					} );
				}
			} );

		// Check dependencies in widgets inside Customize sections (before open for dinamically created sections)
		jQuery( '#customize-theme-controls .control-section' )
			// WordPress 5.7- (classic widgets panel)
			.on( 'click', '.widget-title', function() {
				var widget = jQuery( this ).parents('.widget').eq(0).find('.form');
				if ( widget.length > 0 ) {
					trx_addons_admin_check_dependencies( widget );
				}
			} )
			// WordPress 5.8+ (widgets block editor)
			.on( 'click', '.wp-block-legacy-widget__edit-preview', function() {
				setTimeout( function() {
					var widget = jQuery( this ).siblings('.wp-block-legacy-widget__edit-form').eq(0).find('.form');
					if ( widget.length > 0 ) {
						trx_addons_admin_check_dependencies( widget );
					}
				}, 500 );
			} );
		jQuery('#customize-theme-controls .control-section')
			.on( 'change', '[data-param-name]', function() {
				trx_addons_admin_check_dependencies(jQuery(this).parents('.form').eq(0));
			} );

		// Check dependencies after the widget added or updated
		$document.on( 'widget-added widget-updated', function(e, widget) {
			if ( widget ) {
				var widget_form = jQuery(widget).find('form');
				if ( widget_form.length > 0 ) {
					trx_addons_admin_check_dependencies( widget_form );
				}
			}
		} );

		// Check for dependencies
		function trx_addons_admin_check_dependencies(cont) {
			cont.find('[data-param-name]').each( function() {
				var ctrl = jQuery(this),
					id = ctrl.data('param-name'),
					depend = ctrl.data('param-dependency');
				if ( !id || !depend) return;
				if ( depend ) {
					var dep_cnt = 0, dep_all = 0;
					var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
					var dep_strict = typeof depend.strict != 'undefined';
					var fld=null, val='', name='', subname='', i;
					var parts = '', parts2 = '';
					for (i in depend) {
						if (i == 'compare' || i == 'strict') continue;
						dep_all++;
						name = i;
						subname = '';
						if (name.indexOf('[') > 0) {
							parts = name.split('[');
							name = parts[0];
							subname = parts[1].replace(']', '');
						}
						fld = cont.find('[data-param-name="'+name+'"]');
						if (fld.length > 0) {
							val = trx_addons_admin_get_field_value(fld);
							if (subname !== '') {
								parts = val.split('|');
								for (var p=0; p < parts.length; p++) {
									parts2 = parts[p].split('=');
									if (parts2[0]==subname) {
										val = parts2[1];
									}
								}
							}
							for (var j in depend[i]) {
								if ( 
									   (depend[i][j]=='not_empty' && val !== '')	// Main field value is not empty - show current field
									|| (depend[i][j]=='is_empty' && val === '')		// Main field value is empty - show current field
									|| (val !== '' && (!isNaN(depend[i][j]) 		// Main field value equal to specified value - show current field
														? val==depend[i][j]
														: (dep_strict 
																? val==depend[i][j]
																: (''+val).indexOf(depend[i][j])===0
															)
													)
										)
									|| (val !== '' && (""+depend[i][j]).charAt(0) == '^' && (''+val).indexOf(depend[i][j].substr(1))==-1)
																				// Main field value not equal to specified value - show current field
								) {
									dep_cnt++;
									break;
								}
							}
						} else {
							dep_all--;
						}
						if (dep_cnt > 0 && dep_cmp == 'or')
							break;
					}
					if (((dep_cnt > 0 || dep_all === 0) && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
						ctrl.parents('[class^="widget_field_type_"]').slideDown().removeClass('trx_addons_options_no_use');
					} else {
						ctrl.parents('[class^="widget_field_type_"]').slideUp().addClass('trx_addons_options_no_use');
					}
				}
			});
		}

		function trx_addons_admin_get_field_value(fld) {
			var ctrl = fld.parents('[class^="widget_field_type_"]');
			var val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
						? (ctrl.find('input[type="'+fld.attr('type')+'"]:checked').length > 0
							? (ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val() !== ''
								&& ''+ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val() !== '0'
									? ctrl.find('input[type="'+fld.attr('type')+'"]:checked').val()
									: 1
								)
							: 0
							)
						: fld.val();
			if (val===undefined || val===null) val = '';
			return val;
		}


		// Refresh taxonomies and terms lists when post type is changed
		//-----------------------------------------------------------------------------------
		
		// In widgets mode
		jQuery('.widget-liquid-right,.widgets-holder-wrap,.customize-control-widget_form,.edit-widgets-block-editor,.customize-control-sidebar_block_editor')
			.on('change', '.trx_addons_post_type_selector,.trx_addons_taxonomy_selector', function() {
				var field = jQuery(this),
					num = 0;
				field
					.parent()
					.nextAll()
					.find( field.attr('class').indexOf('_post_type_selector') > 0 ? 'select[class*="_taxonomy_selector"]' : 'select[class*="_terms_selector"]')
					.each(function() {
						var cat_fld = jQuery(this);
						var cat_lbl = cat_fld.prev('label');
						setTimeout(function(){
							trx_addons_refresh_list(cat_fld.attr('class').indexOf('_taxonomy_selector') > 0
													? 'taxonomies'
													: 'terms',
												field.val(),
												cat_fld,
												cat_lbl);
						}, 300*num);
						num++;
					});
				return false;
			});
	
		// In ThemeREX Addons Options
		jQuery('.trx_addons_options')
			.on('change', '.trx_addons_post_type_selector,.trx_addons_taxonomy_selector', function() {
				var field_container = jQuery(this).parents('.trx_addons_options_item');
				var cat_fld = field_container.next().find('[class*="_selector"]');
				var cat_lbl = field_container.next().find('.trx_addons_options_item_title');
				if (cat_fld.length > 0) {
					trx_addons_refresh_list(cat_fld.hasClass('trx_addons_taxonomy_selector')
												? 'taxonomies' 
												: 'terms',
											jQuery(this).val(),
											cat_fld,
											cat_lbl);
				}
				return false;
			});
		
		// In VC editor
		// Attention! Code of this handler is moved to the api/js_composer/js_composer.admin.js
		
		// In Elementor editor
		// Attention! Code of this handler is moved to the api/elementor/elementor.editor.js


		// Refresh link on the post editor when select with layout is changed
		// in Theme Options, VC editor or Elementor editor
		//--------------------------------------------------------------------
		$body.on('change', 'select', function () {
			var selector = jQuery(this),
				parent = selector.data('setting') !== undefined
							? selector.closest('.elementor-control-content').eq(0)
							: selector.parent().parent();
			var a = parent.find('a.trx_addons_post_editor');
			if (a.length > 0) trx_addons_layout_selector_refresh_link(a);
		});
		
		window.trx_addons_layout_selector_refresh_link = function(a) {
			var parent = a.closest('.elementor-control-content').length > 0
							? a.closest('.elementor-control-content').eq(0)
							: a.parent().parent();
			var selector = parent.find('select');
			if (selector.length > 0) {
				var id = Number(selector.val());
				if (isNaN(id) || id <= 0) id = 0;
				a.attr('href', a.attr('href').replace(/post=[0-9]{1,5}/, "post="+id));
				if (id == 0) {
					a.addClass('trx_addons_hidden');
				} else {
					a.removeClass('trx_addons_hidden');
				}
			}
		};

		// Menu item settings
		//---------------------------------------------------
		if ( location.href.indexOf('nav-menus.php') > 0 ) {
			window.TrxNavMenuSettings = {
				currentItemId: null,
				currentItemDepth: null,
	
				init: function() {
					var _this = this;

					jQuery( '#menu-to-edit').on( 'click', '.menu-item-actions .item-settings', function(e) {
						e.preventDefault();
						_this.openItemSettings(_this, e);
						return false;
					} );

					jQuery( '#trx-addons-nav-menu-item-save').on( 'click', function( e ) {
						e.preventDefault();
						var $button = jQuery( this);
						_this.saveItemSettings( _this, $button );
						_this.closeItemSettings();
						return false;
					} );

					jQuery( '.trx-addons-dialog-header-button-close').on( 'click', this.closeItemSettings );
	
					jQuery( document).on( 'click', '.trx-addons-dialog', function (e) {
						if ( jQuery( e.target).closest( ".trx-addons-dialog-modal" ).length < 1 ) {
							window.TrxNavMenuSettings.closeItemSettings();
						}
					} );

					jQuery( '#save_menu_footer').on( 'click', function() {
						trx_addons_options_changed( false );
					} );
				},

				openItemSettings: function( _this, e ) {
					var $item = jQuery(  e.target ).closest('.menu-item');
					_this.currentItemId = _this.getItemId( $item );
					_this.currentItemDepth = _this.getItemDepth( $item );
	
					_this.updateFields( _this, $item );

					// Show popup
					jQuery( '.trx-addons-nav-menu-item-settings').show();
				},
	
				updateFields: function( _this, $item ) {
					var $form = jQuery( '.trx-addons-nav-menu-item-settings' ),
						settings = $item.find( '#edit-menu-item-settings-' + _this.currentItemId ).val(),
						$fld, val;
	
					if ( ! $form.length ) return;

					settings = settings ? JSON.parse( settings ) : {};
					settings = trx_addons_object_merge( _this.defaultItemSettings( _this, $form ), typeof settings == 'object' ? settings : {} );
					settings.submenu_depth = _this.currentItemDepth;

					for ( var param_name in settings ) {
						$fld = $form.find( '[name="trx_addons_options_field_' + param_name + '"]' );
						val = settings[param_name];
						if ( $fld.attr('type') == 'radio' || $fld.attr('type') == 'checkbox' ) {
							$fld.removeAttr( 'checked' );
							$fld.each( function() {
								var item = jQuery(this);
								if ( item.val() == val ) {
									item.get(0).checked = true;
									item.attr( 'checked', 'checked' ).trigger('change');
								}
							} );
						} else if ( $fld.hasClass('trx_addons_color_selector') ) {
							$fld.val( val ).wpColorPicker( 'color', val ).trigger('change');
						} else if ( $fld.next().hasClass('trx_addons_icon_selector') ) {
							if ( val == '' || val == 'none' ) {
								$fld.next().attr( 'class', 'trx_addons_icon_selector' );
							} else {
								$fld.next().addClass( val ).css('background-image', 'none');
							}
							$fld.val( val ).trigger('change');
						} else {
							$fld.val( val ).trigger('change');
						}
					}
					trx_addons_options_changed( false );
				},

				defaultItemSettings: function( _this, $form ) {
					var settings = {};
					$form.find( '[name^="trx_addons_options_field_"]').each( function() {
						var $fld = jQuery( this ),
							name = $fld.attr( 'name' ).replace( 'trx_addons_options_field_', '' ),
							std = $fld.data('std');
						settings[name] = std;
					} );
					return settings;
				},

				saveItemSettings: function( _this, $btn ) {
					var $form = jQuery( '.trx-addons-nav-menu-item-settings' ),
						$settings_field = jQuery( '#edit-menu-item-settings-' + _this.currentItemId ),
						settings = {};
					
					$form.find( '[name^="trx_addons_options_field_"]').each( function() {
						var $fld = jQuery( this ),
							name = $fld.attr( 'name' ).replace( 'trx_addons_options_field_', '' ),
							val = $fld.attr('type') == 'checkbox'
									? ( $fld.prop('checked') ? $fld.val() : '' )
									: ( $fld.attr('type') == 'radio'
										? $form.find( '[name="trx_addons_options_field_' + name + '"]:checked').val()
										: $fld.val()
										);
						settings[name] = val;
					} );
					$settings_field.val( JSON.stringify( settings ) ).trigger('change');
					// Update menu item classes
					$settings_field.closest('.menu-item')
						.toggleClass('menu-item-has-submenu-layout', settings.layout_submenu > 0 )
						.toggleClass('menu-item-has-submenu-settings', ( settings.icon && settings.icon != 'none' ) || settings.badge_text != '' );
				},
	
				closeItemSettings: function() {
					jQuery( '.trx-addons-nav-menu-item-settings').hide();
				},
	
				getItemId: function( $item ) {
					return jQuery( $item).attr('id').replace('menu-item-', '');
				},
	
				getItemDepth: function( $item ) {
					var depth = jQuery( $item).attr('class').match(/menu-item-depth-\d/);
					if ( depth.length ) {
						return depth[0].replace( 'menu-item-depth-', '' );
					} else {
						return 0;
					}
				}
			}
	
			window.TrxNavMenuSettings.init();

			// Check max input vars
			var checkMaxInputVars = function() {
				var form = jQuery('#menu-to-edit');
				if ( form.length > 0 ) {
					// var fields = form.find('input[name^="menu-item"], select[name^="menu-item"], textarea[name^="menu-item"]');
					var fields = form.find('input,select,textarea');
					var count = fields.length;
					var max_input_vars = TRX_ADDONS_STORAGE['max_input_vars'];
					if ( count > max_input_vars ) {
						trx_addons_msgbox_attention( TRX_ADDONS_STORAGE['msg_max_input_vars'].replace('{{count}}', count).replace('{{max_input_vars}}', max_input_vars), TRX_ADDONS_STORAGE['msg_caption_attention'] );
					}
				}
			};
			checkMaxInputVars();
			jQuery( '.submit-add-to-menu').on( 'click', checkMaxInputVars );
		}

		// Prepare media selector params
		//---------------------------------------------------
		if ( typeof( TRX_ADDONS_STORAGE['media_frame'] ) == 'undefined' ) {
			TRX_ADDONS_STORAGE['media_frame'] = {};
			TRX_ADDONS_STORAGE['media_link'] = {};
		}

		// Add extra parameters to all links on page
		//---------------------------------------------------
		if ( TRX_ADDONS_STORAGE['add_to_links_url'] ) {
			trx_addons_add_extra_args_to_links( TRX_ADDONS_STORAGE['add_to_links_url'] );

			// Add extra parameters to all links on events
			if ( TRX_ADDONS_STORAGE['add_to_links_url_events'] ) {
				for ( var i = 0; i < TRX_ADDONS_STORAGE['add_to_links_url_events'].length; i++ ) {
					jQuery( TRX_ADDONS_STORAGE['add_to_links_url_events'][i]['selector'] )
						.data( 'trx-addons-add-to-links-url-event-delay', TRX_ADDONS_STORAGE['add_to_links_url_events'][i]['delay'] ? TRX_ADDONS_STORAGE['add_to_links_url_events'][i]['delay'] : 0 )
						.on( TRX_ADDONS_STORAGE['add_to_links_url_events'][i]['event'], function() {
							var delay = jQuery(this).data( 'trx-addons-add-to-links-url-event-delay' ) || 0;
							if ( delay > 0 ) {
								setTimeout( function() {
									trx_addons_add_extra_args_to_links( TRX_ADDONS_STORAGE['add_to_links_url'] );
								}, delay );
							} else {
								trx_addons_add_extra_args_to_links( TRX_ADDONS_STORAGE['add_to_links_url'] );
							}
						} );
				}
			}
		}

		// First run init fields
		//----------------------------------------------------
		trx_addons_admin_init_fields();

		$document.on( 'action.init_hidden_elements', trx_addons_admin_init_fields );

		// Fix for WordPress 5.8+ Widgets Block Editor
		//$document.on( 'widget-added', function ( $control ) {
		//	$document.trigger( 'action.init_hidden_elements', [ jQuery( $control ) ] );
		//} );

		// Init hidden elements after the page is loaded
		setTimeout( function() {
			$document.trigger( 'action.init_hidden_elements', [ $body ] );
		}, 0 );
	} );

	

	// Init fields at first run and after clone group
	// -------------------------------------------------------------------------------------
	function trx_addons_admin_init_fields(e, container) {
		
//		if (container === undefined) container = $body;
		container = $body;
	
		// Init jQuery Tabs
		if ( jQuery.ui && jQuery.ui.tabs ) {
			var $tabs = container.find( '.trx_addons_tabs_vertical:not(.inited)' );
			if ( $tabs.length > 0 ) {
				$tabs
					.on( 'click', '.trx_addons_tabs_title:not(.trx_addons_tabs_title_sub)', function(e) {
						var sup = jQuery(this),
							stop = false,
							first = true;
						sup.siblings( '.trx_addons_tabs_title_super' ).removeClass( 'ui-tabs-active ui-state-active ui-state-focus' );
						sup.siblings( '.trx_addons_tabs_title_sub' ).stop().slideUp();
						sup.nextAll().each( function() {
							var sub = jQuery(this);
							if ( ! stop ) {
								if ( sub.hasClass( 'trx_addons_tabs_title_sub' ) ) {
									sub.stop().slideDown();
									if ( first ) {
										first = false;
										sup.removeClass('ui-state-focus');							
										sub.addClass('ui-tabs-active ui-state-active ui-state-focus');							
									}
								} else {
									stop = true;
								}
							}
						});
						// Animated scroll to the top of the tabs block
						trx_addons_document_animate_to( jQuery( '.trx_addons_options' ).eq(0) );
					} )
					.on( 'click', '.trx_addons_tabs_title_sub', function(e) {
						var sub = jQuery(this),
							prev = sub.prev(),
							stop = false;
						sub.siblings( '.trx_addons_tabs_title_sub' ).removeClass( 'ui-tabs-active ui-state-active ui-state-focus' );
						while ( prev.length == 1 ) {
							if ( prev.hasClass( 'trx_addons_tabs_title_super' ) ) {
								prev.addClass('ui-tabs-active ui-state-active ui-state-focus');
								break;
							}
							prev = prev.prev();
						}
						// Animated scroll to the top of the tabs block
						trx_addons_document_animate_to( jQuery( '.trx_addons_options' ).eq(0) );
					} );
			}
			// Init all tabs
			container.find('.trx_addons_tabs:not(.inited)')
				.addClass('inited')
				.tabs( {
					create: function( event, ui ) {
						if ( ui.panel.length > 0 ) {
							$document.trigger( 'admin_action.create_tab', [ui.panel] );
							$document.trigger( 'admin_action.init_hidden_elements', [ui.panel] );
						}
					},
					activate: function( event, ui ) {
						if ( ui.newPanel.length > 0 ) {
							$document.trigger( 'admin_action.activate_tab', [ui.newPanel] );
							$document.trigger( 'admin_action.init_hidden_elements', [ui.newPanel] );
						}
					}
				} );
			// Check hash and open tab
			var hash  = window.location.hash;
			if (hash && jQuery('a[href="'+hash+'"]').length > 0) {
				setTimeout(function() {
					jQuery('a[href="'+hash+'"]').trigger('click');
				}, 10);
			}
			// Open first panel
			if ( $tabs.length > 0 ) {
				$tabs.each( function() {
					var $first_title = jQuery( this ).find( '.trx_addons_tabs_title' ).eq(0);
					if ( $first_title.length > 0 && $first_title.hasClass( 'trx_addons_tabs_title_super' ) ) {
						$first_title.trigger( 'click' );
					}
				} );
			}
		}
		
		// Init jQuery Accordion
		if ( jQuery.ui && jQuery.ui.accordion ) {
			container.find('.trx_addons_accordion:not(.inited)').each( function() {
				var $self = jQuery(this);
				$self
					.addClass('inited')
					.accordion( {
						'header': '.trx_addons_accordion_title',
						'heightStyle': 'content',
						'collapsible': $self.hasClass( 'trx_addons_toggle' ),
						create: function( event, ui ) {
							if ( ui.panel.length > 0 ) {
								$document.trigger( 'admin_action.create_accordion', [ui.panel] );
								$document.trigger( 'admin_action.init_hidden_elements', [ui.panel] );
							}
						},
						activate: function( event, ui ) {
							if ( ui.newPanel.length > 0 ) {
								$document.trigger( 'admin_action.activate_accordion', [ui.newPanel] );
								$document.trigger( 'admin_action.init_hidden_elements', [ui.newPanel] );
							}
						}
					} );
			} );
		}

		// Standard WP Color Picker
		if ( container.find('.trx_addons_color_selector:not(.inited)').length > 0 ) {
			container.find('.trx_addons_color_selector:not(.inited)')
				.addClass('inited')
				.wpColorPicker({
					// you can declare a default color here,
					// or in the data-default-color attribute on the input
					//defaultColor: false,
			
					// a callback to fire whenever the color changes to a valid color
					change: function(e, ui){
						jQuery(e.target).val(ui.color).trigger('change');
					},
			
					// a callback to fire when the input is emptied or an invalid color
					clear: function(e) {
						jQuery(e.target).prev().trigger('change');
					}
			
					// hide the color picker controls on load
					//hide: true,
			
					// show a group of common colors beneath the square
					// or, supply an array of colors to customize further
					//palettes: true
				});
		}
		
		// Icon selector
		// Attention! Init container, because icon_selector appear in a cloneable blocks
		if ( ! container.hasClass( 'trx_addons_icon_selector_inited' ) ) {
			container
				.addClass('trx_addons_icon_selector_inited')
				.on( 'keydown', '.trx_addons_icon_selector', function( e ) {
					// If 'Enter' or 'Space' is pressed - switch state of the icons list
					if ( [ 13, 32 ].indexOf( e.which ) >= 0 ) {
						jQuery( this ).trigger( 'click' );
						e.preventDefault();
						return false;
					}
					return true;
				})
				.on('click', '.trx_addons_icon_selector', function(e) {
					var selector = jQuery(this);
					var list = selector.next('.trx_addons_list_icons');
					if (list.length > 0) {
						if (list.css('display') == 'block') {
							list.fadeOut(100);	//.slideUp();
							selector.get(0).focus();
						} else {
							var css_obj = {};
							if (selector.parents('#trx_addons_meta_box').length > 0) {
								css_obj = { 
									'position': 'relative',
									'left': 'auto',
									'top': 'auto'
								};
							} else {
								var pos = selector.position();
								css_obj = {
									'position': 'absolute',
									'left': pos.left,
									'top': pos.top+selector.height()+10
								};
							}
							list.find('.trx_addons_list_icons_search').val('');
							list.find('span').removeClass('trx_addons_list_hidden');
							list.css(css_obj)
								//.slideDown( function() {
								.fadeIn( 100, function() {
																	list.find('.trx_addons_list_icons_search').get(0).focus();
																});
						}
					}
					e.preventDefault();
					return false;
				})
				.on('keyup', '.trx_addons_list_icons_search', function(e) {
					var list = jQuery(this).parent(),
						val = jQuery(this).val();
					list.find('span').removeClass('trx_addons_list_hidden');
					if (val !== '') list.find('span:not([data-icon*="'+val+'"])').addClass('trx_addons_list_hidden');
				})
				.on( 'keydown', '.trx_addons_list_icons span', function( e ) {
					var handled = false,
						icons = jQuery( this ).siblings( 'span' );
					// If 'Enter' or 'Space' is pressed - switch state of the icons list
					if ( [ 13, 32 ].indexOf( e.which ) >= 0 ) {
						jQuery( this ).trigger( 'click' );
						handled = true;
					} else if ( 37 == e.which ) {
						icons.get( Math.max( 0, jQuery( this ).index() - 1 ) ).focus();
						handled = true;
					} else if ( 38 == e.which ) {
						icons.get( Math.max( 0, jQuery( this ).index() - 8 ) ).focus();
						handled = true;
					} else if ( 39 == e.which ) {
						icons.get( Math.min( icons.length - 1, jQuery( this ).index() ) ).focus();
						handled = true;
					} else if ( 40 == e.which ) {
						icons.get( Math.min( icons.length - 1, jQuery( this ).index() + 7 ) ).focus();
						handled = true;
					} else if ( [ 27 ].indexOf( e.which ) >= 0 ) {
						jQuery( this ).parents('.trx_addons_list_icons').prev('.trx_addons_icon_selector').trigger('click');
						handled = true;
					}
					if ( handled ) {
						e.preventDefault();
						return false;
					}
					return true;
				})
				.on('click', '.trx_addons_list_icons span', function(e) {
					var list = jQuery(this).parents('.trx_addons_list_icons');
					list.find('.trx_addons_active').removeClass('trx_addons_active');
					var selector = list.prev('.trx_addons_icon_selector');
					var input = selector.length==1 ? selector.prev('input') : list.prev('input');
					var icon = jQuery(this).addClass('trx_addons_active').data('icon');
					input.val(icon).trigger('change');
					if (selector.length > 0) {
						list.fadeOut();
						var bg = jQuery( this ).css( 'background-image' );
						selector
							.attr('class', trx_addons_chg_icon_class(selector.attr('class'), bg && bg != 'none' ? 'image-selected' : icon))
							.css('background-image', bg && bg != 'none' ? bg : 'none')
							.get(0).focus();
					}
					e.preventDefault();
					return false;
				});
		}
		
		// Media selector
		container.find( trx_addons_apply_filters( 'trx_addons_filter_mediamanager_selector',
													'#customize-theme-controls:not(.inited)'
													+',.widget-liquid-right:not(.inited)'
													+',.widgets-holder-wrap:not(.inited)'
													+',.widget_field_type_image:not(.inited)'
													+',.form-field:not(.inited)'
													+',.trx_addons_options_item_field:not(.inited)'
													+',.postbox-container:not(.inited)'
													+',#elementor-panel:not(.inited_media_selector)'
													+',.edit-widgets-block-editor:not(.inited)'
			) )
			.addClass('inited inited_media_selector')
			.on('click', '.trx_addons_media_selector', function(e) {
dcl('click');
				trx_addons_show_media_manager(this);
				e.preventDefault();
				return false;
			})
			.on( 'keydown', '.trx_addons_media_selector_preview > .trx_addons_media_selector_preview_image', function(e) {
				// If 'Enter' or 'Space' is pressed - remove image
				if ( [ 13, 32 ].indexOf( e.which ) >= 0 ) {
					jQuery( this ).trigger('click');
					e.preventDefault();
					return false;
				}
				return true;
			} )
			.on( 'click', '.trx_addons_media_selector_preview > .trx_addons_media_selector_preview_image', function(e) {
				var image = jQuery(this),
					preview = image.parent(),
					button = preview.siblings('.trx_addons_media_selector'),
					field = jQuery('#'+button.data('linked-field'));
				if (field.length === 0) return true;
				if (button.data('multiple') == 1) {
					var val = field.val().split('|');
					val.splice(image.index(), 1);
					field.val(val.join('|'));
					image.remove();
				} else {
					field.val('');
					image.remove();
				}
				preview.toggleClass('trx_addons_media_selector_preview_with_image', preview.find('> .trx_addons_media_selector_preview_image').length > 0);
				e.preventDefault();
				return false;
			} );

	}
	
	
	// Show WP Media manager to select image
	// -------------------------------------------------------------------------------------
	function trx_addons_show_media_manager(el) {
	
		var media_id = jQuery(el).attr('id');
		TRX_ADDONS_STORAGE['media_link'][media_id] = jQuery(el);
		// If the media frame already exists, reopen it.
		if ( TRX_ADDONS_STORAGE['media_frame'][media_id] ) {
			TRX_ADDONS_STORAGE['media_frame'][media_id].open();
			return false;
		}
	
		// Create the media frame
		var type = TRX_ADDONS_STORAGE['media_link'][media_id].data('type') 
						? TRX_ADDONS_STORAGE['media_link'][media_id].data('type') 
						: 'image';
		var args = {
			// Set the title of the modal.
			title: TRX_ADDONS_STORAGE['media_link'][media_id].data('choose'),
			// Multiple choise
			multiple: TRX_ADDONS_STORAGE['media_link'][media_id].data('multiple')==1 
						? 'add' 
						: false,
			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: TRX_ADDONS_STORAGE['media_link'][media_id].data('update'),
				// Tell the button not to close the modal, since we're
				// going to refresh the page when the image is selected.
				close: true
			}
		};
		// Allow sizes and filters for the images
		if (type == 'image') {
			args['frame'] = 'post';
		}
		// Tell the modal to show only selected post types
		if (type == 'image' || type == 'audio' || type == 'video') {
			args['library'] = {
				type: type
			};
		}
dcl(args);
		TRX_ADDONS_STORAGE['media_frame'][media_id] = wp.media(args);
	
		// When an image is selected, run a callback.
		TRX_ADDONS_STORAGE['media_frame'][media_id].on( 'insert select', function(selection) {
			// Grab the selected attachment.
			var field = jQuery("#"+TRX_ADDONS_STORAGE['media_link'][media_id].data('linked-field')).eq(0);
			var attachment = null, attachment_url = '';
			if (TRX_ADDONS_STORAGE['media_link'][media_id].data('multiple')==1) {
				TRX_ADDONS_STORAGE['media_frame'][media_id].state().get('selection').map( function( att ) {
					attachment_url += (attachment_url ? "|" : "") + att.toJSON().url;
				});
				var val = field.val();
				attachment_url = val + (val ? "|" : '') + attachment_url;
			} else {
				attachment = TRX_ADDONS_STORAGE['media_frame'][media_id].state().get('selection').first().toJSON();
				attachment_url = attachment.url;
				// Get attachment size (for compatibility with old versions)
				if ( false && type == 'image' ) {
					var sizes_selector = jQuery('.media-modal-content .attachment-display-settings select.size');
					if (sizes_selector.length > 0) {
						var size = trx_addons_get_listbox_selected_value(sizes_selector.get(0));
						if (size !== '') attachment_url = attachment.sizes[size].url;
					}
				}
			}
			// Display images in the preview area
			var preview = field.siblings('.trx_addons_media_selector_preview');
			if (preview.length === 0) {
				jQuery('<span class="trx_addons_media_selector_preview"></span>').insertAfter(field);
				preview = field.siblings('.trx_addons_media_selector_preview');
			}
			if (preview.length !== 0) preview.find('.trx_addons_media_selector_preview_image').remove();
			var images = attachment_url.split("|");
			for (var i=0; i < images.length; i++) {
				if (preview.length !== 0) {
					var ext = trx_addons_get_file_ext(images[i]);
					preview.append('<span class="trx_addons_media_selector_preview_image" tabindex="0">'
									+ (ext=='gif' || ext=='jpg' || ext=='jpeg' || ext=='png' 
											? '<img src="'+images[i]+'">'
											: '<a href="'+images[i]+'">'+trx_addons_get_file_name(images[i])+'</a>'
										)
									+ '</span>');
				}
			}
			preview.toggleClass('trx_addons_media_selector_preview_with_image', preview.find('> .trx_addons_media_selector_preview_image').length > 0);
			// Update field
			field.val(attachment_url).trigger('change');
		});
	
		// Finally, open the modal.
		TRX_ADDONS_STORAGE['media_frame'][media_id].open();
		return false;
	}


	// Rotate banners (global)
	window.trx_addons_banners_rotator = function( banners_wrap ) {
		var banners = jQuery( banners_wrap + ' .trx_banners_item' );
		if ( banners.length === 0 ) {
			return;
		}
		var active = jQuery( banners_wrap + ' .trx_banners_item_active' ).index(),
			next = ( active + 1 ) % banners.length,
			duration = 20000;
		if ( active >= 0 ) {
			banners.eq( active ).fadeOut( function() {
				jQuery( this ).removeClass( 'trx_banners_item_active' );
				banners.eq( next ).fadeIn().addClass( 'trx_banners_item_active' );
			});
		} else {
			banners.eq( next ).fadeIn().addClass( 'trx_banners_item_active' );
		}
		if ( ! isNaN( banners.eq( next ).data( 'duration' ) ) ) {
			duration = Math.max( 1000, Math.min( 60000, Number( banners.eq( next ).data( 'duration' ) ) ) );
		}
		setTimeout( function() { trx_addons_banners_rotator( banners_wrap ); }, duration );
	};

})();