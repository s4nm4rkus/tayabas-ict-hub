@extends('layouts.head')
@section('title', 'Head Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;" id="hero-date">
                {{ now()->format('l, F d Y') }}
            </div>
            <h4 style="font-size:22px;font-weight:700;margin-bottom:4px;" id="hero-greeting">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
                {{ explode('@', Auth::user()->username)[0] }} 👋
            </h4>
            <p style="font-size:13.5px;opacity:0.8;margin:0;">
                Here's your HR activity overview for today.
            </p>
        </div>
    </div>

    <script>
        (function() {
            const name = "{{ explode('@', Auth::user()->username)[0] }}";

            function updateHero() {
                const now = new Date();
                const hour = now.getHours();

                // Greeting
                const greeting = hour < 12 ? 'morning' : hour < 18 ? 'afternoon' : 'evening';
                document.getElementById('hero-greeting').innerHTML =
                    `Good ${greeting}, ${name} 👋`;

                // Date + time
                const dateStr = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit'
                });
                const timeStr = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('hero-date').textContent = `${dateStr} · ${timeStr}`;
            }

            updateHero();
            setInterval(updateHero, 1000); // updates every second
        })();
    </script>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 anim-fade-up delay-1">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Pending Requests</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $pendingLeaves }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">awaiting your review</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-hourglass-split" style="font-size:20px;color:#F59E0B;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <a href="{{ route('head.leave.index') }}"
                        style="font-size:12px;font-weight:600;color:#D97706;text-decoration:none;">Review now <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 anim-fade-up delay-2">
            <div class="stat-card h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div
                            style="font-size:12px;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:8px;">
                            Endorsed Today</div>
                        <div
                            style="font-size:32px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:6px;">
                            {{ $endorsedToday }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">forwarded to HR</div>
                    </div>
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(34,197,94,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check-circle" style="font-size:20px;color:#22C55E;"></i>
                    </div>
                </div>
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
                    <span style="font-size:12px;color:var(--text-secondary);">Today's endorsements</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-3">
        <div style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Latest Announcements</div>
        @forelse($announcements as $post)
            <div style="padding:12px 0;border-bottom:1px solid var(--border);">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span
                        style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;background:rgba(252,211,77,0.15);color:#D97706;">{{ $post->role }}</span>
                    <span
                        style="font-size:12px;color:var(--text-secondary);">{{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }}</span>
                </div>
                <div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $post->title }}</div>
                <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;">
                    {{ Str::limit($post->description, 100) }}</div>
            </div>
        @empty
            <p class="text-muted" style="font-size:13px;">No announcements.</p>
        @endforelse
    </div>

@endsection
