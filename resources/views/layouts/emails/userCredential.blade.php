<div>
    <p>Dear {{ucfirst($name)}},</p>
    <p>Your credentials for the Attendance Management System is:</p>
    <p><strong>Email:</strong> {{$email}} <br>
    <strong>Password:</strong>password2@23<br>
    <strong>Role:</strong> @if(count($roles)>1) 
            @foreach($roles as $role)
                {{ucfirst($role)}}
                @if($loop->iteration == 1)
                ,
                @endif
            @endforeach
            @else
                {{ucfirst($roles[0])}}
            @endif
        </p>
    <p>
        Please login using this link: <a href="https://attendance.deerwalk.edu.np">
            attendance.deerwalk.edu.np
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
        <a href="deerwalk.edu.np">deerwalk.edu.np</a>
        <br>
        <p style="color:888888; font-family: ui-monospace;">
            DISCLAIMER:<br>
            This is an automatically generated email - please do not reply to it. If you have any queries please contact Admistration.
        </p>
    </div>
</div>