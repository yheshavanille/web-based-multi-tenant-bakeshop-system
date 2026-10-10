import './bootstrap';
import 'preline';
import 'tailwindcss';

//  Apply saved theme instantly (before render) to avoid flash
(function () {
    const theme = localStorage.getItem('hs_theme') || 'light';
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

const initializePreline = () => {
    const prelineComponents = [
        'HSOverlay',
        'HSDropdown',
        'HSSelect',
        'HSTheme',
        'HSAccordion',
        'HSCollapse',
    ];

    prelineComponents.forEach((component) => {
        const instance = window[component];

        if (instance && typeof instance.autoInit === 'function') {
            instance.autoInit();
        }
    });
};

if (document.readyState === 'complete') {
    initializePreline();
} else {
    window.addEventListener('load', initializePreline);
}

window.addEventListener('livewire:load', initializePreline);
window.addEventListener('livewire:afterDomUpdate', initializePreline);
window.addEventListener('livewire:update', initializePreline);

//  Re-apply theme after Livewire navigation (navigate)
document.addEventListener('livewire:navigated', () => {
    const theme = localStorage.getItem('hs_theme') || 'light';
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});
