<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Immunization Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 11px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 3px 0;
        }

        .info td:first-child {
            font-weight: bold;
            width: 100px;
            color: #555;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table thead {
            background-color: #007bff;
            color: white;
        }

        table.data-table th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #0056b3;
        }

        table.data-table td {
            padding: 6px;
            border: 1px solid #dee2e6;
            font-size: 10px;
            vertical-align: top;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .vaccine-tag {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            margin: 1px;
            font-size: 9px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 9px;
            color: #999;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            font-size: 11px;
        }

        .summary strong {
            color: #2e7d32;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Immunization Report</h1>
        <div class="subtitle">Health Facility Daily Record</div>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>Facility</td>
                <td>: {{ $facility }}</td>
            </tr>
            <tr>
                <td>Report Date</td>
                <td>: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</td>
            </tr>
            @if ($search)
                <tr>
                    <td>Search Filter</td>
                    <td>: "{{ $search }}"</td>
                </tr>
            @endif
            <tr>
                <td>Generated</td>
                <td>: {{ $generatedAt }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Name Child</th>
                <th width="15%">Name Facility</th>
                <th width="30%">Name Vaccine</th>
                <th width="12%">Date Given</th>
                <th width="13%">Officer Name</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->child }}</strong></td>
                    <td>{{ $item->facility }}</td>
                    <td>
                        @foreach ($item->vaccines as $vaccine)
                            <span class="vaccine-tag">{{ $vaccine }}</span>
                        @endforeach
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                    <td>{{ $item->officer }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                        No records found for the selected criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (count($records) > 0)
        <div class="summary">
            <strong>Summary:</strong> Total {{ count($records) }} child(ren) immunized
            on {{ \Carbon\Carbon::parse($date)->format('d F Y') }} at {{ $facility }}.
        </div>
    @endif

    <div class="footer">
        This report was generated automatically from the Immunization Information System.<br>
        © {{ date('Y') }} - All rights reserved.
    </div>
</body>

</html>
