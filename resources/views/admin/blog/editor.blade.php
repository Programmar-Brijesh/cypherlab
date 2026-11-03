<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CypherLabs Blog Studio & Creator</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/split.js/1.6.5/split.min.js"></script>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/blog-editor.css') }}">
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    
    <style>
        body.weblixo-dark-theme {
            overflow: auto !important;
        }
        .topbar-right button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-template-ui {
            background: rgba(0, 212, 255, 0.15);
            color: #00D4FF;
            border: 1px solid rgba(0, 212, 255, 0.3) !important;
        }
        .btn-template-ui:hover {
            background: rgba(0, 212, 255, 0.25);
            color: #ffffff;
        }
        .btn-draft-ui {
            background: #374151;
            color: #fff;
        }
        .btn-draft-ui:hover {
            background: #4B5563;
        }
        .btn-publish-ui {
            background: #00D4FF;
            color: #060812;
            font-weight: 700 !important;
        }
        .btn-publish-ui:hover {
            background: #06F7E3;
            color: #000;
        }
        .topbar-right button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .component-card {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 212, 255, 0.12);
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .component-icon {
            color: #00D4FF;
            font-size: 16px;
            width: 24px;
            text-align: center;
        }
        .component-info h4 {
            font-size: 13px;
            font-weight: 600;
            color: #f8fafc;
            margin: 0;
        }
        .component-info p {
            font-size: 11px;
            color: #94a3b8;
            margin: 2px 0 0;
        }
        .char-counter {
            font-size: 11px;
            color: #94a3b8;
            float: right;
        }
    </style>
</head>

<body class="weblixo-dark-theme">

    @include('components.header')

    <nav class="editor-topbar">
        <div class="topbar-left">
            <a href="{{ route('dashboard') }}" class="back-btn" title="Back to Dashboard"><i class="fas fa-arrow-left"></i></a>
            <input type="text" id="title-input" class="title-input" placeholder="Enter Blog Post Title..." value="">
            <span class="save-status" id="save-status" style="display: none;"><i class="fas fa-info-circle"></i> Ready</span>
        </div>
        <div class="topbar-right">
            <button type="button" class="btn-template-ui" onclick="insertComponent('articleTemplate')" title="Insert Sample Article Template">
                <i class="fas fa-magic"></i> Starter Template
            </button>
            <button type="button" id="btn-draft" class="btn-draft-ui" onclick="submitPost('draft')">
                <i class="fas fa-save"></i> Save Draft
            </button>
            <button type="button" id="btn-publish" class="btn-publish-ui" onclick="submitPost('published')">
                <i class="fas fa-paper-plane"></i> Publish Post
            </button>
        </div>
    </nav>

    <div class="workspace" id="workspace">

        <!-- LEFT SIDEBAR: COMPONENTS & SEO -->
        <div id="panel-left" class="panel glass-panel">
            <div class="sidebar-tabs">
                <button type="button" class="side-tab-btn active" onclick="switchSidebarTab(event, 'components')"><i class="fas fa-cubes"></i> Components</button>
                <button type="button" class="side-tab-btn" onclick="switchSidebarTab(event, 'seo')"><i class="fas fa-search"></i> SEO</button>
            </div>

            <!-- COMPONENTS TAB -->
            <div id="sidebar-components" class="sidebar-content active">
                <div class="components-list">
                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-info-circle text-info"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Info Callout</h4>
                                <p>Pro Tip / Note Box</p>
                            </div>
                            <button type="button" onclick="insertComponent('infoBox')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-exclamation-triangle text-warning"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Warning Box</h4>
                                <p>Important Caution Box</p>
                            </div>
                            <button type="button" onclick="insertComponent('warningBox')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-check-circle text-success"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Success Tip</h4>
                                <p>Best Practice Box</p>
                            </div>
                            <button type="button" onclick="insertComponent('successBox')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-key text-cyan"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Key Takeaways</h4>
                                <p>Summary Checklist Card</p>
                            </div>
                            <button type="button" onclick="insertComponent('keyTakeaways')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-code"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Code Snippet</h4>
                                <p>Formatted Code Block</p>
                            </div>
                            <button type="button" onclick="insertComponent('codeSnippet')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-quote-left"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Pull Quote</h4>
                                <p>Testimonial Quote Box</p>
                            </div>
                            <button type="button" onclick="insertComponent('quoteCard')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>FAQ Accordion</h4>
                                <p>Collapsible Q&A Block</p>
                            </div>
                            <button type="button" onclick="insertComponent('faqBlock')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-table"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Feature Table</h4>
                                <p>Comparison Data Table</p>
                            </div>
                            <button type="button" onclick="insertComponent('featureTable')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-list-ol text-warning"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Steps Timeline</h4>
                                <p>Numbered Step Workflow</p>
                            </div>
                            <button type="button" onclick="insertComponent('stepsTimeline')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-columns text-info"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Pros & Cons</h4>
                                <p>Side-by-side Comparison</p>
                            </div>
                            <button type="button" onclick="insertComponent('prosCons')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-user-edit text-purple"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Author Note</h4>
                                <p>Insider Experience Note</p>
                            </div>
                            <button type="button" onclick="insertComponent('authorNote')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-chart-bar text-cyan"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Stats Grid</h4>
                                <p>Metrics & Stats Banner</p>
                            </div>
                            <button type="button" onclick="insertComponent('statsGrid')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-file-download text-success"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Download Card</h4>
                                <p>Resource Attachment Card</p>
                            </div>
                            <button type="button" onclick="insertComponent('downloadCard')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fab fa-youtube text-danger"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Video Embed</h4>
                                <p>Responsive YouTube Box</p>
                            </div>
                            <button type="button" onclick="insertComponent('videoEmbed')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-bullhorn text-warning"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Hero Section</h4>
                                <p>Spotlight Header Banner</p>
                            </div>
                            <button type="button" onclick="insertComponent('heroSection')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-th-large text-info"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Feature Grid</h4>
                                <p>3-Column Features Section</p>
                            </div>
                            <button type="button" onclick="insertComponent('featureGridSection')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-photo-video text-purple"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Split Media Section</h4>
                                <p>Text & Image 2-Column</p>
                            </div>
                            <button type="button" onclick="insertComponent('splitMediaSection')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="far fa-envelope text-cyan"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Newsletter Box</h4>
                                <p>Email Signup Section</p>
                            </div>
                            <button type="button" onclick="insertComponent('newsletterBox')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-minus text-muted"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Section Divider</h4>
                                <p>Glowing Separator Line</p>
                            </div>
                            <button type="button" onclick="insertComponent('sectionDivider')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-tags text-cyan"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>General Tags</h4>
                                <p>Topic Badges / Hashtags</p>
                            </div>
                            <button type="button" onclick="insertComponent('tagBadges')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-tags text-success"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>Pricing Section</h4>
                                <p>Tier Comparison Cards</p>
                            </div>
                            <button type="button" onclick="insertComponent('pricingSection')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="component-card">
                        <div class="component-icon"><i class="fas fa-rocket"></i></div>
                        <div class="component-info">
                            <div>
                                <h4>CTA Card</h4>
                                <p>Call to Action Card</p>
                            </div>
                            <button type="button" onclick="insertComponent('ctaBox')" class="btn-insert" title="Insert Component"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>



                </div>
            </div>

            <!-- SEO TAB -->
            <div id="sidebar-seo" class="sidebar-content">
                <div class="seo-form">
                    <div class="form-group">
                        <label><i class="far fa-image"></i> Featured Hero Image</label>
                        <input type="text" id="featured_image" class="form-control" placeholder="https://cdn.cypherlabs.com/... or paste URL" oninput="updateImagePreview(this.value)">
                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                            <label for="featured_image_file" class="btn-draft-ui" style="padding: 6px 12px; font-size: 12px; cursor: pointer; border-radius: 6px;">
                                <i class="fas fa-upload"></i> Upload Image File
                            </label>
                            <input type="file" id="featured_image_file" accept="image/*" style="display: none;" onchange="uploadImageFile(this)">
                        </div>
                        <div id="image-preview-box" style="margin-top: 10px; display: none;">
                            <img id="image-preview-img" src="" alt="Preview" style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #374151;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Category</label>
                        <select id="category_id" class="form-control">
                            <option value="">Select Category...</option>
                            <option value="1">Web Development</option>
                            <option value="2">UI/UX Design</option>
                            <option value="3">Developer Tools</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> SEO Title <span class="char-counter" id="seo-title-count">0/60</span></label>
                        <input type="text" id="seo_title" class="form-control" placeholder="60 chars maximum" oninput="document.getElementById('seo-title-count').textContent = this.value.length + '/60'">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Meta Description <span class="char-counter" id="meta-desc-count">0/160</span></label>
                        <textarea id="meta_description" class="form-control" rows="4" placeholder="160 characters maximum..." oninput="document.getElementById('meta-desc-count').textContent = this.value.length + '/160'"></textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tags"></i> Keywords</label>
                        <input type="text" id="keywords" class="form-control" placeholder="laravel, php, tools (comma separated)">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-link"></i> Canonical URL</label>
                        <input type="text" id="canonical_url" class="form-control" placeholder="Leave empty for default">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-code-branch"></i> Schema Type</label>
                        <select id="schema_type" class="form-control">
                            <option value="Article">Article</option>
                            <option value="TechArticle">TechArticle</option>
                            <option value="HowTo">HowTo</option>
                            <option value="FAQ">FAQ</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- CENTER: MONACO CODE EDITOR -->
        <div id="panel-editor" class="panel">
            <div class="editor-tabs">
                <button type="button" class="tab-btn active" onclick="switchTab(event, 'html')"><i class="fab fa-html5 text-orange"></i> index.html</button>
                <button type="button" class="tab-btn" onclick="switchTab(event, 'css')"><i class="fab fa-css3-alt text-blue"></i> style.css</button>
                <button type="button" class="tab-btn" onclick="switchTab(event, 'js')"><i class="fab fa-js text-yellow"></i> script.js</button>
            </div>
            <div id="monaco-html" class="monaco-container active"></div>
            <div id="monaco-css" class="monaco-container"></div>
            <div id="monaco-js" class="monaco-container"></div>
        </div>

        <!-- RIGHT: LIVE PREVIEW IFRAME -->
        <div id="panel-preview" class="panel glass-panel">
            <div class="panel-header">
                <h3><i class="fas fa-desktop"></i> Live Preview</h3>
            </div>
            <div class="preview-wrapper">
                <iframe id="live-preview-frame"></iframe>
            </div>
        </div>

    </div>

    <script>
        window.appUrls = {
            uploadImage: @json(route('admin.blog.upload_image')),
            saveBlog: @json(route('admin.blog.save')),
            dashboard: @json(route('dashboard')),
        };
        window.INITIAL_POST_DATA = {!! json_encode(isset($post) && $post ? [
            'id' => $post->id,
            'title' => $post->title,
            'html' => $post->html,
            'css' => $post->css,
            'js' => $post->js,
            'featured_image' => $post->featured_image_url ?? $post->featured_image,
            'category_id' => $post->category_id,
            'seo_title' => $post->seo_title,
            'meta_description' => $post->meta_description,
            'keywords' => $post->keywords,
            'canonical_url' => $post->canonical_url,
            'schema_type' => $post->schema_type ?? 'Article',
            'status' => $post->status,
        ] : null) !!};
    </script>
    <script src="{{ asset('assets/js/blog-editor.js') }}"></script>
</body>

</html>