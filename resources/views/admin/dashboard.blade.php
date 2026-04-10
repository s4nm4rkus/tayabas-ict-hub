@extends('layouts.admin-layout.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Total Employees</div>
                    <div class="stat-icon" style="background:#e8f0fe;">
                        <i class="bi bi-people" style="color:#4f8ef7;"></i>
                    </div>
                </div>
                <div class="stat-value">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Leave Requests</div>
                    <div class="stat-icon" style="background:#fff3cd;">
                        <i class="bi bi-calendar-check" style="color:#f0ad4e;"></i>
                    </div>
                </div>
                <div class="stat-value">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Certificates</div>
                    <div class="stat-icon" style="background:#d4edda;">
                        <i class="bi bi-file-earmark-check" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Present Today</div>
                    <div class="stat-icon" style="background:#f8d7da;">
                        <i class="bi bi-clock" style="color:#dc3545;"></i>
                    </div>
                </div>
                <div class="stat-value">0</div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-3">
        <div class="col-md-8">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:16px; font-weight:600;">Recent Leave Requests</h6>
                <table class="table table-sm table-hover">
                    <thead>
                        <tr style="font-size:14px; color:#8892a4;">
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                No records yet
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6 class="mb-3" style="font-size:16px; font-weight:600;">Notice Board</h6>
                <p class="text-muted" style="font-size:15px;">No announcements yet.</p>
            </div>
        </div>
    </div>

@endsection
