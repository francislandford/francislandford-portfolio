<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
        }
        .frame {
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            padding: 60px;
            border: 6px solid #0f172a;
        }
        .inner {
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            padding: 40px;
            border: 1px solid #f59e0b;
            text-align: center;
        }
        .eyebrow {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #b45309;
            margin-top: 40px;
        }
        .title {
            font-size: 34px;
            font-weight: bold;
            margin: 16px 0 30px;
            color: #0f172a;
        }
        .lede {
            font-size: 14px;
            color: #475569;
        }
        .name {
            font-size: 30px;
            font-weight: bold;
            margin: 14px 0 22px;
            color: #0f172a;
        }
        .lede2 {
            font-size: 14px;
            color: #475569;
        }
        .course {
            font-size: 22px;
            font-weight: bold;
            margin: 14px 0 50px;
            color: #b45309;
        }
        table.meta {
            width: 100%;
            margin-top: 40px;
        }
        table.meta td {
            width: 33%;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        table.meta td .value {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="frame">
        <div class="inner">
            <p class="eyebrow">Certificate of Completion</p>
            <p class="title">{{ $siteName }}</p>

            <p class="lede">This certifies that</p>
            <p class="name">{{ $studentName }}</p>

            <p class="lede2">has successfully completed</p>
            <p class="course">{{ $courseTitle }}</p>

            <table class="meta">
                <tr>
                    <td>
                        <span class="value">{{ $issuedAt }}</span>
                        Date Issued
                    </td>
                    <td>
                        <span class="value">{{ $siteName }}</span>
                        Instructor
                    </td>
                    <td>
                        <span class="value">{{ $certificateNumber }}</span>
                        Certificate No.
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
