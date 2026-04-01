<!-- BEGIN: main -->
<li class="hover-menu mega-dropdown">
	<a href="{BLOCK_LINK}" class="dropdown-toggle" data-toggle="dropdown">{BLOCK_TITLE} <span class="caret"></span></a>
	<ul class="hovermenus dropdown-menu mega-dropdown-menu">
		<div class="megamenu"><div class="block-content">
		<div class="mastoras">
			<div class="owl-nav"><div class="owl-prev"></div><div class="owl-next"></div></div>
			<div class="menu-carousel owl-carousel">
			    <!-- BEGIN: row -->
			    <div class="mastoras_wide">
				    <div class="thumb">
						<a title="{ROW.title}" href="{ROW.link}"><img src="{ROW.thumb}"></a>
				    </div>
				    <h1><a style="padding:0 5px;margin:0;font-size:12px;text-transform:none;height:24px;line-height:24px" title="{ROW.cat_title}" href="{ROW.cat_link}">{ROW.cat_title}</a></h1>
				    <div class="featuredPost">
				        <a style="background:none;" title="{ROW.title}" href="{ROW.link}">
				        <h4 onmouseover="this.style.color='#09f'" onmouseout="this.style.color='#000'" style="font-size: 13px;text-transform:none;color:#000">{ROW.title_clean}</h4>
				        </a>
					    <div class="metainfo" style="margin-left: 30px;color:#555">
					    <span><i class="fa fa-calendar"></i> {ROW.publtime}</span>
					    <span><i class="fa fa-eye"></i> {ROW.hitstotal}</span>
					    </div>
					    <div class="clear"></div>
				    </div>
			    </div>
                <!-- END: row -->
			</div>
		</div></div></div>
	</ul>
</li>
<!-- END: main -->
