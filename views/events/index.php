<!DOCTYPE html>
<html>

<head>
    <title>Events</title>
</head>

<body>
    <h1>My Events</h1>

    <p>
        <a href="/workflows">Workflows</a> |
        <a href="/jobs">Jobs</a>
    </p>

    <?php if (empty($events)): ?>
        <p>No events yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Occurred at</th>
                    <th>Payload</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$event->id()); ?></td>
                        <td><?php echo htmlspecialchars($event->name()); ?></td>
                        <td><?php echo htmlspecialchars($event->occurredAt()->format('Y-m-d H:i:s')); ?></td>
                        <td>
                            <pre style="margin:0"><?php echo htmlspecialchars(json_encode($event->payload(), JSON_PRETTY_PRINT)); ?></pre>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>

