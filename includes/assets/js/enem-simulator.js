var timerInterval;
var alertTimeInterval;
var endSimulator = false;
var timerSimulator = 0;
var GRADE = 1000;

jQuery(document).ready(function( $ ) {

	$('#start-simulator').on('click', function() {
    let register = localStorage.getItem('enem-simulator-register');
    if(!register)
      $('#enem-simulator-modal-register').modal('toggle');
    else
      startSimulator();
  });

  $('#register-simulator').on('click', function() {
    localStorage.setItem('enem-simulator-register', 1);
    $.ajax({
      type: 'POST',
      url:enem_simulator.ajaxurl,
      data: {
        action: 'enem_simulator_add_user_register',
        name: $('#name').val(),
        mail: $('#mail').val(),
        whatsapp: $('#whatsapp').val()
      },
      success: function(response) {
        console.log(response);
      }
    });
  });

  $('#enem-simulator-modal-register').on('hide.bs.modal', function() {
    startSimulator();
  });

  $('.new-simulator').on('click', function(){
    $('#enem-simulator-modal-new-simulator').modal('toggle');
  });

  $('#new-simulator').on('click', function(){
    location.reload(); 
  });

  $('#finisish-simulator').on('click', function(e) {
    e.preventDefault();
    $('#enem-simulator-modal-finish').modal('toggle');
    finishSimulator();
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

    scroollTo($('.simulator-content'));

    let categoryIndex = $('.question:visible').next().parent().attr('data-category-index');
    let questionIndex = $('.question:visible').next().find('.question-options').attr('data-question-index');

    setVisitedQuestion(categoryIndex, questionIndex);

  });

  $('#previous-question').on('click', function(e) {
    e.preventDefault();
    
    scroollTo($('.simulator-content'));
    
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

  });

  $(document).on('click', 'input[type=checkbox]', function(e) {
    if(endSimulator) return false;

    let $box = $(this);

    if($box.is(':checked')) {

      var group = $('input:checkbox[name=' + $box.attr("name") + ']:checked');
      
      if(enem_simulator.test_change_alert && group.length > 1) {
        const modal = new Promise(function(resolve, reject){
          $('#enem-simulator-modal-alter').modal('toggle');  
          $('#alter-simulator').click(function(){
            resolve();
          });
          $('#alter-simulator-dismiss').click(function(){
            reject();
          });
          $('.close').click(function(){
            resolve();
          });
        }).then(function(){
          group.prop('checked', false);
          $box.prop('checked', true);
          checkboxChecked($box);
        }).catch(function(err){
          $box.prop('checked', false);
          console.log(err);
        });
      } else {
        group.prop('checked', false);
        $box.prop('checked', true);
        checkboxChecked(this);
      }
    } else {
      $box.prop('checked', false);
    }

  });

  $('.simulator-category-list').on('click', function(e){
    e.preventDefault();

    $('.simulator-content').hide('slow');
    $('.question').hide();
    $('.content-question').hide();

    if(!window.endSimulator) {
      $('.simulator-nav').show('slow');
      setNav();
    } else {
      $('.simulator-result').show('slow');
    }
    scroollTo($('.simulator-content'));
  });

  $(document).on('click', '.question-nav-item', function(e){
    let id = $(this).attr('data-question-id');
    let name = $(this).attr('data-category-name');
    let question = $('#' + id);
    let category = $('#' + name);
    
    $('.simulator-content').show('slow');
    $('.simulator-nav').hide('slow');
    $('.question').hide();
    $('.simulator-result').hide('slow');

    if(window.endSimulator) {
      $('.timer').hide();
      $('.simulator-progress').hide();
      $('.revise ').hide();
    }
    
    if(question.next().length != 0) 
      $('#next-question').parent().removeClass('disabled');
    else
      $('#next-question').parent().addClass('disabled');

    if(question.prev().length != 0)
      $('#previous-question').parent().removeClass('disabled');
    else
      $('#previous-question').parent().addClass('disabled');

    category.show('slow');
    question.show('slow');

    scroollTo($('.simulator-content'));
    setProgressbar(category, $(".progress-bar"));

    let categoryIndex = category.attr('data-category-index');
    let questionIndex = question.find('.question-options').attr('data-question-index');

    setVisitedQuestion(categoryIndex, questionIndex);

  });

  $('.fullscreem-simulator').on('click', function() {
    if(!window.screenTop && !window.screenY) 
      closeFullscreen();
    else
      openFullscreem(document.documentElement);
  });

  $('.incease-font-simulator').on('click', function() {
    modifyFontSize('increase');
  });

  $('.decrease-font-simulator').on('click', function() {
    modifyFontSize('decrease');
  });

  function startSimulator() {
    var category = $('#question_category').children('option:selected').val();
    var theIDS = enem_simulator.the_ids;
    var categories = enem_simulator.categories;

    $.ajax({
      type: 'POST',
      url: enem_simulator.ajaxurl,
      data: {
        action : 'enem_simulator_get_question_category',
        category: category,
        the_ids: theIDS,
        categories: categories
      },
      success: function(response) {

        let container = $( '<div class="categories">' + response + '</div>' );
        let result = setQuestionContainer( container );

        $('.simulator-categories').empty().html( result );
        
        $('.content-question:visible .question').eq(0).show('slow');

        $('.simulator-pagination').show('slow');
        $('.simulator-progress').show('slow');
        $('.simulator-footer').show('slow');

        $('.simulator-header').hide('slow');
        $('.simulator-category-options').hide('slow');

        scroollTo($('.simulator-content'));

        setTimer();

        let categoryIndex = $('.question:visible').parent().attr('data-category-index');
        let questionIndex = $('.question:visible .question-options').attr('data-question-index');

        setVisitedQuestion(categoryIndex, questionIndex);

      }
    });

    $.ajax({
      type: 'POST',
      url: enem_simulator.ajaxurl,
      data: {
          action : 'enem_simulator_get_nav',
          the_ids: theIDS,
          categories: categories
      },
      success: function(response){
        $('.simulator-nav-categories').empty().html(response);
        $('.simulator-result-categories').empty().html(response);
        $('.simulator-result-categories .progress').remove();
      }
    });
  }

  function finishSimulator() {
    $('#enem-simulator-revise').parent().addClass('disabled');
    $('.simulator-content').hide('slow');
    $('.simulator-nav').hide('slow');
    $('.simulator-result').show('slow');

    stopTimer(); 
    checkAnswers();
    setResults();
    scroollTo($('.simulator-content'));
  }

  function checkboxChecked(element) {
    let categoryIndex = $(element).parents('.content-question').attr('data-category-index');
    let questionIndex = $(element).parents('.question-options').attr('data-question-index');
    let question = getQuestion(categoryIndex, questionIndex);

    question.user_answer.number = $(element).val();

    setQuestion(categoryIndex, questionIndex, question);
    
    setProgressbar($(element).parents('.content-question'), $(".progress-bar"));
  }

  function setProgressbar(parent, progressBar) {
    let questionsChecked = parent.find('.question input[type=checkbox]:checked');
    let questions = parent.find('.question');
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

      if(hAlertTime === h && mAlertTime === m && sAlertTime === s && enem_simulator.end_test_alert) {
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
        window.endSimulator = true;
        finishSimulator();
        return;
      }

      maxiumTime--;
      window.timerSimulator++;
    
    }, 1000);
  }

  function stopTimer() {
    clearInterval(timerInterval);
    clearInterval(alertTimeInterval);
    window.endSimulator = true;
  }

  function displayTimer(value) {
    let s, m, h;
    m = parseInt(value / 60);
    h = parseInt(m / 60);
    s =  value % 60;

    if(h < 10) 
        h = '0' + h;
    if(m < 10) 
      m = '0' + m;
    if(s < 10) 
      s = '0' + s;

    return h + ':' + m + ':' + s;
  }

  function setQuestionContainer(elements) {

    let categories = new Array();
    
    elements.find('.content-question').each(function(e) {

      let questionsOptions = $(this).find('.question-options');
      let questions = new Array();

      questionsOptions.each(function(e) {

        let questionId = $(this).parent().attr('id');
        let weightInput = $(this).parent().find("#weight_" + questionId);
        let weight = weightInput.val();
        weightInput.remove();

        var question = questionFactory();
        question.weight = weight;
        var correctAnswer = $(this).find('input[type=hidden]');

        correctAnswer.each(function(e) {
          if($(this).val() == 'correct') {
            question.correct_answer = {
              number: $(this).next().val(),
            }
          }
        });
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

  function setNav() {
    $('.simulator-nav .question-nav-item').each(function(e) {

      let id = $(this).attr('data-question-id');
      let name = $(this).attr('data-category-name');
      let question = $('#' + id);
      let category = $('#' + name);

      let categoryIndex = category.attr('data-category-index');
      let questionIndex = question.find('.question-options').attr('data-question-index');

      let item = getQuestion(categoryIndex, questionIndex);

      if(item.user_answer.number) {
        $(this).addClass('bg-success');
        $(this).removeClass('bg-warning');
        $(this).removeClass('bg-danger');
      }
       else if(item.visited) {
        $(this).addClass('bg-warning');
        $(this).removeClass('bg-success');
        $(this).removeClass('bg-danger');
      }
      else {
        $(this).addClass('bg-danger');
        $(this).removeClass('bg-warning');
        $(this).removeClass('bg-success');
      }
    });

    $('.progress-bar-nav').each(function() {
      let name = $(this).attr('data-category-name');
      let category = $('#' + name);

      setProgressbar(category, $(this));
    });
  }

  function setResults() {
    $('.simulator-result .question-nav').each(function() {
      
      let correct = 0;
      
      let easy = 0;
      let easyCorrect = 0;
      let easyAverage = 0;

      let normal = 0;
      let normalCorret = 0;
      let normalAverage = 0;

      let hard = 0;
      let hardCorrect = 0;
      let hardAverage = 0;

      let sumProduct = 0;
      let sumWeight = 0;

      let name;

      $(this).find('.question-nav-item').each(function() {
        let id = $(this).attr('data-question-id');
        name = $(this).attr('data-category-name');
        let question = $('#' + id);
        let category = $('#' + name);

        let categoryIndex = category.attr('data-category-index');
        let questionIndex = question.find('.question-options').attr('data-question-index');

        let item = getQuestion(categoryIndex, questionIndex);
        let weight = parseInt(item.weight);

        if(weight <= 3)
          easy++;
        if(weight > 3 && weight <= 5)
          normal++;
        if(weight > 5)
          hard++;

        if(item.user_answer.number === item.correct_answer.number) {
          $(this).addClass('bg-success');
          $(this).removeClass('bg-warning');
          $(this).removeClass('bg-danger');
          correct++;

          if(weight <= 3)
            easyCorrect++;
          if(weight > 3 && weight <= 5)
            normalCorret++;
          if(weight > 5)
            hardCorrect++;

          sumProduct += GRADE * weight;
        }
        else if(!item.user_answer.number) {
          $(this).addClass('bg-warning');
          $(this).removeClass('bg-danger');
          $(this).removeClass('bg-success');
        }
        else {
          $(this).addClass('bg-danger');
          $(this).removeClass('bg-warning');
          $(this).removeClass('bg-success');
        }

        sumWeight = sumWeight + weight;

      });

      easyAverage = easyCorrect / easy;
      normalAverage = normalCorret / normal;
      hardAverage = hardCorrect / hard;

      let arithmeticResult = sumProduct / sumWeight;
      let finalResult = 0;

      if(easyAverage === 1 && normalAverage === 1 && hardAverage === 1)
        finalResult = arithmeticResult + (arithmeticResult / 100 * enem_simulator.weight_proficiency);
      else if(hardAverage > easyAverage) 
        finalResult = arithmeticResult - (arithmeticResult / 100 * enem_simulator.weight_proficiency);
      else 
        finalResult = arithmeticResult;

      $('.' + name + ' .enem-simulator-successes').html(correct);
      $('.' + name + ' .enem-simulator-grade').html(Number(finalResult).toFixed(1));

    });

    $('.timer-simulator-result').html(displayTimer(window.timerSimulator));
    $('.timer-simulator-max-time').html(displayTimer(enem_simulator.maximum_time));

  }

  function checkAnswers() {
    $('.content-question').each(function(e) {

      let categoryIndex = $(this).attr('data-category-index');
      let questions = $(this).find('.question-options');

      questions.each(function(e) {
        let questionIndex = $(this).attr('data-question-index');
        let userAnswer = $(this).find('input[type=checkbox]');

        userAnswer.each(function(e) {
          var number = $(this).val();

          if($(this).is(':checked')) {
            if(getQuestion(categoryIndex, questionIndex).correct_answer.number === number) {
              $(this).addClass('is-valid');
            } else {
              $(this).addClass('is-invalid');
            }
          } else if(getQuestion(categoryIndex, questionIndex).correct_answer.number === number) {
            $(this).addClass('is-valid');
          }
        });

      });
    })
  }

  function questionFactory() {
    return {
      weight: 0,
      correct_answer: {
        number:'',
      },
      user_answer: {
        number:'',
      },
      visited: 0
    }
  }
  
  function setVisitedQuestion(categoryIndex, questionIndex) {
    let question = getQuestion(categoryIndex, questionIndex);
    question.visited = 1;
    setQuestion(categoryIndex, questionIndex, question);
  }

  function setQuestion(categoryIndex, questionIndex, question) {
    let categories = getItemStorage();
    categories[categoryIndex].questions[questionIndex] = question;
    setItemStorage(categories);
  }

  function getQuestion(categoryIndex, questionIndex) {
    let categories = getItemStorage();
    return categories[categoryIndex].questions[questionIndex];
  }

  function setItemStorage(item) {
    localStorage.setItem('enem_simulator_question', JSON.stringify(item)); 
  }

  function getItemStorage() {
    return JSON.parse(localStorage.getItem('enem_simulator_question'));
  }

  function openFullscreem(element) {
    if (element.requestFullscreen) {
      element.requestFullscreen();
    } else if (element.mozRequestFullScreen) { /* Firefox */
      element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
      element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) { /* IE/Edge */
      element.msRequestFullscreen();
    }
  }

  function closeFullscreen() {
    document.mozCancelFullScreen();
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    }
  }

  function modifyFontSize(flag) {
    let element = $('#enem-simulator');
    let curretnFontSize = parseInt(element.css('font-size'));

    if((curretnFontSize == 27 && flag == 'increase') || 
       (curretnFontSize == 12 && flag == 'decrease')) return;

    if(flag == 'increase')
      element.css('font-size', curretnFontSize+3);
    if(flag == 'decrease')
      element.css('font-size', curretnFontSize-3);
  }

});


