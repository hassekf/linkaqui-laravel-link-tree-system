document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.link-card').forEach(card => {
        card.addEventListener('click', async (e) => {
            e.preventDefault();
            const linkId = card.dataset.linkId;
            const url = card.dataset.url;

            // Fire and forget — don't wait for tracking
            try {
                fetch(`/click/${linkId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
            } catch (e) {
                // Tracking failure shouldn't block navigation
            }

            // Navigate immediately
            window.location.href = url;
        });
    });
});
