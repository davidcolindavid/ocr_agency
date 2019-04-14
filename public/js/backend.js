function setDate () {
    var now = new Date();
    var utcString = now.toISOString().substring(0,15);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    var localDatetime = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) +
                      utcString.substring(16,19);
    var datetimeField = document.querySelector('.admin_post_date');
    datetimeField.value = localDatetime;
};

window.addEventListener("load", setDate);


// Control btn / show edit & delete link
$('#posts_container').on('click', '.control', function() {
    $(this).find('.control_list').css( "display", "block" ).animate({
        opacity: 1,
        top: "30px",
    }, 200);
});

$(document).mouseup(function (e){
    var container = $(".control_list");
    if (container.has(e.target).length === 0) {
        container.animate({
            opacity: 0,
            top: "50px",
        }, 200).css("display", "none");
    }
});


// Add a post
$("#container").on('submit', '.add_post', function(e) {
    e.preventDefault();
    let formElt = $(this);
    let url = formElt.attr('action');

    let titleElt = document.querySelector(".admin_post_title")
    // report if title empty
    if (titleElt.value.trim().length === 0) {
        titleElt.style.backgroundColor = "rgb(53, 234, 165)";
        titleElt.addEventListener("focus", function () {
            titleElt.style.backgroundColor = "#ffffff";
        }) 
    } else {
        $.ajax({
            type: "POST",
            url: url,
            data: formElt.serializeArray(),
            dataType: 'JSON',
            success: function(data) {
                // add a new row to the table
                $('<div class="post"></div>').prependTo('#posts_container').hide();
                $('#posts_container').find(':first').append('<div class="post_header"></div>');
                $('#posts_container').find(':first .post_header').append('<div class="title">' + data[1] + '</div>');
                $('#posts_container').find(':first .post_header').append('<div class="place">' + data[2] + '</div>');
                $('#posts_container').find(':first .post_header').append('<div class="address">' + data[3] + '</div>');
                $('#posts_container').find(':first .post_header').append('<div class="date">' + data[4] + '</div>');
                $('#posts_container').find(':first .post_header').append('<div class="control"></div>');
                $('#posts_container').find(':first .post_header .control').append('<div class="edit_control"><a href="admin.php?action=editPost&amp;id=' + data[0] + '"><i class="fas fa-edit"></i></a></div>');
                $('#posts_container').find(':first .post_header .control').append('<div class="delete_control"><a href="admin.php?action=deletePost&amp;id=' + data[0] + '"><i class="fas fa-times"></i></a></div>');
                $('#posts_container').find(':first').append('<div class="post_body"></div>');
                $('#posts_container').find(':first .post_body').append('<div class="post_content">' + data[5] + '</div>');
                $('#posts_container').find(':first .post_body').append('<div class="post_date">' + data[6] + '</div>');
                $('#posts_container').find(':first').fadeIn();
                $(".admin_post_form")[0].reset()
            },
        })
    }
});


// Edit a post
$('#posts_container').on('click', '.edit_control', function(e) {
    e.preventDefault();
    let editElt = $(this);
    let url = editElt.find(':first').attr('href');
    
    $.ajax({
        type: "GET",
        url: url,
        data: editElt.serializeArray(),
        dataType: 'JSON',
        success: function(data) {
            $('html,body').animate({scrollTop: ( $(".admin_post_form").offset().top - $("#bar").height() )}, '300');
            // add data to fields of the form
            $('.admin_post_title').val(data[0]);
            $('.admin_post_place').val(data[1]);
            $('.admin_post_address').val(data[2]);
            $('.admin_post_date').val(data[3]);
            top.tinyMCE.get('post_content').setContent(data[4]);   
            // modify the form to update the post
            $(".admin_post_form").addClass("update_post" );
            $(".admin_post_form").removeClass("add_post");  
            $('.admin_post_form').attr('action', data[5]);
            $('#btn_post').text('Modifier');
            // add ID to find the title to update
            $('#posts_container').find("#postToEdit").removeAttr('id', 'postToEdit');
            editElt.parents('.post').attr('id', 'postToEdit');
        },
    })
});


// Update a post
$("#container").on('submit', '.update_post', function(e) {
    e.preventDefault();
    let formElt = $(this);
    let url = formElt.attr('action');
    
    $.ajax({
        type: "POST",
        url: url,
        data: formElt.serializeArray(),
        dataType: 'JSON',
        success: function(data) {
            // modify the title
            $('#posts_container').find("#postToEdit").fadeTo(300, 0);
            $('#posts_container').find("#postToEdit").fadeTo(300, 1)
            setTimeout(function() { 
                $('#posts_container').find("#postToEdit .title").html(data[0]);
                $('#posts_container').find("#postToEdit .place").html(data[1]);
                $('#posts_container').find("#postToEdit .address").html(data[2]);
                $('#posts_container').find("#postToEdit .date").html(data[3]);
                $('#posts_container').find("#postToEdit .post_content").html(data[4]);
                $('#posts_container').find("#postToEdit").removeAttr('id', 'postToEdit');
            }, 300);
            // after update, modify the form to add a post
            $(".admin_post_form").removeClass("update_post" );
            $(".admin_post_form").addClass("add_post");
            $(".admin_post_form")[0].reset();
            formElt.attr('action', 'admin.php?action=addPost');
            $('#btn_post').text('Envoyer');
        },
    })
});


// Cancel the form
$("#container").on('click', '#btn_cancel', function(e) {
    if ($('.admin_post_form').hasClass('update_post')) {
        $(".admin_post_form").removeClass("update_post" );
        $(".admin_post_form").addClass("add_post");
    }
    $(".admin_post_form").attr('action', 'admin.php?action=addPost');
    $(".admin_post_form")[0].reset();
    $('#btn_post').text('Envoyer');
    $("#container").addEventListener("load", setDate());
});


// Delete a post and its comments
$('#posts_container').on('click', '.delete_control', function(e) {
    e.preventDefault();
    let deleteElt = $(this);
    let url = deleteElt.find(':first').attr('href');
    
    $.ajax({
        type: "POST",
        url: url,
        data: deleteElt.serializeArray(),
        success: function() {
            deleteElt.parents('.post').fadeOut();
        },
    })
});
