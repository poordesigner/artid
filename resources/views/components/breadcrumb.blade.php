@props(['crumbs' => []])

<nav class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
    @foreach ($crumbs as $crumb)
        @if (isset($crumb['route']) && isset($crumb['label']))
            <a href="{{ $crumb['route'] }}" class="hover:text-gray-700">{{ $crumb['label'] }}</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif
    @endforeach
    <span class="text-gray-900 font-medium">{{ $current }}</span>
</nav>