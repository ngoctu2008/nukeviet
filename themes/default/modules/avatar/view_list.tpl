<!-- BEGIN: main -->
<div class="view-list">
    <div class="page-header">
        <h1>{CAT_INFO.title}</h1>
        <div class="description">{CAT_INFO.description}</div>
    </div>

    <div class="list-group">
        <!-- BEGIN: row -->
        <a href="{ROW.link}" class="list-group-item">
            <div class="media">
                <div class="media-left">
                     <img class="media-object" src="{ROW.image}" alt="{ROW.title}" style="width: 64px; height: 64px;">
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{ROW.title}</h4>
                    <p>{ROW.description}</p>
                    <p><i class="fa fa-eye"></i> {ROW.views} <i class="fa fa-download"></i> {ROW.downloads}</p>
                </div>
            </div>
        </a>
        <!-- END: row -->
    </div>

    <div class="text-center">{GENERATE_PAGE}</div>
</div>
<!-- END: main -->
