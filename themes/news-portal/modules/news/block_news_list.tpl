<!-- BEGIN: main -->
<section class="widget news_portal_block_posts np-clearfix">
    <div class="np-block-wrapper block-posts np-clearfix layout3">
        <h2 class="np-block-title"><a href="{CAT_LINK}"><span class="np-title np-cat-16">{CAT_NAME}</span></a></h2>
        <div class="np-block-posts-wrapper">
             <!-- BEGIN: first -->
            <div class="np-primary-block-wrap">
                <div class="np-single-post">
                    <div class="np-post-thumb">
                        <a href="{ROW.link}">
                            <img width="1132" height="575" src="{ROW.thumb}" class="attachment-full size-full wp-post-image" alt="{ROW.title}" />
                        </a>
                    </div>
                    <div class="np-post-content">
                        <h3 class="np-post-title large-size"><a href="{ROW.link}">{ROW.title}</a></h3>
                         <div class="np-post-meta">
                            <span class="posted-on"><time class="entry-date published">{ROW.publtime}</time></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: first -->
            <div class="np-secondary-block-wrap np-clearfix">
                <!-- BEGIN: loop -->
                <div class="np-single-post">
                    <div class="np-post-thumb">
                        <a href="{ROW.link}">
                            <img width="305" height="207" src="{ROW.thumb}" class="attachment-news-portal-block-medium size-news-portal-block-medium wp-post-image" alt="{ROW.title}" />
                        </a>
                    </div>
                    <div class="np-post-content">
                        <h3 class="np-post-title small-size"><a href="{ROW.link}">{ROW.title}</a></h3>
                        <div class="np-post-meta">
                            <span class="posted-on"><time class="entry-date published">{ROW.publtime}</time></span>
                        </div>
                    </div>
                </div>
                <!-- END: loop -->
            </div>
        </div>
    </div>
</section>
<!-- END: main -->
