var elixir = require('laravel-elixir');

elixir(function(mix) {

 mix.copy('bower_components/font-awesome/fonts', 'public/build/fonts'); //to make it work with versioning
 mix.copy('bower_components/slick-carousel/slick/fonts', 'public/build/fonts');
 mix.copy('bower_components/slick-carousel/slick/ajax-loader.gif', 'public/build/img/');
 mix.copy('bower_components/ion.rangeslider/img', 'public/build/img');
 mix.copy('bower_components/jquery-ui/themes/smoothness/images/', 'public/build/css/images/');
 mix.copy('bower_components/fancybox/source/*.png', 'public/build/css/');
 mix.copy('bower_components/fancybox/source/*.gif', 'public/build/css/');
 mix.copy('bower_components/fancybox/source/helpers/fancybox_buttons.png', 'public/build/css/');


 mix.sass('app.scss');

 mix.styles([
  '../../../bower_components/font-awesome/css/font-awesome.css',
  '../../../bower_components/sweetalert/dist/sweetalert.css',
  '../../../bower_components/jquery-ui/themes/smoothness/theme.css',
  '../../../bower_components/fancybox/source/jquery.fancybox.css',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-buttons.css',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.css',
  '../../../bower_components/fancybox/source/jquery.fancybox.css',
  '../../../public/css/app.css'
 ]);

 //todo: add modernizr and respond?
 mix.scripts([
  '../../../bower_components/jquery/dist/jquery.js',
  '../../../bower_components/jquery-ui/jquery-ui.js',
  'fix-tooltip.js',
  '../../../bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
  '../../../bower_components/throttle-debounce/dist/throttle-debounce.js',
  '../../../bower_components/jQuery.dotdotdot/src/js/jquery.dotdotdot.js',
  '../../../bower_components/fancybox/source/jquery.fancybox.js',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-buttons.js',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-media.js',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.js',
  '../../../bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.js',
  '../../../bower_components/sweetalert/dist/sweetalert-dev.js',
  'scripts.js'
 ]);

 mix.version(['css/all.css', 'js/all.js']);

 mix.browserSync({
  proxy: 'homestead.app'
 });

});