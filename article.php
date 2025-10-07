<?php
// article.php - Article page
 
include 'db.php';
 
$id = $_GET['id'] ?? 0;
 
if ($id <= 0) {
    header('Location: index.php');
    exit;
}
 
$stmt_article = $pdo->prepare("SELECT a.*, c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.id = ?");
$stmt_article->execute([$id]);
$article = $stmt_article->fetch(PDO::FETCH_ASSOC);
 
if (!$article) {
    header('Location: index.php');
    exit;
}
 
// Update views
$pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$id]);
 
// Fetch related articles (same category, exclude self)
$stmt_related = $pdo->prepare("SELECT * FROM articles WHERE category_id = ? AND id != ? ORDER BY published_at DESC LIMIT 3");
$stmt_related->execute([$article['category_id'], $id]);
$related = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?> - CNN Inspired News</title>
    <style>
        /* Internal CSS - Amazing, real-looking, responsive */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #c00; color: white; padding: 20px; text-align: center; font-size: 2em; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .article-content { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .article-content img { width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px; }
        .article-content h1 { margin-bottom: 10px; }
        .article-content p { line-height: 1.6; }
        .related { margin-top: 40px; }
        .related h3 { margin-bottom: 20px; }
        .related-list { display: flex; flex-wrap: wrap; }
        .related-card { background: white; border: 1px solid #ddd; border-radius: 8px; margin: 10px; padding: 10px; width: 200px; cursor: pointer; transition: transform 0.3s; }
        .related-card:hover { transform: scale(1.05); }
        .related-card img { width: 100%; height: auto; }
        .back-link { background: #007bff; color: white; padding: 10px 20px; margin: 20px 0; display: inline-block; border-radius: 5px; cursor: pointer; }
        .back-link:hover { background: #0056b3; }
        @media (max-width: 768px) { .related-card { width: 100%; } .related-list { flex-direction: column; } }
    </style>
</head>
<body>
    <header>CNN Inspired News</header>
    <div class="container">
        <div class="back-link" onclick="window.location.href='index.php'">Back to Homepage</div>
        <div class="article-content">
            <h1><?php echo $article['title']; ?></h1>
            <p><em>Category: <?php echo $article['category_name']; ?> | Published: <?php echo $article['published_at']; ?> | Views: <?php echo $article['views']; ?></em></p>
            <img src="<?php echo $article['thumbnail']; ?>" alt="<?php echo $article['title']; ?>">
            <p><?php echo nl2br($article['content']); ?></p>
        </div>
        <div class="related">
            <h3>Related News</h3>
            <div class="related-list">
                <?php foreach ($related as $rel): ?>
                    <div class="related-card" onclick="window.location.href='article.php?id=<?php echo $rel['id']; ?>'">
                        <img src="<?php echo $rel['thumbnail']; ?>" alt="<?php echo $rel['title']; ?>">
                        <h4><?php echo $rel['title']; ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
