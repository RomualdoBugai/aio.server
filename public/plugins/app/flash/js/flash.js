

        $.flash = function(options) {

            var settings = $.extend({
                class   : 'success',
                content : 'testing',
                icon    : 'icon-flash icon'
            }, options );

            var $element = $('[data-app="flash"]');

            $element
            .delay(500)
            .fadeIn(100)
            .delay(5000)
            .fadeOut(100);

            $element.find('i')
            .addClass(settings.icon);

            $element.find('[data-content]')
            .find('p')
            .html('')
            .html(settings.content);

            $element.find('.message')
            .removeClass('yellow')
            .removeClass('orange')
            .removeClass('red')
            .removeClass('blue')
            .removeClass('green')
            .removeClass('teal')
            .removeClass('black')
            .removeClass('violet')
            .addClass(settings.class);

        };
