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
        $('.answer').eq(0).show();
        $('.nav').show();
      }
    })
  });

  $('#next-question').on('click', function(e) {
    e.preventDefault();
    $('.answer').hide();
    $('.answer').next().show();

  });

  $('#previous-question').on('click', function(e) {
    e.preventDefault();
    $('.answer').hide();
    $('.answer').prev().show();

  });
	
});