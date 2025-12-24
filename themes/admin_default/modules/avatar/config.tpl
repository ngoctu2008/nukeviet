<!-- BEGIN: main -->
<div class="table-responsive">
    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td class="w200">{LANG.per_page}</td>
                    <td><input type="number" name="per_page" value="{DATA.per_page}" class="form-control w100" /></td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2">
                        <input class="btn btn-primary" name="save" type="submit" value="{LANG.save}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<!-- END: main -->
