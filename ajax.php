<?php
session_start();
require 'conf.php';
$time = time();
$job = $_REQUEST['job'];

if ($job == 'save') {
  $req = json_decode($_REQUEST['req']);
  $alias = substr(md5(uniqid()), 0, 16);
  $question = mysqli_real_escape_string($link, $req->question);
  $sql = "INSERT INTO `questions` SET `alias` = '{$alias}', `question` = '{$question}', `created_at` = FROM_UNIXTIME({$time}), `updated_at` = FROM_UNIXTIME({$time});"."\r\n";

  $answers = $req->answers;
  foreach ($answers as $val) {
    $val = mysqli_real_escape_string($link, $val);
    $sql .= "INSERT INTO `answers` SET `question_alias` = '{$alias}', `answer` = '{$val}', `created_at` = FROM_UNIXTIME({$time}), `updated_at` = FROM_UNIXTIME({$time});"."\r\n";
  }

  if (mysqli_multi_query($link, $sql) == false) {
    echo json_encode(['error' => true, 'result' => "An error occurred while executing the request"]);
    exit;
  }

  echo json_encode(['error' => false, 'alias' => $alias]);
}

if ($job == 'answer') {
  $req = json_decode($_REQUEST['req']);
  $question_id = mysqli_real_escape_string($link, $req->id);
  $name = mysqli_real_escape_string($link, $req->name);
  $answer_id = mysqli_real_escape_string($link, $req->answer);

  $sql = "INSERT INTO `users` SET `question_id` = '{$question_id}', `name` = '{$name}', `answer_id` = '{$answer_id}', `created_at` = FROM_UNIXTIME({$time}), `updated_at` = FROM_UNIXTIME({$time})";
  if (mysqli_query($link, $sql) == false) {
    echo json_encode(['error' => true, 'result' => "An error occurred while executing the request"]);
    exit;
  }

  $_SESSION['ans_'.$question_id] = true;

  echo json_encode(['error' => false, 'result' => "ok"]);
}


if ($job == 'update') {
  $question_id = (int) $_SESSION['question_id'];
  $sql = "SELECT `u`.*, `a`.`answer` AS answer FROM `users` AS u LEFT JOIN `answers` AS a ON `u`.`answer_id` = `a`.`id`  WHERE `u`.`question_id` = '{$question_id}'"; // Формируем запрос к базе
  $result = mysqli_query($link, $sql); // Делаем запрос к базе
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // Получаем данные из базы в виде массива

  $result = '';
  foreach ($rows as $row) {
    $result .= '<tr><td>'.$row['name'].'</td><td>'.$row['answer'].'</td></tr>';
  }

  echo json_encode(['error' => false, 'result' => $result]);
}
