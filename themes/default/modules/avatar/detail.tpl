<!-- BEGIN: main -->
<div class="avatar-detail">
    <div class="page-header">
        <h1>{ROW.title}</h1>
        <p class="text-muted"><a href="{CAT_INFO.link}">{CAT_INFO.title}</a> | <i class="fa fa-eye"></i> {ROW.views} | <i class="fa fa-download"></i> {ROW.downloads}</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <img src="{ROW.image}" alt="{ROW.title}" class="img-responsive img-thumbnail" />
        </div>
        <div class="col-md-6">
            <div class="description">
                {ROW.description}
            </div>
            <div class="body">
                {ROW.body}
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
