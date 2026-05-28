{{--
    ┌─────────────────────────────────────────────────────────────────────────┐
    │  LAYOUT: resources/views/layouts/ict-tickets-admin.blade.php           │
    │                                                                         │
    │  Usage in every child view:                                             │
    │  @extends('layouts.ict-tickets-admin')                                   │
    │                                                                         │
    │  Required sections:                                                     │
    │  @section('title', 'Page Title')          ← <title> tag          │
    │  @section('page-title', 'Page Title')          ← topbar heading       │
    │  @section('page-desc', 'Short description')   ← topbar sub-text      │
    │  @section('active-nav', 'ta-tickets')          ← highlights sidebar   │
    │  @section('content') … @endsection             ← page body            │
    │                                                                         │
    │  Optional sections:                                                     │
    │  @section('head')       ← extra <head> styles / meta                  │
    │  @section('actions')    ← topbar right-side buttons                   │
    │  @section('scripts')    ← JS before </body>                           │
    │                                                                         │
    │  active-nav values (one per sidebar link):                             │
    │  'dashboard'   'ta-tickets'   'email-requests'                         │
    │  'dts-requests'  'helpdesk'   'ict-unit'  'main-dashboard'            │
    └─────────────────────────────────────────────────────────────────────────┘
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin') — ICT Unit · SDO Tayabas City</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        /* ═══════════════════════════════════════
           DESIGN TOKENS
           ═══════════════════════════════════════ */
        :root {
            --sidebar-w: 240px;
            --sidebar-w-col: 64px;
            --topbar-h: 60px;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-xs: 6px;

            /* Brand palette (unchanged from ICT pages) */
            --navy: #0B1F3A;
            --navy-2: #102647;
            --blue: #1A4A8A;
            --accent: #2563eb;
            --accent-lt: #4d8ef8;
            --accent-glow: rgba(37, 99, 235, .18);

            --surface: #F5F7FB;
            --white: #FFFFFF;
            --muted: #6B7A90;
            --border: rgba(26, 74, 138, .11);

            --success: #059669;
            --danger: #dc2626;
            --warning: #d97706;

            /* Sidebar-specific tokens */
            --s-text: rgba(255, 255, 255, .55);
            --s-text-hover: rgba(255, 255, 255, .88);
            --s-text-active: #fff;
            --s-icon: rgba(255, 255, 255, .35);
            --s-icon-hover: rgba(255, 255, 255, .70);
            --s-icon-active: var(--accent-lt);
            --s-sep: rgba(255, 255, 255, .06);
            --s-hover-bg: rgba(255, 255, 255, .055);
            --s-active-bg: rgba(37, 99, 235, .14);

            --t-sb: .26s cubic-bezier(.4, 0, .2, 1);
            --t-fast: .15s ease;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 15px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            color: var(--navy);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════
           SIDEBAR
           ═══════════════════════════════════════ */
        #sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background: var(--navy);
            z-index: 300;
            display: flex;
            flex-direction: column;
            transition: width var(--t-sb), transform var(--t-sb);
            overflow: hidden;
            box-shadow: inset -1px 0 0 var(--s-sep), 4px 0 24px rgba(0, 0, 0, .12);
        }

        body.sb-collapsed #sidebar {
            width: var(--sidebar-w-col);
        }

        @media(max-width:900px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }

            body.mob-open #sidebar {
                transform: translateX(0);
            }
        }

        /* ── Brand ── */
        .sb-brand {
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 16px;
            border-bottom: 1px solid var(--s-sep);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sb-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: rgba(77, 142, 248, .16);
            border: 1px solid rgba(77, 142, 248, .28);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--accent-lt);
            flex-shrink: 0;
            transition: background var(--t-fast);
        }

        .sb-brand:hover .sb-brand-icon {
            background: rgba(77, 142, 248, .24);
        }

        .sb-brand-text {
            overflow: hidden;
            transition: opacity var(--t-sb), max-width var(--t-sb);
            max-width: 160px;
        }

        .sb-brand-text strong {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            line-height: 1.35;
            white-space: nowrap;
        }

        .sb-brand-text span {
            font-size: 10px;
            color: rgba(255, 255, 255, .3);
            white-space: nowrap;
        }

        body.sb-collapsed .sb-brand-text {
            opacity: 0;
            max-width: 0;
        }

        /* ── Collapse toggle ── */
        .sb-toggle {
            position: absolute;
            top: 19px;
            right: -11px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--navy-2);
            border: 1.5px solid rgba(255, 255, 255, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background var(--t-fast), border-color var(--t-fast);
        }

        .sb-toggle i {
            font-size: 9px;
            color: rgba(255, 255, 255, .6);
            transition: transform var(--t-sb);
            display: block;
        }

        .sb-toggle:hover {
            background: var(--accent);
            border-color: var(--accent);
        }

        .sb-toggle:hover i {
            color: #fff;
        }

        body.sb-collapsed .sb-toggle i {
            transform: rotate(180deg);
        }

        @media(max-width:900px) {
            .sb-toggle {
                display: none;
            }
        }

        /* ── Nav scroll area ── */
        .sb-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 0 14px;
            scrollbar-width: none;
        }

        .sb-nav::-webkit-scrollbar {
            display: none;
        }

        /* ── Section label ── */
        .sb-section {
            margin-bottom: 2px;
        }

        .sb-section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .22);
            padding: 10px 18px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--t-sb), max-height var(--t-sb), padding var(--t-sb);
            max-height: 32px;
        }

        body.sb-collapsed .sb-section-label {
            opacity: 0;
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        /* Separator line (visible only when collapsed) */
        .sb-sep {
            height: 1px;
            background: var(--s-sep);
            margin: 6px 14px;
            opacity: 0;
            transition: opacity var(--t-sb);
        }

        body.sb-collapsed .sb-sep {
            opacity: 1;
        }

        /* ── Nav item / link ── */
        .sb-item {
            position: relative;
        }

        .sb-link {
            display: flex;
            align-items: center;
            margin: 1px 8px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            overflow: hidden;
            height: 40px;
            transition: background var(--t-fast);
            cursor: pointer;
            position: relative;
        }

        .sb-link:hover {
            background: var(--s-hover-bg);
        }

        .sb-link.active {
            background: var(--s-active-bg);
        }

        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 2.5px;
            border-radius: 0 2px 2px 0;
            background: var(--accent-lt);
        }

        /* ── Icon cell (fixed width = collapsed width - padding) ── */
        .sb-icon {
            width: calc(var(--sidebar-w-col) - 16px);
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
            color: var(--s-icon);
            transition: color var(--t-fast);
        }

        .sb-link:hover .sb-icon {
            color: var(--s-icon-hover);
        }

        .sb-link.active .sb-icon {
            color: var(--s-icon-active);
        }

        /* ── Label ── */
        .sb-label {
            flex: 1;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--s-text);
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--t-sb), max-width var(--t-sb), color var(--t-fast);
            max-width: 200px;
        }

        .sb-link:hover .sb-label {
            color: var(--s-text-hover);
        }

        .sb-link.active .sb-label {
            color: var(--s-text-active);
            font-weight: 600;
        }

        body.sb-collapsed .sb-label {
            opacity: 0;
            max-width: 0;
        }

        /* ── Badge ── */
        .sb-badge {
            font-size: 9.5px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, .22);
            flex-shrink: 0;
            margin-right: 10px;
            transition: opacity var(--t-sb), max-width var(--t-sb);
            max-width: 40px;
            overflow: hidden;
        }

        .sb-badge.blue {
            background: rgba(37, 99, 235, .15);
            color: var(--accent-lt);
            border-color: rgba(37, 99, 235, .25);
        }

        .sb-badge.green {
            background: rgba(5, 150, 105, .12);
            color: #34d399;
            border-color: rgba(5, 150, 105, .22);
        }

        body.sb-collapsed .sb-badge {
            opacity: 0;
            max-width: 0;
            margin-right: 0;
        }

        /* ── Tooltip (collapsed only) ── */
        .sb-item::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(var(--sidebar-w-col) + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--navy-2);
            color: #fff;
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 7px;
            white-space: nowrap;
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, .1);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .28);
            opacity: 0;
            transition: opacity .12s ease;
            z-index: 400;
        }

        body.sb-collapsed .sb-item:hover::after {
            opacity: 1;
        }

        /* ── Disabled placeholder links ── */
        .sb-link-disabled {
            opacity: .38;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ── User footer ── */
        .sb-user {
            display: flex;
            align-items: center;
            height: 56px;
            padding: 0 8px;
            border-top: 1px solid var(--s-sep);
            flex-shrink: 0;
            overflow: hidden;
        }

        .sb-user-avatar {
            width: calc(var(--sidebar-w-col) - 16px);
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sb-user-avatar-inner {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-lt), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .14);
        }

        .sb-user-info {
            flex: 1;
            overflow: hidden;
            transition: opacity var(--t-sb), max-width var(--t-sb);
            max-width: 200px;
        }

        .sb-user-name {
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-user-role {
            font-size: 10px;
            color: rgba(255, 255, 255, .32);
        }

        body.sb-collapsed .sb-user-info {
            opacity: 0;
            max-width: 0;
        }

        /* ═══════════════════════════════════════
           MOBILE OVERLAY
           ═══════════════════════════════════════ */
        #sb-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(11, 31, 58, .5);
            backdrop-filter: blur(4px);
            z-index: 299;
        }

        body.mob-open #sb-overlay {
            display: block;
        }

        /* ═══════════════════════════════════════
           TOPBAR
           ═══════════════════════════════════════ */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 200;
            gap: 16px;
            transition: left var(--t-sb);
        }

        body.sb-collapsed #topbar {
            left: var(--sidebar-w-col);
        }

        @media(max-width:900px) {
            #topbar {
                left: 0;
            }
        }

        .tb-hamburger {
            display: none;
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            background: rgba(26, 74, 138, .06);
            border: 1px solid var(--border);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            color: var(--navy);
            flex-shrink: 0;
            transition: background var(--t-fast);
        }

        .tb-hamburger:hover {
            background: rgba(26, 74, 138, .10);
        }

        @media(max-width:900px) {
            .tb-hamburger {
                display: flex;
            }
        }

        .tb-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .tb-vline {
            width: 1px;
            height: 22px;
            background: var(--border);
            flex-shrink: 0;
        }

        .tb-title {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.02em;
            white-space: nowrap;
        }

        .tb-desc {
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
        }

        .tb-right {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
        }

        .tb-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            background: rgba(26, 74, 138, .05);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: var(--muted);
            text-decoration: none;
            position: relative;
            transition: all var(--t-fast);
        }

        .tb-icon-btn:hover {
            background: rgba(26, 74, 138, .10);
            color: var(--navy);
        }

        .tb-notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--danger);
            border: 1.5px solid #fff;
        }

        .tb-user {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 4px 10px 4px 5px;
            border-radius: 99px;
            background: rgba(26, 74, 138, .05);
            border: 1px solid var(--border);
            cursor: pointer;
            text-decoration: none;
            transition: background var(--t-fast);
        }

        .tb-user:hover {
            background: rgba(26, 74, 138, .10);
        }

        .tb-user-av {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-lt), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
        }

        .tb-user-name {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--navy);
            white-space: nowrap;
        }

        @media(max-width:560px) {

            .tb-user-name,
            .tb-desc {
                display: none;
            }
        }

        /* ═══════════════════════════════════════
           MAIN CONTENT
           ═══════════════════════════════════════ */
        #main {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left var(--t-sb);
        }

        body.sb-collapsed #main {
            margin-left: var(--sidebar-w-col);
        }

        @media(max-width:900px) {
            #main {
                margin-left: 0;
            }
        }

        .page-content {
            padding: 28px 28px 72px;
        }

        @media(max-width:640px) {
            .page-content {
                padding: 18px 16px 72px;
            }
        }

        /* ═══════════════════════════════════════
           SHARED COMPONENT STYLES
           ═══════════════════════════════════════ */

        /* Page header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .page-header h1 {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.02em;
            margin-bottom: 3px;
        }

        .page-header p {
            font-size: 12.5px;
            color: var(--muted);
        }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Card */
        .admin-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(11, 31, 58, .05);
        }

        .admin-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 11px;
            background: rgba(26, 74, 138, .025);
        }

        .admin-card-header-icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: rgba(37, 99, 235, .08);
            border: 1px solid rgba(37, 99, 235, .14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .admin-card-header-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }

        .admin-card-header-sub {
            font-size: 10.5px;
            color: var(--muted);
            margin-top: 1px;
        }

        .admin-card-body {
            padding: 20px;
        }

        /* Stat grid */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            transition: all .22s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(11, 31, 58, .08);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0 0 auto;
            height: 2px;
            border-radius: var(--radius) var(--radius) 0 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            margin-bottom: 13px;
            border: 1px solid;
        }

        .stat-num {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        /* Table */
        .admin-table-wrap {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(11, 31, 58, .04);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table thead tr {
            background: rgba(26, 74, 138, .035);
            border-bottom: 1px solid var(--border);
        }

        .admin-table th {
            padding: 11px 16px;
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .09em;
            white-space: nowrap;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid rgba(26, 74, 138, .055);
            transition: background .12s;
        }

        .admin-table tbody tr:last-child {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: rgba(244, 247, 251, .8);
        }

        .admin-table td {
            padding: 12px 16px;
            font-size: 13px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 99px;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 4.5px;
            height: 4.5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-pending {
            background: rgba(245, 158, 11, .09);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, .2);
        }

        .badge-pending::before {
            background: #D97706;
        }

        .badge-progress {
            background: rgba(37, 99, 235, .08);
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, .18);
        }

        .badge-progress::before {
            background: #2563eb;
        }

        .badge-resolved {
            background: rgba(5, 150, 105, .07);
            color: #059669;
            border: 1px solid rgba(5, 150, 105, .18);
        }

        .badge-resolved::before {
            background: #059669;
        }

        .badge-closed {
            background: rgba(107, 122, 144, .09);
            color: #6B7A90;
            border: 1px solid rgba(107, 122, 144, .18);
        }

        .badge-closed::before {
            background: #6B7A90;
        }

        .badge-cancelled {
            background: rgba(220, 38, 38, .07);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, .18);
        }

        .badge-cancelled::before {
            background: #dc2626;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: all .18s;
            border: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-lt), var(--accent));
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(37, 99, 235, .35);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--navy);
            border: 1.5px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: rgba(26, 74, 138, .28);
            background: var(--surface);
        }

        .btn-danger {
            background: rgba(220, 38, 38, .07);
            color: var(--danger);
            border: 1.5px solid rgba(220, 38, 38, .18);
        }

        .btn-danger:hover {
            background: rgba(220, 38, 38, .13);
            border-color: rgba(220, 38, 38, .32);
        }

        .btn-sm {
            padding: 5px 11px;
            font-size: 11px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            justify-content: center;
        }

        /* Form fields */
        .f-group {
            margin-bottom: 15px;
        }

        .f-group label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: 6px;
        }

        .f-group input,
        .f-group select,
        .f-group textarea {
            width: 100%;
            padding: 9px 13px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-family: inherit;
            color: var(--navy);
            background: #FAFBFD;
            transition: all .18s;
            outline: none;
            -webkit-appearance: none;
        }

        .f-group input:focus,
        .f-group select:focus,
        .f-group textarea:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .09);
        }

        .f-group textarea {
            resize: vertical;
            min-height: 96px;
        }

        /* Filter bar */
        .filter-bar {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 8px 13px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            font-family: inherit;
            color: var(--navy);
            background: var(--white);
            outline: none;
            transition: border-color .18s;
            -webkit-appearance: none;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            border-color: var(--accent);
        }

        .filter-bar input {
            flex: 1;
            min-width: 180px;
        }

        /* Tabs */
        .form-tabs {
            display: flex;
            border-bottom: 2px solid var(--border);
            margin-bottom: 22px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .form-tabs::-webkit-scrollbar {
            display: none;
        }

        .form-tab {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all .18s;
            cursor: pointer;
        }

        .form-tab:hover {
            color: var(--navy);
        }

        .form-tab.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        .form-tab-count {
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 99px;
            background: rgba(37, 99, 235, .1);
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, .18);
        }

        .form-tab.active .form-tab-count {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* Empty state */
        .empty-state {
            padding: 52px 24px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 38px;
            display: block;
            margin-bottom: 12px;
            opacity: .28;
        }

        .empty-state p {
            font-size: 13px;
            line-height: 1.7;
        }

        /* Pagination */
        .pagination-wrap {
            margin-top: 18px;
        }

        /* Breadcrumb */
        .admin-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .admin-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .admin-breadcrumb a:hover {
            color: var(--accent);
        }

        .admin-breadcrumb-sep {
            color: rgba(26, 74, 138, .2);
            font-size: 10px;
        }

        .admin-breadcrumb span {
            color: var(--navy);
            font-weight: 600;
        }

        /* Reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .5s ease, transform .5s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .d1 {
            transition-delay: .06s;
        }

        .d2 {
            transition-delay: .13s;
        }

        .d3 {
            transition-delay: .20s;
        }

        .d4 {
            transition-delay: .27s;
        }

        /* Toast */
        .admin-toast {
            position: fixed;
            bottom: 22px;
            right: 22px;
            z-index: 9999;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 11px;
            box-shadow: 0 12px 32px rgba(11, 31, 58, .13);
            transform: translateY(120%);
            transition: transform .38s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-width: 320px;
            pointer-events: none;
        }

        .admin-toast.show {
            transform: translateY(0);
            pointer-events: all;
        }

        .admin-toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .admin-toast-icon.success {
            background: rgba(5, 150, 105, .09);
            color: var(--success);
        }

        .admin-toast-icon.error {
            background: rgba(220, 38, 38, .09);
            color: var(--danger);
        }

        .admin-toast-icon.warning {
            background: rgba(217, 119, 6, .09);
            color: var(--warning);
        }

        .admin-toast-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 1px;
        }

        .admin-toast-msg {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.5;
        }

        .admin-toast-close {
            margin-left: auto;
            cursor: pointer;
            color: var(--muted);
            font-size: 13px;
            padding: 2px;
            flex-shrink: 0;
        }

        .admin-toast-close:hover {
            color: var(--navy);
        }

        /* Confirm modal */
        .admin-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 800;
            background: rgba(11, 31, 58, .48);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .22s;
            padding: 20px;
        }

        .admin-modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .admin-modal {
            background: var(--white);
            border-radius: 16px;
            padding: 28px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            transform: scale(.94) translateY(12px);
            transition: transform .28s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 20px 56px rgba(11, 31, 58, .18);
        }

        .admin-modal-overlay.open .admin-modal {
            transform: scale(1) translateY(0);
        }

        .admin-modal-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 14px;
        }

        .admin-modal-icon.danger {
            background: rgba(220, 38, 38, .09);
            color: var(--danger);
            border: 1px solid rgba(220, 38, 38, .18);
        }

        .admin-modal-icon.info {
            background: rgba(37, 99, 235, .09);
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, .18);
        }

        .admin-modal-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 7px;
        }

        .admin-modal-desc {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .admin-modal-actions {
            display: flex;
            gap: 9px;
        }

        .admin-modal-actions .btn {
            flex: 1;
            justify-content: center;
        }
    </style>
    @yield('head')
</head>

<body>

    <div id="sb-overlay" onclick="closeMob()"></div>

    {{-- ════════════════════════════════════
         SIDEBAR
         ════════════════════════════════════ --}}
    <aside id="sidebar">

        <button class="sb-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>

        {{-- Brand --}}
        <a href="{{ route('unit.ict') }}" class="sb-brand">
            <div class="sb-brand-icon"><i class="bi bi-cpu-fill"></i></div>
            <div class="sb-brand-text">
                <strong>ICT Admin</strong>
                <span>SDO Tayabas City</span>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="sb-nav">

            @php
                /* Safely count pending tickets — gracefully handles missing models */
                $taPending = class_exists(\App\Models\IctTicket::class)
                    ? \App\Models\IctTicket::where('status', 'Pending')->count()
                    : 0;
                $emailPending = class_exists(\App\Models\IctEmailRequest::class)
                    ? \App\Models\IctEmailRequest::where('status', 'Pending')->count()
                    : 0;
                $dtsPending = class_exists(\App\Models\IctDtsRequest::class)
                    ? \App\Models\IctDtsRequest::where('status', 'Pending')->count()
                    : 0;
                $helpdeskPending = class_exists(\App\Models\IctHelpdeskRequest::class)
                    ? \App\Models\IctHelpdeskRequest::where('status', 'Pending')->count()
                    : 0;
                $totalPending = $taPending + $emailPending + $dtsPending + $helpdeskPending;
                $activeNav = View::yieldContent('active-nav');
            @endphp

            {{-- ── Overview ── --}}
            <div class="sb-section">
                <div class="sb-section-label">Overview</div>

                <div class="sb-item" data-tip="Dashboard">
                    <a href="{{ route('ict.admin.dashboard') }}"
                        class="sb-link {{ $activeNav === 'dashboard' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-grid-fill"></i></div>
                        <span class="sb-label">Dashboard</span>
                        @if ($totalPending > 0)
                            <span class="sb-badge">{{ $totalPending }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="sb-sep"></div>

            {{-- ── ICT Request Forms ── --}}
            <div class="sb-section">
                <div class="sb-section-label">Request Forms</div>

                {{-- Technical Assistance --}}
                <div class="sb-item" data-tip="Technical Assistance">
                    <a href="{{ route('ict.admin.ta-requests.index') }}"
                        class="sb-link {{ $activeNav === 'ta-tickets' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-tools"></i></div>
                        <span class="sb-label">Technical Assistance</span>
                        @if ($taPending > 0)
                            <span class="sb-badge">{{ $taPending }}</span>
                        @endif
                    </a>
                </div>

                {{-- Email Requests --}}
                <div class="sb-item" data-tip="Email Requests">
                    <a href="{{ route('ict.admin.email-requests.index') }}"
                        class="sb-link {{ $activeNav === 'email-requests' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-envelope-fill"></i></div>
                        <span class="sb-label">Email Requests</span>
                        @if ($emailPending > 0)
                            <span class="sb-badge">{{ $emailPending }}</span>
                        @endif
                    </a>
                </div>

                {{-- DTS Requests (coming soon) --}}
                <div class="sb-item" data-tip="DTS Requests">
                    <a href="{{ route('ict.admin.dts-requests.index') }}"
                        class="sb-link {{ $activeNav === 'dts-requests' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <span class="sb-label">DTS Requests</span>
                        {{-- <span class="sb-badge blue">Soon</span> --}}
                        @if ($dtsPending > 0)
                            <span class="sb-badge">{{ $dtsPending }}</span>
                        @endif
                    </a>
                </div>

                {{-- Help Desk (coming soon) --}}
                <div class="sb-item" data-tip="Help Desk — Coming Soon">
                    <a href="{{ route('ict.admin.helpdesk-requests.index') }}"
                        class="sb-link {{ $activeNav === 'helpdesk-requests' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-headset"></i></div>
                        <span class="sb-label">Help Desk</span>
                        @if ($helpdeskPending > 0)
                            <span class="sb-badge">{{ $helpdeskPending }}</span>
                        @endif
                    </a>
                </div>

            </div>

            <div class="sb-sep"></div>

            {{-- ── Links ── --}}
            <div class="sb-section">
                <div class="sb-section-label">Navigation</div>

                <div class="sb-item" data-tip="ICT Unit Page">
                    <a href="{{ route('unit.ict') }}" class="sb-link {{ $activeNav === 'ict-unit' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-house-fill"></i></div>
                        <span class="sb-label">ICT Unit Page</span>
                    </a>
                </div>

                {{-- <div class="sb-item" data-tip="Main Admin Dashboard">
                    <a href="{{ route('admin.dashboard') }}"
                        class="sb-link {{ $activeNav === 'main-dashboard' ? 'active' : '' }}">
                        <div class="sb-icon"><i class="bi bi-grid-fill"></i></div>
                        <span class="sb-label">Main Dashboard</span>
                    </a>
                </div> --}}
            </div>

            <div class="sb-sep"></div>

            {{-- ── Account ── --}}
            <div class="sb-section">
                <div class="sb-section-label">Account</div>

                <div class="sb-item" data-tip="Logout">
                    <a href="#" class="sb-link"
                        onclick="event.preventDefault();document.getElementById('ict-logout-form').submit();">
                        <div class="sb-icon"><i class="bi bi-box-arrow-left"></i></div>
                        <span class="sb-label">Logout</span>
                    </a>
                </div>
            </div>

        </nav>

        {{-- Logged-in user --}}
        <div class="sb-user">
            <div class="sb-user-avatar">
                <div class="sb-user-avatar-inner">
                    {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                </div>
            </div>
            <div class="sb-user-info">
                <div class="sb-user-name">{{ Auth::user()->username ?? 'Admin' }}</div>
                <div class="sb-user-role">{{ Auth::user()->user_pos ?? 'Administrator' }}</div>
            </div>
        </div>

    </aside>

    {{-- Hidden logout form --}}
    <form id="ict-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>


    {{-- ════════════════════════════════════
         TOPBAR
         ════════════════════════════════════ --}}
    <header id="topbar">
        <div class="tb-left">
            <div class="tb-hamburger" onclick="openMob()"><i class="bi bi-list"></i></div>
            <div class="tb-vline"></div>
            <div>
                <div class="tb-title">@yield('page-title', 'ICT Admin')</div>
                <div class="tb-desc">@yield('page-desc', 'SDO Tayabas City · ICT Unit')</div>
            </div>
        </div>

        <div class="tb-right">
            {{-- Page-level action buttons (defined per child view) --}}
            @yield('actions')

            {{-- Bell: pending count --}}
            <a href="{{ route('ict.admin.ta-requests.index', ['status' => 'Pending']) }}" class="tb-icon-btn"
                title="Pending tickets">
                <i class="bi bi-bell-fill"></i>
                @if ($totalPending > 0)
                    <span class="tb-notif-dot"></span>
                @endif
            </a>

            {{-- ICT Unit home --}}
            <a href="{{ route('unit.ict') }}" class="tb-icon-btn" title="ICT Unit Page">
                <i class="bi bi-house-fill"></i>
            </a>

            {{-- User chip --}}
            <div class="tb-user">
                <div class="tb-user-av">{{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}</div>
                <span class="tb-user-name">{{ Auth::user()->username ?? 'Admin' }}</span>
            </div>
        </div>
    </header>


    {{-- ════════════════════════════════════
         MAIN CONTENT
         ════════════════════════════════════ --}}
    <main id="main">
        <div class="page-content">
            @yield('content')
        </div>
    </main>


    {{-- ── Shared Toast ── --}}
    <div class="admin-toast" id="admin-toast">
        <div class="admin-toast-icon" id="admin-toast-icon"></div>
        <div style="flex:1;min-width:0;">
            <div class="admin-toast-title" id="admin-toast-title"></div>
            <div class="admin-toast-msg" id="admin-toast-msg"></div>
        </div>
        <div class="admin-toast-close" onclick="adminHideToast()"><i class="bi bi-x-lg"></i></div>
    </div>

    {{-- ── Shared Confirm/Delete Modal ── --}}
    <div class="admin-modal-overlay" id="admin-confirm-modal" onclick="if(event.target===this)adminCloseConfirm()">
        <div class="admin-modal">
            <div class="admin-modal-icon danger"><i class="bi bi-trash3-fill"></i></div>
            <div class="admin-modal-title">Confirm Delete</div>
            <div class="admin-modal-desc" id="admin-confirm-msg">This action cannot be undone.</div>
            <div class="admin-modal-actions">
                <button class="btn btn-secondary" onclick="adminCloseConfirm()">
                    <i class="bi bi-arrow-left"></i> Cancel
                </button>
                <button class="btn btn-danger" onclick="adminDoDelete()">
                    <i class="bi bi-trash3"></i> Delete
                </button>
            </div>
        </div>
    </div>


    {{-- ── Shared JS ── --}}
    <script>
        /* ── Sidebar collapse ── */
        const SB_KEY = 'ict_sb_col';

        function toggleSidebar() {
            document.body.classList.toggle('sb-collapsed');
            localStorage.setItem(SB_KEY, document.body.classList.contains('sb-collapsed') ? '1' : '0');
        }

        function openMob() {
            document.body.classList.add('mob-open');
        }

        function closeMob() {
            document.body.classList.remove('mob-open');
        }

        // Restore saved state on load
        if (localStorage.getItem(SB_KEY) === '1') document.body.classList.add('sb-collapsed');

        // Auto-close mobile drawer on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 900) closeMob();
        });

        /* ── Scroll reveal ── */
        const _ro = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    _ro.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.08
        });
        document.querySelectorAll('.reveal').forEach(el => _ro.observe(el));

        /* ── Toast ── */
        let _toastTimer;

        function adminShowToast(type, title, msg, duration = 5000) {
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-exclamation-triangle-fill',
                warning: 'bi-exclamation-circle-fill'
            };
            document.getElementById('admin-toast-icon').className = 'admin-toast-icon ' + type;
            document.getElementById('admin-toast-icon').innerHTML = `<i class="bi ${icons[type] || icons.warning}"></i>`;
            document.getElementById('admin-toast-title').textContent = title;
            document.getElementById('admin-toast-msg').textContent = msg;
            document.getElementById('admin-toast').classList.add('show');
            clearTimeout(_toastTimer);
            _toastTimer = setTimeout(adminHideToast, duration);
        }

        function adminHideToast() {
            document.getElementById('admin-toast').classList.remove('show');
        }

        /* ── Confirm delete ── */
        function adminConfirmDelete(formId, message) {
            const overlay = document.getElementById('admin-confirm-modal');
            if (!overlay) {
                document.getElementById(formId)?.submit();
                return;
            }
            document.getElementById('admin-confirm-msg').textContent = message || 'This action cannot be undone.';
            overlay.dataset.formId = formId;
            overlay.classList.add('open');
        }

        function adminCloseConfirm() {
            document.getElementById('admin-confirm-modal')?.classList.remove('open');
        }

        function adminDoDelete() {
            const id = document.getElementById('admin-confirm-modal')?.dataset.formId;
            if (id) document.getElementById(id)?.submit();
            adminCloseConfirm();
        }

        /* ── Laravel flash messages ── */
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () =>
                adminShowToast('success', 'Success!', @json(session('success'))));
        @endif
        @if (session('error'))
            document.addEventListener('DOMContentLoaded', () =>
                adminShowToast('error', 'Error', @json(session('error'))));
        @endif
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () =>
                adminShowToast('error', 'Validation Error', 'Please check the form and try again.'));
        @endif
    </script>

    @yield('scripts')

</body>

</html>
