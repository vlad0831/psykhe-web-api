@extends('layout.email')

@section('content')
    <tr>
        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hi {{ $name }},</h1>
                            <p>Welcome to <strong>{{ $app }}</strong>. Please click the link below to activate your account by verifying your email address.</p>
                            <!-- Action -->
                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Border based button
                     https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ $activationLink }}" class="f-fallback button button--green" target="_blank">Verify your email address</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p>Once your email has been properly activated, you will be able to proceed with our on-boarding process.</p>
                            <p>Thanks,
                                <br>The {{ $app }} Team</p>
                            <!-- Sub copy -->
                            <table class="body-sub" role="presentation">
                                <tr>
                                    <td>
                                        <p class="f-fallback sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                        <p class="f-fallback sub">{{ $activationLink  }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
