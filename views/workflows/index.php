<!DOCTYPE html>
<html>

<head>
    <title>Workflows</title>
</head>

<body>
    <h1>My Workflows</h1>

    <p>
        <a href="/events">Events</a> |
        <a href="/jobs">Jobs</a> |
        <a href="/logout">Logout</a>
    </p>

    <h2>Create workflow</h2>
    <form method="POST" action="/workflows/create">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Steps (JSON array):</label><br>
        <textarea name="steps_json" rows="8" cols="80">[
  {"type":"send_notification","payload":{"message":"Hello from NeuraCore!"}}
]</textarea><br><br>

        <button type="submit">Create</button>
    </form>

    <h2>Existing workflows</h2>

    <?php if (empty($workflows)): ?>
        <p>No workflows yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Steps</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workflows as $wf): ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$wf->id()); ?></td>
                        <td><?php echo htmlspecialchars($wf->name()); ?></td>
                        <td><?php echo htmlspecialchars($wf->status()); ?></td>
                        <td>
                            <pre style="margin:0"><?php echo htmlspecialchars(json_encode($wf->steps(), JSON_PRETTY_PRINT)); ?></pre>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>

