$(function() {
    $.ajax({
        type: 'GET',
        url: 'scripts/list.php',
        data: "",
        dataType: 'json',
        success: function (data) {
             for (var i = 0; i < data.length; i++) {
                 $("#data").append("<tr><td>" + data[i].id + "</td><td>" + data[i].firstName + "</td><td>" + data[i].lastName + "</td><td>" + data[i].email + "</td><td>" + data[i].phone + "</td><td>" + data[i].gender+ "</td><td>" + data[i].referrer+ "</td></tr>");
            };
             
        }
    });
}); 