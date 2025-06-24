$(function (){

    'use strict';


    // toggel hide the contaner
    $('.toggel-info').click(function(){
        $(this).toggleClass("selected").parent().next(".panel-body").fadeToggle(200)

        if($(this).hasClass("selected")){
            $(this).html('<i class="fa fa-plus fa-lg"></i>')
        }else{
            $(this).html('<i class="fa fa-minus fa-lg"></i>')

        }
    })


    // fanction for hide placeholder when focus in input 
    $('[placeholder]').focus(function(){
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','')
    }).blur(function(){
        $(this).attr('placeholder',$(this).attr('data-text'))
    })
})

// fanction for make a star in the required input
$('input').each(function (){
    if($(this).attr('required') === 'required'){

        $(this).after('<span class="req"> * </span>')

    }
})

// fanction for show password

let show=$(".password")

$(".show-pass").hover(function(){
    show.attr("type","text")
}, function(){
    show.attr("type","password")

})

// fanction for the admin delete  


$(".confirm").click(function(){
    return confirm("Are You Sure?")
})

//  fanction for viwe contener

$('.cat h3').click(function(){
    $(this).next('.viwe-classic').fadeToggle(200);
})

$(".cat span").click(function(){
    $(this).addClass('active').siblings('span').removeClass('active')
    if($(this).data('view')==="Full"){
        $('.cat .viwe-classic').fadeIn(200)
    }else{
        $('.cat .viwe-classic').fadeOut(200)
    }
})