$(document).ready(function () {
    deleteUser();
    deleteSurvey();
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
