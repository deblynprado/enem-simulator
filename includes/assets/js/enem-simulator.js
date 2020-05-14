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

        scroollTo($('.entry-content'));

        setTimer();

      }
    });
  });

  $('#revise-question').on('click', function() {
    
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

    $(this).parent().removeClass('disabled');
    $(this).parent().prev().removeClass('disabled');

    if ($('.question:visible').next().next().length == 0)
      $(this).parent().addClass('disabled');

    scroollTo($('.content-question'));

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

    $(this).parent().removeClass('disabled');
    $(this).parent().next().removeClass('disabled');

    if ($('.question:visible').prev().prev().length == 0)
      $(this).parent().addClass('disabled');      

    scroollTo($('.content-question'));

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

    setProgressbar();

  });

  function setProgressbar() {
    let questionsChecked = $('.question input[type=checkbox]:checked');
    let questions = $('.question');
    let progressBar = $(".progress-bar");
    let count = questions.length;
    let checked =  questionsChecked.length;

    console.log(count);
    console.log(checked);

    let percent = parseInt(100 / count * checked);

    progressBar.attr('aria-valuenow', percent);
    progressBar.html(percent + '%');
    progressBar.css('width', percent + '%');
    
  }

  function scroollTo(element) {
    $('html, body').animate({
      scrollTop: element.offset().top
    }, 2000);
  }

  function setTimer() {
    let timer = $('.timer');

    timer.show();

    let s, m, h;
    let maxiumTime = enem_simulator.maximum_time;

    console.log(maxiumTime);

    var x = setInterval(function() {
     
      m = parseInt(maxiumTime / 60);
      h = parseInt(m / 60);
      s =  maxiumTime % 60;

      if(h < 10) 
        h = '0' + h;
      if(m < 10) 
        m = '0' + m;
      if(s < 10) 
        s = '0' + s;

      result = h + ':' + m + ':' + s;

      timer.html(result);

      if(maxiumTime == 0) return;

      maxiumTime--;
    
  }, 1000);
  }

});


