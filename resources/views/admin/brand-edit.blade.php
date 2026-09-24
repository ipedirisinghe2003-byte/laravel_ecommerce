<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Brand</h1>
        <a href="{{ route('admin.brands') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Brands</span>
        </a>
    </div>

    <div class="max-w-5xl">
        <form action="{{ route('admin.brand-update', $brand) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" placeholder="Brand 3 Update" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Brand Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" placeholder="brand-3-update" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                    @error('slug')
                        <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                    <div class="flex items-center justify-center w-full h-40 border border-gray-200 rounded-xl bg-gray-50 overflow-hidden">
                        @if($brand->image)
                            <img src="{{ asset($brand->image) }}" alt="Current brand logo" class="max-h-28 object-contain" />
                        @else
                            <span class="text-sm text-gray-400">No logo uploaded</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Change Brand Logo</label>
                    <div class="relative flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                        <div id="upload-content" class="flex flex-col items-center justify-center text-center {{ $brand->image ? 'hidden' : '' }}">
                            <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Upload new logo</p>
                        </div>

                        <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-4 bg-white" src="" alt="Brand preview" />
                        <input id="brand-image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" />
                    </div>
                    @error('image')
                        <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="status" name="status" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('status', $brand->status) ? 'checked' : '' }}>
                <label for="status" class="text-sm text-gray-700">Set as Active Brand</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.brands') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#1d84d4] text-white rounded-lg text-sm font-medium shadow-sm hover:bg-[#1775c0] transition">Save Brand</button>
            </div>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('brand-image');
        const preview = document.getElementById('image-preview');
        const uploadContent = document.getElementById('upload-content');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                    uploadContent.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</x-admin-layout>
