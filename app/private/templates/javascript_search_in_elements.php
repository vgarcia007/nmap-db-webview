<script>
$(document).ready(function() {
    
  var form = document.querySelector('#search_in_elements_form');
  form.addEventListener('change', function() {
     
    });

  display_results();
});

$(function(){

  $('#search_in_elements_button').click(function(){
    event.preventDefault();
    display_results();

  });

});

function display_results(){
  var searchText = $('#search_in_elements').val().toLowerCase();
    
    $('#elements > .element').each(function(){
        
        var currentLiText = $(this).text().toLowerCase(),
            showCurrentLi = currentLiText.indexOf(searchText) !== -1;

        $('#'+this.id).removeClass('visually-hidden')

        if(showCurrentLi !== true){
            $('#'+this.id).addClass('visually-hidden')
        }
    });    
}
</script>