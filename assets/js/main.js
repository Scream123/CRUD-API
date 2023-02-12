$(document).ready(function () {

    /**
     * Registration of user
     */
    $("#register").submit(function (e) {
        e.preventDefault();


        let name = getValue('#name');
        let email = getValue('#email');
        let password = getValue('#password');
        let pwd2 = getValue('#pwd2');


        let error = false;
        let msg = [];

        //heck name
        if (name.length < 2) {
            msg = 'Имя не должно быть короче 2 символов';
            check(msg);
            lightEmpty($('#name'));
            error = true; // oшибкa
        } else if (password.length < 6) {
            msg = 'Пароль не должен быть короче 6 символов';
            check(msg);
            lightEmpty($('#password'));
            error = true;
        } else if (pwd2 !== password) {
            msg = 'пароли не совпадают!';

            check(msg);
            lightEmpty($('#pwd2'));
            error = true; // oшибкa
        }
        if (!error) {
            let postData = {
                name: name,
                email: email,
                password: password,
                pwd2: pwd2
            };
            $.ajax({
                type: 'POST',
                async: true,
                url: '/register',
                data: postData,
                dataType: 'json',
                success: function (data) {
                    if (data['success']) {
                        alert('Регистрация прошла успешно!');
                        $('.messageShow').text(data['message']).fadeOut(10000);
                        $('#modal').fadeOut();
                        $(".modal-backdrop").fadeOut();
                    } else {
                        console.log(data['message']);
                        check(data['message']);
                        lightEmpty($('#email'));
                    }
                }
            });
        }
    });

    /**
     * Authorization of user
     */
    $("#login").submit(function (e) {
        e.preventDefault();
        let email = getValue('#emailLogin');
        let password = getValue('#passwordLogin');
        let error = false;
        let msg = [];
        if (password.length < 6) {
            msg = 'Пароль не должен быть короче 6-ти символов';
            check(msg);
            lightEmpty(password);
            error = true;
        }
        if (!error) {
            check(msg);
            //записываем в массив данные
            let postData = {
                email: email,
                password: password
            };
            $.ajax({
                type: 'POST',
                async: true,
                url: '/login',
                data: postData,
                dataType: 'json',
                success: function (response) {
                    if (response) {
                        $('.greeting').text(response['user_name']).show();
                        document.location.href = window.location.origin + "/cabinet";
                        $('#modalLogin').fadeOut();
                        $(".modal-backdrop").fadeOut();
                    } else {
                        check(response['message']);
                        lightEmpty($('#emailLogin'));
                    }
                }
            });
        }
    });

    /**
     * Adding Survey
     */
    $("#addSurvey").submit(function (e) {
        e.preventDefault();
        let title = getValue('#titleSurvey');
        let answer = [];
        $('.answer').each(function (index) {
            answer[index] = $(this).val();
        });
        let votes = [];
        $('.votes').each(function (index) {
            votes[index] = $(this).val();
        });
        let status = Number($("#status :selected").val());
        let date_published = getValue('#date_published');
        let postData = {
            title: title,
            answer: answer,
            votes: votes,
            status: status,
            date_published: date_published,
        };
        $.ajax({
            type: 'POST',
            async: true,
            url: '/cabinet/addSurvey',
            data: postData,
            dataType: 'json',
            success: function (response) {
                if (response) {
                    createFields(response['surveyList']);
                } else {
                    check(response['message']);
                }
                $('#modalCreate').fadeOut();
                $(".modal-backdrop").fadeOut();

            }
        });
    });
    $(document).on("click", ".surveyDelete", function () {
        let parentRow = $(this).parents('.surveysTr');
        let survey_id = $('td.survey_id', parentRow).attr('data-id');
        $.ajax({
            type: 'POST',
            async: true,
            url: '/cabinet/deletedSurvey/' + survey_id,
            data: {
                survey_id: survey_id,
            },
            dataType: 'json',
            success: function (data) {
                if (data['success']) {
                    $('.surveysTr_' + survey_id).remove();
                    $('.messageShow').html(data['message']).css('color', 'red').show().fadeOut(5000);
                }
            }
        });

    });
    //Edit page
    $(document).on('click', ".surveyEdit", function (e) {
        e.preventDefault();
        let survey_id = $(this).data('id');
        document.location.href = window.location.origin + "/cabinet/edit/" + survey_id;
    });

    //updating Survey
    $(document).on('submit', "#valentine", function (e) {
        e.preventDefault();
        let survey_id = Number($('.survey_id').text());

        $.ajax({
            type: "POST",
            url: '/cabinet/updateSurvey/' + survey_id,
            data: $(this).serialize(),
            success: function (response) {
                response = jQuery.parseJSON(response);
                if (!response.success) {
                    alert(response.error);
                } else {
                    $('#messageShow').css('color', 'green').text(response['message']).show().fadeOut(5000);
                }
            }
        });
    });

    let i = $('.answer').length + 1;
    $(document).on("click", "#addInput", function (e) {
        e.preventDefault();
        $('.input-group:last').after($('<div class="input-group">' +
            '<input type="text" class="form-control answer" name="answer" ' +
            'aria-label="First name"  value="" required/>' +
            '<input type="number" min="0" placeholder="Укажите кол-во голосов" name="votes"  ' +
            'aria-label="Last name" value="0" class="form-control votes" required></div>'));
        i++;
    });
    $('#deleteInput').on('click', function () {
        if (i > 1) {
            $('.input-group:last').remove();
            i--;
        }
    });
    $('#resetInput').on('click', function () {
        while (i > 4) {
            $('.input-group:last').remove();
            i--;
        }
    });

    $(document).on("click", "#filterSearchTitleBtn", function (event) {
        event.preventDefault();
        let surveyTitleFilter = $('#surveyTitleFilter').val();
        let postData = {
            surveyTitleFilter: surveyTitleFilter,
        };
        if (postData.surveyTitleFilter === '') {
            $("#blockFilterMessage").html('Укажите название для поиска').css('background-color', 'red').show();
            $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
            return false;
        }
        $('.surveyBodyTable').remove();

        $.ajax({
            type: 'POST',
            async: true,
            url: "/cabinet/searchSurveyByTitle",
            data: postData,
            dataType: 'json',
            success: function (response) {
                if (response['surveyList']) {
                    createElementsInTbody(response['surveyList']);
                } else {
                    $("#blockFilterMessage").html('Указанного опроса не существует').css('background-color', 'red').show();
                    $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
                }
            },
        });

    });
    $(document).on("click", "#filterStatusBtn", function (event) {
        event.preventDefault();
        let filterStatus = $('#statusFilter').val();
        let postData = {
            filterStatus: filterStatus,
        };
        if (postData.filterStatus === '') {
            $("#blockFilterMessage").html('Укажите статус для поиска').css('background-color', 'red').show();
            $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
            return false;
        }
        $('.surveyBodyTable').remove();

        $.ajax({
            type: 'POST',
            async: true,
            url: "/cabinet/searchSurveyByStatus",
            data: postData,
            dataType: 'json',
            success: function (response) {

                if (response['surveyList']) {
                    createElementsInTbody(response['surveyList']);
                } else {
                    $("#blockFilterMessage").html('Указанного статуса не существует').css('background-color', 'red').show();
                    $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
                }
            }
        });

    });

    $(document).on("click", "#filterSearchDateBtn", function (event) {
        event.preventDefault();
        let filterDate = $('#surveyDateFilter').val();
        let postData = {
            filterDate: filterDate,
        };
        if (postData.filterDate === '') {
            $("#blockFilterMessage").html('Укажите дату публикации для поиска').css('background-color', 'red').show();
            $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
            return false;
        }
        $('.surveyBodyTable').remove();

        $.ajax({
            type: 'POST',
            async: true,
            url: "/cabinet/searchSurveyByDate",
            data: postData,
            dataType: 'json',
            success: function (response) {
                console.log(response);

                if (response['surveyList']) {
                    createElementsInTbody(response['surveyList']);
                } else {
                    $("#blockFilterMessage").html('Указанной даты публикации не существует').css('background-color', 'red').show();
                    $("#blockFilterMessage").fadeOut(5000).fadeOut('slow');
                }
            }
        });

    });


    function createFields(param) {
        let tr = '';
        let rowNumber = 0;
        let closedTag = $('</td>');
        let closedTr = $('</tr>');
        let countAnswer = Object.keys(param).length;

        $.each(param, function (index, value) {

            tr = ($('<tr>').addClass("surveysTr surveysTr_" + value['survey_id']))
            $(".surveyBodyTable").append(tr)
            if (rowNumber === 0) {
                tr.append($('<td class="survey_id">').attr('rowspan', countAnswer).attr('data-id', value['survey_id'])
                    .text(value['survey_id'])
                    .append(closedTag))
                    .append($('<td>').attr('rowspan', countAnswer).addClass('surveyTitle')
                        .text(value['surveysTitle'])
                        .append(closedTag))
            }
            tr.append($('<td>').addClass('answerTitle').attr('data-id', value['answerId'])
                .text(value['answerTitle'])
                .append(closedTag))
                .append($('<td>').addClass('numberVotes').attr('data-id', value['answerId'])
                    .text(value['number_votes'])
                    .append(closedTag))
                .append(closedTr)
            if (rowNumber === 0) {
                tr.append($('<td>').attr('rowspan', countAnswer).addClass('status')
                    .text(value.status === 0 ? 'Черновик' : 'Опубликовано')
                    .append(closedTag))
                    .append($('<td>').attr('rowspan', countAnswer).addClass('surveyData')
                        .text(value['date_published'])
                        .append(closedTag))
                    .append(($('<td>').attr('rowspan', countAnswer).addClass('surveyAction'))
                        .append($('<a href="" class="btn btn-success surveyEdit">' +
                            '<i class="fa fa-edit"></i></a><a href="" class="btn btn-danger surveyDelete">' +
                            '<i class="fa fa-trash-alt"></i></a>'))
                        .append(closedTag))
                    .append(closedTr)
            }
            tr.append(closedTr)
            rowNumber++;
        });

    }

    function createElementsInTbody(param) {
        let closedTag = $('</td>');
        $('.tableSurveys').append($('<tbody>').addClass('surveyBodyTable'));
        $.each(param, function (index, value) {
            let rowNumber = 0;
            let countAnswer = Object.keys(value['answers']).length;
            $.each(value['answers'], function (answerId, answerParam) {
                let tr = $('<tr>').addClass("surveysTr surveysTr_" + value['survey_id']);
                $('.surveyBodyTable').append(tr);

                if (rowNumber === 0) {
                    tr.append($('<td>')
                        .addClass('survey_id').attr('rowspan', countAnswer).attr('data-id', value['survey_id'])
                        .text(value['survey_id']))
                        .append(closedTag)
                        .append($('<td>').addClass('surveyTitle').attr('rowspan', countAnswer)
                            .text(value['surveysTitle']))
                        .append(closedTag)
                }
                tr.append($('<td>').addClass('answerTitle').attr('data-id', answerId)
                    .text(answerParam['answerTitle']))
                    .append(closedTag)
                    .append($('<td>').addClass('numberVotes').attr('data-id', answerId)
                        .text(answerParam['numberVotes']))
                    .append(closedTag)
                if (rowNumber === 0) {
                    tr.append($('<td>').addClass('status').attr('rowspan', countAnswer)
                        .text(value['status'])
                        .append(closedTag))
                        .append($('<td>').addClass('surveyData').attr('rowspan', countAnswer)
                            .text(value['date_published'])
                            .append(closedTag))
                        .append(($('<td>').addClass('surveyAction')).attr('rowspan', countAnswer)
                            .append($('' +
                                '<a href="" class="btn btn-success surveyEdit">' +
                                '<i class="fa fa-edit"></i></a>' +
                                '<a href="" class="btn btn-danger surveyDelete">' +
                                '<i class="fa fa-trash-alt"></i>' +
                                '</a>'
                            ))
                            .append(closedTag));
                }
                $('.surveysTr').append($('</tr>'));
                rowNumber++;
            });
        });
        $('.surveyBodyTable').append($('</tbody>'));
    }

    function getValue(val) {
        return $(val).val();
    }
    //check fields
    function check(message) {
        $('.messageShow').text(message).show().fadeOut(5000);
    }

    //empty field highlight
    function lightEmpty(field) {
        field.css("border", "red solid 1px");
        setTimeout(function () {
            field.removeAttr('style');
        }, 500);
    }
});