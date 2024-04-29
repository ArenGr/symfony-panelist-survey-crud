$(document).ready(function() {
    $('.delete-btn').click(function() {
        var todoId = $(this).closest('.todo-item').data('id');

        $.ajax({
            url: '/delete/todo/' + todoId,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $('.todo-item[data-id="' + todoId + '"]').remove();
                } else {
                    alert('Failed to delete todo.');
                }
            },
            error: function() {
                alert('Error occurred while deleting todo.');
            }
        });
    });
});
