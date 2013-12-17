/**
 * Created by vir-mir on 20.11.13.
 */

var error = {
    fio: 'Заполните поле ФИО!',
    adress: 'Заполните поле Адрес!',
    sex: 'Не выбран пол!',
    email: 'Неправильно заполненно поле E-mail!'
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

var validateForm = function() {
    //валидация формы
    //все поля формы являются обязательными для заполнения
    //...

    var errors = [];

    if ($.trim($('#fio').val())=='') {
        errors.push(error.fio);
    }
    if (!$('input[name=sex]:checked').val()) {
        errors.push(error.sex);
    }
    if ($.trim($('#adress').val())=='') {
        errors.push(error.adress);
    }
    if (!isEmail($.trim($('#email').val()))) {
        errors.push(error.email);
    }

    var valid = !(errors.length > 0);

    if (!valid) {
        $('#insert_result').html('');
        $('#insert_result').removeClass('hidden');
        $('#insert_result').addClass('alert-danger');
        $.each(errors, function (k,v) {
            $('#insert_result').append('<p>'+v+'</p>');
        });
    }

    return valid;
};

var sendForm = function() {
    if (validateForm()) {
        $('#insert_result').addClass('hidden');
        $('#insert_result').removeClass('alert-danger');
        $('#insert_result').removeClass('alert-success');
        $('.alert-success, .alert-danger').remove();
        $('#insert_result').html('');
        //отправление данных формы, используя AJAX методом POST
        //...
        var btn = $('input[type=submit]');
        var id = $('input[name=id]').val();
        btn.attr('disabled', true);
        $.post('/user/replace_ajax/'+id+'/', $('#user').serialize(), function (data) {
            if (data.errors) {
                $.each(data.errors, function (k,v) {
                    $('#insert_result').removeClass('hidden');
                    $('#insert_result').addClass('alert-danger');
                    $('#insert_result').append('<ul></ul>');
                    $('#insert_result ul').append('<li>'+v+'</li>');
                });
            } else if (data.messengs) {
                $.each(data.messengs, function (k,v) {
                    $('#insert_result').removeClass('hidden');
                    $('#insert_result').addClass('alert-success');
                    $('#insert_result').append('<ul></ul>');
                    $('#insert_result ul').append('<li>'+v+'</li>');
                });
                $('#fio').val('');
                $('#adress').val('');
                $('#email').val('');
                $('input[name=sex]:checked').parent().removeClass('active');
                $('input[name=sex]:checked').attr('checked', false);
            }
            btn.attr('disabled', false);
        }, 'json');
    }

    return false;
};


$(function () {
   $('.filters').change(function () {
       var self = $(this);
       var objCongratulated = $('#congratulated');
       var objNotCongratulated = $('#not_congratulated');
       var objTableUser = $('#table_users');
       var objIfActive = $('input[name=if]:checked');
       var objIf = $('input[name=if]');

       objCongratulated.prop('disabled', true);
       objNotCongratulated.prop('disabled', true);
       objIf.parent().addClass('disabled');
       objTableUser.find('tbody').fadeOut(300);

       $.post('/user/filter/', {
           congratulated: objCongratulated.val(),
           not_congratulated: objNotCongratulated.val(),
           if: objIfActive.val()
       }, function (data) {
           if (data.error) {
               alert(data.error);
           } else if (data.users) {
               var html = "";
               $.each(data.users, function(k,user) {
                   html += "<tr>";
                   html += "<td>"+user.id+"</td>";
                   html += "<td>"+user.fio+"</td>";
                   html += "<td>"+user.email+"</td>";
                   html += "<td>"+((user.sex == 'm')?'Муж.':'Жен.')+"</td>";
                   html += '<td><a href="/user/replace/'+user.id+'/" title="Редактировать">' +
                       '<i class="glyphicon glyphicon-edit"></i></a></td>';
                   html += "</tr>";
               });
               objTableUser.find('tbody').html(html);
           } else {
               alert('Непредвиденная ошибка!')
           }
           objCongratulated.prop('disabled', false);
           objNotCongratulated.prop('disabled', false);
           objIf.parent().removeClass('disabled');
           objTableUser.find('tbody').fadeIn(300);
       }, 'json');

       return false;
   });
});