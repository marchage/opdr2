(function($){
  function init($root){
    $root.find('.jom-grid').each(function(){
      var $grid = $(this);
      var reveal = $grid.data('reveal') || 'flip';

      $grid.find('.jom-card').each(function(){
        var $card = $(this);
        $card.attr('data-reveal', reveal);

        function toggle(){
          var flipped = $card.hasClass('is-flipped');
          $card.toggleClass('is-flipped');
          $card.attr('aria-expanded', !flipped ? 'true' : 'false');
        }

        $card.on('click', '.jom-reveal', function(e){
          e.preventDefault();
          toggle();
        });

        $card.on('keydown', function(e){
          if(e.key === 'Enter' || e.key === ' '){
            e.preventDefault();
            toggle();
          }
        });
      });
    });
  }

  $(function(){
    init($(document));
  });

  // Elementor frontend hook for dynamic widgets
  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/joke-o-matic.default', function($scope){
      init($scope);
    });
  }
})(jQuery);
