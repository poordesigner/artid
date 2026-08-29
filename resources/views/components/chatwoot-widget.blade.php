@php
    $baseUrl = rtrim((string) config('chatwoot.base_url'), '/');
    $token = config('chatwoot.website_token');
    $user = Auth::user();
@endphp

@if ($baseUrl && $token)
    <script>
        @if ($user)
        window.chatwootSettings = {
            user: {
                identifier: @json((string) $user->id),
                name: @json($user->name),
                email: @json($user->email),
            },
        };
        @endif
        (function (d, t) {
            var BASE_URL = @json($baseUrl);
            var g = d.createElement(t);
            var s = d.getElementsByTagName(t)[0];
            g.src = BASE_URL + '/packs/js/sdk.js';
            g.defer = true;
            g.async = true;
            s.parentNode.insertBefore(g, s);
            g.onload = function () {
                window.chatwootSDK.run({
                    websiteToken: @json($token),
                    baseUrl: BASE_URL
                });
            };
        })(document, 'script');
    </script>
@endif