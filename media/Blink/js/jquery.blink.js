/*
Blink Slider - ver 1.0
Author: Fernando Mercadal https://fermercadal.github.io
*/
jQuery.fn.blink = function(options) {
  var config = {
    speedIn: 500,
    speedOut: 300,
    viewTime: 6000,
    items: true,
    navigation: true,
    prevText: '◄ Prev',
    nextText: 'Next ►'
  }
  jQuery.extend(config, options);

  if(config.speedIn > config.viewTime) {
    config.speedIn = config.viewTime;
  }

  this.each(function(){
    var thisSlider = $(this);
    var totalTime = config.speedIn + config.viewTime + config.speedOut;
    thisSlider.find('div.viewSlide:gt(0)').css({opacity: 0});
    thisSlider.find('div.viewSlide:first-child').addClass('active');

    /* Blink 'Next'*/
    function blinkNext() {
      let activeView = $('div.viewSlide.active');
      let activeItem = $('li.viewItem.active');

      let nextView = activeView.next('div.viewSlide');
      if (nextView.length === 0) {
        nextView = $('div.viewSlide:first-child');
      }
      let nextItem = activeItem.next('li.viewItem');
      if (nextItem.length === 0) {
        nextItem = $('li.viewItem:first-child');
      }

      activeView.removeClass('active').animate({opacity: 0}, config.speedOut);
      activeItem.removeClass('active');

      nextView.addClass('active').delay(config.speedOut).animate({opacity: 1}, config.speedIn)
      nextItem.addClass('active');
    }

    /* Blink 'Prev'*/
    function blinkPrev() {
      let activeView = $('div.viewSlide.active');
      let activeItem = $('li.viewItem.active');

      let prevView = activeView.prev('div.viewSlide');
      if (prevView.length === 0) {
        prevView = $('div.viewSlide:last-child');
      }
      let prevItem = activeItem.prev('li.viewItem');
      if (prevItem.length === 0) {
        prevItem = $('li.viewItem:last-child');
      }

      activeView.removeClass('active').animate({opacity: 0}, config.speedOut);
      activeItem.removeClass('active');

      prevView.addClass('active').delay(config.speedOut).animate({opacity: 1}, config.speedIn);
      prevItem.addClass('active');
    }

    /* Blink Loop (auto) */
    var blinkLoop = setInterval(blinkNext, config.viewTime);

    /* BLINK CONTROLS */
    /* Add Items */
    if(config.items) {
      $('#blink-control').append('<ul class="blink-items"></ul>');
      $('.viewSlide').each(function(){
        $('.blink-items').append('<li class="viewItem"></li>');
      })
      $('li.viewItem:first-child').addClass('active');
    }
    /* Add Navigation */
    if(config.navigation) {
      $('#blink-control').prepend('<button class="button" id="prev">' + config.prevText + '</button>')
      $('#blink-control').append('<button class="button" id="next">' + config.nextText + '</button>')
      /* Next button */
      $('#next').click(function(){
        clearInterval(blinkLoop);
        $('div.viewSlide.active').stop(true, false);
        blinkNext();
      });
      /* Prev button */
      $('#prev').click(function(){
        clearInterval(blinkLoop);
        $('div.viewSlide.active').stop(true, false);
        blinkPrev();
      });
    }
  });
return this;
};
