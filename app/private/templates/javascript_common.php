<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  
<script>
    $(document).on('click', '.post_this_form', function(e) {

        e.preventDefault();

        let button = this;
        let formid = $(this).attr('data-form');
        let target_url = $(this).attr('data-target-url');
        console.log(target_url);
        let success_url = $(this).attr('data-success-url');
        console.log(success_url);
        let frompaiload = $('#' + formid).serializeArray();
        console.log(frompaiload);
        //console.log(frompaiload);

        $.ajax({
            type: 'POST',
            url: target_url,
            data: frompaiload,
            success: function(msg) {
                console.log('here comes the message');
                console.log(msg);

                if (msg['state'] != 'success') {
                    console.log('no success');
                    $('#return_post_modalLabel').html('Fehlgeschlagen');
                    $('#return_post_modalFooter').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>');

                } else {
                    console.log('success');
                    $('#return_post_modalLabel').html('Erfolgreich');
                    $('#return_post_modalFooter').html('<button type="button" data-target="' + success_url + '"class="btn btn-primary return_post_modalSuccessBTN" >OK</button>');

                }
                console.log('switching modals');
                $('#return_post_modalBody').html(msg['MESSAGE']);
                $('#ajax_modal').modal('hide');
                $('#return_post_modal').modal('show');
            }
        });

    });

    $(document).on('click', '.return_post_modalSuccessBTN', function(e) {
        let target = $(this).attr('data-target');
        window.location.replace(target);
    });
</script>