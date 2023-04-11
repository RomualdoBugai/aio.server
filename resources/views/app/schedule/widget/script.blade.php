<script type="text/javascript">

    (function($){

        $.fn.calendar = function(options){

            var settings = $.extend({
                data        : "date",
                url         : null,
                container   : '[data-container]',
                method      : 'post'
            }, options );

            return this.each(function(){

                var $this = $(this);

                $this.find('[data-' + settings.data + ']').on('click', function(event){

                    $this.find('[data-' + settings.data + ']').removeClass("inverted").removeClass("blue");
                    
                    $(this).addClass("inverted").addClass("blue");

                    $.ajax({
                        dataType: 'json',
                        url     : settings.url,
                        type    : settings.method,
                        data    : {date: $(this).data(settings.data)},
                        headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        async   : true,
    					cache   : false,
                        beforeSend: function(){
                            $.loading();
                        },
                        success: function(response){
                            $this.find(settings.container).html(response.html);
                            $.loading();
                        }
                    });
                });

                return this;
            });
        };

        $('[data-app="calendar"]').calendar({
            url: '{{ route('userScheduleByDate') }}'
        });

    })(jQuery);

</script>
