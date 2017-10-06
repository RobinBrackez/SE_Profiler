function isInt(value) {
	var x;
	return isNaN(value) ? !1 : (x = parseFloat(value), (0 | x) === x);
}

/**
 * backend
 * This code is 90% copy paste of ANBU profiler for Laravel 3
 */
if (window.jQuery) {
	jQuery(document).ready(function ($) {
		$("#sql-filter").keyup(function() {
			var search = $(this).val();
			$("#sql-table tr").each(function () {
				var $queryDiv = $(this).find(".query");
				//$queryDiv.html($queryDiv.text());
				var queryText = $queryDiv.text();
				if (queryText.length > 0) {
					if (queryText.indexOf(search) === -1) {
						$(this).hide();
					} else {
						if (isInt(search) || search.length > 2) {
							var regex = new RegExp('(' + search + ')', 'ig');
							$($queryDiv).html(queryText.replace(regex, '<span class="highlight">$1</span>'));
						} else {
							$($queryDiv).html(queryText);
						}
						$(this).show();
					}
				}
			});
		});
		$("#sql-id-filter").click(function(){
			$("#sql-filter").val($(this).attr("data-id")).trigger('keyup');
		});
		$("#sql-sku-filter").click(function(){
			$("#sql-filter").val($(this).attr("data-sku")).trigger('keyup');
		});

		$(".execute-sql").click(function() {
			var self = $(this);
			var sql = $(this).attr('data-sql');
			$(self).attr('disabled','disabled');
            self.val("executing...");
			$.post(seProfilerBaseUrl + '/profiler/query/select', { sql: sql }, function(response){
                console.log(response);
                try {
                	if (!Array.isArray(response)) { // check if correct type of response
                        var json = $.parseJSON(response);
                    }
                }
                catch(err) {
                    self.val("Failed");
                    self.addClass("failed");
                    return;
                }

                var $queryResult = $(self).parents("tr").find(".query-result").first();
				var $table = $("<table/>");
				var $header = $("<tr/>");
				var firstRound = true;
				for (var key in response) {
					var $body = $("<tr/>");
					// skip loop if the property is from prototype
					if (!response.hasOwnProperty(key)) continue;
					var obj = response[key];
					for (var prop in obj) {
						// skip loop if the property is from prototype
						if(!obj.hasOwnProperty(prop)) continue;

						if (firstRound) {
							$header.append(
								$("<th/>")
									.attr("data-status", "opened")
									.text(prop)
									.click(function() {
                                        if ($(this).attr("data-status") === "opened") {
                                            $(this).closest("table").find("td").hide();
                                            $(this).attr("data-status", "closed");
                                        } else {
                                            $(this).closest("table").find("td").show();
                                            $(this).attr("data-status", "opened");
                                        }
                                    })
							);
						}
						$body.append($("<td/>").text(obj[prop]));
					}
					if (firstRound) {
						$table.append($header);
						firstRound = false;
					}
					$table.append($body);
				}
				var numberOfResults = response.length;
				if (numberOfResults === 0) {
                    self.val("Empty resultset");
                    self.addClass("failed");
				} else if (numberOfResults === 1){
					self.val("1 record");
				} else {
                    self.val(numberOfResults + " records");
				}
				$queryResult.append($table);
			}).fail(function() {
                self.val("Failed");
                self.addClass("failed");
            });
		});

        // $(".query-result th")
        // });

        $(".change-stock").click(function() {
            var productId = $(this).attr('data-id');
            var setInStock = $(this).attr('data-in-stock');
            var url = "";
            if (setInStock === "1") {
            	url = seProfilerBaseUrl + "/profiler/stock/setInStock";
			} else {
                url = seProfilerBaseUrl + "/profiler/stock/setOutOfStock";
			}
			var self = $(this);
			$(this).val("executing...");
            $.post(url, { id: productId }, function(response){
            	if (response) {
                    console.log(response);
                    $(self).val("reloading page...");
                    location.reload();
                } else {
                    $(self).val("Something went wrong...").addClass("failed");
				}
            });
        });

        $("#fill-cart").click(function() {
            executeAjax($(this), "/profiler/cart/fill");
        });

        $("#empty-cart").click(function() {
        	executeAjax($(this), "/profiler/cart/empty");
        });

        function executeAjax(context, urlPath) {
            $(context).val("executing...");
            url = seProfilerBaseUrl + urlPath;
            $.post(url, {}, function(response){
                if (response) {
                    console.log(response);
                    $(context).val("reloading page...");
                    location.reload();
                } else {
                    $(context).val("Something went wrong...").addClass("failed");
                }
            });
		}

        $("#profiler_section").hide(); //hide the default Magento profiler

		var profiler = {};

		$.extend(profiler, {

			// BOUND ELEMENTS
			// -------------------------------------------------------------
			// Binding these elements early, stops jQuery from "querying"
			// the DOM every time they are used.

			el: {
				main: $('.profiler'),
				close: $('#profiler-close'),
				zoom: $('#profiler-zoom'),
				hide: $('#profiler-hide'),
				show: $('#profiler-show'),
				tab_pane: $('.profiler-tab-pane'),
				hidden_tab_pane: $('.profiler-tab-pane:visible'),
				tab: $('.profiler-tab'),
				tabs: $('.profiler-tabs'),
				tab_links: $('.profiler-tabs a'),
				window: $('.profiler-window'),
				closed_tabs: $('#profiler-closed-tabs'),
				open_tabs: $('#profiler-open-tabs'),
				content_area: $('.profiler-content-area')
			},

			// CLASS ATTRIBUTES
			// -------------------------------------------------------------
			// Useful variable for Anbu.

			// is profiler in full screen mode
			is_zoomed: false,

			// initial height of content area
			small_height: $('.profiler-content-area').height(),

			// the name of the active tab css
			active_tab: 'profiler-active-tab',

			// the data attribute of the tab link
			tab_data: 'data-profiler-tab',

			// size of profiler when compact
			mini_button_width: '2.6em',

			// is the top window open?
			window_open: false,

			// current active pane
			active_pane: '',

			// START()
			// -------------------------------------------------------------
			// Sets up all the binds for Anbu!

			start: function () {

				// hide initial elements
				profiler.el.close.css('visibility', 'visible').hide();
				profiler.el.zoom.css('visibility', 'visible').hide();
				profiler.el.tab_pane.css('visibility', 'visible').hide();

				// bind all click events
				profiler.el.close.click(function (event) {
					profiler.close_window();
					event.preventDefault();
				});
				profiler.el.hide.click(function (event) {
					profiler.hide();
					event.preventDefault();
				});
				profiler.el.show.click(function (event) {
					profiler.show();
					event.preventDefault();
				});
				profiler.el.zoom.click(function (event) {
					profiler.zoom();
					event.preventDefault();
				});
				profiler.el.tab.click(function (event) {
					profiler.clicked_tab($(this));
					event.preventDefault();
				});

			},

			// CLICKED_TAB()
			// -------------------------------------------------------------
			// A tab has been clicked, decide what to do.

			clicked_tab: function (tab) {

				// if the tab is closed
				if (profiler.window_open && profiler.active_pane == tab.attr(profiler.tab_data)) {
					profiler.close_window();
				} else {
					profiler.open_window(tab);
				}

			},

			// OPEN_WINDOW()
			// -------------------------------------------------------------
			// Animate open the top window to the appropriate tab.

			open_window: function (tab) {

				// can't directly assign this line, but it works
				$('.profiler-tab-pane:visible').fadeOut(200);
				$('.' + tab.attr(profiler.tab_data)).delay(220).fadeIn(300);
				profiler.el.tab_links.removeClass(profiler.active_tab);
				tab.addClass(profiler.active_tab);
				profiler.el.window.slideDown(300);
				profiler.el.close.fadeIn(300);
				profiler.el.zoom.fadeIn(300);
				profiler.active_pane = tab.attr(profiler.tab_data);
				profiler.window_open = true;

			},

			// CLOSE_WINDOW()
			// -------------------------------------------------------------
			// Animate closed the top window hiding all tabs.

			close_window: function () {

				profiler.el.tab_pane.fadeOut(100);
				profiler.el.window.slideUp(300);
				profiler.el.close.fadeOut(300);
				profiler.el.zoom.fadeOut(300);
				profiler.el.tab_links.removeClass(profiler.active_tab);
				profiler.active_pane = '';
				profiler.window_open = false;

			},

			// SHOW()
			// -------------------------------------------------------------
			// Show the Anbu toolbar when it has been compacted.

			show: function () {

				profiler.el.closed_tabs.fadeOut(600, function () {
					profiler.el.main.removeClass('profiler-hidden');
					profiler.el.open_tabs.fadeIn(200);
				});
				profiler.el.main.animate({width: '100%'}, 700);

			},

			// HIDE()
			// -------------------------------------------------------------
			// Hide the profiler toolbar, show a tiny re-open button.

			hide: function () {

				profiler.close_window();

				setTimeout(function () {
					profiler.el.window.slideUp(400, function () {
						profiler.close_window();
						profiler.el.main.addClass('profiler-hidden');
						profiler.el.open_tabs.fadeOut(200, function () {
							profiler.el.closed_tabs.fadeIn(200);
						});
						profiler.el.main.animate({width: profiler.mini_button_width}, 700);
					});
				}, 100);

			},

			// TOGGLEZOOM()
			// -------------------------------------------------------------
			// Toggle the zoomed mode of the top window.

			zoom: function () {
				var height;
				if (profiler.is_zoomed) {
					height = profiler.small_height;
					profiler.is_zoomed = false;
				} else {
					// the 6px is padding on the top of the window
					height = ($(window).height() - profiler.el.tabs.height() - 6) + 'px';
					profiler.is_zoomed = true;
				}

				profiler.el.content_area.animate({height: height}, 700);

			}

		});

		$(profiler.start);
	});
}