# block-class-package

## 概要
Gutenbergのブロック登録に関連して、必要なPHPの関数をcomposerで読み込むためのファイルをパッケージにまとめました。
## インストール
コマンドプロンプト等から次のように入力してください。
```
composer require itmar/block-class-package
```
## 収納されているネームスペース・クラス
namespace Itmar\BlockClassPakage;  
class ItmarEntryClass

## メソッドの機能と引数
### block_init(string $text_domain, string $file_path)
#### 説明
$file_path内に含まれている複数のブロックを登録します。同時にPHPとJavascriptの翻訳関数をセットします。翻訳のためのpot,po,moの各ファイルはプラグインのルートフォルダ内のlanguagesフォルダになります。
また、WordPressの関数で取得する変数をフロントエンドのJavaScriptで使用できるようにローカライズします。
ローカライズされた変数はテキストドメイン名の'-'を'_'に置換した名称のオブジェクトに次のように収納されます。  
home_url・・・WordPressサイトのホームURL  
plugin_url・・・プラグインルートのURL  
#### 引数
- `$text_domain` string 必須  
プラグインで設定したテキストドメイン名
- `$file_path` string 必須  
プラグインのルートフォルダへの絶対パス。通常は__FILE__を設定する。
#### 戻り値
なし
#### 呼び出し例
```
add_action('init', function () use ($block_entry) {
	$block_entry->block_init('text-domain', __FILE__);
});
```

### activation_check(array $plugin_data, string array $plugin_slug)
#### 説明
$plugin_slugで指定された名称のプラグインがインストール又は有効化されていない場合、そのプラグイン名等（最初に発見された１つだけ）を表示して、処理を中止します。
#### 引数
- `$plugin_data` array 必須  
get_plugin_dataで取得したプラグインの情報
- `$plugin_slug` array 必須  
依存する（チェックする）プラグインの名称（string）。配列で指定してください。
#### 戻り値
なし
#### 呼び出し例
```
register_activation_hook(__FILE__, function () use ($block_entry) {
	$plugin_data = get_plugin_data(__FILE__);
	$block_entry->activation_check($plugin_data, ['block-collections']); // ここでメソッドを呼び出し
});
```

### show_admin_dependency_notices(array $plugin_data, string array $plugin_slug)
#### 説明
$plugin_slugで指定された名称のプラグインがインストール又は有効化されていない場合、そのプラグイン名等（最初に発見された１つだけ）を管理画面に表示させます。
#### 引数
- `$plugin_data` array 必須  
get_plugin_dataで取得したプラグインの情報
- `$plugin_slug` array 必須  
依存する（チェックする）プラグインの名称（string）。配列で指定してください。
#### 戻り値
なし
#### 呼び出し例
```
add_action('admin_notices', function () use ($block_entry) {
	$plugin_data = get_plugin_data(__FILE__);
	$block_entry->show_admin_dependency_notices($plugin_data, ['block-collections']);
});
```





