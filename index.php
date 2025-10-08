<?php
// index.php - Homepage
 
include 'db.php'; // Include database connection
 
// Fetch featured and breaking news
$stmt_featured = $pdo->query("SELECT a.*, c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.is_featured = TRUE ORDER BY a.published_at DESC LIMIT 3");
$featured = $stmt_featured->fetchAll(PDO::FETCH_ASSOC);
 
$stmt_breaking = $pdo->query("SELECT a.*, c.name as category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.is_breaking = TRUE ORDER BY a.published_at DESC LIMIT 3");
$breaking = $stmt_breaking->fetchAll(PDO::FETCH_ASSOC);
 
// Fetch categories
$stmt_categories = $pdo->query("SELECT * FROM categories");
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Inspired News - Homepage</title>
    <style>
        /* Internal CSS - Amazing, real-looking, responsive design */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #c00; color: white; padding: 20px; text-align: center; font-size: 2em; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .section { margin-bottom: 40px; }
        .featured, .breaking { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .article-card { background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 10px; padding: 15px; width: 300px; transition: transform 0.3s; }
        .article-card:hover { transform: scale(1.05); }
        .article-card img { width: 100%; height: auto; border-radius: 8px; }
        .article-card h3 { margin: 10px 0; }
        .article-card p { font-size: 0.9em; }
        .categories { display: flex; flex-wrap: wrap; }
        .category-link { background: #007bff; color: white; padding: 10px 20px; margin: 5px; border-radius: 5px; text-decoration: none; cursor: pointer; transition: background 0.3s; }
        .category-link:hover { background: #0056b3; }
        @media (max-width: 768px) { .article-card { width: 100%; } .featured, .breaking { flex-direction: column; } }
    </style>
</head>
<body>
    <header>CNN Inspired News</header>
    <div class="container">
        <div class="section">
            <h2>Featured News</h2>
            <div class="featured">
                <?php foreach ($featured as $article): ?>
                    <div class="article-card" onclick="window.location.href='article.php?id=<?php echo $article['id']; ?>'">
                        <img src="<?php echo $article['thumbnail']; ?>" alt="<?php echo $article['title']; ?>">
                        <h3><?php echo $article['title']; ?></h3>
                        <p><?php echo $article['short_description']; ?></p>
                        <p>Category: <?php echo $article['category_name']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="section">
            <h2>Breaking Headlines</h2>
            <div class="breaking">
                <?php foreach ($breaking as $article): ?>
                    <div class="article-card" onclick="window.location.href='article.php?id=<?php echo $article['id']; ?>'">
                        <img src="<?php echo $article['thumbnail']; ?>" alt="<?php echo $article['title']; ?>">
                        <h3><?php echo $article['title']; ?></h3>
                        <p><?php echo $article['short_description']; ?></p>
                        <p>Category: <?php echo $article['category_name']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="section">
            <h2>Categories</h2>
            <div class="categories">
                <?php foreach ($categories as $cat): ?>
                    <div class="category-link" onclick="window.location.href='category.php?slug=<?php echo $cat['slug']; ?>'"><?php echo $cat['name']; ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
