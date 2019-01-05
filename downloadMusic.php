<?php include 'includes/includedFiles.php'; ?>
<script src="/assets/js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/parser.js"></script>
<!--ajax form-->
<form action="includes/handlers/ajax/parser.php" method="POST" id="parser">
    <div id="parserContainer">
        <label for="artist">Введите имя исполнителя (название песни)</label>
        <p class="musicInput">
            <input type="text" name="artist" id="artist">
        </p>
        <button type="submit" class="btnDown">Вперед!</button>
        <p class="echoMusic" id="output">
        <!--сюда выводятся ссылки на скачивание музыки-->
        </p>
    </div>
</form>