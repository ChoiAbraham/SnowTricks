var main = function() {
    $('.dropdown-toggle').click(function() {
        $('.dropdown-menu').toggle();
    });

    $('.arrow-next').click(function() {
        var currentSlide = $('.active-slide');
        var nextSlide = currentSlide.next();
        var currentDot = $('.active-dot');
        var nextDot= currentDot.next()
        //if the current slide is the last, make the next slide the first one
        if(nextSlide.length == 0 ) {
            nextSlide = $('.slide').first() ;
        }
        //if the next dot is the last, make the next dot the first one
        if(nextDot.length == 0){
            nextDot = $('.dot').first()
        }
        //when the arrow is clicked, fade out the current img and fade in the next one
        currentSlide.fadeOut(600).removeClass('active-slide');
        nextSlide.fadeIn(600).addClass('active-slide');
        //when the arrow is clicked, remove the active class from the current dot and add it to the next one
        currentDot.removeClass('active-dot')
        nextDot.addClass('active-dot')
    });
    $('.arrow-prev').click(function(){
        //creating the variables so that we can target them in jQuery
        var currentSlide = $('.active-slide')
        var prevSlide = currentSlide.prev()
        var currentDot= $('.active-dot')
        var prevDot= currentDot.prev()
        //if the current slide is the last, make the prev slide the last one
        if(prevSlide.length == 0){
            prevSlide = $('.slide').last();
        }
        //if the current dot is the last, make the prev dot the last one
        if(prevDot.length==0){
            prevDot = $('.dot').last();
        }
        //fade out the current img and fade in the prev one when the arrow is clicked
        currentSlide.fadeOut(600).removeClass('active-slide')
        prevSlide.fadeIn(600).addClass('active-slide')
        //when the arrow is clicked, remove the active class from the current dot and add it to the next one
        currentDot.removeClass('active-dot')
        prevDot.addClass('active-dot')
    });
}

$(document).ready(main);