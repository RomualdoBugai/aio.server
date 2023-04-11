<script type="text/javascript">

    (function($){

        $.fn.onSubmit = function(options){

            function addProgress(percentual) {
                $('[data-app="loading"]')
                .find('[data-app="progress"]')
                .progress({
                    percent: percentual
                });
            };

            var settings = $.extend({
                action      : 'submit',
                url         : null,
                method      : 'post',
                beforeSend  : function() {

                },
                success     : function() {

                }
            }, options);

            $(this).on(settings.action, function(event){
                $.ajax({
                    //dataType    : 'json',
                    url         : settings.url,
                    type        : settings.method,
                    data        : new FormData( this ),
                    processData : false,
                    contentType : false,
                    headers     : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    async       : true,
                    cache       : false,
                    beforeSend  : function()
                    {
                        $.loading();
                        return settings.beforeSend();
                    },
                    success     : function(response)
                    {
                        $.loading();
                        $.flash({
                            class   : (response.status == true ? 'green' : 'red'),
                            content : response.message
                        });
                        return settings.success(response);
                    },
                    xhr: function()
                    {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total);
                                addProgress(percentComplete * 100);
                            }
                        }, false);
                            xhr.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total);
                                    addProgress((percentComplete * 100));
                                }
                            }, false);
                        return xhr;
                    }
                });

                return false;

            });

            return this;
        };

        $.fn.startEvent = function(options){

            function addProgress(percentual) {
                $('[data-app="loading"]')
                .find('[data-app="progress"]')
                .progress({
                    percent: percentual
                });
            };

            var settings = $.extend({
                url         : null,
                method      : 'post',
                data        : {},
                beforeSend  : function() {

                },
                success     : function() {

                }
            }, options);

            $.ajax({
                //dataType    : 'json',
                url         : settings.url,
                type        : settings.method,
                data        : settings.data,
                headers     : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                async       : true,
                cache       : false,
                beforeSend  : function()
                {
                    $.loading();
                    return settings.beforeSend();
                },
                success     : function(response)
                {
                    $.loading();
                    $.flash({
                        class   : (response.status == true ? 'green' : 'red'),
                        content : response.message
                    });
                    return settings.success(response);
                },
                xhr: function()
                {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total);
                            addProgress(percentComplete * 100);
                        }
                    }, false);
                        xhr.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total);
                                addProgress((percentComplete * 100));
                            }
                        }, false);
                    return xhr;
                }
            });

            return this;
        };

    })(jQuery);

</script>
