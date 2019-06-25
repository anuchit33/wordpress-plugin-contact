<style>
.modal-footer .btn {
    background: none;
    background-image: url(/wp-content/plugins/Contact-captcha/templates/frontend/images/icon/bg_button_modal.png);
    background-size: contain;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    cursor: pointer;
    color: #404040;

    line-height: 22px;
}
.msg1 h1 {
    margin: 0;
}
img.btn-ic-resize {
    height: 31px;
    margin-top: -5px;
}
.modal-footer .btn:hover {
    opacity: 0.8;
}
.modal-body.msg-result1 .msg1 h1 {
    margin: 0;
    color: #40b742;
}
.modal-body.msg-result2 .msg1 h1 {
    margin: 0;
    color: #d6d249;
}
.modal-body.msg-result3 .msg1 h1 {
    margin: 0;
    color: #d66133;
}
.modal-body.msg-result4 .msg1 h1 {
    margin: 0;
    color: #ff4636;
}
</style>
<!-- Modal -->
<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    
    <div class="modal-content">
        <div class="modal-body msg-result1">

          <div class="msg2 text-center">
                <h2 class="text-green">ส่งข้อความเรียบร้อยแล้ว</h2>
          </div>
      </div>
      <div class="modal-footer">       
        <a  class="btn btn-secondary btn-close" data-dismiss="modal" >ตกลง</a>
      </div>
    </div>
  </div>
</div>
