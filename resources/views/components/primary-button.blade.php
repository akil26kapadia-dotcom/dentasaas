<button
    {{ $attributes->merge(['type' => 'submit', 'style' => 'background-color:#465fff;', 'class' => 'inline-flex items-center px-4 py-2.5 rounded-lg font-medium text-sm text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
