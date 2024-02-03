
  $(window).load(function() {

     // init Isotope
      var $grid = $('.grid').isotope({
        // options
      });
      // filter items on button click
      $('.gameNav ul').on( 'click', 'li', function() {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue});
      });


      $('.gameNav li').click(function(){
              $('.gameNav *').removeClass('active');  
              $(this).addClass('active');
      });


        $('ul.tabs li').click(function(){
              var tab_id = $(this).attr('data-tab');

              $('ul.tabs li').removeClass('active');
              $('.tab-content').removeClass('active');

              $(this).addClass('active');
              $("#"+tab_id).addClass('active');
        });

        $('.gameSlider1').owlCarousel({
              items: 6,
              nav: true,
              navText: "<>",
              dots: false,
              loop: true,
              rewindNav: false,
              margin: 20,
              autoplay: true,
              autoplayTimeout: 1500,
              autoplayHoverPause: true,
              responsive: {
                  0: {
                      items: 1
                  },
                  480:{
                      items:2
                  },
                  768: {
                      items: 3
                  },
                  1000: {
                      items: 6
                  }
              }
      });


      $('.gameSlider2').owlCarousel({
              items: 1,
              nav: false,
              dots: true,
              loop: true,
              rewindNav:true,
              margin:0,
              autoplay: true,
              autoplayTimeout:5000,
              autoplayHoverPause: true
      });

        
  });