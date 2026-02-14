jQuery(document).ready(function($) {

	"use strict";

	/**
	 * Preloader
	 */
	if ($('#news-portal-preloader').length > 0) {
		setTimeout(function() {
			$('#news-portal-preloader').hide();
		}, 600);
	}

	/**
	 * Ticker script
	 */
	$("#newsTicker").lightSlider({
		item: 1,
		vertical: true,
		loop: true,
		verticalHeight: 35,
		pager: false,
		enableTouch: false,
		enableDrag: false,
		auto: true,
		controls: true,
		speed: 2000,
		pause: 6000,
		prevHtml: '<i class="fa fa-arrow-left"></i>',
		nextHtml: '<i class="fa fa-arrow-right"></i>',
		onSliderLoad: function() {
			$('#np-newsTicker').removeClass('cS-hidden');
		}
	});

	/**
	 * Slider script
	 */
	$('.slider-posts').each(function() {
		$(".np-main-slider").lightSlider({
			item: 1,
			auto: true,
			pager: false,
			loop: true,
			slideMargin: 0,
			speed: 2000,
			pause: 6000,
			enableTouch: false,
			enableDrag: false,
			prevHtml: '<i class="fa fa-angle-left"></i>',
			nextHtml: '<i class="fa fa-angle-right"></i>',
			onSliderLoad: function() {
				$('.np-main-slider').removeClass('cS-hidden');
			}
		});
	});

	/**
	 * Block carousel layout
	 */
	$('.carousel-posts').each(function() {
		var Id = $(this).parent().attr('id');
		var NewId = Id;
		var crsItem = $(this).data('items');

		NewId = $('#' + Id + " #blockCarousel").lightSlider({
			auto: true,
			loop: true,
			pauseOnHover: true,
			pager: false,
			speed: 2000,
			pause: 6000,
			controls: false,
			prevHtml: '<i class="fa fa-angle-left"></i>',
			nextHtml: '<i class="fa fa-angle-right"></i>',
			item: 4,
			onSliderLoad: function() {
				$('#' + Id + " #blockCarousel").removeClass('cS-hidden');
			},
			responsive: [{
					breakpoint: 840,
					settings: {
						item: 2,
						slideMove: 1,
						slideMargin: 6,
					}
				},
				{
					breakpoint: 480,
					settings: {
						item: 1,
						slideMove: 1,
					}
				}
			]
		});

		$('#' + Id + ' .np-navPrev').click(function() {
			NewId.goToPrevSlide();
		});
		$('#' + Id + ' .np-navNext').click(function() {
			NewId.goToNextSlide();
		});
	});

	/**
	 * Default widget tabbed
	 */
	$("#np-tabbed-widget").tabs();

	// Search toggle
	$('.np-header-search-wrapper .search-main').click(function() {
		$('.search-form-main').toggleClass('active-search');
		$('.search-form-main .search-field').focus();
		var element = document.querySelector('.np-header-search-wrapper');
		if (element) {
			$(document).on('keydown', function(e) {
				if (element.querySelectorAll('.search-form-main.active-search').length === 1) {
					var focusable = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
					var firstFocusable = focusable[0];
					var lastFocusable = focusable[focusable.length - 1];
					news_portal_focus_trap(firstFocusable, lastFocusable, e);
				}
			})
		}
	});

	// responsive menu toggle
	$('.np-header-menu-wrapper .menu-toggle').click(function(event) {
		$('.np-header-menu-wrapper #site-navigation').toggleClass('isActive').slideToggle('slow');
		var element = document.querySelector('.mt-header-menu-wrap');
		if (element) {
			$(document).on('keydown', function(e) {
				if (element.querySelectorAll('.np-header-menu-wrapper #site-navigation.isActive').length === 1) {
					var focusable = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
					var firstFocusable = focusable[0];
					var lastFocusable = focusable[focusable.length - 1];
					news_portal_focus_trap(firstFocusable, lastFocusable, e);
				}
			})
		}
	});

	// responsive sub menu toggle
	$('<a class="sub-toggle" href="javascript:void(0);"><i class="fa fa-angle-right"></i></a>').insertAfter('#site-navigation .menu-item-has-children>a, #site-navigation .page_item_has_children>a');

	$('#site-navigation .sub-toggle').click(function() {
		$(this).parent('.menu-item-has-children').children('ul.sub-menu').first().slideToggle('1000');
		$(this).parent('.page_item_has_children').children('ul.children').first().slideToggle('1000');
		$(this).children('.fa-angle-right').first().toggleClass('fa-angle-down');
	});

	// Scroll To Top
	$(window).scroll(function() {
		if ($(this).scrollTop() > 1000) {
			$('#np-scrollup').fadeIn('slow');
		} else {
			$('#np-scrollup').fadeOut('slow');
		}
	});

	$('#np-scrollup').click(function() {
		$("html, body").animate({
			scrollTop: 0
		}, 600);
		return false;
	});

	/**
	 * Focus trap in popup.
	 */
	var KEYCODE_TAB = 9;
	function news_portal_focus_trap(firstFocusable, lastFocusable, e) {
		if (e.key === 'Tab' || e.keyCode === KEYCODE_TAB) {
			if (e.shiftKey) {
				if (document.activeElement === firstFocusable) {
					lastFocusable.focus();
					e.preventDefault();
				}
			} else {
				if (document.activeElement === lastFocusable) {
					firstFocusable.focus();
					e.preventDefault();
				}
			}
		}
	}

	/**
	 * Close popups on escape key.
	 */
	$(document).on('keydown', function(event) {
		if (event.keyCode === 27) {
			event.preventDefault();
			$('.search-form-main').removeClass('active-search');
		}
	});

	/**
	 * Settings of the sticky menu
	 */
	var menuStickyVal = mtObject.menu_sticky;
	if (menuStickyVal === 'true') {
		var wpAdminBar = $('#wpadminbar');
		if (wpAdminBar.length) {
			$("#np-menu-wrap").sticky({ topSpacing: wpAdminBar.height() });
		} else {
			$("#np-menu-wrap").sticky({ topSpacing: 0 });
		}
	}

	/**
	 * theia sticky sidebar
	 */
	var innerStickyVal = mtObject.inner_sticky;
	if (innerStickyVal === 'true') {
		$('#primary, #secondary').theiaStickySidebar({
			additionalMarginTop: 30
		});
	}

	var frontStickyVal = mtObject.front_sticky;
	if (frontStickyVal === 'true') {
		$('.middle-primary, .middle-aside').theiaStickySidebar({
			additionalMarginTop: 30
		});
	}

	/**
	 * Live Search
	 */
	var ajaxUrl = mtObject.ajaxUrl;
	var _wpnonce = mtObject._wpnonce;
	var liveSearch = mtObject.liveSearch;

	if (liveSearch === 'true') {
		var searchContainer = $(".np-header-search-wrapper");

		if (searchContainer.length > 0) {
			var searchFormContainer = searchContainer.find("form");

			searchContainer.on('input', 'input[type="search"]', function() {
				var searchKey = $(this).val();

				if (searchKey) {
					$.ajax({
						method: 'post',
						url: ajaxUrl,
						data: {
							action: 'news_portal_search_posts_content',
							search_key: searchKey,
							security: _wpnonce
						},
						beforeSend: function() {
							searchFormContainer.addClass('retrieving-posts');
							searchFormContainer.removeClass('results-loaded');
						},
						success: function(res) {
							var parsedRes = JSON.parse(res);
							searchContainer.find(".news-portal-search-results-wrap").remove();
							searchFormContainer.after(parsedRes.posts);
							searchFormContainer.removeClass('retrieving-posts').addClass('results-loaded');
						},
						error: function(xhr, status, error) {
							console.error("AJAX Error:", error);
						}
					});
				} else {
					searchContainer.find(".news-portal-search-results-wrap").remove();
					searchFormContainer.removeClass('results-loaded');
				}
			});
		}
	}

	$(document).mouseup(function(e) {
		var container = $(".np-header-search-wrapper");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.find(".news-portal-search-results-wrap").remove();
			container.removeClass('results-loaded');
		}
	});

});

/**
 * Site mode switcher cookie
 */
document.addEventListener("DOMContentLoaded", function() {
	var modeSwitcher = document.getElementById("mode-switcher");
	var templateBodyClass = document.body;

	if (!modeSwitcher) {
		// If #mode-switcher does not exist, stop execution safely
		return;
	}

	function setSiteMode(mode) {
		localStorage.setItem("site-mode", mode);
	}

	function getSiteMode() {
		return localStorage.getItem("site-mode");
	}

	var modeStored = getSiteMode();
	if (modeStored) {
		if (modeStored === "dark-mode") {
			modeSwitcher.classList.remove("light-mode");
			modeSwitcher.classList.add("dark-mode");
			modeSwitcher.setAttribute("data-site-mode", "dark-mode");
			templateBodyClass.classList.remove('site-mode--light');
			templateBodyClass.classList.add('site-mode--dark');
		} else {
			modeSwitcher.classList.remove("dark-mode");
			modeSwitcher.classList.add("light-mode");
			modeSwitcher.setAttribute("data-site-mode", "light-mode");
			templateBodyClass.classList.remove('site-mode--dark');
			templateBodyClass.classList.add('site-mode--light');
		}
	}

	modeSwitcher.addEventListener("click", function(e) {
		e.preventDefault();
		var currentMode = modeSwitcher.getAttribute("data-site-mode");

		if (currentMode === "light-mode") {
			setSiteMode("dark-mode");
			modeSwitcher.classList.remove("light-mode");
			modeSwitcher.classList.add("dark-mode");
			modeSwitcher.setAttribute("data-site-mode", "dark-mode");
			templateBodyClass.classList.remove('site-mode--light');
			templateBodyClass.classList.add('site-mode--dark');
		} else {
			setSiteMode("light-mode");
			modeSwitcher.classList.remove("dark-mode");
			modeSwitcher.classList.add("light-mode");
			modeSwitcher.setAttribute("data-site-mode", "light-mode");
			templateBodyClass.classList.remove('site-mode--dark');
			templateBodyClass.classList.add('site-mode--light');
		}
	});
});
