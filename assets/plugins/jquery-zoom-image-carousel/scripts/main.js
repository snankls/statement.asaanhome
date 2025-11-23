// Initialize product gallery

$(function(){

	$('.jquery-zoom-image-carousel .show').zoomImage();

	$('.jquery-zoom-image-carousel .show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'})
	$('.jquery-zoom-image-carousel .show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt')
	$('.jquery-zoom-image-carousel .show-small-img').click(function () {
	  $('.jquery-zoom-image-carousel #show-img').attr('src', $(this).attr('src'))
	  $('.jquery-zoom-image-carousel #big-img').attr('src', $(this).attr('src'))
	  $(this).attr('alt', 'now').siblings().removeAttr('alt')
	  $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
	  if ($('.jquery-zoom-image-carousel #small-img-roll').children().length > 4) {
		if ($(this).index() >= 3 && $(this).index() < $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1){
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
		} else if ($(this).index() == $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1) {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($('.jquery-zoom-image-carousel #small-img-roll').children().length - 4) * 76 + 'px')
		} else {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', '0')
		}
	  }
	})

	// Enable the next button
	// ToDo: Set image zoomer plugin as paid service.
	$('.jquery-zoom-image-carousel #next-img').click(function (){
	  $('.jquery-zoom-image-carousel #show-img').attr('src', $(".jquery-zoom-image-carousel .show-small-img[alt='now']").next().attr('src'))
	  $('.jquery-zoom-image-carousel #big-img').attr('src', $(".jquery-zoom-image-carousel .show-small-img[alt='now']").next().attr('src'))
	  $(".jquery-zoom-image-carousel .show-small-img[alt='now']").next().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
	  $(".jquery-zoom-image-carousel .show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
	  if ($('.jquery-zoom-image-carousel #small-img-roll').children().length > 4) {
		if ($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() >= 3 && $(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() < $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1){
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() - 2) * 76 + 'px')
		} else if ($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() == $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1) {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($('.jquery-zoom-image-carousel #small-img-roll').children().length - 4) * 76 + 'px')
		} else {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', '0')
		}
	  }
	})

	$('.jquery-zoom-image-carousel #prev-img').click(function (){
	  $('.jquery-zoom-image-carousel #show-img').attr('src', $(".jquery-zoom-image-carousel .show-small-img[alt='now']").prev().attr('src'))
	  $('.jquery-zoom-image-carousel #big-img').attr('src', $(".jquery-zoom-image-carousel .show-small-img[alt='now']").prev().attr('src'))
	  $(".jquery-zoom-image-carousel .show-small-img[alt='now']").prev().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
	  $(".jquery-zoom-image-carousel .show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
	  if ($('.jquery-zoom-image-carousel #small-img-roll').children().length > 4) {
		if ($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() >= 3 && $(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() < $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1){
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() - 2) * 76 + 'px')
		} else if ($(".jquery-zoom-image-carousel .show-small-img[alt='now']").index() == $('.jquery-zoom-image-carousel #small-img-roll').children().length - 1) {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', -($('.jquery-zoom-image-carousel #small-img-roll').children().length - 4) * 76 + 'px')
		} else {
		  $('.jquery-zoom-image-carousel #small-img-roll').css('left', '0')
		}
	  }
	})

	$('body').on("click", ".jquery-zoom-image-carousel i.image_zoomer", function(e){
		console.log(".jquery-zoom-image-carousel i.image_zoomer click handler");
		$(".lightbox_other_images .lightbox-image[href='"+$(this).closest('.show').find('#show-img').attr("src")+"']").click();
		CF.Prevent(e);
	});

})
