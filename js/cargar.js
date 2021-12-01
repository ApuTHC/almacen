function cambiar(){
    name = $('#file_id').val().replace(/C:\\fakepath\\/i, '');
    $('#label_id').html(name);
}