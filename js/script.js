function update_result() {
  $.ajax({
    url: "/ajax.php",
    type: "POST",
    data: "job=update",
    dataType: "json",
    success: function(data) {
      if (data.error) {
        alert( data.result );
      } else {
        console.log(data.result);
        $('.ex2-table tbody').html(data.result);
      }
    }
  });
}

var ansnum = 3;
$('.poll-table__plus button').on('click', function () {
  $(this).parents('tr').before('<tr><th>Answer ' + ansnum + ':</th><td><input type="text" value="" class="input-text"></td></tr>');
  ansnum++;
});

$('.btn--start').on('click', function () {
  var answers = [];
  $('.poll-table tbody .input-text').each(function (idx, el) {
    if (!$(el).val()) {
      alert('Заполните поле все варианты ответов');
      return false;
    } else {
      answers.push($(el).val());
    }
  });
  let req = {
    question: $('.poll-table thead .input-text').val(),
    answers: answers
  };

  $.ajax({
    url: "/ajax.php",
    type: "POST",
    data: "job=save&req=" + JSON.stringify(req),
    dataType: "json",
    success: function(data) {
      if (data.error) {
        alert( data.result );
      } else {
        document.location.href = '/ans.php?alias=' + data.alias;
      }
    }
  });

});

$('.ex2-question__submit').on('click', function () {
  let name = $('.input-text').val();
  let answer = $('input[name=do-we-go]:checked').val();

  if (!name) {
    alert('Please enter your name');
    return false;
  }
  if (!answer) {
    alert('Please enter your answer');
    return false;
  }

  let req = {
    id: $('input[name=question_id]').val(),
    name: name,
    answer: answer
  };

  $.ajax({
    url: "/ajax.php",
    type: "POST",
    data: "job=answer&req=" + JSON.stringify(req),
    dataType: "json",
    success: function(data) {
      if (data.error) {
        alert( data.result );
      } else {
        $('.poll_question').html('<h1>Your question is accepted</h1>');
        update_result();
      }
    }
  });

  console.log(answer);
});
