jQuery(document).ready(function($) {
  $('#title-prompt-text').text('Enter sub question here');

  bindAddClick();
  adjustRemoveButton();
  function bindAddClick() {
    $('#answer-add').on('click', function (e) {
      e.preventDefault();
      var li = $(this).parents('li').clone(true);
      $(li).find('input[type=text]').val('');
      $(li).find('textarea').val('');
      $(li).find('input[type=url]').val('');
      $(li).find('div.wooproduct').hide();
      $(li).find('div.other').hide();
      $(li).find('input[type=radio]').prop('checked', false);
      $(li).find('input[type=checkbox]').prop('checked', false);
      var relProdCount = parseInt($(li).find('input[type=hidden]').val());
      relProdCount = relProdCount + 1;
      $(li).find('input[type=hidden]').val(relProdCount);
      $(li).find('input[type=checkbox]').prop('name', 'sub_question[related_product_id]['+relProdCount+'][]');
      $(li).find('input[type=radio]').prop('name', 'sub_question[recommend]['+relProdCount+'][]');
      $('#answer-metabox').append(li);
      $('#answer-add').remove();
      adjustRemoveButton();
    });
  }

  $('.answer-delete').on('click', function (e) {
    e.preventDefault();
    var add = false;
    if ($(this).parents('li').find('#answer-add').length > 0) {
      add = $('#answer-add');
    }
    $(this).parents('li').remove();
    
    var i = 0;
    $('#answer-metabox').find('li').each(function() {
      $(this).find('input[type=hidden]').val(i);
      $(this).find('input[type=checkbox]').prop('name', 'sub_question[related_product_id]['+i+'][]');
      $(this).find('input[type=radio]').prop('name', 'sub_question[recommend]['+i+'][]');
      i++;
    });
    
    if (add) {
      $('#answer-metabox').find('li').last().append(add);
      bindAddClick();
    }
    adjustRemoveButton();
  });

  function adjustRemoveButton() {
    if ($('#answer-metabox').find('.answer-delete').length <= 1) {
      $('.answer-delete').hide();
    } else {
      $('.answer-delete').show();
    }
  }

  $('.what_to_recommend').on('click', function() {
    if($(this).val() == 'wooproduct') {
      $(this).parents('li').find('div.wooproduct').fadeIn(1000);
      $(this).parents('li').find('div.other').find('input[type=text].checkvalidshortcodeyoutubeurl').val('');
      $(this).parents('li').find('div.other').fadeOut(1000);
    } else if($(this).val() == 'other') {
      $(this).parents('li').find('div.other').fadeIn(1000);
      $(this).parents('li').find('div.wooproduct').find('input[type=checkbox]').prop('checked', false);
      $(this).parents('li').find('div.wooproduct').fadeOut(1000);
    }
  });
  
  function youtubeUrlValidation(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? true : false;
  }
  
  function shortcodePatternValidation(string) {
    if((string.charAt(0) == "[") || (string.charAt(string.length - 1) == "]")) return true;
    else return false;
  }
  
  $('#post').submit(function(event) {
    var errorInfo = '<div class="error"><p><strong>You have to Enter either a Shortcode or a Valid YouTube URL!</strong></p></div>';
    $('#answer-metabox').find('li').each(function() {
      var recommendOther = $(this).find('input[type=text].checkvalidshortcodeyoutubeurl').val();
      var validShortcode = shortcodePatternValidation(recommendOther);
      var validYoutubeURL = youtubeUrlValidation(recommendOther);
      if(recommendOther != '' && validShortcode == false && validYoutubeURL == false) {
        event.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $(errorInfo).insertBefore("#post");
      } else {
        $('#post').unbind('submit').submit();
      }
    });
  });
});