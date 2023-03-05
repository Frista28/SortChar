<?php

// Получаем значение поля ввода из Ajax-запроса
$input_value = $_GET['input_value'];

$latin_char = 0;
$cyrillic_char = 0;
//Подсчёт на колличество символов обоих алфавитов
for ($i = 0; $i < mb_strlen($input_value); $i++){
    $char = mb_substr($input_value, $i, 1);
    if (preg_match('/\p{Latin}/u', $char)){
        $latin_char++;
    }else if(preg_match('/\p{Cyrillic}/u', $char)){
        $cyrillic_char++;
    }
}

//На основе коллечиства символов, выбор алфавита
if ($cyrillic_char>=$latin_char){
    $language = 'Кириллица';
    $language_bool = true;
    $char_sum = $latin_char;
}else{
    $language = 'Латиница';
    $language_bool = false;
    $char_sum = $cyrillic_char;
}

if ($language_bool){
    $sort_string = preg_replace('/\p{Latin}/u', '<span style="color:green;">$0</span>', $input_value);
}else{
    $sort_string = preg_replace('/\p{Cyrillic}/u', '<span style="color:green;">$0</span>', $input_value);
}

// Формируем JSON-ответ
$response = array('sort_string' => $sort_string,'language' => $language,'char_sum' => $char_sum);

// Отправляем JSON-ответ
echo json_encode($response);
?>
