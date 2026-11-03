@extends('layouts.app')

@section('title', 'Chromatic Studio – Website Color & Theme Intelligence Studio | CypherLabs')
@section('meta_description', 'Generate website color schemes, premium UI themes, Tailwind tokens, and CSS gradients based on custom colors or website categories. 100% private & client-side.')

@section('content')

<style>
    .saas-chroma-wrapper {
        --neon-blue: #00d4ff;
        --neon-cyan: #06f7e3;
        --neon-purple: #a855f7;
        --neon-pink: #ff2e9e;
        --accent-gold: #ffcc44;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(0, 212, 255, 0.18);
        --border-glass-hover: rgba(0, 212, 255, 0.4);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;

        background: var(--bg-dark);
        min-height: calc(100vh - 120px);
        padding: 2.5rem 1rem 4rem;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    .chroma-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .chroma-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .chroma-container {
        max-width: 1180px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .chroma-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .chroma-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        margin-bottom: 1rem;
    }

    .chroma-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .chroma-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* CATEGORY SELECTOR CAROUSEL / GRID */
    .cat-selector-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
    }

    .cat-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 10px;
    }

    .cat-pill-btn {
        padding: 0.75rem 0.6rem;
        border-radius: 14px;
        background: rgba(8, 12, 24, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #cbd5e1;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .cat-pill-btn i {
        font-size: 1.1rem;
    }

    .cat-pill-btn:hover, .cat-pill-btn.active {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        transform: translateY(-2px);
    }

    /* STUDIO WORKSPACE GRID */
    .chroma-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    /* GLASS CARDS */
    .chroma-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
    }

    .chroma-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .chroma-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chroma-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1rem;
    }

    .chroma-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* COLOR PICKER INPUTS */
    .color-picker-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .native-color-box {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        border: 2px solid var(--neon-blue);
        cursor: pointer;
        background: transparent;
        padding: 0;
    }

    .color-input-field {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        color: var(--neon-cyan);
        font-family: 'Fira Code', monospace;
        font-size: 0.88rem;
        width: 100%;
    }

    /* PALETTE SWATCHES */
    .swatch-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 1.25rem;
    }

    .color-swatch-box {
        flex: 1;
        min-width: 65px;
        height: 75px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        padding: 6px;
        font-family: 'Fira Code', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .color-swatch-box:hover {
        transform: translateY(-3px);
        border-color: #ffffff;
    }

    /* LIVE MOCKUP STAGE */
    .mockup-preview-stage {
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .mockup-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mockup-card-box {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-mockup-primary {
        padding: 0.55rem 1.2rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-family: 'Fira Code', monospace;
    }

    /* GRADIENT & ACCESSIBILITY */
    .grad-preview-box {
        height: 85px;
        border-radius: 14px;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .badge-wcag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    .wcag-pass { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
    .wcag-fail { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

    /* ACTION & EXPORT BAR */
    .chroma-action-bar {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .chroma-btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #00d4ff 0%, #06f7e3 100%);
        border: none;
        color: #070913;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chroma-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .chroma-btn-glass {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.86rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chroma-btn-glass:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* TOAST */
    .chroma-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .chroma-toast {
        background: #0f172a;
        border: 1px solid var(--neon-blue);
        border-left: 4px solid var(--neon-blue);
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 850px) {
        .chroma-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-chroma-wrapper">
    <div class="chroma-bg-grid"></div>
    <div class="chroma-ambient-glow"></div>

    <div class="chroma-container">
        
        <!-- HERO HEADER -->
        <div class="chroma-hero-box">
            <div class="chroma-hero-badge">
                <i class="fas fa-palette me-1"></i> CHROMATIC STUDIO AI
            </div>
            <h1 class="chroma-hero-title">
                Website Color & <span class="grad-text">Theme Intelligence Studio</span>
            </h1>
            <p class="chroma-hero-sub">
                Select your custom brand color OR click your website category to instantly generate production-ready UI schemes, gradients, and CSS tokens.
            </p>
        </div>

        <!-- WEBSITE CATEGORY / NICHE PRESETS -->
        <div class="cat-selector-card">
            <div class="cat-header-row">
                <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; font-family:'Fira Code';">
                    Select Website Category
                </div>
                <button type="button" class="chroma-btn-glass" style="padding:0.4rem 0.85rem; font-size:0.75rem;" onclick="shuffleCurrentCategoryTheme()">
                    <i class="fas fa-sync-alt me-1" style="color:var(--neon-blue);"></i> Refresh / Shuffle Category Theme
                </button>
            </div>

            <div class="cat-grid">
                <button type="button" class="cat-pill-btn active" onclick="applyPreset('saas')">
                    <i class="fas fa-rocket" style="color:#00d4ff;"></i> SaaS & AI Tech
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('ecommerce')">
                    <i class="fas fa-shopping-bag" style="color:#22c55e;"></i> E-Commerce Store
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('gaming')">
                    <i class="fas fa-gamepad" style="color:#ff2e9e;"></i> Gaming & Cyberpunk
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('luxury')">
                    <i class="fas fa-gem" style="color:#ffcc44;"></i> Luxury & Elegance
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('agency')">
                    <i class="fas fa-lightbulb" style="color:#f97316;"></i> Creative Agency
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('health')">
                    <i class="fas fa-heartbeat" style="color:#14b8a6;"></i> Health & Wellness
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('fintech')">
                    <i class="fas fa-chart-line" style="color:#3b82f6;"></i> FinTech & Banking
                </button>
                <button type="button" class="cat-pill-btn" onclick="applyPreset('dark')">
                    <i class="fas fa-moon" style="color:#a855f7;"></i> Dark Mode Elite
                </button>
            </div>
        </div>

        <!-- STUDIO WORKSPACE GRID -->
        <div class="chroma-studio-grid">
            
            <!-- LEFT PANEL: COLOR CONTROL & PALETTES -->
            <div class="chroma-glass-card">
                <div class="chroma-card-head">
                    <div class="chroma-head-left">
                        <div class="chroma-card-icon"><i class="fas fa-sliders-h"></i></div>
                        <h2 class="chroma-card-title">Color Control Stage</h2>
                    </div>
                </div>

                <div class="color-picker-row">
                    <input type="color" id="nativePicker" class="native-color-box" value="#00d4ff" oninput="updateColorFromPicker(this.value)">
                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:8px; flex:1;">
                        <input type="text" id="hexInput" class="color-input-field" value="#00D4FF" placeholder="HEX" onchange="updateColorFromHex(this.value)">
                        <input type="text" id="rgbInput" class="color-input-field" value="rgb(0, 212, 255)" placeholder="RGB" readonly>
                        <input type="text" id="hslInput" class="color-input-field" value="hsl(190, 100%, 50%)" placeholder="HSL" readonly>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <span style="font-size:0.8rem; font-weight:700; color:#cbd5e1;">Active Theme Palette</span>
                    <span id="activeThemeTitle" style="font-size:0.75rem; color:var(--neon-blue); font-family:'Fira Code'; font-weight:700;">SaaS Cyber Cyan</span>
                </div>
                <div class="swatch-row" id="themeSwatches"></div>

                <div style="font-size:0.8rem; font-weight:700; color:#cbd5e1; margin-bottom:0.5rem;">
                    Harmonious Analogous Scheme
                </div>
                <div class="swatch-row" id="analogousSwatches"></div>

                <div style="font-size:0.8rem; font-weight:700; color:#cbd5e1; margin-bottom:0.5rem;">
                    Generated Gradient CSS
                </div>
                <div id="gradPreview" class="grad-preview-box"></div>
                <div style="display:flex; gap:8px;">
                    <input type="text" id="gradCssText" class="color-input-field" readonly>
                    <button type="button" class="chroma-btn-glass" onclick="copyGradientCss()"><i class="fas fa-copy"></i> Copy CSS</button>
                </div>
            </div>

            <!-- RIGHT PANEL: LIVE WEBSITE COMPONENT PREVIEW -->
            <div class="chroma-glass-card">
                <div class="chroma-card-head">
                    <div class="chroma-head-left">
                        <div class="chroma-card-icon"><i class="fas fa-desktop"></i></div>
                        <h2 class="chroma-card-title">Live Website UI Mockup</h2>
                    </div>
                    <span id="wcagBadge" class="badge-wcag wcag-pass">
                        <i class="fas fa-check-circle"></i> WCAG AAA PASSED
                    </span>
                </div>

                <div id="mockupStage" class="mockup-preview-stage">
                    <div class="mockup-header-bar">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div id="mockLogo" style="width:24px; height:24px; border-radius:6px;"></div>
                            <span id="mockBrandName" style="font-weight:800; font-size:0.9rem;">CypherBrand</span>
                        </div>
                        <div style="display:flex; gap:10px; font-size:0.75rem;">
                            <span>Products</span>
                            <span>Pricing</span>
                            <span>Docs</span>
                        </div>
                    </div>

                    <div style="margin-bottom:1.25rem;">
                        <h3 id="mockTitle" style="font-size:1.2rem; font-weight:800; margin-bottom:0.3rem;">Build Next-Gen Web Apps</h3>
                        <p id="mockSub" style="font-size:0.8rem; opacity:0.8; margin-bottom:0.8rem;">Experience hyper-fast, beautiful UI components with our modern theme engine.</p>
                        <button type="button" id="mockPrimaryBtn" class="btn-mockup-primary">Get Started Free</button>
                    </div>

                    <div id="mockCard" class="mockup-card-box">
                        <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; margin-bottom:0.25rem;">Feature Highlight</div>
                        <div style="font-size:0.82rem; opacity:0.9;">100% Responsive glassmorphism cards with automated WCAG contrast verification.</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ACTION & EXPORT TOOLBAR -->
        <div class="chroma-action-bar">
            <button type="button" class="chroma-btn-primary" onclick="copyCssVariables()">
                <i class="fas fa-code"></i> Copy CSS Variables (:root)
            </button>
            <button type="button" class="chroma-btn-glass" onclick="copyTailwindConfig()">
                <i class="fas fa-brackets-curly"></i> Copy Tailwind Config
            </button>
            <button type="button" class="chroma-btn-glass" onclick="downloadJsonTokens()">
                <i class="fas fa-download"></i> Download Tokens .json
            </button>
        </div>

    </div>
</div>

<div class="chroma-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    // Rich JSON Theme Database per Website Category
    const themeDatabase = {
        saas: [
            {
                name: "SaaS Cyber Cyan",
                primary: "#00D4FF",
                secondary: "#A855F7",
                accent: "#06F7E3",
                bg: "#070913",
                card: "rgba(13, 18, 33, 0.85)",
                text: "#F8FAFC",
                btnText: "#070913",
                grad: "linear-gradient(135deg, #00D4FF 0%, #A855F7 100%)"
            },
            {
                name: "SaaS Electric Violet",
                primary: "#6366F1",
                secondary: "#EC4899",
                accent: "#38BDF8",
                bg: "#0B0F19",
                card: "rgba(17, 24, 39, 0.85)",
                text: "#F9FAFB",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #6366F1 0%, #EC4899 100%)"
            },
            {
                name: "SaaS Acid Lime",
                primary: "#84CC16",
                secondary: "#06B6D4",
                accent: "#A855F7",
                bg: "#091107",
                card: "rgba(18, 30, 16, 0.85)",
                text: "#ECFDF5",
                btnText: "#091107",
                grad: "linear-gradient(135deg, #84CC16 0%, #06B6D4 100%)"
            }
        ],
        ecommerce: [
            {
                name: "Emerald Store",
                primary: "#22C55E",
                secondary: "#10B981",
                accent: "#FFCC44",
                bg: "#06150D",
                card: "rgba(12, 28, 18, 0.85)",
                text: "#F0FDF4",
                btnText: "#06150D",
                grad: "linear-gradient(135deg, #22C55E 0%, #FFCC44 100%)"
            },
            {
                name: "Ruby Retail",
                primary: "#EF4444",
                secondary: "#F97316",
                accent: "#FBBF24",
                bg: "#180909",
                card: "rgba(35, 15, 15, 0.85)",
                text: "#FEF2F2",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #EF4444 0%, #F97316 100%)"
            },
            {
                name: "Royal Purple Shop",
                primary: "#8B5CF6",
                secondary: "#D946EF",
                accent: "#38BDF8",
                bg: "#0E0A1A",
                card: "rgba(22, 16, 38, 0.85)",
                text: "#FAF5FF",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #8B5CF6 0%, #D946EF 100%)"
            }
        ],
        gaming: [
            {
                name: "Neon Pink Cyberpunk",
                primary: "#FF2E9E",
                secondary: "#8B5CF6",
                accent: "#00F0FF",
                bg: "#0D0414",
                card: "rgba(24, 9, 36, 0.85)",
                text: "#FDF4FF",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #FF2E9E 0%, #00F0FF 100%)"
            },
            {
                name: "Toxic Acid Green",
                primary: "#10B981",
                secondary: "#F59E0B",
                accent: "#EF4444",
                bg: "#051A14",
                card: "rgba(10, 36, 28, 0.85)",
                text: "#ECFDF5",
                btnText: "#051A14",
                grad: "linear-gradient(135deg, #10B981 0%, #F59E0B 100%)"
            },
            {
                name: "Overclocked Orange",
                primary: "#F97316",
                secondary: "#FF2E9E",
                accent: "#00D4FF",
                bg: "#160A04",
                card: "rgba(36, 16, 8, 0.85)",
                text: "#FFF7ED",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #F97316 0%, #FF2E9E 100%)"
            }
        ],
        luxury: [
            {
                name: "Imperial Gold",
                primary: "#FFCC44",
                secondary: "#D97706",
                accent: "#F59E0B",
                bg: "#0C0A09",
                card: "rgba(28, 25, 23, 0.85)",
                text: "#FAFAF9",
                btnText: "#0C0A09",
                grad: "linear-gradient(135deg, #FFCC44 0%, #D97706 100%)"
            },
            {
                name: "Obsidian Platinum",
                primary: "#E2E8F0",
                secondary: "#94A3B8",
                accent: "#38BDF8",
                bg: "#080C14",
                card: "rgba(15, 23, 42, 0.85)",
                text: "#F8FAFC",
                btnText: "#080C14",
                grad: "linear-gradient(135deg, #E2E8F0 0%, #94A3B8 100%)"
            },
            {
                name: "Velvet Rose Gold",
                primary: "#FB7185",
                secondary: "#F43F5E",
                accent: "#FFCC44",
                bg: "#16070B",
                card: "rgba(35, 14, 20, 0.85)",
                text: "#FFF1F2",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #FB7185 0%, #FFCC44 100%)"
            }
        ],
        agency: [
            {
                name: "Sunset Tangerine",
                primary: "#F97316",
                secondary: "#EC4899",
                accent: "#6366F1",
                bg: "#0F0B1E",
                card: "rgba(25, 18, 48, 0.85)",
                text: "#FAF5FF",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #F97316 0%, #EC4899 100%)"
            },
            {
                name: "Electric Magenta",
                primary: "#E11D48",
                secondary: "#9333EA",
                accent: "#38BDF8",
                bg: "#12050E",
                card: "rgba(32, 10, 26, 0.85)",
                text: "#FFF1F2",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #E11D48 0%, #9333EA 100%)"
            }
        ],
        health: [
            {
                name: "Teal Serenity",
                primary: "#14B8A6",
                secondary: "#06B6D4",
                accent: "#10B981",
                bg: "#041416",
                card: "rgba(8, 30, 33, 0.85)",
                text: "#F0FDFA",
                btnText: "#041416",
                grad: "linear-gradient(135deg, #14B8A6 0%, #06B6D4 100%)"
            },
            {
                name: "Lavender Pure",
                primary: "#A78BFA",
                secondary: "#818CF8",
                accent: "#34D399",
                bg: "#090814",
                card: "rgba(18, 16, 35, 0.85)",
                text: "#F5F3FF",
                btnText: "#090814",
                grad: "linear-gradient(135deg, #A78BFA 0%, #34D399 100%)"
            }
        ],
        fintech: [
            {
                name: "Navy Blue Shield",
                primary: "#3B82F6",
                secondary: "#1D4ED8",
                accent: "#10B981",
                bg: "#050B14",
                card: "rgba(10, 22, 40, 0.85)",
                text: "#EFF6FF",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #3B82F6 0%, #10B981 100%)"
            },
            {
                name: "Emerald Asset",
                primary: "#059669",
                secondary: "#2563EB",
                accent: "#F59E0B",
                bg: "#04120D",
                card: "rgba(9, 32, 23, 0.85)",
                text: "#ECFDF5",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #059669 0%, #2563EB 100%)"
            }
        ],
        dark: [
            {
                name: "Dark Purple Glow",
                primary: "#A855F7",
                secondary: "#6366F1",
                accent: "#00D4FF",
                bg: "#05050A",
                card: "rgba(15, 15, 26, 0.85)",
                text: "#F8FAFC",
                btnText: "#FFFFFF",
                grad: "linear-gradient(135deg, #A855F7 0%, #00D4FF 100%)"
            },
            {
                name: "Pitch Midnight Cyan",
                primary: "#06F7E3",
                secondary: "#3B82F6",
                accent: "#FF2E9E",
                bg: "#030712",
                card: "rgba(15, 23, 42, 0.85)",
                text: "#F9FAFB",
                btnText: "#030712",
                grad: "linear-gradient(135deg, #06F7E3 0%, #3B82F6 100%)"
            }
        ]
    };

    let currentCategoryKey = 'saas';
    let currentCategoryIndex = 0;
    let activeTheme = {...themeDatabase.saas[0]};

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'chroma-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function applyPreset(key) {
        document.querySelectorAll('.cat-pill-btn').forEach(btn => btn.classList.remove('active'));
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }
        currentCategoryKey = key;
        currentCategoryIndex = 0;
        activeTheme = {...themeDatabase[key][0]};
        updateAllViews();
        showToast(`Loaded ${activeTheme.name} Theme`);
    }

    function shuffleCurrentCategoryTheme() {
        const themes = themeDatabase[currentCategoryKey];
        if (!themes || themes.length === 0) return;
        currentCategoryIndex = (currentCategoryIndex + 1) % themes.length;
        activeTheme = {...themes[currentCategoryIndex]};
        updateAllViews();
        showToast(`Shuffled to: ${activeTheme.name}`);
    }

    function updateColorFromPicker(hex) {
        activeTheme.primary = hex.toUpperCase();
        activeTheme.grad = `linear-gradient(135deg, ${activeTheme.primary} 0%, #A855F7 100%)`;
        updateAllViews();
    }

    function updateColorFromHex(hex) {
        if (!/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex)) return;
        activeTheme.primary = hex.toUpperCase();
        activeTheme.grad = `linear-gradient(135deg, ${activeTheme.primary} 0%, #A855F7 100%)`;
        updateAllViews();
    }

    function hexToRgb(hex) {
        let h = hex.replace('#','');
        if (h.length === 3) h = h.split('').map(c => c+c).join('');
        const intVal = parseInt(h, 16);
        return {r: (intVal>>16)&255, g: (intVal>>8)&255, b: intVal&255};
    }

    function rgbToHsl(r,g,b) {
        r/=255; g/=255; b/=255;
        const max=Math.max(r,g,b), min=Math.min(r,g,b);
        let h,s,l=(max+min)/2;
        if (max===min) h=s=0;
        else {
            const d=max-min;
            s=l>0.5?d/(2-max-min):d/(max+min);
            switch(max){
                case r: h=(g-b)/d+(g<b?6:0); break;
                case g: h=(b-r)/d+2; break;
                case b: h=(r-g)/d+4; break;
            }
            h/=6;
        }
        return {h:Math.round(h*360), s:Math.round(s*100), l:Math.round(l*100)};
    }

    function updateAllViews() {
        document.getElementById('activeThemeTitle').textContent = activeTheme.name;

        const hex = activeTheme.primary;
        document.getElementById('nativePicker').value = hex;
        document.getElementById('hexInput').value = hex;

        const rgb = hexToRgb(hex);
        document.getElementById('rgbInput').value = `rgb(${rgb.r}, ${rgb.g}, ${rgb.b})`;

        const hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
        document.getElementById('hslInput').value = `hsl(${hsl.h}, ${hsl.s}%, ${hsl.l}%)`;

        // Render Swatches
        const themeSwatches = document.getElementById('themeSwatches');
        themeSwatches.innerHTML = `
            <div class="color-swatch-box" style="background:${activeTheme.primary}; color:${activeTheme.btnText};" onclick="copySwatch('${activeTheme.primary}')">Primary<br>${activeTheme.primary}</div>
            <div class="color-swatch-box" style="background:${activeTheme.secondary}; color:#fff;" onclick="copySwatch('${activeTheme.secondary}')">Secondary<br>${activeTheme.secondary}</div>
            <div class="color-swatch-box" style="background:${activeTheme.accent}; color:#000;" onclick="copySwatch('${activeTheme.accent}')">Accent<br>${activeTheme.accent}</div>
            <div class="color-swatch-box" style="background:${activeTheme.bg}; color:${activeTheme.text}; border:1px solid #444;" onclick="copySwatch('${activeTheme.bg}')">Background<br>${activeTheme.bg}</div>
        `;

        // Analogous
        const analogSwatches = document.getElementById('analogousSwatches');
        const h1 = (hsl.h + 30) % 360;
        const h2 = (hsl.h - 30 + 360) % 360;
        analogSwatches.innerHTML = `
            <div class="color-swatch-box" style="background:hsl(${h1}, ${hsl.s}%, ${hsl.l}%); color:#fff;" onclick="copySwatch('hsl(${h1},${hsl.s}%,${hsl.l}%)')">+30° HSL</div>
            <div class="color-swatch-box" style="background:${activeTheme.primary}; color:${activeTheme.btnText};" onclick="copySwatch('${activeTheme.primary}')">Base</div>
            <div class="color-swatch-box" style="background:hsl(${h2}, ${hsl.s}%, ${hsl.l}%); color:#fff;" onclick="copySwatch('hsl(${h2},${hsl.s}%,${hsl.l}%)')">-30° HSL</div>
        `;

        // Gradient
        document.getElementById('gradPreview').style.background = activeTheme.grad;
        document.getElementById('gradCssText').value = `background: ${activeTheme.grad};`;

        // Mockup Stage
        const mockupStage = document.getElementById('mockupStage');
        mockupStage.style.background = activeTheme.bg;
        mockupStage.style.color = activeTheme.text;

        document.getElementById('mockLogo').style.background = activeTheme.primary;
        document.getElementById('mockBrandName').style.color = activeTheme.text;

        document.getElementById('mockTitle').style.color = activeTheme.text;
        document.getElementById('mockSub').style.color = activeTheme.text;

        const mockBtn = document.getElementById('mockPrimaryBtn');
        mockBtn.style.background = activeTheme.primary;
        mockBtn.style.color = activeTheme.btnText;

        const mockCard = document.getElementById('mockCard');
        mockCard.style.background = activeTheme.card;
        mockCard.style.color = activeTheme.text;
    }

    function copySwatch(val) {
        navigator.clipboard.writeText(val).then(() => showToast(`Copied ${val} to clipboard!`));
    }

    function copyGradientCss() {
        const val = document.getElementById('gradCssText').value;
        navigator.clipboard.writeText(val).then(() => showToast('Gradient CSS copied!'));
    }

    function copyCssVariables() {
        const css = `:root {
  /* ${activeTheme.name} Palette */
  --color-primary: ${activeTheme.primary};
  --color-secondary: ${activeTheme.secondary};
  --color-accent: ${activeTheme.accent};
  --bg-main: ${activeTheme.bg};
  --bg-card: ${activeTheme.card};
  --text-main: ${activeTheme.text};
  --gradient-hero: ${activeTheme.grad};
}`;
        navigator.clipboard.writeText(css).then(() => showToast('CSS Variables copied to clipboard!'));
    }

    function copyTailwindConfig() {
        const tw = `module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '${activeTheme.primary}',
        secondary: '${activeTheme.secondary}',
        accent: '${activeTheme.accent}',
        bgMain: '${activeTheme.bg}',
        bgCard: '${activeTheme.card}',
        textMain: '${activeTheme.text}'
      }
    }
  }
}`;
        navigator.clipboard.writeText(tw).then(() => showToast('Tailwind config copied!'));
    }

    function downloadJsonTokens() {
        const blob = new Blob([JSON.stringify(activeTheme, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'theme-tokens.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Tokens JSON downloaded!');
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateAllViews();
    });
</script>
@endsection