<!-- Footer wrapper -->
<div id="footer-wrap"><div class="footer">

<div class="footer-column section" id="column1">
<p class="footer-site-logo"><a title="{SITE_NAME}" href="{NV_BASE_SITEURL}"><img src="{LOGO_SRC}" alt="{SITE_NAME}"></a></p>
<p class="site-description">{SITE_DESCRIPTION}</p>
<p class="footer-link"><a href="{NV_BASE_SITEURL}about/">Giới thiệu</a> | <a href="javascript:void(0);">Quy định chung</a> | <a href="{NV_BASE_SITEURL}contact/">Liên hệ</a></p>

</div>
<div class="footer-column section" id="column2"><div class="block">
	<div class="block-title"><h2>Từ khóa</h2></div>
    <div class="block-content">
        [FOOTER_TAGS]
    </div>
</div>
</div>
<div class="footer-column section" id="column3"><div class="block">
	<div class="block-title"><h2>Kết nối mạng xã hội</h2></div>
    <div class="block-content"><div class="block"><div class="block-content">
    [FOOTER_SOCIAL]
</div></div></div>
</div>
</div>

</div></div>

<!-- Copyrights -->
<div id="copyrights">
<a class="bttop" href="#top"><i class="fa fa-angle-up"></i></a>
<div class="copyrights">
<div class="copy-left">
<span>©&nbsp;Bản quyền thuộc về <a class="copyright" href="{NV_BASE_SITEURL}">{SITE_NAME}</a>&nbsp; </span>
</div>
<div class="copy-right">

</div>
</div>
</div>


<div id="timeoutsess" class="chromeframe">
    Bạn đã không sử dụng Site, <a onclick="timeoutsesscancel();" href="javascript:void(0);">Bấm vào đây để duy trì trạng thái đăng nhập</a>. Thời gian chờ: <span id="secField"> 60 </span> giây
</div>
<div id="openidResult" class="nv-alert" style="display:none"></div>
<div id="openidBt" data-result="" data-redirect=""></div>

<!-- Scripts -->
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.min.js"></script>
<script>var nv_base_siteurl="{NV_BASE_SITEURL}",nv_lang_data="{NV_LANG_DATA}",nv_lang_interface="{NV_LANG_INTERFACE}",nv_name_variable="{NV_NAME_VARIABLE}",nv_fc_variable="{NV_FC_VARIABLE}",nv_lang_variable="{NV_LANG_VARIABLE}",nv_module_name="{MODULE_NAME}",nv_func_name="{OP}",nv_is_user={NV_IS_USER}, nv_my_ofs={NV_MY_OFS},nv_my_abbr="{NV_MY_ABBR}",nv_cookie_prefix="{NV_COOKIE_PREFIX}",nv_check_pass_mstime={NV_CHECK_PASS_MSTIME},nv_area_admin=0,nv_safemode=0,theme_responsive=1,nv_is_recaptcha=0;</script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/vi.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/global.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/news.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/xpressnews.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/owl.carousel.min.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery-scrolltofixed.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/news-ticker.js"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/main.js"></script>
<script>
	$(window).load(function(e) {
        $("#breaking-news").breakingNews({
			effect		:"slide-h",
			autoplay	:true,
			timer		:3000
		});
    });
</script>
<script>
$('.menu-carousel').owlCarousel({
		nav: true,
		navText: [ '', '' ],
		navContainer: '.owl-nav',
		navClass: [ 'owl-prev', 'owl-next' ],
	loop:true,
	margin:20,
	autoplay: false,
	responsive:{
			0:{
		items:1
			},
			480:{
		items:2
			},
			667:{
		items:4
			}
	}
});
var dot = $('.menu-carousel .owl-dot');
dot.each(function() {
	var index = $(this).index() + 1;
  if(index < 10){
	$(this).html('0').append(index);
  }else{
     $(this).html(index);
  }
});

$(".hover-menu").hover(
	function() {
		$('.hovermenus', this).not('.in .hovermenus').stop(true,true).slideDown("400");
		$(this).toggleClass('open');
	},
	function() {
		$('.hovermenus', this).not('.in .hovermenus').stop(true,true).slideUp("400");
		$(this).toggleClass('open');
	}
);
</script>
<script type="text/javascript">
   $('#menu').scrollToFixed();
</script>
<script>
$('.feature-carousel').owlCarousel({
		animateIn: 'zoomIn',
		animateOut: 'zoomOut',
		nav: true,
		navText: [ '', '' ],
		navContainer: '.owl-nav-feature',
		navClass: [ 'owl-prev-feature', 'owl-next-feature' ],
	loop:true,
	margin:10,
	autoplay: true,
		responsive:{
			0:{
		items:1
			},
			600:{
		items:1
			},
			1000:{
		items:1
			}
	}
});
</script>
<script>
$('.center-carousel').owlCarousel({
		animateIn: 'slideInLeft',
		animateOut: 'slideOutLeft',
		nav: true,
		navText: [ '', '' ],
		navContainer: '.owl-custom-nav',
		navClass: [ 'owl-prev-custom', 'owl-next-custom' ],
	loop:true,
	margin:10,
	dots: false,
	autoplay: false,
	responsive:{
			0:{
		items:2
			},
			480:{
		items:3
			},
			667:{
		items:3
			}
	}
});
</script>
<script>
$('.box-carousel').owlCarousel({
		animateIn: '',
		animateOut: '',
		nav: true,
		navText: [ '', '' ],
		navContainer: '.owl-nav-box',
		navClass: [ 'owl-prev-box', 'owl-next-box' ],
	loop:true,
	margin:10,
	dots: false,
	autoplay: false,
		responsive:{
			0:{
		items:1
			},
			600:{
		items:1
			},
			1000:{
		items:1
			}
	}
});
</script>
<script>
$('.sidebar-carousel').owlCarousel({
		animateIn: 'zoomIn',
		animateOut: 'zoomOut',
		nav: true,
		navText: [ '', '' ],
		navContainer: '.owl-nav-sidebar',
		navClass: [ 'owl-prev-sidebar', 'owl-next-sidebar' ],
	loop:true,
	margin:10,
	dots: false,
	autoplay: false,
	responsive:{
			0:{
		items:1
			},
			600:{
		items:1
			},
			1000:{
		items:1
			}
	}
});
</script>
<script>
$('.bottom-carousel').owlCarousel({
		animateIn: 'slideInLeft',
		animateOut: 'slideOutLeft',
		nav: true,
		navText: [ '<img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/slider_big_arrow_left.png" />', '<img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/slider_big_arrow_right.png" />' ],
		navContainer: '.owl-nav-bottom',
		navClass: [ 'owl-prev-bottom', 'owl-next-bottom' ],
	loop:true,
	margin:10,
	dots: false,
	autoplay: false,
	responsive:{
			0:{
		items:2
			},
			480:{
		items:2
			},
			600:{
		items:3
			},
			667:{
		items:4
			}
	}
});
</script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
{THEME_FOOTER_JS}
</body>
</html>
