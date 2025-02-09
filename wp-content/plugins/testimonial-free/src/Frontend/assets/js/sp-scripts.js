jQuery(document).ready(function ($) {
	$('.sp-testimonial-free-section').each(function (index) {
		var _this = $(this),
			custom_id = $(this).attr('id'),
			preloader = _this.data('preloader');

		if ('1' == preloader) {
			var parents_class = $('#' + custom_id).parent('.sp-testimonial-free-wrapper'),
				parents_siblings_id = parents_class.find('.sp-testimonial-preloader').attr('id');

			setTimeout(function () {
				$('#' + parents_siblings_id).animate({ opacity: 1 }, 600).hide();
			}, 600);
			$('#' + custom_id).animate({ opacity: 1 }, 600)
		}

		var SliderId = $(this).attr('id');
		var SliderData = $(this).data('swiper');
		if (SliderData && !$('#' + SliderId + '.sp-testimonial-free-section').hasClass('swiper-initialized')) {
			var TestimonialSwiper = new Swiper('#' + SliderId + '.sp-testimonial-free-section', {
				speed: SliderData.speed,
				slidesPerView: SliderData.slidesPerView.lg_desktop,
				slidesPerGroup: 1,
				spaceBetween: SliderData.spaceBetween,
				loop: SliderData.infinite,
				loopFillGroupWithBlank: true,
				autoHeight: SliderData.adaptiveHeight,
				simulateTouch: SliderData.draggable,
				freeMode: SliderData.freeMode,
				allowTouchMove: SliderData.swipe,
				mousewheel: SliderData.swipeToSlide,
				slidesPerGroupSkip: 1,
				grabCursor: true,
				pagination:
					SliderData.dots == true
						? {
							el: '#' + SliderId + ' .testimonial-pagination',
							clickable: true,
						}
						: false,
				autoplay: {
					delay: SliderData.autoplaySpeed
				},
				navigation:
					SliderData.arrows == true
						? {
							nextEl: '#' + SliderId + ' .swiper-button-next.testimonial-nav-arrow',
							prevEl: '#' + SliderId + ' .swiper-button-prev.testimonial-nav-arrow',
						}
						: false,
				breakpoints: {
					320: {
						slidesPerView: SliderData.slidesPerView.mobile,
						navigation:
							SliderData.navigation_mobile == true
								? {
									nextEl: '#' + SliderId + ' .swiper-button-next.testimonial-nav-arrow',
									prevEl: '#' + SliderId + ' .swiper-button-prev.testimonial-nav-arrow',
								}
								: false,
						pagination:
							SliderData.pagination_mobile == true
								? {
									el: '#' + SliderId + ' .testimonial-pagination',
									clickable: true,
								}
								: false,
					},
					576: {
						slidesPerView: SliderData.slidesPerView.tablet,
					},
					736: {
						slidesPerView: SliderData.slidesPerView.laptop,
					},
					980: {
						slidesPerView: SliderData.slidesPerView.desktop,
					},
					1200: {
						slidesPerView: SliderData.slidesPerView.lg_desktop,
					},
				},
				keyboard: {
					enabled: true
				},
			});
			if (SliderData.autoplay === false || (($(window).width() < 576) && SliderData.autoplay_mobile == false)) {
				TestimonialSwiper.autoplay.stop()
			}
			if (SliderData.pauseOnHover && SliderData.autoplay) {

				$('#' + SliderId).on({
					mouseenter: function () {
						TestimonialSwiper.autoplay.stop();
					},
					mouseleave: function () {
						TestimonialSwiper.autoplay.start();
					}
				});
			}
		}
	});

});