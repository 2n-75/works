window.onload = function() {


  var pathEls = document.querySelectorAll('path');
  for (var i = 0; i < pathEls.length; i++) {
    var pathEl = pathEls[i];
    var offset = anime.setDashoffset(pathEl);
    pathEl.setAttribute('stroke-dashoffset', offset);

    var anime_1 = anime({
      targets: pathEl,
      strokeDashoffset: [offset, 0],
      duration: anime.random(1000, 1500),
      delay: anime.random(0, 1000),
      //direction: 'alternate',
      easing: 'easeInOutSine',

      autoplay: true
    });
  }

  var anime_4 = anime({
    targets: '.light',
    delay: 2400,
    duration: 1500,
    translateY: [0, -180],
    fill: '#ffffff',
    scale: {
      value: 0.8,
    },
    easing: 'easeInOutQuad'
  })

  var anime_2 = anime({
    targets: '.svg-wrapper',
    delay: 2400,
    duration: 1500,
    translateY: [0, -180],
    opacity: [0, 1],
    easing: 'easeInOutQuad'
  });

  var anime_2 = anime({
    targets: '.svg-wrapper2',
    translateY: [0, -180],
    delay: 2400,
    duration: 1500,
    opacity: [0, 1],
    easing: 'easeInOutQuad'
  });

}
