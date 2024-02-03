var $ = jQuery.noConflict();
// JavaScript Document
var bookmarkscroll;





// Merged JS Start
$(document).ready(function () {
	
	
	
	//************* easy-responsive-tabs.JS Start *************************
	(function ($) {
		$.fn.extend({
			easyResponsiveTabs: function (options) {
				//Set the default values, use comma to separate the settings, example:
				var defaults = {
					type: 'default', //default, vertical, accordion;
					width: 'auto',
					fit: true,
					closed: false,
					activate: function(){}
				}
				//Variables
				var options = $.extend(defaults, options);            
				var opt = options, jtype = opt.type, jfit = opt.fit, jwidth = opt.width, vtabs = 'vertical', accord = 'accordion';
	
				//Events
				$(this).bind('tabactivate', function(e, currentTab) {
					if(typeof options.activate === 'function') {
						options.activate.call(currentTab, e)
					}
				});
	
				//Main function
				this.each(function () {
					var $respTabs = $(this);
					var $respTabsList = $respTabs.find('ul.resp-tabs-list');
					$respTabs.find('ul.resp-tabs-list li').addClass('resp-tab-item');
					$respTabs.css({
						'display': 'block',
						'width': jwidth
					});
	
					$respTabs.find('.resp-tabs-container > div').addClass('resp-tab-content');
					jtab_options();
					//Properties Function
					function jtab_options() {
						if (jtype == vtabs) {
							$respTabs.addClass('resp-vtabs');
						}
						if (jfit == true) {
							$respTabs.css({ width: '100%', margin: '0px' });
						}
						if (jtype == accord) {
							$respTabs.addClass('resp-easy-accordion');
							$respTabs.find('.resp-tabs-list').css('display', 'none');
						}
					}
	
					//Assigning the h2 markup to accordion title
					var $tabItemh2;
					$respTabs.find('.resp-tab-content').before("<h2 class='resp-accordion' role='tab'><span class='resp-arrow'></span></h2>");
	
					var itemCount = 0;
					$respTabs.find('.resp-accordion').each(function () {
						$tabItemh2 = $(this);
						var innertext = $respTabs.find('.resp-tab-item:eq(' + itemCount + ')').html();
						$respTabs.find('.resp-accordion:eq(' + itemCount + ')').append(innertext);
						$tabItemh2.attr('aria-controls', 'tab_item-' + (itemCount));
						itemCount++;
					});
	
					//Assigning the 'aria-controls' to Tab items
					var count = 0,
						$tabContent;
					$respTabs.find('.resp-tab-item').each(function () {
						$tabItem = $(this);
						$tabItem.attr('aria-controls', 'tab_item-' + (count));
						$tabItem.attr('role', 'tab');
	
						//First active tab, keep closed if option = 'closed' or option is 'accordion' and the element is in accordion mode 
						if(options.closed !== true && !(options.closed === 'accordion' && !$respTabsList.is(':visible')) && !(options.closed === 'tabs' && $respTabsList.is(':visible'))) {                  
							$respTabs.find('.resp-tab-item').first().addClass('resp-tab-active');
							$respTabs.find('.resp-accordion').first().addClass('resp-tab-active');
							$respTabs.find('.resp-tab-content').first().addClass('resp-tab-content-active').attr('style', 'display:block');
						}
	
						//Assigning the 'aria-labelledby' attr to tab-content
						var tabcount = 0;
						$respTabs.find('.resp-tab-content').each(function () {
							$tabContent = $(this);
							$tabContent.attr('aria-labelledby', 'tab_item-' + (tabcount));
							tabcount++;
						});
						count++;
					});
	
					//Tab Click action function
					$respTabs.find("[role=tab]").each(function () {
						var $currentTab = $(this);
						$currentTab.click(function () {
	
							var $tabAria = $currentTab.attr('aria-controls');
	
							if ($currentTab.hasClass('resp-accordion') && $currentTab.hasClass('resp-tab-active')) {
								$respTabs.find('.resp-tab-content-active').slideUp('', function () { $(this).addClass('resp-accordion-closed'); });
								$currentTab.removeClass('resp-tab-active');
								return false;
							}
							if (!$currentTab.hasClass('resp-tab-active') && $currentTab.hasClass('resp-accordion')) {
								$respTabs.find('.resp-tab-active').removeClass('resp-tab-active');
								$respTabs.find('.resp-tab-content-active').slideUp().removeClass('resp-tab-content-active resp-accordion-closed');
								$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('resp-tab-active');
	
								$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').slideDown().addClass('resp-tab-content-active');
							} else {
								$respTabs.find('.resp-tab-active').removeClass('resp-tab-active');
								$respTabs.find('.resp-tab-content-active').removeAttr('style').removeClass('resp-tab-content-active').removeClass('resp-accordion-closed');
								$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('resp-tab-active');
								$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').addClass('resp-tab-content-active').attr('style', 'display:block');
							}
							//Trigger tab activation event
							$currentTab.trigger('tabactivate', $currentTab);
						});
						//Window resize function                   
						$(window).resize(function () {
							$respTabs.find('.resp-accordion-closed').removeAttr('style');
						});
					});
				});
			}
		});
	})(jQuery);
	//************* easy-responsive-tabs.JS End *************************
	
	
	



	//************* footable.JS Start *************************
	(function ($, w, undefined) {
		w.footable = {
			options: {
				delay: 100, // The number of millseconds to wait before triggering the react event
				breakpoints: { // The different screen resolution breakpoints
					phone: 740,
					tablet: 768
				},
				parsers: {  // The default parser to parse the value out of a cell (values are used in building up row detail)
					alpha: function (cell) {
						return $(cell).data('value') || $.trim($(cell).text());
					},
					numeric: function (cell) {
						var val = $(cell).data('value') || $(cell).text().replace(/[^0-9.\-]/g, '');
						val = parseFloat(val);
						if (isNaN(val)) val = 0;
						return val;
					}
				},
				addRowToggle: true,
				calculateWidthOverride: null,
				toggleSelector: ' > tbody > tr:not(.footable-row-detail)', //the selector to show/hide the detail row
				columnDataSelector: '> thead > tr:last-child > th, > thead > tr:last-child > td', //the selector used to find the column data in the thead
				detailSeparator: ':', //the seperator character used when building up the detail row
				createGroupedDetail: function (data) {
					var groups = { '_none': { 'name': null, 'data': [] } };
					for (var i = 0; i < data.length; i++) {
						var groupid = data[i].group;
						if (groupid !== null) {
							if (!(groupid in groups))
								groups[groupid] = { 'name': data[i].groupName || data[i].group, 'data': [] };
	
							groups[groupid].data.push(data[i]);
						} else {
							groups._none.data.push(data[i]);
						}
					}
					return groups;
				},
				createDetail: function (element, data, createGroupedDetail, separatorChar, classes) {
					/// <summary>This function is used by FooTable to generate the detail view seen when expanding a collapsed row.</summary>
					/// <param name="element">This is the div that contains all the detail row information, anything could be added to it.</param>
					/// <param name="data">
					///  This is an array of objects containing the cell information for the current row.
					///  These objects look like the below:
					///    obj = {
					///      'name': String, // The name of the column
					///      'value': Object, // The value parsed from the cell using the parsers. This could be a string, a number or whatever the parser outputs.
					///      'display': String, // This is the actual HTML from the cell, so if you have images etc you want moved this is the one to use and is the default value used.
					///      'group': String, // This is the identifier used in the data-group attribute of the column.
					///      'groupName': String // This is the actual name of the group the column belongs to.
					///    }
					/// </param>
					/// <param name="createGroupedDetail">The grouping function to group the data</param>
					/// <param name="separatorChar">The separator charactor used</param>
					/// <param name="classes">The array of class names used to build up the detail row</param>
	
					var groups = createGroupedDetail(data);
					for (var group in groups) {
						if (groups[group].data.length === 0) continue;
						if (group !== '_none') element.append('<div class="' + classes.detailInnerGroup + '">' + groups[group].name + '</div>');
	
						for (var j = 0; j < groups[group].data.length; j++) {
							var separator = (groups[group].data[j].name) ? separatorChar : '';
							element.append('<div class="' + classes.detailInnerRow + '"><div class="' + classes.detailInnerName + '">' + groups[group].data[j].name + separator + '</div><div class="' + classes.detailInnerValue + '">' + groups[group].data[j].display + '</div></div>');
						}
					}
				},
				classes: {
					main: 'footable',
					loading: 'footable-loading',
					loaded: 'footable-loaded',
					toggle: 'footable-toggle',
					disabled: 'footable-disabled',
					detail: 'footable-row-detail',
					detailCell: 'footable-row-detail-cell',
					detailInner: 'footable-row-detail-inner',
					detailInnerRow: 'footable-row-detail-row',
					detailInnerGroup: 'footable-row-detail-group',
					detailInnerName: 'footable-row-detail-name',
					detailInnerValue: 'footable-row-detail-value',
					detailShow: 'footable-detail-show'
				},
				triggers: {
					initialize: 'footable_initialize',                      //trigger this event to force FooTable to reinitialize
					resize: 'footable_resize',                              //trigger this event to force FooTable to resize
					redraw: 'footable_redraw',								//trigger this event to force FooTable to redraw
					toggleRow: 'footable_toggle_row',                       //trigger this event to force FooTable to toggle a row
					expandFirstRow: 'footable_expand_first_row'             //trigger this event to force FooTable to expand the first row
				},
				events: {
					alreadyInitialized: 'footable_already_initialized',     //fires when the FooTable has already been initialized
					initializing: 'footable_initializing',                  //fires before FooTable starts initializing
					initialized: 'footable_initialized',                    //fires after FooTable has finished initializing
					resizing: 'footable_resizing',                          //fires before FooTable resizes
					resized: 'footable_resized',                            //fires after FooTable has resized
					redrawn: 'footable_redrawn',                            //fires after FooTable has redrawn
					breakpoint: 'footable_breakpoint',                      //fires inside the resize function, when a breakpoint is hit
					columnData: 'footable_column_data',                     //fires when setting up column data. Plugins should use this event to capture their own info about a column
					rowDetailUpdating: 'footable_row_detail_updating',      //fires before a detail row is updated
					rowDetailUpdated: 'footable_row_detail_updated',        //fires when a detail row is being updated
					rowCollapsed: 'footable_row_collapsed',                 //fires when a row is collapsed
					rowExpanded: 'footable_row_expanded',                   //fires when a row is expanded
					rowRemoved: 'footable_row_removed'                      //fires when a row is removed
				},
				debug: false, // Whether or not to log information to the console.
				log: null
			},
	
			version: {
				major: 0, minor: 5,
				toString: function () {
					return w.footable.version.major + '.' + w.footable.version.minor;
				},
				parse: function (str) {
					version = /(\d+)\.?(\d+)?\.?(\d+)?/.exec(str);
					return {
						major: parseInt(version[1], 10) || 0,
						minor: parseInt(version[2], 10) || 0,
						patch: parseInt(version[3], 10) || 0
					};
				}
			},
	
			plugins: {
				_validate: function (plugin) {
					///<summary>Simple validation of the <paramref name="plugin"/> to make sure any members called by FooTable actually exist.</summary>
					///<param name="plugin">The object defining the plugin, this should implement a string property called "name" and a function called "init".</param>
	
					if (!$.isFunction(plugin)) {
					  if (w.footable.options.debug === true) console.error('Validation failed, expected type "function", received type "{0}".', typeof plugin);
					  return false;
					}
					var p = new plugin();
					if (typeof p['name'] !== 'string') {
						if (w.footable.options.debug === true) console.error('Validation failed, plugin does not implement a string property called "name".', p);
						return false;
					}
					if (!$.isFunction(p['init'])) {
						if (w.footable.options.debug === true) console.error('Validation failed, plugin "' + p['name'] + '" does not implement a function called "init".', p);
						return false;
					}
					if (w.footable.options.debug === true) console.log('Validation succeeded for plugin "' + p['name'] + '".', p);
					return true;
				},
				registered: [], // An array containing all registered plugins.
				register: function (plugin, options) {
					///<summary>Registers a <paramref name="plugin"/> and its default <paramref name="options"/> with FooTable.</summary>
					///<param name="plugin">The plugin that should implement a string property called "name" and a function called "init".</param>
					///<param name="options">The default options to merge with the FooTable's base options.</param>
	
					if (w.footable.plugins._validate(plugin)) {
						w.footable.plugins.registered.push(plugin);
						if (typeof options === 'object') $.extend(true, w.footable.options, options);
					}
				},
				load: function(instance){
				  var loaded = [], registered, i;
				  for(i = 0; i < w.footable.plugins.registered.length; i++){
					try {
					  registered = w.footable.plugins.registered[i];
					  loaded.push(new registered(instance));
					} catch (err) {
					  if (w.footable.options.debug === true) console.error(err);
					}
				  }
				  return loaded;
				},
				init: function (instance) {
					///<summary>Loops through all registered plugins and calls the "init" method supplying the current <paramref name="instance"/> of the FooTable as the first parameter.</summary>
					///<param name="instance">The current instance of the FooTable that the plugin is being initialized for.</param>
	
					for (var i = 0; i < instance.plugins.length; i++) {
						try {
						  instance.plugins[i]['init'](instance);
						} catch (err) {
							if (w.footable.options.debug === true) console.error(err);
						}
					}
				}
			}
		};
	
		var instanceCount = 0;
	
		$.fn.footable = function (options) {
			///<summary>The main constructor call to initialize the plugin using the supplied <paramref name="options"/>.</summary>
			///<param name="options">
			///<para>A JSON object containing user defined options for the plugin to use. Any options not supplied will have a default value assigned.</para>
			///<para>Check the documentation or the default options object above for more information on available options.</para>
			///</param>
	
			options = options || {};
			var o = $.extend(true, {}, w.footable.options, options); //merge user and default options
			return this.each(function () {
				instanceCount++;
				var footable = new Footable(this, o, instanceCount);
				$(this).data('footable', footable);
			});
		};
	
		//helper for using timeouts
		function Timer() {
			///<summary>Simple timer object created around a timeout.</summary>
			var t = this;
			t.id = null;
			t.busy = false;
			t.start = function (code, milliseconds) {
				///<summary>Starts the timer and waits the specified amount of <paramref name="milliseconds"/> before executing the supplied <paramref name="code"/>.</summary>
				///<param name="code">The code to execute once the timer runs out.</param>
				///<param name="milliseconds">The time in milliseconds to wait before executing the supplied <paramref name="code"/>.</param>
	
				if (t.busy) {
					return;
				}
				t.stop();
				t.id = setTimeout(function () {
					code();
					t.id = null;
					t.busy = false;
				}, milliseconds);
				t.busy = true;
			};
			t.stop = function () {
				///<summary>Stops the timer if its runnning and resets it back to its starting state.</summary>
	
				if (t.id !== null) {
					clearTimeout(t.id);
					t.id = null;
					t.busy = false;
				}
			};
		}
	
		function Footable(t, o, id) {
			///<summary>Inits a new instance of the plugin.</summary>
			///<param name="t">The main table element to apply this plugin to.</param>
			///<param name="o">The options supplied to the plugin. Check the defaults object to see all available options.</param>
			///<param name="id">The id to assign to this instance of the plugin.</param>
	
			var ft = this;
			ft.id = id;
			ft.table = t;
			ft.options = o;
			ft.breakpoints = [];
			ft.breakpointNames = '';
			ft.columns = {};
			ft.plugins = w.footable.plugins.load(ft);
	
			var opt = ft.options,
				cls = opt.classes,
				evt = opt.events,
				trg = opt.triggers,
				indexOffset = 0;
	
			// This object simply houses all the timers used in the FooTable.
			ft.timers = {
				resize: new Timer(),
				register: function (name) {
					ft.timers[name] = new Timer();
					return ft.timers[name];
				}
			};
	
			ft.init = function () {
				var $window = $(w), $table = $(ft.table);
	
				w.footable.plugins.init(ft);
	
				if ($table.hasClass(cls.loaded)) {
					//already loaded FooTable for the table, so don't init again
					ft.raise(evt.alreadyInitialized);
					return;
				}
	
				//raise the initializing event
				ft.raise(evt.initializing);
	
				$table.addClass(cls.loading);
	
				// Get the column data once for the life time of the plugin
				$table.find(opt.columnDataSelector).each(function () {
					var data = ft.getColumnData(this);
					ft.columns[data.index] = data;
				});
	
				// Create a nice friendly array to work with out of the breakpoints object.
				for (var name in opt.breakpoints) {
					ft.breakpoints.push({ 'name': name, 'width': opt.breakpoints[name] });
					ft.breakpointNames += (name + ' ');
				}
	
				// Sort the breakpoints so the smallest is checked first
				ft.breakpoints.sort(function (a, b) {
					return a['width'] - b['width'];
				});
	
				$table
					//bind to FooTable initialize trigger
					.bind(trg.initialize, function () {
						//remove previous "state" (to "force" a resize)
						$table.removeData('footable_info');
						$table.data('breakpoint', '');
	
						//trigger the FooTable resize
						$table.trigger(trg.resize);
	
						//remove the loading class
						$table.removeClass(cls.loading);
	
						//add the FooTable and loaded class
						$table.addClass(cls.loaded).addClass(cls.main);
	
						//raise the initialized event
						ft.raise(evt.initialized);
					})
					//bind to FooTable redraw trigger
					.bind(trg.redraw, function () {
						ft.redraw();
					})
	
					//bind to FooTable resize trigger
					.bind(trg.resize, function () {
						ft.resize();
					})
					//bind to FooTable expandFirstRow trigger
					.bind(trg.expandFirstRow, function () {
						$table.find(opt.toggleSelector).first().not('.' + cls.detailShow).trigger(trg.toggleRow);
					});
	
				//trigger a FooTable initialize
				$table.trigger(trg.initialize);
	
				//bind to window resize
				$window
					.bind('resize.footable', function () {
						ft.timers.resize.stop();
						ft.timers.resize.start(function () {
							ft.raise(trg.resize);
						}, opt.delay);
					});
			};
	
			ft.addRowToggle = function () {
				if (!opt.addRowToggle) return;
	
				var $table = $(ft.table),
					hasToggleColumn = false;
	
				//first remove all toggle spans
				$table.find('span.' + cls.toggle).remove();
	
				for (var c in ft.columns) {
					var col = ft.columns[c];
					if (col.toggle) {
						hasToggleColumn = true;
						var selector = '> tbody > tr:not(.' + cls.detail + ',.' + cls.disabled + ') > td:nth-child(' + (parseInt(col.index, 10) + 1) + ')';
						$table.find(selector).not('.' + cls.detailCell).prepend($('<span />').addClass(cls.toggle));
						return;
					}
				}
				//check if we have an toggle column. If not then add it to the first column just to be safe
				if (!hasToggleColumn) {
					$table
						.find('> tbody > tr:not(.' + cls.detail + ',.' + cls.disabled + ') > td:first-child')
						.not('.' + cls.detailCell)
						.prepend($('<span />').addClass(cls.toggle));
				}
			};
	
			ft.setColumnClasses = function () {
				$table = $(ft.table);
				for (var c in ft.columns) {
					var col = ft.columns[c];
					if (col.className !== null) {
						var selector = '', first = true;
						$.each(col.matches, function (m, match) { //support for colspans
							if (!first) selector += ', ';
							selector += '> tbody > tr:not(.' + cls.detail + ') > td:nth-child(' + (parseInt(match, 10) + 1) + ')';
							first = false;
						});
						//add the className to the cells specified by data-class="blah"
						$table.find(selector).not('.' + cls.detailCell).addClass(col.className);
					}
				}
			};
	
			//moved this out into it's own function so that it can be called from other add-ons
			ft.bindToggleSelectors = function () {
				var $table = $(ft.table);
	
				if (!ft.hasAnyBreakpointColumn()) return;
	
				$table.find(opt.toggleSelector).unbind(trg.toggleRow).bind(trg.toggleRow, function (e) {
					var $row = $(this).is('tr') ? $(this) : $(this).parents('tr:first');
					ft.toggleDetail($row.get(0));
				});
	
				$table.find(opt.toggleSelector).unbind('click.footable').bind('click.footable', function (e) {
					if ($table.is('.breakpoint') && $(e.target).is('td,.'+ cls.toggle)) {
						$(this).trigger(trg.toggleRow);
					}
				});
			};
	
			ft.parse = function (cell, column) {
				var parser = opt.parsers[column.type] || opt.parsers.alpha;
				return parser(cell);
			};
	
			ft.getColumnData = function (th) {
				var $th = $(th), hide = $th.data('hide'), index = $th.index();
				hide = hide || '';
				hide = jQuery.map(hide.split(','), function (a) {
					return jQuery.trim(a);
				});
				var data = {
					'index': index,
					'hide': { },
					'type': $th.data('type') || 'alpha',
					'name': $th.data('name') || $.trim($th.text()),
					'ignore': $th.data('ignore') || false,
					'toggle': $th.data('toggle') || false,
					'className': $th.data('class') || null,
					'matches': [],
					'names': { },
					'group': $th.data('group') || null,
					'groupName': null
				};
	
				if (data.group !== null) {
					var $group = $(ft.table).find('> thead > tr.footable-group-row > th[data-group="' + data.group + '"], > thead > tr.footable-group-row > td[data-group="' + data.group + '"]').first();
					data.groupName = ft.parse($group, { 'type': 'alpha' });
				}
	
				var pcolspan = parseInt($th.prev().attr('colspan') || 0, 10);
				indexOffset += pcolspan > 1 ? pcolspan - 1 : 0;
				var colspan = parseInt($th.attr('colspan') || 0, 10), curindex = data.index + indexOffset;
				if (colspan > 1) {
					var names = $th.data('names');
					names = names || '';
					names = names.split(',');
					for (var i = 0; i < colspan; i++) {
						data.matches.push(i + curindex);
						if (i < names.length) data.names[i + curindex] = names[i];
					}
				} else {
					data.matches.push(curindex);
				}
	
				data.hide['default'] = ($th.data('hide') === "all") || ($.inArray('default', hide) >= 0);
	
				var hasBreakpoint = false;
				for (var name in opt.breakpoints) {
					data.hide[name] = ($th.data('hide') === "all") || ($.inArray(name, hide) >= 0);
					hasBreakpoint = hasBreakpoint || data.hide[name];
				}
				data.hasBreakpoint = hasBreakpoint;
				var e = ft.raise(evt.columnData, { 'column': { 'data': data, 'th': th } });
				return e.column.data;
			};
	
			ft.getViewportWidth = function () {
				return window.innerWidth || (document.body ? document.body.offsetWidth : 0);
			};
	
			ft.calculateWidth = function ($table, info) {
				if (jQuery.isFunction(opt.calculateWidthOverride)) {
					return opt.calculateWidthOverride($table, info);
				}
				if (info.viewportWidth < info.width) info.width = info.viewportWidth;
				if (info.parentWidth < info.width) info.width = info.parentWidth;
				return info;
			};
	
			ft.hasBreakpointColumn = function (breakpoint) {
				for (var c in ft.columns) {
					if (ft.columns[c].hide[breakpoint]) {
						if (ft.columns[c].ignore) {
							continue;
						}
						return true;
					}
				}
				return false;
			};
	
			ft.hasAnyBreakpointColumn = function () {
				for (var c in ft.columns) {
					if (ft.columns[c].hasBreakpoint) {
						return true;
					}
				}
				return false;
			};
	
			ft.resize = function () {
				var $table = $(ft.table);
	
				if (!$table.is(':visible')) {
					return;
				} //we only care about FooTables that are visible
	
				if (!ft.hasAnyBreakpointColumn()) {
					return;
				} //we only care about FooTables that have breakpoints
	
				var info = {
					'width': $table.width(),                  //the table width
					'viewportWidth': ft.getViewportWidth(),   //the width of the viewport
					'parentWidth': $table.parent().width()    //the width of the parent
				};
	
				info = ft.calculateWidth($table, info);
	
				var pinfo = $table.data('footable_info');
				$table.data('footable_info', info);
				ft.raise(evt.resizing, { 'old': pinfo, 'info': info });
	
				// This (if) statement is here purely to make sure events aren't raised twice as mobile safari seems to do
				if (!pinfo || (pinfo && pinfo.width && pinfo.width !== info.width)) {
	
					var current = null, breakpoint;
					for (var i = 0; i < ft.breakpoints.length; i++) {
						breakpoint = ft.breakpoints[i];
						if (breakpoint && breakpoint.width && info.width <= breakpoint.width) {
							current = breakpoint;
							break;
						}
					}
	
					var breakpointName = (current === null ? 'default' : current['name']),
						hasBreakpointFired = ft.hasBreakpointColumn(breakpointName),
						previousBreakpoint = $table.data('breakpoint');
	
					$table
						.data('breakpoint', breakpointName)
						.removeClass('default breakpoint').removeClass(ft.breakpointNames)
						.addClass(breakpointName + (hasBreakpointFired ? ' breakpoint' : ''));
	
					//only do something if the breakpoint has changed
					if (breakpointName !== previousBreakpoint) {
						//trigger a redraw
						$table.trigger(trg.redraw);
						//raise a breakpoint event
						ft.raise(evt.breakpoint, { 'breakpoint': breakpointName, 'info': info });
					}
				}
	
				ft.raise(evt.resized, { 'old': pinfo, 'info': info });
			};
	
			ft.redraw = function () {
				//add the toggler to each row
				ft.addRowToggle();
	
				//bind the toggle selector click events
				ft.bindToggleSelectors();
	
				//set any cell classes defined for the columns
				ft.setColumnClasses();
	
				var $table = $(ft.table),
					breakpointName = $table.data('breakpoint'),
					hasBreakpointFired = ft.hasBreakpointColumn(breakpointName);
	
				$table
					.find('> tbody > tr:not(.' + cls.detail + ')').data('detail_created', false).end()
					.find('> thead > tr:last-child > th')
					.each(function () {
						var data = ft.columns[$(this).index()], selector = '', first = true;
						$.each(data.matches, function (m, match) {
							if (!first) {
								selector += ', ';
							}
							var count = match + 1;
							selector += '> tbody > tr:not(.' + cls.detail + ') > td:nth-child(' + count + ')';
							selector += ', > tfoot > tr:not(.' + cls.detail + ') > td:nth-child(' + count + ')';
							selector += ', > colgroup > col:nth-child(' + count + ')';
							first = false;
						});
	
						selector += ', > thead > tr[data-group-row="true"] > th[data-group="' + data.group + '"]';
						var $column = $table.find(selector).add(this);
						if (data.hide[breakpointName] === false) $column.show();
						else $column.hide();
	
						if ($table.find('> thead > tr.footable-group-row').length === 1) {
							var $groupcols = $table.find('> thead > tr:last-child > th[data-group="' + data.group + '"]:visible, > thead > tr:last-child > th[data-group="' + data.group + '"]:visible'),
								$group = $table.find('> thead > tr.footable-group-row > th[data-group="' + data.group + '"], > thead > tr.footable-group-row > td[data-group="' + data.group + '"]'),
								groupspan = 0;
	
							$.each($groupcols, function () {
								groupspan += parseInt($(this).attr('colspan') || 1, 10);
							});
	
							if (groupspan > 0) $group.attr('colspan', groupspan).show();
							else $group.hide();
						}
					})
					.end()
					.find('> tbody > tr.' + cls.detailShow).each(function () {
						ft.createOrUpdateDetailRow(this);
					});
	
				$table.find('> tbody > tr.' + cls.detailShow + ':visible').each(function () {
					var $next = $(this).next();
					if ($next.hasClass(cls.detail)) {
						if (!hasBreakpointFired) $next.hide();
						else $next.show();
					}
				});
	
				// adding .footable-first-column and .footable-last-column to the first and last th and td of each row in order to allow
				// for styling if the first or last column is hidden (which won't work using :first-child or :last-child)
				$table.find('> thead > tr > th.footable-last-column, > tbody > tr > td.footable-last-column').removeClass('footable-last-column');
				$table.find('> thead > tr > th.footable-first-column, > tbody > tr > td.footable-first-column').removeClass('footable-first-column');
				$table.find('> thead > tr, > tbody > tr')
					.find('> th:visible:last, > td:visible:last')
					.addClass('footable-last-column')
					.end()
					.find('> th:visible:first, > td:visible:first')
					.addClass('footable-first-column');
	
				ft.raise(evt.redrawn);
			};
	
			ft.toggleDetail = function (row) {
				var $row = (row.jquery) ? row : $(row),
					$next = $row.next();
	
				//check if the row is already expanded
				if ($row.hasClass(cls.detailShow)) {
					$row.removeClass(cls.detailShow);
	
					//only hide the next row if it's a detail row
					if ($next.hasClass(cls.detail)) $next.hide();
	
					ft.raise(evt.rowCollapsed, { 'row': $row[0] });
	
				} else {
					ft.createOrUpdateDetailRow($row[0]);
					$row.addClass(cls.detailShow);
					$row.next().show();
	
					ft.raise(evt.rowExpanded, { 'row': $row[0] });
				}
			};
	
			ft.removeRow = function (row) {
				var $row = (row.jquery) ? row : $(row);
				if ($row.hasClass(cls.detail)) {
					$row = $row.prev();
				}
				var $next = $row.next();
				if ($row.data('detail_created') === true) {
					//remove the detail row
					$next.remove();
				}
				$row.remove();
	
				//raise event
				ft.raise(evt.rowRemoved);
			};
	
			ft.appendRow = function (row) {
				var $row = (row.jquery) ? row : $(row);
				$(ft.table).find('tbody').append($row);
	
				//redraw the table
				ft.redraw();
			};
	
			ft.getColumnFromTdIndex = function (index) {
				/// <summary>Returns the correct column data for the supplied index taking into account colspans.</summary>
				/// <param name="index">The index to retrieve the column data for.</param>
				/// <returns type="json">A JSON object containing the column data for the supplied index.</returns>
				var result = null;
				for (var column in ft.columns) {
					if ($.inArray(index, ft.columns[column].matches) >= 0) {
						result = ft.columns[column];
						break;
					}
				}
				return result;
			};
	
			ft.createOrUpdateDetailRow = function (actualRow) {
				var $row = $(actualRow), $next = $row.next(), $detail, values = [];
				if ($row.data('detail_created') === true) return true;
	
				if ($row.is(':hidden')) return false; //if the row is hidden for some reason (perhaps filtered) then get out of here
				ft.raise(evt.rowDetailUpdating, { 'row': $row, 'detail': $next });
				$row.find('> td:hidden').each(function () {
					var index = $(this).index(), column = ft.getColumnFromTdIndex(index), name = column.name;
					if (column.ignore === true) return true;
	
					if (index in column.names) name = column.names[index];
					values.push({ 'name': name, 'value': ft.parse(this, column), 'display': $.trim($(this).html()), 'group': column.group, 'groupName': column.groupName });
					return true;
				});
				if (values.length === 0) return false; //return if we don't have any data to show
				var colspan = $row.find('> td:visible').length;
				var exists = $next.hasClass(cls.detail);
				if (!exists) { // Create
					$next = $('<tr class="' + cls.detail + '"><td class="' + cls.detailCell + '"><div class="' + cls.detailInner + '"></div></td></tr>');
					$row.after($next);
				}
				$next.find('> td:first').attr('colspan', colspan);
				$detail = $next.find('.' + cls.detailInner).empty();
				opt.createDetail($detail, values, opt.createGroupedDetail, opt.detailSeparator, cls);
				$row.data('detail_created', true);
				ft.raise(evt.rowDetailUpdated, { 'row': $row, 'detail': $next });
				return !exists;
			};
	
			ft.raise = function (eventName, args) {
	
				if (ft.options.debug === true && $.isFunction(ft.options.log)) ft.options.log(eventName, 'event');
	
				args = args || { };
				var def = { 'ft': ft };
				$.extend(true, def, args);
				var e = $.Event(eventName, def);
				if (!e.ft) {
					$.extend(true, e, def);
				} //pre jQuery 1.6 which did not allow data to be passed to event object constructor
				$(ft.table).trigger(e);
				return e;
			};
	
			ft.init();
			return ft;
		}
	})(jQuery, window);
	//************* footable.JS End ***************************

	
	
	
	
	//************* footable.paginate.JS Start ***************************
	(function ($, w, undefined) {
		if (w.footable === undefined || w.footable === null)
			throw new Error('Please check and make sure footable.js is included in the page and is loaded prior to this script.');
	
		var defaults = {
			paginate: true,
			pageSize: 10,
			pageNavigation: '.pagination',
			//firstText: '&lsaquo;',
			previousText: '&lsaquo;',
			nextText: '&rsaquo;',
			//lastText: '&rsaquo;'
		};
	
		function pageInfo(ft) {
			var $table = $(ft.table), $tbody = $table.find('> tbody');
			this.pageNavigation = $table.data('page-navigation') || ft.options.pageNavigation;
			this.pageSize = $table.data('page-size') || ft.options.pageSize;
			this.firstText = $table.data('page-first-text') || ft.options.firstText;
			this.previousText = $table.data('page-previous-text') || ft.options.previousText;
			this.nextText = $table.data('page-next-text') || ft.options.nextText;
			this.lastText = $table.data('page-last-text') || ft.options.lastText;
			this.currentPage = 0;
			this.pages = [];
			this.control = false;
		}
	
		function Paginate() {
			var p = this;
			p.name = 'Footable Paginate';
	
			p.init = function (ft) {
				if (ft.options.paginate === true) {
					if ($(ft.table).data('page') === false) return;
					$(ft.table).bind({
						'footable_initialized': function () {
							ft.pageInfo = new pageInfo(ft);
							ft.raise('footable_setup_paging');
						},
						'footable_row_removed footable_redrawn footable_sorted footable_filtered footable_setup_paging': function () {
							if (ft.pageInfo) {
								p.setupPaging(ft);
							}
						}
					});
				}
			};
	
			p.setupPaging = function(ft) {
				var $tbody = $(ft.table).find('> tbody');
				p.createPages(ft, $tbody);
				p.createNavigation(ft, $tbody);
				p.fillPage(ft, $tbody, ft.pageInfo.currentPage);
			};
	
			p.createPages = function (ft, tbody) {
				var pages = 1;
				var info = ft.pageInfo;
				var pageCount = pages * info.pageSize;
				var page = [];
				var lastPage = [];
				info.pages = [];
				var rows = tbody.find('> tr:not(.footable-filtered,.footable-row-detail)');
				rows.each(function (i, row) {
					page.push(row);
					if (i === pageCount - 1) {
						info.pages.push(page);
						pages++;
						pageCount = pages * info.pageSize;
						page = [];
					} else if (i >= rows.length - (rows.length % info.pageSize)) {
						lastPage.push(row);
					}
				});
				if (lastPage.length > 0) info.pages.push(lastPage);
				if (info.currentPage >= info.pages.length) info.currentPage = info.pages.length - 1;
				if (info.currentPage < 0) info.currentPage = 0;
				if (info.pages.length === 1) {
					//we only have a single page
					$(ft.table).addClass('no-paging');
				} else {
					$(ft.table).removeClass('no-paging');
				}
			};
	
			p.createNavigation = function (ft, tbody) {
				var $nav = $(ft.table).find(ft.pageInfo.pageNavigation);
				//if we cannot find the navigation control within the table, then try find it outside
				if ($nav.length === 0) {
					$nav = $(ft.pageInfo.pageNavigation);
					//if the navigation control is inside another table, then get out
					if ($nav.parents('table:first') !== $(ft.table)) return;
					//if we found more than one navigation control, write error to console
					if ($nav.length > 1 && ft.options.debug === true) console.error('More than one pagination control was found!');
				}
				//if we still cannot find the control, then don't do anything
				if ($nav.length === 0) return;
				//if the nav is not a UL, then find or create a UL
				if (!$nav.is('ul')) {
					if ($nav.find('ul:first').length === 0) { $nav.append('<ul />'); }
					$nav = $nav.find('ul');
				}
				$nav.find('li').remove();
				var info = ft.pageInfo;
				info.control = $nav;
				if (info.pages.length > 0) {
					//$nav.append('<li class="footable-page-arrow"><a data-page="first" href="#first" class="first">'+ft.pageInfo.firstText+'</a>');
					$nav.append('<li class="footable-page-arrow"><a data-page="prev" href="#prev" class="prev">'+'<i class="fa fa-angle-left" aria-hidden="true"></i>'+'</a></li>');
					$.each(info.pages, function (i, page) {
						if (page.length > 0) {
							$nav.append('<li class="footable-page"><a class="page_number" data-page="' + i + '" href="#">' + (i + 1) + '</a></li>');
						}
					});
					$nav.append('<li class="footable-page-arrow"><a data-page="next" href="#next" class="next">'+'<i class="fa fa-angle-right" aria-hidden="true"></i>'+'</a></li>');
				   //$nav.append('<li class="footable-page-arrow"><a data-page="last" href="#last" class="last">'+ft.pageInfo.lastText+'</a></li>');
				}
				$nav.find('a').click(function (e) {
					e.preventDefault();
					var page = $(this).data('page');
					var newPage = info.currentPage;
					if (page === 'first') {
						newPage = 0;
					} else if (page === 'prev') {
						if (newPage > 0) newPage--;
					} else if (page === 'next') {
						if (newPage < info.pages.length - 1) newPage++;
					} else if (page === 'last') {
						newPage = info.pages.length - 1;
					} else {
						newPage = page;
					}
					p.paginate(ft, newPage);
				});
				p.setPagingClasses($nav, info.currentPage, info.pages.length);
			};
	
			p.paginate = function (ft, newPage) {
				var info = ft.pageInfo;
				if (info.currentPage !== newPage) {
					var $tbody = $(ft.table).find('> tbody');
	
					//raise a pre-pagin event so that we can cancel the paging if needed
					var event = ft.raise('footable_paging', { page: newPage, size: info.pageSize });
					if (event && event.result === false) return;
	
					p.fillPage(ft, $tbody, newPage);
					info.control.find('li').removeClass('active disabled');
					p.setPagingClasses(info.control, info.currentPage, info.pages.length);
				}
			};
	
			p.setPagingClasses = function(nav, currentPage, pageCount) {
				nav.find('li.footable-page > a[data-page=' + currentPage + ']').parent().addClass('active');
				if (currentPage >= pageCount - 1) {
					nav.find('li.footable-page-arrow > a[data-page="next"]').parent().addClass('disabled');
					nav.find('li.footable-page-arrow > a[data-page="last"]').parent().addClass('disabled');
				}
				if (currentPage < 1) {
					nav.find('li.footable-page-arrow > a[data-page="first"]').parent().addClass('disabled');
					nav.find('li.footable-page-arrow > a[data-page="prev"]').parent().addClass('disabled');
				}
			};
	
			p.fillPage = function (ft, tbody, pageNumber) {
				ft.pageInfo.currentPage = pageNumber;
				tbody.find('> tr').hide();
				$(ft.pageInfo.pages[pageNumber]).each(function () {
					p.showRow(this, ft);
				});
			};
	
			p.showRow = function (row, ft) {
				var $row = $(row), $next = $row.next(), $table = $(ft.table);
				if ($table.hasClass('breakpoint') && $row.hasClass('footable-detail-show') && $next.hasClass('footable-row-detail')) {
					$row.add($next).show();
					ft.createOrUpdateDetailRow(row);
				}
				else $row.show();
			};
		}
	
		w.footable.plugins.register(Paginate, defaults);
	
	})(jQuery, window);
	//************* footable.paginate.JS End *****************************

	
    //************* footable.filter.JS Start ***************************
	(function ($, w, undefined) {
		if (w.footable === undefined || w.footable === null)
			throw new Error('Please check and make sure footable.js is included in the page and is loaded prior to this script.');
	
		var defaults = {
			filter: {
				enabled: true,
				input: '.footable-filter',
				timeout: 300,
				minimum: 1,
				disableEnter: false,
				filterFunction: function(index) {
					var $t = $(this),
						$table = $t.parents('table:first'),
						filter = $table.data('current-filter').toUpperCase(),
						text = $t.find('td').text();
					if (!$table.data('filter-text-only')) {
						$t.find('td[data-value]').each(function () {
							text += $(this).data('value');
						});
					}
					return text.toUpperCase().indexOf(filter) >= 0;
				}
			}
		};
	
		function Filter() {
			var p = this;
			p.name = 'Footable Filter';
			p.init = function (ft) {
				p.footable = ft;
				if (ft.options.filter.enabled === true) {
					if ($(ft.table).data('filter') === false) return;
					ft.timers.register('filter');
					$(ft.table).bind({
						'footable_initialized': function (e) {
							var $table = $(ft.table);
							var data = {
								'input': $table.data('filter') || ft.options.filter.input,
								'timeout': $table.data('filter-timeout') || ft.options.filter.timeout,
								'minimum': $table.data('filter-minimum') || ft.options.filter.minimum,
								'disableEnter': $table.data('filter-disable-enter') || ft.options.filter.disableEnter
							};
							if (data.disableEnter) {
								$(data.input).keypress(function (event) {
									if (window.event)
										return (window.event.keyCode !== 13);
									else
										return (event.which !== 13);
								});
							}
							$table.bind('footable_clear_filter', function () {
								$(data.input).val('');
								p.clearFilter();
							});
							$table.bind('footable_filter', function (event, args) {
								p.filter(args.filter);
							});
							$(data.input).keyup(function (eve) {
								ft.timers.filter.stop();
								if (eve.which === 27) {
									$(data.input).val('');
								}
								ft.timers.filter.start(function () {
									var val = $(data.input).val() || '';
									p.filter(val);
								}, data.timeout);
							});
						},
						'footable_redrawn': function (e) {
							var $table = $(ft.table),
								filter = $table.data('filter-string');
							if (filter) {
								p.filter(filter);
							}
						}
					})
					//save the filter object onto the table so we can access it later
					.data('footable-filter', p);
				}
			};
	
			p.filter = function (filterString) {
				var ft = p.footable,
					$table = $(ft.table),
					minimum = $table.data('filter-minimum') || ft.options.filter.minimum,
					clear = !filterString;
	
				//raise a pre-filter event so that we can cancel the filtering if needed
				var event = ft.raise('footable_filtering', { filter: filterString, clear: clear });
				if (event && event.result === false) return;
				if (event.filter && event.filter.length < minimum) {
				  return; //if we do not have the minimum chars then do nothing
				}
	
			  if (event.clear) {
					p.clearFilter();
				  $("#filter-not-found").hide();
				} else {
					var filters = event.filter.split(' ');
	
					$table.find('> tbody > tr').hide().addClass('footable-filtered');
					var rows = $table.find('> tbody > tr:not(.footable-row-detail)');
					$.each(filters, function (i, f) {
						if (f && f.length > 0) {
							$table.data('current-filter', f);
							rows = rows.filter(ft.options.filter.filterFunction);
						}
					});
					if( rows.length > 0 ){
						$("#filter-not-found").hide();
					}
					else
					{
						$("#filter-not-found").show();
					}

					rows.each(function () {
						p.showRow(this, ft);
						$(this).removeClass('footable-filtered');
					});

					$table.data('filter-string', event.filter);
					ft.raise('footable_filtered', { filter: event.filter, clear: false });
				}
			};
	
			p.clearFilter = function () {
				var ft = p.footable,
					$table = $(ft.table);
	
				$table.find('> tbody > tr:not(.footable-row-detail)').removeClass('footable-filtered').each(function () {
					p.showRow(this, ft);
				});
				$table.removeData('filter-string');
				ft.raise('footable_filtered', { clear: true });
			};
	
			p.showRow = function (row, ft) {
				var $row = $(row), $next = $row.next(), $table = $(ft.table);
				if ($row.is(':visible')) return; //already visible - do nothing
				if ($table.hasClass('breakpoint') && $row.hasClass('footable-detail-show') && $next.hasClass('footable-row-detail')) {
					$row.add($next).show();
					ft.createOrUpdateDetailRow(row);
				}
				else $row.show();
			};
		}
	
		w.footable.plugins.register(Filter, defaults);
	
	})(jQuery, window);
	//************* footable.filter.JS Start ***************************
	
	
	
	//************* icheck.JS Start *************************
	(function($) {

	  // Cached vars
	  var _iCheck = 'iCheck',
		_iCheckHelper = _iCheck + '-helper',
		_checkbox = 'checkbox',
		_radio = 'radio',
		_checked = 'checked',
		_unchecked = 'un' + _checked,
		_disabled = 'disabled',
		_determinate = 'determinate',
		_indeterminate = 'in' + _determinate,
		_update = 'update',
		_type = 'type',
		_click = 'click',
		_touch = 'touchbegin.i touchend.i',
		_add = 'addClass',
		_remove = 'removeClass',
		_callback = 'trigger',
		_label = 'label',
		_cursor = 'cursor',
		_mobile = /ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent);
	
	  // Plugin init
	  $.fn[_iCheck] = function(options, fire) {
	
		// Walker
		var handle = 'input[type="' + _checkbox + '"], input[type="' + _radio + '"]',
		  stack = $(),
		  walker = function(object) {
			object.each(function() {
			  var self = $(this);
	
			  if (self.is(handle)) {
				stack = stack.add(self);
			  } else {
				stack = stack.add(self.find(handle));
			  }
			});
		  };
	
		// Check if we should operate with some method
		if (/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(options)) {
	
		  // Normalize method's name
		  options = options.toLowerCase();
	
		  // Find checkboxes and radio buttons
		  walker(this);
	
		  return stack.each(function() {
			var self = $(this);
	
			if (options == 'destroy') {
			  tidy(self, 'ifDestroyed');
			} else {
			  operate(self, true, options);
			}
	
			// Fire method's callback
			if ($.isFunction(fire)) {
			  fire();
			}
		  });
	
		// Customization
		} else if (typeof options == 'object' || !options) {
	
		  // Check if any options were passed
		  var settings = $.extend({
			  checkedClass: _checked,
			  disabledClass: _disabled,
			  indeterminateClass: _indeterminate,
			  labelHover: true
			}, options),
	
			selector = settings.handle,
			hoverClass = settings.hoverClass || 'hover',
			focusClass = settings.focusClass || 'focus',
			activeClass = settings.activeClass || 'active',
			labelHover = !!settings.labelHover,
			labelHoverClass = settings.labelHoverClass || 'hover',
	
			// Setup clickable area
			area = ('' + settings.increaseArea).replace('%', '') | 0;
	
		  // Selector limit
		  if (selector == _checkbox || selector == _radio) {
			handle = 'input[type="' + selector + '"]';
		  }
	
		  // Clickable area limit
		  if (area < -50) {
			area = -50;
		  }
	
		  // Walk around the selector
		  walker(this);
	
		  return stack.each(function() {
			var self = $(this);
	
			// If already customized
			tidy(self);
	
			var node = this,
			  id = node.id,
	
			  // Layer styles
			  offset = -area + '%',
			  size = 100 + (area * 2) + '%',
			  layer = {
				position: 'absolute',
				top: offset,
				left: offset,
				display: 'block',
				width: size,
				height: size,
				margin: 0,
				padding: 0,
				background: '#fff',
				border: 0,
				opacity: 0
			  },
	
			  // Choose how to hide input
			  hide = _mobile ? {
				position: 'absolute',
				visibility: 'hidden'
			  } : area ? layer : {
				position: 'absolute',
				opacity: 0
			  },
	
			  // Get proper class
			  className = node[_type] == _checkbox ? settings.checkboxClass || 'i' + _checkbox : settings.radioClass || 'i' + _radio,
	
			  // Find assigned labels
			  label = $(_label + '[for="' + id + '"]').add(self.closest(_label)),
	
			  // Check ARIA option
			  aria = !!settings.aria,
	
			  // Set ARIA placeholder
			  ariaID = _iCheck + '-' + Math.random().toString(36).substr(2,6),
	
			  // Parent & helper
			  parent = '<div class="' + className + '" ' + (aria ? 'role="' + node[_type] + '" ' : ''),
			  helper;
	
			// Set ARIA "labelledby"
			if (aria) {
			  label.each(function() {
				parent += 'aria-labelledby="';
	
				if (this.id) {
				  parent += this.id;
				} else {
				  this.id = ariaID;
				  parent += ariaID;
				}
	
				parent += '"';
			  });
			}
	
			// Wrap input
			parent = self.wrap(parent + '/>')[_callback]('ifCreated').parent().append(settings.insert);
	
			// Layer addition
			helper = $('<ins class="' + _iCheckHelper + '"/>').css(layer).appendTo(parent);
	
			// Finalize customization
			self.data(_iCheck, {o: settings, s: self.attr('style')}).css(hide);
			!!settings.inheritClass && parent[_add](node.className || '');
			!!settings.inheritID && id && parent.attr('id', _iCheck + '-' + id);
			parent.css('position') == 'static' && parent.css('position', 'relative');
			operate(self, true, _update);
	
			// Label events
			if (label.length) {
			  label.on(_click + '.i mouseover.i mouseout.i ' + _touch, function(event) {
				var type = event[_type],
				  item = $(this);
	
				// Do nothing if input is disabled
				if (!node[_disabled]) {
	
				  // Click
				  if (type == _click) {
					if ($(event.target).is('a')) {
					  return;
					}
					operate(self, false, true);
	
				  // Hover state
				  } else if (labelHover) {
	
					// mouseout|touchend
					if (/ut|nd/.test(type)) {
					  parent[_remove](hoverClass);
					  item[_remove](labelHoverClass);
					} else {
					  parent[_add](hoverClass);
					  item[_add](labelHoverClass);
					}
				  }
	
				  if (_mobile) {
					event.stopPropagation();
				  } else {
					return false;
				  }
				}
			  });
			}
	
			// Input events
			self.on(_click + '.i focus.i blur.i keyup.i keydown.i keypress.i', function(event) {
			  var type = event[_type],
				key = event.keyCode;
	
			  // Click
			  if (type == _click) {
				return false;
	
			  // Keydown
			  } else if (type == 'keydown' && key == 32) {
				if (!(node[_type] == _radio && node[_checked])) {
				  if (node[_checked]) {
					off(self, _checked);
				  } else {
					on(self, _checked);
				  }
				}
	
				return false;
	
			  // Keyup
			  } else if (type == 'keyup' && node[_type] == _radio) {
				!node[_checked] && on(self, _checked);
	
			  // Focus/blur
			  } else if (/us|ur/.test(type)) {
				parent[type == 'blur' ? _remove : _add](focusClass);
			  }
			});
	
			// Helper events
			helper.on(_click + ' mousedown mouseup mouseover mouseout ' + _touch, function(event) {
			  var type = event[_type],
	
				// mousedown|mouseup
				toggle = /wn|up/.test(type) ? activeClass : hoverClass;
	
			  // Do nothing if input is disabled
			  if (!node[_disabled]) {
	
				// Click
				if (type == _click) {
				  operate(self, false, true);
	
				// Active and hover states
				} else {
	
				  // State is on
				  if (/wn|er|in/.test(type)) {
	
					// mousedown|mouseover|touchbegin
					parent[_add](toggle);
	
				  // State is off
				  } else {
					parent[_remove](toggle + ' ' + activeClass);
				  }
	
				  // Label hover
				  if (label.length && labelHover && toggle == hoverClass) {
	
					// mouseout|touchend
					label[/ut|nd/.test(type) ? _remove : _add](labelHoverClass);
				  }
				}
	
				if (_mobile) {
				  event.stopPropagation();
				} else {
				  return false;
				}
			  }
			});
		  });
		} else {
		  return this;
		}
	  };
	
	  // Do something with inputs
	  function operate(input, direct, method) {
		var node = input[0],
		  state = /er/.test(method) ? _indeterminate : /bl/.test(method) ? _disabled : _checked,
		  active = method == _update ? {
			checked: node[_checked],
			disabled: node[_disabled],
			indeterminate: input.attr(_indeterminate) == 'true' || input.attr(_determinate) == 'false'
		  } : node[state];
	
		// Check, disable or indeterminate
		if (/^(ch|di|in)/.test(method) && !active) {
		  on(input, state);
	
		// Uncheck, enable or determinate
		} else if (/^(un|en|de)/.test(method) && active) {
		  off(input, state);
	
		// Update
		} else if (method == _update) {
	
		  // Handle states
		  for (var each in active) {
			if (active[each]) {
			  on(input, each, true);
			} else {
			  off(input, each, true);
			}
		  }
	
		} else if (!direct || method == 'toggle') {
	
		  // Helper or label was clicked
		  if (!direct) {
			input[_callback]('ifClicked');
		  }
	
		  // Toggle checked state
		  if (active) {
			if (node[_type] !== _radio) {
			  off(input, state);
			}
		  } else {
			on(input, state);
		  }
		}
	  }
	
	  // Add checked, disabled or indeterminate state
	  function on(input, state, keep) {
		var node = input[0],
		  parent = input.parent(),
		  checked = state == _checked,
		  indeterminate = state == _indeterminate,
		  disabled = state == _disabled,
		  callback = indeterminate ? _determinate : checked ? _unchecked : 'enabled',
		  regular = option(input, callback + capitalize(node[_type])),
		  specific = option(input, state + capitalize(node[_type]));
	
		// Prevent unnecessary actions
		if (node[state] !== true) {
	
		  // Toggle assigned radio buttons
		  if (!keep && state == _checked && node[_type] == _radio && node.name) {
			var form = input.closest('form'),
			  inputs = 'input[name="' + node.name + '"]';
	
			inputs = form.length ? form.find(inputs) : $(inputs);
	
			inputs.each(function() {
			  if (this !== node && $(this).data(_iCheck)) {
				off($(this), state);
			  }
			});
		  }
	
		  // Indeterminate state
		  if (indeterminate) {
	
			// Add indeterminate state
			node[state] = true;
	
			// Remove checked state
			if (node[_checked]) {
			  off(input, _checked, 'force');
			}
	
		  // Checked or disabled state
		  } else {
	
			// Add checked or disabled state
			if (!keep) {
			  node[state] = true;
			}
	
			// Remove indeterminate state
			if (checked && node[_indeterminate]) {
			  off(input, _indeterminate, false);
			}
		  }
	
		  // Trigger callbacks
		  callbacks(input, checked, state, keep);
		}
	
		// Add proper cursor
		if (node[_disabled] && !!option(input, _cursor, true)) {
		  parent.find('.' + _iCheckHelper).css(_cursor, 'default');
		}
	
		// Add state class
		parent[_add](specific || option(input, state) || '');
	
		// Set ARIA attribute
		if (!!parent.attr('role') && !indeterminate) {
		  parent.attr('aria-' + (disabled ? _disabled : _checked), 'true');
		}
	
		// Remove regular state class
		parent[_remove](regular || option(input, callback) || '');
	  }
	
	  // Remove checked, disabled or indeterminate state
	  function off(input, state, keep) {
		var node = input[0],
		  parent = input.parent(),
		  checked = state == _checked,
		  indeterminate = state == _indeterminate,
		  disabled = state == _disabled,
		  callback = indeterminate ? _determinate : checked ? _unchecked : 'enabled',
		  regular = option(input, callback + capitalize(node[_type])),
		  specific = option(input, state + capitalize(node[_type]));
	
		// Prevent unnecessary actions
		if (node[state] !== false) {
	
		  // Toggle state
		  if (indeterminate || !keep || keep == 'force') {
			node[state] = false;
		  }
	
		  // Trigger callbacks
		  callbacks(input, checked, callback, keep);
		}
	
		// Add proper cursor
		if (!node[_disabled] && !!option(input, _cursor, true)) {
		  parent.find('.' + _iCheckHelper).css(_cursor, 'pointer');
		}
	
		// Remove state class
		parent[_remove](specific || option(input, state) || '');
	
		// Set ARIA attribute
		if (!!parent.attr('role') && !indeterminate) {
		  parent.attr('aria-' + (disabled ? _disabled : _checked), 'false');
		}
	
		// Add regular state class
		parent[_add](regular || option(input, callback) || '');
	  }
	
	  // Remove all traces
	  function tidy(input, callback) {
		if (input.data(_iCheck)) {
	
		  // Remove everything except input
		  input.parent().html(input.attr('style', input.data(_iCheck).s || ''));
	
		  // Callback
		  if (callback) {
			input[_callback](callback);
		  }
	
		  // Unbind events
		  input.off('.i').unwrap();
		  $(_label + '[for="' + input[0].id + '"]').add(input.closest(_label)).off('.i');
		}
	  }
	
	  // Get some option
	  function option(input, state, regular) {
		if (input.data(_iCheck)) {
		  return input.data(_iCheck).o[state + (regular ? '' : 'Class')];
		}
	  }
	
	  // Capitalize some string
	  function capitalize(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	  }
	
	  // Executable handlers
	  function callbacks(input, checked, callback, keep) {
		if (!keep) {
		  if (checked) {
			input[_callback]('ifToggled');
		  }
	
		  input[_callback]('ifChanged')[_callback]('if' + capitalize(callback));
		}
	  }
	})(window.jQuery || window.Zepto);
	//************* icheck.JS End ***************************
	
	
	
	
	
	
	
	//************* Bookmarkscoll.JS Start *************************
	bookmarkscroll={
		setting: {duration:2000, yoffset:-20}, //{duration_of_scroll_milliseconds, offset_from_target_element_to_rest}
		topkeyword: '#top', //keyword used in your anchors and scrollTo() to cause script to scroll page to very top
	
		scrollTo:function(dest, options, hash){
			var $=jQuery, options=options || {}
			var $dest=(typeof dest=="string" && dest.length>0)? (dest==this.topkeyword? 0 : $('#'+dest)) : (dest)? $(dest) : [] //get element based on id, topkeyword, or dom ref
			if ($dest===0 || $dest.length==1 && (!options.autorun || options.autorun && Math.abs($dest.offset().top+(options.yoffset||this.setting.yoffset)-$(window).scrollTop())>5)){
				this.$body.animate({scrollTop: ($dest===0)? 0 : $dest.offset().top+(options.yoffset||this.setting.yoffset)}, (options.duration||this.setting.duration), function(){
					if ($dest!==0 && hash)
						location.hash=hash
				})
			}
		},
	
		urlparamselect:function(){
			var param=window.location.search.match(/scrollto=[\w\-_,]+/i) //search for scrollto=divid
			return (param)? param[0].split('=')[1] : null
		},
		
		init:function(){
			console.log('akjasj aksjajkj as');
			//jQuery(document).ready(function($){
				var mainobj=bookmarkscroll
				mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body')
				var urlselectid=mainobj.urlparamselect() //get div of page.htm?scrollto=divid
				if (urlselectid) //if id defined
					setTimeout(function(){mainobj.scrollTo(document.getElementById(urlselectid) || $('a[name='+urlselectid+']:eq(0)').get(0), {autorun:true})}, 100)
				$('a[href^="#"]').each(function(){ //loop through links with "#" prefix
					var hashvalue=this.getAttribute('href').match(/#\w+$/i) //filter links at least 1 character following "#" prefix
					hashvalue=(hashvalue)? hashvalue[0].substring(1) : null //strip "#" from hashvalue
					if (this.hash.length>1){ //if hash value is more than just "#"
						var $bookmark=$('a[name='+this.hash.substr(1)+']:eq(0)')
						if ($bookmark.length==1 || this.hash==mainobj.topkeyword){ //if HTML anchor with given ID exists or href==topkeyword
							if ($bookmark.length==1 && !document.all) //non IE, or IE7+
								$bookmark.html('.').css({position:'absolute', fontSize:1, visibility:'hidden'})
							$(this).click(function(e){
								mainobj.scrollTo((this.hash==mainobj.topkeyword)? mainobj.topkeyword : $bookmark.get(0), {}, this.hash)
								e.preventDefault()
							})
						}
					}
				})
			//})
		}
	}
	
	bookmarkscroll.init()
	//************* Bookmarkscoll.JS End *************************
	
	
});
// Merged JS End







$(document).ready(function () {

    /***************Foo Table*************/
    $(function () {
        if($('.table').length) {
            $('.table').footable();
        }

        if($('.clear-filter').length) {
            $('.clear-filter').click(function (e) {
                e.preventDefault();
                $('table.demo').trigger('footable_clear_filter');
                $('.filter-status').val('');
            });
        }

        if($('.filter-status').length) {
            $('.filter-status').change(function (e) {
                e.preventDefault();
                var filter = $(this).val();
                $('#filter').val($(this).text());
                $('table.demo').trigger('footable_filter', {filter: filter});
            });
        }
    });
    /***************Foo Table*************/

    /***************Home Horizontal Tab Js*************/
    if($('#featuresTab').length > 0){
        $('#featuresTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true   // 100% fit in a container
        });
    }
    /***************Home Horizontal Tab Js*************/

    /***************Home Banner*************/
    if($('.home_banner').length > 0){
        $('.home_banner').slick({
            dots: false,
            fade: true,
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            arrows: true,
            speed: 500,
            responsive: [{
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    }

    if($('.home_mobile_banner').length > 0){
        $('.home_mobile_banner').slick({
            dots: false,
            fade: true,
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            arrows: true,
            speed: 500,
            responsive: [
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    /***************Home Banner*************/
	
	if($('.post_login_popup_banner').length > 0){
        $('.post_login_popup_banner').slick({
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            arrows: true,
            speed: 500            
        });
    }
	
    /***************Player Speak carousel*************/
    if($('.player_speak').length > 0){
        $('.player_speak').slick({
            dots: false,
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            arrows: true,
            speed: 1000
        });
    }
    /***************Player Speak carousel*************/


    /***************SEO Accordion*************/
    if($(".accordion_example2").length > 0){
        $(".accordion_example2").smk_Accordion({
            closeAble: true //boolean
        });
    }
    /***************SEO Accordion*************/

    /***************Cashier Tab Vertical Tab Js*************/
    if($('#cashiertab').length > 0){
        $('#cashiertab').easyResponsiveTabs({
            type: 'vertical',
            width: 'auto',
            fit: true
        });
    }
    /***************Cashier Tab Vertical Tab Js*************/

    /***************Withdrawal Tab Vertical Tab Js*************/
    if($('#withdrawltab').length > 0){
        $('#withdrawltab').easyResponsiveTabs({
            type: 'vertical',
            width: 'auto',
            fit: true
        });
    }
    /***************Withdrawal Tab Vertical Tab Js*************/

    /***************placeholder.js*************/
    $(function() {
        // Invoke the plugin
        $('input, textarea').placeholder({customClass:'my-placeholder'});
    });
    /***************placeholder.js*************/

    /***************Checkbox And Radio Js*************/
    if($('input').length > 0){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%'
        });
    }
    /***************Checkbox And Radio Js*************/

    /********** Amount Selector Code **********/
    $('#amount_10000_div').on('ifClicked', function (event) {
        $( "#amount_10000_div" ).removeClass( "selected" );
		$( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_10000_div" ).addClass( "selected" );
    });
	
    $('#amount_5000_div').on('ifClicked', function (event) {
        $( "#amount_10000_div" ).removeClass( "selected" );
		$( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).addClass( "selected" );
    });

    $('#amount_1000_div').on('ifClicked', function (event) {
		$( "#amount_10000_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).addClass( "selected" );
    });

    $('#amount_500_div').on('ifClicked', function (event) {
		$( "#amount_10000_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).addClass( "selected" );
    });

    $('#amount_100_div').on('ifClicked', function (event) {
		$( "#amount_10000_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).addClass( "selected" );
    });
	
	$('#amount_50_div').on('ifClicked', function (event) {
		$( "#amount_10000_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_50_div" ).addClass( "selected" );
    });

    $('#amount_other_div').on('ifClicked', function (event) {
		$( "#amount_10000_div" ).removeClass( "selected" );
        $( "#amount_5000_div" ).removeClass( "selected" );
        $( "#amount_1000_div" ).removeClass( "selected" );
        $( "#amount_500_div" ).removeClass( "selected" );
        $( "#amount_100_div" ).removeClass( "selected" );
		$( "#amount_50_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).removeClass( "selected" );
        $( "#amount_other_div" ).addClass( "selected" );
    });

    $('#Debit_visa_div').on('ifClicked', function (event) {
        $( "#Debit_mastercard_div" ).removeClass( "selected" );
        $( "#Debit_maestro_div" ).removeClass( "selected" );
        $( "#Debit_americanexpress_div" ).removeClass( "selected" );
        $( "#Debit_rupay_div" ).removeClass( "selected" );
        $( "#Debit_visa_div" ).addClass( "selected" );
    });

    $('#Debit_mastercard_div').on('ifClicked', function (event) {
        $( "#Debit_visa_div" ).removeClass( "selected" );
        $( "#Debit_maestro_div" ).removeClass( "selected" );
        $( "#Debit_americanexpress_div" ).removeClass( "selected" );
        $( "#Debit_rupay_div" ).removeClass( "selected" );
        $( "#Debit_mastercard_div" ).addClass( "selected" );
    });

    $('#Debit_maestro_div').on('ifClicked', function (event) {
        $( "#Debit_visa_div" ).removeClass( "selected" );
        $( "#Debit_mastercard_div" ).removeClass( "selected" );
        $( "#Debit_americanexpress_div" ).removeClass( "selected" );
        $( "#Debit_rupay_div" ).removeClass( "selected" );
        $( "#Debit_maestro_div" ).addClass( "selected" );
    });

    $('#Debit_americanexpress_div').on('ifClicked', function (event) {
        $( "#Debit_visa_div" ).removeClass( "selected" );
        $( "#Debit_mastercard_div" ).removeClass( "selected" );
        $( "#Debit_maestro_div" ).removeClass( "selected" );
        $( "#Debit_rupay_div" ).removeClass( "selected" );
        $( "#Debit_americanexpress_div" ).addClass( "selected" );
    });

    $('#Debit_rupay_div').on('ifClicked', function (event) {
        $( "#Debit_visa_div" ).removeClass( "selected" );
        $( "#Debit_mastercard_div" ).removeClass( "selected" );
        $( "#Debit_maestro_div" ).removeClass( "selected" );
        $( "#Debit_americanexpress_div" ).removeClass( "selected" );
        $( "#Debit_rupay_div" ).addClass( "selected" );
    });

    $('#Credit_visa_div').on('ifClicked', function (event) {
        $( "#Credit_mastercard_div" ).removeClass( "selected" );
        $( "#Credit_maestro_div" ).removeClass( "selected" );
        $( "#Credit_americanexpress_div" ).removeClass( "selected" );
        $( "#Credit_rupay_div" ).removeClass( "selected" );
        $( "#Credit_visa_div" ).addClass( "selected" );
    });

    $('#Credit_mastercard_div').on('ifClicked', function (event) {
        $( "#Credit_visa_div" ).removeClass( "selected" );
        $( "#Credit_maestro_div" ).removeClass( "selected" );
        $( "#Credit_americanexpress_div" ).removeClass( "selected" );
        $( "#Credit_rupay_div" ).removeClass( "selected" );
        $( "#Credit_mastercard_div" ).addClass( "selected" );
    });

    $('#Credit_maestro_div').on('ifClicked', function (event) {
        $( "#Credit_visa_div" ).removeClass( "selected" );
        $( "#Credit_mastercard_div" ).removeClass( "selected" );
        $( "#Credit_americanexpress_div" ).removeClass( "selected" );
        $( "#Credit_rupay_div" ).removeClass( "selected" );
        $( "#Credit_maestro_div" ).addClass( "selected" );
    });

    $('#Credit_americanexpress_div').on('ifClicked', function (event) {
        $( "#Credit_visa_div" ).removeClass( "selected" );
        $( "#Credit_mastercard_div" ).removeClass( "selected" );
        $( "#Credit_maestro_div" ).removeClass( "selected" );
        $( "#Credit_rupay_div" ).removeClass( "selected" );
        $( "#Credit_americanexpress_div" ).addClass( "selected" );
    });

    $('#Credit_rupay_div').on('ifClicked', function (event) {
        $( "#Credit_visa_div" ).removeClass( "selected" );
        $( "#Credit_mastercard_div" ).removeClass( "selected" );
        $( "#Credit_maestro_div" ).removeClass( "selected" );
        $( "#Credit_americanexpress_div" ).removeClass( "selected" );
        $( "#Credit_rupay_div" ).addClass( "selected" );
    });
    /********** Amount Selector Code **********/

    /********** Bank Details Tab Js **********/
    $(".tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
    /********** Bank Details Tab Js **********/

    /********** Bank Details Tab Js **********/
    if($('#redeemtab').length > 0){
        $('#redeemtab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true,   // 100% fit in a container
            activate: function(e, tab) {
                $('.redeem_merchandise').slick('setPosition');
                $('.redeem_cash').slick('setPosition');

            },
        });
    }
    /********** Bank Details Tab Js **********/

    /***************promotions carousel*************/
    if($('.redeem_cash').length > 0){
        $('.redeem_cash').slick({
            dots: false,
            autoplay: false,
            autoplaySpeed: 3000,
            infinite: false,
            arrows: true,
            speed: 1000,
            slidesToShow: 6,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 801,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 740,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 668,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 601,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 416,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    }

    if($('.redeem_merchandise').length > 0){
        $('.redeem_merchandise').slick({
            dots: false,
            autoplay: false,
            autoplaySpeed: 3000,
            infinite: false,
            arrows: true,
            speed: 1000,
            slidesToShow: 6,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 801,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 740,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 668,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 601,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 416,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    }
    /***************promotions carousel*************/

    /***************Home Horizontal Tab Js*************/
    if($('#upload_doc_tab').length > 0){
        $('#upload_doc_tab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true,   // 100% fit in a container
        });
    }
    /***************Home Horizontal Tab Js*************/

    $('.scrollbar-outer').scrollbar();


    /***************Back To Top*************/
    //Check to see if the window is top if not then display button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });
    //Click event to scroll to top
    $('.scrollToTop').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });
    /***************Back To Top*************/
});
/***************Sitemap JS*************/
$(window).load(function () {
var $container = $('.isotope');
$container.isotope({ itemSelector: '.sitemapbox', layoutMode: 'masonry' });
});
/***************Sitemap JS*************/



$(document).ready(function () {
    
	/*$zopim(function(){
		$zopim.livechat.button.setOffsetHorizontalMobile(0);
		$zopim.livechat.button.setOffsetVerticalMobile(0);
		$zopim.livechat.button.setPositionMobile('bl')
	});
	
	
	if ('ontouchstart' in window) {
    /* cache dom references */ 
  /*  var $body = $('body'); 

    /* bind events */
   /* $(document)
    .on('focus', 'input', function() {
	   $(".zopim").hide();
    })
    .on('blur', 'input', function() {
		$(".zopim").show();
    });
}		*/
	
	
});