document.getElementById('favoriteForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch('/favorites/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    });

        console.log('Response status:', response.status);

    const data = await response.json();

    if (data.success) {
        showToast(data.message, 'success');
        document.getElementById('favoriteBtn').classList.add('favorited');
    } else {
        showToast(data.message, 'error');
    }
});