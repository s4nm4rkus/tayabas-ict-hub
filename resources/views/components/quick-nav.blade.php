{{--
    ┌─────────────────────────────────────────────────────────────────────────┐
    │  COMPONENT: resources/views/components/quick-nav.blade.php             │
    │                                                                         │
    │  Include on every layout's <body> — just before </body>:               │
    │  <x-quick-nav />                                                        │
    │                                                                         │
    │  Pass current page to hide irrelevant links:                           │
    │  <x-quick-nav :current="'ict'" />                                      │
    │  <x-quick-nav :current="'personnel'" />                                │
    │  <x-quick-nav :current="'home'" />                                     │
    └─────────────────────────────────────────────────────────────────────────┘
--}}

@props(['current' => null])

@php
    $isAuth = auth()->check();
    $user = auth()->user();

    /*
     * Each nav item:
     *  key       — matches $current to auto-hide when already on that page
     *  label     — shown on hover tooltip
     *  icon      — bootstrap icon class
     *  href      — route or url
     *  auth      — true = requires login (will redirect to login page if guest)
     *  color     — accent color for this item's ring + icon
 *  external  — open in new tab
 */
$items = [
    [
        'key' => 'home',
        'label' => 'SDO Home',
        'icon' => 'bi-house-fill',
        'href' => route('home'),
        'auth' => false,
        'color' => '#1A4A8A',
        'external' => false,
    ],
    [
        'key' => 'ict',
        'label' => 'ICT Unit',
        'icon' => 'bi-cpu-fill',
        'href' => route('unit.ict'),
        'auth' => false,
        'color' => '#2563eb',
        'external' => false,
    ],
    [
        'key' => 'ict-ticket',
        'label' => 'Submit ICT Ticket',
        'icon' => 'bi-ticket-perforated-fill',
        'href' => route('ict.forms'),
        'auth' => false,
        'color' => '#4d8ef8',
        'external' => false,
    ],
    [
        'key' => 'personnel',
        'label' => 'Personnel Unit',
        'icon' => 'bi-people-fill',
        'href' => route('unit.personnel'),
        'auth' => false,
        'color' => '#059669',
        'external' => false,
    ],
    [
        'key' => 'leave',
        'label' => 'Apply for Leave',
        'icon' => 'bi-calendar-check-fill',
        'href' => $isAuth ? route('employee.leave.create') : route('login'),
        'auth' => true,
        'color' => '#D97706',
        'external' => false,
    ],
    [
        'key' => 'top',
        'label' => 'Back to Top',
        'icon' => 'bi-arrow-up',
        'href' => '#',
        'auth' => false,
        'color' => '#6B7A90',
        'external' => false,
        'action' => 'scrollTop',
    ],
    // Logout — only injected when user is logged in
    ...$isAuth
        ? [
            [
                'key' => 'logout',
                'label' => 'Logout',
                'icon' => 'bi-box-arrow-right',
                'href' => '#',
                'auth' => true,
                'color' => '#dc2626',
                'external' => false,
                'action' => 'logout',
            ],
        ]
        : [],
];

// Filter out current page item
$items = array_filter($items, fn($item) => $item['key'] !== $current);
@endphp

{{-- Hidden logout form — submitted by JS --}}
@auth
    <form id="qn-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
@endauth

{{-- ── FLOATING SPEED DIAL ── --}}
<div id="qn-root" aria-label="Quick Navigation">

    {{-- Backdrop blur overlay (visible when open) --}}
    <div id="qn-backdrop" onclick="qnClose()"></div>

    {{-- Item list (renders above the trigger) --}}
    <div id="qn-menu" role="menu">
        @foreach (array_values($items) as $idx => $item)
            @php
                $href = $item['href'];
                $isLogin = !$isAuth && ($item['auth'] ?? false);
                $target = $item['external'] ?? false ? '_blank' : '_self';
                $action = $item['action'] ?? null;
            @endphp

            <div class="qn-item" style="--i:{{ $idx }};--c:{{ $item['color'] }};" role="menuitem">

                {{-- Tooltip label --}}
                <span class="qn-label">
                    @if ($isLogin)
                        <i class="bi bi-lock-fill" style="font-size:9px;opacity:.6;margin-right:3px;"></i>
                    @endif
                    {{ $item['label'] }}
                </span>

                {{-- Button --}}
                <a href="{{ $href }}" target="{{ $target }}" class="qn-btn"
                    @if ($action === 'scrollTop') onclick="event.preventDefault();window.scrollTo({top:0,behavior:'smooth'});qnClose();"
                   @elseif($action === 'logout')  onclick="event.preventDefault();qnLogout();" @endif
                    @if ($isLogin && $action !== 'logout') title="Login required" @endif aria-label="{{ $item['label'] }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    @if ($isLogin)
                        <span class="qn-lock-dot"><i class="bi bi-lock-fill"></i></span>
                    @endif
                </a>

            </div>
        @endforeach
    </div>

    {{-- ── Trigger button ── --}}
    <button id="qn-trigger" onclick="qnToggle()" aria-expanded="false" aria-controls="qn-menu"
        aria-label="Quick navigation menu">
        <span id="qn-trigger-icon"><i class="bi bi-grid-fill"></i></span>
        <span id="qn-trigger-close"><i class="bi bi-x-lg"></i></span>
        <span class="qn-trigger-pulse"></span>
    </button>

</div>

<style>
    /* ════════════════════════════════════
       QUICK NAV — SPEED DIAL
       ════════════════════════════════════ */
    #qn-root {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9000;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0;
    }

    @media(max-width:600px) {
        #qn-root {
            bottom: 20px;
            right: 16px;
        }
    }

    /* ── Backdrop ── */
    #qn-backdrop {
        position: fixed;
        inset: 0;
        z-index: -1;
        background: rgba(11, 31, 58, 0);
        backdrop-filter: blur(0px);
        -webkit-backdrop-filter: blur(0px);
        pointer-events: none;
        transition: background .3s ease, backdrop-filter .3s ease;
    }

    #qn-root.open #qn-backdrop {
        background: rgba(11, 31, 58, 0.28);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        pointer-events: all;
    }

    /* ── Trigger ── */
    #qn-trigger {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1A4A8A, #0B1F3A);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        box-shadow: 0 8px 28px rgba(11, 31, 58, 0.35), 0 2px 8px rgba(11, 31, 58, 0.2);
        transition: transform .25s cubic-bezier(0.34, 1.56, 0.64, 1),
            box-shadow .25s ease,
            border-radius .25s ease;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        margin-top: 10px;
        overflow: hidden;
    }

    #qn-trigger:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 36px rgba(11, 31, 58, 0.4), 0 4px 12px rgba(11, 31, 58, 0.25);
    }

    #qn-root.open #qn-trigger {
        border-radius: 14px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
    }

    /* Icon swap */
    #qn-trigger-icon,
    #qn-trigger-close {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity .2s ease, transform .2s ease;
    }

    #qn-trigger-close {
        opacity: 0;
        transform: rotate(-90deg) scale(0.6);
    }

    #qn-root.open #qn-trigger-icon {
        opacity: 0;
        transform: rotate(90deg) scale(0.6);
    }

    #qn-root.open #qn-trigger-close {
        opacity: 1;
        transform: rotate(0deg) scale(1);
    }

    /* Pulse ring on trigger (subtle, not open) */
    .qn-trigger-pulse {
        position: absolute;
        inset: -3px;
        border-radius: 18px;
        border: 2px solid rgba(26, 74, 138, 0.4);
        animation: qn-pulse 2.5s ease-in-out infinite;
        pointer-events: none;
    }

    #qn-root.open .qn-trigger-pulse {
        animation: none;
        opacity: 0;
    }

    @keyframes qn-pulse {

        0%,
        100% {
            opacity: 0.6;
            transform: scale(1);
        }

        50% {
            opacity: 0;
            transform: scale(1.18);
        }
    }

    /* ── Menu ── */
    #qn-menu {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        pointer-events: none;
        padding-bottom: 2px;
    }

    /* ── Item ── */
    .qn-item {
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(16px) scale(0.88);
        transition:
            opacity .22s ease,
            transform .28s cubic-bezier(0.34, 1.56, 0.64, 1);
        /* stagger: each item delays by its index */
        transition-delay: calc(var(--i) * 0.045s);
        pointer-events: none;
    }

    #qn-root.open .qn-item {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: all;
        /* reverse stagger when opening: first item appears first */
        transition-delay: calc((5 - var(--i)) * 0.04s);
    }

    /* ── Tooltip label ── */
    .qn-label {
        background: rgba(11, 31, 58, 0.90);
        color: #fff;
        font-size: 11.5px;
        font-weight: 600;
        font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
        padding: 5px 12px;
        border-radius: 8px;
        white-space: nowrap;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-shadow: 0 4px 14px rgba(11, 31, 58, 0.25);
        letter-spacing: 0.01em;
        pointer-events: none;
        user-select: none;
    }

    /* ── Action button ── */
    .qn-btn {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: #fff;
        border: 2px solid rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        color: var(--c);
        text-decoration: none;
        flex-shrink: 0;
        position: relative;
        box-shadow:
            0 4px 16px rgba(11, 31, 58, 0.14),
            0 1px 4px rgba(11, 31, 58, 0.08),
            inset 0 0 0 1.5px var(--c, rgba(26, 74, 138, 0.15));
        transition:
            transform .2s cubic-bezier(0.34, 1.56, 0.64, 1),
            box-shadow .2s ease,
            background .2s ease;
        overflow: hidden;
    }

    .qn-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--c, #1A4A8A);
        opacity: 0;
        transition: opacity .2s ease;
        border-radius: inherit;
    }

    .qn-btn:hover {
        transform: scale(1.12);
        box-shadow:
            0 8px 24px rgba(11, 31, 58, 0.2),
            0 2px 6px rgba(11, 31, 58, 0.12),
            inset 0 0 0 2px var(--c, #1A4A8A);
    }

    .qn-btn:hover::before {
        opacity: 0.10;
    }

    .qn-btn i {
        position: relative;
        z-index: 1;
    }

    /* Lock dot on auth-required buttons */
    .qn-lock-dot {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #D97706;
        border: 1.5px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 7px;
        color: #fff;
        z-index: 2;
    }

    /* ── Reduced motion ── */
    @media (prefers-reduced-motion: reduce) {

        .qn-item,
        #qn-trigger,
        #qn-trigger-icon,
        #qn-trigger-close {
            transition: none !important;
        }

        .qn-trigger-pulse {
            animation: none !important;
        }
    }
</style>

<script>
    (function() {
        const root = document.getElementById('qn-root');
        let isOpen = false;

        function qnOpen() {
            isOpen = true;
            root.classList.add('open');
            document.getElementById('qn-trigger').setAttribute('aria-expanded', 'true');
            document.addEventListener('keydown', qnEscHandler);
        }

        function qnClose() {
            isOpen = false;
            root.classList.remove('open');
            document.getElementById('qn-trigger').setAttribute('aria-expanded', 'false');
            document.removeEventListener('keydown', qnEscHandler);
        }

        window.qnToggle = function() {
            isOpen ? qnClose() : qnOpen();
        };
        window.qnClose = qnClose;
        window.qnLogout = function() {
            qnClose();
            const form = document.getElementById('qn-logout-form');
            if (form) form.submit();
        };

        function qnEscHandler(e) {
            if (e.key === 'Escape') qnClose();
        }

        /* Close on scroll (optional UX polish) */
        let lastScroll = window.scrollY;
        window.addEventListener('scroll', function() {
            const delta = Math.abs(window.scrollY - lastScroll);
            if (delta > 80 && isOpen) qnClose();
            lastScroll = window.scrollY;
        }, {
            passive: true
        });
    })();
</script>
