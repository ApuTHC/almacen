function cambiar(){
    name1 = $('#file_id').val().replace(/C:\\fakepath\\/i, '');
    $('#label_id').html(name1);
}