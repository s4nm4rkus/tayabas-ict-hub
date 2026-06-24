<x-pdf.leave.layout-form title="CS Form No. 6 - Application for Leave">
    @slot('styles')
        <x-pdf.leave.styles-form />
    @endslot

    @php
        $leaveTypes = array_map('trim', explode(',', $leave->leave_types ?? ($leave->leavetype ?? '')));
        $details = $leave->leave_details ?? '';

        function detailContains(string $needle, string $haystack): bool
        {
            return stripos($haystack, $needle) !== false;
        }

        function detailValue(string $prefix, string $haystack): string
        {
            if (preg_match('/' . preg_quote($prefix, '/') . '\s*:\s*([^;]+)/i', $haystack, $m)) {
                return trim($m[1]);
            }
            return '';
        }

        $logoUrl = asset('storage/logo-nav.png');
        $empSigUrl = $leave->employee?->user?->e_signature
            ? asset('storage/' . $leave->employee->user->e_signature)
            : null;
        $headSigUrl = $leave->head_esign_path
            ? asset('storage/' . $leave->head_esign_path)
            : ($leave->deptHead?->e_signature
                ? asset('storage/' . $leave->deptHead->e_signature)
                : null);
        $hrSigUrl = $leave->hr_esign_path
            ? asset('storage/' . $leave->hr_esign_path)
            : ($leave->hrApprover?->e_signature
                ? asset('storage/' . $leave->hrApprover->e_signature)
                : null);
        $aoSigUrl = $leave->ao_esign_path
            ? asset('storage/' . $leave->ao_esign_path)
            : ($leave->aoApprover?->e_signature
                ? asset('storage/' . $leave->aoApprover->e_signature)
                : null);
        $asdsSigUrl = $leave->asds_esign_path
            ? asset('storage/' . $leave->asds_esign_path)
            : ($leave->asdsApprover?->e_signature
                ? asset('storage/' . $leave->asdsApprover->e_signature)
                : null);

        $chk = '/';
    @endphp

    {{-- ══ PAGE 1 ══ --}}

    <div class="form6-tag">
        <p>Civil Service Form No. 6</p>
        <p>Revised 2020</p>
    </div>

    {{-- HEADER --}}
    <table style="margin-bottom: 6px;">
        <tr>
            <td style="width: 80px; text-align: center; vertical-align: middle;">
                <img src="{{ $logoUrl }}" style="width: 65px; height: auto;" alt="Logo">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <p style="font-size: 10px; font-style: italic; margin: 0; line-height: 1.5;">Republic of the Philippines
                </p>
                <p style="font-size: 10px; font-style: italic; margin: 0; line-height: 1.5;">City Schools Division of the
                    City of Tayabas</p>
                <p style="font-size: 10px; font-style: italic; margin: 0; line-height: 1.5;">Brgy. Potol, Tayabas City
                </p>
                <p style="font-size: 13px; font-weight: bold; text-transform: uppercase; margin: 4px 0 0;">Application
                    for Leave</p>
            </td>
            <td style="width: 130px; text-align: right; vertical-align: middle;">
                <div class="stamp-box">Stamp of Date Receipt</div>
            </td>
        </tr>
    </table>

    <div class="main-container">
        {{-- Sections continue here... (copy from original, just clean up the formatting) --}}
        {{-- Row 1: Department + Name --}}
        <table style="border-bottom: 1px solid #000;">
            <tr>
                <td class="cell-pad" style="width: 28%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">1. Office/Department</span>
                    <div class="cell-value">{{ $leave->department }}</div>
                </td>
                <td class="cell-pad" style="width: 24%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">2. Name (Last Name)</span>
                    <div class="cell-value">{{ $leave->employee?->last_name ?? '' }}</div>
                </td>
                <td class="cell-pad" style="width: 24%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label" style="font-weight: normal;">(First Name)</span>
                    <div class="cell-value">{{ $leave->employee?->first_name ?? '' }}</div>
                </td>
                <td class="cell-pad" style="width: 24%; vertical-align: top;">
                    <span class="cell-label" style="font-weight: normal;">(Middle Name)</span>
                    <div class="cell-value">{{ $leave->employee?->middle_name ?? '' }}</div>
                </td>
            </tr>
        </table>

        {{-- Row 2: Date + Position + Salary --}}
        <table style="border-bottom: 1px solid #000;">
            <tr>
                <td class="cell-pad" style="width: 33%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">3. Date of Filing</span>
                    <div class="cell-value">{{ $leave->date_applied?->format('m/d/Y') }}</div>
                </td>
                <td class="cell-pad" style="width: 40%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">4. Position</span>
                    <div class="cell-value">{{ $leave->position }}</div>
                </td>
                <td class="cell-pad" style="width: 27%; vertical-align: top;">
                    <span class="cell-label">5. Salary</span>
                    <div class="cell-value">{{ $leave->salary }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">6. Details of Application</div>

        {{-- 6A & 6B --}}
        <table style="border-bottom: 1px solid #000;">
            <tr>
                <td class="cell-pad" style="width: 52%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">6. A. TYPE OF LEAVE TO BE AVAILED OF</span>
                    @php
                        $leaveOptions = [
                            'Vacation Leave' => 'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Mandatory / Forced Leave' => 'Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Sick Leave' => 'Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Maternity Leave' => 'R.A. No. 11210 / IRR issued by CSC, DOLE and SSS',
                            'Paternity Leave' => 'R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended',
                            'Special Privilege Leave' => 'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Solo Parent Leave' => 'RA No. 8972 / CSC MC No. 8, s. 2004',
                            'Study Leave' => 'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            '10-Day VAWC Leave' => 'RA No. 9262 / CSC MC No. 15, s. 2005',
                            'Rehabilitation Privilege' => 'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                            'Special Leave Benefits for Women' => 'RA No. 9710 / CSC MC No. 25, s. 2010',
                            'Special Emergency' => 'Calamity Leave (CSC MC No. 2, s. 2012, as amended)',
                            'Adoption Leave' => 'R.A. No. 8552',
                        ];
                    @endphp
                    @foreach ($leaveOptions as $value => $ref)
                        <div class="check-item">
                            <span class="check-box">{{ in_array($value, $leaveTypes) ? $chk : '' }}</span>
                            {{ $value }} <span class="check-ref">({{ $ref }})</span>
                        </div>
                    @endforeach
                    <div class="check-item">
                        <span class="check-box">{{ in_array('Others', $leaveTypes) ? $chk : '' }}</span>
                        Others: <span class="detail-value">{{ detailValue('Others', $details) }}</span>
                    </div>
                </td>

                <td class="cell-pad" style="width: 48%; vertical-align: top;">
                    <span class="cell-label">B. DETAILS OF LEAVE</span>

                    <p class="detail-sub">In case of Vacation/Special Privilege Leave:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Within Philippines', $details) ? $chk : '' }}</span>
                        Within the Philippines <span
                            class="detail-value">{{ detailValue('Within Philippines', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Abroad', $details) ? $chk : '' }}</span>
                        Abroad (Specify) <span class="detail-value">{{ detailValue('Abroad', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Sick Leave:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('In Hospital', $details) ? $chk : '' }}</span>
                        In Hospital (Specify Illness) <span
                            class="detail-value">{{ detailValue('In Hospital', $details) }}</span>
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Out Patient', $details) ? $chk : '' }}</span>
                        Out Patient (Specify Illness) <span
                            class="detail-value">{{ detailValue('Out Patient', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Special Leave Benefits for Women:</p>
                    <div class="check-item">
                        (Specify Illness) <span
                            class="detail-value">{{ detailValue('Special Leave (Women)', $details) }}</span>
                    </div>

                    <p class="detail-sub">In case of Study Leave:</p>
                    <div class="check-item">
                        <span
                            class="check-box">{{ detailContains('Completion of Master', $details) ? $chk : '' }}</span>
                        Completion of Master's Degree
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('BAR/Board', $details) ? $chk : '' }}</span>
                        BAR/Board Examination Review
                    </div>

                    <p class="detail-sub">Other purpose:</p>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Monetization', $details) ? $chk : '' }}</span>
                        Monetization of Leave Credits
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ detailContains('Terminal Leave', $details) ? $chk : '' }}</span>
                        Terminal Leave
                    </div>
                </td>
            </tr>
        </table>

        {{-- 6C & 6D --}}
        <table style="border-bottom: 1px solid #000;">
            <tr>
                <td class="cell-pad" style="width: 55%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">6. C. NUMBER OF WORKING DAYS APPLIED FOR</span>
                    <span class="value-line">{{ $leave->number_of_days }}</span>
                    <span class="cell-label" style="margin-top: 4px;">INCLUSIVE DATES</span>
                    <span class="value-line">{{ $leave->inclusive_dates }}</span>
                </td>
                <td class="cell-pad" style="width: 45%; vertical-align: top;">
                    <span class="cell-label">6. D. COMMUTATION</span>
                    <div class="check-item">
                        <span class="check-box">{{ $leave->commutation === 'Not Required' ? $chk : '' }}</span>
                        Not Required
                    </div>
                    <div class="check-item">
                        <span class="check-box">{{ $leave->commutation === 'Required' ? $chk : '' }}</span>
                        Required
                    </div>
                    {{-- Employee Signature --}}
                    <div class="sig-block" style="margin-top: 6px;">
                        @if ($empSigUrl)
                            <img src="{{ $empSigUrl }}" class="sig-image" alt="Employee Signature">
                        @else
                            <div style="height: 35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <div class="sig-label">(Signature of Applicant)</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">7. Details of Action on Application</div>

        {{-- 7A & 7B --}}
        <table style="border-bottom: 1px solid #000;">
            <tr>
                {{-- 7A: Leave Credits + HR Sig --}}
                <td class="cell-pad" style="width: 55%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">7. A. CERTIFICATION OF LEAVE CREDITS</span>
                    <p style="font-size: 9px; margin: 3px 0;">
                        As of <span class="detail-value">{{ $leave->hr_as_of }}</span>
                    </p>
                    <table class="credits-table">
                        <tr>
                            <th style="width: 40%; text-align: left;"></th>
                            <th>Vacation Leave</th>
                            <th>Sick Leave</th>
                        </tr>
                        <tr>
                            <td style="text-align: left;"><em>Total Earned</em></td>
                            <td>{{ $leave->vl_earned }}</td>
                            <td>{{ $leave->sl_earned }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;"><em>Less this application</em></td>
                            <td>{{ $leave->vl_less }}</td>
                            <td>{{ $leave->sl_less }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;"><em>Balance</em></td>
                            <td>{{ $leave->vl_balance }}</td>
                            <td>{{ $leave->sl_balance }}</td>
                        </tr>
                    </table>
                    {{-- AO Signature --}}
                    @if ($leave->ao_esign_name)
                        <div class="sig-block">
                            @if ($aoSigUrl)
                                <img src="{{ $aoSigUrl }}" class="sig-image" alt="AO Signature">
                            @else
                                <div style="height: 35px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $leave->ao_esign_name }}</div>
                            <div class="sig-position">Administrative Officer</div>
                            <div class="sig-label">(Authorized Officer)</div>
                        </div>
                    @endif
                </td>

                {{-- 7B: Recommendation + Head Sig --}}
                <td class="cell-pad" style="width: 45%; vertical-align: top;">
                    <span class="cell-label">7. B. RECOMMENDATION</span>
                    <div class="check-item" style="margin-bottom: 4px;">
                        <span class="check-box">{{ $leave->head_esign_name ? $chk : '' }}</span>
                        For Approval
                    </div>
                    <div class="check-item">
                        <span class="check-box"></span>
                        For disapproval due to
                    </div>
                    <div style="border-bottom: 1px solid #000; min-height: 28px; margin: 2px 0 4px 14px;"></div>
                    {{-- Head Signature --}}
                    <div class="sig-block" style="margin-top: 4px;">
                        @if ($headSigUrl)
                            <img src="{{ $headSigUrl }}" class="sig-image" alt="Head Signature">
                        @else
                            <div style="height: 35px;"></div>
                        @endif
                        <div class="sig-line"></div>
                        @if ($leave->head_esign_name)
                            <div class="sig-name">{{ $leave->head_esign_name }}</div>
                            <div class="sig-position">Department Head</div>
                        @endif
                        <div class="sig-label">(Authorized Officer)</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- 7C & 7D --}}
        <table style="border-top: 2px solid #000;">
            <tr>
                <td class="cell-pad" style="width: 55%; border-right: 1px solid #000; vertical-align: top;">
                    <span class="cell-label">7.C. APPROVED FOR:</span>
                    <p style="margin-bottom: 3px;">
                        <span class="approved-line-val">{{ $leave->asds_days_with_pay }}</span> days with pay
                    </p>
                    <p style="margin-bottom: 3px;">
                        <span class="approved-line-val">{{ $leave->asds_days_without_pay }}</span> days without pay
                    </p>
                    <p style="margin-bottom: 3px;">
                        <span class="approved-line-val">{{ $leave->asds_others }}</span> others (specify)
                    </p>
                </td>
                <td class="cell-pad" style="width: 45%; vertical-align: top;">
                    <span class="cell-label">7.D. DISAPPROVED DUE TO:</span>
                    <div style="border-bottom: 1px solid #000; min-height: 40px; font-size: 9px; padding: 2px;">
                        {{ $leave->asds_disapproval }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- ASDS Signature --}}
        <table style="border-top: 1px solid #000; width: 100%;">
            <tr>
                <td style="text-align: center; padding: 0;">
                    @if ($asdsSigUrl)
                        <img src="{{ $asdsSigUrl }}"
                            style="max-width: 140px; max-height: 55px; display: block; margin: 0 auto 4px;"
                            alt="ASDS Signature">
                    @else
                        <div style="height: 55px;">&nbsp;</div>
                    @endif
                </td>
                <td style="text-align: center; padding: 6px 4px;">
                    <div class="sig-name-box" style="margin-top: 4px;">
                        @if ($leave->asds_esign_name)
                            <div style="font-size: 10px; font-weight: bold; text-transform: uppercase;">
                                {{ $leave->asds_esign_name }}
                            </div>
                            <div style="font-size: 9px;">ASDS</div>
                        @else
                            <div style="height: 14px;">&nbsp;</div>
                        @endif
                    </div>
                    <div style="margin-top: 4px;">
                        <div style="border-top: 1px solid #000; width: 250px; margin: 0 auto 2px;"></div>
                        <div class="sig-label">(Authorized Official)</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>{{-- end .main-container --}}

    <div class="page-break"></div>

    {{-- ══ PAGE 2: INSTRUCTIONS ══ --}}
    <div class="page-two">
        <div class="instructions-header">INSTRUCTIONS AND REQUIREMENTS</div>
        <table>
            <tr>
                <td class="inst-col">
                    <p>Application for any type of leave shall <strong>be made on this Form and to be accomplished at
                            least in duplicate</strong> with documentary requirements, as follows:</p>
                    {{-- Rest of instructions content... --}}
                </td>
                <td class="inst-col inst-col-right">
                    {{-- Right column instructions... --}}
                </td>
            </tr>
        </table>
    </div>

</x-pdf.leave.layout-form>
