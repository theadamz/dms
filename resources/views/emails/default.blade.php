<div style="min-width:100%;width:100%!important;color:#1a2e44;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;font-size:14px;line-height:1.5;margin:0;padding:0"
    bgcolor="#f8fafa">
    <div style="min-width:100%;width:100%!important;background-color:#f8fafa;margin:0;padding:32px 0">
        <table style="width:100%;border-collapse:collapse;border-spacing:0;table-layout:fixed;padding:0;border-width:0"
            height="100%">
            <tbody>
                <tr style="padding:0">
                    <td style="border-collapse:collapse!important;word-break:break-word;min-width:100%;width:100%!important;margin:0;padding:0"
                        align="center" valign="top">
                        <img src="{{ $logo }}" alt="Logo"
                            style="max-width:100%;width:auto!important;outline:none;text-decoration:none;height:auto!important;border-style:none">

                        <table
                            style="width:580px;border-collapse:separate;border-spacing:0;table-layout:auto;border-radius:8px;margin-top:24px;padding:0;border:1px solid #eee"
                            bgcolor="#fff">
                            <tbody>
                                <tr style="padding:0">
                                    <td style="border-collapse:collapse!important;word-break:break-word;padding:24px 32px 30px"
                                        align="left" valign="top">
                                        {!! $content !!}
                                    </td>
                                </tr>
                                <tr style="padding:0">
                                    <td style="border-collapse:collapse!important;word-break:break-word;padding:0 32px 24px"
                                        align="left" valign="middle">
                                        <table
                                            style="width:50%;border-collapse:collapse;border-spacing:0;table-layout:auto;padding:0;border-width:0">
                                            <tbody>
                                                <tr style="padding:0">
                                                    <td style="border-collapse:collapse!important;word-break:break-word;border-top-width:1px;border-top-color:#e4e4e9;border-top-style:solid;font-size:12px;line-height:1.5;padding:15px 0 0"
                                                        align="left" valign="middle">
                                                        <table
                                                            style="border-collapse:collapse;border-spacing:0;table-layout:auto;padding:0;border-width:0">
                                                            <tbody>
                                                                <tr style="padding:0">
                                                                    <td
                                                                        style="border-collapse:collapse!important;word-break:break-word;padding:0">
                                                                        <span>Sincerely,</span><br>
                                                                        <strong>{{ $mail_app_name }}</strong><br>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
