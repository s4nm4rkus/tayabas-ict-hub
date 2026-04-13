@php
    $prefix = match (Auth::user()->user_pos) {
        'Super Administrator' => 'admin',
        'HR' => 'hr',
        default => App\Models\Role::where('role_desc', Auth::user()->user_pos)->first()?->role_type ===
        'Department Head'
            ? 'head'
            : 'employee',
    };
    $layout = match ($prefix) {
        'admin' => 'layouts.admin',
        'hr' => 'layouts.hr',
        'head' => 'layouts.head',
        default => 'layouts.employee',
    };
@endphp

@extends($layout)

@section('title', 'Notice Board')
@section('page-title', 'Notice Board')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Post Form — HR and Admin only --}}
        @if ($canPost)
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="info-card-title">
                        <i class="bi bi-megaphone"></i> New Announcement
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size:13px;">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route($prefix . '.board.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                maxlength="100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" maxlength="1000" required>{{ old('description') }}</textarea>
                            <div class="form-text">Max 1000 characters.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-megaphone me-1"></i> Publish
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Posts --}}
        <div class="{{ $canPost ? 'col-md-8' : 'col-md-12' }}">
            @forelse($posts as $post)
                <div class="board-card mb-3">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="board-role-badge">{{ $post->role }}</span>
                                <span class="board-date">
                                    {{ \Carbon\Carbon::parse($post->date_time)->format('F d, Y h:i A') }}
                                </span>
                            </div>
                            <h6 class="board-title">{{ $post->title }}</h6>
                            <p class="board-body">{{ $post->description }}</p>
                            <div class="board-author">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $post->user?->username ?? '—' }}
                            </div>
                        </div>
                        @if ($post->user_id === Auth::id() || Auth::user()->user_pos === 'Super Administrator')
                            <form method="POST" action="{{ route($prefix . '.board.destroy', $post->id) }}"
                                onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger flex-shrink-0">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="stat-card text-center py-5">
                    <i class="bi bi-megaphone" style="font-size:32px;color:#dee2e6;"></i>
                    <p class="text-muted mt-2 mb-0">No announcements yet.</p>
                </div>
            @endforelse

            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        </div>

    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .board-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
            transition: box-shadow 0.15s;
        }

        .board-card:hover {
            border-color: #d0d7e2;
        }

        .board-role-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 99px;
            background: #e8f0fe;
            color: #1a56db;
        }

        .board-date {
            font-size: 12px;
            color: #8892a4;
        }

        .board-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 6px;
        }

        .board-body {
            font-size: 14px;
            color: #495057;
            line-height: 1.6;
            margin-bottom: 8px;
            white-space: pre-wrap;
        }

        .board-author {
            font-size: 12px;
            color: #8892a4;
        }
    </style>

@endsection
