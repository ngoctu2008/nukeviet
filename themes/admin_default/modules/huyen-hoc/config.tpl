<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td>{LANG.config}</td>
                    <td><input type="text" name="config_value" value="{CONFIG_VALUE}" class="form-control" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->
