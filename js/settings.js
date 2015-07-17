jQuery(document).ready(function($) {
  bindAddClick();
  adjustRemoveButton();
  function bindAddClick() {
    $('#survey-add').on('click', function (e) {
      e.preventDefault();
      var li = $(this).parents('li').clone(true);
      $(li).find('input[type=checkbox]').prop('checked', false);
      $(li).find('select').val('');
      $(li).find('input[type=text]').val('');
      var liCount = parseInt($(li).find('input[type=hidden]').val());
      liCount = liCount + 1;
      $(li).find('input[type=hidden]').val(liCount);
      $(li).find('input[type=checkbox]').prop('name', 'og_survey_settings_shortcode[survey_shortcode_questions][' + liCount + '][]');
      $('#og-survey-multiple-survey').append(li);
      $('#survey-add').remove();
      adjustRemoveButton();
    });
  }

  $('.survey-delete').on('click', function (e) {
    e.preventDefault();
    var add = false;
    if ($(this).parents('li').find('#survey-add').length > 0) {
      add = $('#survey-add');
    }
    $(this).parents('li').remove();

    var i = 0;
    $('#og-survey-multiple-survey').find('li').each(function () {
      $(this).find('input[type=hidden]').val(i);
      $(this).find('input[type=checkbox]').prop('name', 'og_survey_settings_shortcode[survey_shortcode_questions][' + i + '][]');
      i++;
    });

    if (add) {
      $('#og-survey-multiple-survey').find('li').last().append(add);
      bindAddClick();
    }
    adjustRemoveButton();
  });

  function adjustRemoveButton() {
    if ($('#og-survey-multiple-survey').find('.survey-delete').length <= 1) {
      $('.survey-delete').hide();
    } else {
      $('.survey-delete').show();
    }
  }

  $('.og-survey-shortcode-question-checkbox').on('click', function() {
    var checkBox = $(this).parents('li').find('.og-survey-shortcode-question-checkbox:checked');
    var totalChecked = checkBox.length;
    if(totalChecked == 0)
      $(this).parents('li').find('.survey_shortcode').val('');
    else if(totalChecked == 1)
      $(this).parents('li').find('.survey_shortcode').val('[questions ids="' + checkBox.val() + '"]');
    else if(totalChecked > 1) {
      var valString = '';
      checkBox.each(function() {
        valString += $(this).val()+',';
      });
      $(this).parents('li').find('.survey_shortcode').val('[questions ids="' + valString.slice(0,-1) + '"]');
    }
  });
});