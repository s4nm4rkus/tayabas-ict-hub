@extends('layouts.hr')
@section('title', 'Certificate Preview - ' . $employee->full_name)

@section('content')

    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 style="margin-bottom: 4px;">Certificate Preview</h4>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                    {{ $certRequest->req_type }} • {{ $employee->full_name }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()"
                    style="display:inline-flex;align-items:center;gap:6px;
                       padding:8px 16px;border:1px solid #ddd;border-radius:6px;
                       background:#fff;cursor:pointer;font-size:13px;font-weight:600;">
                    <i class="bi bi-printer"></i> Print
                </button>
                <a href="{{ route('hr.certificates.pdf', $certRequest->id) }}"
                    style="display:inline-flex;align-items:center;gap:6px;
                       padding:8px 16px;border:none;border-radius:6px;
                       background:rgba(239,68,68,0.1);color:#B91C1C;cursor:pointer;
                       font-size:13px;font-weight:600;text-decoration:none;transition:all var(--transition);">
                    <i class="bi bi-download"></i> Download PDF
                </a>
                <a href="{{ route('hr.certificates.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;
                       padding:8px 16px;border:1px solid #ddd;border-radius:6px;
                       background:#fff;cursor:pointer;font-size:13px;font-weight:600;
                       text-decoration:none;transition:all var(--transition);">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div style="background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;">
        <iframe src="data:application/pdf;base64,{{ $pdfBase64 }}" style="width: 100%; height: 900px; border: none;">
        </iframe>
    </div>

    <style>
        @media print {

            button,
            a[href],
            .d-flex {
                display: none !important;
            }

            iframe {
                height: auto;
                border: none;
            }
        }
    </style>

@endsection
