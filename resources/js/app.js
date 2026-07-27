import './echo';

/**
 * Real-time notifications and post-view events via Laravel Echo / Reverb.
 *
 * Both blocks are guarded so the module does not throw when:
 *  - The user is not authenticated (USER_ID is empty / "")
 *  - Reverb / Pusher credentials are not configured (dev or production without WS)
 */

if (typeof window.Echo !== 'undefined' && typeof USER_ID !== 'undefined' && USER_ID !== '') {
    Echo.private(`App.Models.User.${USER_ID}`)
        .notification(function (data) {
            if (data && data.body) {
                console.info('[Echo] Notification:', data.body);
            }
        });

    Echo.private(`posts.${USER_ID}`)
        .listen('.post-viewed', function () {
            console.info('[Echo] Post viewed event received.');
        });
}
