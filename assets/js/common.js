$(document).ready(function () {

  function openMenu () {
    $('#sidebarMenu').addClass('opened');
    $('#main-wrap').addClass('sidebar-opened');

    $('#main-wrap').on('click', closeMenu);
  }

  function closeMenu () {
    $('#sidebarMenu').removeClass('opened');
    $('#main-wrap').removeClass('sidebar-opened');

    $('#main-wrap').off('click', closeMenu);
  }

  $('#toggleSideMenuBtn').click(function (e) {
    e.stopPropagation();
    openMenu();
  });

  // Modal popup
  $('#login-modal-trigger, #register-modal-trigger').magnificPopup({
    type: 'inline',

    fixedContentPos: false,
    fixedBgPos: true,

    overflowY: 'auto',

    closeBtnInside: true,
    preloader: false,
    
    midClick: true,
    removalDelay: 1000,

    mainClass: 'mfp-zoom-in'
  });

	// Carousels
  $(".header-main-slider").owlCarousel({
    items: 1,
    margin: 20,
    loop:  true,
    autoplay: true,
    dots:  true,
  });
  
  $(".product-offers-carousel").owlCarousel({
    loop:    true,
    autoplay: true,
    nav:     true,
    navText: ['<i class="fa fa-long-arrow-left"></i>', '<i class="fa fa-long-arrow-right"></i>'],
    responsive: {
      0: {
        items: 1
      },
      576: {
        items: 2
      },
      992: {
        items: 3
      }
    }
  });

  $(".product-image-gallery").owlCarousel({
    items:   1,
    margin:  20,
    loop:    true,
    autoplay: true,
    nav:     true,
    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    dots:    true,
  });

  $(".testimonials-carousel").owlCarousel({
    margin:  20,
    loop:    true,
    autoplay: true,
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      }
    }
  });

  $('#product-autobet-settings-trigger').click(function () {
    $(this).parent().siblings('.settings').slideToggle(300);
  });

  $('.lk-profile-info-wrap .profile-data-block .block-title').click(function (e) {
      $(this).parent().toggleClass('active');
      $(this).parent().find('.profile-data-set').slideToggle();    
  });

  // Input file  
  $('.input-file-container .input-file').each(function (index) {
    $(this).change(function (e) {
      var files = '';

      Array.from(e.target.files).forEach(function (file) {
        files += file.name + '<br>';
      });

      $(this).parent().siblings('.input-file-return').html(files);
    })
  });

  // Equal heights
  $('.header-main-slider .header-slide-wrapper').equalHeights();

  // To top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 500) { 
      $("#to-top-btn").addClass('active');
    } else { 
      $("#to-top-btn").removeClass('active');
    }
  });

  $('#to-top-btn').click(function () {
    $("body, html").animate({ scrollTop: 0 }, 100);
  });

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch (err) {};

});
