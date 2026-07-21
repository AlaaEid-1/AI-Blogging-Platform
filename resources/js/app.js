//

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

Echo.private(`App.Models.User.${USER_ID}`)
    .notification(function (data) {
        alert(data.body)
    });

Echo.private(`posts.${USER_ID}`)
    .listen('.post-viewed', function () {
        alert('Post viewed');
    })
