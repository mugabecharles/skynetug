<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'SkyNetug' }}</title>
    <style>
        body { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue',Arial,sans-serif; font-size:15px; color:#1C2333; line-height:1.6; }
        .wrapper { max-width:620px; margin:32px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.07); }
        .header { background:linear-gradient(135deg,#0A0F1E,#0D1433); padding:28px 36px; text-align:center; }
        .header img { height:40px; margin-bottom:8px; }
        .header-title { color:#fff; font-size:1.3rem; font-weight:700; margin:0; }
        .header-sub { color:rgba(255,255,255,.6); font-size:.85rem; margin:4px 0 0; }
        .body { padding:36px; }
        .greeting { font-size:1.05rem; font-weight:600; margin-bottom:12px; }
        .message { color:#374151; margin-bottom:20px; }
        .btn { display:inline-block; background:#0066FF; color:#fff !important; text-decoration:none; border-radius:8px; padding:13px 28px; font-weight:700; font-size:.95rem; margin:8px 0; }
        .btn-green { background:#00C896; }
        .info-box { background:#f8fafc; border:1px solid #e8ecf0; border-radius:10px; padding:20px; margin:20px 0; }
        .info-box table { width:100%; border-collapse:collapse; }
        .info-box td { padding:7px 0; font-size:.9rem; }
        .info-box td:first-child { color:#6B7280; width:44%; }
        .info-box td:last-child { font-weight:600; color:#0A0F1E; }
        .divider { border:none; border-top:1px solid #f3f4f6; margin:24px 0; }
        .footer { background:#f8fafc; border-top:1px solid #e8ecf0; padding:20px 36px; text-align:center; font-size:.78rem; color:#9CA3AF; }
        .footer a { color:#0066FF; text-decoration:none; }
        .alert-warning { background:#FEF3C7; border-left:4px solid #F59E0B; border-radius:6px; padding:14px 16px; margin:16px 0; font-size:.9rem; color:#92400e; }
        .alert-danger  { background:#FEE2E2; border-left:4px solid #EF4444; border-radius:6px; padding:14px 16px; margin:16px 0; font-size:.9rem; color:#991b1b; }
        .alert-success { background:#D1FAE5; border-left:4px solid #10B981; border-radius:6px; padding:14px 16px; margin:16px 0; font-size:.9rem; color:#065f46; }
        .credential-box { background:#0A0F1E; border-radius:10px; padding:20px; margin:20px 0; }
        .credential-box table { width:100%; border-collapse:collapse; }
        .credential-box td { padding:6px 0; font-size:.9rem; color:#d1d5db; }
        .credential-box td:first-child { color:#9CA3AF; width:44%; }
        .credential-box td:last-child { font-weight:700; color:#fff; font-family:monospace; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-title">SkyNetug</div>
        <div class="header-sub">Web Hosting &amp; Domain Registration · Uganda</div>
    </div>

    {{-- Body --}}
    <div class="body">
        @yield('content')
    </div>

    <hr class="divider" style="margin:0;">

    {{-- Footer --}}
    <div class="footer">
        <p style="margin:0 0 6px;">
            <strong>SkyNetug Ltd</strong> · Kampala, Uganda<br>
            <a href="{{ config('app.url') }}">{{ config('app.url') }}</a> &nbsp;·&nbsp;
            <a href="mailto:support@skynetug.com">support@skynetug.com</a>
        </p>
        <p style="margin:6px 0 0;">
            You are receiving this email because you have an account with SkyNetug.
            If you believe this is an error, please
            <a href="mailto:support@skynetug.com">contact support</a>.
        </p>
    </div>

</div>
</body>
</html>
