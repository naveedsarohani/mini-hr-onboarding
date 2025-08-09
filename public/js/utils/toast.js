function showToast({ type = 'info', message = '' }) {
    const colors = {
        success: {
            bg: 'bg-green-100',
            border: 'border-green-500',
            text: 'text-green-700',
        },
        error: {
            bg: 'bg-red-100',
            border: 'border-red-500',
            text: 'text-red-700',
        },
    };

    const theme = colors[type] || colors.info;

    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-3';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `
        flex items-center gap-3 border-l-4 p-4 py-2 rounded-l-lg shadow-lg 
        transform transition-all duration-300 opacity-0 translate-x-5 
        ${theme.bg} ${theme.border} ${theme.text}
    `;

    toast.innerHTML = `
        <p>${message}</p>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-5');
        toast.classList.add('opacity-100', 'translate-x-0');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-x-0');
        toast.classList.add('opacity-0', 'translate-x-5');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

export default showToast;
