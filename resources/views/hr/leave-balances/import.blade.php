@extends('layouts.hr')
@section('title', 'Import Opening Balances')
@section('page-title', 'Import Opening Balances')

@section('content')

    <div class="d-flex gap-2 mb-3 anim-fade-up">
        <a href="{{ route('hr.leave-balances.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <a href="{{ route('hr.leave-balances.import.template') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i> Download Template (CSV)
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave Management</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Import Opening Balances</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">
                Enter the starting VL/SL balance of many employees at once from a CSV or Excel file.
            </p>
        </div>
    </div>

    {{-- ── Result of the last upload ── --}}
    @isset($result)
        @php
            $errorCount = count($result['errors']);
            $validCount = count($result['valid']);
        @endphp

        @if ($result['imported'])
            <div class="alert alert-success anim-fade-up">
                <i class="bi bi-check-circle me-2"></i>
                <strong>{{ $validCount }} opening balance(s) were created.</strong>
                Each one has an entry in the employee's leave credit history.
            </div>
        @elseif ($errorCount > 0)
            <div class="alert alert-danger anim-fade-up">
                <i class="bi bi-x-circle me-2"></i>
                <strong>{{ $errorCount }} of {{ $result['total'] }} row(s) have problems. Nothing was imported.</strong>
                Fix the rows listed below in your file, then upload the whole file again.
            </div>
        @elseif ($validCount > 0)
            <div class="alert alert-success anim-fade-up">
                <i class="bi bi-check-circle me-2"></i>
                <strong>File looks good — {{ $validCount }} row(s) are ready to import.</strong>
                Nothing has been saved yet. Upload the same file again and choose
                <em>Check &amp; Import</em> to save them.
            </div>
        @else
            <div class="alert alert-warning anim-fade-up">
                <i class="bi bi-exclamation-circle me-2"></i>No data rows were found in the file.
            </div>
        @endif

        @if ($errorCount > 0)
            <div class="stat-card anim-fade-up mb-3">
                <div style="font-size:13px;font-weight:700;color:#B91C1C;text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.75rem;">
                    Rows to fix
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:12.5px;">
                        <thead>
                            <tr>
                                <th style="width:90px;">File row</th>
                                <th>Problem(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['errors'] as $rowNumber => $messages)
                                <tr>
                                    <td style="font-weight:700;">{{ $rowNumber }}</td>
                                    <td style="color:var(--text-secondary);">
                                        @foreach ($messages as $message)
                                            <div>{{ $message }}</div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="font-size:11.5px;color:var(--text-secondary);margin-top:8px;">
                    "File row" is the row number you see in Excel (row 1 is the heading row).
                </div>
            </div>
        @endif

        @if ($validCount > 0 && $errorCount === 0)
            <div class="stat-card anim-fade-up mb-3">
                <div style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.75rem;">
                    {{ $result['imported'] ? 'Imported' : 'Ready to import' }}
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:12.5px;">
                        <thead>
                            <tr>
                                <th style="width:70px;">Row</th>
                                <th>Employee</th>
                                <th style="text-align:center;">VL</th>
                                <th style="text-align:center;">SL</th>
                                <th>As of</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['valid'] as $r)
                                <tr>
                                    <td>{{ $r['row'] }}</td>
                                    <td style="font-weight:600;">{{ $r['name'] }}</td>
                                    <td style="text-align:center;">{{ number_format($r['vl'], 3) }}</td>
                                    <td style="text-align:center;">{{ number_format($r['sl'], 3) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($r['as_of'])->format('M d, Y') }}</td>
                                    <td style="color:var(--text-secondary);">{{ $r['notes'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endisset

    {{-- ── Upload form ── --}}
    <div class="stat-card anim-fade-up delay-1 mb-3">
        <div style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(0,0,0,0.06);">
            <i class="bi bi-upload me-2"></i>Upload File
        </div>

        <form method="POST" action="{{ route('hr.leave-balances.import.run') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">CSV or Excel file</label>
                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                </div>
                <div class="col-md-6 d-flex gap-2 flex-wrap">
                    <button type="submit" name="mode" value="check" class="btn btn-outline-secondary">
                        <i class="bi bi-search me-1"></i> Check File Only
                    </button>
                    <button type="submit" name="mode" value="import" class="btn btn-primary"
                        onclick="return confirm('Create these opening balances now? Each employee can only have ONE opening balance, so this cannot be repeated for the same employees.')">
                        <i class="bi bi-check-lg me-1"></i> Check &amp; Import
                    </button>
                </div>
            </div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:10px;">
                Tip: run <strong>Check File Only</strong> first. It reads every row and reports problems, but saves nothing.
            </div>
        </form>
    </div>

    {{-- ── How the file must look ── --}}
    <div class="stat-card anim-fade-up delay-2">
        <div style="font-size:13px;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(0,0,0,0.06);">
            <i class="bi bi-info-circle me-2"></i>File Format
        </div>

        <div class="table-responsive mb-3">
            <table class="table mb-0" style="font-size:12.5px;">
                <thead>
                    <tr><th>Column</th><th>Required?</th><th>What to put</th></tr>
                </thead>
                <tbody>
                    <tr><td><code>gov_email</code></td><td>Yes</td><td>The employee's government email, exactly as saved in their profile. This is how we find the employee.</td></tr>
                    <tr><td><code>vl_balance</code></td><td>Yes</td><td>Current Vacation Leave balance, e.g. <code>15.250</code> (0 or more, up to 3 decimals).</td></tr>
                    <tr><td><code>sl_balance</code></td><td>Yes</td><td>Current Sick Leave balance, e.g. <code>8.000</code>.</td></tr>
                    <tr><td><code>as_of_date</code></td><td>Yes</td><td>The date the balances are correct as of, written <code>YYYY-MM-DD</code> (e.g. <code>2026-08-31</code>). Cannot be a future date.</td></tr>
                    <tr><td><code>notes</code></td><td>No</td><td>Optional, e.g. "Migrated from Leave Card".</td></tr>
                </tbody>
            </table>
        </div>

        <ul style="font-size:12.5px;color:var(--text-secondary);margin:0;padding-left:1.1rem;line-height:1.7;">
            <li><strong>All or nothing:</strong> if any row has a problem, no row is imported. Fix the file and upload it again.</li>
            <li>An employee who <strong>already has a balance</strong> is rejected — use Manual Adjustment on their page to correct it.</li>
            <li>Set <code>as_of_date</code> to the last day of the last month already counted in the balance, and only run <em>Compute Monthly</em> for months <strong>after</strong> that date, otherwise those months get credited twice.</li>
            <li>In Excel, format the date column as text (or use real date cells) so it isn't changed to <code>8/31/2026</code>.</li>
        </ul>
    </div>

@endsection