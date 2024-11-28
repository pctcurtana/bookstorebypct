function showAlert(message, type) {
    const alertDiv = document.getElementById('alertMessage');
    if (!alertDiv) {
        console.error('Không tìm thấy element với id "alertMessage"');
        return;
    }

    const alertHTML = `
        <div class="custom-alert alert-${type}">
            <span class="alert-message">${message}</span>
            <button type="button" class="btn-close" aria-label="Close"></button>
        </div>
    `;
    
    alertDiv.innerHTML = alertHTML;
    
    // Thêm event listener cho nút close
    const closeButton = alertDiv.querySelector('.btn-close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            const alert = alertDiv.querySelector('.custom-alert');
            if (alert) {
                alert.classList.remove('show-alert');
                alert.classList.add('hide-alert');
                setTimeout(() => alert.remove(), 400);
            }
        });
    }

    // Hiển thị alert với animation
    setTimeout(() => {
        const alert = alertDiv.querySelector('.custom-alert');
        if (alert) {
            alert.classList.add('show-alert');
        }
    }, 100);

    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        const alert = alertDiv.querySelector('.custom-alert');
        if (alert) {
            alert.classList.remove('show-alert');
            alert.classList.add('hide-alert');
            setTimeout(() => alert.remove(), 400);
        }
    }, 3000);
}