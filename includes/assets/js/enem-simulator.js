var timerInterval;
var alertTimeInterval;
var endSimulator = false;
var timerSimulator = 0;
var GRADE = 1000;

jQuery(document).ready(function( $ ) {

	$('#start-simulator').on('click', function() {
    let register = localStorage.getItem('enem-simulator-register');
    if(!register)
      $('#simulator-modal-register').modal('toggle');
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

  $('#simulator-modal-register').on('hide.bs.modal', function() {
    startSimulator();
  });

  $('#enem-simulator-revise').on('click', function(event){
    event.preventDefault();
    $('#simulator-modal-finish').modal('toggle');
  });

  $('.new-simulator').on('click', function(event){
    event.preventDefault();
    $('#simulator-modal-new-simulator').modal('toggle');
  });

  $('#new-simulator').on('click', function(){
    location.reload(); 
  });

  $('#finisish-simulator').on('click', function(e) {
    e.preventDefault();
    $('#simulator-modal-finish').modal('toggle');
    finishSimulator();
  });

  $('.question').each(function(e) {
    if (e != 0) $(this).hide();
  })

  $('#next-question').on('click', function(e) {
    e.preventDefault();

    if ($('.question:visible').next().length == 0) {
      let category;
      if($('.categories .content-question:visible').next().length > 0) 
        category = $('.categories .content-question:visible').next();
      else if($('.categories .content-question:visible').prev().length > 0) 
        category = $('.categories .content-question:visible').prev();
      let id = category.find('.question').eq(0).attr('id');
      let name = category.attr('id');
      getNextCategory(id, name);
      return true;
    }

    if ($('.question:visible').next().length != 0)
      $('.question:visible').next().show().prev().hide();
    else {
      $('.question:visible').hide();
      $('.question:first').show();
    }

    $(this).parent().removeClass('disabled');
    $(this).parent().prev().removeClass('disabled');

    scroollTo($('.simulator-content'));

    let categoryIndex = $('.question:visible').parent().attr('data-category-index');
    let questionIndex = $('.question:visible').find('.question-options').attr('data-question-index');

    setVisitedQuestion(categoryIndex, questionIndex);

  });

  $('#previous-question').on('click', function(e) {
    e.preventDefault();

    if ($('.question:visible').prev().length == 0) {
      let category;
      if($('.categories .content-question:visible').prev().length > 0) 
        category = $('.categories .content-question:visible').prev();
      if(typeof category !== 'undefined') {
        let id = category.find('.question:last').eq(0).attr('id');
        let name = category.attr('id');
        getNextCategory(id, name);
      } 
      return true;
    }
    
    scroollTo($('.simulator-content'));
    
    if ($('.question:visible').prev().length != 0)
      $('.question:visible').prev().show().next().hide();
    else {
      $('.question:visible').hide();
      $('.question:last').show();
    }

    $(this).parent().removeClass('disabled');
    $(this).parent().next().removeClass('disabled');

  });

  $(document).on('click', 'input[type=checkbox]', function(e) {
    if(endSimulator) return false;

    let $box = $(this);

    if($box.is(':checked')) {

      var group = $('input:checkbox[name=' + $box.attr("name") + ']:checked');
      
      if(enem_simulator.test_change_alert && group.length > 1) {
        const modal = new Promise(function(resolve, reject){
          $('#simulator-modal-alter').modal('toggle');  
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

    hideQuestions(); 

    if(!window.endSimulator) {
      $('.simulator-nav').fadeIn();
      setNav();
    } else {
      $('.simulator-result').fadeIn();
    }
    scroollTo($('.simulator-nav'));
  });

  $(document).on('click', '.question-nav-item', function(e){
    let id = $(this).attr('data-question-id');
    let name = $(this).attr('data-category-name');
    getNextCategory(id, name);
  });

  $('.fullscreem-simulator').on('click', function() {
    toggleFullScreen();
  });

  $('.incease-font-simulator').on('click', function() {
    modifyFontSize('increase');
  });

  $('.decrease-font-simulator').on('click', function() {
    modifyFontSize('decrease');
  });

  function getNextCategory(questionID, categoryName) {
    let question = $('#' + questionID);
    let category = $('#' + categoryName);

    hideQuestions();
    
    $('.simulator-content').fadeIn();
    $('.simulator-nav').hide();
    $('.question').hide();
    $('.simulator-result').hide();

    if(window.endSimulator) {
      $('.timer').hide();
      $('.simulator-progress').hide();
      $('.revise ').hide();
    }
    
    category.fadeIn();
    question.fadeIn();

    scroollTo($('.simulator-content'));
    setProgressbar(category, $(".progress-bar"));

    let categoryIndex = category.attr('data-category-index');
    let questionIndex = question.find('.question-options').attr('data-question-index');

    setVisitedQuestion(categoryIndex, questionIndex);
  }

  function hideQuestions() {
    $('.simulator-content').hide();
    $('.question').hide();
    $('.content-question').hide();
  }

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
        
        $('.content-question:visible .question').eq(0).fadeIn();

        $('.simulator-pagination').show();
        $('.simulator-progress').show();
        $('.simulator-footer').show();

        $('.simulator-header').hide();
        $('.simulator-category-options').hide();

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
    }, 1000);
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

  function toggleFullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen(); 
      }
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


