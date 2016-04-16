scrollToElement = function scrollToElement(id, speed){
  speed = typeof speed=='undefined' ? 200 : speed;

  // Remove "link" from the ID
  id = id.replace("link", "");
    // Scroll
  $('html,body').animate({
      scrollTop: $("#"+id).offset().top},
      speed);
};