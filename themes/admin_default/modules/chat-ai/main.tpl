<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">{LANG.config}</div>
    <div class="panel-body">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <div class="form-group">
                <label>{LANG.provider}</label>
                <select name="provider" class="form-control">
                    <option value="openai" {SELECTED_OPENAI}>OpenAI</option>
                    <option value="gemini" {SELECTED_GEMINI}>Google Gemini</option>
                </select>
            </div>
            <div class="form-group">
                <label>{LANG.api_key}</label>
                <input type="text" name="api_key" value="{DATA.api_key}" class="form-control" />
            </div>
            <div class="form-group">
                <label>{LANG.model}</label>
                <input type="text" name="model" value="{DATA.model}" class="form-control" placeholder="gpt-3.5-turbo / gemini-pro" />
            </div>
            <div class="form-group">
                <label>{LANG.system_prompt}</label>
                <textarea name="system_prompt" class="form-control" rows="3">{DATA.system_prompt}</textarea>
            </div>
            <div class="form-group">
                <label>{LANG.search_limit}</label>
                <input type="number" name="search_limit" value="{DATA.search_limit}" class="form-control" />
            </div>
             <div class="form-group">
                <label>{LANG.history_limit}</label>
                <input type="number" name="history_limit" value="{DATA.history_limit}" class="form-control" />
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="use_news" value="1" {CHECKED_NEWS}> {LANG.use_news}
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="use_laws" value="1" {CHECKED_LAWS}> {LANG.use_laws}
                </label>
            </div>
            <div class="text-center">
                <input type="submit" name="save" value="{LANG.save}" class="btn btn-primary" />
            </div>
        </form>
    </div>
</div>
<!-- END: main -->
