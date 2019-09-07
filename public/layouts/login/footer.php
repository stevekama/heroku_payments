    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
<script>
$('#registerForm').submit(function(event){
  event.preventDefault();
  var form_data = $(this).serialize();
  $.ajax({
    url : base_url+'api/users/insert.php',
    type: 'POST',
    data:form_data,
    dataType: 'json',
    beforeSend:function(){
      $('#registrationBtn').html('Loading...');
    },
    success:function(data){
      if(data.message == 'success'){
        window.location.href = base_url+'index.php';  
      }

      if(data.message == 'emailError'){
        $('#messageAlert').html('<div class="alert alert-danger alert-dismissible">Email Entererd Exist. Please Check and Try again...</div>');
        return false;
      }

      if(data.message == 'failed'){
        $('#messageAlert').html('<div class="alert alert-danger alert-dismissible">Failed To Register user. Please Try again..</div>');
        return false;
      }

      if(data.message == 'errorPass'){
        $('#messageAlert').html('<div class="alert alert-danger alert-dismissible">Failed To Register user. The password entered do not match. Please check and try again...</div>');
        return false;
      }
    }
  });
});
</script>
</body>
</html>