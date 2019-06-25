<?php
include(plugin_dir_path(__FILE__) . 'utillity.php');
global $wpdb;
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', '/wp-content/plugins/ElixirQuiz/css/jquery-ui.min.css');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_script('script', '/wp-content/plugins/ElixirQuiz/js/export-table2excel.js', array('jquery'), 1.1, true);
wp_enqueue_style('admin-css', '/wp-content/plugins/ElixirQuiz/css/admin-style.css');


$from = $_GET['from'];
$to = $_GET['to'];

$from = empty($from) ? '01' . date('/m/Y') : $from;
$to = empty($to) ? '30' . date('/m/Y') : $to;

$q_from = explode('/', $from);
$q_to = explode('/', $to);

$s_from = $q_from[2] . '-' . $q_from[1] . '-' . $q_from[0];
$s_to = $q_to[2] . '-' . $q_to[1] . '-' . $q_to[0];
?>
<div class="wrap">
    <h1 class="wp-heading-inline">รายชื่อผู้ติดต่อ</h1>
    <a href="#" class="page-title-action" id="export">
        Export
    </a>
    <form method="get">
        <input type="hidden" name="page" value="elixir-contact"/>
        <p class="search-box">
            <label for="from">From</label>
            <input type="text" id="from" name="from" value="<?= $from ?>">
            <label for="to">to</label>
            <input type="text" id="to" name="to" value="<?= $to ?>">
            <input type="submit" id="search-submit" class="button" value="Search"></p>
        <div id="col-container">
            <?php
            $tablename = $wpdb->prefix . 'elixir_contact';
            $results = $wpdb->get_results("SELECT * FROM " . $tablename . " Where created_datetime >= '" . $s_from . " 00:00:00' and created_datetime <= '" . $s_to . " 23:00:00'", OBJECT);
            ?>
            <table class="wp-list-table widefat" id="result-table">
                <thead>
                    <tr>
                        <td data-style="head1">วันที่</td>
                        <td data-style="head1">ชื่อ</td>
                        <td data-style="head1">อีเมล</td>
                        <td data-style="head1">เบอร์โทร</td>
                        <td data-style="head1">ข้อความ</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($results as $key => $value) {
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($value->created_datetime)) ?> <?= date('H:i', strtotime($value->created_datetime)) ?></td>
                            <td><?= $value->name ?></td>
                            <td><?= $value->email ?></td>
                            <td><?= $value->phone ?></td>
                            <td><?= $value->message ?></td>                        
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
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