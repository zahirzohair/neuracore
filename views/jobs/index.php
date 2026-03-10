<!DOCTYPE html>
<html>

<head>
    <title>Jobs</title>
</head>

<body>
    <h1>My Jobs</h1>

    <p>
        <a href="/workflows">Workflows</a> |
        <a href="/events">Events</a>
    </p>

    <?php if (empty($jobs)): ?>
        <p>No jobs yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Attempts</th>
                    <th>Payload</th>
                    <th>Last error</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$job->id()); ?></td>
                        <td><?php echo htmlspecialchars($job->type()); ?></td>
                        <td><?php echo htmlspecialchars($job->status()); ?></td>
                        <td><?php echo htmlspecialchars($job->attempts() . '/' . $job->maxAttempts()); ?></td>
                        <td>
                            <pre style="margin:0"><?php echo htmlspecialchars(json_encode($job->payload(), JSON_PRETTY_PRINT)); ?></pre>
                        </td>
                        <td><?php echo htmlspecialchars((string)($job->lastError() ?? '')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>

