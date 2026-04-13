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

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Compose --}}
        <div class="col-md-4">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-pencil-square"></i> Compose Message
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route($prefix . '.messages.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">To (Email) <span class="text-danger">*</span></label>
                        <input type="email" name="receiver" class="form-control" value="{{ old('receiver') }}"
                            placeholder="recipient@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject_mes" class="form-control" value="{{ old('subject_mes') }}"
                            maxlength="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="messages" class="form-control" rows="5" maxlength="1000" required>{{ old('messages') }}</textarea>
                        <div class="form-text">Max 1000 characters.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-1"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        {{-- Inbox & Sent --}}
        <div class="col-md-8">

            {{-- Tabs --}}
            <ul class="profile-tabs mb-3">
                <li><a href="#inbox" class="tab-link active">
                        Inbox
                        @php $unread = $inbox->where('mes_status', 'Unread')->count(); @endphp
                        @if ($unread > 0)
                            <span class="badge"
                                style="background:#e8f0fe;color:#1a56db;
                                 font-size:11px;margin-left:4px;">
                                {{ $unread }}
                            </span>
                        @endif
                    </a></li>
                <li><a href="#sent" class="tab-link">Sent</a></li>
            </ul>

            {{-- Inbox --}}
            <div class="tab-panel active" id="inbox">
                <div class="stat-card">
                    @forelse($inbox as $msg)
                        <div class="msg-row {{ $msg->mes_status === 'Unread' ? 'unread' : '' }}">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="msg-from">{{ $msg->userid }}</span>
                                    <span class="msg-date">
                                        {{ \Carbon\Carbon::parse($msg->date_time)->format('M d, Y h:i A') }}
                                    </span>
                                    @if ($msg->mes_status === 'Unread')
                                        <span class="badge" style="background:#e8f0fe;color:#1a56db;font-size:11px;">
                                            New
                                        </span>
                                    @endif
                                </div>
                                <div class="msg-subject">{{ $msg->subject_mes }}</div>
                                <div class="msg-preview">{{ Str::limit($msg->messages, 80) }}</div>
                            </div>
                            <form method="POST" action="{{ route($prefix . '.messages.destroy', $msg->id) }}"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3" style="font-size:14px;">
                            No messages in inbox.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Sent --}}
            <div class="tab-panel" id="sent">
                <div class="stat-card">
                    @forelse($sent as $msg)
                        <div class="msg-row">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="msg-from">To: {{ $msg->receiver }}</span>
                                    <span class="msg-date">
                                        {{ \Carbon\Carbon::parse($msg->date_time)->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <div class="msg-subject">{{ $msg->subject_mes }}</div>
                                <div class="msg-preview">{{ Str::limit($msg->messages, 80) }}</div>
                            </div>
                            <form method="POST" action="{{ route($prefix . '.messages.destroy', $msg->id) }}"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3" style="font-size:14px;">
                            No sent messages.
                        </p>
                    @endforelse
                </div>
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

        .profile-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 4px;
            border-bottom: 2px solid #e9ecef;
        }

        .profile-tabs li {
            margin-bottom: -2px;
        }

        .tab-link {
            display: block;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #8892a4;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            border-radius: 4px 4px 0 0;
        }

        .tab-link:hover {
            color: #1a1f2e;
        }

        .tab-link.active {
            color: #4f8ef7;
            border-bottom-color: #4f8ef7;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .msg-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 10px;
            border-radius: 8px;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .msg-row:hover {
            background: #fafafa;
        }

        .msg-row.unread {
            background: #f0f7ff;
        }

        .msg-row.unread:hover {
            background: #e8f0fe;
        }

        .msg-from {
            font-size: 13px;
            font-weight: 500;
            color: #1a1f2e;
        }

        .msg-date {
            font-size: 12px;
            color: #8892a4;
        }

        .msg-subject {
            font-size: 14px;
            font-weight: 500;
            color: #1a1f2e;
            margin-bottom: 2px;
        }

        .msg-preview {
            font-size: 13px;
            color: #8892a4;
        }
    </style>

    @push('scripts')
        <script>
            document.querySelectorAll('.tab-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelector(this.getAttribute('href')).classList.add('active');
                });
            });
        </script>
    @endpush

@endsection
