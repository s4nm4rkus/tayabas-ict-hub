@php
    $prefix = match (Auth::user()->user_pos) {
        'Super Administrator' => 'admin',
        'HR' => 'hr',
        default => \App\Models\Role::where('role_desc', Auth::user()->user_pos)->first()?->role_type ===
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
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Communication</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Messages</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Send and receive messages within the system.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Compose --}}
        <div class="col-md-4 anim-fade-up delay-1">
            <div class="stat-card">
                <div
                    style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-pencil-square me-2" style="color:#4A90E2;"></i>Compose Message
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
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
                    <div class="mb-4">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="messages" class="form-control" rows="5" maxlength="1000" required>{{ old('messages') }}</textarea>
                        <div class="form-text">Max 1000 characters.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        {{-- Inbox & Sent --}}
        <div class="col-md-8 anim-fade-up delay-2">

            {{-- Tabs --}}
            <div style="display:flex;gap:2px;border-bottom:2px solid var(--border);margin-bottom:1rem;">
                @php $unread = $inbox->where('mes_status','Unread')->count(); @endphp
                <a href="#inbox" class="msg-tab active" id="tab-inbox">
                    Inbox
                    @if ($unread > 0)
                        <span
                            style="font-size:11px;font-weight:700;padding:1px 7px;border-radius:99px;
                                 background:rgba(110,168,254,0.15);color:#1D4ED8;margin-left:4px;">
                            {{ $unread }}
                        </span>
                    @endif
                </a>
                <a href="#sent" class="msg-tab" id="tab-sent">Sent</a>
            </div>

            {{-- Inbox Panel --}}
            <div id="panel-inbox">
                <div class="stat-card">
                    @forelse($inbox as $msg)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 10px;
                            border-radius:10px;border-bottom:1px solid var(--border);
                            transition:background var(--transition);
                            background:{{ $msg->mes_status === 'Unread' ? 'rgba(110,168,254,0.06)' : 'transparent' }};"
                            onmouseover="this.style.background='rgba(110,168,254,0.04)'"
                            onmouseout="this.style.background='{{ $msg->mes_status === 'Unread' ? 'rgba(110,168,254,0.06)' : 'transparent' }}'">

                            <div
                                style="width:34px;height:34px;border-radius:50%;flex-shrink:0;
                                background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                color:#fff;display:flex;align-items:center;justify-content:center;
                                font-size:12px;font-weight:700;">
                                {{ strtoupper(substr($msg->userid, 0, 2)) }}
                            </div>

                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;flex-wrap:wrap;">
                                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                        {{ $msg->userid }}
                                    </span>
                                    <span style="font-size:11px;color:var(--text-secondary);">
                                        {{ \Carbon\Carbon::parse($msg->date_time)->format('M d, Y h:i A') }}
                                    </span>
                                    @if ($msg->mes_status === 'Unread')
                                        <span
                                            style="font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;
                                             background:rgba(110,168,254,0.15);color:#1D4ED8;">New</span>
                                    @endif
                                </div>
                                <div style="font-size:13.5px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">
                                    {{ $msg->subject_mes }}
                                </div>
                                <div style="font-size:12px;color:var(--text-secondary);">
                                    {{ Str::limit($msg->messages, 80) }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route($prefix . '.messages.destroy', $msg->id) }}"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                   border:1px solid rgba(239,68,68,0.12);border-radius:8px;
                                   padding:5px 9px;cursor:pointer;flex-shrink:0;">
                                    <i class="bi bi-trash" style="font-size:13px;"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div style="text-align:center;padding:3rem;color:var(--text-secondary);">
                            <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                            No messages in inbox.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Sent Panel --}}
            <div id="panel-sent" style="display:none;">
                <div class="stat-card">
                    @forelse($sent as $msg)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 10px;
                            border-radius:10px;border-bottom:1px solid var(--border);
                            transition:background var(--transition);"
                            onmouseover="this.style.background='rgba(110,168,254,0.04)'"
                            onmouseout="this.style.background='transparent'">

                            <div
                                style="width:34px;height:34px;border-radius:50%;flex-shrink:0;
                                background:linear-gradient(135deg,#34D399,#059669);
                                color:#fff;display:flex;align-items:center;justify-content:center;
                                font-size:12px;font-weight:700;">
                                <i class="bi bi-send-fill" style="font-size:12px;"></i>
                            </div>

                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;flex-wrap:wrap;">
                                    <span style="font-size:13px;font-weight:600;color:var(--text-primary);">
                                        To: {{ $msg->receiver }}
                                    </span>
                                    <span style="font-size:11px;color:var(--text-secondary);">
                                        {{ \Carbon\Carbon::parse($msg->date_time)->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <div style="font-size:13.5px;font-weight:600;color:var(--text-primary);margin-bottom:2px;">
                                    {{ $msg->subject_mes }}
                                </div>
                                <div style="font-size:12px;color:var(--text-secondary);">
                                    {{ Str::limit($msg->messages, 80) }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route($prefix . '.messages.destroy', $msg->id) }}"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                   border:1px solid rgba(239,68,68,0.12);border-radius:8px;
                                   padding:5px 9px;cursor:pointer;flex-shrink:0;">
                                    <i class="bi bi-trash" style="font-size:13px;"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div style="text-align:center;padding:3rem;color:var(--text-secondary);">
                            <i class="bi bi-send" style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                            No sent messages.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <style>
        .msg-tab {
            display: block;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all var(--transition);
            border-radius: 6px 6px 0 0;
            margin-bottom: -2px;
        }

        .msg-tab:hover {
            color: var(--text-primary);
            background: rgba(110, 168, 254, 0.05);
        }

        .msg-tab.active {
            color: var(--primary-end);
            border-bottom-color: var(--primary-end);
            background: rgba(110, 168, 254, 0.06);
        }
    </style>

    @push('scripts')
        <script>
            document.getElementById('tab-inbox').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('tab-sent').classList.remove('active');
                document.getElementById('panel-inbox').style.display = 'block';
                document.getElementById('panel-sent').style.display = 'none';
            });
            document.getElementById('tab-sent').addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.add('active');
                document.getElementById('tab-inbox').classList.remove('active');
                document.getElementById('panel-sent').style.display = 'block';
                document.getElementById('panel-inbox').style.display = 'none';
            });
        </script>
    @endpush

@endsection
