{FILE "header.tpl"}

<div id="outer-wrapper">
    <div id="wrap2">
        <!-- Slider Area -->
        <div class="fp-slider">
            <div class="fp-slides-container">
                <div style="overflow: hidden;" class="fp-slides slider-area section" id="slider-area">
                    <div class="block">
                        <div class="fp-slides-items">
                             [SLIDER_AREA]
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Slider Below Main Slider -->
         [FEATURED_SLIDER]

    </div>
</div>

<div id="content-wrapper">
    <div id="main-wrapper">
        <!-- Big Box 1 -->
        <div class="big-box">
             [CONTENT_CENTER_TOP]
        </div>
        <div class="clear"></div>

        <!-- Carousel Center -->
        <div class="owlcarousel">
             [CAROUSEL_CENTER]
        </div>

        <!-- Big Box 2 -->
        <div class="big-box">
             [CONTENT_CENTER_BOTTOM]
        </div>
        <div class="clear"></div>

        <!-- Topic Section -->
         [BOX_TOPIC]

        <!-- Box FP Slider (Topic Slider) -->
        <div class="box-fp-slider section">
             [BOX_FP_SLIDER]
        </div>

        <!-- Box Section 1 -->
         [BOX_SECTION_1]

        <!-- Box Section 2 -->
         [BOX_SECTION_2]

        <div class="clear"></div>

        <!-- Video Section -->
         [VIDEO_SECTION]
    </div>

    <aside id="sidebar-wrapper" class="right-sidebar">
         [SIDEBAR_RIGHT]
    </aside>
</div>

<div class="clear"></div>
<div id="carousel" style="margin-bottom:10px">
     [BOTTOM_CAROUSEL]
</div>

{FILE "footer.tpl"}
