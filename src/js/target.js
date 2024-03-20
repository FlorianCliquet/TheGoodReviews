$(document).ready(function(){
    $('.target').click(function (event){
        var url = $(this).attr("href");
            window.open(url);
            event.preventDefault();
    });
});﻿