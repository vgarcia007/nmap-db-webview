<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content card shadow">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <article class="card-body">
                <h2 class="card-title text-center mb-4 mt-1 fw-bold">Anmeldung</h2>
                <p class="text-success text-center form-return-message">

                </p>
                <form class="form" enctype="multipart/form-data" method="POST" id="loginform">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                            <input name="UserName" id="UserName" class="form-control" placeholder="UserName" type="UserName">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input name="password" id="password" class="form-control" placeholder="******" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="keep_login" value="keep_login" class="me-2" checked>
                                    Angemeldet bleiben
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <button class="btn btn-success  btn-lg w-100 formpost" data-form="loginform"> Anmelden</button>
                </div>
            </article>
        </div>
    </div>
</div>

<script>

    $('.form-return-message').html('');

    $(document).on('click','.formpost', function (e) {

        e.preventDefault();

        $('.form-return-message').html('<img src="/img/loader.gif" height="50px" wheigt="50px;">');

        let button = this;
        let formid = $(this).attr('data-form');
        letfrompaiload = $('#'+formid).serializeArray();

        console.log(letfrompaiload);

        $.ajax({
            type:'POST',
            url:'/auth/login',
            data: letfrompaiload,
            success:function(msg){

                console.log(msg);

                if (msg['login'] != 'success') {
                    console.log('wrong');
                    $('.form-return-message').html(msg['message']);
                    $('.form-return-message').removeClass('text-success');
                    $('.form-return-message').addClass('text-danger');
                    
                }else{
                    console.log('ok');
                    $('.form-return-message').html(msg['message']);
                    $('.form-return-message').removeClass('text-danger');
                    $('.form-return-message').addClass('text-success');
                    window.location.replace("/");
                }
            }
        });

    });
</script>