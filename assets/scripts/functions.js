$(document).ready(function () {
    deleteUser();
    deleteSurvey();
    unassignSurvey();
    assignSurvey();
});

function deleteUser() {
    $('.delete-user').click(function (event) {
        event.preventDefault();

        let button = $(this);
        let id = button.data('id');
        let token = button.data('token');
        let rows = $("#user-tbody tr").length;

        $.ajax({
            url: '/user/' + id,
            type: 'DELETE',
            data: {
                _token: token
            },
            success: function (response) {
                button.closest('tr').remove();
                if ((rows - 1) === 0) {
                    $('tbody').append('<tr><td colspan="10">No records found</td></tr>');
                }
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    console.error('Invalid CSRF token');
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
}

function deleteSurvey() {
    $('.delete-survey').click(function (event) {
        event.preventDefault();

        let button = $(this);
        let id = button.data('id');
        let token = button.data('token');
        let rows = $("#survey-tbody tr").length;

        $.ajax({
            url: '/survey/' + id,
            type: 'DELETE',
            data: {
                _token: token
            },
            success: function (response) {
                button.closest('tr').remove();
                if ((rows - 1) === 0) {
                    $('tbody').append('<tr><td colspan="10">No records found</td></tr>');
                }
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    console.error('Invalid CSRF token');
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
}

function unassignSurvey() {
    $(document).on('click', '.unassign-survey', function(event) {
        event.preventDefault();

        let button = $(this);
        let userId = button.data('user-id');
        let surveyId = button.data('survey-id');
        let token = button.data('token');
        let rows = $("#assigned-survey-tbody tr").length;

        $.ajax({
            url: '/user/' + userId + '/survey/' + surveyId,
            type: 'DELETE',
            data: {
                _token: token
            },
            success: function (response) {
                button.closest('tr').remove();
                if ((rows - 1) === 0) {
                    $('#assigned-survey-tbody').append('<tr><td colspan="10">No records found</td></tr>');
                }
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    console.error('404 not found');
                } else if (xhr.status === 403) {
                    console.error('Invalid CSRF token');
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
}

function assignSurvey() {
    $('#assign-survey').click(function (event) {
        event.preventDefault();

        let button = $(this);
        let userId = button.data('user-id');
        let token = button.data('token');
        let surveyId = $('#assign-select').val();
        let newToken = $('#token').data('token');

        $.ajax({
            url: '/user/' + userId + '/survey/' + surveyId,
            type: 'POST',
            data: {
                _token: token
            },
            success: function (response) {
                // ToDo Hide already assigned surveys
                addSurveyRow(response.data, userId, newToken)
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    console.error('404 not found');
                } else if (xhr.status === 403) {
                    console.error('Invalid CSRF token');
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
}

function addSurveyRow(assignedSurvey, userId, token) {
    let newRow = $('<tr>')
        .append('<td>' + assignedSurvey.name + '</td>')
        .append('<td>' + (assignedSurvey.status ? 'active' : 'inactive') + '</td>')
        .append('<td>' + (assignedSurvey.createdAt.date.split(".")[0] ?? '') + '</td>')
        .append('<td class="align-content-end d-flex justify-content-end">' +
            '<button class="btn btn-danger ms-2 unassign-survey" data-survey-id="' + assignedSurvey.id + '" data-user-id="' + userId + '" data-token="' + token + '">Unassign</button>' +
            '</td>');
    $('#assigned-survey-tbody').append(newRow);
}
