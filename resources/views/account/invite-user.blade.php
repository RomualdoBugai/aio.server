@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hi')) }} {!! ownName(firstName($wasInvited->name)) !!}!
    </strong>

    <br>
    <br>

    @php ($appUrl = "<a href=\"{$app->url}\">{$app->name}</a>")
    {!! message('common', 'mail.invite-user.text', ['name' => ownName($hasInvited->name), 'app' => $appUrl]) !!}

    <br>
    <br>

    <!-- Button : Begin -->
    <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
        <tr>
            <td>
                <a href="{{ $url }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                    <span style="color:#ffffff;" class="button-link">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {{ message('common', 'mail.invite-user.button') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                </a>
            </td>
        </tr>
    </table>
    <!-- Button : END -->

@stop
