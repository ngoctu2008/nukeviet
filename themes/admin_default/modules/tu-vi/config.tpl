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
            <div class="text-center">
                <button class="btn btn-primary" name="submit" type="submit">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
