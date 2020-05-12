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
        $('.content-question').empty();
        $('.content-question').html( response );
        
        $('.question').eq(0).show('slow');

        $('.nav').show('slow');

        $('.content-simulator').hide('slow');

        $('.end-simulator').show('slow');

      }
    });
  });

  $('#end-simulator').on('click', function() {
    
  });

  $('.question').each(function(e) {
    if (e != 0) $(this).hide('slow');
  })

  $('#next-question').on('click', function(e) {
    e.preventDefault();

    if ($('.question:visible').next().length != 0)
      $('.question:visible').next().show('slow').prev().hide('slow');
    else {
      $('.question:visible').hide('slow');
      $('.question:first').show('slow');
    }
    return false;

  });

  $('#previous-question').on('click', function(e) {
    e.preventDefault();
    
    if ($('.question:visible').prev().length != 0)
      $('.question:visible').prev().show('slow').next().hide('slow');
    else {
      $('.question:visible').hide('slow');
      $('.question:last').show('slow');
    }
    return false;

  });

});

