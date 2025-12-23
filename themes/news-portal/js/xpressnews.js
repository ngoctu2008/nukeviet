// Tabs
$(document).ready(function() {
    $(".tab-wrapper a").click(function(event) {
			event.preventDefault();
			event.stopImmediatePropagation();
			$(this).parent().addClass("active");
			$(this).parent().siblings().removeClass("active");
			var tab = $(this).attr("href");
			$(".tab-content").not(tab).css("display", "none");
			$(tab).fadeIn();
    });
    $(".detail-tab-wrapper a").click(function(event) {
			event.preventDefault();
			event.stopImmediatePropagation();
			$(this).parent().addClass("active");
			$(this).parent().siblings().removeClass("active");
			var tab = $(this).attr("href");
			$(".detail-tab-content").not(tab).css("display", "none");
			$(tab).fadeIn();
    });
});

// Theia Sticky Sidebar v1.7.0
!function(i){i.fn.theiaStickySidebar=function(t){function e(t,e){var a=o(t,e);a||(console.log("TSS: Body width smaller than options.minWidth. Init is delayed."),i(document).on("scroll."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)),i(window).on("resize."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)))}function o(t,e){return t.initialized===!0||!(i("body").width()<t.minWidth)&&(a(t,e),!0)}function a(t,e){t.initialized=!0;var o=i("#theia-sticky-sidebar-stylesheet-"+t.namespace);0===o.length&&i("head").append(i('<style id="theia-sticky-sidebar-stylesheet-'+t.namespace+'">.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')),e.each(function(){function e(){a.fixedScrollTop=0,a.sidebar.css({"min-height":"1px"}),a.stickySidebar.css({position:"static",width:"",transform:"none"})}function o(t){var e=t.height();return t.children().each(function(){e=Math.max(e,i(this).height())}),e}var a={};if(a.sidebar=i(this),a.options=t||{},a.container=i(a.options.containerSelector),0==a.container.length&&(a.container=a.sidebar.parent()),a.sidebar.parents().css("-webkit-transform","none"),a.sidebar.css({position:a.options.defaultPosition,overflow:"visible","-webkit-box-sizing":"border-box","-moz-box-sizing":"border-box","box-sizing":"border-box"}),a.stickySidebar=a.sidebar.find(".theiaStickySidebar"),0==a.stickySidebar.length){var s=/(?:text|application)\/(?:x-)?(?:javascript|ecmascript)/i;a.sidebar.find("script").filter(function(i,t){return 0===t.type.length||t.type.match(s)}).remove(),a.stickySidebar=i("<div>").addClass("theiaStickySidebar").append(a.sidebar.children()),a.sidebar.append(a.stickySidebar)}a.marginBottom=parseInt(a.sidebar.css("margin-bottom")),a.paddingTop=parseInt(a.sidebar.css("padding-top")),a.paddingBottom=parseInt(a.sidebar.css("padding-bottom"));var r=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight();a.stickySidebar.css("padding-top",1),a.stickySidebar.css("padding-bottom",1),r-=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight()-d-r,0==r?(a.stickySidebar.css("padding-top",0),a.stickySidebarPaddingTop=0):a.stickySidebarPaddingTop=1,0==d?(a.stickySidebar.css("padding-bottom",0),a.stickySidebarPaddingBottom=0):a.stickySidebarPaddingBottom=1,a.previousScrollTop=null,a.fixedScrollTop=0,e(),a.onScroll=function(a){if(a.stickySidebar.is(":visible")){if(i("body").width()<a.options.minWidth)return void e();if(a.options.disableOnResponsiveLayouts){var s=a.sidebar.outerWidth("none"==a.sidebar.css("float"));if(s+50>a.container.width())return void e()}var r=i(document).scrollTop(),d="static";if(r>=a.sidebar.offset().top+(a.paddingTop-a.options.additionalMarginTop)){var c,p=a.paddingTop+t.additionalMarginTop,b=a.paddingBottom+a.marginBottom+t.additionalMarginBottom,l=a.sidebar.offset().top,f=a.sidebar.offset().top+o(a.container),h=0+t.additionalMarginTop,g=a.stickySidebar.outerHeight()+p+b<i(window).height();c=g?h+a.stickySidebar.outerHeight():i(window).height()-a.marginBottom-a.paddingBottom-t.additionalMarginBottom;var u=l-r+a.paddingTop,S=f-r-a.paddingBottom-a.marginBottom,y=a.stickySidebar.offset().top-r,m=a.previousScrollTop-r;"fixed"==a.stickySidebar.css("position")&&"modern"==a.options.sidebarBehavior&&(y+=m),"stick-to-top"==a.options.sidebarBehavior&&(y=t.additionalMarginTop),"stick-to-bottom"==a.options.sidebarBehavior&&(y=c-a.stickySidebar.outerHeight()),y=m>0?Math.min(y,h):Math.max(y,c-a.stickySidebar.outerHeight()),y=Math.max(y,u),y=Math.min(y,S-a.stickySidebar.outerHeight());var k=a.container.height()==a.stickySidebar.outerHeight();d=(k||y!=h)&&(k||y!=c-a.stickySidebar.outerHeight())?r+y-a.sidebar.offset().top-a.paddingTop<=t.additionalMarginTop?"static":"absolute":"fixed"}if("fixed"==d){var v=i(document).scrollLeft();a.stickySidebar.css({position:"fixed",width:n(a.stickySidebar)+"px",transform:"translateY("+y+"px)",left:a.sidebar.offset().left+parseInt(a.sidebar.css("padding-left"))-v+"px",top:"0px"})}else if("absolute"==d){var x={};"absolute"!=a.stickySidebar.css("position")&&(x.position="absolute",x.transform="translateY("+(r+y-a.sidebar.offset().top-a.stickySidebarPaddingTop-a.stickySidebarPaddingBottom)+"px)",x.top="0px"),x.width=n(a.stickySidebar)+"px",x.left="",a.stickySidebar.css(x)}else"static"==d&&e();"static"!=d&&1==a.options.updateSidebarHeight&&a.sidebar.css({"min-height":a.stickySidebar.outerHeight()+a.stickySidebar.offset().top-a.sidebar.offset().top+a.paddingBottom}),a.previousScrollTop=r}},a.onScroll(a),i(document).on("scroll."+a.options.namespace,function(i){return function(){i.onScroll(i)}}(a)),i(window).on("resize."+a.options.namespace,function(i){return function(){i.stickySidebar.css({position:"static"}),i.onScroll(i)}}(a)),"undefined"!=typeof ResizeSensor&&new ResizeSensor(a.stickySidebar[0],function(i){return function(){i.onScroll(i)}}(a))})}function n(i){var t;try{t=i[0].getBoundingClientRect().width}catch(i){}return"undefined"==typeof t&&(t=i.width()),t}var s={containerSelector:"",additionalMarginTop:0,additionalMarginBottom:0,updateSidebarHeight:!0,minWidth:0,disableOnResponsiveLayouts:!0,sidebarBehavior:"modern",defaultPosition:"relative",namespace:"TSS"};return t=i.extend(s,t),t.additionalMarginTop=parseInt(t.additionalMarginTop)||0,t.additionalMarginBottom=parseInt(t.additionalMarginBottom)||0,e(t,this),this}}(jQuery);
$('.left-sidebar, .right-sidebar').theiaStickySidebar({
	"additionalMarginTop": "40",
});

// Color Scheme
$(".colorom a").each(function() {
    var o = $(this).attr("data-color");
    $(this).css("background-color", o), $(this).click(function() {
        return $("a:hover,.sticker.fp-slides a:hover,.sidebar-tab .tab-content .featuredTitle a:hover").css("color", o),

		// background
		$(".box-fp-slider #large-section h2,.box-video #large-section h2,li.active a.dropdown-toggle,.bottom .news_pictures h1, .bigbox .mastoras_wide h1, .box .mastoras_wide h1, .box1 .mastoras_wide h1, .bigbox .news_pictures h1,.sidebar-tab .mastoras_wide h1, .sidebar-tab #large-section h1.owlcarousel h1,.search-block .search-button:hover,#slider-area .block h2,.sidebar-tab #large-section h2,.tab-wrapper li.active").css("background-color", o),

		$("#menu").css("border-bottom-color", o),
		$("#footer-wrap,.megamenu").css("border-top-color", o),

		//hover
		$(".sidebar-tab .tab-wrapper li").hover(function() {$(this).css("background-color", o)},
		function() {$(this).css("background-color", "rgba(0,0,0,0.8)")}),

		$(".news-tags-list a").hover(function() {$(this).css("border-color", o)},
		function() {$(this).css("border-color", "#444")}),

		//Category name
		$(".box-fp-slider #large-section h2,.box-video #large-section h2,.sidebar-carousel h2,.owlcarousel h1,.fp-slider #large-section h2,.bigbox .mastoras_wide h1, .box .mastoras_wide h1, .box1 .mastoras_wide h1, .bigbox .news_pictures h1,.sidebar-tab .mastoras_wide h1, .sidebar-tab #large-section h1.owlcarousel h1").hover(function() {$(this).css("background-color", "rgba(0,0,0,0.8)")},
		function() {$(this).css("background-color", o)}),

		$("#menu li a.home,#menu li.parent-menu a,#menu li.hover-menu a.dropdown-toggle").hover(function() {$(this).css("background-color", o)},
			function() {$(this).css("background-color", "#1c2a39")}),

		//mouseout
		$(".owlcarousel h5").mouseout(function() {$(this).css("background-color", o)},
			function() {$(this).css("background-color", "rgba(0,0,0,0.8)")}),

		$("a.bttop,a:hover,.owl-next-feature,.owl-next-custom,.owl-next-box,.owl-next-sidebar,.owl-next,.owl-next-related,.owl-prev-feature,.owl-prev-custom,.owl-prev-box,.owl-prev-sidebar,.owl-prev,.owl-prev-related").hover(function() {
            $(this).css("background-color", o)
        }, function() {
            $(this).css("background-color", "#272727")
        }), $(".search-icon").hover(function() {
            $(this).css("background-color", o)
        }, function() {
            $(this).css("background-color", "rgba(255,255,255,0.1)")
        }), !1
    })
}), $(".bground a").each(function() {
    $(this).click(function() {
        var o = $(this).find("img").attr("src");
        return $("body").css("background", "url(" + o + ") repeat scroll top left"), !1
    })
}), $(document).on("click", ".switchom.close", function() {
    return $(".switcher").css("margin-right", "0"), $(this).removeClass("close"), $(this).addClass("opend"), !1
}), $(document).on("click", ".switchom.opend", function() {
    return $(".switcher").css("margin-right", "-240px"), $(this).removeClass("opend"), $(this).addClass("close"), !1
}), $(document).on("click", ".contsho .full", function() {
    return $("#outer-wrapper").css("max-width", "none"), $(".row").css("margin-left", "auto"), $(".row").css("margin-right", "auto"), $(".row").css("max-width", "1152px"), !1
}), $(document).on("click", ".contsho .boxed", function() {
    return $("#outer-wrapper").css("max-width", "1200px"), $(".row").css("margin-left", "2%"), $(".row").css("margin-right", "2%"), !1
});

// Modal detail images
var modal = document.getElementById('myModal');
var img = $('.detail img');
var modalImg = $("#img01");
var captionText = document.getElementById("caption");
$('.detail img').click(function(){
    modal.style.display = "block";
    var newSrc = this.src;
    modalImg.attr('src', newSrc);
    captionText.innerHTML = this.alt;
});
var span = document.getElementsByClassName("closex")[0];
	span.onclick = function() {
	modal.style.display = "none";
}
$(document).keydown(function(event) {
    if (event.keyCode == 27) {
       $('#myModal').hide();
    }
});
