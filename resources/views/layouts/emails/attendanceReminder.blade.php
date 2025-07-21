<div>
    <p>Hello {{ ucfirst($teacherName) }},</p>
    <p>This is a friendly reminder that you haven't taken attendance for <strong>{{ $date }}</strong> yet.</p>
    <p>
        <strong>Section:</strong> {{ $sectionName }} <br>
        <strong>Grade:</strong> {{ $gradeName }}
    </p>
    <p>Please log in to the system and take attendance as soon as possible.</p>
    <p>
        Please login using this link: <a href="https://attendance-dss.deerwalk.edu.np/" style="color: #3490dc; text-decoration: none;">
            Take Attendance Now
        </a>
    </p>
    <p>Regards,</p>
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
