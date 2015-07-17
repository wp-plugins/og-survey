jQuery(document).ready(function($) {
  var totalQuestions = $('.og-survey-main-questions').children('li').length;
  var currentQuestionNumber = parseInt($('#og-survey-question-number').val());
  if(totalQuestions == currentQuestionNumber) {
    $('#og-survey-previous-question').hide();
    $('#og-survey-next-question').hide();
    $('#og-survey-question-progress').hide();
  } else {
    $('#og-survey-previous-question').hide();
    $('#og-survey-form-submit').hide();
  }
  $('.og-survey-main-questions').children('li').hide();
  $('.og-survey-main-questions').children('li:first-child').show();
  $('#og-survey-question-progress').children('li:first-child').find('img').css('width', 'auto');
  $('#og-survey-previous-question').on('click', function() {
    var questionNumber = parseInt($('#og-survey-question-number').val());
    var updatedQuestionNumber = questionNumber - 1;
    $('#og-survey-question-number').val(updatedQuestionNumber);
    $('#og-survey-next-question').css('display', 'inline');
    $('#og-survey-form-submit').hide();
    if(updatedQuestionNumber == 1) {
      $(this).hide();
    }
    $('.og-survey-main-questions').children('li:nth-child('+questionNumber+')').hide();
    $('.og-survey-main-questions').children('li:nth-child('+updatedQuestionNumber+')').fadeIn(2500);
    $('#og-survey-question-progress').children('li:nth-child('+questionNumber+')').find('img').css('width', '10px');
    $('#og-survey-question-progress').children('li:nth-child('+updatedQuestionNumber+')').find('img').css('width', 'auto');
  });
  
  $('#og-survey-next-question').on('click', function() {
    var questionNumber = parseInt($('#og-survey-question-number').val());
    var updatedQuestionNumber = questionNumber + 1;
    $('#og-survey-question-number').val(updatedQuestionNumber);
    $('#og-survey-previous-question').css('display', 'inline');
    if(totalQuestions == updatedQuestionNumber) {
      $(this).hide();
      $('#og-survey-form-submit').show();
    }
    $('.og-survey-main-questions').children('li:nth-child('+questionNumber+')').hide();
    $('.og-survey-main-questions').children('li:nth-child('+updatedQuestionNumber+')').fadeIn(2500);
    $('#og-survey-question-progress').children('li:nth-child('+questionNumber+')').find('img').css('width', '10px');
    $('#og-survey-question-progress').children('li:nth-child('+updatedQuestionNumber+')').find('img').css('width', 'auto');
  });
  
  $('.og-survey-main-questions').find('li').each(function() {
    $(this).find('ul.og-survey-main-questions-answers').find('li').each(function() {
      $(this).find('input[type=radio]').each(function() {
        var thisInputRadio = $(this);
        var thisSpan = $(this).parent('span');
        var thisUl = $(this).parent('span').parent('li').parent('ul');
        var thisLi = $(this).parent('span').parent('li');
        var questionId = $(this).data('id');
        $(this).on('click', function() {
          var loader = '<img src="'+valueObject.loaderPath+'" class="ajax-loader" alt="" />';
          $(loader).insertAfter(thisSpan);
          var previousButton = $('#og-survey-previous-question');
          var nextButton = $('#og-survey-next-question');
          var isPreviousButtonHidden = 0;
          var isNextButtonHidden = 0;
          if($(previousButton).css('display') != 'none') {
            $(previousButton).hide();
            isPreviousButtonHidden++;
          }
          if($(nextButton).css('display') != 'none') {
            $(nextButton).hide();
            isNextButtonHidden++;
          }
          $.ajax({
            type: 'POST',
            url: valueObject.ajaxUrl,
            data: {'action': 'find_sub_question', 'questionId': questionId, 'answer': $(this).val()},
            success: function(resp) {
              $(thisLi).find('img.ajax-loader').remove();
              if(isPreviousButtonHidden == 1) {
                $(previousButton).css('display', 'inline');
              }
              if(isNextButtonHidden == 1) {
                $(nextButton).css('display', 'inline');
              }
              isPreviousButtonHidden = 0, isNextButtonHidden = 0;
              $(thisUl).find('ul.og-survey-main-questions-answers-sub-questions').remove();
              if(resp != '') {
                $(resp).insertAfter(thisSpan);
                $(thisLi).find('ul.og-survey-main-questions-answers-sub-questions-answers').find('li').each(function () {
                  $(this).find('input[type=radio]').on('click', function () {
                    $(thisInputRadio).prop('checked', false);
                  });
                });
              }
            }
          });
        });
      });
    });
  });
});