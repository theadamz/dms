<?php

return [
    'general' => [
        'web_name' => 'Document Management System',
        'web_name_short' => 'DMS',
        'web_description' => '',
        'web_keywords' => 'DMS, Document Management System',
        'web_author' => 'theadamz',
        'web_email' => 'theadamz91@gmail.com',
        'company_name' => 'HAS Soft',
        'company_name_short' => 'HAS',
        'version' => '1.0.0',
        'copyright' => 'Development',
    ],
    'local' => [
        'charset' => 'UTF-8',
        'locale' => 'en_US',
        'locale_short' => 'en',
        'locale_long' => 'en-US|en_US.UTF-8',
        'timezone' => 'Asia/Jakarta',
        'country' => 'Indonesia',
        'numeric_thousand_separator' => ',',
        'numeric_decimal_separator' => '.',
        'numeric_precision_length' => 2,
        'js_datetime_format' => 'DD-MMM-YYYY HH:mm',
        'js_date_format' => 'DD-MMM-YYYY',
        'js_time_format' => 'HH:mm',
        'js_datetime_format_mask' => 'dd-mmm-yyyy HH:MM',
        'js_date_format_mask' => 'dd-mmm-yyyy',
        'js_time_format_mask' => 'HH:mm',
        'jasper_format_datetime' => 'd/MM/yyyy HH:mm',
        'jasper_format_date' => 'd/MM/yyyy',
        'jasper_format_time' => 'HH:mm',
        'jasper_format_integer' => '#,##0',
        'jasper_format_float' => '#,##0.00#;(#,##0.00#)',
        'jasper_format_number' => '#,##0;(#,##0)',
        'backend_datetime_format' => 'd-M-Y H:i',
        'backend_date_format' => 'd-M-Y',
        'backend_time_format' => 'H:i',
    ],
    'other' => [
        'max_file_attachment' => 4,
        'max_file_size' => 25600,
        'file_doc_attachment_allowed' => ['pdf', 'doc', 'docx', 'xlsx', 'ppt', 'pptx', 'rtf', 'csv', 'txt'],
        'file_img_allowed' => ['png', 'jpg', 'jpeg', 'bmp'],
        'path_to_upload' => 'contents',
        'path_to_template' => 'templates',
        'path_to_temp' => 'contents/temp',
        'cache_time' => 60 * 5, // default cache time : 5 min
    ],
    'page' => [
        'limits' => [5, 10, 25, 50, 100],
        'default_limit' => 50,
    ],
    'sequence' => [
        'year_format' => 'y',
        'month_format' => 'n',
        'array_format' => ['prefix' => '{prefix}', 'suffix' => '{suffix}', 'year' => '{year}', 'month' => '{month}', 'seq' => '{seq}'],
        'format_seq_default' => '{prefix}/{year}/{month}/{seq}',
        'length' => 6
    ],
    'method' => [
        'allowed' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']
    ],
    'regxp' => [
        'forCode' => "/^[A-Za-z0-9-+=^]*$/",
        'forUsername' => "/^[A-Za-z0-9-+=^@.]*$/",
        'forTransType' => "/^[A-Za-z_{}#+=\/-]*$/",
        'forHexColor' => "/^#([a-f0-9]{6}|[a-f0-9]{3})$/i",
    ],
    'data' => [
        'day_names' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'seconds_one_day' => 86400,
    ],
];
