<div>
    <p>Hello Admin,</p>
    <p>This is to inform you that <strong>{{ $teacherName }}</strong> has not taken attendance for <strong>{{ $date }}</strong>.</p>
    <p>
        <strong>Teacher Details:</strong><br>
        - Name: {{ $teacherName }}<br>
        - Email: {{ $teacherEmail }}<br>
        - Section: {{ $sectionName }}<br>
        - Grade: {{ $gradeName }}
    </p>
    <p>A reminder has been sent to the teacher. Please follow up if necessary.</p>
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
