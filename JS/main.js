$(document).ready(function () {
$('.postArticle').one('click',function (event) {
    event.preventDefault();
    var contentId = $(this).attr('data-contentId');
    console.log(contentId);
    $.ajax({
        url: '/ajax/showContentsHandler.php',
        data: {
            "articleId" : contentId
        },
        dataType: 'json',
        method: 'POST'
    }).done(function (content) {
        $("." + contentId).children('.hidden').text(content);


    })
})


    $('.getArticle').one('click',function (event) {
        event.preventDefault();
        var contentId = $(this).attr('data-contentId');
        console.log(contentId);
        $.ajax({
            url: '/ajax/showContentsHandler.php?articleId=' + contentId,
            method: 'GET'
        }).done(function (content) {
            $("." + contentId).children('.hidden').text(content);


        })
    })
})