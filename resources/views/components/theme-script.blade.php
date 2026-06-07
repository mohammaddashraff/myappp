<script>
    (() => {
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldUseDark = storedTheme ? storedTheme === 'dark' : prefersDark;

        document.documentElement.classList.toggle('dark', shouldUseDark);
        document.documentElement.style.colorScheme = shouldUseDark ? 'dark' : 'light';
    })();
</script>
