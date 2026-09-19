<script>
    (function () {
        function applyChartTheme() {
            if (!window.Chart) { return; }
            var dark = document.documentElement.classList.contains('dark');
            Chart.defaults.color = dark ? '#98a2b3' : '#667085';
            Chart.defaults.borderColor = dark ? 'rgba(152, 162, 179, 0.16)' : 'rgba(16, 24, 40, 0.08)';
            Object.values(Chart.instances || {}).forEach(function (chart) { chart.update(); });
        }
        applyChartTheme();
        window.addEventListener('themechange', applyChartTheme);
    })();
</script>
