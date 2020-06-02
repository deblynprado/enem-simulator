(function( $ ){
  const queryString = window.location.search;
  const ulrParams = new URLSearchParams(queryString);
  const page = ulrParams.get('page');
  if(page === 'enem-simulator-import-export') {
    
    let $submit = $('#submitdiv');
    $submit.parent().remove();
    
    let $exportQuestionsGroup = $('.acf-field-5ed1099030710');
    let $exportSettingsGroup = $('.acf-field-5ed55188efb44');
    let $importQuestionsGroup = $('.acf-field-5ed0f0e5631f1');
    let $importSettingsGroup = $('.acf-field-5ed5515fefb42');
    
    let exportQuestionsContainer = "<div class='acf-label'>"+
                                    "<p class='description'>Clique em exportar para salvar as questões em um arquivo csv.</p>"+
                                  "</div>"+
                                  "<div class='acf-actions'>"+
                                    "<button type='button' class='button button-primary button-large' id='export-questions-enem-simulator'>Exportar Questões</button>"+
                                  "</div>";

    let exportSettingsContainer = "<div class='acf-label'>"+
                                    "<p class='description'>Clique em exportar para salvar as configurações em um arquivo csv.</p>"+
                                  "</div>"+
                                  "<div class='acf-actions'>"+
                                    "<button type='button' class='button button-primary button-large' id='export-settings-enem-simulator'>Exportar Configurações</button>"+
                                  "</div>";

    let importQuestionsContainer = "<div class='acf-label'>"+
                                      "<p class='description'>Selecione um arquivo no formato csv para realizar a importação das questões.</p>"+
                                  "</div>"+
                                  "<div class='acf-input'>"+
                                    "<label for='upload' id='upload-questions-label-enem-simulator'><span>Nenhum arquivo selecionado </span>"+
                                      "<a href='#' class='acf-button button' id='import-questions-enem-simulator'>Adicionar Arquivo</a>"+
                                      "<input type='file' accept='.csv' id='upload-questions-enem-simulator' style='display:none'>"+
                                    "</label>"+
                                  "</div>"+
                                  "<div class='acf-actions'>"+
                                    "<button type='button' class='button button-primary button-large' id='import-questions-button-enem-simulator'>Importar Questões</button"+
                                  "</div>";

    let importSettignsContainer = "<div class='acf-label'>"+
                                    "<p class='description'>Selecione um arquivo no formato csv para realizar a importação das configurações.</p>"+
                                  "</div>"+
                                  "<div class='acf-input'>"+
                                    "<label for='upload' id='upload-settings-label-enem-simulator'><span>Nenhum arquivo selecionado </span>"+
                                      "<a href='#' class='acf-button button' id='import-settings-enem-simulator'>Adicionar Arquivo</a>"+
                                      "<input type='file' accept='.csv' id='upload-settings-enem-simulator' style='display:none'>"+
                                    "</label>"+
                                  "</div>"+
                                  "<div class='acf-actions'>"+
                                    "<button type='button' class='button button-primary button-large' id='import-settings-button-enem-simulator'>Importar Configurações</button"+
                                  "</div>";

    $exportQuestionsGroup.html(exportQuestionsContainer); 
    $exportSettingsGroup.html(exportSettingsContainer); 
    $importQuestionsGroup.html(importQuestionsContainer); 
    $importSettingsGroup.html(importSettignsContainer); 

    $('#upload-questions-enem-simulator').change(function() {
      let file = $(this)[0].files[0];
      if(file)
        $('#upload-questions-label-enem-simulator span').text(file.name+' ');
    });

    $('#upload-settings-enem-simulator').change(function() {
      let file = $(this)[0].files[0];
      if(file)
        $('#upload-settings-label-enem-simulator span').text(file.name+' ');
    });

    $('#import-questions-enem-simulator').on('click', function() {
      $('#upload-questions-enem-simulator').trigger('click'); 
    })

    $('#import-settings-enem-simulator').on('click', function() {
      $('#upload-settings-enem-simulator').trigger('click'); 
    })

    $('#import-questions-button-enem-simulator').on('click', function() {

      $(this).text('Carregando...');
      $(this).attr('disabled', 'disabled');
      
      let fd = new FormData();
      let file = $('#upload-questions-enem-simulator');
      let individual_file = file[0].files[0];
      fd.append('file', individual_file);
      fd.append('action', 'enem_simulator_import_questions');
      $.ajax({
        type: 'POST',
        url:enem_simulator.ajaxurl,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          let $button = $('#import-questions-button-enem-simulator');
          $button.text('Importar Questões');
          $button.removeAttr('disabled');
          adminNotices('success', response.message);
        },
        error: function() {
          adminNotices('error', 'Ocorreu um erro ao realizar a importação. Verifique o arquivo de importação e tente novamente');
        }
      });
    });

    $('#import-settings-button-enem-simulator').on('click', function() {

      $(this).text('Carregando...');
      $(this).attr('disabled', 'disabled');
      
      let fd = new FormData();
      let file = $('#upload-settings-enem-simulator');
      let individual_file = file[0].files[0];
      fd.append('file', individual_file);
      fd.append('action', 'enem_simulator_import_settings');
      $.ajax({
        type: 'POST',
        url:enem_simulator.ajaxurl,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          let $button = $('#import-settings-button-enem-simulator');
          $button.text('Importar Questões');
          $button.removeAttr('disabled');
          adminNotices('success', response.message);
        },
        error: function(response) {
          adminNotices('error', 'Ocorreu um erro ao realizar a importação. Verifique o arquivo de importação e tente novamente');
        }
      });
    });

    function adminNotices(type, message) {
      let element = '<div class="notice notice-'+type+' is-dismissible">'+
                      '<p>'+message+'</p>'+
                      '<button type="button" id="my-dismiss-admin-message" class="notice-dismiss">'+
                        '<span class="screen-reader-text">Dispensar este aviso.</span>'+
                      '</button>'+
                    '</div>';
      $(element).insertAfter('.wrap h1');
      $("#my-dismiss-admin-message").click(function(event) {
        event.preventDefault();
        $('.notice-'+type).fadeTo(100, 0, function() {
            $('.notice-'+type).slideUp(100, function() {
                $('.notice-'+type).remove();
            });
        });
      });
    }
  }
})(jQuery);