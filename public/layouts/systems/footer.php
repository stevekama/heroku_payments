            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
                </div>
                <strong>
                    Copyright &copy; 2019 <a href="<?php echo base_url(); ?>">Payments Gateway</a>.
                </strong> All rights reserved.
            </footer>
            <!-- Add the sidebar's background. This div must be placed
            immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->
        <script>
            function find_user(){
                var action = "FETCH_USER";
                $.ajax({
                    url:base_url+'api/users/users.php',
                    type:'POST',
                    data:{action:action},
                    dataType:"json",
                    success:function(data){
                        if(data.message == 'notLoggedIn'){
                            window.location.href = base_url + 'index.php';
                        }else{
                            $('#userFullNames').html(data.fullnames);
                            $('#userName').html(data.username);
                            $('#userEmailAddress').html(data.email);
                        }
                    }
                });
            }
            find_user();
            $(document).on('click', '.logout', function(){
                var action = "LOGOUT";
                $.ajax({
                    url:base_url+'api/users/users.php',
                    type:'POST',
                    data:{action:action},
                    dataType:"json",
                    success:function(data){
                        if(data.message == 'success'){
                            window.location.href = base_url+'index.php';
                        }
                    }
                });
            });
        </script>
    </body>
</html>