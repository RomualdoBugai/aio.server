<script type="text/javascript">
$(function(){
    'use strict';
    $("[autocomplete-postal-code]").on('blur', function(event){
        var $this = $(this);
        var value = $this.val().replace(/[^\w\s]/gi, '');
        if (value.length == 8)
        {
            $.ajax({
                url : "https://viacep.com.br/ws/" + value + "/json/",
                type: "GET",
                datatype: 'JSON',
                beforeSend: function(){
                    loading.start();
                },
                success: function(response){
                    if (typeof response.erro != 'undefined')
                    {

                    } else {
                        $.each($("[data-address-item]"), function(k,i){
                            var field = $(i).data("address-item");
                            $.each(response, function(f,v){
                                if (f == field)
                                {
                                    $(i).val(v);
                                }
                            });
                        });
                    }
                    loading.stop();
                }
            });
        }
    });
});
</script>
