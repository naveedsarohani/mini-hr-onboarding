<!DOCTYPE html>
<html lang="<?= config('app.locale') ?>">

<head>
    <meta charset="UTF-8">
    <title>New Employee Hired</title>
</head>

<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f6f9fc; color:#333;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding:20px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background-color:#2563eb; color:#ffffff; padding:16px 24px; font-size:20px; font-weight:bold; text-align:center;">
                            <?= config('app.name') ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px; font-size:15px; line-height:1.6;">
                            <p style="margin-top:0;">Dear Manager,</p>

                            <p>A new employee has been successfully hired:</p>

                            <table cellpadding="0" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:12px;">
                                <tr>
                                    <td style="font-weight:bold; width:30%;">Name:</td>
                                    <td><?= __($employeeName) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Email:</td>
                                    <td><?= __($employeeEmail) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Hired On:</td>
                                    <td><?= __($employeeHireDate) ?></td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;">Regards,<br><?= config('app.name') ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>