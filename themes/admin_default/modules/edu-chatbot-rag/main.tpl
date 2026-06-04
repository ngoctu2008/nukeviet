<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-body">
        <!-- BEGIN: error -->
        <div class="alert alert-danger">{ERROR}</div>
        <!-- END: error -->
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="checksess" value="{NV_CHECK_SESSION}" />
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>OpenAI API Key</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="text" name="openai_api_key" value="{DATA.openai_api_key}" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>Pinecone API Key</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="text" name="pinecone_api_key" value="{DATA.pinecone_api_key}" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>Pinecone URL</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="text" name="pinecone_url" value="{DATA.pinecone_url}" placeholder="https://your-index.pinecone.io" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>System Prompt</strong></label>
                <div class="col-sm-20">
                    <textarea class="form-control" name="system_prompt" rows="5">{DATA.system_prompt}</textarea>
                    <span class="help-block">Biến {context} sẽ được thay thế bằng tài liệu trích xuất từ Vector DB. Biến {user_question} sẽ là câu hỏi của người dùng.</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>Similarity Threshold (0.0 - 1.0)</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="text" name="similarity_threshold" value="{DATA.similarity_threshold}" />
                </div>
            </div>
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary" value="{GLANG.save}" />
            </div>
        </form>
    </div>
</div>
<!-- END: main -->