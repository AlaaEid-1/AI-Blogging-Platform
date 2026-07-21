<x-mail::message>
    <div>
        @foreach ($posts as $post)
        <article>
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->excerpt }}</p>
            <p><a href="">Read More</a></p>
        </article>
    </div>
</x-mail::message>