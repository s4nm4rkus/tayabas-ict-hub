{{-- ══════════════════════════════════════════════════════════════════════════
     RECORD CHANGE MODAL
     ══════════════════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div id="rcm-overlay"
    style="display:none;position:fixed;inset:0;z-index:1050;
           background:rgba(0,0,0,0.55);backdrop-filter:blur(3px);">
</div>

{{-- Modal panel --}}
<div id="rcm-panel"
    style="display:none;position:fixed;top:50%;left:50%;
           transform:translate(-50%,-50%);z-index:1055;
           width:calc(100% - 1.5rem);max-width:540px;max-height:92vh;
           overflow-y:auto;background:var(--card-bg, #ffffff);
           border-radius:var(--radius);border:1px solid var(--border);
           box-shadow:0 24px 64px rgba(0,0,0,0.25);">

    {{-- Sticky header --}}
    <div
        style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);
         display:flex;align-items:center;justify-content:space-between;
         position:sticky;top:0;background:var(--card-bg, #ffffff);z-index:1;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div
                style="width:30px;height:30px;border-radius:8px;background:rgba(139,92,246,0.12);
                 display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-arrow-up-circle" style="color:#7C3AED;font-size:14px;"></i>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--text-primary);line-height:1.2;">
                    Record Employment Change
                </div>
                <div style="font-size:11px;color:var(--text-secondary);">
                    Updates employment info &amp; regenerates service records.
                </div>
            </div>
        </div>
        <button type="button" onclick="closeRecordChangeModal()"
            style="width:28px;height:28px;border-radius:7px;border:1px solid var(--border);
            background:var(--bg);color:var(--text-secondary);cursor:pointer;
            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-x" style="font-size:17px;"></i>
        </button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('hr.employees.history.store', $employee->user_id) }}"
        style="padding:1rem 1.25rem;">
        @csrf

        {{-- Reason — compact 5-column grid --}}
        <div class="mb-3">
            <label
                style="font-size:11px;font-weight:600;color:var(--text-secondary);
                   text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:6px;">
                Reason for Change <span class="text-danger">*</span>
            </label>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px;">
                @foreach ([['value' => 'PROMOTION', 'icon' => 'bi-arrow-up-circle', 'color' => '#059669', 'short' => 'Promotion'], ['value' => 'DEMOTION', 'icon' => 'bi-arrow-down-circle', 'color' => '#B91C1C', 'short' => 'Demotion'], ['value' => 'TRANSFER', 'icon' => 'bi-arrow-left-right', 'color' => '#2563EB', 'short' => 'Transfer'], ['value' => 'RECLASSIFICATION', 'icon' => 'bi-shuffle', 'color' => '#B45309', 'short' => 'Reclassify'], ['value' => 'REINSTATEMENT', 'icon' => 'bi-arrow-counterclockwise', 'color' => '#7C3AED', 'short' => 'Reinstate']] as $reason)
                    <label style="cursor:pointer;">
                        <input type="radio" name="change_reason" value="{{ $reason['value'] }}" style="display:none;"
                            class="reason-radio" required>
                        <div class="reason-card"
                            style="padding:8px 4px;border:1.5px solid var(--border);
                            border-radius:var(--radius-sm);text-align:center;font-size:10px;
                            font-weight:600;color:var(--text-secondary);transition:all 0.2s;
                            background:var(--bg);user-select:none;line-height:1.2;"
                            data-color="{{ $reason['color'] }}">
                            <i class="bi {{ $reason['icon'] }}"
                                style="display:block;font-size:14px;margin-bottom:3px;"></i>
                            {{ $reason['short'] }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Effective Date + Position on same row --}}
        <div class="row g-2 mb-2">
            <div class="col-5">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    Effective Date <span class="text-danger">*</span>
                </label>
                <input type="date" name="effective_date" class="form-control form-control-sm"
                    value="{{ now()->toDateString() }}" required style="font-size:12px;">
            </div>
            <div class="col-7">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    New Position <span class="text-danger">*</span>
                </label>
                <input type="text" name="position" class="form-control form-control-sm"
                    value="{{ $employee->employment?->position }}" required style="font-size:12px;"
                    placeholder="e.g. Teacher III">
            </div>
        </div>

        {{-- Designation --}}
        <div class="mb-2">
            <label
                style="font-size:11px;font-weight:600;color:var(--text-secondary);
                   text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                Designation (if any)
            </label>
            <select name="sub_position" class="form-select form-select-sm" style="font-size:12px;">
                <option value="">— None —</option>
                @foreach ($subPositions as $sub)
                    <option value="{{ $sub->sub_position }}"
                        {{ $employee->employment?->sub_position == $sub->sub_position ? 'selected' : '' }}>
                        {{ $sub->sub_position }}{{ $sub->main_pos ? ' (' . $sub->main_pos . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- SG + Step + Nature + Status in 2x2 grid --}}
        <div class="row g-2 mb-2">
            <div class="col-6">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    Salary Grade <span class="text-danger">*</span>
                </label>
                <select name="salary_grade" id="rcm_salary_grade" class="form-select form-select-sm"
                    style="font-size:12px;" required>
                    <option value="">— Select —</option>
                    @foreach ($salaryGrades as $sg)
                        <option value="{{ $sg->salary_grade }}"
                            {{ $employee->employment?->salary_grade == $sg->salary_grade ? 'selected' : '' }}>
                            SG {{ $sg->salary_grade }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    Salary Step <span class="text-danger">*</span>
                </label>
                <select name="salary_step" id="rcm_salary_step" class="form-select form-select-sm"
                    style="font-size:12px;" required>
                    @foreach (range(1, 8) as $step)
                        <option value="{{ $step }}"
                            {{ $employee->employment?->salary_step == $step ? 'selected' : '' }}>
                            Step {{ $step }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    Nature of Appointment
                </label>
                <select name="nature_appoint" class="form-select form-select-sm" style="font-size:12px;">
                    <option value="">— Select —</option>
                    @foreach ($natureOptions as $opt)
                        <option value="{{ $opt->option_label }}"
                            {{ $employee->employment?->nature_appoint == $opt->option_label ? 'selected' : '' }}>
                            {{ $opt->option_label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label
                    style="font-size:11px;font-weight:600;color:var(--text-secondary);
                       text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:4px;">
                    Status of Appointment
                </label>
                <select name="status_appoint" class="form-select form-select-sm" style="font-size:12px;">
                    <option value="">— Select —</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt->option_label }}"
                            {{ $employee->employment?->status_appoint == $opt->option_label ? 'selected' : '' }}>
                            {{ $opt->option_label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Live salary preview --}}
        <div id="rcm_salary_preview"
            style="display:none;background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.2);
                   border-radius:var(--radius-sm);padding:8px 12px;margin-bottom:0.75rem;
                   font-size:12px;color:var(--text-primary);align-items:center;gap:6px;">
            <i class="bi bi-cash-coin" style="color:#059669;"></i>
            Monthly: <strong id="rcm_monthly_val">—</strong>
            &nbsp;·&nbsp; Annual: <strong id="rcm_annual_val">—</strong>
        </div>

        {{-- Warning --}}
        <div
            style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);
             border-radius:var(--radius-sm);padding:8px 12px;margin-bottom:0.75rem;
             font-size:11px;color:#92400E;display:flex;gap:6px;align-items:flex-start;">
            <i class="bi bi-exclamation-triangle" style="flex-shrink:0;margin-top:1px;"></i>
            <span>This will update employment info and regenerate all service records automatically.</span>
        </div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-primary btn-sm" style="flex:1;font-size:13px;">
                <i class="bi bi-check-lg me-1"></i> Save Change
            </button>
            <button type="button" onclick="closeRecordChangeModal()" class="btn btn-outline-secondary btn-sm"
                style="font-size:13px;">
                Cancel
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        const salaryTable = @json($salaryGrades->keyBy('salary_grade'));

        function openRecordChangeModal() {
            document.getElementById('rcm-overlay').style.display = 'block';
            document.getElementById('rcm-panel').style.display = 'block';
            document.body.style.overflow = 'hidden';
            updateSalaryPreview();
        }

        function closeRecordChangeModal() {
            document.getElementById('rcm-overlay').style.display = 'none';
            document.getElementById('rcm-panel').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('rcm-overlay').addEventListener('click', closeRecordChangeModal);

        document.querySelectorAll('.reason-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.reason-card').forEach(card => {
                    card.style.borderColor = 'var(--border)';
                    card.style.color = 'var(--text-secondary)';
                    card.style.background = 'var(--bg)';
                });
                if (this.checked) {
                    const card = this.nextElementSibling;
                    const color = card.dataset.color;
                    card.style.borderColor = color;
                    card.style.color = color;
                    card.style.background = color + '18';
                }
            });
        });

        function updateSalaryPreview() {
            const sg = document.getElementById('rcm_salary_grade').value;
            const step = document.getElementById('rcm_salary_step').value;
            const preview = document.getElementById('rcm_salary_preview');

            if (!sg || !step) {
                preview.style.display = 'none';
                return;
            }

            const gradeData = salaryTable[sg];
            if (!gradeData) {
                preview.style.display = 'none';
                return;
            }

            const monthly = parseFloat(gradeData['step_' + step]);
            if (!monthly) {
                preview.style.display = 'none';
                return;
            }

            document.getElementById('rcm_monthly_val').textContent =
                '₱' + monthly.toLocaleString('en-PH', {
                    minimumFractionDigits: 2
                });
            document.getElementById('rcm_annual_val').textContent =
                '₱' + (monthly * 12).toLocaleString('en-PH', {
                    minimumFractionDigits: 2
                });

            preview.style.display = 'flex';
        }

        document.getElementById('rcm_salary_grade').addEventListener('change', updateSalaryPreview);
        document.getElementById('rcm_salary_step').addEventListener('change', updateSalaryPreview);
    </script>
@endpush
