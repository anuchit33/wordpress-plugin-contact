<?php
$_SESSION = array();
wp_enqueue_script('equiz', '/wp-content/plugins/Contact-captcha/js/equiz.js', array('jquery'), 1.1, true);
$_SESSION['captcha'] = simple_php_captcha();

wp_enqueue_style('style-css', '/wp-content/plugins/Contact-captcha/css/style.css');
?>
<style>
    .image-captcha {
        margin-top: 23px;
        border: 1px solid #ccc;
        display: inline-block;
        background: #fbfbfb;
        padding: 2px;
        width: 249px;
        overflow: hidden;
    }
    .image-captcha input#contact_captcha {

    }
    .image-captcha a#refresh-captcha {
        display: inline-block;
        margin: 21px;
        padding: 3px;
    }
    .msg.error {
        color: #F44336;
        display: none;
    }

    .modal-loading.fade.show {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999999;
        background: #ccc;
        opacity: 0.5;
        text-align: right;
        color: #000;
        cursor: progress;
    }
</style>

<section >
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="avada-contact-form" id="equizContact" >
        <?php wp_nonce_field('post_contact','post_contact'); ?>	
        <div id="comment-input">
            <div class="row">
                <div class="col-xs-12 col-md-4"><div class="msg error" id="error-name"></div></div>
                <div class="col-xs-12 col-md-4"><div class="msg error" id="error-email"></div></div>
                <div class="col-xs-12 col-md-4"><div class="msg error" id="error-phone"></div></div>
            </div>

            <input type="text" name="name" id="author" value="" placeholder="ชื่อ" size="22" required="" aria-required="true" aria-label="ชื่อ" class="input-name">
            <input type="email" name="email" id="email" value="" placeholder="อีเมล" size="22" required="" aria-required="true" aria-label="อีเมล" class="input-email">
            <input type="tel"  name="phone" id="phone" value="" placeholder="เบอร์โทร" aria-label="เบอร์โทร" maxlength="11" class="input-website">
        </div>

        <div id="comment-textarea" class="fusion-contact-comment-below">
            <div class="msg error" id="error-message"></div>
            <textarea name="message" id="comment" cols="39" rows="4" class="textarea-comment" placeholder="ข้อความ" aria-label="ข้อความ" style="max-width: 1170px;"></textarea>
        </div>

        <div class="image-captcha">
            <img src="" attr="CAPTCHA code" id="image-captcha" />    
            <a href="#refresh-captcha" id="refresh-captcha">
                <i class="fa fa-refresh"></i>
            </a>
            <div class="msg error" id="error-contact_captcha"></div>
            <input type="text" name="contact_captcha" id="contact_captcha" size="22" required="" aria-required="true" aria-label="code" placeholder="โค้ด" class="input-name">
        </div>

        <div id="alert_message" style="display: none;" class="fusion-alert alert success alert-success fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#dff0d8;color:#5ca340;border-color:#5ca340;border-width:1px;"><button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button><div class="fusion-alert-content-wrapper"><span class="alert-icon"><i class="fa-lg  fa fa-check-circle"></i></span><span class="fusion-alert-content" id="alert-content">ส่งข้อความเรียบร้อยแล้ว</span></div></div>
        <div id="comment-submit-container">
            <button type="submit" id="submit"  class="comment-submit fusion-button fusion-button-default fusion-button-default-size fusion-button-medium fusion-button-square fusion-button-flat">ตกลง</div>
        </div>
    </form>
</section>
<?php include 'modal.php' ?>      

<?php
// Register the script
wp_register_script('some_handle', array());

// Localize the script with new data
$translation_array = array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('equiz_security_9$tu_8K!'),
);
wp_localize_script('some_handle', 'object_name', $translation_array);

// Enqueued script with localized data.
wp_enqueue_script('some_handle');
?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        function loadImageCaptcha() {

            var data = {
                'action': 'get_captcha_image',
                'security': object_name.security
            }

            jQuery.get(object_name.ajaxurl, data, function (response, dd) {
                var o = JSON.parse(response)
                $('#image-captcha').attr('src', o.image_src)
            })
        }

        function sumitContact() {

            var arr = $('#equizContact').serializeArray()
            var data = {
                'action': 'post_contact',
                'security': object_name.security
            }

            for (k in arr) {
                data[arr[k]['name']] = arr[k]['value']
            }

            showLoading()
            $('.msg.error').hide()
            jQuery.post(object_name.ajaxurl, data, function (response, dd) {

                hideLoading()
                var o = JSON.parse(response)
                if (o.status) {
                    showAlertModal(o.message)
                    $('#equizContact input').val('')
                    $('#equizContact textarea').val('')
                    loadImageCaptcha()
                } else {
                    for (k in o.error) {
                        $('#error-' + k).html(o.error[k]).show()
                    }
                }
            });
        }

        function showAlertModal(message) {

            var c = $('#exampleModal').clone();

            c.attr('id', 'modalAlert')
            c.find('.msg2 h2').html(message)

            hideAlertModal();
            $('body').append('<div class="modal-backdrop fade show"></div>')
            $('body').append(c)
            $('#modalAlert').show();
            $('.modal-backdrop').off('click')
            $('.modal-backdrop').on('click', function () {
                hideAlertModal()
            })

            $('a.btn-close').off('click')
            $('a.btn-close').on('click', function () {
                hideAlertModal()
                return false;
            })
        }

        function hideAlertModal() {

            $('body').find('.modal-backdrop').remove()
            $('#modalAlert').remove()
            $('#loadingModal').remove()
        }

        $('#equizContact').submit(function (e) {
            e.preventDefault()
            sumitContact();
        })

        $('#refresh-captcha').click(function (e) {
            e.preventDefault()
            loadImageCaptcha()
        })

        loadImageCaptcha()

    });
</script>