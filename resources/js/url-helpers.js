// Shared URL helper functions

export function copyToClipboard(inputId = 'shortUrl') {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.select();
    navigator.clipboard.writeText(input.value);
    
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-600');
    button.classList.remove('bg-indigo-600');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-indigo-600');
    }, 2000);
}

export function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="text-green-500 text-xs font-medium">Copied!</span>';
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 1500);
    });
}

// Make functions globally available
window.copyToClipboard = copyToClipboard;
window.copyUrl = copyUrl;
