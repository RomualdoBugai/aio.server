<div class="ui basic segment">
    <div class="ui vertical menu fluid">

@foreach($data as $enterprise)
    <a class="item" href="{{ route('enterprise.show', ['id' => $enterprise['enterprise']['id']]) }}" title="{{ message('enterprise', 'enterprise.show') }}">

        <div class="ui grid">

            <div class="two wide column mobile">
                <span style="width: 30px; height: 30px; line-height: 30px; text-align: center; background: #222; color: #fff; display: inline-block; border-radius: 50% !important">
                    {{ getCapitalLetters($enterprise['enterprise']['name']) }}
                </span>
            </div>

            <div class="fourteen wide column mobile">
                <div style="font-size: 18px; font-weight: bold; line-height: 18px; margin-bottom: 5px;">
                    {{ ownName($enterprise['enterprise']['name']) }}
                </div>
                <small>
                    {{ $enterprise['enterprise']['national_code'] }}
                </small>
            </div>

        </div>

    </a>

@endforeach
</div>
</div>
