@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hi')) }} {!! ownName(firstName($user->name)) !!}!
    </strong>

    <br>
    <br>

    {!! message('common', 'mail.user-email-confirmed.text') !!}

    <br>
    <br>

    <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
        <tr>
            <td>
                <a href="{{ $url }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                    <span style="color:#ffffff;" class="button-link">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {{ message('common', 'mail.user-email-confirmed.button') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                </a>
            </td>
        </tr>
    </table>

@stop
