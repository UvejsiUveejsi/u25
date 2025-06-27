// Theme toggle with smooth transition
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('themeToggle');
    const root = document.documentElement;

    const setTheme = (dark) => {
        if (dark) {
            root.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            root.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    // Initialize theme
    setTheme(localStorage.getItem('theme') === 'dark');

    toggle?.addEventListener('click', () => {
        setTheme(!root.classList.contains('dark'));
    });
});