<script>
    (function () {
        var root = document.documentElement;

        function apply(dark) {
            root.classList.toggle('dark', dark);
            root.style.colorScheme = dark ? 'dark' : 'light';
        }

        function stored() {
            try { return localStorage.getItem('theme'); } catch (e) { return null; }
        }

        var saved = stored();
        apply(saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches);

        // Follow the OS until the visitor picks a theme explicitly.
        try {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                if (!stored()) { apply(e.matches); window.dispatchEvent(new Event('themechange')); }
            });
        } catch (e) {}

        window.addEventListener('storage', function (e) {
            if (e.key === 'theme') { apply(e.newValue === 'dark'); window.dispatchEvent(new Event('themechange')); }
        });

        window.__toggleTheme = function () {
            var dark = !root.classList.contains('dark');
            apply(dark);
            try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
            window.dispatchEvent(new Event('themechange'));
        };
    })();
</script>
