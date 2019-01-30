window.onload = function() {
  var anime_1 = anime({

    targets: '.square1',
    backgroundColor: [
      {value: 'rgb(255, 255, 255)'},
    ],
    loop:true,
    duration:5000,
  });

  var anime_2 = anime({
    targets: '.square2',
    backgroundColor: [{
      value: 'rgb(255,255,255)'
    }, ],
    duration: 5000,
    loop: true,
    easing: 'linear'
  });

  var anime_3 = anime({
    targets: '.square3',
    backgroundColor: [{
      value: 'rgb(0,0,0)'
    }, ],
    duration: 5000,
    loop: true,
    easing: 'linear'
  });

  var anime_4 = anime({
    targets: '.square4',
    backgroundColor: [
      {value: 'rgb(255, 255, 255)'},
    ],
    duration: 5000,
    loop: true
  });

  var anime_5 = anime({

        targets: '.square5',
        backgroundColor: [
          {value: 'rgb(0,0,0)'},
          {value: 'rgb(255, 255, 255)'},
        ],
        loop:true,
  });
}
