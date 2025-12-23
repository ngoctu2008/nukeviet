<!DOCTYPE html>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>
    {THEME_PAGE_TITLE}
    {THEME_META_TAGS}
    <link rel="shortcut icon" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/favicon.ico">
    {THEME_CSS}
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/xpressnews.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/xpressnews-responsive.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/animate.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/news.css">
    <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/video.css">
    <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/block_news_tags.css">
    <style id="theia-sticky-sidebar-stylesheet-TSS">.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>
    {THEME_SITE_JS}
</head>
<body {THEME_BODY_ATTRIBUTES}>

<div class="top-container">
    <!-- Headline -->
    <div class="headline-wrapper">
        <div class="headline">
            <div class="breaking-news">TIN MỚI</div>
            <div class="headline-left">
                [TOP_TICKER]
            </div>
            <div class="search-block">
                <form action="{NV_BASE_SITEURL}index.php" id="searchform" method="get">
                    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                    <input type="hidden" name="{NV_NAME_VARIABLE}" value="seek" />
                    <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
                    <input id="s" name="q" onblur="if (this.value == '') {this.value = 'Tìm kiếm ...';}" onfocus="if (this.value == 'Tìm kiếm ...') {this.value = '';}" value="Tìm kiếm ..." type="text" maxlength="60">
                </form>
            </div>
            <!-- .search-block -->
        </div>
    </div>
    <!-- End headline -->

    <!--Header-->
    <div id="header-wrapper">
        <div class="header section" id="header"><div class="block Header">
            <div id="header-inner">
                <div class="hue-color" style="width:190;height:100">
                    <a title="{SITE_NAME}" href="{NV_BASE_SITEURL}">
                        <img src="{LOGO_SRC}" alt="{SITE_NAME}">
                    </a>
                </div>
                <h1>{SITE_NAME}</h1>
                <h2>{SITE_DESCRIPTION}</h2>
            </div>
        </div></div>

        <div class="header2 section" id="header2">
            <div class="block"><div class="block-content">
                [HEADER_BANNER]
            </div></div>
        </div>
    </div>
    <!--End header-->

    <nav id="menu" class="scroll-to-fixed-fixed" style="z-index: 1000; position: fixed; top: 0px; margin-left: 0px; width: 984px; left: 220.5px;">
        <input type="checkbox">
        <label>
            <div class="button-sr">≡</div>
            <div class="home-description">{SITE_NAME}</div>
        </label>

        <!-- Main Menu -->
        <ul class="navbar">
             [MENU_SITE]
             <!-- Mega Menu Placeholder if needed inside menu block or separately -->
             [MEGA_MENU_CONTENT]
        </ul>
    </nav>
    <div style="display: block; width: 984px; height: 40px; float: none;"></div>
    <noscript>
        <div class="alert alert-danger">Trình duyệt của bạn đã tắt chức năng hỗ trợ JavaScript.<br />Website chỉ làm việc khi bạn bật nó trở lại.<br />Để tham khảo cách bật JavaScript, hãy click chuột <a href="http://wiki.nukeviet.vn/support:browser:enable_javascript">vào đây</a>!</div>
    </noscript>
