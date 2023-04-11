<span data-widget="follow">
    <a data-following="{{ ($following == true ? 1 : 0) }}" data-update="{{ route("follow.update", ['controller' => $controller, 'controller_id' => $controller_id]) }}" data-show="{{ route("follow.show", ['controller' => $controller, 'controller_id' => $controller_id]) }}">
        {!! ($following == true ? '<i class="icon-star inverted orange icon"></i>' : '<i class="icon-star-empty inverted orange icon"></i>' ) !!}
    </a>
</span>
