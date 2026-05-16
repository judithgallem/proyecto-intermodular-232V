<?php
$jsonFile = __DIR__ . '/checklist.json';
$data = json_decode(file_get_contents($jsonFile), true);

function apply_checked_ids(array &$nodes, array $checkedIds): void
{
    foreach ($nodes as &$node) {
        if (isset($node['id'])) {
            $node['checked'] = in_array($node['id'], $checkedIds, true);
        }
        if (!empty($node['children']) && is_array($node['children'])) {
            apply_checked_ids($node['children'], $checkedIds);
        }
    }
}

function count_items(array $nodes): array
{
    $total = 0;
    $done = 0;

    foreach ($nodes as $node) {
        if (isset($node['id'])) {
            $total++;
            if (!empty($node['checked'])) {
                $done++;
            }
        }
        if (!empty($node['children']) && is_array($node['children'])) {
            [$childDone, $childTotal] = count_items($node['children']);
            $done += $childDone;
            $total += $childTotal;
        }
    }

    return [$done, $total];
}

function render_meta(array $node): void
{
    $fields = [
        'command' => 'Comando',
        'capture' => 'Captura',
        'report' => 'Informe',
        'deliverable' => 'Entregable',
        'note' => 'Nota',
    ];

    $hasMeta = false;
    foreach ($fields as $field => $label) {
        if (!empty($node[$field])) {
            $hasMeta = true;
            break;
        }
    }

    if (!$hasMeta) {
        return;
    }

    echo '<dl class="meta">';
    foreach ($fields as $field => $label) {
        if (!empty($node[$field])) {
            echo '<div><dt>' . htmlspecialchars($label) . '</dt><dd>' . htmlspecialchars($node[$field]) . '</dd></div>';
        }
    }
    echo '</dl>';
}

function render_nodes(array $nodes, int $level = 1): void
{
    echo '<ul class="level level-' . $level . '">';
    foreach ($nodes as $node) {
        $hasChildren = !empty($node['children']) && is_array($node['children']);
        $type = $node['type'] ?? 'task';
        $class = 'node node-' . htmlspecialchars($type);

        echo '<li class="' . $class . '">';
        if ($hasChildren) {
            [$done, $total] = count_items($node['children']);
            echo '<details' . ($level <= 2 ? ' open' : '') . '>';
            echo '<summary>';
            echo '<span class="summary-title">' . htmlspecialchars($node['title']) . '</span>';
            echo '<span class="summary-count">' . $done . '/' . $total . '</span>';
            echo '</summary>';
            render_meta($node);
            render_nodes($node['children'], $level + 1);
            echo '</details>';
        } else {
            $id = htmlspecialchars($node['id']);
            $checked = !empty($node['checked']);
            echo '<label class="task' . ($checked ? ' task-checked' : '') . '">';
            echo '<input type="checkbox" name="checked[]" value="' . $id . '"' . ($checked ? ' checked' : '') . '>';
            echo '<span>' . htmlspecialchars($node['title']) . '</span>';
            echo '</label>';
            render_meta($node);
        }
        echo '</li>';
    }
    echo '</ul>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkedIds = $_POST['checked'] ?? [];
    if (!is_array($checkedIds)) {
        $checkedIds = [];
    }
    apply_checked_ids($data, $checkedIds);
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

[$done, $total] = count_items($data);
$percent = $total > 0 ? round(($done / $total) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist informe proyecto ASIR 232V</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <p class="eyebrow">Proyecto intermodular 2ASIR</p>
        <h1>Checklist para completar el informe final</h1>
        <p class="intro">Organizado por implementación, validación e informe, con comandos, capturas y entregables necesarios.</p>
        <div class="progress">
            <span><?php echo $done; ?> de <?php echo $total; ?> tareas</span>
            <strong><?php echo $percent; ?>%</strong>
        </div>
    </header>

    <form method="post">
        <?php render_nodes($data); ?>
        <div class="actions">
            <button type="submit">Guardar checklist</button>
        </div>
    </form>
</body>
</html>
