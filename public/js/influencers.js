// create a stylesheet to insert CSS keyframe rules
$("body").append('<style class="lightbox-animations" type="text/css"></style>');

$(".profile").on('click', function() {
    // set the top & left properties of the container profile
    var bounding_box = $(this).get(0).getBoundingClientRect();
    $(this).css({ top: bounding_box.top + 'px', left: bounding_box.left + 'px' });

    // add animation
    $(this).width( $( this ).parent('.item').width() )
    $(this).height( $( this ).parent('.item').height() )
    $(this).addClass('in-animation');

    // add an empty container in place of the lightbox container
    $('<div id="empty-container"></div>').insertAfter(this);

    // keyframes to animate the container from full-screen to normal
    var styles = '';
    styles = '@keyframes outlightbox {';
        styles += '0% {'; 
        styles += 'height: 100%;';
        styles += 'width: 100%;';
        styles += 'top: 0px;';
        styles += 'left: 0px;';
        styles += '}';
        styles += '100% {';
        styles += 'height: ' + $('.in-animation').height( $('.empty-container').height() ) + 'px;';
        styles += 'width: ' + $('.in-animation').width( $('.empty-container').width() ) + 'px;';
        styles += 'top: ' + bounding_box.y + 'px;';
        styles += 'left: ' + bounding_box.x + 'px;';
        styles += '}';
    styles += '}';

    // add keyframe to CSS
    $(".lightbox-animations").get(0).sheet.insertRule(styles, 0);

    // hide the window scrollbar
    $("body").css('overflow', 'hidden');
});

// close btn
$(".close").on('click', function(e) {
    $(".close").hide();
    $("body").css('overflow', 'auto');
    // show animation
    $(".profile").addClass('out-animation');
    e.stopPropagation();
});

// on animationend : from normal to full screen & full screen to normal
$(".profile").on('animationend', function(e) {
    // on animation end from normal to full-screen
    if(e.originalEvent.animationName == 'inlightbox') {
        $(".close").show();
    }
    // on animation end from full-screen to normal
    else if(e.originalEvent.animationName == 'outlightbox') {
        // Remove fixed positioning, remove animation rules
        $(this).removeClass('in-animation').removeClass('out-animation');
        $(".empty-container").remove();
        // Delete the dynamic keyframe rule that was earlier created */
        $(".lightbox-animations").get(0).sheet.deleteRule(0);
        $('.profile').css('width', '100%');
        $('.profile').css('height', '100%');
    }
});