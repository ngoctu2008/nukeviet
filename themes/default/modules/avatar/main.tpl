<!-- BEGIN: main -->
<div class="avatar-main">
    <div class="row">
        <!-- BEGIN: cat -->
        <div class="col-md-4 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><a href="{CAT.link}">{CAT.title}</a></h3>
                </div>
                <div class="panel-body">
                    <!-- BEGIN: image -->
                    <a href="{CAT.link}"><img src="{CAT.image}" alt="{CAT.title}" class="img-responsive" /></a>
                    <!-- END: image -->
                    <p>{CAT.description}</p>
                </div>
            </div>
        </div>
        <!-- END: cat -->
    </div>
    <!-- BEGIN: page -->
    <div class="text-center">{GENERATE_PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: main -->
