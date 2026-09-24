@extends('layouts.admin')

@section('content')
    <div class="px-6 pt-8 pb-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-[38px] font-bold text-gray-800 leading-none">Brands</h1>
                <p class="mt-2 text-[15px] text-gray-500">Manage product brands and partners</p>
            </div>
            <a href="{{ route('admin.brand-add') }}" class="inline-flex items-center gap-2 bg-[#1d84d4] hover:bg-[#1776c0] text-white px-5 py-3 rounded-lg text-sm font-medium shadow-sm transition">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Brand</span>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <form method="GET" action="{{ route('admin.brands') }}" class="px-5 py-5 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="relative flex-1 max-w-[420px]">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search brand..." class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-gray-50 focus:outline-none focus:border-[#1d84d4] focus:ring-2 focus:ring-sky-100">
                    </div>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-lg bg-white text-sm text-gray-600 px-4 py-3 pr-10 focus:outline-none focus:border-[#1d84d4]">
                            <option value="all" @selected(request('status', 'all') === 'all')>All Status</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        </select>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa-solid fa-chevron-down text-xs"></i></span>
                    </div>
                    <button type="submit" class="rounded-lg bg-[#1d84d4] px-4 py-3 text-sm font-medium text-white hover:bg-[#1776c0]">Search</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-[12px] uppercase tracking-[0.08em] font-semibold">
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Logo</th>
                            <th class="px-6 py-4 text-left">Brand Name</th>
                            <th class="px-6 py-4 text-left">Slug</th>
                            <th class="px-6 py-4 text-left">Products</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($brands as $brand)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 text-sm font-medium text-gray-600">{{ $brand->id }}</td>
                                <td class="px-6 py-5">
                                    <div class="w-12 h-12 rounded-lg border border-gray-200 bg-gray-100 flex items-center justify-center overflow-hidden shadow-sm">
                                        @if($brand->image)
                                            <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" class="w-8 h-8 object-contain">
                                        @else
                                            <div class="w-8 h-8 rounded-md bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">B</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 font-bold text-gray-800">{{ $brand->name }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $brand->slug }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">0</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center justify-center min-w-[80px] rounded-full px-2.5 py-1 text-xs font-semibold {{ $brand->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $brand->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.brand-edit', $brand) }}" class="text-blue-500 hover:text-blue-700 text-lg" aria-label="Edit brand"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form id="delete-brand-{{ $brand->id }}" action="{{ route('admin.brand-destroy', $brand) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="w-8 h-8 rounded-full hover:bg-red-50 text-red-500 transition flex items-center justify-center" onclick="openDeleteModal({{ $brand->id }}, @js($brand->name))" aria-label="Delete brand"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="text-gray-500">
                                        <i class="fa-solid fa-boxes-stacked text-5xl mb-4 text-gray-300"></i>
                                        <p class="text-xl font-semibold text-gray-700">No brands found</p>
                                        <p class="mt-2 text-sm">Add your first brand to begin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($brands->count())
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $brands->links() }}</div>
            @endif
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/45 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-500"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <h2 id="delete-modal-title" class="text-sm font-bold text-gray-800">Delete Brand</h2>
                    <p class="mt-1 text-xs leading-5 text-gray-500">Are you sure you want to delete <span id="delete-brand-name" class="font-semibold text-gray-700"></span>? All of its data will be permanently removed. This action cannot be undone.</p>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" id="cancel-delete" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirm-delete" class="rounded-md bg-red-500 px-4 py-2 text-xs font-semibold text-white hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('delete-modal');
        const deleteBrandName = document.getElementById('delete-brand-name');
        let selectedBrandId = null;

        function openDeleteModal(brandId, brandName) {
            selectedBrandId = brandId;
            deleteBrandName.textContent = brandName;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        }

        function closeDeleteModal() {
            selectedBrandId = null;
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        }

        document.getElementById('cancel-delete').addEventListener('click', closeDeleteModal);
        document.getElementById('confirm-delete').addEventListener('click', function () {
            if (selectedBrandId) document.getElementById('delete-brand-' + selectedBrandId).submit();
        });
        deleteModal.addEventListener('click', function (event) {
            if (event.target === deleteModal) closeDeleteModal();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !deleteModal.classList.contains('hidden')) closeDeleteModal();
        });
    </script>
@endsection
