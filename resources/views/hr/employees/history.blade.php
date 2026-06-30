@extends('layouts.hr')
@section('title', 'Employment History — ' . $employee->full_name)
@section('page-title', 'Employment History')

@section('content')

    {{-- Header --}}
    <div class="anim-fade-up mb-4"
        style="background:var(--surface);border-radius:var(--radius);
               border:1px solid var(--border);padding:1.5rem;box-shadow:var(--shadow-sm);">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            @if ($employee->photo_path)
                <img src="{{ asset('storage/' . $employee->photo_path) }}"
                    style="width:56px;height:56px;border-radius:50%;object-fit:cover;
                           border:3px solid rgba(52,211,153,0.3);">
            @else
                <div
                    style="width:56px;height:56px;border-radius:50%;flex-shrink:0;
                            background:linear-gradient(135deg,#34D399,#059669);
                            color:#fff;display:flex;align-items:center;justify-content:center;
                            font-size:20px;font-weight:700;">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-grow-1">
                <h5 style="margin:0;font-size:17px;font-weight:700;color:var(--text-primary);">
                    {{ $employee->full_name }}
                </h5>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:3px;">
                    {{ $employee->employment?->position ?? '—' }}
                    &nbsp;·&nbsp; SG {{ $employee->employment?->salary_grade ?? '—' }},
                    Step {{ $employee->employment?->salary_step ?? '—' }}
                </div>
            </div>
            <a href="{{ route('hr.employees.edit', $employee->user_id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Employee
            </a>
            <a href="{{ route('hr.employees.show', $employee->user_id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4 anim-fade-up">
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Total Entries
                </div>
                <div style="font-size:28px;font-weight:700;color:var(--text-primary);line-height:1;">
                    {{ $histories->count() }}
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Years in Service
                </div>
                <div style="font-size:28px;font-weight:700;color:#4A90E2;line-height:1;">
                    @php
                        $first = $histories->sortBy('effective_date')->first();
                        $years = $first ? \Carbon\Carbon::parse($first->effective_date)->diffInYears(now()) : 0;
                    @endphp
                    {{ $years }}
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="padding:1rem 1.25rem;">
                <div
                    style="font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:6px;">
                    Current SG / Step
                </div>
                <div style="font-size:22px;font-weight:700;color:#059669;line-height:1;">
                    SG {{ $employee->employment?->salary_grade ?? '—' }} /
                    {{ $employee->employment?->salary_step ?? '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="stat-card anim-fade-up">
        <div
            style="font-size:14px;font-weight:700;color:var(--text-primary);
             margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);
             display:flex;align-items:center;justify-content:space-between;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div
                    style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,0.12);
                     display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-clock-history" style="color:#7C3AED;font-size:13px;"></i>
                </div>
                Employment Timeline
            </div>
        </div>

        @if ($histories->isEmpty())
            <div style="text-align:center;padding:3rem;color:var(--text-secondary);">
                <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                <div style="font-size:13px;">No employment history yet.</div>
            </div>
        @else
            <div style="position:relative;padding-left:24px;">
                {{-- Timeline line --}}
                <div
                    style="position:absolute;left:7px;top:8px;bottom:8px;width:2px;
                     background:var(--border);border-radius:2px;">
                </div>

                @foreach ($histories->sortByDesc('effective_date') as $hist)
                    @php
                        $badgeColor = match ($hist->change_reason) {
                            'PROMOTION' => [
                                'bg' => 'rgba(52,211,153,0.12)',
                                'color' => '#059669',
                                'border' => 'rgba(52,211,153,0.3)',
                            ],
                            'DEMOTION' => [
                                'bg' => 'rgba(239,68,68,0.08)',
                                'color' => '#B91C1C',
                                'border' => 'rgba(239,68,68,0.2)',
                            ],
                            'TRANSFER' => [
                                'bg' => 'rgba(74,144,226,0.1)',
                                'color' => '#2563EB',
                                'border' => 'rgba(74,144,226,0.25)',
                            ],
                            'RECLASSIFICATION' => [
                                'bg' => 'rgba(245,158,11,0.1)',
                                'color' => '#B45309',
                                'border' => 'rgba(245,158,11,0.25)',
                            ],
                            default => [
                                'bg' => 'rgba(139,92,246,0.1)',
                                'color' => '#7C3AED',
                                'border' => 'rgba(139,92,246,0.25)',
                            ],
                        };
                    @endphp
                    <div style="position:relative;margin-bottom:14px;">
                        {{-- Dot --}}
                        <div
                            style="position:absolute;left:-21px;top:16px;width:10px;height:10px;border-radius:50%;
                             background:{{ is_null($hist->end_date) ? '#34D399' : 'var(--border)' }};
                             border:2px solid {{ is_null($hist->end_date) ? '#059669' : 'var(--text-secondary)' }};
                             z-index:1;">
                        </div>

                        <div
                            style="background:var(--surface);border:1px solid var(--border);
                             border-radius:var(--radius-sm);padding:14px 16px;">
                            <div
                                style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">

                                {{-- Left side --}}
                                <div style="flex:1;min-width:200px;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                        <span
                                            style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;
                                              background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['color'] }};
                                              border:1px solid {{ $badgeColor['border'] }};letter-spacing:0.05em;">
                                            {{ $hist->change_reason }}
                                        </span>
                                        @if (is_null($hist->end_date))
                                            <span
                                                style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;
                                                  background:rgba(52,211,153,0.1);color:#059669;
                                                  border:1px solid rgba(52,211,153,0.3);">
                                                ● CURRENT
                                            </span>
                                        @endif
                                    </div>

                                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);">
                                        {{ $hist->position }}
                                        @if ($hist->sub_position)
                                            <span style="font-weight:400;color:var(--text-secondary);font-size:12px;">
                                                — {{ $hist->sub_position }}
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        style="font-size:12px;color:var(--text-secondary);margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;">
                                        <span><i class="bi bi-bar-chart-steps" style="font-size:11px;"></i> SG
                                            {{ $hist->salary_grade }}, Step {{ $hist->salary_step }}</span>
                                        <span><i class="bi bi-bookmark" style="font-size:11px;"></i>
                                            {{ $hist->status_appoint ?? '—' }}</span>
                                        <span><i class="bi bi-file-text" style="font-size:11px;"></i>
                                            {{ $hist->nature_appoint ?? '—' }}</span>
                                        @if ($hist->station)
                                            <span><i class="bi bi-geo-alt" style="font-size:11px;"></i>
                                                {{ $hist->station }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right side --}}
                                <div style="text-align:right;flex-shrink:0;">
                                    <div style="font-size:13px;font-weight:700;color:var(--text-primary);">
                                        {{ \Carbon\Carbon::parse($hist->effective_date)->format('M d, Y') }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">
                                        @if (is_null($hist->end_date))
                                            to <em>present</em>
                                        @else
                                            to {{ \Carbon\Carbon::parse($hist->end_date)->format('M d, Y') }}
                                        @endif
                                    </div>
                                    <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">
                                        <i class="bi bi-calendar3" style="font-size:10px;"></i>
                                        Step anchor: {{ \Carbon\Carbon::parse($hist->step_anchor)->format('M d, Y') }}
                                    </div>

                                    @if ($hist->change_reason !== 'ORIGINAL')
                                        <form method="POST"
                                            action="{{ route('hr.employees.history.destroy', ['userId' => $employee->user_id, 'historyId' => $hist->id]) }}"
                                            onsubmit="return confirm('Remove this entry? Service records will be recalculated.')"
                                            style="margin-top:8px;">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="font-size:11px;padding:3px 10px;border-radius:5px;
                                                background:rgba(239,68,68,0.08);color:#B91C1C;
                                                border:1px solid rgba(239,68,68,0.15);cursor:pointer;">
                                                <i class="bi bi-trash" style="font-size:10px;"></i> Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
