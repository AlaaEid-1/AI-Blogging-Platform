<!-- Who to Follow -->
<div class="space-y-6">
    <h3 class="font-ui-label text-ui-label uppercase tracking-widest text-secondary font-bold">{{ $title }}</h3>
    <div class="space-y-4">
        @foreach ($authors as $author)
        <form method="post"
            action="{{ route(($author->followers_exists ? 'users.unfollow' : 'users.follow'), $author->id) }}">
            @csrf
            @if ($author->followers_exists)
            @method('delete')
            @endif
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img alt="User" class="w-10 h-10 rounded-full object-cover" src="{{ $author->avatar_url }}" />
                    <div>
                        <p class="font-ui-label text-ui-label font-bold text-on-surface">{{ $author->name }}</p>
                        <p class="font-metadata text-metadata text-secondary">{{ $author->username }}</p>
                    </div>
                </div>
                <button type="submit"
                    class="px-3 py-1 border border-on-surface text-on-surface rounded-full font-metadata text-metadata font-bold hover:bg-on-surface hover:text-white transition-all">{{ $author->followers_exists ? 'Unfollow' : 'Follow' }}</button>
            </div>
        </form>
        @endforeach
    </div>
    <a class="block font-ui-label text-ui-label text-primary font-bold hover:underline" href="#">View all
        recommendations</a>
</div>