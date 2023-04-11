@if($status == true)
    <div class="ui vertical menu fluid">
        @foreach($users as $user)
        <a class="item" href="{{ route('user.show', ['id' => $user['id']]) }} ">
            {{ ownName($user['name']) }}
        </a>
        @endforeach
    </div>
@endif