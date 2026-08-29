<div class="inline-flex rounded-md border border-gray-200 overflow-hidden text-sm">
    <a href="{{ route('locale', 'es') }}" class="px-3 py-1.5 {{ app()->getLocale() === 'es' ? 'bg-brand text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} transition">
        ES
    </a>
    <a href="{{ route('locale', 'en') }}" class="px-3 py-1.5 {{ app()->getLocale() === 'en' ? 'bg-brand text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} transition">
        EN
    </a>
</div>
