<?php
if ($total_pages > 1 )
{
    $prevPage = $page > 2 ? $page - 1 : $page; 
    $nextPage = $page < $total_pages ? $page + 1 : $page;

    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $tokens = explode("?", $actual_link);

    echo "<div>";
    echo "<ul class='navPager'>";
    echo "    <li><a href='javascript:CallPage(\"$element\",\"".$tokens[0]."?page=1\")'><< ".locale("strFirstPage")."</a></li>";
    echo "    <li>";
    echo "        <a href='javascript:CallPage(\"$element\",\"".$tokens[0]."?page=$prevPage\")'>< ".locale("strPrevPage")."</a>";
    echo "    </li>";
    echo "    <li>";
    echo "        <a href='javascript:CallPage(\"$element\",\"".$tokens[0]."?page=$nextPage\")'>".locale("strNextPage")." ></a>";
    echo "    </li>";
    echo "    <li><a href='javascript:CallPage(\"$element\",\"".$tokens[0]."?page=$total_pages\")'>".locale("strLastPage")." >></a></li>";
    echo "</ul>";
    echo "</div>";
}
?>