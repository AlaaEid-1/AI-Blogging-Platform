<x-layout.app
    :title="'Edit Profile — ' . config('app.name')"
    :has-sidebar="true"
    :has-right-sidebar="false"
>
    <div class="max-w-2xl mx-auto pb-24">

        {{-- Header & Back button --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <a
                    href="{{ route('profile.show', $user->username) }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-text-tertiary hover:text-primary transition-colors mb-2"
                >
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Public Profile
                </a>
                <h1 class="text-3xl font-extrabold text-text-primary tracking-tight">
                    Edit Profile
                </h1>
                <p class="text-sm text-text-secondary mt-1">
                    Update your public profile details and avatar.
                </p>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 text-sm font-medium space-y-1">
                <div class="font-bold flex items-center gap-1.5 mb-1">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    Please fix the following issues:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Profile Form --}}
        <form
            action="{{ route('profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-surface rounded-3xl border border-border/50 shadow-soft p-6 sm:p-8 space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div>
                <label class="block text-sm font-bold text-text-primary mb-3">
                    Profile Photo
                </label>
                <div class="flex items-center gap-5" x-data="{ preview: null }">
                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden border-2 border-border bg-surface-secondary shrink-0">
                        <template x-if="preview">
                            <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!preview">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        </template>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label
                            for="avatar-upload"
                            class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-hover border border-border/60 text-text-primary text-xs font-bold hover:bg-border/30 transition-all duration-200"
                        >
                            <span class="material-symbols-outlined text-[18px]">cloud_upload</span>
                            Choose New Image
                        </label>
                        <input
                            id="avatar-upload"
                            type="file"
                            name="avatar"
                            accept="image/png,image/jpeg,image/webp"
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (file) { preview = URL.createObjectURL(file); }
                            "
                        >
                        <p class="text-[11px] text-text-tertiary">
                            JPG, PNG or WEBP. Max size 2MB.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Full Name --}}
            <div>
                <label for="name" class="block text-sm font-bold text-text-primary mb-2">
                    Full Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm text-text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                >
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-bold text-text-primary mb-2">
                    Username Handle
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-tertiary text-sm font-bold">@</span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        required
                        class="w-full bg-background border border-border rounded-xl py-3 pl-9 pr-4 text-sm text-text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    >
                </div>
               
            </div>

            {{-- Bio --}}
            <div>
                <label for="bio" class="block text-sm font-bold text-text-primary mb-2">
                    Bio
                </label>
                <textarea
                    id="bio"
                    name="bio"
                    rows="4"
                    maxlength="1000"
                    placeholder="Tell the community about yourself, your skills, or what you write about..."
                    class="w-full bg-background border border-border rounded-xl p-4 text-sm text-text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all leading-relaxed"
                >{{ old('bio', $user->bio) }}</textarea>
                <p class="text-[11px] text-text-tertiary mt-1.5 text-right">
                    Maximum 1000 characters
                </p>
            </div>

            {{-- Submit Row --}}
            <div class="pt-4 border-t border-border/50 flex items-center justify-end gap-3">
                <a
                    href="{{ route('profile.show', $user->username) }}"
                    class="px-5 py-2.5 rounded-xl border border-border/60 text-text-secondary text-sm font-semibold hover:bg-surface-hover transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark transition-all shadow-md shadow-primary/20 active:scale-95"
                >
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</x-layout.app>
