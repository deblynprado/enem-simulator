(function( $ ){
  const queryString = window.location.search;
  const ulrParams = new URLSearchParams(queryString);
  const page = ulrParams.get('page');
  if(page === 'enem-simulator-import-export') {
    
    let $submit = $('#submitdiv');
    $submit.parent().remove();
    
    let $exportGroup = $('.acf-field-5ed1099030710');
    let $importGroup = $('.acf-field-5ed0f0e5631f1');
    
    let exportContainer = "<div class='acf-label'>"+
                            "<label for='export-enem-simulator'>Exportar</label>"+
                            "<p class='description'>Clique em exportar para salvar as questões em um arquivo csv.</p>"+
                          "</div>"+
                          "<div class='acf-actions'>"+
                            "<button type='button' class='button button-primary button-large' id='export-enem-simulator'>Exportar Questões</button>"+
                          "</div>";

    let importContainer = "<div class='acf-label'>"+
                            "<label for='import-enem-simulator'>Importar</label>"+
                            "<p class='description'>Selecione um arquivo no formato csv para realizar a importação das questões.</p>"+
                          "</div>"+
                          "<div class='acf-input'>"+
                            "<label for='upload' id='upload-label-enem-simulator'><span>Nenhum arquivo selecionado </span>" +
                              "<a href='#' class='acf-button button' id='import-enem-simulator'>Adicionar Arquivo</a>"+
                              "<input type='file' accept='.csv' id='upload-enem-simulator' style='display:none'>"+
                            "</label>" +
                          "</div>"+
                          "<div class='acf-actions'>"+
                            "<button type='button' class='button button-primary button-large' id='import-button-enem-simulator'>Importar Questões</button"+
                          "</div>";

    $exportGroup.html(exportContainer); 
    $importGroup.html(importContainer); 

    $('#upload-enem-simulator').change(function() {
      let file = $(this)[0].files[0];
      if(file)
        $('#upload-label-enem-simulator span').text(file.name+' ');
    });

    $('#import-enem-simulator').on('click', function() {
      $('#upload-enem-simulator').trigger('click'); 
    })

    $('#import-button-enem-simulator').on('click', function() {

      $(this).text('Carregando...');
      $(this).attr('disabled', 'disabled');
      
      let fd = new FormData();
      let file = $('#upload-enem-simulator');
      let individual_file = file[0].files[0];
      fd.append('file', individual_file);
      fd.append('action', 'enem_simulator_import');
      $.ajax({
        type: 'POST',
        url:enem_simulator.ajaxurl,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          let $button = $('#import-button-enem-simulator');
          $button.text('Importar Questões');
          $button.removeAttr('disabled');
          let element = '<div class="acf-admin-notice notice notice-success is-dismissible">'+
                          '<p>'+response.message+'</p>'+
                          '<button type="button" id="my-dismiss-admin-message" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button>'+
                        '</div>';
          $(element).insertAfter('.wrap h1');
          $("#my-dismiss-admin-message").click(function(event) {
            event.preventDefault();
            $('.notice-success').fadeTo(100, 0, function() {
                $('.notice-success').slideUp(100, function() {
                    $('.notice-success').remove();
                });
            });
        });
        },
        error: function(response) {
          let element = '<div class="acf-admin-notice notice notice-error is-dismissible">'+
                          '<p>Ocorreu um erro ao realizar a importação. Verifique o arquivo de importação e tente novamente</p>'+
                          '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button>'+
                        '</div>';
          $('.wrap').prepend(element);
        }
      });
    });
  }
})(jQuery);