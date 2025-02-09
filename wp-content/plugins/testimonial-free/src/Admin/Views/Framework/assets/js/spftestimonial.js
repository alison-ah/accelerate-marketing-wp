; (function ($, window, document, undefined) {
	'use strict';

	//
	// Constants
	//
	var SPFTESTIMONIAL = SPFTESTIMONIAL || {};

	SPFTESTIMONIAL.funcs = {};

	SPFTESTIMONIAL.vars = {
		onloaded: false,
		$body: $('body'),
		$window: $(window),
		$document: $(document),
		$form_warning: null,
		is_confirm: false,
		form_modified: false,
		code_themes: [],
		is_rtl: $('body').hasClass('rtl'),
	};

	//
	// Helper Functions
	//
	SPFTESTIMONIAL.helper = {

		//
		// Generate UID
		//
		uid: function (prefix) {
			return (prefix || '') + Math.random().toString(36).substr(2, 9);
		},

		// Quote regular expression characters
		//
		preg_quote: function (str) {
			return (str + '').replace(/(\[|\])/g, "\\$1");
		},

		//
		// Reneme input names
		//
		name_nested_replace: function ($selector, field_id) {

			var checks = [];
			var regex = new RegExp(SPFTESTIMONIAL.helper.preg_quote(field_id + '[\\d+]'), 'g');

			$selector.find(':radio').each(function () {
				if (this.checked || this.orginal_checked) {
					this.orginal_checked = true;
				}
			});

			$selector.each(function (index) {
				$(this).find(':input').each(function () {
					this.name = this.name.replace(regex, field_id + '[' + index + ']');
					if (this.orginal_checked) {
						this.checked = true;
					}
				});
			});

		},

		//
		// Debounce
		//
		debounce: function (callback, threshold, immediate) {
			var timeout;
			return function () {
				var context = this, args = arguments;
				var later = function () {
					timeout = null;
					if (!immediate) {
						callback.apply(context, args);
					}
				};
				var callNow = (immediate && !timeout);
				clearTimeout(timeout);
				timeout = setTimeout(later, threshold);
				if (callNow) {
					callback.apply(context, args);
				}
			};
		},
		//
		// Get a cookie
		//
		get_cookie: function (name) {

			var e, b, cookie = document.cookie, p = name + '=';

			if (!cookie) {
				return;
			}

			b = cookie.indexOf('; ' + p);

			if (b === -1) {
				b = cookie.indexOf(p);

				if (b !== 0) {
					return null;
				}
			} else {
				b += 2;
			}

			e = cookie.indexOf(';', b);

			if (e === -1) {
				e = cookie.length;
			}

			return decodeURIComponent(cookie.substring(b + p.length, e));

		},

		//
		// Set a cookie
		//
		set_cookie: function (name, value, expires, path, domain, secure) {

			var d = new Date();

			if (typeof (expires) === 'object' && expires.toGMTString) {
				expires = expires.toGMTString();
			} else if (parseInt(expires, 10)) {
				d.setTime(d.getTime() + (parseInt(expires, 10) * 1000));
				expires = d.toGMTString();
			} else {
				expires = '';
			}

			document.cookie = name + '=' + encodeURIComponent(value) +
				(expires ? '; expires=' + expires : '') +
				(path ? '; path=' + path : '') +
				(domain ? '; domain=' + domain : '') +
				(secure ? '; secure' : '');

		},
		//
		// Remove a cookie
		//
		remove_cookie: function (name, path, domain, secure) {
			SPFTESTIMONIAL.helper.set_cookie(name, '', -1000, path, domain, secure);
		},
	};

	//
	// Custom clone for textarea and select clone() bug
	//
	$.fn.spftestimonial_clone = function () {

		var base = $.fn.clone.apply(this, arguments),
			clone = this.find('select').add(this.filter('select')),
			cloned = base.find('select').add(base.filter('select'));

		for (var i = 0; i < clone.length; ++i) {
			for (var j = 0; j < clone[i].options.length; ++j) {

				if (clone[i].options[j].selected === true) {
					cloned[i].options[j].selected = true;
				}

			}
		}

		this.find(':radio').each(function () {
			this.orginal_checked = this.checked;
		});

		return base;

	};

	//
	// Expand All Options
	//
	$.fn.spftestimonial_expand_all = function () {
		return this.each(function () {
			$(this).on('click', function (e) {

				e.preventDefault();
				$('.spftestimonial-wrapper').toggleClass('spftestimonial-show-all');
				$('.spftestimonial-section').spftestimonial_reload_script();
				$(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

			});
		});
	};

	//
	// Options Navigation
	//
	$.fn.spftestimonial_nav_options = function () {
		return this.each(function () {

			var $nav = $(this),
				$window = $(window),
				$wpwrap = $('#wpwrap'),
				$links = $nav.find('a'),
				$last;

			$window.on('hashchange spftestimonial.hashchange', function () {

				var hash = window.location.hash.replace('#tab=', '');
				var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
				var $link = $('[data-tab-id="' + slug + '"]');

				if ($link.length) {

					$link.closest('.spftestimonial-tab-item').addClass('spftestimonial-tab-expanded').siblings().removeClass('spftestimonial-tab-expanded');

					if ($link.next().is('ul')) {

						$link = $link.next().find('li').first().find('a');
						slug = $link.data('tab-id');

					}

					$links.removeClass('spftestimonial-active');
					$link.addClass('spftestimonial-active');

					if ($last) {
						$last.addClass('hidden');
					}

					var $section = $('[data-section-id="' + slug + '"]');

					$section.removeClass('hidden');
					$section.spftestimonial_reload_script();

					$('.spftestimonial-section-id').val($section.index() + 1);

					$last = $section;

					if ($wpwrap.hasClass('wp-responsive-open')) {
						$('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
						$wpwrap.removeClass('wp-responsive-open');
					}

				}

			}).trigger('spftestimonial.hashchange');

		});
	};

	//
	// Metabox Tabs
	//
	$.fn.spftestimonial_nav_metabox = function () {
		return this.each(function () {

			var $nav = $(this),
				$links = $nav.find('a'),
				$sections = $nav.parent().find('.spftestimonial-section'),
				unique_id = $nav.data('unique'),
				post_id = $('#post_ID').val() || 'global',
				$last;

			$links.each(function (index) {

				$(this).on('click', function (e) {

					e.preventDefault();

					var $link = $(this);
					var section_id = $link.data('section');
					$links.removeClass('spftestimonial-active');
					$link.addClass('spftestimonial-active');
					if ($last !== undefined) {
						$last.addClass('hidden');
					}

					var $section = $sections.eq(index);

					$section.removeClass('hidden');
					$section.spftestimonial_reload_script();
					SPFTESTIMONIAL.helper.set_cookie('spftestimonial-last-metabox-tab-' + post_id + '-' + unique_id, section_id);

					$last = $section;

				});

			});

			var get_cookie = SPFTESTIMONIAL.helper.get_cookie('spftestimonial-last-metabox-tab-' + post_id + '-' + unique_id);
			if (get_cookie) {
				$nav.find('a[data-section="' + get_cookie + '"]').trigger('click');
			} else {
				$links.first('a').trigger('click');
			}

		});
	};



	//
	// Search
	//
	$.fn.spftestimonial_search = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input');

			$input.on('change keyup', function () {

				var value = $(this).val(),
					$wrapper = $('.spftestimonial-wrapper'),
					$section = $wrapper.find('.spftestimonial-section'),
					$fields = $section.find('> .spftestimonial-field:not(.spftestimonial-depend-on)'),
					$titles = $fields.find('> .spftestimonial-title, .spftestimonial-search-tags');

				if (value.length > 3) {

					$fields.addClass('spftestimonial-metabox-hide');
					$wrapper.addClass('spftestimonial-search-all');

					$titles.each(function () {

						var $title = $(this);

						if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

							var $field = $title.closest('.spftestimonial-field');

							$field.removeClass('spftestimonial-metabox-hide');
							$field.parent().spftestimonial_reload_script();

						}

					});

				} else {

					$fields.removeClass('spftestimonial-metabox-hide');
					$wrapper.removeClass('spftestimonial-search-all');

				}

			});

		});
	};

	//
	// Sticky Header
	//
	$.fn.spftestimonial_sticky = function () {
		return this.each(function () {

			var $this = $(this),
				$window = $(window),
				$inner = $this.find('.spftestimonial-header-inner'),
				padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
				offset = 32,
				scrollTop = 0,
				lastTop = 0,
				ticking = false,
				stickyUpdate = function () {

					var offsetTop = $this.offset().top,
						stickyTop = Math.max(offset, offsetTop - scrollTop),
						winWidth = $window.innerWidth();

					if (stickyTop <= offset && winWidth > 782) {
						$inner.css({ width: $this.outerWidth() - padding });
						$this.css({ height: $this.outerHeight() }).addClass('spftestimonial-sticky');
					} else {
						$inner.removeAttr('style');
						$this.removeAttr('style').removeClass('spftestimonial-sticky');
					}

				},
				requestTick = function () {

					if (!ticking) {
						requestAnimationFrame(function () {
							stickyUpdate();
							ticking = false;
						});
					}

					ticking = true;

				},
				onSticky = function () {

					scrollTop = $window.scrollTop();
					requestTick();

				};

			$window.on('scroll resize', onSticky);

			onSticky();

		});
	};

	//
	// Dependency System
	//
	$.fn.spftestimonial_dependency = function () {
		return this.each(function () {

			var $this = $(this),
				$fields = $this.children('[data-controller]');

			if ($fields.length) {

				var normal_ruleset = $.spftestimonial_deps.createRuleset(),
					global_ruleset = $.spftestimonial_deps.createRuleset(),
					normal_depends = [],
					global_depends = [];

				$fields.each(function () {

					var $field = $(this),
						controllers = $field.data('controller').split('|'),
						conditions = $field.data('condition').split('|'),
						values = $field.data('value').toString().split('|'),
						is_global = $field.data('depend-global') ? true : false,
						ruleset = (is_global) ? global_ruleset : normal_ruleset;

					$.each(controllers, function (index, depend_id) {

						var value = values[index] || '',
							condition = conditions[index] || conditions[0];

						ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

						ruleset.include($field);

						if (is_global) {
							global_depends.push(depend_id);
						} else {
							normal_depends.push(depend_id);
						}

					});

				});

				if (normal_depends.length) {
					$.spftestimonial_deps.enable($this, normal_ruleset, normal_depends);
				}

				if (global_depends.length) {
					$.spftestimonial_deps.enable(SPFTESTIMONIAL.vars.$body, global_ruleset, global_depends);
				}

			}

		});
	};

	//
	// Field: accordion
	//
	$.fn.spftestimonial_field_accordion = function () {
		return this.each(function () {

			var $titles = $(this).find('.spftestimonial-accordion-title');

			$titles.on('click', function (e) {

				var $title = $(this),
					$icon = $title.find('.spftestimonial-accordion-icon'),
					$content = $title.next();

				if ($icon.hasClass('fa-angle-right')) {
					$icon.removeClass('fa-angle-right').addClass('fa-angle-down');
				} else {
					$icon.removeClass('fa-angle-down').addClass('fa-angle-right');
				}

				if (!$content.data('opened')) {

					$content.spftestimonial_reload_script();
					$content.data('opened', true);

				}

				$content.toggleClass('spftestimonial-accordion-open');

			});
			if ($(this).hasClass('opened_accordion')) {
				$titles.trigger('click');
			}

		});
	};

	//
	// Field: code_editor
	//
	$.fn.spftestimonial_field_code_editor = function () {
		return this.each(function () {

			if (typeof CodeMirror !== 'function') { return; }

			var $this = $(this),
				$textarea = $this.find('textarea'),
				$inited = $this.find('.CodeMirror'),
				data_editor = $textarea.data('editor');

			if ($inited.length) {
				$inited.remove();
			}

			var interval = setInterval(function () {
				if ($this.is(':visible')) {

					var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

					// load code-mirror theme css.
					if (data_editor.theme !== 'default' && SPFTESTIMONIAL.vars.code_themes.indexOf(data_editor.theme) === -1) {

						var $cssLink = $('<link>');

						$('#spftestimonial-codemirror-css').after($cssLink);

						$cssLink.attr({
							rel: 'stylesheet',
							id: 'spftestimonial-codemirror-' + data_editor.theme + '-css',
							href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
							type: 'text/css',
							media: 'all'
						});

						SPFTESTIMONIAL.vars.code_themes.push(data_editor.theme);

					}

					CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
					CodeMirror.autoLoadMode(code_editor, data_editor.mode);

					code_editor.on('change', function (editor, event) {
						$textarea.val(code_editor.getValue()).trigger('change');
					});

					clearInterval(interval);

				}
			});

		});
	};

	//
	// Field: fieldset
	//
	$.fn.spftestimonial_field_fieldset = function () {
		return this.each(function () {
			$(this).find('.spftestimonial-fieldset-content').spftestimonial_reload_script();
		});
	};

	//
	// Field: repeater
	//
	$.fn.spftestimonial_field_repeater = function () {
		return this.each(function () {

			var $this = $(this),
				$fieldset = $this.children('.spftestimonial-fieldset'),
				$repeater = $fieldset.length ? $fieldset : $this,
				$wrapper = $repeater.children('.spftestimonial-repeater-wrapper'),
				$hidden = $repeater.children('.spftestimonial-repeater-hidden'),
				$max = $repeater.children('.spftestimonial-repeater-max'),
				$min = $repeater.children('.spftestimonial-repeater-min'),
				field_id = $wrapper.data('field-id'),
				max = parseInt($wrapper.data('max')),
				min = parseInt($wrapper.data('min'));

			$wrapper.children('.spftestimonial-repeater-item').children('.spftestimonial-repeater-content').spftestimonial_reload_script();

			$wrapper.sortable({
				axis: 'y',
				handle: '.spftestimonial-repeater-sort',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				update: function (event, ui) {

					SPFTESTIMONIAL.helper.name_nested_replace($wrapper.children('.spftestimonial-repeater-item'), field_id);
					// $wrapper.spftestimonial_customizer_refresh();
					// ui.item.spftestimonial_reload_script_retry();

				}
			});

			$repeater.children('.spftestimonial-repeater-add').on('click', function (e) {

				e.preventDefault();

				var count = $wrapper.children('.spftestimonial-repeater-item').length;

				$min.hide();

				if (max && (count + 1) > max) {
					$max.show();
					return;
				}

				var $cloned_item = $hidden.spftestimonial_clone(true);

				$cloned_item.removeClass('spftestimonial-repeater-hidden');

				$cloned_item.find(':input[name!="_pseudo"]').each(function () {
					this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
				});

				$wrapper.append($cloned_item);
				$cloned_item.children('.spftestimonial-repeater-content').spftestimonial_reload_script();
				// $wrapper.spftestimonial_customizer_refresh();
				// $wrapper.spftestimonial_customizer_listen({ closest: true });

			});

			var event_clone = function (e) {

				e.preventDefault();

				var count = $wrapper.children('.spftestimonial-repeater-item').length;

				$min.hide();

				if (max && (count + 1) > max) {
					$max.show();
					return;
				}

				var $this = $(this),
					$parent = $this.parent().parent().parent(),
					$cloned_content = $parent.children('.spftestimonial-repeater-content').spftestimonial_clone(),
					$cloned_helper = $parent.children('.spftestimonial-repeater-helper').spftestimonial_clone(true),
					$cloned_item = $('<div class="spftestimonial-repeater-item" />');

				$cloned_item.append($cloned_content);
				$cloned_item.append($cloned_helper);

				$wrapper.children().eq($parent.index()).after($cloned_item);

				$cloned_item.children('.spftestimonial-repeater-content').spftestimonial_reload_script();

				SPFTESTIMONIAL.helper.name_nested_replace($wrapper.children('.spftestimonial-repeater-item'), field_id);

				// $wrapper.spftestimonial_customizer_refresh();
				// $wrapper.spftestimonial_customizer_listen({ closest: true });

			};

			$wrapper.children('.spftestimonial-repeater-item').children('.spftestimonial-repeater-helper').on('click', '.spftestimonial-repeater-clone', event_clone);
			$repeater.children('.spftestimonial-repeater-hidden').children('.spftestimonial-repeater-helper').on('click', '.spftestimonial-repeater-clone', event_clone);

			var event_remove = function (e) {

				e.preventDefault();

				var count = $wrapper.children('.spftestimonial-repeater-item').length;

				$max.hide();
				$min.hide();

				if (min && (count - 1) < min) {
					$min.show();
					return;
				}

				$(this).closest('.spftestimonial-repeater-item').remove();

				SPFTESTIMONIAL.helper.name_nested_replace($wrapper.children('.spftestimonial-repeater-item'), field_id);

				//  $wrapper.spftestimonial_customizer_refresh();

			};

			$wrapper.children('.spftestimonial-repeater-item').children('.spftestimonial-repeater-helper').on('click', '.spftestimonial-repeater-remove', event_remove);
			$repeater.children('.spftestimonial-repeater-hidden').children('.spftestimonial-repeater-helper').on('click', '.spftestimonial-repeater-remove', event_remove);

		});
	};

	//
	// Field: sortable
	//
	$.fn.spftestimonial_field_sortable = function () {
		return this.each(function () {

			var $sortable = $(this).find('.spftestimonial-sortable');

			$sortable.sortable({
				axis: 'y',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				update: function (event, ui) {
					// $sortable.spftestimonial_customizer_refresh();
				}
			});

			$sortable.find('.spftestimonial-sortable-content').spftestimonial_reload_script();
			$('.form_fields').find('.spftestimonial-sortable').sortable("disable");

		});
	};

	//
	// Field: sorter
	//
	$.fn.spftestimonial_field_sorter = function () {
		return this.each(function () {

			var $this = $(this),
				$enabled = $this.find('.spftestimonial-enabled'),
				$has_disabled = $this.find('.spftestimonial-disabled'),
				$disabled = ($has_disabled.length) ? $has_disabled : false;

			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				update: function (event, ui) {

					var $el = ui.item.find('input');

					if (ui.item.parent().hasClass('spftestimonial-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
					}

					// $this.spftestimonial_customizer_refresh();

				}
			});

			if ($disabled) {

				$disabled.sortable({
					connectWith: $enabled,
					placeholder: 'ui-sortable-placeholder',
					update: function (event, ui) {
						// $this.spftestimonial_customizer_refresh();
					}
				});

			}

		});
	};

	//
	// Field: spinner
	//
	$.fn.spftestimonial_field_spinner = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$inited = $this.find('.ui-button'),
				data = $input.data();

			if ($inited.length) {
				$inited.remove();
			}

			$input.spinner({
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				create: function (event, ui) {
					if (data.unit) {
						$input.after('<span class="ui-button spftestimonial--unit">' + data.unit + '</span>');
					}
				},
				spin: function (event, ui) {
					$input.val(ui.value).trigger('change');
				}
			});

		});
	};

	//
	// Field: switcher
	//
	$.fn.spftestimonial_field_switcher = function () {
		return this.each(function () {

			var $switcher = $(this).find('.spftestimonial--switcher');

			$switcher.on('click', function () {

				var value = 0;
				var $input = $switcher.find('input');

				if ($switcher.hasClass('spftestimonial--active')) {
					$switcher.removeClass('spftestimonial--active');
				} else {
					value = 1;
					$switcher.addClass('spftestimonial--active');
				}

				$input.val(value).trigger('change');

			});

		});
	};

	//
	// Field: slider
	//
	$.fn.spftestimonial_field_slider = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.spftestimonial-slider-ui'),
				data = $input.data(),
				value = $input.val() || 0;
			if ($slider.hasClass('ui-slider')) {
				$slider.empty();
			}

			$slider.slider({
				range: 'min',
				value: value,
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				slide: function (e, o) {
					$input.val(o.value).trigger('change');
				}
			});

			$input.on('keyup', function () {
				$slider.slider('value', $input.val());
			});
		});
	};

	//
	// Field: tabbed
	//
	$.fn.spftestimonial_field_tabbed = function () {
		return this.each(function () {
			var $this = $(this),
				$links = $this.find('.spftestimonial-tabbed-nav a'),
				$sections = $this.find('.spftestimonial-tabbed-section');

			$links.on('click', function (e) {
				e.preventDefault();
				var $link = $(this),
					index = $link.index(),
					$section = $sections.eq(index);

				// Store the active tab index in a cookie
				SPFTESTIMONIAL.helper.set_cookie('activeTabIndex', index);

				$link.addClass('spftestimonial-tabbed-active').siblings().removeClass('spftestimonial-tabbed-active');
				$section.spftestimonial_reload_script();
				$section.removeClass('hidden').siblings().addClass('hidden');
			});
			// Check if there's a stored active tab index in the cookie
			var activeTabIndex = SPFTESTIMONIAL.helper.get_cookie('activeTabIndex');
			// Check if the cookie exists
			if (activeTabIndex !== null) {
				$links.eq(activeTabIndex).trigger('click');
			} else {
				$links.first().trigger('click');
			}
		});
	};

	//
	// Field: typography
	//
	$.fn.spftestimonial_field_typography = function () {
		return this.each(function () {

			var base = this;
			var $this = $(this);
			var loaded_fonts = [];
			var webfonts = spftestimonial_typography_json.webfonts;
			var googlestyles = spftestimonial_typography_json.googlestyles;
			var defaultstyles = spftestimonial_typography_json.defaultstyles;

			//
			//
			// Sanitize google font subset
			base.sanitize_subset = function (subset) {
				subset = subset.replace('-ext', ' Extended');
				subset = subset.charAt(0).toUpperCase() + subset.slice(1);
				return subset;
			};

			//
			//
			// Sanitize google font styles (weight and style)
			base.sanitize_style = function (style) {
				return googlestyles[style] ? googlestyles[style] : style;
			};

			//
			//
			// Load google font
			base.load_google_font = function (font_family, weight, style) {

				if (font_family && typeof WebFont === 'object') {

					weight = weight ? weight.replace('normal', '') : '';
					style = style ? style.replace('normal', '') : '';

					if (weight || style) {
						font_family = font_family + ':' + weight + style;
					}

					if (loaded_fonts.indexOf(font_family) === -1) {
						WebFont.load({ google: { families: [font_family] } });
					}

					loaded_fonts.push(font_family);

				}

			};

			//
			//
			// Append select options
			base.append_select_options = function ($select, options, condition, type, is_multi) {

				$select.find('option').not(':first').remove();

				var opts = '';

				$.each(options, function (key, value) {

					var selected;
					var name = value;

					// is_multi
					if (is_multi) {
						selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
					} else {
						selected = (condition && condition === value) ? ' selected' : '';
					}

					if (type === 'subset') {
						name = base.sanitize_subset(value);
					} else if (type === 'style') {
						name = base.sanitize_style(value);
					}

					opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

				});

				$select.append(opts).trigger('spftestimonial.change').trigger('chosen:updated');

			};

			base.init = function () {

				//
				//
				// Constants
				var selected_styles = [];
				var $typography = $this.find('.spftestimonial--typography');
				var $type = $this.find('.spftestimonial--type');
				var $styles = $this.find('.spftestimonial--block-font-style');
				var unit = $typography.data('unit');
				var line_height_unit = $typography.data('line-height-unit');
				var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

				//
				//
				// Chosen init
				if ($this.find('.spftestimonial--chosen').length) {

					var $chosen_selects = $this.find('select');

					$chosen_selects.each(function () {

						var $chosen_select = $(this),
							$chosen_inited = $chosen_select.parent().find('.chosen-container');

						if ($chosen_inited.length) {
							$chosen_inited.remove();
						}

						$chosen_select.chosen({
							allow_single_deselect: true,
							disable_search_threshold: 15,
							width: '100%'
						});

					});

				}

				//
				//
				// Font family select
				var $font_family_select = $this.find('.spftestimonial--font-family');
				var first_font_family = $font_family_select.val();

				// Clear default font family select options
				$font_family_select.find('option').not(':first-child').remove();

				var opts = '';

				$.each(webfonts, function (type, group) {

					// Check for exclude fonts
					if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

					opts += '<optgroup label="' + group.label + '">';

					$.each(group.fonts, function (key, value) {

						// use key if value is object
						value = (typeof value === 'object') ? key : value;
						var selected = (value === first_font_family) ? ' selected' : '';
						opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

					});

					opts += '</optgroup>';

				});

				// Append google font select options
				$font_family_select.append(opts).trigger('chosen:updated');

				//
				//
				// Font style select
				var $font_style_block = $this.find('.spftestimonial--block-font-style');

				if ($font_style_block.length) {

					var $font_style_select = $this.find('.spftestimonial--font-style-select');
					var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

					//
					// Font Style on on change listener
					$font_style_select.on('change spftestimonial.change', function (event) {

						var style_value = $font_style_select.val();

						// set a default value
						if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
							style_value = selected_styles[0];
						}

						// set font weight, for eg. replacing 800italic to 800
						var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
						var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
						var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

						$this.find('.spftestimonial--font-weight').val(font_weight);
						$this.find('.spftestimonial--font-style').val(font_style);

					});

					//
					//
					// Extra font style select
					var $extra_font_style_block = $this.find('.spftestimonial--block-extra-styles');

					if ($extra_font_style_block.length) {
						var $extra_font_style_select = $this.find('.spftestimonial--extra-styles');
						var first_extra_style_value = $extra_font_style_select.val();
					}

				}

				//
				//
				// Subsets select
				var $subset_block = $this.find('.spftestimonial--block-subset');
				if ($subset_block.length) {
					var $subset_select = $this.find('.spftestimonial--subset');
					var first_subset_select_value = $subset_select.val();
					var subset_multi_select = $subset_select.data('multiple') || false;
				}

				//
				//
				// Backup font family
				var $backup_font_family_block = $this.find('.spftestimonial--block-backup-font-family');

				//
				//
				// Font Family on Change Listener
				$font_family_select.on('change spftestimonial.change', function (event) {

					// Hide subsets on change
					if ($subset_block.length) {
						$subset_block.addClass('hidden');
					}

					// Hide extra font style on change
					if ($extra_font_style_block.length) {
						$extra_font_style_block.addClass('hidden');
					}

					// Hide backup font family on change
					if ($backup_font_family_block.length) {
						$backup_font_family_block.addClass('hidden');
					}

					var $selected = $font_family_select.find(':selected');
					var value = $selected.val();
					var type = $selected.data('type');

					if (type && value) {

						// Show backup fonts if font type google or custom
						if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
							$backup_font_family_block.removeClass('hidden');
						}

						// Appending font style select options
						if ($font_style_block.length) {

							// set styles for multi and normal style selectors
							var styles = defaultstyles;

							// Custom or gogle font styles
							if (type === 'google' && webfonts[type].fonts[value][0]) {
								styles = webfonts[type].fonts[value][0];
							} else if (type === 'custom' && webfonts[type].fonts[value]) {
								styles = webfonts[type].fonts[value];
							}

							selected_styles = styles;

							// Set selected style value for avoid load errors
							var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
							var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

							// Append style select options
							base.append_select_options($font_style_select, styles, set_style_value, 'style');

							// Clear first value
							first_style_value = false;

							// Show style select after appended
							$font_style_block.removeClass('hidden');

							// Appending extra font style select options
							if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

								// Append extra-style select options
								base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

								// Clear first value
								first_extra_style_value = false;

								// Show style select after appended
								$extra_font_style_block.removeClass('hidden');

							}

						}

						// Appending google fonts subsets select options
						if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

							var subsets = webfonts[type].fonts[value][1];
							var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
							var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

							// check for multiple subset select
							set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

							base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

							first_subset_select_value = false;

							$subset_block.removeClass('hidden');

						}

					} else {

						// Clear Styles
						$styles.find(':input').val('');

						// Clear subsets options if type and value empty
						if ($subset_block.length) {
							$subset_select.find('option').not(':first-child').remove();
							$subset_select.trigger('chosen:updated');
						}

						// Clear font styles options if type and value empty
						if ($font_style_block.length) {
							$font_style_select.find('option').not(':first-child').remove();
							$font_style_select.trigger('chosen:updated');
						}

					}

					// Update font type input value
					$type.val(type);

				}).trigger('spftestimonial.change');

				//
				//
				// Preview
				var $preview_block = $this.find('.spftestimonial--block-preview');

				if ($preview_block.length) {

					var $preview = $this.find('.spftestimonial--preview');

					// Set preview styles on change
					$this.on('change', SPFTESTIMONIAL.helper.debounce(function (event) {

						$preview_block.removeClass('hidden');

						var font_family = $font_family_select.val(),
							font_weight = $this.find('.spftestimonial--font-weight').val(),
							font_style = $this.find('.spftestimonial--font-style').val(),
							font_size = $this.find('.spftestimonial--font-size').val(),
							font_variant = $this.find('.spftestimonial--font-variant').val(),
							line_height = $this.find('.spftestimonial--line-height').val(),
							text_align = $this.find('.spftestimonial--text-align').val(),
							text_transform = $this.find('.spftestimonial--text-transform').val(),
							text_decoration = $this.find('.spftestimonial--text-decoration').val(),
							text_color = $this.find('.spftestimonial--color').val(),
							word_spacing = $this.find('.spftestimonial--word-spacing').val(),
							letter_spacing = $this.find('.spftestimonial--letter-spacing').val(),
							custom_style = $this.find('.spftestimonial--custom-style').val(),
							type = $this.find('.spftestimonial--type').val();

						if (type === 'google') {
							base.load_google_font(font_family, font_weight, font_style);
						}

						var properties = {};

						if (font_family) { properties.fontFamily = font_family; }
						if (font_weight) { properties.fontWeight = font_weight; }
						if (font_style) { properties.fontStyle = font_style; }
						if (font_variant) { properties.fontVariant = font_variant; }
						if (font_size) { properties.fontSize = font_size + unit; }
						if (line_height) { properties.lineHeight = line_height + line_height_unit; }
						if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
						if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
						if (text_align) { properties.textAlign = text_align; }
						if (text_transform) { properties.textTransform = text_transform; }
						if (text_decoration) { properties.textDecoration = text_decoration; }
						if (text_color) { properties.color = text_color; }

						$preview.removeAttr('style');

						// Customs style attribute
						if (custom_style) { $preview.attr('style', custom_style); }

						$preview.css(properties);

					}, 100));

					// Preview black and white backgrounds trigger
					$preview_block.on('click', function () {

						$preview.toggleClass('spftestimonial--black-background');

						var $toggle = $preview_block.find('.spftestimonial--toggle');

						if ($toggle.hasClass('fa-toggle-off')) {
							$toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
						} else {
							$toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
						}

					});

					if (!$preview_block.hasClass('hidden')) {
						$this.trigger('change');
					}

				}

			};

			base.init();

		});
	};

	//
	// Field: wp_editor
	//
	$.fn.spftestimonial_field_wp_editor = function () {
		return this.each(function () {

			if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.spftestimonial_wp_editor === 'undefined') {
				return;
			}

			var $this = $(this),
				$editor = $this.find('.spftestimonial-wp-editor'),
				$textarea = $this.find('textarea');

			// If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
			var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

			if ($has_wp_editor) {
				$editor.empty();
				$editor.append($textarea);
				$textarea.css('display', '');
			}

			// Generate a unique id
			var uid = SPFTESTIMONIAL.helper.uid('spftestimonial-editor-');
			$textarea.attr('id', uid);

			// Get default editor settings
			var default_editor_settings = {
				tinymce: window.tinyMCEPreInit.mceInit.spftestimonial_wp_editor,
				quicktags: window.tinyMCEPreInit.qtInit.spftestimonial_wp_editor
			};

			// Get default editor settings
			var field_editor_settings = $editor.data('editor-settings');

			// Callback for old wp editor
			var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

			if (wpEditor && wpEditor.hasOwnProperty('autop')) {
				wp.editor.autop = wpEditor.autop;
				wp.editor.removep = wpEditor.removep;
				wp.editor.initialize = wpEditor.initialize;
			}

			// Add on change event handle
			var editor_on_change = function (editor) {
				editor.on('change keyup', function () {
					var value = (field_editor_settings.wpautop) ? editor.getContent() : wp.editor.removep(editor.getContent());
					$textarea.val(value).trigger('change');
				});
			};

			// Extend editor selector and on change event handler
			default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });

			// Override editor tinymce settings
			if (field_editor_settings.tinymce === false) {
				default_editor_settings.tinymce = false;
				$editor.addClass('spftestimonial-no-tinymce');
			}

			// Override editor quicktags settings
			if (field_editor_settings.quicktags === false) {
				default_editor_settings.quicktags = false;
				$editor.addClass('spftestimonial-no-quicktags');
			}

			// Wait until :visible
			var interval = setInterval(function () {
				if ($this.is(':visible')) {
					window.wp.editor.initialize(uid, default_editor_settings);
					clearInterval(interval);
				}
			});

			// Add Media buttons
			if (field_editor_settings.media_buttons && window.spftestimonial_media_buttons) {
				var $editor_buttons = $editor.find('.wp-media-buttons');
				if ($editor_buttons.length) {
					$editor_buttons.find('.spftestimonial-shortcode-button').data('editor-id', uid);
				} else {
					var $media_buttons = $(window.spftestimonial_media_buttons);
					$media_buttons.find('.spftestimonial-shortcode-button').data('editor-id', uid);
					$editor.prepend($media_buttons);
				}
			}
		});
	};


	//
	// Confirm
	//
	$.fn.spftestimonial_confirm = function () {
		return this.each(function () {
			$(this).on('click', function (e) {

				var confirm_text = $(this).data('confirm') || window.spftestimonial_vars.i18n.confirm;
				var confirm_answer = confirm(confirm_text);

				if (confirm_answer) {
					SPFTESTIMONIAL.vars.is_confirm = true;
					SPFTESTIMONIAL.vars.form_modified = false;
				} else {
					e.preventDefault();
					return false;
				}

			});
		});
	};

	$.fn.serializeObject = function () {

		var obj = {};

		$.each(this.serializeArray(), function (i, o) {
			var n = o.name,
				v = o.value;

			obj[n] = obj[n] === undefined ? v
				: $.isArray(obj[n]) ? obj[n].concat(v)
					: [obj[n], v];
		});

		return obj;

	};

	//
	// Options Save
	//
	$.fn.spftestimonial_save = function () {
		return this.each(function () {

			var $this = $(this),
				$buttons = $('.spftestimonial-save'),
				$panel = $('.spftestimonial-options'),
				flooding = false,
				timeout;

			$this.on('click', function (e) {

				if (!flooding) {

					var $text = $this.data('save'),
						$value = $this.val();

					$buttons.attr('value', $text);

					if ($this.hasClass('spftestimonial-save-ajax')) {

						e.preventDefault();

						$panel.addClass('spftestimonial-saving');
						$buttons.prop('disabled', true);

						window.wp.ajax.post('spftestimonial_' + $panel.data('unique') + '_ajax_save', {
							data: $('#spftestimonial-form').serializeJSONSPFTESTIMONIAL()
						})
							.done(function (response) {

								// clear errors
								$('.spftestimonial-error').remove();

								if (Object.keys(response.errors).length) {

									var error_icon = '<i class="spftestimonial-label-error spftestimonial-error">!</i>';

									$.each(response.errors, function (key, error_message) {

										var $field = $('[data-depend-id="' + key + '"]'),
											$link = $('a[href="#tab=' + $field.closest('.spftestimonial-section').data('section-id') + '"]'),
											$tab = $link.closest('.spftestimonial-tab-item');

										$field.closest('.spftestimonial-fieldset').append('<p class="spftestimonial-error spftestimonial-error-text">' + error_message + '</p>');

										if (!$link.find('.spftestimonial-error').length) {
											$link.append(error_icon);
										}

										if (!$tab.find('.spftestimonial-arrow .spftestimonial-error').length) {
											$tab.find('.spftestimonial-arrow').append(error_icon);
										}
										//  $('.spftestimonial-options .spftestimonial-save.spftestimonial-save-ajax').attr('disabled', true);
									});

								}

								$panel.removeClass('spftestimonial-saving');
								$buttons.prop('disabled', true).attr('value', 'Changes Saved');
								flooding = false;

								SPFTESTIMONIAL.vars.form_modified = false;
								SPFTESTIMONIAL.vars.$form_warning.hide();

								clearTimeout(timeout);

								var $result_success = $('.spftestimonial-form-success');
								$result_success.empty().append(response.notice).fadeIn('fast', function () {
									timeout = setTimeout(function () {
										$result_success.fadeOut('fast');
									}, 1000);
								});

							})
							.fail(function (response) {
								alert(response.error);
							});

					} else {

						SPFTESTIMONIAL.vars.form_modified = false;

					}

				}

				flooding = true;

			});

		});
	};

	//
	// Option Framework
	//
	$.fn.spftestimonial_options = function () {
		return this.each(function () {

			var $this = $(this),
				$content = $this.find('.spftestimonial-content'),
				$form_success = $this.find('.spftestimonial-form-success'),
				$form_warning = $this.find('.spftestimonial-form-warning'),
				$save_button = $this.find('.spftestimonial-header .spftestimonial-save');

			SPFTESTIMONIAL.vars.$form_warning = $form_warning;

			// Shows a message white leaving theme options without saving
			if ($form_warning.length) {

				window.onbeforeunload = function () {
					return (SPFTESTIMONIAL.vars.form_modified) ? true : undefined;
				};

				$content.on('change keypress', ':input', function () {
					if (!SPFTESTIMONIAL.vars.form_modified) {
						$form_success.hide();
						$form_warning.fadeIn('fast');
						SPFTESTIMONIAL.vars.form_modified = true;
					}
				});

			}

			if ($form_success.hasClass('spftestimonial-form-show')) {
				setTimeout(function () {
					$form_success.fadeOut('fast');
				}, 1000);
			}

			$(document).on('keydown', function (event) {
				if ((event.ctrlKey || event.metaKey) && event.which === 83) {
					$save_button.trigger('click');
					event.preventDefault();
					return false;
				}
			});

		});
	};

	//
	// WP Color Picker
	//
	if (typeof Color === 'function') {

		Color.prototype.toString = function () {

			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}

			var hex = parseInt(this._color, 10).toString(16);

			if (this.error) { return ''; }

			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}

			return '#' + hex;

		};

	}

	SPFTESTIMONIAL.funcs.parse_color = function (color) {

		var value = color.replace(/\s+/g, ''),
			trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
			rgba = (trans < 100) ? true : false;

		return { value: value, transparent: trans, rgba: rgba };

	};

	$.fn.spftestimonial_color = function () {
		return this.each(function () {

			var $input = $(this),
				picker_color = SPFTESTIMONIAL.funcs.parse_color($input.val()),
				palette_color = window.spftestimonial_vars.color_palette.length ? window.spftestimonial_vars.color_palette : true,
				$container;

			// Destroy and Reinit
			if ($input.hasClass('wp-color-picker')) {
				$input.closest('.wp-picker-container').after($input).remove();
			}

			$input.wpColorPicker({
				palettes: palette_color,
				change: function (event, ui) {

					var ui_color_value = ui.color.toString();

					$container.removeClass('spftestimonial--transparent-active');
					$container.find('.spftestimonial--transparent-offset').css('background-color', ui_color_value);
					$input.val(ui_color_value).trigger('change');

				},
				create: function () {

					$container = $input.closest('.wp-picker-container');

					var a8cIris = $input.data('a8cIris'),
						$transparent_wrap = $('<div class="spftestimonial--transparent-wrap">' +
							'<div class="spftestimonial--transparent-slider"></div>' +
							'<div class="spftestimonial--transparent-offset"></div>' +
							'<div class="spftestimonial--transparent-text"></div>' +
							'<div class="spftestimonial--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
							'</div>').appendTo($container.find('.wp-picker-holder')),
						$transparent_slider = $transparent_wrap.find('.spftestimonial--transparent-slider'),
						$transparent_text = $transparent_wrap.find('.spftestimonial--transparent-text'),
						$transparent_offset = $transparent_wrap.find('.spftestimonial--transparent-offset'),
						$transparent_button = $transparent_wrap.find('.spftestimonial--transparent-button');

					if ($input.val() === 'transparent') {
						$container.addClass('spftestimonial--transparent-active');
					}

					$transparent_button.on('click', function () {
						if ($input.val() !== 'transparent') {
							$input.val('transparent').trigger('change').removeClass('iris-error');
							$container.addClass('spftestimonial--transparent-active');
						} else {
							$input.val(a8cIris._color.toString()).trigger('change');
							$container.removeClass('spftestimonial--transparent-active');
						}
					});

					$transparent_slider.slider({
						value: picker_color.transparent,
						step: 1,
						min: 0,
						max: 100,
						slide: function (event, ui) {

							var slide_value = parseFloat(ui.value / 100);
							a8cIris._color._alpha = slide_value;
							$input.wpColorPicker('color', a8cIris._color.toString());
							$transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

						},
						create: function () {

							var slide_value = parseFloat(picker_color.transparent / 100),
								text_value = slide_value < 1 ? slide_value : '';

							$transparent_text.text(text_value);
							$transparent_offset.css('background-color', picker_color.value);

							$container.on('click', '.wp-picker-clear', function () {

								a8cIris._color._alpha = 1;
								$transparent_text.text('');
								$transparent_slider.slider('option', 'value', 100);
								$container.removeClass('spftestimonial--transparent-active');
								$input.trigger('change');

							});

							$container.on('click', '.wp-picker-default', function () {

								var default_color = SPFTESTIMONIAL.funcs.parse_color($input.data('default-color')),
									default_value = parseFloat(default_color.transparent / 100),
									default_text = default_value < 1 ? default_value : '';

								a8cIris._color._alpha = default_value;
								$transparent_text.text(default_text);
								$transparent_slider.slider('option', 'value', default_color.transparent);

								if (default_color.value === 'transparent') {
									$input.removeClass('iris-error');
									$container.addClass('spftestimonial--transparent-active');
								}

							});

						}
					});
				}
			});

		});
	};

	//
	// ChosenJS
	//
	$.fn.spftestimonial_chosen = function () {
		return this.each(function () {

			var $this = $(this),
				$inited = $this.parent().find('.chosen-container'),
				is_sortable = $this.hasClass('spftestimonial-chosen-sortable') || false,
				is_ajax = $this.hasClass('spftestimonial-chosen-ajax') || false,
				is_multiple = $this.attr('multiple') || false,
				set_width = is_multiple ? '100%' : 'auto',
				set_options = $.extend({
					allow_single_deselect: true,
					disable_search_threshold: 10,
					width: set_width,
					no_results_text: window.spftestimonial_vars.i18n.no_results_text,
				}, $this.data('chosen-settings'));

			if ($inited.length) {
				$inited.remove();
			}

			// Chosen ajax
			if (is_ajax) {

				var set_ajax_options = $.extend({
					data: {
						type: 'post',
						nonce: '',
					},
					allow_single_deselect: true,
					disable_search_threshold: -1,
					width: '100%',
					min_length: 3,
					type_delay: 500,
					typing_text: window.spftestimonial_vars.i18n.typing_text,
					searching_text: window.spftestimonial_vars.i18n.searching_text,
					no_results_text: window.spftestimonial_vars.i18n.no_results_text,
				}, $this.data('chosen-settings'));

				$this.SPFTESTIMONIALAjaxChosen(set_ajax_options);

			} else {

				$this.chosen(set_options);

			}

			// Chosen keep options order
			if (is_multiple) {

				var $hidden_select = $this.parent().find('.spftestimonial-hide-select');
				var $hidden_value = $hidden_select.val() || [];

				$this.on('change', function (obj, result) {

					if (result && result.selected) {
						$hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
					} else if (result && result.deselected) {
						$hidden_select.find('option[value="' + result.deselected + '"]').remove();
					}

					// Force customize refresh
					if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
						window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
					}

					$hidden_select.trigger('change');

				});

				// Chosen order abstract
				$this.SPFTESTIMONIALChosenOrder($hidden_value, true);

			}

			// Chosen sortable
			if (is_sortable) {

				var $chosen_container = $this.parent().find('.chosen-container');
				var $chosen_choices = $chosen_container.find('.chosen-choices');

				$chosen_choices.bind('mousedown', function (event) {
					if ($(event.target).is('span')) {
						event.stopPropagation();
					}
				});

				$chosen_choices.sortable({
					items: 'li:not(.search-field)',
					helper: 'orginal',
					cursor: 'move',
					placeholder: 'search-choice-placeholder',
					start: function (e, ui) {
						ui.placeholder.width(ui.item.innerWidth());
						ui.placeholder.height(ui.item.innerHeight());
					},
					update: function (e, ui) {

						var select_options = '';
						var chosen_object = $this.data('chosen');
						var $prev_select = $this.parent().find('.spftestimonial-hide-select');

						$chosen_choices.find('.search-choice-close').each(function () {
							var option_array_index = $(this).data('option-array-index');
							$.each(chosen_object.results_data, function (index, data) {
								if (data.array_index === option_array_index) {
									select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
								}
							});
						});

						$prev_select.children().remove();
						$prev_select.append(select_options);
						$prev_select.trigger('change');

					}
				});

			}

		});
	};

	//
	// Helper Checkbox Checker
	//
	$.fn.spftestimonial_checkbox = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('.spftestimonial--input'),
				$checkbox = $this.find('.spftestimonial--checkbox');

			$checkbox.on('click', function () {
				$input.val(Number($checkbox.prop('checked'))).trigger('change');
			});

		});
	};

	//
	// Siblings
	//
	$.fn.spftestimonial_siblings = function () {
		return this.each(function () {

			var $this = $(this),
				$siblings = $this.find('.spftestimonial--sibling'),
				multiple = $this.data('multiple') || false;

			$siblings.on('click', function () {

				var $sibling = $(this);

				if (multiple) {

					if ($sibling.hasClass('spftestimonial--active')) {
						$sibling.removeClass('spftestimonial--active');
						$sibling.find('input').prop('checked', false).trigger('change');
					} else {
						$sibling.addClass('spftestimonial--active');
						$sibling.find('input').prop('checked', true).trigger('change');
					}

				} else {

					$this.find('input').prop('checked', false);
					$sibling.find('input').prop('checked', true).trigger('change');
					$sibling.addClass('spftestimonial--active').siblings().removeClass('spftestimonial--active');

				}

			});

		});
	};

	//
	// Help Tooltip
	//
	$.fn.spftestimonial_help = function () {
		return this.each(function () {

			var $this = $(this),
				$tooltip,
				offset_left,
				$class = '';

			$this.on({
				mouseenter: function () {
					// this class add with the support tooltip.
					if ($this.find('.spftestimonial-support').length > 0) {
						$class = 'support-tooltip';
					}

					var help_text = $this.find('.spftestimonial-help-text').html();
					if ($('.spftestimonial-tooltip').length > 0) {
						$tooltip = $('.spftestimonial-tooltip').html(help_text);
					} else {
						$tooltip = $('<div class="spftestimonial-tooltip ' + $class + '"></div>').html(help_text).appendTo('body');
					}

					offset_left = SPFTESTIMONIAL.vars.is_rtl
						? $this.offset().left - $tooltip.outerWidth()
						: $this.offset().left + 24;
					var $top = $this.offset().top - ($tooltip.outerHeight() / 2 - 14);

					// this block used for support tooltip.
					if ($this.find('.spftestimonial-support').length > 0) {
						$top = $this.offset().top + 43;
						offset_left = $this.offset().left - 221;
					}

					$tooltip.css({
						top: $top,
						left: offset_left,
						textAlign: 'left',
					});
				},
				mouseleave: function () {
					if (!$tooltip.is(':hover')) {
						$tooltip.remove();
					}
				}
			});
			// Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
			$('body').on('mouseleave', '.spftestimonial-tooltip', function () {
				if ($tooltip !== undefined) {
					$tooltip.remove();
				}
			});
		});
	};


	//
	// Window on resize
	//
	SPFTESTIMONIAL.vars.$window.on('resize spftestimonial.resize', SPFTESTIMONIAL.helper.debounce(function (event) {

		var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SPFTESTIMONIAL.vars.$window.width() : window.innerWidth;

		if (window_width <= 782 && !SPFTESTIMONIAL.vars.onloaded) {
			$('.spftestimonial-section').spftestimonial_reload_script();
			SPFTESTIMONIAL.vars.onloaded = true;
		}

	}, 200)).trigger('spftestimonial.resize');

	//
	// Reload Plugins
	//
	$.fn.spftestimonial_reload_script = function (options) {

		var settings = $.extend({
			dependency: true,
		}, options);

		return this.each(function () {

			var $this = $(this);

			// Avoid for conflicts
			if (!$this.data('inited')) {

				// Field plugins
				$this.children('.spftestimonial-field-accordion:not(.tfree_pro_only)').spftestimonial_field_accordion();
				$this.children('.spftestimonial-field-code_editor').spftestimonial_field_code_editor();
				// $this.children('.spftestimonial-field-icon').spftestimonial_field_icon();
				$this.children('.spftestimonial-field-fieldset').spftestimonial_field_fieldset();
				$this.children('.spftestimonial-field-repeater').spftestimonial_field_repeater();
				$this.children('.spftestimonial-field-sortable').spftestimonial_field_sortable();
				$this.children('.spftestimonial-field-sorter').spftestimonial_field_sorter();
				$this.children('.spftestimonial-field-spinner').spftestimonial_field_spinner();
				$this.children('.spftestimonial-field-switcher').spftestimonial_field_switcher();
				$this.children('.spftestimonial-field-slider').spftestimonial_field_slider();
				$this.children('.spftestimonial-field-tabbed').spftestimonial_field_tabbed();
				$this.children('.spftestimonial-field-typography').spftestimonial_field_typography();
				$this.children('.spftestimonial-field-wp_editor').spftestimonial_field_wp_editor();

				// Field colors
				$this.children('.spftestimonial-field-box_shadow').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-border').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-background').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-color').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-color_group').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-link_color').find('.spftestimonial-color').spftestimonial_color();
				$this.children('.spftestimonial-field-typography').find('.spftestimonial-color').spftestimonial_color();

				// Field chosenjs
				$this.children('.spftestimonial-field-select').find('.spftestimonial-chosen').spftestimonial_chosen();

				// Field Checkbox
				$this.children('.spftestimonial-field-checkbox').find('.spftestimonial-checkbox').spftestimonial_checkbox();

				// Field Siblings
				$this.children('.spftestimonial-field-button_set').find('.spftestimonial-siblings').spftestimonial_siblings();
				$this.children('.spftestimonial-field-image_select, .spftestimonial-field-icon_select').find('.spftestimonial-siblings').spftestimonial_siblings();
				$this.children('.spftestimonial-field-palette').find('.spftestimonial-siblings').spftestimonial_siblings();

				// Help Tooptip
				$this.children('.spftestimonial-field').find('.spftestimonial-help').spftestimonial_help();

				if (settings.dependency) {
					$this.spftestimonial_dependency();
				}

				$this.data('inited', true);

				$(document).trigger('spftestimonial-reload-script', $this);

			}

		});
	};

	//
	// Document ready and run scripts
	//
	$(document).ready(function () {

		$('.spftestimonial-save').spftestimonial_save();
		$('.spftestimonial-options').spftestimonial_options();
		$('.spftestimonial-sticky-header').spftestimonial_sticky();
		$('.spftestimonial-nav-options').spftestimonial_nav_options();
		$('.spftestimonial-nav-metabox').spftestimonial_nav_metabox();
		$('.spftestimonial-search').spftestimonial_search();
		$('.spftestimonial-confirm').spftestimonial_confirm();
		$('.spftestimonial-expand-all').spftestimonial_expand_all();
		$('.spftestimonial-onload').spftestimonial_reload_script();
		$('.spftestimonial-admin-header').find('.spftestimonial-support-area').spftestimonial_help();
	});

	// Live Preview script for Testimonial-free.
	var is_manage_preview = $('body').hasClass('post-type-spt_shortcodes');
	var is_form_preview = $('body').hasClass('post-type-spt_testimonial_form');
	var preview_box = $('#sp_tpro-preview-box');

	if (is_manage_preview) {
		var preview_display = $('#sp_tpro_live_preview').hide();
		var action = 'sp_tpro_preview_meta_box';
		var nonce = $('#spftestimonial_metabox_noncesp_tpro_shortcode_options').val();
	}

	if (is_form_preview) {
		var preview_display = $('#sp_tpro_form_live_preview').hide();
		var action = 'sp_testimonial_form_preview';
		var nonce = $('#spftestimonial_metabox_noncesp_tpro_form_live_preview').val();
	}

	$(document).on('click', '#sp-testimonial-show-preview:contains(Hide)', function (e) {
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
		preview_box.html('');
		preview_display.hide();
	});

	$(document).on('click', '#sp-testimonial-show-preview:not(:contains(Hide))', function (e) {
		e.preventDefault();
		var previewJS = window.spftestimonial_vars.previewJS;
		var _data = $('form#post').serialize();
		var _this = $(this);
		var data = {
			action: action,
			data: _data,
			ajax_nonce: nonce
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			error: function (response) {
				console.log(response)
			},
			success: function (response) {

				preview_display.show();
				preview_box.html(response);
				$.getScript(previewJS, function () {
					_this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
					$(document).on('keyup change', function (e) {
						e.preventDefault();
						_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
					});
					$("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
				});
				$('.sp-testimonial-preloader').animate({ opacity: 1 }, 600).hide();
			}
		})
	});

	/* Copy to clipboard */
	$('.sp-testimonial-copy-btn,.tpro-sc-code,.spftestimonial-shortcode-selectable').on('click', function (e) {
		e.preventDefault();
		spftestimonial_copyToClipboard($(this));
		spftestimonial_SelectText($(this));
		$(this).focus().select();
		$('.sp-testimonial-after-copy-text').animate({
			opacity: 1,
			bottom: 25
		}, 300);
		setTimeout(function () {
			jQuery(".sp-testimonial-after-copy-text").animate({
				opacity: 0,
			}, 200);
			jQuery(".sp-testimonial-after-copy-text").animate({
				bottom: 0
			}, 0);
		}, 2000);
	});
	$('.sp_tfree_input').on('click', function (e) {
		e.preventDefault();
		/* Get the text field */
		var copyText = $(this);
		/* Select the text field */
		copyText.select();
		document.execCommand("copy");
		$('.sp-testimonial-after-copy-text').animate({
			opacity: 1,
			bottom: 25
		}, 300);
		setTimeout(function () {
			jQuery(".sp-testimonial-after-copy-text").animate({
				opacity: 0,
			}, 200);
			jQuery(".sp-testimonial-after-copy-text").animate({
				bottom: 0
			}, 0);
		}, 2000);
	});
	function spftestimonial_copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}
	function spftestimonial_SelectText(element) {
		var r = document.createRange();
		var w = element.get(0);
		r.selectNodeContents(w);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(r);
	}

	// Check is valid JSON string
	function isValidJSONString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}

	// Testimonial pro import.
	// Csv to array function.
	function CSVToArray(strData, strDelimiter) {
		// Check to see if the delimiter is defined. If not,
		// then default to comma.
		strDelimiter = (strDelimiter || ",");
		// Create a regular expression to parse the CSV values.
		var objPattern = new RegExp((
			// Delimiters.
			"(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
			// Quoted fields.
			"(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
			// Standard fields.
			"([^\"\\" + strDelimiter + "\\r\\n]*))"), "gi");
		// Create an array to hold our data. Give the array
		// a default empty first row.
		var arrData = [[]];
		// Create an array to hold our individual pattern
		// matching groups.
		var arrMatches = null;
		// Keep looping over the regular expression matches
		// until we can no longer find a match.
		while (arrMatches = objPattern.exec(strData)) {
			// Get the delimiter that was found.
			var strMatchedDelimiter = arrMatches[1];
			// Check to see if the given delimiter has a length
			// (is not the start of string) and if it matches
			// field delimiter. If id does not, then we know
			// that this delimiter is a row delimiter.
			if (strMatchedDelimiter.length && (strMatchedDelimiter != strDelimiter)) {
				// Since we have reached a new row of data,
				// add an empty row to our data array.
				arrData.push([]);
			}
			// Now that we have our delimiter out of the way,
			// let's check to see which kind of value we
			// captured (quoted or unquoted).
			if (arrMatches[2]) {
				// We found a quoted value. When we capture
				// this value, unescape any double quotes.
				var strMatchedValue = arrMatches[2].replace(
					new RegExp("\"\"", "g"), "\"");
			} else {
				// We found a non-quoted value.
				var strMatchedValue = arrMatches[3];
			}
			// Now that we have our value string, let's add
			// it to the data array.
			arrData[arrData.length - 1].push(strMatchedValue);
		}
		// Return the parsed data.
		return (arrData);
	}

	// CSV to json.
	function CSV2JSON(csv) {
		var array = CSVToArray(csv);
		var objArray = [];
		for (var i = 1; i < array.length - 1; i++) {
			objArray[i - 1] = {};
			for (var k = 0; k < array[0].length && k < array[i].length; k++) {
				var key = array[0][k];
				objArray[i - 1][key] = array[i][k]
			}
		}
		var json = JSON.stringify(objArray);
		var str = json.replace(/},/g, "},\r\n");
		return str;
	}

	// Function to convert CSV to json.
	function csvToForm(props, csvData) {
		props.shortcode = [];
		csvData.forEach((data) => {
			props.shortcode.push({
				title: (typeof data.title !== 'undefined' && data.title) ? data.title : '',
				original_id: (typeof data.original_id !== 'undefined' && data.original_id) ? data.original_id : '',
				content: (typeof data.content !== 'undefined' && data.content) ? data.content : '',
				image: (typeof data.image !== 'undefined' && data.image) ? data.image : '',
				all_testimonial: (typeof data.all_testimonial !== 'undefined' && data.all_testimonial) ? data.all_testimonial : '',
				spt_post: 'spt_testimonial',
				meta: {
					_edit_last: (typeof data._edit_last !== 'undefined') ? data._edit_last : '',
					_edit_lock: (typeof data._edit_lock !== 'undefined') ? data._edit_lock : '',
					_thumbnail_id: (typeof data._thumbnail_id !== 'undefined') ? data._thumbnail_id : '',
					sp_tpro_meta_options: {
						tpro_name: (typeof data.name !== 'undefined') ? data.name : '',
						tpro_designation: (typeof data.designation !== 'undefined') ? data.designation : '',
						tpro_rating: (typeof data.rating !== 'undefined') ? data.rating : '',
					}
				}
			});
		})
	}
	function removeTags(html) {
		return html.replace(/<[^>]*>/g, '');
	}

	// Testimonial pro export.
	var $export_type = $('.spt_what_export').find('input:checked').val();
	$('.spt_what_export').on('change', function () {
		$export_type = $(this).find('input:checked').val();
	});

	var $export_file_type = $('.spt_export_file_type').find('input:checked').val();
	$('.spt_export_file_type').on('change', function () {
		$export_file_type = $(this).find('input:checked').val();
	});

	$('.spt_export .spftestimonial--button').on('click', function (event) {
		event.preventDefault();
		var $shortcode_ids = $('.spt_post_id select').val();
		var $forms_ids = $('.spt_post_forms_id select').val();
		var $ex_nonce = $('#spftestimonial_options_noncesp_testimonial_pro_tools').val();
		if ($export_type === 'all_testimonial') {
			var data = {
				action: 'spt_export_shortcodes',
				lcp_ids: 'all_testimonial',
				nonce: $ex_nonce,
				file_type: $export_file_type,
			}
		} else if ('all_spt_shortcodes' === $export_type) {
			var data = {
				action: 'spt_export_shortcodes',
				lcp_ids: 'all_spt_shortcodes',
				nonce: $ex_nonce,
			}
		} else if ('selected_spt_shortcodes' === $export_type) {
			var data = {
				action: 'spt_export_shortcodes',
				lcp_ids: $shortcode_ids,
				text_ids: 'select_shortcodes',
				nonce: $ex_nonce,
			}
		} else {
			$('.spftestimonial-form-result.spftestimonial-form-success').text('No group selected.').show();
			setTimeout(function () {
				$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('');
			}, 3000);
		}

		$.post(ajaxurl, data, function (resp) {
			if (resp) {
				// Convert JSON Array to string.
				if (isValidJSONString(resp)) {
					var json = JSON.stringify(JSON.parse(resp));
				} else {
					var json = JSON.stringify(resp);
				}

				if ('csv_file' === $export_file_type) {
					if (isValidJSONString(resp)) {
						resp = JSON.parse(resp);
					}
					resp.type = `${$export_type}`;
					var props = [];

					resp.shortcode.forEach((item) => {
						props.push([
							resp.metadata.version || "", // Check version
							resp.metadata.date || "", // Check date
							item.all_testimonial || "", // Check all_testimonial
							JSON.stringify(item.title) || "", // Check title
							item.original_id || "", // Check original_id
							item.content ? JSON.stringify(item.content) : "", // Check content
							item.image ? item.image : '', // Check image
							item.meta._edit_last || "", // Check _edit_last
							item.meta._edit_lock || "", // Check _edit_lock
							item.meta.sp_tpro_meta_options.tpro_name || "", // Check tpro_name
							JSON.stringify(item.meta.sp_tpro_meta_options.tpro_designation) || "", // Check tpro_designation
							item.meta.sp_tpro_meta_options.tpro_rating || "", // Check tpro_rating
						]);
					});
					var csvData = 'version,date,all_testimonial,title,original_id,content,image,_edit_last,_edit_lock,name,designation,rating\n';

					props.forEach(function (row) {
						csvData += row.join(',');
						csvData += "\n";
					});
					json = csvData;
				}

				var blob = new Blob([json], { type: 'application/json' });
				var link = document.createElement('a');
				var lcp_time = $.now();
				link.href = window.URL.createObjectURL(blob);
				// link.download = "testtimonial-pro-export-" + lcp_time + ".json";
				link.download = "testtimonial-pro-export-" + lcp_time + ('csv_file' === $export_file_type ? ".csv" : ".json");
				link.click();
				$('.spftestimonial-form-result.spftestimonial-form-success').text('Exported successfully!').show();
				setTimeout(function () {
					$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('');
					$('.spt_post_id select').val('').trigger('chosen:updated');
					$('.spt_post_forms_id select').val('').trigger('chosen:updated');
				}, 3000);
			}
		});
	});

	// Testimonial free import.
	$('.spt_import button.import').on('click', function (event) {
		var $this = $(this),
			button_label = $(this).text();
		event.preventDefault();
		var lcp_shortcodes = $('#import').prop('files')[0];
		if ($('#import').val() != '') {
			var getFileExtension = lcp_shortcodes.name.split('.').pop();
			var $im_nonce = $('#spftestimonial_options_noncesp_testimonial_pro_tools').val();
			var reader = new FileReader();
			reader.readAsText(lcp_shortcodes);
			reader.onload = function (event) {
				var jsonObj = JSON.stringify(event.target.result);

				// Import CSV file.
				if ('csv' === getFileExtension) {
					const props = {};
					// Dynamic field key.
					var csvData = JSON.parse(CSV2JSON(event.target.result));
					// Dynamic field key.
					var dynamicKey = Object.keys(csvData[0]);
					// Our field key.
					var fieldKey = ['Do Not Import', 'title', 'original_id', 'content', 'image', 'name', 'designation'];
					// Our avoid field.
					var avoidField = ['version', 'date', 'all_testimonial', 'original_id', '_edit_last', '_edit_lock', '_thumbnail_id'];
					// Demo data.
					var sampleData = csvData[0];
					// Data match form.
					var formHtml = `<form class="stp_csv_file_import_form" method="post">`;
					dynamicKey.forEach((keyType) => {
						if (avoidField.includes(keyType.toLowerCase())) {
							return;
						}
						formHtml += `<div data-type="${keyType}" class="stp_csv_file_row">
						<div class="stp_csv_file_content">
						<p class="stp_csv_file_type">${keyType}</p>
						<p class="stp_csv_file_demo">Sample: ${(sampleData[keyType].length > 150) ? removeTags(sampleData[keyType].substring(0, 150)) + '...' : sampleData[keyType]}</p>
						</div>
						<select>`;
						fieldKey.forEach(key => {
							formHtml += `<option ${key === keyType ? 'selected' : ''} value="${key}"> ${key} </option>`;
						})
						formHtml += `</select>
					  </div>`;
					})
					formHtml += `</form><button type="button" class="import import-csv-file">CSV Upload</button>`;
					$('.spt_import .spftestimonia-fieldset .import').hide();
					$('.spt_import .spftestimonial-fieldset').append(formHtml);
					csvToForm(props, csvData);
					$('.spt_import .spftestimonial-fieldset form.stp_csv_file_import_form').on('change', 'select', function () {
						var type = $(this).parent().attr('data-type');
						var currentType = $(this).val();
						var changeData = csvData;
						csvData.forEach((data, i) => {
							changeData[i][currentType] = data[type];
						})
						csvToForm(props, changeData);
					})
					props.metadata = {
						version: (typeof csvData[0].version != 'undefined') ? csvData[0].version : '',
						date: (typeof csvData[0].date != 'undefined') ? csvData[0].date : '',
					};
					$('.import-csv-file').on('click', function () {
						var $this = $(this),
							button_text = $this.text();
						jsonObj = JSON.stringify(JSON.stringify(props));
						$this.append('<span class="spftestimonial-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								shortcode: jsonObj,
								action: 'spt_import_shortcodes',
								nonce: $im_nonce,
								file_type: getFileExtension,
								timeout: 100000
							},
							success: function (resp) {
								$('.spftestimonial-form-result.spftestimonial-form-success').text('Imported successfully!').show();
								$this.prop('disabled', false).css('opacity', '1').text(button_text);
								setTimeout(function () {
									$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('');
									$('#import').val('');
									if (resp.data === 'spt_testimonial') {
										window.location.replace($('#spt_testimonial_link_redirect').attr('href'));
									} else {
										window.location.replace($('#spt_shortcode_link_redirect').attr('href'));
									}
								}, 2000);
							},
							error: function (error) {
								$('.spftestimonial-form-result.spftestimonial-form-success').text('Something went wrong!').addClass('spftestimonial-import-warning').show();
								$this.prop('disabled', false).css('opacity', '1').text(button_text);
								setTimeout(function () {
									$('.spftestimonial-form-result.spftestimonial-form-success').hide().removeClass('spftestimonial-import-warning').text('');
									$('#import').val('');
								}, 2000);
							}
						});
					});
				} else {
					$this.append('<span class="spftestimonial-page-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');
					$this.css('opacity', '0.7');
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							shortcode: jsonObj,
							action: 'spt_import_shortcodes',
							nonce: $im_nonce,
						},
						success: function (resp) {
							$this.html(button_label).css('opacity', '1');

							$('.spftestimonial-form-result.spftestimonial-form-success').text('Imported successfully!').show();
							setTimeout(function () {
								$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('');
								$('#import').val('');
								if (resp.data === 'spt_testimonial') {
									window.location.replace($('#spt_testimonial_link_redirect').attr('href'));
								} else if (resp.data === 'spt_testimonial_form') {
									window.location.replace($('#spt_forms_link_redirect').attr('href'));
								} else {
									window.location.replace($('#spt_shortcode_link_redirect').attr('href'));
								}
							}, 2000);
						},
						error: function (error) {
							$('#import').val('');
							$this.html(button_label).css('opacity', '1');
							$('.spftestimonial-form-result.spftestimonial-form-success').addClass('error')
								.text('Something went wrong, please try again!').show();
							setTimeout(function () {
								$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('').removeClass('error');
							}, 2000);
						}
					});
				}
			}
		} else {
			$('.spftestimonial-form-result.spftestimonial-form-success').text('No exported json file chosen.').show();
			setTimeout(function () {
				$('.spftestimonial-form-result.spftestimonial-form-success').hide().text('');
			}, 3000);
		}
	});
	// Disabled save button.
	$(document).on('keyup change', '.spftestimonial-options #spftestimonial-form', function (e) {
		e.preventDefault();
		$(this).find('.spftestimonial-save.spftestimonial-save-ajax').attr('value', 'Save Settings').attr('disabled', false);
	});

	// Reusable function to handle limit type on changes
	function LimitTypeHandler(limitType, $limitContainer) {
		if (limitType === 'characters') {
			$limitContainer.find(".character_limit").show();
			$limitContainer.find(".word_limit").hide();
		} else {
			$limitContainer.find(".character_limit").hide();
			$limitContainer.find(".word_limit").show();
		}
	}

	// Cache frequently used elements
	var $titleLengthType = $(".title-length_type");
	var $contentLengthType = $(".content-length_type");

	$titleLengthType.on('change', function (e) {
		var titleLimitType = $titleLengthType.find("option:selected").val();
		LimitTypeHandler(titleLimitType, $(".title_limit"));
	});

	$contentLengthType.on('change', function (e) {
		var contentLimitType = $contentLengthType.find("option:selected").val();
		LimitTypeHandler(contentLimitType, $(".content_limit"));
	});

	// Trigger initial change event to set initial state
	$titleLengthType.trigger('change');
	$contentLengthType.trigger('change');

	$("select option:contains((Pro))").attr('disabled', true).css('opacity', '0.8');
	$(".addition_extra_fields_pro select option").attr('disabled', true).css('opacity', '0.8');
	$("label:contains((Pro))").css({ 'pointer-events': 'none' }).css('opacity', '0.8');

	/* Show/Hide dependency of 'Slider Settings' tab on page load. */
	var layout_val = $(".tfree-layout-preset .spftestimonial--active").find('input:checked').val();
	if (layout_val == 'slider' || layout_val == 'carousel' || layout_val == 'multi_rows' || layout_val == 'thumbnail_slider') {
		$(".spftestimonial-metabox .spftestimonial-nav li a[data-section=sp_tpro_shortcode_options_3]").show();
	} else {
		$(".spftestimonial-metabox .spftestimonial-nav li a[data-section=sp_tpro_shortcode_options_3]").hide();
	}

	$(".tfree-layout-preset").on('click', '.spftestimonial--sibling', function (e) {
		var layout_val = $(".tfree-layout-preset .spftestimonial--active").find('input:checked').val(),
			slider_mode = $('.spftestimonial-field.sp_slider_mode .spftestimonial--active').find("input:checked").val(),
			carousel_mode = $('.spftestimonial-field.sp_carousel_mode .spftestimonial--active').find("input:checked").val();

		// Define column values based on layout and slider mode.
		var columnValues = {
			standard: {
				large_desktop: "1",
				desktop: "1",
				laptop: "1",
				tablet: "1",
				mobile: "1"
			},
			default: {
				large_desktop: "3",
				desktop: "3",
				laptop: "2",
				tablet: "1",
				mobile: "1"
			},
		};
		// Set column values based on conditions
		var responsiveColumns = ((layout_val == "slider" && slider_mode == 'standard') || layout_val == "list") ? columnValues.standard : columnValues.default;

		$.each(responsiveColumns, function (key, value) {
			$("input.spftestimonial-number[name='sp_tpro_shortcode_options[columns][" + key + "]']").val(value);
		});

		/* Show/Hide dependency of 'Slider Settings' tab. */
		if (layout_val == 'slider' || layout_val == 'carousel' || layout_val == 'multi_rows' || layout_val == 'thumbnail_slider') {
			$(".spftestimonial-metabox .spftestimonial-nav li a[data-section=sp_tpro_shortcode_options_3]").show();
		} else {
			$(".spftestimonial-metabox .spftestimonial-nav li a[data-section=sp_tpro_shortcode_options_3]").hide();
		}
	});

	// Show/hide approval status notice based on selected options.
	var optionVal = $('.testimonial_approval_status select').find('option:selected').val(),
		navigationVal = $('.spftestimonial-carousel-nav-position select').find('option:selected').val(),
		approvalStatusNotice = $('.testimonial-approval-status-notice');
	// show/hide text of based on review option.
	if (optionVal == 'publish' || optionVal == 'based_on_rating_star') {
		$(approvalStatusNotice).show();
	} else {
		$(approvalStatusNotice).hide();
	}
	// show hide text of carousel navigation.
	if (navigationVal != 'vertical_outer') {
		$('.sp_carousel-navigation-notice').show();
	} else {
		$('.sp_carousel-navigation-notice').hide();
	}

	$('.testimonial_approval_status').on('change', 'select', function () {
		var optionVal = $(this).find('option:selected').val(),
			approvalStatusNotice = $('.testimonial-approval-status-notice');
		if (optionVal == 'publish' || optionVal == 'based_on_rating_star') {
			$(approvalStatusNotice).show();
		} else {
			$(approvalStatusNotice).hide();
		}
	});

	// Get the last activated or selected layout.
	var lastSelectedOption = $('input[name="sp_tpro_layout_options[layout]"]:checked').val();
	$('input[name="sp_tpro_layout_options[layout]"]').on('change', function () {
		if (!$(this).is(':disabled')) {
			lastSelectedOption = $(this).val();
		}
	});

	// Revert the selection to the last valid activated option that was selected before if the disabled/pro option is chosen.
	$('#publishing-action').on('click', '#publish', function (e) {
		if ($('input[name="sp_tpro_layout_options[layout]"]:checked').is(':disabled')) {
			$('input[name="sp_tpro_layout_options[layout]"][value="' + lastSelectedOption + '"]').prop('checked', true);
		}
	});

	$('.spt-live-demo-icon').on('click', function (event) {
		event.stopPropagation();
		// Add any additional code here if needed
	});

	/* Carousel Navigation - Select Position Preview */
	function navigationPositionPreview(selector, regex) {
		var str = "";
		$(selector + ' option:selected').each(function () {
			str = $(this).val();
			if (str != 'vertical_outer') {
				$('.sp_carousel-navigation-notice').show();
			} else {
				$('.sp_carousel-navigation-notice').hide();
			}
		});
		var src = $(selector + ' .spftestimonial-fieldset img').attr('src');
		var result = src.match(regex);
		if (result && result[1]) {
			src = src.replace(result[1], str);
			$(selector + ' .spftestimonial-fieldset img').attr('src', src);
		}
	}

	$('.spftestimonial-carousel-nav-position').on('change', function () {
		navigationPositionPreview(".spftestimonial-carousel-nav-position", /carousel-navigation\/(.+)\.svg/);
	});
	// Disable specific select options by their values
	var valuesToDisable = ['characters', 'words'];

	valuesToDisable.forEach(function (value) {
		$('.testimonial_text_limit .testimonial_length_type').find('select option[value="' + value + '"]').attr('disabled', 'disabled');
	});

})(jQuery, window, document);
