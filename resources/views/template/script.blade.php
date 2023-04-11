<script type="text/javascript" src="{{{ URL::asset('plugins/jquery/jquery-3.1.1.min.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('semantic-ui/semantic.min.js') }}}"></script>

<script type="text/javascript" src="{{{ URL::asset('plugins/moment/js/moment-with-locales.js') }}}"></script>


<script type="text/javascript" src="{{{ URL::asset('plugins/jquery.inputmask-3.x/dist/jquery.inputmask.bundle.min.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('plugins/app/loading/js/loading.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('plugins/app/flash/js/flash.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('plugins/clockpicker/clockpicker.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('plugins/datetimepicker/build/jquery.datetimepicker.full.min.js') }}}"></script>
<script type="text/javascript" src="{{{ URL::asset('plugins/jquery-mask-money/jquery.maskMoney.js') }}}"></script>


<script type="text/javascript">
    $(function(){

        'use strict';

        $('.checkbox,.radio').checkbox();
        $('.dropdown').dropdown();
        $('.menu .item').tab();
        $('.accordion').accordion();

        $('.clockpicker').clockpicker({
            autoclose: true,
            donetext: '{{ message('common', 'done') }}'
        });

        $.datetimepicker.setLocale('pt-BR');

        $("[datepicker]").datetimepicker(
            {
                lang: 'pt-BR',
                lazyInit: true,
                value: null,
                mask: true,
                format:'{{ inputDateFormat() }}',
                timepicker: false,
                minDate: '{{ date(inputDateFormat()) }}',
                startDate: '{{ date(inputDateFormat()) }}'
            }
        );

        $.fn.widgetFollow = function() {
            return this.each(function(){
                $(this).find('[data-following]').click(function(){
                    var $this   = $(this);

                    var update  = $this.data('update');
                    var show    = $this.data('show');

                    $.ajax({
                        dataType: 'json',
                        url     : update,
                        type    : "POST",
                        data    : $(this).serialize(),
                        headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        async   : true,
    					cache   : false,
                        beforeSend: function(){
                            $.loading();
                        },
                        success: function(response){
                            var $container = $this.closest('[data-widget="follow"]');
                            $.get(show, function(html){
                                $container.html("");
                                $container.html(html);
                                $container.widgetFollow();
                            });
                            $.loading();
                        }
                    });

                    return false;
                });
            });
        }

        $('[data-widget="follow"]').widgetFollow();


        $('a[data-tab="favorites"]').click(function(event){

            $.ajax({
                url     : "{{ route("follow.index", ['controller' => 'enterprise'])  }}",
                type    : "GET",
                headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                async   : true,
                cache   : false,
                beforeSend: function(){
                    $.loading();
                },
                success: function(response){
                    $('div[data-tab="favorites"]').html(response);
                    $.loading();
                }
            });

        });

        $('[data-app="quotation"] [reload]').on('click', function(event){
            $.ajax({
                url     : "{{ route("currencyQuote.index")  }}",
                type    : "GET",
                headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                async   : true,
                cache   : false,
                beforeSend: function(){
                    $.loading();
                },
                success: function(response){
                    $.loading();
                }
            });
        });


        var Quotation = {
            show: function(){
                $.ajax({
                    url     : "{{ route("currencyQuote.index")  }}",
                    type    : "GET",
                    headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    async   : true,
                    cache   : false,
                    beforeSend: function(){
                        $.loading();
                    },
                    success: function(response){
                        $('[data-container="quotation"]').find("[container]").html(response);
                        $.loading();
                        $(".menu .item").tab();
                    }
                });
            },
            reload: function(){
                $.ajax({
                    url     : "{{ route("currencyQuote.auto")  }}",
                    type    : "GET",
                    headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    async   : true,
                    cache   : false,
                    beforeSend: function(){
                        $.loading();
                    },
                    success: function(response){
                        $.loading();
                        $.flashMessage({
                            class   : (response.status == true ? 'green' : 'red'),
                            content : response.message
                        });
                    }
                });
                this.show();
            }
        }

        $('a[data-tab="quotation"]').on('click', function(event){
            Quotation.show();
            return false;
        });

        $('[data-container="quotation"]').find('[reload]').on('click', function(event){
            Quotation.reload();
        });

        //$("[price-format]").maskMoney();
        var priceFormat = {
           change: function($selector){
                var currency = $selector.val();
                return $('[price-format="value"]').each(function(){
                    if (currency == 1) {
                        $(this).maskMoney(
                            {thousands:'.', decimal:',', prefix: 'R$ ', affixesStay: false}
                        );
                    }
                    if (currency == 2) {
                        $(this).maskMoney(
                            {thousands:',', decimal:'.', prefix: 'US$ ', affixesStay: false}
                        );
                    }
                    if (currency == 2) {
                        $(this).maskMoney(
                            {thousands:',', decimal:'.', prefix: '€ ', affixesStay: false}
                        );
                    }

                    if (currency == 2) {
                        $(this).maskMoney(
                            {thousands:',', decimal:'.', prefix: '£ ', affixesStay: false}
                        );
                    }
                    $(this).focus();
                });
           }
        };

        //$('[price-format="value"]').priceFormat();

        $('[price-format="selector"] > select').on('change', function(event){
            priceFormat.change($(this));
        });


    });
</script>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
