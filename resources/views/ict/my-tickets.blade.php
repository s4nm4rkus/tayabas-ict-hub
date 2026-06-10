{{-- resources/views/ict-views/my-tickets.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'My ICT Tickets')
@section('navbar-badge-icon', 'bi-ticket-perforated-fill')
@section('navbar-badge-label', 'My Tickets')
@section('navbar-back-route', route('ict.forms'))
@section('navbar-back-label', 'Back to Forms')
@section('navbar-action-icon', 'bi-plus-circle-fill')
@section('navbar-action-label', 'New Ticket')
@section('navbar-action-route', route('ict.ta-form'))
@section('footer-label', 'My ICT Tickets')
@section('footer-back-route', route('ict.forms'))
@section('footer-back-label', 'Back to Forms')

@section('content')

    {{-- ── HERO ── --}}
    <div class="ict-page-hero">
        <div class="ict-hero-grid"></div>
        <div class="ict-hero-orb ict-hero-orb-tr"></div>
        <div class="ict-hero-inner">
            <div class="ict-a1">
                <div class="ict-breadcrumb">
                    <a href="{{ route('home') }}">SDO Tayabas City</a>
                    <span class="ict-breadcrumb-sep">/</span>
                    <a href="{{ route('unit.ict') }}">ICT Unit</a>
                    <span class="ict-breadcrumb-sep">/</span>
                    <span>My Tickets</span>
                </div>
                <h1
                    style="font-size:clamp(22px,3vw,36px);font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.025em;margin-bottom:8px;">
                    My <span style="color:var(--h-gold);">Submitted</span> Tickets
                </h1>
                <p style="font-size:13.5px;color:var(--h-text);line-height:1.75;max-width:460px;">
                    Track the status of your ICT Technical Assistance requests.
                </p>
            </div>
        </div>
    </div>

    {{-- ── BODY ── --}}
    <div class="ict-page-body">

        {{-- New ticket CTA --}}
        <div
            style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:28px;">
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--navy);">
                    {{ $tickets->total() }} ticket{{ $tickets->total() !== 1 ? 's' : '' }} submitted
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                    Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }}
                </div>
            </div>
            <a href="{{ route('ict.ta-form') }}" class="ict-btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Submit New Ticket
            </a>
        </div>

        @if ($tickets->isEmpty())
            {{-- Empty state --}}
            <div
                style="background:#fff;border:1.5px solid var(--border);border-radius:20px;padding:64px 32px;text-align:center;">
                <i class="bi bi-ticket-perforated"
                    style="font-size:48px;color:var(--border);display:block;margin-bottom:16px;"></i>
                <div style="font-size:16px;font-weight:700;color:var(--navy);margin-bottom:8px;">No tickets yet</div>
                <div style="font-size:13.5px;color:var(--muted);margin-bottom:24px;">You haven't submitted any ICT support
                    requests.</div>
                <a href="{{ route('ict.ta-form') }}" class="ict-btn-primary" style="display:inline-flex;">
                    <i class="bi bi-plus-circle-fill"></i> Submit Your First Ticket
                </a>
            </div>
        @else
            {{-- Tickets list --}}
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach ($tickets as $ticket)
                    @php
                        $colors = [
                            'Pending' => ['#D97706', 'rgba(245,158,11,0.08)', 'rgba(245,158,11,0.2)'],
                            'In Progress' => ['#2563eb', 'rgba(37,99,235,0.08)', 'rgba(37,99,235,0.18)'],
                            'Resolved' => ['#059669', 'rgba(5,150,105,0.08)', 'rgba(5,150,105,0.18)'],
                            'Closed' => ['#6B7A90', 'rgba(107,122,144,0.08)', 'rgba(107,122,144,0.2)'],
                            'Cancelled' => ['#dc2626', 'rgba(220,38,38,0.08)', 'rgba(220,38,38,0.18)'],
                        ];
                        [$sc, $sbg, $sborder] = $colors[$ticket->status] ?? ['#6B7A90', '#f9fafb', '#e5e7eb'];
                    @endphp
                    <div style="background:#fff;border:1.5px solid var(--border);border-radius:14px;padding:20px 24px;display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;transition:box-shadow .2s;"
                        onmouseenter="this.style.boxShadow='0 8px 28px rgba(11,31,58,0.09)'"
                        onmouseleave="this.style.boxShadow=''">

                        {{-- Ticket no + date --}}
                        <div style="flex-shrink:0;min-width:140px;">
                            <div
                                style="font-size:13.5px;font-weight:800;color:var(--accent);font-family:'Courier New',monospace;letter-spacing:0.04em;">
                                {{ $ticket->ticket_no }}
                            </div>
                            <div style="font-size:11px;color:var(--muted);margin-top:3px;">
                                {{ $ticket->created_at->format('M j, Y') }}
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div style="width:1px;background:var(--border);align-self:stretch;flex-shrink:0;"></div>

                        {{-- Details --}}
                        <div style="flex:1;min-width:200px;">
                            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px;">
                                @foreach ($ticket->assistance_types as $type)
                                    <span
                                        style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:4px;background:rgba(37,99,235,0.07);color:#1A4A8A;border:1px solid rgba(37,99,235,0.14);">
                                        {{ $type === 'Others' && $ticket->others_text ? 'Others: ' . $ticket->others_text : $type }}
                                    </span>
                                @endforeach
                            </div>
                            <div style="font-size:13px;color:var(--text-mid,#3D5168);line-height:1.6;">
                                {{ Str::limit($ticket->description, 120) }}
                            </div>
                            <div style="font-size:11px;color:var(--muted);margin-top:6px;">
                                <i class="bi bi-building" style="font-size:10px;"></i> {{ $ticket->department }}
                            </div>
                        </div>

                        {{-- Status --}}
                        <div style="flex-shrink:0;text-align:right;">
                            <span
                                style="display:inline-flex;align-items:center;font-size:11px;font-weight:700;padding:4px 12px;border-radius:99px;background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sborder }};">
                                {{ $ticket->status }}
                            </span>
                            @if ($ticket->admin_notes)
                                <div
                                    style="font-size:10.5px;color:var(--success,#059669);margin-top:6px;display:flex;align-items:center;gap:4px;justify-content:flex-end;">
                                    <i class="bi bi-chat-square-text-fill" style="font-size:10px;"></i> Admin replied
                                </div>
                            @endif
                            @if ($ticket->resolved_at)
                                <div style="font-size:10.5px;color:var(--muted);margin-top:4px;">
                                    Resolved {{ \Carbon\Carbon::parse($ticket->resolved_at)->format('M j') }}
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($tickets->hasPages())
                <div style="margin-top:24px;">
                    {{ $tickets->links() }}
                </div>
            @endif
        @endif

    </div>

@endsection
