jQuery(document).ready(function( $ ) {
	
	$('#start-simulator').on('click', function() {

    var category = $('#question_category').children('option:selected').val();

    $.ajax({
      type: 'POST',
      url: 'http://wordpress/wp-admin/admin-ajax.php',
      data: {
              action : 'enem_simulator_get_question_category',
              category: category
            },
      success: function( response ) {
        $('.content-answer').empty();
        $('.content-answer').html( response );
      }
    })
  });
	
});