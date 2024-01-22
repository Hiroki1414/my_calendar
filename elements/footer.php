<footer class="footer py-3 mt-auto bg-light">
    <div class="container text-center">
        <span class="text-muted">&copy; <?= SITE_NAME ?></span>
    </div>
</footer>

<!-- JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/ja.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>

<script>
$(function () {
    $('#ymPicker').datetimepicker({
        format: 'YYYY-MM',
        locale: 'ja'
    });
    $('.task-datetime').datetimepicker({
        dayViewHeaderFormat: 'YYYY年 MMMM',
        format: 'YYYY/MM/DD HH:mm',
        locale: 'ja',
    });
    $('.search-date').datetimepicker({
        format: 'YYYY/MM/DD',
        locale: 'ja'
    });
    $('#selectColor').bind('change', function(){
        $(this).removeClass();
        $(this).addClass('form-select').addClass($(this).val());
    });
});
</script>