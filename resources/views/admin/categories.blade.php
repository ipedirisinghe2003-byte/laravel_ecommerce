@extends('layouts.admin')

@section('content')
    <div class="px-6 pt-8 pb-4" id="categories-page">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-[38px] font-bold leading-none text-gray-800">Categories</h1>
                <p class="mt-2 text-[15px] text-gray-500">Manage product categories and partners</p>
            </div>
            <button type="button" id="add-category-button" class="inline-flex items-center gap-2 rounded-lg bg-[#1d84d4] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-[#1776c0]">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Category</span>
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <form id="category-filter-form" class="border-b border-gray-200 px-5 py-5" onsubmit="return false">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full max-w-[420px] flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="category-search" type="search" placeholder="Search category..." class="w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-700 focus:border-[#1d84d4] focus:outline-none focus:ring-2 focus:ring-sky-100">
                    </div>
                    <select id="category-status" class="rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-sm text-gray-600 focus:border-[#1d84d4] focus:outline-none">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="all">All</option>
                    </select>
                    <button type="button" id="clear-category-filters" class="whitespace-nowrap px-1 text-sm text-gray-500 hover:text-[#1d84d4]">&times; Clear</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-[12px] font-semibold uppercase tracking-[0.08em] text-gray-500">
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Logo</th>
                            <th class="px-6 py-4 text-left">Category Name</th>
                            <th class="px-6 py-4 text-left">Slug</th>
                            <th class="px-6 py-4 text-left">Products</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="category-rows" class="divide-y divide-gray-200 bg-white"></tbody>
                </table>
            </div>
            <div id="category-pagination" class="flex items-center justify-end border-t border-gray-200 bg-gray-50 px-6 py-4"></div>
        </div>
    </div>

    <div id="category-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/45 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
        <div class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <h2 id="category-modal-title" class="text-xl font-bold text-gray-800">Add New Category</h2>
                <button type="button" id="close-category-modal" class="text-xl text-gray-400 hover:text-gray-700" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="category-form" class="space-y-4" novalidate>
                <div>
                    <label for="category-name" class="mb-1 block text-sm font-medium text-gray-700">Category Name *</label>
                    <input id="category-name" name="name" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#1d84d4] focus:outline-none focus:ring-2 focus:ring-sky-100">
                    <p data-error="name" class="mt-1 hidden text-xs text-red-500"></p>
                </div>
                <div>
                    <label for="category-slug" class="mb-1 block text-sm font-medium text-gray-700">Slug *</label>
                    <input id="category-slug" name="slug" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#1d84d4] focus:outline-none focus:ring-2 focus:ring-sky-100">
                    <p data-error="slug" class="mt-1 hidden text-xs text-red-500"></p>
                </div>
                <div>
                    <label for="category-logo" class="mb-1 block text-sm font-medium text-gray-700">Logo</label>
                    <input id="category-logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-600">
                    <img id="category-logo-preview" class="mt-3 hidden h-20 w-20 rounded-lg border border-gray-200 object-contain p-2" alt="Category logo preview">
                    <label id="remove-category-logo-wrap" class="mt-3 hidden items-center gap-2 text-sm text-gray-600">
                        <input id="remove-category-logo" name="remove_logo" type="checkbox" value="1" class="rounded border-gray-300 text-[#1d84d4] focus:ring-[#1d84d4]">
                        Remove current logo
                    </label>
                    <p data-error="logo" class="mt-1 hidden text-xs text-red-500"></p>
                </div>
                <div>
                    <label for="category-description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="category-description" name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#1d84d4] focus:outline-none focus:ring-2 focus:ring-sky-100"></textarea>
                    <p data-error="description" class="mt-1 hidden text-xs text-red-500"></p>
                </div>
                <div>
                    <label for="category-status-form" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                    <select id="category-status-form" name="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#1d84d4] focus:outline-none focus:ring-2 focus:ring-sky-100">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <button type="button" id="cancel-category" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50">Cancel</button>
                    <button type="submit" id="save-category" class="rounded-lg bg-[#1d84d4] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#1776c0]">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <div id="category-delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/45 px-4 backdrop-blur-sm" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-500"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Delete Category</h2>
                    <p class="mt-1 text-xs leading-5 text-gray-500">Are you sure you want to delete <span id="delete-category-name" class="font-semibold text-gray-700"></span>?</p>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" id="cancel-category-delete" class="rounded-md border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirm-category-delete" class="rounded-md bg-red-500 px-4 py-2 text-xs font-semibold text-white hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>

    <div id="category-toast" class="fixed right-5 top-5 z-[60] hidden rounded-lg border px-4 py-3 text-sm shadow-lg" role="status"></div>

    <script>
        (() => {
            const apiUrl = '/api/categories';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const rows = document.getElementById('category-rows');
            const pagination = document.getElementById('category-pagination');
            const searchInput = document.getElementById('category-search');
            const statusInput = document.getElementById('category-status');
            const form = document.getElementById('category-form');
            const modal = document.getElementById('category-modal');
            const deleteModal = document.getElementById('category-delete-modal');
            let currentPage = 1;
            let editingId = null;
            let deletingId = null;
            let searchTimer;

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[char]);
            const showToast = (message, error = false) => {
                const toast = document.getElementById('category-toast');
                toast.textContent = message;
                toast.className = `fixed right-5 top-5 z-[60] rounded-lg border px-4 py-3 text-sm shadow-lg ${error ? 'border-red-200 bg-red-50 text-red-700' : 'border-green-200 bg-green-50 text-green-700'}`;
                setTimeout(() => toast.classList.add('hidden'), 3500);
            };
            const open = (element) => { element.classList.remove('hidden'); element.classList.add('flex'); };
            const close = (element) => { element.classList.add('hidden'); element.classList.remove('flex'); };

            function renderRows(categories, loading = false) {
                if (loading) {
                    rows.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading categories...</td></tr>';
                    return;
                }
                if (!categories.length) {
                    rows.innerHTML = '<tr><td colspan="7" class="px-6 py-16 text-center"><i class="fa-solid fa-boxes-stacked mb-4 text-5xl text-gray-300"></i><p class="text-xl font-semibold text-gray-700">No categories found</p><p class="mt-2 text-sm text-gray-500">Add your first category to begin.</p></td></tr>';
                    return;
                }
                rows.innerHTML = categories.map((category) => `
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-6 py-5 text-sm font-medium text-gray-600">${category.id}</td>
                        <td class="px-6 py-5"><div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-gray-100 shadow-sm">${category.logo ? `<img src="${escapeHtml(category.logo)}" alt="${escapeHtml(category.name)}" class="h-8 w-8 object-contain">` : '<i class="fa-solid fa-folder text-gray-400"></i>'}</div></td>
                        <td class="px-6 py-5 font-bold text-gray-800">${escapeHtml(category.name)}</td>
                        <td class="px-6 py-5 text-sm text-gray-600">${escapeHtml(category.slug)}</td>
                        <td class="px-6 py-5"><span class="rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700">${category.product_count}</span></td>
                        <td class="px-6 py-5"><span class="inline-flex min-w-[80px] justify-center rounded-full px-2.5 py-1 text-xs font-semibold ${category.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">${category.status === 'active' ? 'Active' : 'Inactive'}</span></td>
                        <td class="px-6 py-5 text-right"><div class="flex justify-end gap-3"><button type="button" class="text-blue-500 hover:text-blue-700" data-edit="${category.id}" aria-label="Edit category"><i class="fa-solid fa-pen-to-square"></i></button><button type="button" class="text-red-500 hover:text-red-700" data-delete="${category.id}" data-name="${escapeHtml(category.name)}" aria-label="Delete category"><i class="fa-solid fa-trash"></i></button></div></td>
                    </tr>`).join('');
            }

            function renderPagination(meta) {
                if (meta.last_page <= 1) { pagination.innerHTML = ''; return; }
                pagination.innerHTML = `<div class="flex items-center gap-2"><button type="button" data-page="${meta.current_page - 1}" class="rounded border px-3 py-1 text-sm ${meta.current_page === 1 ? 'cursor-not-allowed text-gray-300' : 'text-gray-600 hover:bg-white'}" ${meta.current_page === 1 ? 'disabled' : ''}>Previous</button><span class="text-sm text-gray-500">Page ${meta.current_page} of ${meta.last_page}</span><button type="button" data-page="${meta.current_page + 1}" class="rounded border px-3 py-1 text-sm ${meta.current_page === meta.last_page ? 'cursor-not-allowed text-gray-300' : 'text-gray-600 hover:bg-white'}" ${meta.current_page === meta.last_page ? 'disabled' : ''}>Next</button></div>`;
            }

            async function loadCategories(page = 1) {
                currentPage = page;
                renderRows([], true);
                const params = new URLSearchParams({ search: searchInput.value.trim(), status: statusInput.value, page, limit: 10 });
                try {
                    const response = await fetch(`${apiUrl}?${params}`, { headers: { Accept: 'application/json' } });
                    if (!response.ok) throw new Error('Unable to load categories.');
                    const result = await response.json();
                    renderRows(result.data);
                    renderPagination(result.meta);
                    return result;
                } catch (error) {
                    renderRows([]);
                    showToast(error.message, true);
                    return null;
                }
            }

            function clearErrors() { document.querySelectorAll('[data-error]').forEach((element) => { element.textContent = ''; element.classList.add('hidden'); }); }
            function showErrors(errors) { Object.entries(errors || {}).forEach(([field, messages]) => { const element = document.querySelector(`[data-error="${field}"]`); if (element) { element.textContent = messages[0]; element.classList.remove('hidden'); } }); }
            function resetForm() { form.reset(); document.getElementById('category-status-form').value = 'active'; document.getElementById('category-logo-preview').classList.add('hidden'); document.getElementById('remove-category-logo-wrap').classList.add('hidden'); clearErrors(); }
            async function editCategory(id) {
                const response = await fetch(`${apiUrl}/${id}`, { headers: { Accept: 'application/json' } });
                const result = await response.json();
                const category = result.data;
                editingId = id;
                document.getElementById('category-modal-title').textContent = 'Edit Category';
                document.getElementById('category-name').value = category.name;
                document.getElementById('category-slug').value = category.slug;
                document.getElementById('category-description').value = category.description || '';
                document.getElementById('category-status-form').value = category.status;
                const preview = document.getElementById('category-logo-preview');
                preview.src = category.logo || '';
                preview.classList.toggle('hidden', !category.logo);
                document.getElementById('remove-category-logo').checked = false;
                document.getElementById('remove-category-logo-wrap').classList.toggle('hidden', !category.logo);
                document.getElementById('remove-category-logo-wrap').classList.toggle('flex', Boolean(category.logo));
                clearErrors();
                open(modal);
            }

            document.getElementById('add-category-button').addEventListener('click', () => { editingId = null; resetForm(); document.getElementById('category-modal-title').textContent = 'Add New Category'; open(modal); });
            document.getElementById('close-category-modal').addEventListener('click', () => close(modal));
            document.getElementById('cancel-category').addEventListener('click', () => close(modal));
            document.getElementById('cancel-category-delete').addEventListener('click', () => close(deleteModal));
            searchInput.addEventListener('input', () => { clearTimeout(searchTimer); searchTimer = setTimeout(() => loadCategories(1), 300); });
            statusInput.addEventListener('change', () => loadCategories(1));
            document.getElementById('clear-category-filters').addEventListener('click', () => { searchInput.value = ''; statusInput.value = 'active'; loadCategories(1); });
            document.getElementById('category-logo').addEventListener('change', (event) => { const file = event.target.files[0]; if (!file) return; const reader = new FileReader(); reader.onload = (loadEvent) => { const preview = document.getElementById('category-logo-preview'); preview.src = loadEvent.target.result; preview.classList.remove('hidden'); }; reader.readAsDataURL(file); });
            rows.addEventListener('click', (event) => { const editButton = event.target.closest('[data-edit]'); const deleteButton = event.target.closest('[data-delete]'); if (editButton) editCategory(editButton.dataset.edit).catch(() => showToast('Unable to load category.', true)); if (deleteButton) { deletingId = deleteButton.dataset.delete; document.getElementById('delete-category-name').textContent = deleteButton.dataset.name; open(deleteModal); } });
            pagination.addEventListener('click', (event) => { const button = event.target.closest('[data-page]'); if (button) loadCategories(button.dataset.page); });
            document.getElementById('confirm-category-delete').addEventListener('click', async () => { try { const response = await fetch(`${apiUrl}/${deletingId}`, { method: 'DELETE', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken } }); const result = await response.json(); if (!response.ok) throw new Error(result.message || 'Unable to delete category.'); close(deleteModal); showToast(result.message); const pageResult = await loadCategories(currentPage); if (pageResult && !pageResult.data.length && currentPage > 1) loadCategories(currentPage - 1); } catch (error) { close(deleteModal); showToast(error.message, true); } });
            form.addEventListener('submit', async (event) => { event.preventDefault(); clearErrors(); const name = document.getElementById('category-name').value.trim(); if (!name) { showErrors({ name: ['Category name is required.'] }); return; } const data = new FormData(form); if (editingId) data.append('_method', 'PUT'); const button = document.getElementById('save-category'); button.disabled = true; button.textContent = 'Saving...'; try { const response = await fetch(editingId ? `${apiUrl}/${editingId}` : apiUrl, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: data }); const result = await response.json(); if (!response.ok) { if (response.status === 422) showErrors(result.errors); throw new Error(result.message || 'Unable to save category.'); } close(modal); showToast(result.message); loadCategories(currentPage); } catch (error) { if (!error.message.includes('validation')) showToast(error.message, true); } finally { button.disabled = false; button.textContent = 'Save Category'; } });
            loadCategories();
        })();
    </script>
@endsection
