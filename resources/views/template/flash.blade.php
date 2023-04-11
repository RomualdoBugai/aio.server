<div data-app="flash">
    <div class="ui icon small message">
        <i style="font-size: 16px;"></i>
        <div class="content" data-content>
            <p>

            </p>
        </div>
    </div>
</div>

@if (session()->has('flash_notification.message'))
    <script type="text/javascript">
        $.flash({
            content: '{!! session('flash_notification.message') !!}',
            class  : '{!! session('flash_notification.level') !!}',
        });
    </script>
@endif
