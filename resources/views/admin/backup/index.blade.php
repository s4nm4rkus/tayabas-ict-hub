@extends('layouts.admin')

@section('title', 'Database Backup')
@section('page-title', 'Database Backup')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="stat-card text-center">
                <div style="font-size:48px;color:#e9ecef;margin-bottom:16px;">
                    <i class="bi bi-database"></i>
                </div>
                <h5 style="font-weight:600;color:#1a1f2e;">Database Backup</h5>
                <p class="text-muted" style="font-size:14px;margin-bottom:24px;">
                    Download a full SQL backup of the
                    <strong>icthub_db</strong> database including all tables and data.
                </p>

                <div class="alert alert-warning text-start" style="font-size:13px;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>Important:</strong> Store backups in a secure location.
                    This file contains sensitive employee data.
                </div>

                <a href="{{ route('admin.backup.download') }}" class="btn btn-primary btn-lg w-100 mt-2">
                    <i class="bi bi-download me-2"></i>
                    Download SQL Backup
                </a>

                <div class="mt-3" style="font-size:12px;color:#8892a4;">
                    This action will be logged in the audit trail.
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            border: 1px solid #e9ecef;
        }
    </style>

@endsection
