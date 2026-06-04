<?php
// ============================================================
//  Email Configuration — switches between local & production
// ============================================================

// AUTO-DETECT environment
$is_production = (
    strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === false &&
    strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') === false
);

// ---- LOCAL (Mailtrap sandbox) ----
define('MAIL_HOST',       $is_production ? 'smtp-relay.brevo.com'      : 'sandbox.smtp.mailtrap.io');
define('MAIL_PORT',       $is_production ? 587                          : 2525);
define('MAIL_SECURE',     $is_production ? 'tls'                        : 'tls');
define('MAIL_USERNAME',   $is_production ? 'YOUR_BREVO_LOGIN_EMAIL'     : 'bd2537a2c7f91b');
define('MAIL_PASSWORD',   $is_production ? 'YOUR_BREVO_SMTP_KEY'        : '6eb575eefadd55');
define('MAIL_FROM_EMAIL', 'noreply@travelindia.com');
define('MAIL_FROM_NAME',  'The Real Travel');

// ============================================================
//  Beautiful HTML Email Templates
// ============================================================

function getOtpEmailTemplate($fname, $otp) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>OTP Verification</title>
    </head>
    <body style='margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f4;padding:40px 0;'>
        <tr><td align='center'>
          <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
            
            <!-- HEADER -->
            <tr>
              <td style='background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);padding:40px 30px;text-align:center;'>
                <h1 style='color:#ffffff;margin:0;font-size:28px;letter-spacing:2px;'>✈️ THE REAL TRAVEL</h1>
                <p style='color:#a0aec0;margin:8px 0 0;font-size:14px;'>Your Journey Begins Here</p>
              </td>
            </tr>

            <!-- BODY -->
            <tr>
              <td style='padding:40px 40px 20px;'>
                <h2 style='color:#1a1a2e;font-size:22px;margin:0 0 12px;'>Hello, $fname! 👋</h2>
                <p style='color:#4a5568;font-size:15px;line-height:1.7;margin:0 0 30px;'>
                  Thank you for signing up with <strong>The Real Travel</strong>. 
                  To complete your registration and activate your account, 
                  please use the verification code below.
                </p>

                <!-- OTP BOX -->
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr><td align='center' style='padding:10px 0 30px;'>
                    <div style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:30px 40px;display:inline-block;'>
                      <p style='color:#e2e8f0;margin:0 0 8px;font-size:13px;letter-spacing:3px;text-transform:uppercase;'>Your Verification Code</p>
                      <h1 style='color:#ffffff;margin:0;font-size:48px;letter-spacing:16px;font-weight:900;'>$otp</h1>
                    </div>
                  </td></tr>
                </table>

                <div style='background:#fff5f5;border-left:4px solid #e53e3e;border-radius:4px;padding:14px 18px;margin-bottom:30px;'>
                  <p style='color:#c53030;margin:0;font-size:14px;'>
                    ⏱️ <strong>This code expires in 1 minute.</strong> Do not share it with anyone.
                  </p>
                </div>

                <p style='color:#718096;font-size:14px;line-height:1.6;'>
                  If you did not create an account, please ignore this email. 
                  No action is required from your side.
                </p>
              </td>
            </tr>

            <!-- FOOTER -->
            <tr>
              <td style='background:#f7fafc;padding:25px 40px;border-top:1px solid #e2e8f0;text-align:center;'>
                <p style='color:#a0aec0;font-size:12px;margin:0;'>
                  © 2024 The Real Travel. All rights reserved.<br>
                  This is an automated email, please do not reply.
                </p>
              </td>
            </tr>

          </table>
        </td></tr>
      </table>
    </body>
    </html>";
}

function getPasswordResetTemplate($reset_link) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body style='margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f4;padding:40px 0;'>
        <tr><td align='center'>
          <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
            
            <!-- HEADER -->
            <tr>
              <td style='background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);padding:40px 30px;text-align:center;'>
                <h1 style='color:#ffffff;margin:0;font-size:28px;letter-spacing:2px;'>✈️ THE REAL TRAVEL</h1>
                <p style='color:#a0aec0;margin:8px 0 0;font-size:14px;'>Password Reset Request</p>
              </td>
            </tr>

            <!-- BODY -->
            <tr>
              <td style='padding:40px 40px 20px;'>
                <h2 style='color:#1a1a2e;font-size:22px;margin:0 0 12px;'>Reset Your Password 🔐</h2>
                <p style='color:#4a5568;font-size:15px;line-height:1.7;margin:0 0 30px;'>
                  We received a request to reset your password. 
                  Click the button below to create a new password. 
                  This link is valid for <strong>24 hours</strong>.
                </p>

                <!-- BUTTON -->
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr><td align='center' style='padding:10px 0 30px;'>
                    <a href='$reset_link' style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#ffffff;text-decoration:none;padding:16px 40px;border-radius:8px;font-size:16px;font-weight:bold;display:inline-block;letter-spacing:1px;'>
                      Reset My Password →
                    </a>
                  </td></tr>
                </table>

                <div style='background:#fff5f5;border-left:4px solid #e53e3e;border-radius:4px;padding:14px 18px;margin-bottom:30px;'>
                  <p style='color:#c53030;margin:0;font-size:14px;'>
                    ⚠️ If you did not request a password reset, please ignore this email. 
                    Your account is safe.
                  </p>
                </div>

                <p style='color:#718096;font-size:13px;'>
                  If the button does not work, copy and paste this link:<br>
                  <a href='$reset_link' style='color:#667eea;word-break:break-all;'>$reset_link</a>
                </p>
              </td>
            </tr>

            <!-- FOOTER -->
            <tr>
              <td style='background:#f7fafc;padding:25px 40px;border-top:1px solid #e2e8f0;text-align:center;'>
                <p style='color:#a0aec0;font-size:12px;margin:0;'>
                  © 2024 The Real Travel. All rights reserved.<br>
                  This is an automated email, please do not reply.
                </p>
              </td>
            </tr>

          </table>
        </td></tr>
      </table>
    </body>
    </html>";
}
?>
