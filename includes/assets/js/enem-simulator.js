jQuery(document).ready(function( $ ) {
	
	$('#question_category').on('change', function() {

    var category = $(this).children('option:selected').val();

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