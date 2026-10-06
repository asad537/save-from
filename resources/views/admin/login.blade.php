<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin Login — Save-Froms.net</title>
    <link rel="icon" href="{{ asset('images/save-froms-icon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(circle at 20% 10%,#dcebff 0,transparent 34%),linear-gradient(145deg,#f8fcff,#edf6ff);font-family:'DM Sans',sans-serif;color:#152746}.login-shell{width:100%;max-width:430px}.logo{display:block;width:245px;height:58px;margin:0 auto 25px;background:url('{{ asset('images/save-froms-logo.svg') }}') center/contain no-repeat}.login-card{padding:34px;background:#fff;border:1px solid #dce8f7;border-radius:22px;box-shadow:0 24px 70px rgba(30,81,148,.16)}.badge{width:48px;height:48px;display:grid;place-items:center;margin-bottom:20px;border-radius:14px;background:linear-gradient(135deg,#2878ff,#7048f5);color:#fff;font-size:21px}.login-card h1{margin:0 0 8px;font:700 30px 'Space Grotesk',sans-serif}.lead{margin:0 0 26px;color:#6a7d99;font-size:14px;line-height:1.6}.field{margin-bottom:17px}.field label{display:block;margin-bottom:7px;font-weight:700;font-size:13px}.input-wrap{position:relative}.input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#7990af}.input-wrap input{width:100%;height:50px;padding:0 42px;border:1px solid #d7e3f3;border-radius:11px;outline:0;color:#193252;font:500 14px 'DM Sans',sans-serif;transition:.2s}.input-wrap input:focus{border-color:#5c96fa;box-shadow:0 0 0 4px #e9f2ff}.toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#7187a6;cursor:pointer}.submit{width:100%;height:51px;margin-top:5px;border:0;border-radius:11px;background:linear-gradient(135deg,#2b72f5,#1d5ee9);color:#fff;font:700 15px 'DM Sans',sans-serif;cursor:pointer;box-shadow:0 12px 28px rgba(33,102,243,.25)}.submit:hover{filter:brightness(1.05)}.error,.status{padding:11px 13px;margin-bottom:18px;border-radius:9px;font-size:13px}.error{background:#fff0f0;color:#b33b3b;border:1px solid #ffd0d0}.status{background:#eafaf1;color:#17834a;border:1px solid #c9eed9}.back{display:block;margin-top:20px;text-align:center;color:#5b7190;font-size:13px;text-decoration:none}.back:hover{color:#2166f3}
    </style>
</head>
<body>
    <main class="login-shell">
        <a class="logo" href="{{ route('home') }}" aria-label="Save-Froms home"></a>
        <section class="login-card">
            <div class="badge"><i class="bi bi-shield-lock-fill"></i></div>
            <h1>Admin Login</h1>
            <p class="lead">Sign in to manage Save-Froms.net.</p>
            @if(session('status'))<div class="status">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="error"><i class="bi bi-exclamation-circle"></i>&nbsp; {{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('admin.authenticate') }}">
                @csrf
                <div class="field"><label for="email">Email address</label><div class="input-wrap"><i class="bi bi-envelope"></i><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus></div></div>
                <div class="field"><label for="password">Password</label><div class="input-wrap"><i class="bi bi-key"></i><input id="password" name="password" type="password" autocomplete="current-password" required><button class="toggle" type="button" onclick="var p=document.getElementById('password');p.type=p.type==='password'?'text':'password'" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
                <button class="submit" type="submit"><i class="bi bi-box-arrow-in-right"></i>&nbsp; Sign In Securely</button>
            </form>
            <a class="back" href="{{ route('home') }}"><i class="bi bi-arrow-left"></i>&nbsp; Back to website</a>
        </section>
    </main>
</body>
</html>
