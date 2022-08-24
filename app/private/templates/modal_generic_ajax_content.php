<!-- AJAX Modal -->
<div class="modal fade" id="ajax_modal" tabindex="-1" aria-labelledby="ajax_modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_modal_content">

    </div>
  </div>
</div>

<script>


$(document).on('click','.show-ajax-modal', function (e) {

    e.preventDefault();
    
    let url = $(this).attr('href');
    console.log(url);

    $.ajax({
        url: url,
        type: 'get',
        success: function(response){
            $('#ajax_modal_content').html(response);
            $('#ajax_modal').modal('show'); 
        }
    });
});

</script>