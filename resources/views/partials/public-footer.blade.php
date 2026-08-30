<footer class="border-t border-gray-200 py-12">
    <div class="max-w-[75rem] mx-auto px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/navbar_240x120.png') }}" alt="QRTE" class="h-8 w-auto">
                <span class="text-sm text-gray-400">by <a href="https://poordesigner.com" class="text-gray-500 hover:text-gray-900 transition" target="_blank" rel="noopener">POORdesigner.com</a></span>
            </div>
            <div class="flex items-center gap-8 text-sm text-gray-500">
                <a href="{{ route('login') }}" class="hover:text-gray-900 transition">{{ __('Login') }}</a>
                <a href="{{ route('ayuda') }}" class="hover:text-gray-900 transition">{{ __('Ayuda') }}</a>
                <a href="{{ route('planes') }}" class="hover:text-gray-900 transition">{{ __('Planes') }}</a>
                <a href="{{ route('caracteristicas') }}" class="hover:text-gray-900 transition">{{ __('Características') }}</a>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} POORdesigner.com. {{ __('Todos los derechos reservados.') }}
            </p>
        </div>
    </div>
</footer>