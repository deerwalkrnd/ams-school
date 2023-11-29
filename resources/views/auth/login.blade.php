<form action="{{ route('login') }}" method="POST">
    @csrf
    <Label>Email</Label>
    <input class="email" type="email" name="email" placeholder="Enter Your Email Address" />
    <Label>password</Label>
    <input id="password" class="password" type="password" name="password" placeholder="********" />

    </div>
    <button class="button button3">Login</button>
    <span><a href="/forgot-password">Forgot Password?</a></span>
    </div>

</form>
