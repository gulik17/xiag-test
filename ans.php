<?php
  session_start(); // Стартуем сессию
  require 'conf.php'; // Подключаем конфиг с подключением к базе
  $alias = mysqli_real_escape_string($link, $_REQUEST['alias']); // Проверяем корректность полученых в запросе данных
  $sql = "SELECT * FROM `questions` WHERE `alias` = '$alias'"; // Формируем запрос к базе
  $result = mysqli_query($link, $sql); // Делаем запрос к базе
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // Получаем данные из базы в виде массива

  if (!$alias || !$rows) { // Проверяем данные, если что-то из данных отсутствует то редиректим т.к. работать будет не с чем
    header('Location: /'); // Редирект на главную
    exit;
  }

  $question = $rows[0]; // Т.к. алиас уникальный, то вопрос будет только один (не применяя foreach)
  $answered = (isset($_SESSION['ans_'.$question['id']])) ? true : false; // Проверяем отвечал ли посетитель уже на этот вопрос
  $_SESSION['question_id'] = $question['id'];
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,400i,700" media="all">
    <title>XIAG test task</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
  </head>
  <body>
    <div class="page">
      <div class="page__header">
        <div class="page__logo">
          <a href="https://www.xiag.ch" target="_blank">
            <img src="/images/page-logo.png" alt="XIAG AG">
          </a>
        </div>
        <div class="page__task-name">Poll website task: HTML example 2</div>
      </div>
      <div class="page__image">
        <div class="page__task-title">Poll website task: HTML example 2</div>
      </div>
      <div class="page__content page__content--padding">
        <div class="poll">
          <div class="poll_question"><?php 
            if ($answered) { ?>
              <h1>Your question is accepted</h1><?php
            } else { ?>
              <h1><?=$question['question']?></h1>
              <div class="ex2-question">
                <input type="hidden" name="question_id" value="<?=$question['id']?>">
                <div class="ex2-question__label">Your name:</div>
                <div class="ex2-question__input">
                  <input type="text" class="input-text">
                </div>
                <div class="ex2-question__answer">
                  <?php 
                    $sql = "SELECT * FROM `answers` WHERE `question_alias` = '{$alias}'"; // Формируем запрос к базе
                    $result = mysqli_query($link, $sql); // Делаем запрос к базе
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // Получаем данные из базы в виде массива
                    foreach ($rows as $row) {
                      echo '<label><input type="radio" name="do-we-go" value="'.$row['id'].'"> '.$row['answer'].'</label>';
                    } ?>
                </div>
                <div class="ex2-question__submit">
                  <input type="submit" class="btn" value="Submit">
                </div>
              </div><?php
            } ?>
          </div>
          <h1>Results</h1>
          <br>
          <table class="ex2-table">
            <thead>
              <tr>
                <th>Name</th><th>Answer</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="/js/jquery-3.5.1.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
      update_result();
    </script>
  </body>
</html>
