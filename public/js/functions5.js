
$(document).ready(function() {

	$('button.delete-machine-index').click(function() {
		var del = confirm("Are you sure you want to delete this post?");
		if(del) {
			window.location = $(this).data('href');
		}
	});

	$('button.delete-machine').click(function() {
		var del = confirm("Are you sure you want to delete this post?");
		if(del) {
			window.location = $(this).data('href');
		}
	});

	$('.fancybox').fancybox();

	$('.fancybox-media')
		.attr('rel', 'media-gallery')
		.fancybox({
			openEffect : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',

			arrows : false,
			helpers : {
				media : {},
				buttons : {}
			}
		});

    jQuery.ajaxSetup({ headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } });
	
	$(".like").click(function() {

            if(loggedIn == 0)
            {
                console.log('You must be logged in to like posts!');
                return;
            }
	
			var id = $(this).data('id');

            if(!($(this).hasClass('btn-warning')) && !($(this).hasClass('voted'))) //already voted if warning
            {
                if($(this).hasClass('btn'))
                {
                    $(this).removeClass('btn-primary');
                    $(this).addClass('btn-warning');
                } else {
                    $(this).addClass('voted');
                }

                var score = $('.score[data-id="' + id + '"]').html();
                $('.score[data-id="' + id + '"]').html(parseInt(score) + 1);

                likePost(id);
            }




/*
			//not voted, vote
			if(!$('.like[data-id="' + id + '"]').hasClass('voted')) {
			
				var score = $('.score[data-id="' + id + '"]').html();

				$('.score[data-id="' + id + '"]').html(parseInt(score) + 1);
				
				$('.like[data-id="' + id + '"]').addClass('voted');

				likePost(id);

			//already upvoted, remove upvote
			} else if($('.like[data-id="' + id + '"]').hasClass('voted')) {
			
				
				var score = $('.score[data-id="' + id + '"]').html();

				$('.score[data-id="' + id + '"]').html(parseInt(score) - 1);
				
				$('.like[data-id="' + id + '"]').removeClass('voted');

				removeLike(id);

			}*/
	});
	
});


function likePost(id) {

    $.ajax({
        type: 'POST',
		url: '/like/' + id,
		complete : function(){
			
		},
		success: function(json){

		},
        fail: function(json){
            //alert('ajax failure');
        },
        error: function(json) {
            //alert('ajax failure');
        }
	});
}

function removeLike(id) {
/*
	$.ajax({
		url: '/ajax/like.php',
		data: {'id': id, 'isUpvote': -1},
		dataType: 'json',
		complete : function(){
			
		},
		success: function(json){
	
		
		}
	});
*/

}