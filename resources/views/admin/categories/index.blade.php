@extends('layouts.app')

@section('title', 'Manage Blog Categories – Admin CMS Portal')
@section('meta_description', 'Admin dashboard category management module for CypherLabs engineering blog.')

@section('content')

<style>
    .saas-category-wrapper {
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(0, 212, 255, 0.18);
        --border-glass-hover: rgba(0, 212, 255, 0.4);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;

        background: var(--bg-dark);
        min-height: calc(100vh - 120px);
        padding: 2.25rem 1rem 4rem;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    .cat-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .cat-ambient-glow {
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.08) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .cat-container {
        max-width: 1120px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .cat-header-banner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(10, 15, 30, 0.95) 100%);
        border: 1px solid var(--border-glass);
        border-radius: 18px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
    }

    .cat-header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #00d4ff, #a855f7, #06f7e3);
    }

    .cat-header-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cat-header-sub {
        color: var(--text-muted);
        font-size: 0.88rem;
        margin: 0;
    }

    .cat-glass-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(14px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        margin-bottom: 2rem;
    }

    .cat-controls-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .cat-search-form {
        display: flex;
        align-items: center;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid var(--border-glass);
        border-radius: 10px;
        padding: 4px 6px 4px 14px;
        min-width: 280px;
        flex: 1;
        max-width: 440px;
    }

    .cat-search-form:focus-within {
        border-color: var(--neon-blue);
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.25);
    }

    .cat-search-form input {
        background: transparent;
        border: none;
        outline: none;
        color: #ffffff;
        font-size: 0.88rem;
        flex: 1;
        padding: 6px 8px;
    }

    .cat-search-form input::placeholder {
        color: var(--text-muted);
    }

    .cat-table-wrap {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .cat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
        text-align: left;
    }

    .cat-table th {
        background: rgba(15, 23, 42, 0.95);
        color: var(--neon-blue);
        padding: 0.85rem 1.15rem;
        font-weight: 700;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        font-family: 'Fira Code', monospace;
        border-bottom: 1px solid rgba(0, 212, 255, 0.2);
    }

    .cat-table td {
        padding: 0.95rem 1.15rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #ffffff;
        vertical-align: middle;
    }

    .cat-table tr:hover td {
        background: rgba(30, 41, 59, 0.4);
    }

    .slug-pill {
        display: inline-block;
        padding: 3px 8px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.22);
        color: var(--neon-cyan);
        border-radius: 6px;
        font-family: 'Fira Code', monospace;
        font-size: 0.75rem;
    }

    .badge-count-pub {
        background: rgba(6, 247, 227, 0.12);
        border: 1px solid rgba(6, 247, 227, 0.3);
        color: var(--neon-cyan);
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        font-size: 0.76rem;
    }

    .badge-count-total {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: var(--text-muted);
        padding: 2px 8px;
        border-radius: 10px;
        font-family: 'Fira Code', monospace;
        font-size: 0.76rem;
    }

    .btn-action-sm {
        padding: 0.32rem 0.65rem;
        font-size: 0.76rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none !important;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-action-edit {
        background: rgba(0, 212, 255, 0.12);
        border-color: rgba(0, 212, 255, 0.35);
        color: var(--neon-blue);
    }

    .btn-action-edit:hover {
        background: var(--neon-blue);
        color: #030712;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.35);
    }

    .btn-action-delete {
        background: rgba(239, 68, 68, 0.12);
        border-color: rgba(239, 68, 68, 0.35);
        color: #f87171;
    }

    .btn-action-delete:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
    }

    /* MODAL STYLES */
    .cat-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(3, 7, 18, 0.85);
        backdrop-filter: blur(8px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .cat-modal-dialog {
        background: #0d1221;
        border: 1px solid var(--border-glass-hover);
        border-radius: 18px;
        width: 100%;
        max-width: 520px;
        padding: 1.75rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7), 0 0 30px rgba(0, 212, 255, 0.15);
        position: relative;
    }

    .cat-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 0.85rem;
    }

    .cat-modal-title {
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-modal-close {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
    }

    .cat-modal-close:hover {
        color: #ffffff;
    }

    .cat-form-group {
        margin-bottom: 1.15rem;
    }

    .cat-form-label {
        display: block;
        color: var(--text-main);
        font-size: 0.84rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .cat-form-control {
        width: 100%;
        padding: 9px 14px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(0, 212, 255, 0.22);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.25s ease;
    }

    .cat-form-control:focus {
        border-color: var(--neon-blue);
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.25);
    }

    .cat-form-hint {
        font-size: 0.74rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }

    /* FLASH ALERTS */
    .saas-alert-success {
        background: rgba(6, 247, 227, 0.1);
        border: 1px solid rgba(6, 247, 227, 0.35);
        color: var(--neon-cyan);
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
    }

    .saas-alert-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #fca5a5;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
    }
</style>

<div class="saas-category-wrapper">
    <div class="cat-bg-grid"></div>
    <div class="cat-ambient-glow"></div>

    <div class="cat-container">

        <!-- HEADER BANNER -->
        <div class="cat-header-banner">
            <div>
                <h1 class="cat-header-title">
                    <i class="fas fa-folder-tree text-cyan"></i> Blog Category Management
                </h1>
                <p class="cat-header-sub">
                    Organize, create and safely reassign topics across the CypherLabs developer publication.
                </p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm" style="color: var(--text-muted); border-color: rgba(255,255,255,0.15);">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
                <button type="button" class="btn btn-primary-gradient btn-sm" id="openAddCategoryBtn">
                    <i class="fas fa-plus me-1"></i> Add Category
                </button>
            </div>
        </div>

        <!-- NOTIFICATIONS -->
        @if(session('success'))
            <div class="saas-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="saas-alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="saas-alert-danger">
                <i class="fas fa-times-circle"></i>
                <div>
                    <strong>Validation Error:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- MAIN TABLE CARD -->
        <div class="cat-glass-card">
            
            <!-- CONTROLS & SEARCH -->
            <div class="cat-controls-row">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="cat-search-form">
                    <i class="fas fa-search" style="color: var(--text-muted); font-size: 0.85rem;"></i>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Search categories by name or slug..."
                           autocomplete="off">
                    @if(!empty($search))
                        <a href="{{ route('admin.categories.index') }}" style="color: var(--text-muted); text-decoration: none; padding: 0 4px;" title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                <div style="color: var(--text-muted); font-size: 0.82rem; font-family: 'Fira Code', monospace;">
                    Showing {{ $categories->total() }} {{ Str::plural('Category', $categories->total()) }}
                </div>
            </div>

            <!-- CATEGORY DATA TABLE -->
            @if($categories->count() > 0)
                <div class="cat-table-wrap">
                    <table class="cat-table">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Published</th>
                                <th>Total Articles</th>
                                <th>Created</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #ffffff;">{{ $category->name }}</div>
                                    @if($category->description)
                                        <div style="font-size: 0.76rem; color: var(--text-muted); max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $category->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="slug-pill">/{{ $category->slug }}</span>
                                </td>
                                <td>
                                    <span class="badge-count-pub">
                                        <i class="far fa-check-circle me-1"></i>{{ $category->published_articles_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-count-total">
                                        {{ $category->total_articles_count }}
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.8rem;">
                                    {{ $category->created_at ? $category->created_at->format('M d, Y') : '—' }}
                                </td>
                                <td style="text-align: right;">
                                    <button type="button"
                                            class="btn-action-sm btn-action-edit edit-category-btn"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-slug="{{ $category->slug }}"
                                            data-description="{{ $category->description }}"
                                            title="Edit Category">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    @if($category->total_articles_count > 0)
                                        <!-- Open Safe Reassignment Modal -->
                                        <button type="button"
                                                class="btn-action-sm btn-action-delete delete-reassign-btn"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-count="{{ $category->total_articles_count }}"
                                                title="Delete & Reassign Articles">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @else
                                        <!-- Direct Safe Deletion Form -->
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete category \'{{ $category->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-sm btn-action-delete" title="Delete Category">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if($categories->hasPages())
                    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            @else
                <!-- EMPTY STATE -->
                <div style="text-align: center; padding: 3rem 1.5rem;">
                    <i class="fas fa-folder-open" style="font-size: 2.75rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <h3 style="color: #ffffff; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                        @if(!empty($search))
                            No Categories Matching "{{ $search }}"
                        @else
                            No Categories Found
                        @endif
                    </h3>
                    <p style="color: var(--text-muted); font-size: 0.88rem; max-width: 440px; margin: 0 auto 1.5rem;">
                        @if(!empty($search))
                            We couldn't find any category matching your search criteria. Try a different keyword or view all categories.
                        @else
                            Categories help classify engineering insights and make knowledge discovery seamless for readers.
                        @endif
                    </p>
                    @if(!empty($search))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-rotate-left me-1"></i> Clear Search
                        </a>
                    @endif
                    <button type="button" class="btn btn-primary-gradient btn-sm" onclick="document.getElementById('openAddCategoryBtn').click()">
                        <i class="fas fa-plus me-1"></i> Create First Category
                    </button>
                </div>
            @endif

        </div>

    </div>
</div>

<!-- ==========================================================================
     MODAL 1: ADD CATEGORY
     ========================================================================== -->
<div class="cat-modal-backdrop" id="addCategoryModal">
    <div class="cat-modal-dialog">
        <div class="cat-modal-header">
            <h3 class="cat-modal-title">
                <i class="fas fa-folder-plus text-cyan"></i> Create New Category
            </h3>
            <button type="button" class="cat-modal-close" onclick="closeModal('addCategoryModal')">&times;</button>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" id="addCategoryForm">
            @csrf

            <div class="cat-form-group">
                <label class="cat-form-label" for="add_cat_name">Category Name <span style="color: #ef4444;">*</span></label>
                <input type="text"
                       name="name"
                       id="add_cat_name"
                       class="cat-form-control"
                       placeholder="e.g. System Design, Laravel, DevOps"
                       required
                       autocomplete="off">
                <div class="cat-form-hint">Used as the public topic title throughout the blog.</div>
            </div>

            <div class="cat-form-group">
                <label class="cat-form-label" for="add_cat_slug">URL Slug</label>
                <input type="text"
                       name="slug"
                       id="add_cat_slug"
                       class="cat-form-control"
                       placeholder="e.g. system-design"
                       autocomplete="off">
                <div class="cat-form-hint">Generated automatically from category name. Can be customized.</div>
            </div>

            <div class="cat-form-group">
                <label class="cat-form-label" for="add_cat_desc">Description (Optional)</label>
                <textarea name="description"
                          id="add_cat_desc"
                          class="cat-form-control"
                          rows="3"
                          placeholder="Brief technical summary of articles in this category..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeModal('addCategoryModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary-gradient btn-sm">
                    <i class="fas fa-check-circle me-1"></i> Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 2: EDIT CATEGORY
     ========================================================================== -->
<div class="cat-modal-backdrop" id="editCategoryModal">
    <div class="cat-modal-dialog">
        <div class="cat-modal-header">
            <h3 class="cat-modal-title">
                <i class="fas fa-edit text-cyan"></i> Edit Category
            </h3>
            <button type="button" class="cat-modal-close" onclick="closeModal('editCategoryModal')">&times;</button>
        </div>

        <form action="" method="POST" id="editCategoryForm">
            @csrf
            @method('PUT')

            <div class="cat-form-group">
                <label class="cat-form-label" for="edit_cat_name">Category Name <span style="color: #ef4444;">*</span></label>
                <input type="text"
                       name="name"
                       id="edit_cat_name"
                       class="cat-form-control"
                       required
                       autocomplete="off">
            </div>

            <div class="cat-form-group">
                <label class="cat-form-label" for="edit_cat_slug">URL Slug</label>
                <input type="text"
                       name="slug"
                       id="edit_cat_slug"
                       class="cat-form-control"
                       autocomplete="off">
                <div class="cat-form-hint">Unique URL path segment used for filtering.</div>
            </div>

            <div class="cat-form-group">
                <label class="cat-form-label" for="edit_cat_desc">Description (Optional)</label>
                <textarea name="description"
                          id="edit_cat_desc"
                          class="cat-form-control"
                          rows="3"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeModal('editCategoryModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary-gradient btn-sm">
                    <i class="fas fa-save me-1"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 3: SAFE REASSIGN & DELETE CATEGORY
     ========================================================================== -->
<div class="cat-modal-backdrop" id="reassignDeleteModal">
    <div class="cat-modal-dialog">
        <div class="cat-modal-header">
            <h3 class="cat-modal-title" style="color: #f87171;">
                <i class="fas fa-exclamation-triangle"></i> Safe Category Deletion
            </h3>
            <button type="button" class="cat-modal-close" onclick="closeModal('reassignDeleteModal')">&times;</button>
        </div>

        <form action="" method="POST" id="reassignDeleteForm">
            @csrf
            @method('DELETE')

            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem;">
                <p style="color: #fca5a5; font-size: 0.88rem; line-height: 1.55; margin: 0;">
                    Category <strong id="deleteCatName" style="color: #ffffff;"></strong> contains <strong id="deleteArticleCount" style="color: #ffffff;"></strong> assigned articles.
                    To prevent orphaning or losing article associations, you must reassign these articles to another category before deletion.
                </p>
            </div>

            <div class="cat-form-group">
                <label class="cat-form-label" for="reassign_category_id">
                    Reassign All Articles To: <span style="color: #ef4444;">*</span>
                </label>
                <select name="reassign_category_id" id="reassign_category_id" class="cat-form-control" required>
                    <option value="">Select Destination Category...</option>
                    @foreach($allCategories as $destCat)
                        <option value="{{ $destCat->id }}" class="reassign-option" data-id="{{ $destCat->id }}">
                            {{ $destCat->name }} (/{{ $destCat->slug }})
                        </option>
                    @endforeach
                </select>
                <div class="cat-form-hint">All articles in this category will be safely moved to the chosen category.</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeModal('reassignDeleteModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-exchange-alt me-1"></i> Reassign & Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function stringToSlug(str) {
    return str
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Add Category Modal triggers & auto-slug
    const openAddBtn = document.getElementById('openAddCategoryBtn');
    const addNameInput = document.getElementById('add_cat_name');
    const addSlugInput = document.getElementById('add_cat_slug');
    let addSlugManuallyEdited = false;

    if (openAddBtn) {
        openAddBtn.addEventListener('click', function() {
            addSlugManuallyEdited = false;
            openModal('addCategoryModal');
            if (addNameInput) addNameInput.focus();
        });
    }

    if (addSlugInput) {
        addSlugInput.addEventListener('input', function() {
            addSlugManuallyEdited = true;
        });
    }

    if (addNameInput && addSlugInput) {
        addNameInput.addEventListener('input', function() {
            if (!addSlugManuallyEdited) {
                addSlugInput.value = stringToSlug(this.value);
            }
        });
    }

    // 2. Edit Category Modal triggers
    const editBtns = document.querySelectorAll('.edit-category-btn');
    const editForm = document.getElementById('editCategoryForm');
    const editNameInput = document.getElementById('edit_cat_name');
    const editSlugInput = document.getElementById('edit_cat_slug');
    const editDescInput = document.getElementById('edit_cat_desc');

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const slug = this.getAttribute('data-slug');
            const description = this.getAttribute('data-description') || '';

            if (editForm) {
                editForm.action = '{{ url("admin/categories") }}/' + id;
            }
            if (editNameInput) editNameInput.value = name;
            if (editSlugInput) editSlugInput.value = slug;
            if (editDescInput) editDescInput.value = description;

            openModal('editCategoryModal');
        });
    });

    // 3. Safe Reassignment & Delete Modal triggers
    const reassignBtns = document.querySelectorAll('.delete-reassign-btn');
    const reassignForm = document.getElementById('reassignDeleteForm');
    const deleteCatName = document.getElementById('deleteCatName');
    const deleteArticleCount = document.getElementById('deleteArticleCount');
    const reassignSelect = document.getElementById('reassign_category_id');

    reassignBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const count = this.getAttribute('data-count');

            if (reassignForm) {
                reassignForm.action = '{{ url("admin/categories") }}/' + id;
            }
            if (deleteCatName) deleteCatName.textContent = name;
            if (deleteArticleCount) deleteArticleCount.textContent = count;

            // Hide the category being deleted from destination options
            if (reassignSelect) {
                reassignSelect.value = '';
                const options = reassignSelect.querySelectorAll('.reassign-option');
                options.forEach(opt => {
                    if (opt.getAttribute('data-id') === id) {
                        opt.style.display = 'none';
                        opt.disabled = true;
                    } else {
                        opt.style.display = 'block';
                        opt.disabled = false;
                    }
                });
            }

            openModal('reassignDeleteModal');
        });
    });

    // Close modals on backdrop click
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('cat-modal-backdrop')) {
            e.target.style.display = 'none';
        }
    });

    // Close modals on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.cat-modal-backdrop').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });
});
</script>
@endsection

