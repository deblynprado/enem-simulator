var timerInterval;
var alertTimeInterval;
var endSimulator = false;

jQuery(document).ready(function( $ ) {

	$('#start-simulator').on('click', function() {

    var category = $('#question_category').children('option:selected').val();

    $.ajax({
      type: 'POST',
      url: enem_simulator.ajaxurl,
      data: {
          action : 'enem_simulator_get_question_category',
          category: category
      },
      success: function(response) {

        let container = $( '<div class="categories">' + response + '</div>' );
        let result = setQuestion( container );

        $('.simulator-categories').empty().html( result );
        
        $('.content-question:visible .question').eq(0).show('slow');

        $('.simulator-pagination').show('slow');
        $('.simulator-progress').show('slow');
        $('.simulator-footer').show('slow');

        $('.simulator-header').hide('slow');
        $('.simulator-category-options').hide('slow');

        scroollTo($('.entry-content'));

        setTimer();

      }
    });

    $.ajax({
      type: 'POST',
      url: enem_simulator.ajaxurl,
      data: {
          action : 'enem_simulator_get_nav'
      },
      success: function(response){
        $('.simulator-nav-categories').empty().html(response);
      }
    });
  });

  $('#revise-question').on('click', function(e) {
    e.preventDefault();
    $(this).parent().addClass('disabled');

    stopTimer(); 
    checkAnswers();

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

    scroollTo($('.entry-content'));

    return false;

  });

  $('#previous-question').on('click', function(e) {
    e.preventDefault();

    scroollTo($('.entry-content'));
    
    if ($('.question:visible').prev().length != 0)
      $('.question:visible').prev().show('fast').next().hide('fast');
    else {
      $('.question:visible').hide('fast');
      $('.question:last').show('fast');
    }

    $(this).parent().removeClass('disabled');
    $(this).parent().next().removeClass('disabled');

    if ($('.question:visible').prev().prev().length == 0)
      $(this).parent().addClass('disabled');      

    return false;

  });

  $(document).on('click', 'input[type=checkbox]', function(e) {
    if(endSimulator) return false;

    let $box = $(this);

    if ($box.is(':checked')) {
      var group = 'input:checkbox[name=' + $box.attr("name") + ']';
      $(group).prop('checked', false);
      $box.prop('checked', true);
    } else {
      $box.prop('checked', false);
    }

    let categories = getItemStorage();
    let categoryIndex = $(this).parent().parent().parent().parent().attr('data-category-index');
    let questionIndex = $(this).parent().parent().attr('data-question-index');
    let question = categories[categoryIndex].questions[questionIndex];

    question.user_answer.number = $(this).val();

    categories[categoryIndex].questions[questionIndex] = question;

    setItemStorage(categories);

    setProgressbar($(this).parent().parent().parent().parent());

  });

  $('.simulator-category-list').on('click', function(e){
    e.preventDefault();
    $('.simulator-content').hide('slow');
    $('.question').hide();
    $('.content-question').hide();
    $('.simulator-nav').show('slow');
    scroollTo($('.entry-content'));
  });

  $(document).on('click', '.question-nav-item', function(e){
    let id = $(this).attr('data-question-id');
    let name = $(this).attr('data-category-name');
    let question = $('#' + id);
    let category = $('#' + name);
    $('.simulator-content').show('slow');
    $('.simulator-nav').hide('slow');
    category.show('slow');
    question.show('slow');
    scroollTo($('.entry-content'));
    setProgressbar(category);
  });

  function setProgressbar(parent) {
    console.log(parent);
    let questionsChecked = parent.find('.question input[type=checkbox]:checked');
    let questions = parent.find('.question');
    let progressBar = $(".progress-bar");
    let count = questions.length;
    let checked =  questionsChecked.length;

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
    let alertTime = enem_simulator.alert_time;

    mAlertTime = parseInt(alertTime / 60);
    hAlertTime = parseInt(mAlertTime / 60);
    sAlertTime =  alertTime % 60;

    timerInterval = setInterval(function() {
     
      m = parseInt(maxiumTime / 60);
      h = parseInt(m / 60);
      s =  maxiumTime % 60;

      let p = timer.find('p');

      if(hAlertTime === h && mAlertTime === m && sAlertTime === s) {
        p.stop(true, true).addClass('text-danger', 1000);
        if(!alertTimeInterval) {
          alertTimeInterval = setInterval(function() {
            p.fadeOut(500);
            p.fadeIn(500);
          }, 1000);
        }
      }

      if(h < 10) 
        h = '0' + h;
      if(m < 10) 
        m = '0' + m;
      if(s < 10) 
        s = '0' + s;

      result = h + ':' + m + ':' + s;

      p.html(result);

      if(maxiumTime == 0) {
        $('.revise').addClass('disabled');
        stopTimer();
        checkAnswers();
        endSimulator = true;
        return;
      }

      maxiumTime--;
    
    }, 1000);
  }

  function stopTimer() {
    clearInterval(timerInterval);
    clearInterval(alertTimeInterval);
  }

  function setQuestion(elements) {

    let categories = new Array();
    
    elements.find('.content-question').each(function(e) {

      let questionsOptions = $(this).find('.question-options');
      let questions = new Array();

      questionsOptions.each(function(e) {
        var question = questionFactory();
        question.post_id = $(this).attr('data-question-id');

        var correctAnswer = $(this).find('input[type=hidden]');
        correctAnswer.each(function(e) {
          if($(this).val() == 'correct') 
            question.correct_answer = {
              number: $(this).next().val(),
            }
        }).remove();
        questions.push(question);
      });

      let category = {
        name: $(this).attr('id'),
        questions: questions
      };

      categories.push(category);

    });
    
    setItemStorage(categories); 

    return elements;
  }

  function checkAnswers() {
    let categoryIndex = $('.content-question:visible').attr('data-category-index');
    let questions = $('.content-question:visible .question-options');
    let categories = getItemStorage();

    questions.each(function(e) {
      var questionIndex = $(this).attr('data-question-index');
      var userAnswer = $(this).find('input[type=checkbox]');

      userAnswer.each(function(e) {
        var number = $(this).val();

        if($(this).is(':checked')) {
          if(categories[categoryIndex].questions[questionIndex].correct_answer.number === number) {
            $(this).addClass('is-valid');
          } else {
            $(this).addClass('is-invalid');
          }
        } else if(categories[categoryIndex].questions[questionIndex].correct_answer.number === number) {
          $(this).addClass('is-valid');
        }
      });

    });
  }

  function questionFactory() {
    return {
      post_id:'',
      correct_answer: {
        number:'',
      },
      user_answer: {
        number:'',
      }
    }
  }

  function setItemStorage(item) {
    localStorage.setItem('enem_simulator_question', JSON.stringify(item)); 
  }

  function getItemStorage() {
    return JSON.parse(localStorage.getItem('enem_simulator_question'));
  }

});


