<?php
include(plugin_dir_path(__FILE__) . 'utillity.php');
global $wpdb;
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', '/wp-content/plugins/ElixirQuiz/css/jquery-ui.min.css');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_script('script', '/wp-content/plugins/ElixirQuiz/js/export-table2excel.js', array('jquery'), 1.1, true);
wp_enqueue_style('admin-css', '/wp-content/plugins/ElixirQuiz/css/admin-style.css');
$tablename = $wpdb->prefix . 'elixir_contact_email';

$error = "";
if (isset($_POST['email'])) {
    if (!isset($_POST['add_email']) || !wp_verify_nonce($_POST['add_email'], 'post_add_email')) {
       $error = 'Sorry, your nonce did not verify.';
    }

    $wpdb->insert($tablename, array(
        'email' => sanitize_text_field($_POST['email'])
            )
    );
}

if (isset($_GET) && $_GET['action'] == 'delete') {
    $wpdb->delete($tablename, array('id' => intval($_GET['id']))
    );
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline">ผู้รับเมล</h1>
    <div id="col-container">
        <?php
        $tablename = $wpdb->prefix . 'elixir_contact_email';
        $results = $wpdb->get_results("SELECT * FROM " . $tablename . " ", OBJECT);
        ?>
        <table class="wp-list-table widefat" id="result-table">
            <thead>
                <tr>
                    <td data-style="head1">อีเมล</td>
                    <td data-style="head1"></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($results as $key => $value) {
                    ?>
                    <tr>
                        <td><?= $value->email ?></td>  
                        <td><a href="?page=elixir-email&action=delete&id=<?= $value->id ?>">ลบ</a></td>                   
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <form method="post">
        <?php wp_nonce_field('post_add_email', 'add_email'); ?>
        <br/>
        <div class="form-group">
            <?=$error?>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
            <button type="submit" class="button">เพิมอีเมล</button>
        </div>

    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var dateFormat = "dd/mm/yy",
                from = $("#from")
                .datepicker({
                    dateFormat: dateFormat,
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1
                })
                .on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                }),
                to = $("#to").datepicker({
            dateFormat: dateFormat,
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        })
                .on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                });

        function getDate(element) {
            var date;
            try {

                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }

        $('#export').click(function () {

            tablesToExcel(['result-table'], ['รายชื่อผู้ติดต่อ'], 'รายชื่อผู้ติดต่อ.xls')
            return false
        })
    });
</script>
