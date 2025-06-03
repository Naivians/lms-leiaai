<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function SendVerificationLink($user, $verification_link)
    {
        $mail = new PHPMailer(true);

        $subject = 'Email Verification - LEIAAI';

        $body = '

        <img src="{{ asset("assets/img/leiaai_logo.png") }}">
        <table width="100%" bgcolor="#f2f4f6" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; margin-top: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
          <tr>
            <td style="background-color: #4a90e2; padding: 20px; text-align: center;">
              <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Verify Your Email</h1>
            </td>
          </tr>
          <tr>
            <td style="padding: 30px;">
              <p style="font-size: 16px; color: #333;">Hi ' . $user->fname . ' ,</p>
              <p style="font-size: 16px; color: #555;">
                Thank you for signing up. Please verify your email address by clicking the button below. This helps us make sure we’ve got the right address.
              </p>

              <p style="text-align: center; margin: 30px 0;">
                <a href=" ' . trim($verification_link) . '" style="background-color: #4a90e2; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">Verify Email</a>
              </p>

              <p style="font-size: 14px; color: #777;">
                If you didn’t create an account, no further action is required.
              </p>
            </td>
          </tr>
          <tr>
            <td style="background-color: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #aaa;">
              &copy; ' . date('Y') . ' Leading Edge International Aviation Academy • All rights reserved
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>';

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), "Marvin");
            $mail->addAddress($user->email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            if (!$mail->send()) {
                dd("Email not sent");
            }
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }

    public function WelcomeMessage($user, $message)
    {
        $mail = new PHPMailer(true);
        $subject = 'Welcome on board ' . $user->fname;
        $body = '<table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; margin-top: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
              <tr>
                <td align="center" style="background-color: #4a90e2; padding: 20px;">
                  <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Your App Name</h1>
                </td>
              </tr>
              <tr>
                <td style="padding: 30px;">
                  <h2 style="margin-top: 0; color: #333;">Hello {{name}},</h2>
                  <p style="color: #555; font-size: 16px; line-height: 1.6;">
                    We’re excited to have you on board. This is a sample email template that looks great in Gmail and most other clients. Feel free to customize this message as needed for your application.
                  </p>

                  <p style="text-align: center; margin: 30px 0;">
                    <a href="{{cta_link}}" style="background-color: #4a90e2; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; display: inline-block;">Take Action</a>
                  </p>

                  <p style="color: #888; font-size: 14px;">
                    If you did not request this email, please ignore it.
                  </p>
                </td>
              </tr>
              <tr>
                <td align="center" style="background-color: #f0f0f0; padding: 20px; font-size: 12px; color: #999;">
                  &copy; {{year}} Your Company. All rights reserved.<br>
                  1234 Street Rd., City, Country
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>';

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), "Marvin");
            $mail->addAddress("madera.marvin98@gmail.com");

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            if (!$mail->send()) {
                dd("Failed to send email");
            } else {
                dd("Email has been sent");
            }
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
}
