<?php

$product_cat = get_query_var('product_cat');

$list = array(
    array(
        'link'  => 'clothing',
        'name'  => '宫廷服饰',
    ),
    array(
        'link'  => 'electronics',
        'name'  => '宫廷数码',
    ),
    array(
        'link'  => 'home',
        'name'  => '宫廷家居',
    ),
    array(
        'link'  => 'study',
        'name'  => '宫廷文房',
    ),
    array(
        'link'  => 'media',
        'name'  => '宫廷音画',
    ),
    array(
        'link'  => 'toys',
        'name'  => '宫廷童趣',
    ),
    array(
        'link'  => 'zhuangmei',
        'name'  => '壮壮美美',
    ),
);

?>

                <div class="list_menu">
                    <ul>
                        <?foreach ($list as $l):?>
                        <li>
                            <a href="/category/<?=$l['link']?>" <?if($l['link']==$product_cat){echo'class="active"';} ?>><?=$l['name']?></a>
                        </li>
                        <?endforeach; ?>
                    </ul>
                </div>
