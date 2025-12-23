<!-- BEGIN: main -->
<div class="box section" id="box"><div class="block"><div class="block-content">
<div class="mastoras1">

<!--BIG-->
<!-- BEGIN: big_row -->
<div class="mastoras_wide left">
	<div class="thumb">
		<a title="{ROW.title}" href="{ROW.link}">
		<img class="alignone" src="{ROW.thumb}" height="180" width="300">
		</a>
		<a title="{ROW.cat_title}" href="{ROW.cat_link}"><h1 style="background:" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background=''">{ROW.cat_title}</h1></a>
	</div>
	<div class="featuredPost lastPost">
		<h2 class="postTitle" style="margin-top: 10px">
		<a onmouseover="this.style.color=''" onmouseout="this.style.color=''" title="{ROW.title}" href="{ROW.link}">{ROW.title}</a>
		</h2>

		<div class="metainfo" style="margin: 10px 0">
				<span class="author"><i class="fa fa-user"></i> {ROW.author}</span>
		<span><i class="fa fa-calendar"></i> {ROW.publtime}</span>
		<span><i class="fa fa-eye"></i> {ROW.hitstotal}</span>
		</div>

		<p>{ROW.hometext}</p>
		<div class="clear"></div>
	</div>
</div>
<!-- END: big_row -->
<!--BIG-->

<div class="mastoras_narrow right">
<!-- BEGIN: small_row -->
		<div id="mastoras_narrow">
		<div class="thumb">
			<a title="{ROW.title}" href="{ROW.link}">
			<img class="alignright" src="{ROW.thumb}" height="65" width="90">
			</a>
		</div>
		<div class="featuredTitle">
			<a onmouseover="this.style.color=''" onmouseout="this.style.color=''" title="{ROW.title}" href="{ROW.link}">{ROW.title}</a>	</div>
			<div class="metainfo" style="margin: 10px 0 0 0">
				<span><i class="fa fa-user"></i> {ROW.author}</span>
				<span><i class="fa fa-calendar"></i> {ROW.publtime}</span>
				<span><i class="fa fa-eye"></i> {ROW.hitstotal}</span>
			</div>
		<div class="clear"></div>
	</div>
<!-- END: small_row -->
</div>

</div></div></div></div>
<!-- END: main -->
