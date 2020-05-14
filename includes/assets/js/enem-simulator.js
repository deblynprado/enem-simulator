jQuery(document).ready(function( $ ) {
	console.log(enem_simulator.ajaxurl)
	$('#start-simulator').on('click', function() {

    var category = { 
                      name:  $('#question_category').children('option:selected').text() , 
                      value: $('#question_category').children('option:selected').val() 
                    };

    $.ajax({
      type: 'POST',
      url: enem_simulator.ajaxurl,
      data: {
          action : 'enem_simulator_get_question_category',
          category: category
      },
      success: function( response ) {
        $('.content-question').empty();
        $('.content-question').html( response );
        
        $('.question').eq(0).show('slow');

        $('.nav').show('slow');
        $('.progress').show('slow');

        $('.simulator-header').hide('slow');
        $('.simulator-category-options').hide('slow');

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
    setProgressbar();
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
    setProgressbar();
    return false;

  });

  $(document).on('click', 'input[type=checkbox]', function(e) {
    let $box = $(this);

    if ($box.is(':checked')) {
      var group = 'input:checkbox[name=' + $box.attr("name") + ']';
      $(group).prop('checked', false);
      $box.prop('checked', true);
    } else {
      $box.prop('checked', false);
    }
  });

  function setProgressbar() {
    let questions = $('.question');
    let progressBar = $(".progress-bar");
    let count = questions.length;
    let index =  $('.question:visible').index();

    let percent = 100 / count * (index + 1);

    progressBar.attr('aria-valuenow', percent);
    progressBar.html(percent);

    let delay = 500;
    progressBar.each(function(i) {
      $(this).delay(delay * i).animate({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);

      $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
      }, {
        duration: delay,
        // easing: 'swing',
        step: function(now) {
          $(this).text(Math.ceil(now) + '%');
        }
      });
    });
  }

});


