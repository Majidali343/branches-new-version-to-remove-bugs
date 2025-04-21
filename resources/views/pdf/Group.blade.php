<!DOCTYPE html>
<html>
<head>
    <title>Households List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reduced font size */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ensures the table fits within the page */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px; /* Reduced padding */
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
        /* Apply text wrapping to specific columns */
        .wrap {
            word-wrap: break-word;
        }
        .no-wrap {
            white-space: nowrap;
        }
        /* Define custom widths for specific columns */
        .wide {
            width: 14%;
        }
        .narrow {
            width: 7%;
        }
        .mediumsize{
            width: 10%;
        }
    </style>
</head>
<body>
    <h1>Households List</h1>
    <table>
        <thead>
            <tr>
                <th class="mediumsize wrap">Household ID</th>
                <th class="mediumsize wrap">Household Name</th>
                <th class="mediumsize wrap">Household Bio</th>
                <th class="wide wrap">Address</th>
                <th class="narrow wrap">City</th>
                <th class="narrow wrap">State</th>
                <th class="narrow no-wrap">Zip</th>
                <th class="narrow no-wrap">Country</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="mediumsize wrap">{{ $user->household_id }}</td>
                    <td class="mediumsize wrap">{{ $user->name }}</td>
                    <td class="mediumsize wrap">{{ $user->household_bio }}</td>
                    <td class="wide wrap">{{ $user->address }}</td>
                    <td class="narrow wrap">{{ $user->city }}</td>
                    <td class="narrow wrap">{{ $user->state }}</td>
                    <td class="narrow no-wrap">{{ $user->zip }}</td>
                    <td class="narrow no-wrap">{{ $user->country }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
