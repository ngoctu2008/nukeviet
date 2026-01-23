<!-- BEGIN: main -->
<form action="{ACTION}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config}</div>
        <div class="panel-body">
            <div class="form-group">
                <label>Phương phái an sao (Horoscope Method)</label>
                <select name="horoscope_method" class="form-control">
                    <option value="nam_phai" selected>Nam Phái</option>
                    <option value="bac_phai">Bắc Phái</option>
                </select>
            </div>

            <div class="form-group">
                <label>{LANG.groups_view}</label>
                <div class="well" style="max-height: 200px; overflow-y: auto;">
                    <!-- BEGIN: groups_view -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="groups_view[]" value="{GROUPS_VIEW.id}" {GROUPS_VIEW.checked}> {GROUPS_VIEW.title}
                        </label>
                    </div>
                    <!-- END: groups_view -->
                </div>
            </div>

            <div class="form-group">
                <label>{LANG.advice_high}</label>
                <textarea name="advice_high" class="form-control" rows="3">{ROW.advice_high}</textarea>
            </div>
            <div class="form-group">
                <label>{LANG.advice_medium}</label>
                <textarea name="advice_medium" class="form-control" rows="3">{ROW.advice_medium}</textarea>
            </div>
            <div class="form-group">
                <label>{LANG.advice_low}</label>
                <textarea name="advice_low" class="form-control" rows="3">{ROW.advice_low}</textarea>
            </div>

            <div class="text-center">
                <button class="btn btn-primary" name="submit" type="submit">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
