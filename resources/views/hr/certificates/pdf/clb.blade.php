<x-pdf.certificates.layout title="Certificate of Leave Balance">

    <div class="salutation">To whom it may concern:</div>

    <div class="body-text">
        This is to certify that as per our records, <span class="highlight">{{ $employee->full_name }}</span>,
        <span class="highlight">{{ $employee->employment?->nature_appoint ?? 'permanent' }}</span>
        <span class="highlight">{{ $employee->employment?->position ?? ($employee->user?->user_pos ?? '—') }}</span>
        at City Schools Division of the City of Tayabas has the following accumulated leave balances as of
        <span class="highlight">{{ now()->format('F d, Y') }}</span>:
    </div>

    @php
        $leaveTypes = ['Vacation Leave', 'Sick Leave', 'Force Leave', 'Special Leave Benefits'];
        $approvedDays = [];
        foreach ($leaveTypes as $type) {
            $approvedDays[$type] = $employee->leaves
                ->where('leavetype', $type)
                ->where('leave_status', 'Approved')
                ->sum('total_days');
        }
        $totalPoints = $employee->points->sum('acc_points');
    @endphp
    <table class="leave-table" style="margin: 16px auto; width: 70%; border-collapse: collapse;">
        <thead>
            <tr>
                <th
                    style="border: 1px solid #000; padding: 10px; text-align: center; font-weight: bold; background: #f5f5f5;">
                    Leave Type</th>
                <th
                    style="border: 1px solid #000; padding: 10px; text-align: center; font-weight: bold; background: #f5f5f5;">
                    Days Used</th>
                <th
                    style="border: 1px solid #000; padding: 10px; text-align: center; font-weight: bold; background: #f5f5f5;">
                    Points Earned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypes as $type)
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $type }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                        {{ $approvedDays[$type] ?? 0 }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">
                        {{ $type === 'Vacation Leave' ? round($totalPoints, 4) : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="body-text" style="text-align: center; margin-top: 16px;">
        Total Accumulated Leave Points: <span class="highlight">{{ round($totalPoints, 4) }}</span>
    </div>

    <div class="body-text" style="text-indent: 50px">
        Issued upon request of Ms/Mr. {{ $employee->last_name }} this <strong>{{ now()->format('jS') }}</strong> day of
        <strong>{{ now()->format('F Y') }}</strong> for whatever legal purpose this may serve.
    </div>


    {{-- SIGNATURE --}}
    <div class="signature-block" style="text-align: right; align-self: flex-end;">
        <div class="signature-name">CONRADO C. GABARDA</div>
        <div class="signature-title" style="margin-right: 30px">Administrative Officer V</div>
    </div>

    @slot('footer')
        <x-pdf.certificates.footer footer-title="Cert. of Leave Balance" footer-code="HRC-CLB" />
    @endslot

</x-pdf.certificates.layout>
