@extends('layout.email')

@section('content')
    <tr>
        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <div class="f-fallback">
                            <h1>Hi there,</h1>
                            <p>We've received a password reset request for your account.</p>
                            <!-- Action -->
                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center">
                                        <!-- Border based button
                     https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ $resetLink }}" class="f-fallback button button--gold" target="_blank">Reset Password</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p>This link will expire in 60 minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>
                            <p>Thanks,
                                <br>The PSYKHE Fashion Team</p>
                            <!-- Sub copy -->
                            <table class="body-sub" role="presentation">
                                <tr>
                                    <td>
                                        <p class="f-fallback sub">If you're having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                        <p class="f-fallback sub">{{ $resetLink  }}</p>
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
