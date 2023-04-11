<script type="text/javascript">
    $(function(){
        'use strict';
        var $form = $("[create-user-address]");

        $form.on("submit", function(event){
            var $this = $(this);
            var url = $this.attr("action");
            var method = $this.attr("method");
            var token = $this.find('input[name="_token"]').val();
            $.ajax({
                url : url,
                type: method,
                data: $this.serializeArray(),
                datatype: 'json',
                headers : {'X-CSRF-TOKEN': token},
                async   : true,
        		cache   : false,
                beforeSend: function(){
                    loading.start();
                },
                success: function(response){
                    console.log(response);
                    loading.stop();
                }
            });
            return false;
        });
    });
</script>
