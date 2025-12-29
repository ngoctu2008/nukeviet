<!-- BEGIN: main -->
<div class="view-grid">
    <div class="page-header">
        <h1>{CAT_INFO.title}</h1>
        <!-- BEGIN: image -->
        <img src="{CAT_INFO.image}" alt="{CAT_INFO.title}" class="img-responsive" />
        <!-- END: image -->
        <div class="description">{CAT_INFO.description}</div>
    </div>

    <div class="row">
        <!-- BEGIN: row -->
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="thumbnail">
                <a href="{ROW.link}" title="{ROW.title}">
                    <img src="{ROW.image}" alt="{ROW.title}" class="img-responsive" />
                </a>
                <div class="caption text-center">
                    <h4><a href="{ROW.link}">{ROW.title}</a></h4>
                    <p><i class="fa fa-eye"></i> {ROW.views} <i class="fa fa-download"></i> {ROW.downloads}</p>
                    <!-- BEGIN: allow_use -->
                    <p><a href="{ROW.image}" download class="btn btn-success btn-xs"><i class="fa fa-download"></i> Download</a></p>
                    <!-- END: allow_use -->
                </div>
            </div>
        </div>
        <!-- END: row -->
    </div>

    <div class="text-center">{GENERATE_PAGE}</div>
</div>
<!-- END: main -->
