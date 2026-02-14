<!-- BEGIN: main -->
<section id="news_portal_featured_slider-2" class="widget news_portal_featured_slider">
    <div class="np-block-wrapper np-clearfix">
        <div class="slider-posts">
            <ul id="npSlider" class="cS-hidden np-main-slider">
                <!-- BEGIN: loop -->
                <li>
                    <div class="np-single-slide-wrap">
                        <div class="np-slide-thumb">
                            <a href="{ROW.link}" title="{ROW.title}">
                                <img width="622" height="420" src="{ROW.thumb}" class="attachment-news-portal-slider-medium size-news-portal-slider-medium wp-post-image" alt="{ROW.title}" />
                            </a>
                        </div>
                        <div class="np-slide-content-wrap">
                            <div class="post-cats-list">
                                <span class="category-button np-cat-10"><a href="{ROW.catlink}">{ROW.catname}</a></span>
                            </div>
                            <h3 class="post-title large-size"><a href="{ROW.link}" title="{ROW.title}">{ROW.title}</a></h3>
                            <div class="np-post-meta">
                                <span class="posted-on"><time class="entry-date published">{ROW.publtime}</time></span>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- END: loop -->
            </ul>
        </div>
    </div>
</section>
<!-- END: main -->
