<?php

declare(strict_types=1);

it('dump hero logo html', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $html = $response->getContent();
    preg_match_all('/<img[^>]*src="[^"]*"[^>]*>/', $html, $m);
    foreach ($m[0] as $tag) {
        fwrite(STDERR, $tag.PHP_EOL);
    }
    fwrite(STDERR, 'has tree: '.(str_contains($html, 'municipality-tree.png') ? 'YES' : 'NO').PHP_EOL);
    fwrite(STDERR, 'has old logo: '.(str_contains($html, 'storage/municipality/media/logo') ? 'YES' : 'NO').PHP_EOL);
});
