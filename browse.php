<?php include 'includes/includedFiles.php'; ?>
<h3 class="pageHeadingBig">Приятного прослушивания!</h3>
<div class="gridViewContainer">
    <?php
    // чтобы картинки появлялись рандомно
    $stmt = $pdo->prepare("SELECT * FROM `albums` ORDER BY RAND() LIMIT 10");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<div class='gridViewItem'>
               <span role='link' tabindex='0' onclick='openPage(\"album.php?id=".$row['id']."\")'>
                   <img src='".$row['artworkPath']."'>
                   <div class='gridViewInfo'>"
            .$row['title'].
            "</div>
               </span>
             </div>";
    }
    ?>
</div>