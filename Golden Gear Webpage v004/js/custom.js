//sliders autoplay
	$('#carousel_fade_intro').carousel({
		interval: 4000,
		pause: "false"
	});

	$('#carousel_fade_work').carousel({
		interval: 8000,
		pause: "false"
	});

//smooth scroll on page
	$(function() {
		$('.nav a, .nav li a, .brand, #footer li a').on('click', function(event) {
			var $anchor = $(this);
			var target = $anchor.attr('href');
			if (!target || target === '#') return;
			$('[data-spy="scroll"]').each(function() {
				$(this).scrollspy('refresh');
			});
			$('html, body').stop().animate({
				scrollTop: $(target).offset().top - 61
			}, 1000);
			event.preventDefault();
		});
	});

//collapse menu on click on mobile and tablet devices
	$('.nav a').click(function() { $(".nav-collapse").collapse("hide"); });