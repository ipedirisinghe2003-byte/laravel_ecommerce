<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Add New Brand</h1>
            <a href="{{ route('admin.brands') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Brands</span>
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            <form action="{{ route('admin.brand-store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Samsung" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Brand Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="samsung" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo *</label>
                    <div class="relative flex items-center justify-center w-full h-44">
                        <label for="brand-image" class="relative flex flex-col items-center justify-center w-full h-full border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                            <div id="upload-content" class="z-10 flex flex-col items-center justify-center text-center">
                                <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Upload brand logo (PNG/JPG)</p>
                            </div>

                            <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 z-20 bg-white" src="" alt="Brand preview" />

                            <input id="brand-image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" />
                        </label>

                        <button type="button" id="remove-logo-btn" class="hidden absolute top-2 right-2 z-30 flex items-center justify-center h-8 w-8 rounded-full border border-gray-200 bg-white text-red-500 shadow-sm hover:bg-red-500 hover:text-white transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @error('image')
                        <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="status" name="status" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                    <label for="status" class="text-sm text-gray-700">Set as Active Brand</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.brands') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#1d84d4] text-white rounded-lg text-sm font-medium shadow-sm hover:bg-[#1775c0] transition">Save Brand</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const fileInput = document.getElementById('brand-image');
        const preview = document.getElementById('image-preview');
        const uploadContent = document.getElementById('upload-content');
        const removeBtn = document.getElementById('remove-logo-btn');

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.classList.remove('hidden');
                uploadContent.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        removeBtn.addEventListener('click', function () {
            fileInput.value = '';
            preview.src = '';
            preview.classList.add('hidden');
            uploadContent.classList.remove('hidden');
            removeBtn.classList.add('hidden');
        });
    </script>
</x-admin-layout>
