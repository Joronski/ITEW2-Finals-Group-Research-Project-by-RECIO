$(document).ready(function() {
    getAdmins();
    
    function getAdmins() {
        $.ajax({
            url: '../admin/classes/Admin.php',
            method: 'POST',
            data: {GET_ADMIN: 1},
            success: function(response) {
                console.log(response);
                var resp = $.parseJSON(response);

                if (resp.status == 202) {
                    var adminHTML = '';

                    $.each(resp.message, function(index, value) {
                        adminHTML += '<tr>' +
                                        '<td>' + (index + 1) + '</td>' +
                                        '<td>' + value.name + '</td>' +
                                        '<td>' + value.email + '</td>' +
                                        '<td>' + value.is_active + '</td>' +
                                        '<td><a class="btn btn-sm btn-danger delete-admin" aid="' + value.id + '"><i class="fas fa-trash-alt"></i></a></td>' +
                                    '</tr>';
                    });

                    $("#admin_list").html(adminHTML);

                } else if(resp.status == 303) {
                    $("#admin_list").html(resp.message);
                }
            }
        });
    }

    $(document.body).on('click', '.delete-admin', function() {
        var aid = $(this).attr('aid');
        
        if (confirm("Are you sure you want to delete this admin?")) {
            $.ajax({
                url: '../admin/classes/Admin.php',
                method: 'POST',
                data: {DELETE_ADMIN: 1, aid: aid},
                success: function(response) {
                    var resp = $.parseJSON(response);
                    if (resp.status == 202) {
                        alert(resp.message);
                        getAdmins();
                    } else if(resp.status == 303) {
                        alert(resp.message);
                    }
                }
            });
        } else {
            alert('Cancelled');
        }
    });
});