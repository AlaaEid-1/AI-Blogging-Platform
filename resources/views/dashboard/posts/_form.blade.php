<x-layout.app :title="$title ?? 'Editor'" :has-sidebar="true" :has-right-sidebar="false">
    <x-slot:head-scripts>
        <script src="https://cdn.tiny.cloud/1/3zeb83osgkdu6u7uj9aoyv91ra7ng7vkvtcv5xzt5vclykvd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    </x-slot:head-scripts>

    <form action="{{ $action ?? route('dashboard.posts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="relative">
        @csrf
        @method($method ?? 'POST')

        <div class="pt-8 pb-32 flex flex-col xl:flex-row max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 gap-10 items-start">
            
            <!-- Editor Canvas -->
            <div class="flex-1 w-full max-w-3xl mx-auto xl:mx-0 order-2 xl:order-1">
                <div class="bg-surface rounded-3xl border border-border/40 shadow-xl shadow-surface-secondary/20 p-8 sm:p-12 min-h-[75vh]">
                    @if ($errors->any())
                        <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium space-y-1">
                            <div class="flex items-center gap-2 text-red-900 font-bold mb-2">
                                <span class="material-symbols-outlined text-[18px]">error</span>
                                Please fix the following errors:
                            </div>
                            <ul class="list-disc pl-6 space-y-1">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- AI Assistant Box -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-primary/10 to-accent/10 border border-primary/20 rounded-2xl"
                         x-data="{ 
                             aiGenerating: false, 
                             aiPrompt: '', 
                             aiError: '',
                             async generate() {
                                 if (!this.aiPrompt.trim()) return;
                                 this.aiGenerating = true;
                                 this.aiError = '';
                                 try {
                                     let res = await fetch('{{ route('dashboard.ai.generate') }}', {
                                         method: 'POST',
                                         headers: {
                                             'Content-Type': 'application/json',
                                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                         },
                                         body: JSON.stringify({ prompt: this.aiPrompt })
                                     });
                                     let data = await res.json();
                                     if (!res.ok) {
                                         this.aiError = data.error || 'Something went wrong';
                                     } else {
                                         let titleEl = document.querySelector('textarea[name=title]');
                                         titleEl.value = data.title;
                                         titleEl.style.height = '';
                                         titleEl.style.height = titleEl.scrollHeight + 'px';
                                         
                                         document.querySelector('input[name=tags]').value = (data.tags || []).join(', ');
                                         document.querySelector('input[name=\'meta[title]\']').value = data.title;
                                         document.querySelector('textarea[name=\'meta[description]\']').value = data.excerpt;
                                         
                                         if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                                             tinymce.get('content').setContent(data.content);
                                         } else {
                                             document.querySelector('textarea[name=content]').value = data.content;
                                         }
                                     }
                                 } catch (e) {
                                     this.aiError = 'Network error or timeout. Please try again.';
                                 } finally {
                                     this.aiGenerating = false;
                                 }
                             }
                         }">
                        <h3 class="text-sm font-bold text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                            Generate With AI
                        </h3>
                        
                        <div x-show="aiError" class="mb-3 p-3 bg-red-50 text-red-700 rounded-xl text-sm border border-red-200" x-text="aiError" style="display: none;"></div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="text" x-model="aiPrompt" placeholder="What should the article be about?" 
                                   class="flex-1 bg-white/60 border border-primary/20 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-text-tertiary shadow-inner"
                                   @keydown.enter.prevent="generate()">
                            
                            <button type="button" @click="generate()" x-bind:disabled="aiGenerating || !aiPrompt.trim()"
                                    class="bg-gradient-to-r from-primary to-accent text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 whitespace-nowrap">
                                <span x-show="!aiGenerating">Generate Article</span>
                                <span x-show="aiGenerating" class="flex items-center gap-2" style="display: none;">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Generating...
                                </span>
                            </button>
                        </div>
                    </div>                    <!-- Title Field -->
                    <div class="mb-8">
                        <textarea name="title" rows="1"
                            class="w-full bg-transparent border-none focus:ring-0 text-4xl sm:text-5xl font-extrabold text-text-primary resize-none placeholder:text-text-tertiary p-0 overflow-hidden outline-none tracking-tight leading-tight transition-all duration-300 focus:placeholder-transparent"
                            placeholder="Untitled article..."
                            oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'>{{ old('title', $post->title) }}</textarea>
                        @error('title')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TinyMCE Editor -->
                    <div class="prose prose-purple max-w-none w-full border-t border-border/40 pt-8 mt-2 relative">
                        <textarea name="content" id="content"
                            class="w-full bg-transparent border-none focus:ring-0 text-lg text-text-secondary leading-relaxed placeholder:text-text-tertiary min-h-[500px]"
                            placeholder="Start writing... Use / for commands">{{ old('content', $post->content) }}</textarea>
                    </div>
                    @error('content')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">error</span> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar: Publishing Settings -->
            <aside class="w-full xl:w-[340px] shrink-0 order-1 xl:order-2 space-y-6 xl:sticky xl:top-24">
                
                <!-- Action Bar -->
                <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-xl rounded-3xl p-5 flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-4 border-b border-border/50">
                        <span class="text-sm font-bold text-text-secondary uppercase tracking-wider">{{ isset($post->id) ? 'Edit Post' : 'New Draft' }}</span>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard.posts.index') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 w-full text-sm font-bold text-text-secondary bg-surface border border-border/60 hover:bg-surface-hover hover:text-text-primary rounded-xl transition-colors active:scale-95">
                            Cancel
                        </a>
                        <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-primary to-accent hover:shadow-[0_8px_25px_-8px_rgba(99,102,241,0.5)] transition-all duration-300 hover:-translate-y-0.5 active:scale-95 overflow-hidden" x-bind:disabled="submitting">
                            <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-700 ease-out skew-x-12"></div>
                            <span x-show="!submitting" class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">{{ isset($post->id) ? 'update' : 'publish' }}</span>
                                {{ isset($post->id) ? 'Update' : 'Publish' }}
                            </span>
                            <span x-show="submitting" class="flex items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-xl rounded-3xl p-5">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">image</span>
                        Cover Image
                    </h3>
                    
                    <div class="relative rounded-2xl overflow-hidden mb-2 border-2 border-dashed border-border/60 bg-surface-hover group transition-all hover:border-primary/50">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/' . $post?->cover_image) }}" alt="Cover" class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-sm font-semibold bg-black/40 px-4 py-2 rounded-xl backdrop-blur-md flex items-center gap-2 shadow-lg">
                                    <span class="material-symbols-outlined text-[18px]">cameraswitch</span>
                                    Change Cover
                                </span>
                            </div>
                        @else
                            <div class="aspect-video w-full flex flex-col items-center justify-center gap-3 text-text-tertiary group-hover:text-primary transition-colors cursor-pointer bg-surface/50">
                                <div class="w-12 h-12 rounded-full bg-surface shadow-sm border border-border/50 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[24px]">add_photo_alternate</span>
                                </div>
                                <span class="text-xs font-semibold">Click or drag to upload</span>
                            </div>
                        @endif
                        <input type="file" name="cover" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" title="Upload cover" />
                    </div>
                    @error('cover')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags Input -->
                <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-xl rounded-3xl p-5">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">sell</span>
                        Tags
                    </h3>
                    <div class="relative group">
                        <input type="text" name="tags" value="{{ old('tags', isset($post->tags) ? $post->tags->pluck('name')->join(', ') : '') }}"
                            class="w-full px-4 py-3 text-sm bg-surface border border-border/60 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-text-tertiary" placeholder="tech, ai, tutorial">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-text-tertiary">
                            <span class="material-symbols-outlined text-[18px]">keyboard_return</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-text-tertiary mt-2">Separate tags with commas</p>
                    @error('tags')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Settings Accordion -->
                <div class="glass backdrop-blur-xl bg-white/60 border border-white/40 shadow-xl rounded-3xl divide-y divide-border/60 overflow-hidden">
                    
                    <!-- Metadata -->
                    <div x-data="{ open: false }" class="p-5">
                        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-xs font-bold text-text-secondary uppercase tracking-wider focus:outline-none group">
                            <span class="flex items-center gap-2 group-hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[16px]">public</span>
                                SEO & Meta
                            </span>
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:text-primary" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" class="mt-5 space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div>
                                <label class="block text-[11px] font-bold text-text-secondary mb-1.5 uppercase tracking-wider">SEO Title</label>
                                <input type="text" name="meta[title]" value="{{ old('meta.title', $post->meta['title'] ?? '') }}"
                                    class="w-full px-3 py-2 text-sm bg-surface border border-border/60 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-text-tertiary shadow-inner">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-text-secondary mb-1.5 uppercase tracking-wider">Meta Description</label>
                                <textarea name="meta[description]" rows="3"
                                    class="w-full px-3 py-2 text-sm bg-surface border border-border/60 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-text-tertiary resize-none shadow-inner">{{ old('meta.description', $post->meta['description'] ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-text-secondary mb-1.5 uppercase tracking-wider">Slug / URL</label>
                                <div class="flex items-center border border-border/60 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all bg-surface shadow-inner">
                                    <span class="px-3 text-xs text-text-tertiary bg-surface-secondary border-r border-border/60 font-medium">/posts/</span>
                                    <input type="text" name="meta[url]" value="{{ old('meta.url', $post->meta['url'] ?? '') }}"
                                        class="w-full px-2 py-2 text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-text-tertiary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility & Date -->
                    <div class="p-5 bg-surface-secondary/30 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">toggle_on</span>
                                Status
                            </label>
                            <select name="status" class="w-full bg-surface border border-border/60 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-text-primary shadow-inner">
                                <option value="draft" {{ old('status', $post->status?->value ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status?->value ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $post->status?->value ?? 'draft') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                                Publish Date
                            </label>
                            <input name="published_at" value="{{ old('published_at', $post->published_at) }}" type="datetime-local"
                                class="w-full bg-surface border border-border/60 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-text-primary shadow-inner" />
                            <p class="text-[11px] text-text-tertiary mt-2">Leave blank to publish immediately</p>
                            @error('published_at')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>

    <script>
      tinymce.init({
        selector: '#content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        menubar: false,
        branding: false,
        skin: "oxide",
        content_css: "default",
        setup: function (editor) {
            editor.on('init', function () {
                editor.getContainer().style.border = 'none';
                editor.getContainer().style.boxShadow = 'none';
                editor.getContainer().style.borderRadius = '12px';
                editor.getContainer().style.backgroundColor = 'transparent';
                
                const iframe = editor.getContainer().querySelector('iframe');
                if (iframe) {
                    iframe.style.backgroundColor = 'transparent';
                }
            });
        }
      });
    </script>
</x-layout.app>
