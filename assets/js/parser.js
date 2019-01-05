$(function(){
    var options = {
        target: "#output",
        beforeSubmit: showRequest,
        success: showResponse,
        timeout: 3000
    };

    $("#parser").submit(function() {
        $(this).ajaxSubmit(options);
        return false;
    });

    // вызов перед передачей данных
    function showRequest(formData, jqForm, options) {
        var queryString = $.param(formData);
        // alert('Вот что мы передаем: \n\n' + queryString);
        return true;
    }

    // вызов после получения ответа
    function showResponse(responseText, statusText)  {
        /*alert('Статус ответа сервера: ' + statusText +
            '\n\nТекст ответа сервера: \n' + responseText +
            '\n\nбудет помещен в элемент #output.');*/
    }
});
