<?php
include_once 'classes/db1.php';

// Initialize search variables
$searchEvent = $_GET['event'] ?? '';
$searchLocation = $_GET['location'] ?? '';
$searchDateFrom = $_GET['date_from'] ?? '';
$searchDateTo = $_GET['date_to'] ?? '';

// Base query
$query = "SELECT * FROM staff_coordinator s, event_info ef, student_coordinator st, events e 
          WHERE e.event_id = ef.event_id AND e.event_id = s.event_id AND e.event_id = st.event_id";

if (!empty($searchEvent)) {
    $query .= " AND e.event_title LIKE '%" . mysqli_real_escape_string($conn, $searchEvent) . "%'";
}
if (!empty($searchLocation)) {
    $query .= " AND ef.location LIKE '%" . mysqli_real_escape_string($conn, $searchLocation) . "%'";
}
if (!empty($searchDateFrom)) {
    $query .= " AND ef.Date >= '" . mysqli_real_escape_string($conn, $searchDateFrom) . "'";
}
if (!empty($searchDateTo)) {
    $query .= " AND ef.Date <= '" . mysqli_real_escape_string($conn, $searchDateTo) . "'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>cems</title>
</head>
    
<body>
<?php include 'utils/adminHeader.php'?>
 
<div class="content">
    <div class="container">
        <h1>Event details</h1>
        <!-- Search Form -->
        <form method="get" action="">
            <input type="text" name="event" placeholder="Event Title" value="<?php echo htmlspecialchars($searchEvent); ?>">
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($searchLocation); ?>">
            <input type="date" name="date_from" value="<?php echo htmlspecialchars($searchDateFrom); ?>">
            <input type="date" name="date_to" value="<?php echo htmlspecialchars($searchDateTo); ?>">
            <button type="submit">Search</button>
        </form>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Event_name</th>
                        <th>No. of Participents</th>
                        <th>Price</th>
                        <th>Student Co-ordinator</th>
                        <th>Staff Co-ordinator</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['event_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['participents']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['st_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Date']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                            
                            <td><a class="delete" href="deleteEvent.php?id=<?php echo $row['event_id']; ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No events found.</p>
        <?php } ?>
        
        <a class="btn btn-default" href="createEventForm.php">Create Event</a>
    </div>
</div>

<?php require 'utils/footer.php'; ?>
</body>
</html>
