<?php
require_once ('header.php');

// Параметры подключения к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "application";

// Создание подключения
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Проверка соединения
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$page = $_GET['page'];
echo '<div class = "center_content">';
switch ($page) {
    case 'form':
        $result = formAction();
        break;
    case 'list':
        $result = listAction();
        break;
    case 'send':
        $result = sendAction();
        break;
    default:
        $result = formAction();
}
echo '</div>';
require_once 'footer.php';

function formAction(): string {
    ?>
    <form  class = 'central-block' method='GET'>
        <p class='text2'>Ваша строка <input type='text' name='string'></p>
        <input type="hidden" name = "page" value = "send">
        <input type="hidden" name = "insert" value = "yes">
        <input class=button_log type='submit' value='Добавить'>
    </form>
    <?php
    return "";
}

function listAction(): string {

    // селект из базы $database->query()
    global $conn;
    $result = $conn->query("Select * from sortchar");
    $num=$result->num_rows;
    echo "<div class = 'list_block'><div class='list_string_up'><p>Запрос</p><p>Основной алфавит</p><p>Сторонних смволы</p></div>";
    for ($j = 0 ; $j < $num ; ++$j){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $string = $row['string'];
        $language = $row['language'];
        $charsum = $row['charsum'];

        if ($language == 'Кириллица'){
            $language_bool = true;
        }else{
            $language_bool = false;
        }


        if ($language_bool) {
            $newVarchar = preg_replace('/\p{Latin}/u', '<span style="color:green;">$0</span>', $string);
        } else {
            $newVarchar = preg_replace('/\p{Cyrillic}/u', '<span style="color:green;">$0</span>', $string);
        }

        echo "<div class = 'list_string'><p>$newVarchar</p><p>$language</p><p>$charsum</p></div>";
    }
    echo "</div>";

    return '';
}

function sendAction(): string {

    $varchar = $_GET['string'];
    $latin_char = 0;
    $cyrillic_char = 0;
    //Подсчёт на колличество символов обоих алфавитов
    for ($i = 0; $i < mb_strlen($varchar); $i++){
        $char = mb_substr($varchar, $i, 1);
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


    echo '<div class = "up_string">Основной алфавит -&nbsp<p id = "language"> '.$language.'</p>; Сторонних смволов -&nbsp<p id = "char_sum"> '.$char_sum.'</p></div>';


    //Добавление строки в БД
    if($_GET['insert'] == 'yes'){
        $sql = "Insert into sortchar(string, language, charsum)
                                            VALUES ('$varchar', '$language', '$char_sum')";

        global $conn;
        if (mysqli_query($conn, $sql)) {
            $res = "Record inserted successfully";
        } else {
            $res = "Error inserting record: " . mysqli_error($conn);
        }
    }

    ?>
    <!-- Тег P для отображения значения поля ввода -->
    <div class = "sort_string" id="sort_string">
        <?php
        if ($language_bool){
            $newVarchar = preg_replace('/\p{Latin}/u', '<span style="color:green;">$0</span>', $varchar);
            echo $newVarchar;
        }else{
            $newVarchar = preg_replace('/\p{Cyrillic}/u', '<span style="color:green;">$0</span>', $varchar);
            echo $newVarchar;
        } ?>
    </div>

    <!-- Поле ввода -->
    <input type="text" name="input-value" id="input-value" value="<?php echo $varchar ?>">
    <?php
    return "";
}
mysqli_close($conn);
?>
