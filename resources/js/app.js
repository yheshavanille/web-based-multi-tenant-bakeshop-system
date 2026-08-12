import './bootstrap';
import 'preline';
import 'tailwindcss';

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
