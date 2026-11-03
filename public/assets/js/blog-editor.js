// 1. Initialize Split.js for 3 columns (Fixed ID targeting)
Split(["#panel-left", "#panel-editor", "#panel-preview"], {
    sizes: [22, 38, 40], // Default percentages
    minSize: [240, 360, 300], // Minimum pixels
    gutterSize: 8,
});

// Component HTML Library (16 Total Components)
const componentLibrary = {
    // 1. Info Callout Box
    infoBox: `<div class="wx-alert wx-alert-info">
    <div class="wx-alert-title"><i class="fas fa-info-circle"></i> Pro Tip</div>
    <p>This is a helpful tip or contextual note for your readers.</p>
</div>`,

    // 2. Warning Box
    warningBox: `<div class="wx-alert wx-alert-warning">
    <div class="wx-alert-title"><i class="fas fa-exclamation-triangle"></i> Important Warning</div>
    <p>Please ensure you backup your codebase and database before executing this command.</p>
</div>`,

    // 3. Success Tip Box
    successBox: `<div class="wx-alert wx-alert-success">
    <div class="wx-alert-title"><i class="fas fa-check-circle"></i> Best Practice</div>
    <p>Following this approach improves performance and eliminates memory leaks.</p>
</div>`,

    // 4. Key Takeaways Card
    keyTakeaways: `<div class="wx-key-takeaways">
    <h4><i class="fas fa-key"></i> Key Takeaways</h4>
    <ul>
        <li>Fast performance with optimized queries.</li>
        <li>Clean, modular Blade component architecture.</li>
        <li>Fully responsive across all mobile viewports.</li>
    </ul>
</div>`,

    // 5. Code Snippet
    codeSnippet: `<pre><code>// Example PHP snippet
function calculateMetrics($data) {
    return array_map(fn($item) => $item * 2, $data);
}
</code></pre>`,

    // 6. Quote Card
    quoteCard: `<blockquote class="wx-quote-card">
    <p>"Great software is crafted by keeping complexity hidden behind clean, elegant abstractions."</p>
    <cite>— CypherLabs Engineering</cite>
</blockquote>`,

    // 7. FAQ Accordion Block
    faqBlock: `<div class="wx-faq-block">
    <h3><i class="fas fa-question-circle"></i> Frequently Asked Questions</h3>
    <details>
        <summary>How do I configure this tool?</summary>
        <p>You can set the environment variables in your .env file or configuration settings panel.</p>
    </details>
    <details>
        <summary>Is this solution mobile responsive?</summary>
        <p>Yes, all elements automatically adapt to screen sizes from 320px up to 4K displays.</p>
    </details>
</div>`,

    // 8. Data / Feature Table
    featureTable: `<table class="wx-table">
    <thead>
        <tr>
            <th>Feature</th>
            <th>Standard</th>
            <th>Pro (CypherLabs)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Execution Speed</td>
            <td>120ms</td>
            <td><strong>12ms</strong> ⚡</td>
        </tr>
        <tr>
            <td>Security Sandbox</td>
            <td>Basic</td>
            <td><strong>Enterprise Isolated</strong> 🛡️</td>
        </tr>
    </tbody>
</table>`,

    // 9. Call to Action (CTA) Card
    ctaBox: `<div class="wx-cta-box">
    <h3><i class="fas fa-rocket"></i> Ready to Upgrade Your Workflow?</h3>
    <p>Explore CypherLabs' full suite of free developer tools and utilities today.</p>
    <a href="/tools" class="wx-cta-btn"><i class="fas fa-arrow-right"></i> Explore Free Tools</a>
</div>`,

    // 10. Steps / Workflow Timeline
    stepsTimeline: `<div class="wx-steps-timeline">
    <div class="wx-step-item">
        <div class="wx-step-number">1</div>
        <div class="wx-step-content">
            <h4>Step 1: Install Dependencies</h4>
            <p>Run the package manager command to install required modules into your project environment.</p>
        </div>
    </div>
    <div class="wx-step-item">
        <div class="wx-step-number">2</div>
        <div class="wx-step-content">
            <h4>Step 2: Configure Environment</h4>
            <p>Add your API credentials and environment variables to your system configuration file.</p>
        </div>
    </div>
    <div class="wx-step-item">
        <div class="wx-step-number">3</div>
        <div class="wx-step-content">
            <h4>Step 3: Deploy & Verify</h4>
            <p>Run your build pipeline and verify live functionality on your testing server.</p>
        </div>
    </div>
</div>`,

    // 11. Pros & Cons Grid
    prosCons: `<div class="wx-pros-cons-grid">
    <div class="wx-pros-box">
        <h4><i class="fas fa-check-circle" style="color:#22c55e;"></i> Advantages</h4>
        <ul>
            <li>High execution speed and low latency</li>
            <li>Easy maintenance and modular scaling</li>
            <li>Built-in security sandboxing</li>
        </ul>
    </div>
    <div class="wx-cons-box">
        <h4><i class="fas fa-times-circle" style="color:#ef4444;"></i> Disadvantages</h4>
        <ul>
            <li>Requires initial environment setup</li>
            <li>Slightly higher initial memory footprint</li>
        </ul>
    </div>
</div>`,

    // 12. Author Note / Insider Tip
    authorNote: `<div class="wx-author-note">
    <div class="wx-author-note-badge"><i class="fas fa-user-edit"></i> Author Note</div>
    <p>I personally benchmarked this approach across 10,000 concurrent requests, and latency dropped by 45% compared to legacy setups.</p>
</div>`,

    // 13. Stats / Metrics Grid
    statsGrid: `<div class="wx-stats-grid">
    <div class="wx-stat-card">
        <div class="wx-stat-number">99.99%</div>
        <div class="wx-stat-label">Uptime SLA</div>
    </div>
    <div class="wx-stat-card">
        <div class="wx-stat-number">&lt; 15ms</div>
        <div class="wx-stat-label">Avg Response Time</div>
    </div>
    <div class="wx-stat-card">
        <div class="wx-stat-number">50,000+</div>
        <div class="wx-stat-label">Active Developers</div>
    </div>
</div>`,

    // 14. Download / Attachment Card
    downloadCard: `<div class="wx-download-card">
    <div class="wx-download-info">
        <i class="fas fa-file-code wx-download-icon"></i>
        <div>
            <h4>Source Code & Demo Files</h4>
            <p>Complete project repository & starter assets (ZIP, 2.4 MB)</p>
        </div>
    </div>
    <a href="#" class="wx-download-btn"><i class="fas fa-download"></i> Download Files</a>
</div>`,

    // 15. Responsive Video Embed Wrapper
    videoEmbed: `<div class="wx-video-wrapper">
    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video Tutorial" allowfullscreen></iframe>
</div>`,

    // 16. General Topic Tags Component
    tagBadges: `<div class="wx-tags-container">
    <span class="wx-tag-badge"><i class="fas fa-hashtag"></i> Laravel</span>
    <span class="wx-tag-badge"><i class="fas fa-hashtag"></i> PHP</span>
    <span class="wx-tag-badge"><i class="fas fa-hashtag"></i> WebDev</span>
    <span class="wx-tag-badge"><i class="fas fa-hashtag"></i> Frontend</span>
    <span class="wx-tag-badge"><i class="fas fa-hashtag"></i> CypherLabs</span>
</div>`,

    // 17. Section Hero Component
    heroSection: `<section class="wx-section-hero">
    <span class="wx-category-pill">Feature Spotlight</span>
    <h2>Build Faster with CypherLabs Tools</h2>
    <p>Discover high-performance utilities and modern web architectures designed for developers.</p>
    <a href="/tools" class="wx-cta-btn"><i class="fas fa-rocket"></i> Get Started Free</a>
</section>`,

    // 18. Feature Grid Section Component
    featureGridSection: `<section class="wx-section-feature-grid">
    <div class="wx-feature-card">
        <div class="wx-feature-icon"><i class="fas fa-bolt"></i></div>
        <h4>Blazing Fast</h4>
        <p>Optimized execution speed with minimal resource consumption.</p>
    </div>
    <div class="wx-feature-card">
        <div class="wx-feature-icon"><i class="fas fa-shield-alt"></i></div>
        <h4>Secure Sandbox</h4>
        <p>Isolated execution environment ensuring complete data privacy.</p>
    </div>
    <div class="wx-feature-card">
        <div class="wx-feature-icon"><i class="fas fa-mobile-alt"></i></div>
        <h4>100% Responsive</h4>
        <p>Fluid designs adapting seamlessly to any screen resolution.</p>
    </div>
</section>`,

    // 19. Split Image + Text Section Component
    splitMediaSection: `<section class="wx-section-split">
    <div class="wx-split-text">
        <h3>Streamlined Developer Experience</h3>
        <p>Integrate our tools into your daily workflow without complex setup or dependencies.</p>
        <ul>
            <li>Zero configuration required</li>
            <li>Instant real-time live previews</li>
        </ul>
    </div>
    <div class="wx-split-media">
        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800" alt="Code Editor">
    </div>
</section>`,

    // 20. Newsletter Subscription Box
    newsletterBox: `<section class="wx-section-newsletter">
    <div class="wx-newsletter-content">
        <h3><i class="far fa-envelope"></i> Subscribe to Developer Digest</h3>
        <p>Get the latest web tutorials, tools updates, and tech insights delivered to your inbox.</p>
        <form class="wx-newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
            <input type="email" placeholder="Enter your email address..." required>
            <button type="submit"><i class="fas fa-paper-plane"></i> Subscribe</button>
        </form>
    </div>
</section>`,

    // 21. Section Divider Line
    sectionDivider: `<div class="wx-section-divider">
    <span><i class="fas fa-gem"></i></span>
</div>`,

    // 22. Pricing / Tier Comparison Section
    pricingSection: `<section class="wx-section-pricing">
    <div class="wx-pricing-card">
        <div class="wx-plan-name">Community</div>
        <div class="wx-plan-price">$0 <span>/ forever</span></div>
        <ul>
            <li>Access to all free web tools</li>
            <li>Standard execution speeds</li>
            <li>Community support</li>
        </ul>
        <a href="#" class="wx-plan-btn outline">Current Plan</a>
    </div>
    <div class="wx-pricing-card featured">
        <div class="wx-plan-badge">Popular</div>
        <div class="wx-plan-name">Pro Developer</div>
        <div class="wx-plan-price">$19 <span>/ month</span></div>
        <ul>
            <li>Unlimited API & Tool Usage</li>
            <li>Priority Dedicated Speed ⚡</li>
            <li>1-on-1 Developer Support</li>
        </ul>
        <a href="#" class="wx-plan-btn filled">Upgrade to Pro</a>
    </div>
</section>`,



    // 16. Complete Article Starter Template
    articleTemplate: `<section>
    <h2>Introduction</h2>
    <p>In this article, we explore modern web application development best practices. Building scalable systems requires clean code architecture and responsive UI components.</p>
    
    <div class="wx-key-takeaways">
        <h4><i class="fas fa-key"></i> Key Takeaways</h4>
        <ul>
            <li>Understand core architecture patterns before writing code.</li>
            <li>Maintain consistent design tokens and responsive typography.</li>
            <li>Ensure fast page load times and mobile optimization.</li>
        </ul>
    </div>
</section>

<section>
    <h2>Implementation Guide</h2>
    <p>Here is a step-by-step code example demonstrating how to configure your setup efficiently.</p>
    
    <pre><code>// Initialize configuration
const config = {
    theme: 'dark',
    mode: 'auto',
    responsive: true
};
console.log("Setup ready:", config);
</code></pre>

    <div class="wx-alert wx-alert-info">
        <div class="wx-alert-title"><i class="fas fa-info-circle"></i> Optimization Note</div>
        <p>Always verify your build bundle size before deploying to production.</p>
    </div>
</section>

<section>
    <h2>Conclusion</h2>
    <p>By following these best practices, your web application will be fast, maintainable, and user-friendly.</p>
</section>`
};

let editors = {}; // Store our Monaco instances

// 2. Load Monaco Editor via CDN securely
const requireConfig = {
    paths: {
        vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs",
    },
};
const script = document.createElement("script");
script.src = "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js";

script.onload = () => {
    require.config(requireConfig);
    require(["vs/editor/editor.main"], function () {
        const initData = window.INITIAL_POST_DATA || {};
        if (initData.id) {
            currentPostId = initData.id;
        }

        const initialHtml = initData.html !== undefined && initData.html !== null ? initData.html : componentLibrary.articleTemplate;
        const initialCss = initData.css !== undefined && initData.css !== null ? initData.css : '';
        const initialJs = initData.js !== undefined && initData.js !== null ? initData.js : '';

        if (initData.title) {
            const titleEl = document.getElementById('title-input') || document.querySelector('.title-input');
            if (titleEl) titleEl.value = initData.title;
        }
        if (initData.featured_image) {
            const featEl = document.getElementById('featured_image');
            if (featEl) featEl.value = initData.featured_image;
            if (window.updateImagePreview) window.updateImagePreview(initData.featured_image);
        }
        if (initData.category_id) {
            const catEl = document.getElementById('category_id');
            if (catEl) catEl.value = initData.category_id;
        }
        if (initData.seo_title) {
            const seoEl = document.getElementById('seo_title');
            if (seoEl) seoEl.value = initData.seo_title;
        }
        if (initData.meta_description) {
            const metaEl = document.getElementById('meta_description');
            if (metaEl) metaEl.value = initData.meta_description;
        }
        if (initData.keywords) {
            const kwEl = document.getElementById('keywords');
            if (kwEl) kwEl.value = initData.keywords;
        }
        if (initData.canonical_url) {
            const canEl = document.getElementById('canonical_url');
            if (canEl) canEl.value = initData.canonical_url;
        }
        if (initData.schema_type) {
            const schEl = document.getElementById('schema_type');
            if (schEl) schEl.value = initData.schema_type;
        }

        // Initialize HTML Editor
        editors.html = monaco.editor.create(document.getElementById("monaco-html"), {
            value: initialHtml,
            language: "html",
            theme: "vs-dark",
            automaticLayout: true,
            minimap: { enabled: false },
            fontSize: 14,
            wordWrap: "on",
        });

        // Initialize CSS Editor
        editors.css = monaco.editor.create(document.getElementById("monaco-css"), {
            value: initialCss,
            language: "css",
            theme: "vs-dark",
            automaticLayout: true,
            minimap: { enabled: false },
            fontSize: 14,
        });

        // Initialize JS Editor
        editors.js = monaco.editor.create(document.getElementById("monaco-js"), {
            value: initialJs,
            language: "javascript",
            theme: "vs-dark",
            automaticLayout: true,
            minimap: { enabled: false },
            fontSize: 14,
        });

        // Add Change Listeners for Live Preview (Debounced)
        editors.html.onDidChangeModelContent(() => debounce(updatePreview, 300)());
        editors.css.onDidChangeModelContent(() => debounce(updatePreview, 300)());
        editors.js.onDidChangeModelContent(() => debounce(updatePreview, 300)());

        // Run first preview
        updatePreview();
    });
};
document.head.appendChild(script);

// 3. Live Preview Logic (Reflected to match CypherLabs dark frontend theme)
function updatePreview() {
    if (!editors.html) return;

    const html = editors.html.getValue();
    const css = editors.css ? editors.css.getValue() : '';
    const js = editors.js ? editors.js.getValue() : '';

    const iframeContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
            <style>
                body {
                    background: #060812;
                    color: #CBD5E1;
                    font-family: 'Inter', sans-serif;
                    padding: 24px;
                    margin: 0;
                    line-height: 1.78;
                    font-size: 1.02rem;
                }
                * { box-sizing: border-box; }
                h1, h2, h3, h4 { color: #FFFFFF; font-weight: 700; margin-top: 1.8rem; margin-bottom: 0.85rem; }
                h1 { font-size: 26px; border-bottom: 1px solid rgba(0, 212, 255, 0.16); padding-bottom: 8px; }
                h2 { font-size: 22px; }
                h3 { font-size: 19px; }
                p { margin-bottom: 1.15rem; }
                a { color: #00D4FF; text-decoration: underline; }
                pre { background: #080B17; border: 1px solid rgba(0, 212, 255, 0.16); padding: 1rem 1.25rem; border-radius: 10px; font-family: 'Fira Code', monospace; color: #E2E8F0; }
                code { font-family: 'Fira Code', monospace; background: rgba(0, 212, 255, 0.12); color: #06F7E3; padding: 2px 6px; border-radius: 4px; }
                pre code { background: transparent; padding: 0; color: inherit; }
                
                /* Component Styles */
                .wx-alert { padding: 14px 18px; border-radius: 12px; margin: 1.5rem 0; border: 1px solid rgba(255,255,255,0.1); }
                .wx-alert-info { background: rgba(0, 212, 255, 0.08); border-color: rgba(0, 212, 255, 0.3); color: #E2E8F0; }
                .wx-alert-warning { background: rgba(234, 179, 8, 0.08); border-color: rgba(234, 179, 8, 0.3); color: #FEF08A; }
                .wx-alert-success { background: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.3); color: #DCFCE7; }
                .wx-alert-title { font-weight: 700; font-size: 14px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }

                .wx-key-takeaways { background: rgba(13, 18, 33, 0.8); border: 1px solid rgba(0, 212, 255, 0.25); border-radius: 14px; padding: 18px 22px; margin: 1.8rem 0; }
                .wx-key-takeaways h4 { color: #00D4FF; margin-top: 0; font-size: 16px; }

                .wx-quote-card { border-left: 3px solid #00D4FF; background: rgba(0, 212, 255, 0.05); padding: 1rem 1.35rem; margin: 1.5rem 0; font-style: italic; border-radius: 0 10px 10px 0; }

                .wx-cta-box { background: linear-gradient(135deg, rgba(0, 212, 255, 0.12), rgba(138, 92, 246, 0.12)); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 16px; padding: 24px; text-align: center; margin: 2rem 0; }
                .wx-cta-box h3 { margin-top: 0; color: #FFFFFF; }
                .wx-cta-btn { display: inline-flex; align-items: center; gap: 6px; background: #00D4FF; color: #060812; font-weight: 700; padding: 8px 20px; border-radius: 30px; text-decoration: none; margin-top: 10px; }

                .wx-table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; border-radius: 10px; overflow: hidden; border: 1px solid rgba(0,212,255,0.16); }
                .wx-table th, .wx-table td { padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); text-align: left; }
                .wx-table th { background: rgba(0,212,255,0.12); color: #fff; }

                /* NEW COMPONENT STYLES */
                .wx-steps-timeline { display: flex; flex-direction: column; gap: 16px; margin: 1.8rem 0; }
                .wx-step-item { display: flex; gap: 16px; background: rgba(13, 18, 33, 0.6); border: 1px solid rgba(0, 212, 255, 0.16); border-radius: 12px; padding: 16px; }
                .wx-step-number { width: 36px; height: 36px; border-radius: 50%; background: #00D4FF; color: #060812; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
                .wx-step-content h4 { margin-top: 0; margin-bottom: 4px; font-size: 16px; }
                .wx-step-content p { margin: 0; font-size: 14px; color: #94A3B8; }

                .wx-pros-cons-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 1.8rem 0; }
                .wx-pros-box, .wx-cons-box { background: rgba(13, 18, 33, 0.6); border-radius: 12px; padding: 16px; border: 1px solid rgba(0, 212, 255, 0.16); }
                .wx-pros-box h4, .wx-cons-box h4 { margin-top: 0; margin-bottom: 12px; font-size: 15px; }
                .wx-pros-box ul, .wx-cons-box ul { margin: 0; padding-left: 20px; }

                .wx-author-note { background: linear-gradient(135deg, rgba(138, 92, 246, 0.1), rgba(0, 212, 255, 0.08)); border: 1px solid rgba(138, 92, 246, 0.3); border-radius: 12px; padding: 16px 20px; margin: 1.8rem 0; }
                .wx-author-note-badge { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #8B5CF6; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
                .wx-author-note p { margin: 0; color: #F1F5F9; font-style: italic; }

                .wx-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin: 1.8rem 0; }
                .wx-stat-card { background: rgba(13, 18, 33, 0.8); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 12px; padding: 18px; text-align: center; }
                .wx-stat-number { font-size: 24px; font-weight: 800; color: #00D4FF; }
                .wx-stat-label { font-size: 12px; color: #94A3B8; margin-top: 4px; }

                .wx-download-card { display: flex; align-items: center; justify-content: space-between; background: rgba(13, 18, 33, 0.7); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 14px; padding: 16px 20px; margin: 1.8rem 0; }
                .wx-download-info { display: flex; align-items: center; gap: 14px; }
                .wx-download-icon { font-size: 26px; color: #00D4FF; }
                .wx-download-info h4 { margin: 0; font-size: 15px; }
                .wx-download-info p { margin: 2px 0 0; font-size: 12.5px; color: #94A3B8; }
                .wx-download-btn { background: rgba(0, 212, 255, 0.15); border: 1px solid #00D4FF; color: #00D4FF; padding: 6px 16px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 600; }

                .wx-video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 14px; margin: 1.8rem 0; border: 1px solid rgba(0, 212, 255, 0.2); }
                .wx-video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }

                .wx-tags-container { display: flex; flex-wrap: wrap; gap: 8px; margin: 1.5rem 0; }
                .wx-tag-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(0, 212, 255, 0.08); border: 1px solid rgba(0, 212, 255, 0.22); color: #06F7E3; font-size: 12px; font-weight: 600; font-family: 'Fira Code', monospace; padding: 4px 12px; border-radius: 20px; }

                /* NEW LAYOUT SECTION STYLES */
                .wx-section-hero { background: linear-gradient(135deg, rgba(0,212,255,0.1), rgba(138,92,246,0.1)); border: 1px solid rgba(0,212,255,0.25); border-radius: 16px; padding: 30px; text-align: center; margin: 2rem 0; }
                .wx-section-hero h2 { font-size: 24px; margin-top: 10px; }

                .wx-section-feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin: 2rem 0; }
                .wx-feature-card { background: rgba(13,18,33,0.7); border: 1px solid rgba(0,212,255,0.16); border-radius: 12px; padding: 18px; text-align: center; }
                .wx-feature-icon { font-size: 24px; color: #00D4FF; margin-bottom: 8px; }
                .wx-feature-card h4 { margin: 0 0 6px; font-size: 15px; }
                .wx-feature-card p { margin: 0; font-size: 13px; color: #94A3B8; }

                .wx-section-split { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center; margin: 2rem 0; background: rgba(13,18,33,0.6); border: 1px solid rgba(0,212,255,0.16); border-radius: 14px; padding: 20px; }
                .wx-split-media img { width: 100%; border-radius: 10px; display: block; }
                .wx-split-text h3 { margin-top: 0; }

                .wx-section-newsletter { background: linear-gradient(135deg, rgba(13,18,33,0.9), rgba(6,8,18,0.9)); border: 1px solid rgba(0,212,255,0.3); border-radius: 16px; padding: 24px; text-align: center; margin: 2rem 0; }
                .wx-newsletter-form { display: flex; gap: 8px; max-width: 440px; margin: 16px auto 0; }
                .wx-newsletter-form input { flex: 1; background: rgba(0,0,0,0.4); border: 1px solid rgba(0,212,255,0.25); border-radius: 30px; padding: 8px 16px; color: #fff; outline: none; }
                .wx-newsletter-form button { background: #00D4FF; border: none; color: #060812; font-weight: 700; border-radius: 30px; padding: 8px 18px; cursor: pointer; }

                .wx-section-divider { display: flex; align-items: center; justify-content: center; margin: 2.2rem 0; position: relative; }
                .wx-section-divider::before { content: ""; position: absolute; left: 0; right: 0; top: 50%; height: 1px; background: linear-gradient(90deg, transparent, rgba(0,212,255,0.4), transparent); }
                .wx-section-divider span { position: relative; background: #060812; padding: 0 12px; color: #00D4FF; font-size: 14px; }

                .wx-section-pricing { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 2rem 0; }
                .wx-pricing-card { background: rgba(13,18,33,0.7); border: 1px solid rgba(0,212,255,0.16); border-radius: 14px; padding: 20px; position: relative; }
                .wx-pricing-card.featured { border-color: #00D4FF; background: rgba(0,212,255,0.06); }
                .wx-plan-badge { position: absolute; top: -10px; right: 16px; background: #00D4FF; color: #060812; font-weight: 800; font-size: 10px; text-transform: uppercase; padding: 2px 10px; border-radius: 10px; }
                .wx-plan-name { font-weight: 700; font-size: 16px; color: #fff; }
                .wx-plan-price { font-size: 26px; font-weight: 800; color: #00D4FF; margin: 8px 0; }
                .wx-plan-price span { font-size: 12px; color: #94A3B8; font-weight: normal; }
                .wx-plan-btn { display: block; text-align: center; padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 13px; text-decoration: none; margin-top: 14px; }
                .wx-plan-btn.filled { background: #00D4FF; color: #060812; }
                .wx-plan-btn.outline { border: 1px solid rgba(0,212,255,0.3); color: #00D4FF; }



                ${css}
            </style>
        </head>
        <body>
            ${html}
            <script>${js}<\/script>
        </body>
        </html>
    `;

    document.getElementById("live-preview-frame").srcdoc = iframeContent;
}

// 4. Tab Switching Logic for Monaco Editor
window.switchTab = function (event, lang) {
    document.querySelectorAll(".monaco-container").forEach((el) => el.classList.remove("active"));
    document.querySelectorAll(".tab-btn").forEach((el) => el.classList.remove("active"));

    document.getElementById(`monaco-${lang}`).classList.add("active");
    if (event) event.currentTarget.classList.add("active");
};

// Tab Switching Logic for Sidebar (Components/SEO)
window.switchSidebarTab = function (event, tabName) {
    document.querySelectorAll(".sidebar-content").forEach((el) => el.classList.remove("active"));
    document.querySelectorAll(".side-tab-btn").forEach((el) => el.classList.remove("active"));

    document.getElementById(`sidebar-${tabName}`).classList.add("active");
    if (event) event.currentTarget.classList.add("active");
};

// 5. Insert Component Logic
window.insertComponent = function (componentKey) {
    if (!editors.html) return;

    const htmlTab = document.querySelector('.tab-btn[onclick*="html"]');
    if (htmlTab) htmlTab.click();

    const htmlString = componentLibrary[componentKey] || '';
    const editor = editors.html;
    const position = editor.getPosition();

    editor.executeEdits("insert-component", [
        {
            range: new monaco.Range(
                position.lineNumber,
                position.column,
                position.lineNumber,
                position.column
            ),
            text: "\n" + htmlString + "\n",
            forceMoveMarkers: true,
        },
    ]);

    editor.focus();
};

let debounceTimer;
function debounce(func, delay) {
    return function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(this, arguments), delay);
    };
}

let currentPostId = null;

function getVal(id) {
    const el = document.getElementById(id);
    return el ? el.value : '';
}

window.uploadImageFile = function(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    const formData = new FormData();
    formData.append('image', file);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    const previewBox = document.getElementById('image-preview-box');
    const previewImg = document.getElementById('image-preview-img');
    
    const uploadUrl = (window.appUrls && window.appUrls.uploadImage) ? window.appUrls.uploadImage : '/admin/blog/upload-image';
    fetch(uploadUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.url) {
            document.getElementById('featured_image').value = data.url;
            if (previewImg && previewBox) {
                previewImg.src = data.url;
                previewBox.style.display = 'block';
            }
            alert('Image uploaded successfully!');
        } else {
            alert(data.message || 'Image upload failed.');
        }
    })
    .catch(err => {
        console.error('Image upload error:', err);
        alert('Image upload failed.');
    });
};

window.updateImagePreview = function(url) {
    const previewBox = document.getElementById('image-preview-box');
    const previewImg = document.getElementById('image-preview-img');
    if (url && previewImg && previewBox) {
        previewImg.src = url;
        previewBox.style.display = 'block';
    } else if (previewBox) {
        previewBox.style.display = 'none';
    }
};

function getPostPayload(statusType) {
    const titleInput = document.querySelector(".title-input");
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    return {
        post_id: currentPostId,
        title: titleInput && titleInput.value ? titleInput.value : "Untitled",
        html: editors.html ? editors.html.getValue() : "",
        css: editors.css ? editors.css.getValue() : "",
        js: editors.js ? editors.js.getValue() : "",
        featured_image: getVal("featured_image"),
        category_id: getVal("category_id") || null,
        seo_title: getVal("seo_title"),
        meta_description: getVal("meta_description"),
        keywords: getVal("keywords"),
        canonical_url: getVal("canonical_url"),
        schema_type: getVal("schema_type"),
        status: statusType,
        _token: csrfToken ? csrfToken.getAttribute("content") : "",
    };
}

window.submitPost = function (statusType) {
    if (!editors.html) return;

    const btnId = statusType === "published" ? "btn-publish" : "btn-draft";
    const btn = document.getElementById(btnId);
    let originalText = "Save";

    if (btn) {
        originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;
    }

    const payload = getPostPayload(statusType);

    const saveUrl = (window.appUrls && window.appUrls.saveBlog) ? window.appUrls.saveBlog : "/admin/blog/save";
    const dashboardUrl = (window.appUrls && window.appUrls.dashboard) ? window.appUrls.dashboard : '/dashboard';

    fetch(saveUrl, { 
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        body: JSON.stringify(payload),
    })
    .then((response) => response.json())
    .then((data) => {
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        if (data.post_id) {
            currentPostId = data.post_id;
        }

        alert(data.message || `Post successfully saved as ${statusType}!`);
        window.location.href = data.redirect || dashboardUrl;
    })
    .catch((error) => {
        console.error("Submission failed:", error);
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
        alert("Something went wrong during submission!");
    });
};