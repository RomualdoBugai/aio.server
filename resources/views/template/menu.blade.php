@php ($routePrefixes = explode("/", \Request::route()->getPrefix()))
@php ($routePrefix   = $routePrefixes[1])

<!--
<a class="item {{ ( $routePrefix == "enterprise" ? 'active' : null ) }}" href="{{ route("enterprise.index") }}">
-->

<a class="item" href="{{ route("enterprise.index") }}">
    <i class="icon-users icon"></i>
    {{ message("enterprise", "index") }}
</a>

<!--
<a class="item" href="{{ route("bankAccount.index") }}">
    <i class="icon-dollar icon"></i>
    {{ message("bank-account", "index") }}
</a>

<a class="item" href="{{ route("expense.index") }}">
    <i class="icon-dollar icon"></i>
    {{ message("expense", "index") }}
</a>
-->
