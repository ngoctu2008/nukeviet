<!-- BEGIN: main -->
<div class="lo-ban-container">
    <h2 class="text-center">{LANG.lo_ban}</h2>

    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline text-center">
        <div class="form-group">
            <label>Nhập kích thước (cm): </label>
            <input type="number" step="0.1" name="length" value="{LENGTH}" class="form-control" placeholder="Ví dụ: 250" required>
        </div>
        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <h3>Kết quả tra cứu: {LENGTH} cm</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loại thước</th>
                    <th>Phạm vi</th>
                    <th>Cung</th>
                    <th>Ý nghĩa</th>
                    <th>Kết luận</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: ruler -->
                <tr style="color: {RULER.color}">
                    <td><strong>{RULER.id}cm</strong></td>
                    <td>{RULER.scope}</td>
                    <td><strong>{RULER.name}</strong></td>
                    <td>{RULER.desc}</td>
                    <td><strong>{RULER.result_text}</strong></td>
                </tr>
                <!-- END: ruler -->
            </tbody>
        </table>
    </div>

    <div class="alert alert-info">
        <p><strong>Chú thích:</strong></p>
        <ul>
            <li><strong style="color:red">Màu đỏ:</strong> Cung tốt.</li>
            <li><strong style="color:black">Màu đen:</strong> Cung xấu.</li>
        </ul>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
