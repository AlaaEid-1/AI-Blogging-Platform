@include('dashboard.posts._form', [
    'post' => $post,
    'action' => route('dashboard.posts.update', $post->id),
    'method' => 'PUT',
    'title' => 'Edit Post',
])