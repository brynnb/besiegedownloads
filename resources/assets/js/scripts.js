$(document).ready(function() {

    var csrfToken = $('meta[name="csrfToken"]').attr('content')

    $('p.read-more').hide();

    $('.read-more-toggle').click(function() {
       $('p.read-more').slideToggle();
    });

    $('.post .instructions').click(function() {
        swal({
            title: "Using .bsg files",
            text: '<p>BSG files are made by Besiege when you save your machine, vehicle, or creation. They can be opened and modified with a text editor just like a text file (it uses an XML format), but it\'s not very straightforward to do so. <br/ ><br/ > You can use any saved machines found here by downloading the .bsg file and saving it to the "SavedMachines" folder inside the Besiege installation folder. It is often located here: </p>',
            type: "input",
            inputValue: 'C:\\Program Files (x86)\\Steam\\SteamApps\\common\\Besiege\\Besiege_Data\\SavedMachines',
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Got it!",
            closeOnConfirm: true,
            html: true
        });

        window.setTimeout(function() {
            $('.sweet-alert input').select();
        }, 500);

    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    var defaultDotSettings = {
        ellipsis	: '... ',
        wrap		: 'letter',
        watch		: true,
        lastCharacter	: {

            /*	Remove these characters from the end of the truncated text. */
            remove		: [ ' ', ',', ';', '.', '!', '?' ]
        }
    }

    $(".tile .footer .title").dotdotdot(defaultDotSettings);

    $(".tile .footer .bottom").dotdotdot(defaultDotSettings);

    $( ".featured-store-tile .title").dotdotdot(defaultDotSettings);

    $("ul.product-management .list-group-item .list-item .description").dotdotdot(defaultDotSettings);

    //$(".misc-desc-thumb").dotdotdot(defaultDotSettings);

    //if the tagline is too long in any page width, "hide" it
    $('.header-tagline .tagline').dotdotdot({
        ellipsis	: '... ',
        wrap		: 'letter',
        watch		: true,
        lastCharacter	: {

            /*	Remove these characters from the end of the truncated text. */
            remove		: [ ' ', ',', ';', '.', '!', '?' ]
        },
        callback    : function(isTruncated, orgContent ) {
            if(isTruncated) {
                this.fadeTo(0, 0.01); //can't hide entirely because dotdotdot will stop watching
            } else {
                this.fadeTo(0, 1);
            }
        }.bind($('.header-tagline .tagline'))
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $('.delete').on('click', function() {

        var id = $(this).data('id');
        var type = $(this).data('type');
        var token = $(this).data('token')
        var _this = $(this);

        swal({
            title: "Delete " + capitalizeFirstLetter(type) + "?",
            text: "You will not be able to recover this " + type + "!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            html: false
        }, function(){
            $.ajax({
                url: '/' + type + '/' + id,
                type: 'post',
                data: {_method: 'delete', _token: csrfToken},
                success: function () {
                    _this.parent().parent().parent().fadeOut(); //TODO: make this more versatile
                    swal("Deleted!",
                        "Your " + type + " has been deleted.",
                        "success");
                    var slotsPrev = $('#slotsUsed').html();
                    $('#slotsUsed').html(slotsPrev - 1);
                },
                error: function (){
                    swal("Error!",
                        "There was a problem deleting your " + type + ".",
                        "error");
                }
            });

        });

    });

    $('.lockToggle').on('click', function() {

        var id = $(this).data('id');
        var token = $(this).data('token');
        var locked = $(this).data('locked');
        var _this = $(this);

        if(locked == "1") {
            var message = "Unlocking this account will make it accessible again and republish all of its products!";
            var type = "unlock";
        } else {
            var message = "Locking this account will make it inaccessible and unpublish all of its products!";
            var type = "lock"
        }

        swal({
            title: capitalizeFirstLetter(type) + " User?",
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, " + capitalizeFirstLetter(type) + " User!",
            closeOnConfirm: false,
            html: false
        }, function(){
            $.ajax({
                url: '/' + type + 'User/' + id,
                type: 'post',
                data: {_method: 'post', _token: csrfToken},
                success: function () {
                    swal("Successfully " + type + "ed!",
                        "User has been " + type + "ed.",
                        "success");
                    _this.data('locked', 1 - locked);
                    _this.children('i').toggleClass('fa-unlock');
                    _this.children('i').toggleClass('fa-lock');
                },
                error: function (){
                    swal("Error!",
                        "There was a problem updating the user.",
                        "error");
                }
            });

        });

    });

    $(document).on('click', '.btn.load-more', function(e) {

        $(this).css('background-color', 'white');
        $(this).text('');
        $(this).append('<img src="/img/ajax-loader.gif">');
        var _oldButton = $(this);

        $('<div>').load($(this).data('url') + " .result-grid", function() {
            $(".result-grid").append($(this).find(".result-grid").html());
            _oldButton.hide();

            $('.btn.load-more').each(function() {
                if ($(this).data('url') == '') {
                    $(this).hide();
                }
            });

            $(".tile .footer .title").dotdotdot(defaultDotSettings);

            $(".tile .footer .bottom").dotdotdot(defaultDotSettings);


        });



    });


    //prevent forms from submitting on enter
    $(document).on("keypress", ':input:not(textarea):not([type="password"])', function(event) {
        return event.keyCode != 13;
    });


});


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
