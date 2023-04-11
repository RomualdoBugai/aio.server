<div data-app="loading" style="z-index: 1000 !important" data-status="stoped">
    <div class="ui icon small message">
        <i style="font-size: 14px;" class="icon-spin6 animate-spin icon"></i>
        <div class="content" data-content>
            <strong>
                <p class="title"></p>
            </strong>
            <p>
                {{ message('common', 'loading') }}...
            </p>
            <div data-app="progress" class="ui tiny indicating progress" data-value="0" data-total="100" id="example5">
                <div class="bar">
                </div>
            </div>
        </div>
    </div>
</div>
