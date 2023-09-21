<?php

/*
Plugin Name: Preview Domain Resolver
Plugin URI: zawatech.com
Description: previewのurlを他のドメインに切り替えるためのプラグイン
Version: 1.0
Author: zawa1205
Author URI: https://github.com/zawa1205
*/

//=================================================
// 管理画面に追加
//=================================================
add_action('admin_menu', function(){
    add_menu_page(
		'Preview Domain Resolver'
		, 'Preview Domain'
		, 'manage_options'
		, 'preview_domain_resolver'
		, 'preview_domain_resolver_page_contents'
		, 'dashicons-admin-site-alt3'
		, 80
	);
});


//=================================================
// メインメニューページ内容の表示・更新処理
//=================================================
function preview_domain_resolver_page_contents() {
    if (!current_user_can('manage_options')) {
      wp_die( __('この設定ページのアクセス権限がありません') );
    }

    $opt_name = 'resolved_preview_domain'; //オプション名の変数
    $opt_val = get_option( $opt_name ); // 既に保存してある値があれば取得
	$opt_val_old = $opt_val;
	$message_html = "";

	//---------------------------------
	// 更新されたときの処理
	//---------------------------------
    if( isset($_POST[ $opt_name ])) {
        // POST されたデータを取得
        $opt_val = $_POST[ $opt_name ];

        update_option($opt_name, $opt_val);

		$message_html =<<<EOF
			
<div class="notice notice-success is-dismissible">
	<p>
		保存しました
		({$opt_val})
	</p>
</div>
			
EOF;
    }

	//---------------------------------
	// HTML表示
	//---------------------------------
	echo $html =<<<EOF

    {$message_html}
    <div class="wrap">
        <h2>Preview Domain Resolver</h2>
        <form name="form1" method="post" action="">
            <p>
                プレビュードメイン：<input type="text" name="{$opt_name}" value="{$opt_val}" size="32" placeholder="例: https://example.com/preview">
            </p>
            <hr />
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="保存" />
            </p>
        </form>
    </div>
    EOF;
}

add_filter( 'preview_post_link', function($url) {
	$replace_url = str_replace(get_option('home'), get_option('resolved_preview_domain'), $url);
        return $replace_url;
});