<?php
// category.php - Category page
 
include 'db.php';
 
$slug = $_GET['slug'] ?? '';
 
if (empty($slug)) {
    header('Location: index.php');
    exit;
}
 
$stmt_cat = $pdo->prepare("SELECT id, name FROM categories WHERE slug = ?");
$stmt_cat->execute([$slug]);
$category = $stmt_cat->fetch(PDO::FETCH_ASSOC);
 
if (!$category) {
    header('Location: index.php');
    exit;
}
 
$stmt_articles = $pdo->prepare("SELECT * FROM articles WHERE category_id = ? ORDER BY published_at DESC");
$stmt_articles->execute([$category['id']]);
$articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category['name']; ?> - CNN Inspired News</title>
    <style>
        /* Internal CSS - Amazing, real-looking, responsive */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #c00; color: white; padding: 20px; text-align: center; font-size: 2em; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .article-list { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .article-card { background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 10px; padding: 15px; width: 300px; transition: transform 0.3s; }
        .article-card:hover { transform: scale(1.05); }
        .article-card img { width: 100%; height: auto; border-radius: 8px; }
        .article-card h3 { margin: 10px 0; }
        .article-card p { font-size: 0.9em; }
        .back-link { background: #007bff; color: white; padding: 10px 20px; margin: 20px 0; display: inline-block; border-radius: 5px; cursor: pointer; }
        .back-link:hover { background: #0056b3; }
        @media (max-width: 768px) { .article-card { width: 100%; } .article-list { flex-direction: column; } }
    </style>
</head>
<body>
    <header>CNN Inspired News - <?php echo $category['name']; ?></header>
    <div class="container">
        <div class="back-link" onclick="window.location.href='index.php'">Back to Homepage</div>
        <h2><?php echo $category['name']; ?> News</h2>
        <div class="article-list">
            <?php foreach ($articles as $article): ?>
                <div class="article-card" onclick="window.location.href='article.php?id=<?php echo $article['id']; ?>'">
                    <img src="<?php echo $article['thumbnail']; ?>" alt="<?php echo $article['title']; ?>">
                    <h3><?php echo $article['title']; ?></h3>
                    <p><?php echo $article['short_description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
