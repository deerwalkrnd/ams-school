<!-- This is the new Blade template for the consolidated admin attendance summary -->
<div>
    <p>Hello Admin,</p>
    <p>This is a daily summary of teachers who have not yet taken attendance for <strong>{{ $date }}</strong>:</p>
    @if($teachers->isNotEmpty())
        <table style="width:100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Teacher Name</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Email</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Section</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $teacher->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $teacher->email }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $teacher->section->name ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $teacher->section->grade->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Individual reminders have been sent to these teachers. Please follow up if necessary.</p>
    @else
        <p>All teachers have taken attendance for today. No pending attendance found.</p>
    @endif
    <p>Thanks,</p>
    <br>
    <div id="signature">
        --<br>
        <span style="color:rgba(231, 94, 15, 1);"><b>AMS SYSTEM</b></span><br>
        Deerwalk Sifal School<br>
        Sifal, Kathmandu<br>
        Nepal<br>
        <a href="deerwalk.edu.np" style="color: #3490dc; text-decoration: none;">deerwalk.edu.np</a>
        <br>
        <p style="color:#888888; font-family: ui-monospace; font-size: 0.8em;">
            DISCLAIMER:<br>
            This is an automatically generated email - please do not reply to it. If you have any queries please contact Administration.
        </p>
    </div>
</div>