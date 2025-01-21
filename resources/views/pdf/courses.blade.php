<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Courses List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 20px;
        }
        .active { color: green; }
        .inactive { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Courses List</h1>
        <p>Generated on {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th>Enrolled Students</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->description }}</td>
                    <td>Rp {{ number_format($course->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="{{ $course->status ? 'active' : 'inactive' }}">
                            {{ $course->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $course->students_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>