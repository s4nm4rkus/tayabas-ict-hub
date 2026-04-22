@extends('layouts.admin')
@section('title', 'Sub Positions')
@section('page-title', 'Sub Positions')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Sub Positions</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Manage additional designations assigned to employees.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Add Form --}}
        <div class="col-md-4 anim-fade-up delay-1">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-plus-circle"></i> Add Sub Position
                </div>
                <form method="POST" action="{{ route('admin.subposition.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Main Position</label>
                        <input type="text" name="main_pos" class="form-control" value="{{ old('main_pos') }}"
                            placeholder="e.g. Teacher I">
                        <div class="form-text">The main role this designation is for.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Sub Position / Designation <span class="text-danger">*</span></label>
                        <input type="text" name="sub_position" class="form-control" value="{{ old('sub_position') }}"
                            placeholder="e.g. Class Adviser" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus me-1"></i> Add Sub Position
                    </button>
                </form>
            </div>
        </div>

        {{-- List --}}
        <div class="col-md-8 anim-fade-up delay-2">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-diagram-3"></i> All Sub Positions
                    <span
                        style="margin-left:8px;font-size:12px;font-weight:400;
                             color:var(--text-secondary);background:var(--bg);
                             padding:2px 10px;border-radius:99px;border:1px solid var(--border);">
                        {{ $subPositions->count() }} designations
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Main Position</th>
                                <th>Sub Position / Designation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subPositions as $sub)
                                <tr>
                                    <td style="font-size:13px;color:var(--text-secondary);">
                                        {{ $sub->main_pos ?? '—' }}
                                    </td>
                                    <td style="font-weight:600;font-size:13.5px;">
                                        {{ $sub->sub_position }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.subposition.destroy', $sub->id) }}"
                                            onsubmit="return confirm('Delete this sub position?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm"
                                                style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                               border:1px solid rgba(239,68,68,0.15);border-radius:8px;padding:4px 9px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                        No sub positions yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
