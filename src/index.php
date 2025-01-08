<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Workshop</title>
</head>

<body>
    <h2>Choose your role</h2>
    <form action="View/ViewReparation.php">
        <label for="rol">
            Rol:
            <select name="rol" id="">
                <option value="employee">Employee</option>
                <option value="client">Client</option>
            </select>
            <button type="submit">Submit</button>
        </label>
    </form>
</body>

</html>