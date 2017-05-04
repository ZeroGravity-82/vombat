$(document).ready(function(){
    $('#avatar').on('change', function(){
        var avatar_container = $('#avatar-container');
        avatar_container.empty();
        if (typeof(FileReader)) {
            var reader = new FileReader();
            reader.onload = function (event) {
                $('<img>', {
                    'src': event.target.result,
                    'style': 'max-width:100%;max-height: 100%'
                }).appendTo(avatar_container);
            };
            avatar_container.show();
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            // TODO: сообщить о невозможности загрузки файла
        }
    });
});