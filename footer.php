<?php
?>
<script>
    $(document).ready(function () {
        // Отслеживаем изменение поля ввода
        $("#input-value").on("input", function () {
            // Получаем значение поля ввода
            var value = $(this).val();
            // Отправляем Ajax-запрос на сервер для обработки
            $.ajax({
                type: "GET",
                url: "content.php", // Укажите путь к вашему обработчику
                data: {input_value: value},
                dataType: "json", // Указываем, что ожидаем JSON-ответ от сервера
                success: function (response) {
                    // Обновляем значение тега P на странице
                    $("#sort_string").html(response.sort_string);
                    $("#language").html(response.language);
                    $("#char_sum").html(response.char_sum);
                },
                error: function () {
                    alert("Ошибка запроса!");
                }
            });
        });
    });
</script>
<footer>
    <div id="result"></div>
</footer>
