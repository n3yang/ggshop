<?php

$product_cat = get_query_var('product_cat');

$list = array(
    array(
        'link'  => 'presales',
        'name'  => '超前预售',
    ),
    array(
        'link'  => 'hot',
        'name'  => '热销产品',
    ),
    array(
        'link'  => 'toys',
        'name'  => '宫廷童趣',
    ),
    array(
        'link'  => 'study',
        'name'  => '宫廷文房',
    ),
    array(
        'link'  => 'home',
        'name'  => '宫廷家居',
    ),
    array(
        'link'  => 'electronics',
        'name'  => '宫廷数码',
    ),
    array(
        'link'  => 'clothing',
        'name'  => '宫廷服饰',
    ),
    array(
        'link'  => 'zhuangmei',
        'name'  => '壮壮美美',
    ),
    // array(
    // 	'link'	=> 'limited-edition',
    // 	'name'	=> '限量发售',
    // ),
    array(
        'link'  => 'old-photos',
        'name'  => '老照片展',
    ),
);

?>

                <div class="list_menu">
                    <ul>
                        <?foreach ($list as $l):?>
                        <li>
                            <a href="/category/<?=$l['link']?>" <?if($l['link']==$product_cat){echo'class="active"';}?>><?=$l['name']?></a>
                        </li>
                        <?endforeach; ?>
                    </ul>
                </div>
