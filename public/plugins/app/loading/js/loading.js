

    $.loading = function(title) {
        var $element = $('[data-app="loading"]');
        $element.find(".title").html("").html(title);
        if ($element.data('status') == 'started') {
            $element.delay(100).fadeOut(100).data('status', 'stoped');
        } else {
            $element.fadeIn(100).data('status', 'started');
        }
    }
