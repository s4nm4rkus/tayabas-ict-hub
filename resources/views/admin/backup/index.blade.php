@extends('layouts.admin')
@section('title', 'Database Backup')
@section('page-title', 'Backup')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Database Backup</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Download a full backup of the system database.</p>
        </div>
    </div>

    <div class="row justify-content-center anim-fade-up delay-1">
        <div class="col-md-6">
            <div class="stat-card text-center" style="padding:2.5rem;">

                <div
                    style="width:72px;height:72px;border-radius:20px;margin:0 auto 20px;
                        background:linear-gradient(135deg,rgba(110,168,254,0.15),rgba(139,92,246,0.15));
                        display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-database" style="font-size:32px;color:var(--primary-end);"></i>
                </div>

                <h5 style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px;">
                    Export Database
                </h5>
                <p style="font-size:13.5px;color:var(--text-secondary);margin-bottom:24px;">
                    Download a full SQL backup of <strong>icthub_db</strong> including all tables and records.
                </p>

                <div class="alert alert-warning text-start mb-4" style="font-size:13px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Store backups securely — this file contains sensitive employee data.
                </div>

                <a href="{{ route('admin.backup.download') }}" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-download me-2"></i> Download SQL Backup
                </a>

                <div style="margin-top:12px;font-size:12px;color:var(--text-secondary);">
                    <i class="bi bi-journal-check me-1"></i>
                    This action will be logged in the audit trail.
                </div>
            </div>
        </div>
    </div>

@endsection
