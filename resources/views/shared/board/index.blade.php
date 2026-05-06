@php
    $exact = match (Auth::user()->user_pos) {
        'Super Administrator' => 'admin',
        'HR' => 'hr',
        'Administrative Officer' => 'ao',
        'ASDS' => 'asds',
        'Department Head' => 'head',
        default => null,
    };

    if (!$exact) {
        $roleType = \App\Models\Role::where('role_desc', Auth::user()->user_pos)->value('role_type');
        $exact = $roleType === 'Department Head' ? 'head' : 'employee';
    }

    $prefix = $exact;

    $layout = match ($prefix) {
        'admin' => 'layouts.admin',
        'hr' => 'layouts.hr',
        'head' => 'layouts.head',
        'ao' => 'layouts.ao',
        'asds' => 'layouts.asds',
        default => 'layouts.employee',
    };
@endphp

@extends($layout)
@section('title', 'Notice Board')
@section('page-title', 'Notice Board')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Communication</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Notice Board</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Official announcements and notices from HR and Admin.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Post Form (HR & Admin only) --}}
        @if ($canPost)
            <div class="col-md-4 anim-fade-up delay-1">
                <div class="stat-card">
                    <div
                        style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                        <i class="bi bi-megaphone me-2" style="color:#4A90E2;"></i>New Announcement
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route($prefix . '.board.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                maxlength="100" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" maxlength="1000" required>{{ old('description') }}</textarea>
                            <div class="form-text">Max 1000 characters.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-megaphone me-2"></i> Publish
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Posts --}}
        <div class="{{ $canPost ? 'col-md-8' : 'col-md-12' }} anim-fade-up delay-2">
            @forelse($posts as $post)
                <div style="background:var(--surface);border-radius:var(--radius);
                    border:1px solid var(--border);padding:1.375rem;
                    margin-bottom:12px;box-shadow:var(--shadow-sm);
                    transition:all var(--transition);"
                    onmouseover="this.style.boxShadow='var(--shadow-md)';this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.boxShadow='var(--shadow-sm)';this.style.transform='translateY(0)'">

                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                                <span
                                    style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;
                                     background:rgba(110,168,254,0.12);color:#1D4ED8;">
                                    {{ $post->role }}
                                </span>
                                <span style="font-size:12px;color:var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($post->date_time)->format('F d, Y h:i A') }}
                                </span>
                            </div>
                            <div style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:6px;">
                                {{ $post->title }}
                            </div>
                            <div
                                style="font-size:13.5px;color:var(--text-secondary);line-height:1.7;
                                white-space:pre-wrap;margin-bottom:10px;">
                                {{ $post->description }}
                            </div>
                            <div
                                style="font-size:12px;color:var(--text-secondary);display:flex;align-items:center;gap:6px;">
                                <div
                                    style="width:22px;height:22px;border-radius:50%;
                                    background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                    color:#fff;display:flex;align-items:center;justify-content:center;
                                    font-size:9px;font-weight:700;">
                                    {{ strtoupper(substr($post->user?->username ?? 'U', 0, 2)) }}
                                </div>
                                {{ $post->user?->username ?? '—' }}
                            </div>
                        </div>

                        @if ($post->user_id === Auth::id() || Auth::user()->user_pos === 'Super Administrator')
                            <form method="POST" action="{{ route($prefix . '.board.destroy', $post->id) }}"
                                onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                   border:1px solid rgba(239,68,68,0.12);border-radius:8px;
                                   padding:6px 10px;cursor:pointer;flex-shrink:0;">
                                    <i class="bi bi-trash" style="font-size:13px;"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    style="background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);
                    padding:4rem;text-align:center;color:var(--text-secondary);">
                    <i class="bi bi-megaphone" style="font-size:36px;display:block;margin-bottom:12px;opacity:0.2;"></i>
                    <div style="font-size:14px;font-weight:500;">No announcements yet.</div>
                    <div style="font-size:13px;margin-top:4px;opacity:0.7;">Check back later for updates.</div>
                </div>
            @endforelse

            <div class="mt-3">{{ $posts->links() }}</div>
        </div>

    </div>

@endsection
