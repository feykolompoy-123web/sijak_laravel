@if (session('ok') || session('galat') || session('status'))
    <div id="toasts" aria-live="polite">
        @if (session('ok'))<div class="toast ok">{{ session('ok') }}</div>@endif
        @if (session('status'))<div class="toast ok">{{ session('status') }}</div>@endif
        @if (session('galat'))<div class="toast err">{{ session('galat') }}</div>@endif
    </div>
@endif
