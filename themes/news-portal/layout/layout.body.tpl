<!doctype html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>
    {THEME_PAGE_TITLE}
    <meta http-equiv="Content-Type" content="text/html; charset={NV_CONTENT_ENCODING}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BEGIN: metatags -->
    <meta name="{THEME_META_TAGS.name}" content="{THEME_META_TAGS.content}" />
    <!-- END: metatags -->
    <link rel="shortcut icon" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/favicon.png" />
    <!-- BEGIN: links -->
    <link {LINKS.key} />
    <!-- END: links -->
    <!-- BEGIN: js -->
    <!-- BEGIN: ext -->
    <script type="text/javascript" src="{JS_SRC}"></script>
    <!-- END: ext -->
    <!-- BEGIN: int -->
    <script type="text/javascript">
    {JS_CONTENT}
    </script>
    <!-- END: int -->
    <!-- END: js -->
</head>

<body class="home site-mode--light">

<div id="page" class="site">
    <div class="np-top-header-wrap">
        <div class="mt-container">
            <div class="np-top-left-section-wrapper">
                <div class="date-section">{NV_CURRENTTIME}</div>
                <nav id="top-navigation" class="top-navigation" role="navigation">
                   <!-- Block Group: Top Navigation -->
                   {BLOCK_top_nav}
                </nav>
            </div>
            <div class="np-top-right-section-wrapper">
                <div class="mt-social-icons-wrapper">
                    <span class="social-link"><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></span>
                    <span class="social-link"><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></span>
                    <span class="social-link"><a href="#" target="_blank"><i class="fab fa-youtube"></i></a></span>
                </div>
            </div>
        </div>
    </div>

    <header id="masthead" class="site-header" role="banner">
        <div class="np-logo-section-wrapper">
            <div class="mt-container">
                <div class="site-branding">
                    <a href="{THEME_SITE_HREF}" class="custom-logo-link" rel="home">
                        <img width="290" height="47" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/logo.png" class="custom-logo" alt="{SITE_NAME}" />
                    </a>
                    <h1 class="site-title"><a href="{THEME_SITE_HREF}" rel="home">{SITE_NAME}</a></h1>
                    <p class="site-description">{SITE_DESCRIPTION}</p>
                </div>
                <div class="np-header-ads-area">
                    <!-- Banner Position -->
                     [THEME_ERROR_INFO]
                </div>
            </div>
        </div>

        <div id="np-menu-wrap" class="np-header-menu-wrapper">
            <div class="np-header-menu-block-wrap">
                <div class="mt-container">
                    <div class="np-home-icon">
                        <a href="{THEME_SITE_HREF}" rel="home"> <i class="fa fa-home"> </i> </a>
                    </div>
                    <div class="mt-header-menu-wrap">
                        <a href="javascript:void(0)" class="menu-toggle hide"><i class="fa fa-navicon"> </i> </a>
                        <nav id="site-navigation" class="main-navigation" role="navigation">
                             {BLOCK_header}
                        </nav>
                    </div>
                    <div class="np-icon-elements-wrapper">
                         <div id="np-site-mode-wrap" class="np-icon-elements">
                            <a id="mode-switcher" class="light-mode" data-site-mode="light-mode" href="#">
                                <span class="site-mode-icon">site mode button</span>
                            </a>
                        </div>
                        <div class="np-header-search-wrapper">
                            <span class="search-main"><a href="javascript:void(0)"><i class="fa fa-search"></i></a></span>
                            <div class="search-form-main np-clearfix">
                                <form role="search" method="get" class="search-form" action="{THEME_SEARCH_URL}">
                                    <label>
                                        <span class="screen-reader-text">Search for:</span>
                                        <input type="search" class="search-field" placeholder="Search &hellip;" value="" name="q" />
                                    </label>
                                    <input type="submit" class="search-submit" value="Search" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="np-ticker-wrapper">
        <div class="mt-container">
            <div class="np-ticker-block np-clearfix">
                <span class="ticker-caption">Breaking News</span>
                <div class="ticker-content-wrapper">
                    {BLOCK_breaking_news}
                </div>
            </div>
        </div>
    </div>

    <div id="content" class="site-content">
        <div class="mt-container">
            <div class="np-home-top-section np-clearfix">
                {BLOCK_top_slider}
            </div>

            <div class="np-home-middle-section np-clearfix">
                <div class="middle-primary">
                    {BLOCK_main_content}
                    {MODULE_CONTENT}
                </div>
                <div class="middle-aside">
                    {BLOCK_sidebar}
                </div>
            </div>

            <div class="np-home-bottom-section">
                {BLOCK_content_top}
            </div>
        </div>
    </div>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div id="top-footer" class="footer-widgets-wrapper footer_column_three np-clearfix">
            <div class="mt-container">
                <div class="footer-widgets-area np-clearfix">
                    <div class="np-footer-widget-wrapper np-column-wrapper np-clearfix">
                        {BLOCK_footer_widgets}
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-footer np-clearfix">
            <div class="mt-container">
                <div class="site-info">
                    <span class="np-copyright-text">{NV_SITE_COPYRIGHT}</span>
                    <span class="sep"> | </span>
                    {BLOCK_copyright}
                </div>
            </div>
        </div>
    </footer>
    <div id="np-scrollup" class="animated arrow-hide"><i class="fa fa-chevron-up"></i></div>
</div>

</body>
</html>
