/**
 * Created by Dominus on 24.08.16.
 */

$(document).ready(function(){

    $(".ul-dropfree").find("li:has(ul)").prepend('<div class="drop"></div>');
    $(".ul-dropfree div.drop").click(function() {
        if ($(this).nextAll("ul").css('display')=='none') {
            $(this).nextAll("ul").slideDown(400);
            $(this).css({'background-position':"-11px 0"});
        } else {
            $(this).nextAll("ul").slideUp(400);
            $(this).css({'background-position':"0 0"});
        }
    });
    $(".ul-dropfree li:not(:has(strong))").children("ul").slideUp(400);
    $(".ul-dropfree li:not(:has(strong))").children("div.drop").css({'background-position':"0 0"});


    $(".ul-drop").find("li:has(ul)").prepend('<div class="drop"></div>');
    $(".ul-drop div.drop").click(function() {
        if ($(this).nextAll("ul").css('display')=='none') {
            $(this).nextAll("ul").slideDown(400);
            $(this).css({'background-position':"-11px 0"});
        } else {
            $(this).nextAll("ul").slideUp(400);
            $(this).css({'background-position':"0 0"});
        }
    });
    $(".ul-drop").find("ul").slideUp(400).parents("li").children("div.drop").css({'background-position':"0 0"});


    $(".lasttreedrophide").click(function(){
        $("#lasttree").find("ul").slideUp(400).parents("li").children("div.drop").css({'background-position':"0 0"});
    });
    $(".lasttreedropshow").click(function(){
        $("#lasttree").find("ul").slideDown(400).parents("li").children("div.drop").css({'background-position':"-11px 0"});
    });

});